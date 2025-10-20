<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExperienceField;
use App\Models\OccupationalField;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index(){
        $experince = ExperienceField::where('status', 0)->get();
        $occupat = OccupationalField::where('status', 0)->get();

        $combines = $experince->merge($occupat);

        return view('admin.experience.index', compact('combines'));
    }

    public function update(Request $request, $id)
    {
        $table = $request->input('table');
    
        if ($table == 'experience_fields') {
            \App\Models\ExperienceField::findOrFail($id)->update(['name' => $request->input('name')]);
        } elseif ($table == 'occupational_fields') {
            \App\Models\OccupationalField::findOrFail($id)->update(['name' => $request->input('name')]);
    
        }
    
        return back()->with('success', 'آیتم با موفقیت تایید شد');
    }
    
    
    public function edit($id)
    {
        $table = $_GET['table'];
    
        if ($table == 'experience_fields') {
            \App\Models\ExperienceField::findOrFail($id)->update(['status' => 1]);
    
            // بررسی کاربران دارای این فیلد
            $userIds = \DB::table('user_experience_field')
                ->where('experience_field_id', $id)
                ->pluck('user_id');
    
            foreach ($userIds as $userId) {
                $user = \App\Models\User::find($userId);
    
                if (
                    $user->experiences()
                         ->where('status', '!=', 1)
                         ->count() == 0
                ) {
                    $user->update(['experience_status' => 1]);
                }
            }
    
        } elseif ($table == 'occupational_fields') {
            \App\Models\OccupationalField::findOrFail($id)->update(['status' => 1]);
    
            // بررسی کاربران دارای این فیلد
            $userIds = \DB::table('user_occupational_field')
                ->where('occupational_field_id', $id)
                ->pluck('user_id');
    
            foreach ($userIds as $userId) {
                $user = \App\Models\User::find($userId);
    
                if (
                    $user->specialties()
                         ->where('status', '!=', 1)
                         ->count() == 0
                ) {
                    $user->update(['occupational_status' => 1]);
                }
            }
        }
    
        return back()->with('success', 'آیتم با موفقیت تایید شد');
    }
    


    public function delete($id){
        $table = $_GET['table'];

        if($table == 'experience_fields'){
            ExperienceField::findOrFail($id)->delete();
        }elseif($table == 'occupational_fields'){
            OccupationalField::findOrFail($id)->delete();
        }

        return back()->with('success', 'آیتم با موفقیت حذف شد');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'required|string'
        ]);

        $modelMap = [
            'experience_fields' => \App\Models\ExperienceField::class,
            'occupational_fields' => \App\Models\OccupationalField::class,
            'experience' => \App\Models\ExperienceField::class,  // Add this mapping
            'occupational' => \App\Models\OccupationalField::class,  // Add this mapping
        ];

        $approvedCount = 0;
        $usersUpdated = 0;

        foreach ($request->input('items') as $itemString) {
            // Parse the item string (format: "table_id")
            $parts = explode('_', $itemString);
            if (count($parts) >= 2) {
                $table = $parts[0];
                $id = end($parts);

                if (isset($modelMap[$table])) {
                    $model = $modelMap[$table];
                    $item = $model::find($id);
                    
                    if ($item && $item->status == 0) {
                        $item->update(['status' => 1]);
                        $approvedCount++;

                        // Update related users based on table type
                        if ($table == 'experience_fields' || $table == 'experience') {
                            $userIds = \DB::table('user_experience_field')
                                ->where('experience_field_id', $id)
                                ->pluck('user_id');

                            foreach ($userIds as $userId) {
                                $user = \App\Models\User::find($userId);
                                if ($user && $user->experiences()->where('status', '!=', 1)->count() == 0) {
                                    $user->update(['experience_status' => 1]);
                                    $usersUpdated++;
                                }
                            }
                        } elseif ($table == 'occupational_fields' || $table == 'occupational') {
                            $userIds = \DB::table('user_occupational_field')
                                ->where('occupational_field_id', $id)
                                ->pluck('user_id');

                            foreach ($userIds as $userId) {
                                $user = \App\Models\User::find($userId);
                                if ($user && $user->specialties()->where('status', '!=', 1)->count() == 0) {
                                    $user->update(['occupational_status' => 1]);
                                    $usersUpdated++;
                                }
                            }
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$approvedCount} آیتم تأیید شد و {$usersUpdated} کاربر فعال شد."
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'required|string'
        ]);

        $modelMap = [
            'experience_fields' => \App\Models\ExperienceField::class,
            'occupational_fields' => \App\Models\OccupationalField::class,
            'experience' => \App\Models\ExperienceField::class,  // Add this mapping
            'occupational' => \App\Models\OccupationalField::class,  // Add this mapping
        ];

        $deletedCount = 0;

        foreach ($request->input('items') as $itemString) {
            // Parse the item string (format: "table_id")
            $parts = explode('_', $itemString);
            if (count($parts) >= 2) {
                $table = $parts[0];
                $id = end($parts);

                if (isset($modelMap[$table])) {
                    $model = $modelMap[$table];
                    $item = $model::find($id);
                    if ($item) {
                        $item->delete();
                        $deletedCount++;
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} آیتم با موفقیت حذف شد."
        ]);
    }
}
