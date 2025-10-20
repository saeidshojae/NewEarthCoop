<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Str;

class InvitationController extends Controller
{
    public function get()
    {
        return view('invitation.index');
    }

    public function store(Request $request)
    {
        $inputs = $request->validate([
            'email' => 'required|email|unique:invitations,email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'message' => 'nullable|string|max:500',
            'job' => 'nullable|string|max:500',
        ]);

        $inputs['code'] = Str::random(5);

        Invitation::create($inputs);

        return redirect('/')->with('success', 'ازینکه برای پیوستن به زیست بوم همکاری های جهانی Earth Coop مشتاقید بسیار خوشحالیم، درخواست شما دریافت شد و بزودی برسی میشود');
    }
}
