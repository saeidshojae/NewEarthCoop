@extends('layouts.app')

@section('head-tag')
<style>
    .verification-container {
        direction: rtl;
        text-align: right;
        margin-top: 2rem;
    }

    .verification-code {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        margin: 2rem 0;
    }

    .verification-code input {
        width: 3rem;
        height: 3rem;
        text-align: center;
        font-size: 1.5rem;
        border: 2px solid #dee2e6;
        border-radius: 0.5rem;
    }

    .verification-code input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: none;
    }

    .resend-code {
        text-align: center;
        margin-top: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container verification-container" style='margin-top: 0'>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">تأیید ایمیل</div>

                <div class="card-body">
                    
        @if (session('success'))
        <div class="alert alert-info">
            {{ session('success') }}
        </div>
    @endif
    
                    <p class="text-center">کد تأیید به ایمیل شما ارسال شده است. لطفاً کد را وارد کنید:</p>
                    
                    <form action="{{ route('email.verify') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        
                        <div class="verification-code" style='flex-direction: row-reverse;'>
                            <input type="text" name="code[]" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" name="code[]" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" name="code[]" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" name="code[]" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" name="code[]" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" name="code[]" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                        </div>

                        @error('verification_code')
                            <div class="alert alert-danger text-center">{{ $message }}</div>
                        @enderror

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">تأیید</button>
                        </div>
                    </form>

                    <div class="resend-code">
    <form id="resend-form" action="{{ route('email.verification.resend') }}" method="POST">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <button type="submit" id="resend-button"
            class=""
            style="border: 1px solid #37c4b4; color: #37c4b4; text-decoration: none; border-radius: .3rem;">
            ارسال مجدد کد
        </button>
    </form>
    <div id="timer" style="margin-top: 0.5rem; color: #6c757d;"></div>
</div>


                </div>
            </div>
        </div>
    </div>
</div>
@php
if(isset($_GET['email'])){

$verification = \App\Models\EmailVerification::where('email', $_GET['email'])->first();
$remainingSeconds = $verification && $verification->expires_at > now()
    ? now()->diffInSeconds($verification->expires_at)
    : 0;
    
    }else{
    $remainingSeconds = 0;
    }
@endphp
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.verification-code input');

    inputs.forEach((input, index) => {
        input.addEventListener('keyup', function(e) {
            if (e.key >= 0 && e.key <= 9) {
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            } else if (e.key === 'Backspace') {
                if (index > 0) {
                    inputs[index - 1].focus();
                }
            }
        });
    });

    // ✨ تایمر برای غیرفعال کردن ارسال مجدد
    let remaining = {{ $remainingSeconds ?? 0 }};
    const resendButton = document.getElementById('resend-button');
    const timerDisplay = document.getElementById('timer');

    function updateTimer() {
        if (remaining > 0) {
            resendButton.disabled = true;
            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            timerDisplay.textContent = `امکان ارسال مجدد تا ${minutes}:${seconds.toString().padStart(2, '0')} دیگر فعال می‌شود.`;
            remaining--;
        } else {
            resendButton.disabled = false;
            timerDisplay.textContent = '';
            clearInterval(interval);
        }
    }

    if (remaining > 0) {
        updateTimer();
        const interval = setInterval(updateTimer, 1000);
    }
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.verification-code input');
    
    inputs.forEach((input, index) => {
        input.addEventListener('keyup', function(e) {
            if (e.key >= 0 && e.key <= 9) {
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            } else if (e.key === 'Backspace') {
                if (index > 0) {
                    inputs[index - 1].focus();
                }
            }
        });
    });
});
</script>
@endsection 