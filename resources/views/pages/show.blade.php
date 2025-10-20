@extends('layouts.app')

@section('content')
<div class="container " dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <article class="page shadow rounded-4 overflow-hidden bg-white" style="border: 1px solid #eee;">
                
                <!-- هدر آبی -->
                <header class="page-header bg-primary text-white text-center" style="padding: .8rem 0">
                    <h1 class="page-title mb-0" style="font-size: 1rem;">{{ $page->title }}</h1>
                </header>

                <!-- محتوای صفحه -->
                <div class="page-content p-4" style="font-size: 1.2rem; line-height: 2; text-align: justify;">
                    {!! $page->content !!}
                </div>

            </article>
        </div>
    </div>
</div>
@endsection
