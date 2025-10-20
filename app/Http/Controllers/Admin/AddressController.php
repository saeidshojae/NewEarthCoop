<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alley;
use App\Models\Neighborhood;
use App\Models\Region;
use App\Models\Rural;
use App\Models\Street;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(){
        $alleys = Alley::where('status', 0)->get();
        $streeys = Street::where('status', 0)->get();
        $neighborhoods = Neighborhood::where('status', 0)->get();
        $regions = Region::where('status', 0)->get();
        $rurals = Rural::where('status', 0)->get();
        $combines = $alleys->merge($streeys)->merge($neighborhoods)->merge($regions)->merge($rurals);

        return view('admin.address.index', compact('combines'));
    }


public function update(Request $request, $id)
{
    $modelMap = [
        'alleies' => \App\Models\Alley::class,
        'streets' => \App\Models\Street::class,
        'regions' => \App\Models\Region::class,
        'rural' => \App\Models\Rural::class,
        'neighborhoods' => \App\Models\Neighborhood::class,
    ];
    
    $table = $request->input('table');
    if (isset($modelMap[$table])) {
        $model = $modelMap[$table];

        $item = $model::findOrFail($id);
        $item->update(['name' => $request->input('name')]); // آیتم تأیید می‌شه

    }
    
    return back();
}

public function edit($id)
{
    $table = $_GET['table'];
    $updated = false;
    $modelMap = [
        'alleies' => \App\Models\Alley::class,
        'streets' => \App\Models\Street::class,
        'regions' => \App\Models\Region::class,
        'rural' => \App\Models\Rural::class,
        'neighborhoods' => \App\Models\Neighborhood::class,
    ];

    $fieldMap = [
        'alleies' => 'alley_id',
        'streets' => 'street_id',
        'regions' => 'region_id',
        'rural' => 'rural_district_id',
        'neighborhoods' => 'neighborhood_id',
    ];

    if (isset($modelMap[$table]) && isset($fieldMap[$table])) {
        $model = $modelMap[$table];
        $field = $fieldMap[$table];

        $item = $model::findOrFail($id);
        $item->update(['status' => 1]); // آیتم تأیید می‌شه

        // پیدا کردن تمام آدرس‌هایی که این آیتم را استفاده می‌کنند
        $addresses = \App\Models\Address::where($field, $id)->get();

        foreach ($addresses as $address) {
            if ($this->isAddressFullyApproved($address)) {
                $address->update(['status' => 1]);
            }
        }

        $updated = true;
    }

    return $updated
        ? back()->with('success', 'آدرس تأیید شد و آدرس‌های کامل نیز فعال شدند.')
        : back()->with('error', 'خطا در تأیید آدرس.');
}

public function isAddressFullyApproved($address)
{
    return
        (!$address->region_id || optional($address->region)->status == 1) &&
        (!$address->neighborhood_id || optional($address->neighborhood)->status == 1) &&
        (!$address->street_id || optional($address->street)->status == 1) &&
        (!$address->alley_id || optional($address->alley)->status == 1);
}

    
    public function delete($id){
        $table = $_GET['table'];

        if($table == 'alleies'){
            Alley::findOrFail($id)->delete();
        }elseif($table == 'streets'){
            Street::findOrFail($id)->delete();
        }elseif($table == 'regions'){
            Region::findOrFail($id)->delete();
        }elseif($table == 'rural'){
            Rural::findOrFail($id)->delete();
        }elseif($table == 'neighborhoods'){
            Neighborhood::findOrFail($id)->delete();
        }

        return back()->with('success', 'آدرس با موفقیت حذف شد');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'required|string'
        ]);

        $modelMap = [
            'alleies' => \App\Models\Alley::class,
            'streets' => \App\Models\Street::class,
            'regions' => \App\Models\Region::class,
            'rural' => \App\Models\Rural::class,
            'neighborhoods' => \App\Models\Neighborhood::class,
        ];

        $fieldMap = [
            'alleies' => 'alley_id',
            'streets' => 'street_id',
            'regions' => 'region_id',
            'rural' => 'rural_district_id',
            'neighborhoods' => 'neighborhood_id',
        ];

        $approvedCount = 0;
        $addressesUpdated = 0;

        foreach ($request->input('items') as $itemString) {
            // Parse the item string (format: "table_id")
            $parts = explode('_', $itemString);
            if (count($parts) >= 2) {
                $table = $parts[0];
                $id = end($parts);

                if (isset($modelMap[$table]) && isset($fieldMap[$table])) {
                    $model = $modelMap[$table];
                    $field = $fieldMap[$table];

                    $item = $model::find($id);
                    if ($item && $item->status == 0) {
                        $item->update(['status' => 1]);
                        $approvedCount++;

                        // Update related addresses
                        $addresses = \App\Models\Address::where($field, $id)->get();
                        foreach ($addresses as $address) {
                            if ($this->isAddressFullyApproved($address)) {
                                $address->update(['status' => 1]);
                                $addressesUpdated++;
                            }
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$approvedCount} آدرس تأیید شد و {$addressesUpdated} آدرس کامل فعال شد."
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'required|string'
        ]);

        $modelMap = [
            'alleies' => \App\Models\Alley::class,
            'streets' => \App\Models\Street::class,
            'regions' => \App\Models\Region::class,
            'rural' => \App\Models\Rural::class,
            'neighborhoods' => \App\Models\Neighborhood::class,
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
            'message' => "{$deletedCount} آدرس با موفقیت حذف شد."
        ]);
    }

}
