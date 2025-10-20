<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index()
    {
        $terms = Term::all();
        return view('admin.rule.index', compact('terms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'parent_id' => 'nullable'
        ]);
        
        if($request->parent_id == 'null'){
            $request->parent_id = null;
        }

        Term::create(['title' => $request->title, 'message' => $request->message, 'parent_id' => $request->parent_id]);

        return redirect()->route('admin.rule.index')->with('success', 'اساسنامه با موفقیت ایجاد شد.');
    }



    public function update(Request $request, Term $term)
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'parent_id' => 'nullable'
        ]);
        
        if($request->parent_id == 'null'){
            $request->parent_id = null;
        }

        $term->update(['title' => $request->title, 'message' => $request->message, 'parent_id' => $request->parent_id]);

        return redirect()->route('admin.rule.index')->with('success', 'اساسنامه با موفقیت ویرایش شد.');
    }


    public function destroy(Term $term)
    {
        $term->delete();

        return redirect()->route('admin.rule.index')->with('success', 'اساسنامه با موفقیت حذف شد.');
    }

}
