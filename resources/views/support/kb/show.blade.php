@extends('layouts.unified')

@section('title', $article->title)

@section('content')
<div class="flex" dir="rtl">
    @include('partials.sidebar-unified')

    <div class="flex-1 min-h-screen bg-[var(--color-soft-warm)] dark:bg-slate-900 p-6">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center gap-3 text-sm text-slate-500">
                <a href="{{ route('support.kb.index') }}" class="text-[var(--color-teal)] hover:underline">پایگاه دانش</a>
                <i class="fas fa-chevron-left text-xs"></i>
                <span>{{ $article->category?->name }}</span>
            </div>

            <article class="bg-white dark:bg-slate-800 rounded-3xl shadow p-8 space-y-6">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-xs text-slate-600 dark:text-slate-300">{{ $article->category?->name }}</span>
                    @if($article->is_featured)
                        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">ویژه</span>
                    @endif
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $article->title }}</h1>
                <div class="flex flex-wrap gap-4 text-xs text-slate-400">
                    <span><i class="far fa-clock ml-1"></i>انتشار: {{ optional($article->published_at)->format('Y/m/d H:i') }}</span>
                    <span><i class="far fa-eye ml-1"></i>{{ number_format($article->view_count) }} بازدید</span>
                </div>
                <p class="text-lg text-slate-600 dark:text-slate-300 leading-8">{{ $article->excerpt }}</p>
                <div class="prose prose-slate max-w-none prose-headings:text-slate-900 prose-strong:text-slate-900 prose-a:text-[var(--color-teal)] dark:prose-invert">
                    {!! nl2br(e($article->content)) !!}
                </div>
                @if($article->tags->count())
                    <div class="flex flex-wrap gap-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                        @foreach($article->tags as $tag)
                            <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: {{ $tag->color ?? 'rgba(14,165,233,0.1)' }};">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </article>

            @if($relatedArticles->count())
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow p-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">مقالات مرتبط</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($relatedArticles as $related)
                            <a href="{{ route('support.kb.show', $related->slug) }}" class="border border-slate-100 dark:border-slate-700 rounded-2xl p-4 hover:border-[var(--color-teal)] hover:shadow transition">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $related->title }}</h3>
                                <p class="text-sm text-slate-500 mt-2 line-clamp-2">{{ $related->excerpt }}</p>
                                <div class="text-xs text-slate-400 mt-3">
                                    <i class="far fa-clock ml-1"></i>{{ optional($related->published_at)->format('Y/m/d') }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">هنوز نیاز به کمک دارید؟</h3>
                        <p class="text-sm text-slate-500">اگر پاسخ خود را پیدا نکردید، تیکت جدید ثبت کنید.</p>
                    </div>
                    <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center px-5 py-3 rounded-xl bg-[var(--color-teal)] text-white font-semibold">
                        ارسال تیکت پشتیبانی
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




