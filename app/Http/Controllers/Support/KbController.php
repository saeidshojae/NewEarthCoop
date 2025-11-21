<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\KbArticle;
use App\Models\KbCategory;
use App\Models\KbTag;
use Illuminate\Http\Request;

class KbController extends Controller
{
    /**
     * نمایش لیست مقالات پایگاه دانش
     */
    public function index(Request $request)
    {
        $query = KbArticle::with(['category', 'author'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc');

        // فیلتر بر اساس دسته
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // فیلتر بر اساس تگ
        if ($request->filled('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('kb_tags.id', $request->input('tag'));
            });
        }

        // جستجو
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($s) use ($q) {
                $s->where('title', 'like', "%{$q}%")
                  ->orWhere('excerpt', 'like', "%{$q}%")
                  ->orWhere('content', 'like', "%{$q}%");
            });
        }

        $articles = $query->paginate(12)->withQueryString();
        $categories = KbCategory::where('is_active', true)->orderBy('name')->get();
        $tags = KbTag::where('is_active', true)->orderBy('name')->get();
        $featuredArticles = KbArticle::where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();

        return view('support.kb.index', compact('articles', 'categories', 'tags', 'featuredArticles'));
    }

    /**
     * نمایش مقاله
     */
    public function show($slug)
    {
        $article = KbArticle::with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // افزایش تعداد بازدید
        $article->increment('view_count');

        // مقالات مرتبط
        $relatedArticles = KbArticle::where('status', 'published')
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        // مقالات محبوب
        $popularArticles = KbArticle::where('status', 'published')
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();

        return view('support.kb.show', compact('article', 'relatedArticles', 'popularArticles'));
    }

    /**
     * پیشنهاد مقالات بر اساس جستجو (AJAX)
     */
    public function suggest(Request $request)
    {
        $query = $request->input('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $articles = KbArticle::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->orderBy('view_count', 'desc')
            ->take(4)
            ->get()
            ->map(function($article) {
                return [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'excerpt' => $article->excerpt,
                    'category' => $article->category?->name,
                ];
            });

        return response()->json($articles);
    }
}

