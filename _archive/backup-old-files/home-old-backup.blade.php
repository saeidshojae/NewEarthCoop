@extends('layouts.app')

@section('head-tag')

<!-- بارگذاری اسکریپت‌های Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>

    <style>
        .content-box{
            background-color: #fff;
            box-shadow: var(--shadow-box);
            padding: 20px 0;
            margin-bottom: 20px;
            border-radius: 10px;
        }
        
        .content-box h6{
            margin: 0
        }
        .img-profile-panel {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-left: .7rem
        }
        .panel-box {
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
            margin-bottom: 10px;
        }

        .navbar-nav .nav-link {
            padding-left: 0;
            padding-right: 0;
        }

        .profile-box-nav ul li {
            width: 100%;
            position: relative;
            padding: .2rem 5px;
            border-radius: 5px;
            transition: 0.3s ease-out;
            margin-bottom: 3px;
            padding-right: 1rem
        }
        .profile-box-nav ul li:hover {
            background-color: #5c9ac658;
        }
        .profile-box-nav ul {
            width: 100%;
            padding-right: 0
        }

        #groupAccordion{
            margin-right: 2rem
        }
        .main-content{
            display: flex
        }

        
        @media only screen and (max-width: 990px) {
            #groupAccordion{
            margin-right: 0
        }
        .main-content{
            display: block;
                display: flex;
    flex-direction: column-reverse;
        }
        
        .navbarprofile{
            width: 100%;
        }
        
        #groupAccordion{
            width: 100%;
            margin-bottom: 1rem;
        }
        
        #groupAccordion h2 {
            text-align: center;
        }
        
        
        }
    </style>
@endsection

@section('content')
<div class="container main-content align-items-start justify-content-between " style="direction: rtl; text-align: right;">

    @include('partials.nav-bar')
        
    @php
        /*
         * فیلتر گروه‌ها بر اساس نوع (عمومی، تخصصی، اختصاصی)
         * فرض شده که ستون group_type شامل مقادیر general, specialized, exclusive است.
         */
        $generalGroups = $groups->filter(function($group) {
            return strtolower(trim($group->group_type)) === 'general';
        });
        $specializedGroups = $groups->filter(function($group) {
            return strtolower(trim($group->group_type)) === 'specialized';
        });
        $exclusiveGroups = $groups->filter(function($group) {
            return strtolower(trim($group->group_type)) === 'exclusive';
        });
    @endphp

    <!-- Accordion برای دسته‌بندی گروه‌ها -->
    <div class="col-lg-8 col-md-12" id="groupAccordion"><br>
        <h2 class="text-center">{{ \App\Models\Setting::find(1)->home_titre }}</h2>

        <section class="manager-messages">
            <swiper-container class="mySwiper" pagination="true" loop="true" style="width: 100%; padding: 2rem; padding-bottom: 0" autoplay-delay="6000">
                @foreach(\App\Models\Slider::where('position', 1)->get() as $slider)
                <swiper-slide>
                  <img src="{{ asset('images/sliders/' . $slider->src) }}" class="w-100 rounded-3" alt="slide 1">
                </swiper-slide>
                @endforeach
              </swiper-container>
      
        </section>
        <div style='    margin: 2rem;'>{!! \App\Models\Setting::find(1)->home_content !!}</div>
    </div>

</div>

@endsection

@section('scripts')

<script>

@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showSuccessAlert(`{{ session('success') }}`);
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        showErrorAlert(`{{ session('error') }}`);
    });
@endif

</script>

@endsection