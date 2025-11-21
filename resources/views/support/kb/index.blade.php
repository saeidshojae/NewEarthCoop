@extends('layouts.unified')

@section('title', 'پایگاه دانش پشتیبانی')

@section('content')
<div class="flex" dir="rtl">
    @include('partials.sidebar-unified')

    <div class="flex-1 min-h-screen bg-[var(--color-soft-warm)] dark:bg-slate-900 p-6">
        <div class="max-w-5xl mx-auto space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-400">پشتیبانی سریع‌تر</p>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">پایگاه دانش</h1>
                    </div>
                    <a href="{{ route('user.tickets.create') }}"
                       class="inline-flex items-center px-5 py-2 rounded-xl bg-[var(--color-teal)] text-white text-sm font-semibold shadow hover:bg-emerald-500 transition">
                        <i class="fas fa-headset ml-2"></i>
                        ایجاد تیکت جدید
                    </a>
                </div>
                <form method="GET" class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <label class="text-xs text-slate-500">جستجو در مقالات</label>
                            <input type="search" name="q" value="{{ request('q') }}" placeholder="کلیدواژه، خطا، موضوع..."
                                   class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[var(--color-teal)]">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500">دسته‌بندی</label>
                            <select name="category" class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                                <option value="">همه</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3 mt-4">
                        <button class="inline-flex items-center px-4 py-2 rounded-lg bg-[var(--color-teal)] text-white text-sm font-semibold">
                            <i class="fas fa-search ml-2"></i>
                            جستجو
                        </button>
                        <a href="{{ route('support.kb.index') }}" class="text-sm text-slate-500 hover:text-slate-900 dark:hover:text-white">پاک کردن فیلترها</a>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($categories as $category)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-[var(--color-soft-sky)] text-[var(--color-soft-navy)] flex items-center justify-center text-xl">
                                <i class="{{ $category->icon ?? 'fas fa-book' }}"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $category->name }}</h3>
                                <p class="text-sm text-slate-500">{{ $category->description }}</p>
                            </div>
                        </div>
                        @if($category->children->count())
                            <div class="mt-4">
                                <div class="text-xs text-slate-400 mb-2">زیرمجموعه‌ها:</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($category->children as $child)
                                        <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-xs text-slate-600 dark:text-slate-300">{{ $child->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">لیست مقالات</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <a href="{{ route('support.kb.index', array_merge(request()->query(), ['tag' => $tag->id])) }}"
                               class="px-3 py-1 rounded-full text-xs font-semibold"
                               style="background-color: {{ $tag->color ?? 'rgba(14,165,233,0.1)' }}; color: #0f172a;">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($articles as $article)
                        <a href="{{ route('support.kb.show', $article->slug) }}"
                           class="flex flex-col gap-2 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/40 rounded-xl px-2 transition">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-xs text-slate-600 dark:text-slate-300">{{ $article->category?->name }}</span>
                                @if($article->is_featured)
                                    <span class="px-2 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">ویژه</span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $article->title }}</h3>
                            <p class="text-sm text-slate-500 line-clamp-2">{{ $article->excerpt }}</p>
                            <div class="text-xs text-slate-400 flex items-center gap-3">
                                <span><i class="far fa-clock ml-1"></i>{{ optional($article->published_at)->format('Y/m/d') }}</span>
                                <span><i class="far fa-eye ml-1"></i>{{ number_format($article->view_count) }} بازدید</span>
                            </div>
                        </a>
                    @empty
                        <div class="py-6 text-center text-slate-500">مقاله‌ای یافت نشد.</div>
                    @endforelse
                </div>
                <div class="mt-4">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




