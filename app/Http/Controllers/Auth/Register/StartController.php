<?php
namespace App\Http\Controllers\Auth\Register;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\InvitationCode;
use App\Http\Controllers\Auth\EmailVerificationController;

class StartController extends Controller
{
    public function showWelcome()
    {
        if(auth()->check()){
            return redirect()->route('home');
            exit;
        }

        return view('welcome');
    }

    public function processAgreement(Request $request)
    {
        $code = '';
        $setting = Setting::find(1);
        if($setting->invation_status == 0){
            $request->validate([
                'invite_code' => 'nullable|string'
            ]);    
        }else{
            $inputs = $request->validate([
                'invite_code' => 'required|string|exists:invitation_codes,code'
            ]);
            
            $invartion = InvitationCode::where('code', $inputs['invite_code'])->where('used', 0)->where('expire_at', '>=', now())->first();
            if($invartion == null){
                return back()->with('error', 'کد وارد شده نامعتبر است');
            }else{
                $code = $invartion->code;
            }
        }

        session(['fingerprint_id' => $request->fingerprint_id]);
        return redirect()->route('register.form', ['code' => $code]);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function processRegister(Request $request)
    {
        $inputs = $request->validate([
            'email'    => 'required|email|unique:users,email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'phone'    => 'nullable|unique:users,phone',
            'password' => 'required|min:6|confirmed',
            'invation_code' => 'nullable|string',
        ]);

        $user = User::create([
            'email'          => $request->email,
            'phone'          => $request->phone,
            'password'       => Hash::make($request->password),
            'fingerprint_id' => session('fingerprint_id'),
        ]);
        
        if(isset($inputs['invation_code'])){
            $invartion = InvitationCode::where('code', $inputs['invation_code'])->where('used', 0)->where('expire_at', '>=', now())->first();
            if($invartion != null){
                $invartion->update([
                    'used' => 1 ,
                    'used_by' => $user->id,
                ]);
            }
        }

        // ارسال کد تأیید ایمیل
        $emailVerification = new EmailVerificationController();
        $emailVerification->sendVerificationCode($request);

        auth()->login($user);
        return redirect()->route('email.verify.form', ['email' => $request->email]);
    }
}
