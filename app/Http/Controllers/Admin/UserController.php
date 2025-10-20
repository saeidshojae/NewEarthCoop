<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }
    
    public function store(Request $request){
        $inputs = $request->validate([
                        'email'    => 'required|email|unique:users,email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',

            'first_name'   => 'required|string|max:50|regex:/^[\x{0600}-\x{06FF}\s]+$/u',
            'last_name'    => 'required|string|max:50|regex:/^[\x{0600}-\x{06FF}\s]+$/u',
            'birth_date'   => 'required|array|min:3',
            'gender'       => 'required|in:male,female',
            'national_id'  => 'required|string|regex:/^\d{10}$/|unique:users,national_id',
            'phone'        => 'required|regex:/^(0)?9\d{9}$/|unique:users,phone',
            'password' => 'required|min:6|confirmed',
        ]);
        
        $nationalId = $this->convertNumbersToEnglish($inputs['national_id']);
        $phone = $this->normalizePhoneNumber($this->convertNumbersToEnglish($inputs['phone']));

        // اعتبارسنجی کد ملی
        if (!$this->isValidIranianNationalCode($nationalId)) {
            return back()->with('error', 'کد ملی وارد شده معتبر نیست')->withInput();
        }

    [$day, $month, $year] = array_map(
        fn($v) => $this->convertNumbersToEnglish($v),
        $inputs['birth_date']
    );


    $birthDateMiladi = (new \Morilog\Jalali\Jalalian((int)$year, (int)$month, (int)$day))->toCarbon();

    // بررسی سن
    if ($birthDateMiladi->age < 15) {
        return back()->with('error', 'سن شما باید حداقل ۱۵ سال باشد');
    }
    
    $inputs['birth_date'] = $birthDateMiladi->toDateString();


    $inputs['password'] = Hash::make($inputs['password']);
        User::create($inputs);
        return redirect()->route('admin.users.index')->with('success', 'کاربر با موفقیت ایجاد شد');
    }
    
    public function update(Request $request, User $user){
        $inputs = $request->validate([
                        'email'    => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:users,email,' . $user->id,

            'first_name'   => 'required|string|max:50|regex:/^[\x{0600}-\x{06FF}\s]+$/u',
            'last_name'    => 'required|string|max:50|regex:/^[\x{0600}-\x{06FF}\s]+$/u',
            'birth_date'   => 'required|array|min:3',
            'gender'       => 'required|in:male,female',
            'national_id'  => 'required|string|regex:/^\d{10}$/|unique:users,national_id,' . $user->id,
            'phone'        => 'required|regex:/^(0)?9\d{9}$/|unique:users,phone,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'blocks' => 'nullable|array',
        ]);
        
        foreach(\App\Models\Block::where('user_id', $user->id)->get() as $block){
            $block->delete();
        }
        
        if(isset($inputs['blocks'])){
            foreach($inputs['blocks'] as $block){
                \App\Models\Block::create(['user_id' => $user->id, 'position' => $block]);
            }
        }
        
        $nationalId = $this->convertNumbersToEnglish($inputs['national_id']);
        $phone = $this->normalizePhoneNumber($this->convertNumbersToEnglish($inputs['phone']));

        // اعتبارسنجی کد ملی
        if (!$this->isValidIranianNationalCode($nationalId)) {
            return back()->with('error', 'کد ملی وارد شده معتبر نیست')->withInput();
        }

    [$day, $month, $year] = array_map(
        fn($v) => $this->convertNumbersToEnglish($v),
        $inputs['birth_date']
    );


    $birthDateMiladi = (new \Morilog\Jalali\Jalalian((int)$year, (int)$month, (int)$day))->toCarbon();

    // بررسی سن
    if ($birthDateMiladi->age < 15) {
        return back()->with('error', 'سن شما باید حداقل ۱۵ سال باشد');
    }
    
    $inputs['birth_date'] = $birthDateMiladi->toDateString();

    
    if($inputs['password'] != null){
         $inputs['password'] = Hash::make($inputs['password']);
    }else{
        unset($inputs['password']);
    }
    $user->update($inputs);
            return redirect()->route('admin.users.index')->with('success', 'کاربر با موفقیت ایجاد شد');

   
    }
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }
    
    
        public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'کاربر با موفقیت حذف شد');
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

    protected function convertNumbersToEnglish($string) {
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
    
        $string = str_replace($persian, $english, $string);
        return str_replace($arabic, $english, $string);
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
    
}
