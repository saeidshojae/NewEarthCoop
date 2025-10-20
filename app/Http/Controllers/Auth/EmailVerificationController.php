<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailVerificationController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        $email = $request->email;
        $code = sprintf('%06d', random_int(0, 999999));
        
        // ذخیره کد در دیتابیس
        EmailVerification::updateOrCreate(
            ['email' => $email],
            [
                'code' => $code,
                'expires_at' => Carbon::now()->addMinutes(5)
            ]
        );
        
        // ارسال ایمیل
        $mail = Mail::send('emails.verification-code', ['code' => $code], function($message) use ($email) {
            $message->to($email)
                    ->subject('کد تأیید ایمیل');
        });
        

        return redirect()->route('email.verify.form', ['email' => $email])
                        ->with('success', 'کد تأیید به ایمیل شما ارسال شد.');
    }
    
    public function showVerificationForm(Request $request)
    {
        return view('auth.verify-email', ['email' => $request->email]);
    }
    
    public function verify(Request $request)
    {
        $email = $request->email;
        $code = implode('', $request->code);
        
        $verification = EmailVerification::where('email', $email)
                                      ->where('code', $code)
                                      ->where('expires_at', '>', Carbon::now())
                                      ->first();
        
        if (!$verification) {
            return back()->withErrors(['verification_code' => 'کد وارد شده نامعتبر است.']);
        }
        
        // فعال کردن کاربر
        $user = User::where('email', $email)->first();
        $user->email_verified_at = Carbon::now();
        $user->save();
        
        // حذف کد تأیید
        $verification->delete();
        
        return redirect()->route('register.step1')
                        ->with('success', 'سهامدار عزیز، تبریک! شما با موفقیت به EarthCoop پیوستید. دنیای همکاری های بزرگ در انتظار شماست. لطفا دقایقی وقت بگذارید و در سه مرحله اطلاعات هویتی، اطلاعات صنفی و تخصصی، و اطلاعات مکانی خود را وارد و عضویت خود را فعال نمایید.');
    }
    
 public function resend(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $email = $request->email;

    $existing = EmailVerification::where('email', $email)
        ->where('expires_at', '>', now())
        ->first();

    if ($existing) {
        $remainingSeconds = now()->diffInSeconds($existing->expires_at);
        return back()->withErrors([
            'resend' => "کد قبلی هنوز معتبر است. لطفا پس از " . ceil($remainingSeconds / 60) . " دقیقه دوباره تلاش کنید.",
        ]);
    }

    return $this->sendVerificationCode($request);
}

} 