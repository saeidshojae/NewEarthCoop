<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\InvitationCode;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvitationCodeController extends Controller
{
    public function index()
    {
        if(isset($_GET['invation'])){
                                $codes = Invitation::all();

        }else{
             if(isset($_GET['filter'])){
                if($_GET['filter'] == 1){
                    $codes = InvitationCode::all();
                }elseif($_GET['filter'] == 2){
                    $codes = InvitationCode::where('user_id', 171)->get();
                }elseif($_GET['filter'] == 3){
                    $codes = InvitationCode::where('user_id', '!=', 171)->get();
                }
            }else{
                $codes = InvitationCode::all();
            }   
        }
        return view('admin.invitation_codes.index', compact('codes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:invitation_codes,code'
        ]);

        InvitationCode::create(['code' => $request->code, 'user_id' => 171, 'expire_at' => Carbon::now()->addHours(\App\Models\Setting::find(1)->expire_invation_time)]);

        return redirect()->route('admin.invitation_codes.index')->with('success', 'کد دعوت با موفقیت ایجاد شد.');
    }



}