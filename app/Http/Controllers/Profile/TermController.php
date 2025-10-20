<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function store(Request $request){
        $setting = Setting::find(1);
        if($setting->finger_status == 0){
            $input = $request->validate([
                'finger' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5020'
            ]);
        }else{
            $input = $request->validate([
                'finger' => 'required|image|mimes:png,jpg,jpeg,webp|max:5020'
            ]);
        }
        
        if ($request->hasFile('finger')) {
            $name = time() . '.' . $request->finger->extension();
            $request->finger->move(public_path('images/fingers/'), $name);
            $inputs['finger'] = $name;
        }

        if(auth()->check()){
            if(isset($inputs['finger'])){
             auth()->user()->update([
                'fingerprint_id' =>  $inputs['finger'],
            ]);   
            }
        };

        return redirect('/')->with('success', 'تایید اساسنامه باموفقیت انجام شد، به مراحل ثبت نام بازگردید');
    }
}
