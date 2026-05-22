<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // 1. Danh sách
    public function index()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.category.index', compact('categories'));
    }

    // 2. Form Thêm mới
    public function create()
    {
        return view('admin.category.create');
    }

    // 3. Xử lý Lưu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories,name',
            'slug' => 'required|unique:categories,slug',
        ]);

        $category = new Category;
        $category->name = $request->name;
        $category->slug = Str::slug($request->slug);
        $category->description = $request->description;
        $category->status = $request->status == true ? '1' : '0';
        $category->save();

        return redirect('admin/category')->with('message', 'Thêm danh mục thành công!');
    }

    // 4. Form Sửa
    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    // 5. Xử lý Cập nhật
    public function update(Request $request, $category)
    {
        $category = Category::findOrFail($category);

        $category->name = $request->name;
        $category->slug = Str::slug($request->slug);
        $category->description = $request->description;
        $category->status = $request->status == true ? '1' : '0';
        $category->update();

        return redirect('admin/category')->with('message', 'Cập nhật thành công!');
    }

    // 6. Xóa (Rất quan trọng: Xóa danh mục thì các sản phẩm thuộc danh mục đó sẽ bị bơ vơ, nên cẩn thận)
    public function destroy($category)
    {
        $item = Category::findOrFail($category);
        $item->delete();
        return redirect('admin/category')->with('message', 'Đã xóa danh mục!');
    }
}