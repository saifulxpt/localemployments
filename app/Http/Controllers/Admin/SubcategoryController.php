<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    public function index(ServiceCategory $category)
    {
        $subcategories = $category->subcategories()->orderBy('sort_order')->paginate(20);
        return view('admin.categories.subcategories', compact('category', 'subcategories'));
    }

    public function create(ServiceCategory $category)
    {
        return view('admin.categories.subcategory-create', compact('category'));
    }

    public function store(Request $request, ServiceCategory $category)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['integer', 'min:0'],
        ]);
        $data['category_id'] = $category->id;
        $slug                = Str::slug($category->name . '-' . $data['name']);
        $data['slug']        = !empty($slug) ? $slug : 'sub-' . Str::random(6) . '-' . time();
        ServiceSubcategory::create($data);
        return redirect()->route('admin.categories.subcategories.index', $category)->with('success', 'সাব-ক্যাটাগরি যোগ হয়েছে।');
    }

    public function edit(ServiceSubcategory $subcategory)
    {
        $category = $subcategory->category;
        return view('admin.categories.subcategory-edit', compact('category', 'subcategory'));
    }

    public function update(Request $request, ServiceSubcategory $subcategory)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['integer', 'min:0'],
            'is_active'   => ['boolean'],
        ]);
        $subcategory->update($data);
        return back()->with('success', 'সাব-ক্যাটাগরি আপডেট হয়েছে।');
    }

    public function destroy(ServiceSubcategory $subcategory)
    {
        $category = $subcategory->category;
        $subcategory->delete();
        return redirect()->route('admin.categories.subcategories.index', $category)->with('success', 'সাব-ক্যাটাগরি মুছে ফেলা হয়েছে।');
    }
}
