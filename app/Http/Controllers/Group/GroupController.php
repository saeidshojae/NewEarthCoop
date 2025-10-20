<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\GroupUse;

class GroupController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('groups.index', [
            'generalGroups' => $user->groups()
                ->where('group_type', 0)    // فیلتر بر اساس نوع گروه
                ->get()->reverse(),
            'specialityGroups' => $user->groups()
                ->whereNotNull('specialty_id')
                ->whereNull('experience_id')
                ->get()->reverse(),
            'experienceGroups' => $user->groups()
                ->whereNull('specialty_id')
                ->whereNotNull('experience_id')
                ->get()->reverse(),
            'ageGroups' => $user->groups()
                ->where('group_type', 3)
                ->get()->reverse(),
            'genderGroups' => $user->groups()
                ->where('group_type', 4)
                ->get()->reverse(),
            'managedGroups' => $user->groups()
                ->where('location_level', 10)  // Filter groups where user is a manager
                ->get(),
        ]);
        
    }

    public function show(Group $group)
    {
        return view('groups.show', [
            'group' => $group,
            'messages' => $group->messages()->latest()->get(),
            'generalGroups' => Group::where('group_type', 'general')->get(),
            'specializedGroups' => Group::where('group_type', 'specialized')->get(),
            'exclusiveGroups' => Group::where('group_type', 'exclusive')->get(),
        ]);
    }

    public function logout(Group $group){
        $groupUserModel = GroupUser::where('group_id', $group->id)->where('user_id', auth()->user()->id)->first();
        $groupUserModel->update(['status' => 0]);

        return redirect()->route('groups.index')->with('success', 'شما با موفقیت از گروه خارج شدید');
    }

    public function relogout(Group $group){
        $groupUserModel = GroupUser::where('group_id', $group->id)->where('user_id', auth()->user()->id)->first();
        $groupUserModel->update(['status' => 1]);

        return redirect()->route('groups.index')->with('success', 'شما با موفقیت وارد گروه خارج شدید');
    }

    public function open(Group $group){
        if($group->is_open == 1){
            $group->is_open = 0;
            $msg = 'گروه با موفقیت به حالت بسته گردید';
        }else{
            $group->is_open = 1;
            $msg = 'گروه با موفقیت به حالت باز گردید';
        }
        $group->save();
        return back()->with('success', $msg);
    }

    public function update(Request $request, Group $group)
    {
        // Check if user is a manager
        $userRole = GroupUser::where('group_id', $group->id)
            ->where('user_id', auth()->id())
            ->value('role');

        if ($userRole !== 3) {
            return back()->with('error', 'شما دسترسی لازم برای ویرایش گروه را ندارید.');
        }

        $inputs = $request->validate([
            'description' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('images/groups'), $filename);
            $inputs['avatar'] = $filename;
        }

        $group->update($inputs);

        return back()->with('success', 'اطلاعات گروه با موفقیت به‌روزرسانی شد.');
    }

public function addUsersToGroup(Request $request)
{
    $userInfo = $request->input('userInfo');
    $group = Group::find($request->input('groupId'));


// ابتدا جستجو بر اساس ترکیب first_name و last_name
    $user = User::where(DB::raw('CONCAT(first_name, " ", last_name)'),  $userInfo)
            ->first();

    if (!$user) {
        // اگر کاربر پیدا نشد، ادامه بررسی‌های دیگر
        if (is_numeric($userInfo)) {
            // بررسی شماره تلفن ایران
            $userInfo = preg_replace('/\D/', '', $userInfo); // حذف هر کاراکتر غیر عددی
                // جستجو بر اساس شماره تلفن ایران
            $user = User::where('phone', $userInfo)->orWhere('id', $userInfo)->first();

        } elseif (filter_var($userInfo, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $userInfo)->first();
        } elseif (preg_match('/^\+?(\d{1,3})?(\d{10})$/', $userInfo)) {
            // اعتبارسنجی شماره تلفن با فرمت‌های جهانی
            $userInfo = preg_replace('/\D/', '', $userInfo); // حذف کاراکترهای غیر عددی
            $user = User::where('phone', $userInfo)->first();
        }
    }

    $checkInGroup = GroupUser::where('group_id', $group->id)->where('user_id', $user->id)->first();

    if($checkInGroup){
        return response()->json(['message' => 'کاربر قبلا در گروه ثبت‌نام کرده است']);
    }

    $group->users()->attach($user->id, ['role' => '4', 'status' => 0, 'expired' => now()->addHours($request->input('hours'))]); // Adding as guest

    return response()->json(['message' => 'Users added successfully']);
}

}
