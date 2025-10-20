<?php

// HomeController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\UserExperience;

class HomeController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
       
        $checkUserHave = UserExperience::where('user_id', auth()->user()->id)->first();

        
                if($checkUserHave == null){
            return redirect('register/step2')->with('success', 'شما نمیتوانید وارد برنامه شوید، لطفا مراحل ثبت نام را کامل کنید و اگر نیاز به ویرایش دارید پس از ثبت نام از درون برنامه اقدام کنید');
        }
        
                if(Address::where('user_id', auth()->user()->id)->first() == null){
            return redirect('register/step3')->with('success', 'شما نمیتوانید وارد برنامه شوید، لطفا مراحل ثبت نام را کامل کنید و اگر نیاز به ویرایش دارید پس از ثبت نام از درون برنامه اقدام کنید');
        }
        
        
        
        // دریافت گروه‌ها از کاربر احراز هویت شده
        $groups = auth()->user()->groups;
        
        // دریافت حراج‌های فعال
        $activeAuctions = \App\Modules\Stock\Models\Auction::where('status', 'running')
            ->where('ends_at', '>', now())
            ->with('stock')
            ->orderBy('ends_at', 'asc')
            ->limit(3)
            ->get();
    
        // ارسال متغیرها به ویو
        return view('home', compact('groups', 'activeAuctions'));
    }
}

