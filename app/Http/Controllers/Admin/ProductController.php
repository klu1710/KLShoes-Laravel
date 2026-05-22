<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Color;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // 0. Hàm hiển thị Danh sách giày
    public function index()
    {
        $products = Product::orderBy('category_id', 'DESC')->paginate(10);
        return view('admin.giay.giay', compact('products'));
    }

    // 1. Hàm hiển thị Form thêm mới
    public function create()
    {
        $loaigiays = Category::all();
        $thuonghieus = Brand::all();
        $colors = Color::where('status', 0)->get();
        
        return view('admin.giay.them', compact('loaigiays', 'thuonghieus', 'colors'));
    }

    // 2. Hàm xử lý Lưu dữ liệu (Store)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'slug' => 'required',
            'brand_id' => 'required',
            'original_price' => 'required',
            'selling_price' => 'required',
            'image' => 'nullable|image',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array', 
            'quantities' => 'nullable|array',
            // THÊM VALIDATE CHO KHUYẾN MÃI
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'sale_start' => 'nullable|date',
            'sale_end' => 'nullable|date|after:sale_start',
        ]);

        $product = new Product;
        $product->category_id = $validated['category_id'];
        $product->brand_id = $validated['brand_id'];
        $product->name = $validated['name'];
        $product->slug = Str::slug($validated['slug']);
        $product->small_description = $request->small_description;
        $product->description = $request->description;
        $product->original_price = $validated['original_price'];
        $product->selling_price = $validated['selling_price'];
        
        //  LƯU THÔNG TIN KHUYẾN MÃI 
        $product->discount_percent = $request->discount_percent;
        $product->sale_start = $request->sale_start;
        $product->sale_end = $request->sale_end;

        $product->status = $request->status == true ? 1 : 0;
        $product->trending = $request->trending == true ? 1 : 0;

        // Xử lý ảnh đại diện
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('uploads/products/', $filename);
            $product->image = "uploads/products/$filename";
        }
        
        $product->save(); // Lưu vào DB

        // B. Lưu Album ảnh (ProductImage)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $ext = $imageFile->getClientOriginalExtension();
                $filename = time() . rand(1, 1000) . '.' . $ext;
                $imageFile->move('uploads/products/', $filename);

                $product->productImages()->create([
                    'product_id' => $product->id,
                    'image' => "uploads/products/$filename"
                ]);
            }
        }

        // C. Lưu Size và Màu
        if ($request->sizes) {
            foreach ($request->sizes as $key => $size) {
                if (!empty($size)) {
                    $product->productSizes()->create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color_id' => $request->colors[$key] ?? null,
                        'quantity' => $request->quantities[$key] ?? 0
                    ]);
                }
            }
        }

        return redirect('admin/products/create')->with('message', 'Thêm giày thành công! ✅');
    }

    // 3. Hàm hiển thị Form sửa (Edit)
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $loaigiays = Category::all();
        $thuonghieus = Brand::all();
        $colors = Color::where('status', 0)->get();

        return view('admin.giay.sua', compact('product', 'loaigiays', 'thuonghieus', 'colors'));
    }

    // 4. Hàm xử lý Cập nhật (Update)
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'slug' => 'required',
            'brand_id' => 'required',
            'selling_price' => 'required',
            //  THÊM VALIDATE UPDATE
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'sale_start' => 'nullable|date',
            'sale_end' => 'nullable|date|after:sale_start',
        ]);

        $product = Product::findOrFail($id);

        $product->category_id = $validated['category_id'];
        $product->brand_id = $validated['brand_id'];
        $product->name = $validated['name'];
        $product->slug = Str::slug($validated['slug']);
        $product->description = $request->description;
        $product->original_price = $request->original_price;
        $product->selling_price = $validated['selling_price'];
        
        //  UPDATE THÔNG TIN KHUYẾN MÃI 
        $product->discount_percent = $request->discount_percent;
        $product->sale_start = $request->sale_start;
        $product->sale_end = $request->sale_end;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('uploads/products/', $filename);
            $product->image = "uploads/products/$filename";
        }

        $product->save(); // Đã đổi từ update() sang save() cho chuẩn Logic

        // Cập nhật Size/Màu
        if ($request->sizes) {
            $product->productSizes()->delete(); 
            foreach ($request->sizes as $key => $size) {
                if (!empty($size)) {
                    $product->productSizes()->create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color_id' => $request->colors[$key] ?? null,
                        'quantity' => $request->quantities[$key] ?? 0
                    ]);
                }
            }
        }

        return redirect('admin/products')->with('message', 'Cập nhật giày thành công! ✅');
    }

    // 5. Hàm Xóa
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->productImages()->delete();
        $product->productSizes()->delete();
        $product->delete();

        return redirect()->back()->with('message', 'Đã xóa giày thành công! 🗑️');
    }
}