<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GroupSetting;
use Illuminate\Http\Request;

class GroupSettingController extends Controller
{
    public function index(){
        if(isset($_GET['sort'])){
            if($_GET['sort'] == 'experience'){
                $groupSettings = GroupSetting::where('level', 'LIKE', '%' . $_GET['sort'] . '%')->orderBy('created_at', 'asc')->get();            
            }elseif($_GET['sort'] == 'job'){
                $groupSettings = GroupSetting::where('level', 'LIKE', '%' . $_GET['sort'] . '%')->orderBy('created_at', 'asc')->get();            
            }elseif($_GET['sort'] == 'age'){
                $groupSettings = GroupSetting::where('level', 'LIKE', '%' . $_GET['sort'] . '%')->orderBy('created_at', 'asc')->get();            
            }elseif($_GET['sort'] == 'gender'){
                $groupSettings = GroupSetting::where('level', 'LIKE', '%' . $_GET['sort'] . '%')->orderBy('created_at', 'asc')->get();            
            }else{
                $groupSettings = GroupSetting::where('id', '<=', 10)->orWhere('id', 42)->orderBy('created_at', 'desc')->get();            
            }
        }else{
            $groupSettings = GroupSetting::orderBy('created_at', 'desc')->get();            
        }
        
        return view('admin.group-setting.index', compact('groupSettings'));
    }
    
    public function edit(GroupSetting $setting){
        if($setting->election_status == 1){
            $setting->election_status = 0;
        }else{
            $setting->election_status = 1;
        }
        
        $setting->save();
                return back();

    }
    
    public function update(Request $request, GroupSetting $setting){
        $inputs = $request->validate([
            'manager_count' => 'required|numeric',
            'inspector_count' => 'required|numeric',
            'election_time' => 'required|numeric',
                        'max_for_election' => 'required|numeric',
                        'second_election_time' => 'required|numeric',

        ]);

        $setting->update($inputs);
        return back();

    }
}
