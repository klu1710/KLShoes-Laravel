<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand; 
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brand.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:brands,name',
            'slug' => 'required|unique:brands,slug',
        ]);

        $brand = new Brand;
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->slug);
        $brand->status = $request->status == true ? '1' : '0';
        $brand->save();

        return redirect('admin/brand')->with('message', 'Thêm thương hiệu thành công!');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brand.edit', compact('brand'));
    }

    public function update(Request $request, $brand)
    {
        $brand = Brand::findOrFail($brand);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->slug);
        $brand->status = $request->status == true ? '1' : '0';
        $brand->update();

        return redirect('admin/brand')->with('message', 'Cập nhật thành công!');
    }

    public function destroy($brand)
    {
        $item = Brand::findOrFail($brand);
        $item->delete();
        return redirect('admin/brand')->with('message', 'Đã xóa thương hiệu!');
    }
}