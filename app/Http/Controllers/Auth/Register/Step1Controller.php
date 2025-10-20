<?php

namespace App\Http\Controllers\Auth\Register;

use App\Http\Controllers\Controller;
use App\Rules\JalaliMinimumAge;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Morilog\Jalali\Jalalian;
use App\Models\UserExperience;
use App\Models\User;

class Step1Controller extends Controller
{
    public function show()
    {
        if(auth()->user()->national_id == null){
            return view('auth.register_step1');
        }else{
            $checkUserHave = UserExperience::where('user_id', auth()->user()->id)->first();
            if($checkUserHave == null){
                return redirect('register/step2')->with('success', 'شما نمیتوانید به عقب برگردید اگر نیاز به ویرایش دارید ثبت نام خود را کامل کنید و از درون برنامه ویرایش را انجام دهید');
            }else{
                return redirect('register/step3')->with('success', 'شما نمیتوانید به عقب برگردید اگر نیاز به ویرایش دارید ثبت نام خود را کامل کنید و از درون برنامه ویرایش را انجام دهید');
            }
        }
    }

    protected function convertNumbersToEnglish($string) {
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
    
        $string = str_replace($persian, $english, $string);
        return str_replace($arabic, $english, $string);
    }

    protected function persianToGregorian($persianDate) {
        // فرمت ورودی: 1402-12-28 یا 1402/12/28
        $persianDate = str_replace('/', '-', $persianDate);
        [$jy, $jm, $jd] = explode('-', $persianDate);
        
        $jy = $this->convertNumbersToEnglish($jy);
        $jm = $this->convertNumbersToEnglish($jm);
        $jd = $this->convertNumbersToEnglish($jd);

        $gy = 0; $gm = 0; $gd = 0;
    
        list($gy, $gm, $gd) = $this->j2g($jy, $jm, $jd);
    
        return sprintf('%04d-%02d-%02d', $gy, $gm, $gd);
    }
    
    protected function j2g($jy, $jm, $jd) {
        $gy = ($jy <= 979) ? 621 : 1600;
        $jy -= ($jy <= 979) ? 0 : 979;
        $days = (365 * $jy) + (int)($jy / 33) * 8 + (int)((($jy % 33) + 3) / 4);
        for ($i = 0; $i < $jm - 1; ++$i)
            $days += [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29][$i];
        $days += $jd - 1;
    
        $gy += 400 * (int)($days / 146097);
        $days %= 146097;
    
        if ($days > 36524) {
            $gy += 100 * --$days / 36524;
            $days %= 36524;
    
            if ($days >= 365)
                $days++;
        }
    
        $gy += 4 * (int)($days / 1461);
        $days %= 1461;
    
        if ($days > 365) {
            $gy += (int)(($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
    
        $gd = $days + 1;
        $sal_a = [0,31,($gy % 4 == 0 && ($gy % 100 != 0 || $gy % 400 == 0)) ? 29 : 28,31,30,31,30,31,31,30,31,30,31];
        for ($gm = 1; $gm <= 12 && $gd > $sal_a[$gm]; $gm++)
            $gd -= $sal_a[$gm];
    
        return [$gy, $gm, $gd];
    }
    
    public function normalizePhoneNumber($number) {
        // حذف تمام فاصله‌ها و کاراکترهای اضافی
        $number = preg_replace('/\s+/', '', $number);
    
        // اگر شماره با 0 شروع شد، حذفش کن
        if (substr($number, 0, 1) === '0') {
            $number = substr($number, 1);
        }
    
        return $number;
    }
    
    public function process(Request $request)
{
    // اعتبارسنجی داده‌ها
    $rules = [
        'first_name'   => 'nullable|string|max:50|regex:/^[\x{0600}-\x{06FF}\s]+$/u',
        'last_name'    => 'nullable|string|max:50|regex:/^[\x{0600}-\x{06FF}\s]+$/u',
        'birth_date'   => 'nullable|array|min:3',
        'gender'       => 'nullable|in:male,female',
        'nationality'  => 'nullable|string',
        'national_id'  => 'nullable|string|regex:/^\d{10}$/|unique:users,national_id',
        'country_code' => ['nullable', 'in:+98,+1,+44,+49'],
        'phone'        => 'nullable|regex:/^(0)?9\d{9}$/',
    ];
    
    // بررسی نیاز به رمز عبور
    $rules['password'] = auth()->user()->password
        ? 'nullable|min:6|confirmed'
        : 'required|min:6|confirmed';

    $validated = $request->validate($rules);
    if($validated['first_name'] != null AND $validated['last_name'] != null AND $validated['gender'] != null AND $validated['national_id'] != null AND $validated['phone'] != null){
       $validated['status'] = 1; 
    }else{
       $validated['status'] = 0; 
    }
    
    if(isset($validated['phone']) AND $validated['phone'] != null){
        $checkPhoneUser = User::where('phone', $validated['phone'])->first();
        if($checkPhoneUser != null){
            return back()->with('error', 'شماره تلفن قبلا ثبت شده است');
        }
    }

    // تبدیل اعداد فارسی به انگلیسی
    $nationalId = $this->convertNumbersToEnglish($validated['national_id']);
    $phone = $this->normalizePhoneNumber($this->convertNumbersToEnglish($validated['phone']));

    // اعتبارسنجی کد ملی
    if($validated['national_id'] != null){
        if (!$this->isValidIranianNationalCode($nationalId)) {
            return back()->with('error', 'کد ملی وارد شده معتبر نیست')->withInput();
        }   
    }

    // تبدیل تاریخ تولد شمسی به میلادی
    [$day, $month, $year] = array_map(
        fn($v) => $this->convertNumbersToEnglish($v),
        $validated['birth_date']
    );

    $birthDateMiladi = (new \Morilog\Jalali\Jalalian((int)$year, (int)$month, (int)$day))->toCarbon();

    // بررسی سن
    if ($birthDateMiladi->age < 15) {
        return back()->with('error', 'سن شما باید حداقل ۱۵ سال باشد');
    }

    // به‌روزرسانی اطلاعات کاربر
    $user = User::findOrFail(auth()->id());

    $userData = [
        'first_name'   => $validated['first_name'],
        'last_name'    => $validated['last_name'],
        'birth_date'   => $birthDateMiladi->toDateString(),
        'gender'       => $validated['gender'],
        'nationality'  => $validated['nationality'],
        'national_id'  => $nationalId,
        'country_code' => $validated['country_code'],
        'phone'        => $phone,
        'status'        => 1,
    ];

    if (!empty($validated['password'])) {
        $userData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
    }

    $user->update($userData);

    return redirect()->route('register.step2');
}

protected function isValidIranianNationalCode(string $code): bool
{
    if (!preg_match('/^[0-9]{10}$/', $code)) return false;

    // رد کردن کدهای تکراری مانند 1111111111
    for ($i = 0; $i < 10; $i++) {
        if (preg_match("/^{$i}{10}$/", $code)) return false;
    }

    // الگوریتم بررسی صحت
    $sum = 0;
    for ($i = 0; $i < 9; $i++) {
        $sum += ((10 - $i) * (int)$code[$i]);
    }

    $remainder = $sum % 11;
    $checkDigit = (int)$code[9];

    return ($remainder < 2 && $checkDigit === $remainder) ||
           ($remainder >= 2 && $checkDigit === (11 - $remainder));
}

}
