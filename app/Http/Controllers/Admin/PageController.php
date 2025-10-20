<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::latest()->get();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'is_published' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($request->title);
        
        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'is_published' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($request->title);
        
        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }
   
    public function upload(Request $request)
{
    if ($request->hasFile('upload')) {
        $file = $request->file('upload');
        $filename = time().'_'.$file->getClientOriginalName();
        $filename = str_replace(' ', '_', time().'_'.$file->getClientOriginalName());

        $file->move(public_path('uploads'), $filename);

        $url = url('uploads/'.$filename); // ساختن لینک کامل

        return response()->json([
            'uploaded' => 1,
            'fileName' => $filename,
            'url' => $url,
        ]);
    }

    return response()->json([
        'uploaded' => 0,
        'error' => [
            'message' => 'هیچ فایلی آپلود نشد.'
        ]
    ]);
}

    
}
