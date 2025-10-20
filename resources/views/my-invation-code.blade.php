@extends('layouts.app')

@section('title', 'نظرسنجی های جاری')

@section('head-tag')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <style>
    .toggle-box {
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
      transition: all 0.3s ease;
      width: 100%;
      background-color: #fff
    }

    .toggle-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      font-weight: bold;
      font-size: 1.1rem;
    }

    .toggle-content {
      margin-top: 1rem;
      display: none;
      font-size: 0.95rem;
      line-height: 1.6;
    }

    .toggle-icon {
      font-size: 1.3rem;
      transition: transform 0.3s;
    }

    .open .toggle-icon {
      transform: rotate(180deg);
    }

    .open .toggle-content {
      display: block;
    }
    input {
        background-color: #fff !important
    }
    select {
        background-color: #fff !important
    }

    textarea {
        background-color: #fff !important
    }

    .remove-selection {
    padding: 0 .4rem;
    margin: .2rem .3rem .2rem .1rem;
  }
  .badge {
    background-color: #57a1d7bf !important;
  }
  .error-message {
    color: red;
    font-size: 0.9rem;
    display: none;
  }

  .select2-container{
    margin-bottom: 1rem !important;
  }

    </style>
@endsection

@section('content')
<div class="container" style="direction: rtl; text-align: right;">
    <h1 class="mb-4 text-center">کد های دعوت شما</h1>
    <div class="invite-quota" style='display: flex; flex-direction: column;
    align-items: center;'>
    <h2>سهمیه دعوت‌نامه‌ی شما به ارثکوپ</h2>
    
    <p>عضویت در ارثکوپ فقط با دعوت یک عضو احراز هویت‌شده ممکن است.<br>
    شما نیز با دعوت یکی از اعضا، به جمع اولین ساکنان ارثکوپ پیوسته‌اید.</p>
    
    <p><strong>اکنون می‌توانید حداکثر ۱۰ نفر</strong> از کسانی را که هویت واقعی و ایرانی‌شان را تأیید می‌کنید، دعوت کنید.</p>
    
    <div class="invite-note">
        <p>🔐 <strong>کدهای دعوت ارثکوپ</strong>، زمام‌دارند و تنها تا <strong>۷۲ ساعت</strong> اعتبار دارند.<br>
        پیش از ایجاد کد، با فرد موردنظر گفت‌وگو کنید و در صورت تمایل، دعوت‌نامه را برای او بفرستید.</p>
    </div>
    
    <div class="invite-reward">
        <p>💰 <strong>پاداش دعوت موفق:</strong><br>
        با پیوستن هر دعوت‌شده و تأیید موافقت‌نامه «نجم بهار»،<br>
        <strong>۱۰ بهار</strong> معادل <strong>۱ گرم طلای ۲۴ عیار</strong> به حساب شما واریز می‌شود.<br>
        با ۱۰ دعوت موفق، تا <strong>۱۰۰ بهار</strong> (۱۰ گرم طلا) دریافت خواهید کرد.</p>
    </div>
    
    <p>نگران نباشید؛ اگر کدی منقضی شود یا استفاده نشود، سهمیه دعوت شما محفوظ می‌ماند و می‌توانید کد جدید بسازید.</p>
    
    <p><strong>با هم ارثکوپ را می‌سازیم.</strong></p>
</div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover text-center align-middle">
        <thead class="table-dark">
             @php
                        $codes = \App\Models\InvitationCode::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
                        $checkExpire = \App\Models\InvitationCode::where('used', 0)->where('expire_at', '<=', now())->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
                        foreach($checkExpire as $check){
                            $check->delete();
                        }
                    @endphp
             <tr>
                        <td>کد</td>
                        <td>وضعیت</td>
                        <td>تاریخ ایجاد</td>
                        <td>تاریخ انقضا</td>
                        <td>اشتراک گذاری</td>
                    </tr>
                        @forelse($codes as $code)
                            <tr>
                                <th>{{ $code->code }}</th>
                                <th>{{ $code->used == 0 ? 'استفاده نشده' : $code->usedBy->fullName() }}</th>
                                <th>{{ verta($code->created_at)->format('Y-m-d') }}</th>
                                <th>{{ verta($code->expire_at)->format('Y-m-d') }}</th>
                                        <th>
            <!-- دکمه اشتراک‌گذاری -->
            <button class='btn btn-warning' @if($code->used == 1) style='' disabled @else onclick="shareToSocialMedia('{{ $code->code }}')" @endif><i style='margin: 0' class="fa fa-share-alt"></i>
</button>
        </th>
                            </tr>
                        @endforeach
                        
                    </table>
                    <div class="text-center mt-4">
                        <a @if($codes->count() >= 10) style='opacity: .5' @else href="{{ route('profile.generate-code') }}" @endif class="btn btn-primary">درخواست کد جدید</a>
                        <a class="">{{ $codes->count() . '/10' }}</a>
                    </div>
</div>

</div>
<script>
function shareToSocialMedia(code) {
var url = "https://earthcoop.info?code=" + code; // لینک سایت شما
var message = `
سلام ! در EarthCoop منتظر شما هستم. با زدن روی لینک زیر و وارد کردن کد دعوت در زیست‌بوم همکاری‌های جهانی به ما بپیوندید.
کد دعوت: ${code}
لینک: 
`
    if (navigator.share) {
        // استفاده از Web Share API برای دستگاه‌های موبایل یا مرورگرهایی که این API را پشتیبانی می‌کنند
        navigator.share({
            title: 'دعوت از دوستان',
            text: message,
            url: url,
        }).then(() => {
            console.log('اشتراک‌گذاری موفق');
        }).catch((error) => {
            console.error('خطا در اشتراک‌گذاری:', error);
        });
    } else {
        navigator.clipboard.writeText(url)
  .then(() => {
    alert("لینک با موفقیت کپی شد!");
  })
  .catch(err => {
    alert("خطا در کپی کردن لینک:", err);
  });
  
    }
}
</script>
@endsection

@section('scripts')
{{-- اسکریپت جی کوئری و Bootstrap --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

{{-- اگر از Bootstrap5 استفاده می‌کنید: --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- بارگذاری jQuery -->
<!-- بارگذاری Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>



<script>
    function toggleBox(header) {
      const content = header.nextElementSibling;
      const icon = header.querySelector('.toggle-icon');
      content.style.display = content.style.display === 'none' ? 'block' : 'none';
      icon.classList.toggle('fa-chevron-down');
      icon.classList.toggle('fa-chevron-up');
    }
  
    // همه رو پیش‌فرض ببنده
    document.querySelectorAll('.toggle-content').forEach(el => el.style.display = 'none');
  </script>
  

<script>
    function updatePlaceholder() {
        const select = document.getElementById('country_code');
        const selected = select.options[select.selectedIndex];
        const placeholder = selected.getAttribute('data-placeholder');
    
        document.getElementById('phone').placeholder = 'برای مثال: ' + placeholder;
    }
    </script>

@endsection
