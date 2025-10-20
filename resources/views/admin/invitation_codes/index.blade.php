@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <h2 class="text-center fw-bold mb-4">مدیریت کدهای دعوت</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    
    <form action="{{ route('admin.activate.update') }}" method="POST" class="row justify-content-center">
        @csrf
        @method('PUT')

        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <label for="invation_status" class="form-label mb-0 fw-semibold">فعالسازی کد دعوت</label>
                    <input type="checkbox" name="invation_status" id="invation_status"
                        {{ old('invation_status', \App\Models\Setting::find(1)->invation_status) == 1 ? 'checked' : '' }}>
                </div>
                
              
            </div>
            <div class='form-group'>
                    <label>زمان انقضای کد (ساعت)</label>
                     <input type="number" name="expire_invation_time" class='form-control' id="expire_invation_time" value='{{ old('expire_invation_time', \App\Models\Setting::find(1)->expire_invation_time) }}'>
                </div><br>
                            <div class='form-group'>
                    <label>تعداد مجاز برای ایجاد کد دعوت</label>
                     <input type="number" name="count_invation" class='form-control' id="count_invation" value='{{ old('count_invation', \App\Models\Setting::find(1)->count_invation) }}'>
                </div><br>
            <div class="text-center">
                <button type="submit" class="btn btn-success px-4">ذخیره تغییرات</button>
            </div>
        </div>
    </form>
    <hr>
    <form action="{{ route('admin.invitation_codes.store') }}" method="POST" class="mb-5">
        @csrf
        <div class="mb-3">
            <label for="code" class="form-label">کد دعوت:</label>
            <input type="text" name="code" id="code" class="form-control text-end" required>
        </div>
        <button type="submit" class="btn btn-success">ایجاد کد دعوت</button>
    </form>
    
     @if(isset($_GET['invation']))
         <h4 class="mb-3 fw-semibold text-center">لیست درخواست های کد دعوت</h4>

     @else
    
    <h4 class="mb-3 fw-semibold text-center">لیست کدهای دعوت</h4>
    <div>
        <label>فیلتر: </label>
        <a href='{{ route('admin.invitation_codes.index', ['filter' => 1]) }}' class='btn' style='    width: auto;
    background-color: #f4ce00;'>کل کد ها</a>
        <a href='{{ route('admin.invitation_codes.index', ['filter' => 2]) }}' class='btn' style='    width: auto;
    background-color: #f4ce00;'>کد های مختص به سیستم</a>
        <a href='{{ route('admin.invitation_codes.index', ['filter' => 3]) }}' class='btn' style='    width: auto;
    background-color: #f4ce00;'>کد های مختص به کاربران</a>
    </div>
    @endif
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            @if(isset($_GET['invation']))
            <thead class="table-dark">
                <tr>
                    <th>ایمیل</th>
                    <th>پیام</th>
                    <th>شغل</th>
                    <th>کد</th>
                    <th>وضعیت</th>
                </tr>
            </thead>
            <tbody>
                @foreach($codes as $code)
                    <tr>
                        <td>{{ $code->email }}</td>
                        <td>{{ $code->message }}</td>
                        <td>{{ $code->job }}</td>
                        <td>{{ $code->code }}</td>
                        <td>{{ $code->status == 0 ? 'استفاده شده' : 'استفاده نشده' }}</td>
                    </tr>
                @endforeach
            </tbody>            
            @else
            <thead class="table-dark">
                    @php
                        $checkExpire = \App\Models\InvitationCode::where('used', 0)->where('expire_at', '<=', now())->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
                        foreach($checkExpire as $check){
                            $check->delete();
                        }
                    @endphp
                <tr>
                    <th>صادر کننده</th>
                    <th>استفاده کننده</th>
                    <th>کد</th>
                    <th>وضعیت</th>
                    <th>اشتراک گذاری</th>
                </tr>
            </thead>
            <tbody>
                @foreach($codes as $code)
                    <tr>
                            <td>{{ $code->user ? $code->user->fullName() : '-' }}</td>
                            <td>{{ $code->used_by != null && $code->usedBy ? $code->usedBy->fullName() : '-' }}</td>
                        <td>{{ $code->code }}</td>
                        <td>{{ $code->used == 1 ? 'استفاده شده' : 'استفاده نشده' }}</td>
                        <td>
                            @if($code->user_id == 171)
                                <button class='btn btn-warning' @if($code->used == 1) style='' disabled @else onclick="shareToSocialMedia('{{ $code->code }}')" @endif><i style='margin: 0' class="fa fa-share-alt"></i></button>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            @endif
        </table>
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
