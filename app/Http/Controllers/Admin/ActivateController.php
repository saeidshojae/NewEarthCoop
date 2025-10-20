<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ActivateController extends Controller
{
    public function index(){
        return view('admin.activate.index');
    }

    public function update(Request $request){
        $setting = Setting::find(1);
        $inputs['invation_status'] = $request->input('invation_status') == 'on' ? 1 : 0;
        $inputs['finger_status'] = $request->input('finger_status') == 'on' ? 1 : 0;
        $inputs['expire_invation_time'] = $request->input('expire_invation_time');
        $inputs['count_invation'] = $request->input('count_invation');

        $setting->update($inputs);
        return back();
    }
}
