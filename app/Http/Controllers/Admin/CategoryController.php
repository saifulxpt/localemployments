<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::withCount('activeSubcategories')->orderBy('sort_order')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150', 'unique:service_categories,name'],
            'icon'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['integer', 'min:0'],
        ]);
        $data['slug'] = Str::slug($data['name']);
        ServiceCategory::create($data);
        AdminActivityLog::record("Created category: {$data['name']}");
        return redirect()->route('admin.categories.index')->with('success', 'ক্যাটাগরি যোগ হয়েছে।');
    }

    public function edit(ServiceCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, ServiceCategory $category)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150', 'unique:service_categories,name,' . $category->id],
            'icon'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['integer', 'min:0'],
            'is_active'   => ['boolean'],
        ]);
        $category->update($data);
        AdminActivityLog::record("Updated category: {$category->name}", $category);
        return redirect()->route('admin.categories.index')->with('success', 'ক্যাটাগরি আপডেট হয়েছে।');
    }

    public function destroy(ServiceCategory $category)
    {
        $category->delete();
        AdminActivityLog::record("Deleted category: {$category->name}", $category);
        return redirect()->route('admin.categories.index')->with('success', 'ক্যাটাগরি মুছে ফেলা হয়েছে।');
    }
}
