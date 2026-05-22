<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first(); 
        return view('admin.setting.index', compact('setting'));
    }

    public function save(Request $request)
    {
        $setting = Setting::first();
        if($setting) {
            $setting->update($request->all());
            return redirect()->back()->with('message', 'Cập nhật cấu hình thành công!');
        } else {
            Setting::create($request->all());
            return redirect()->back()->with('message', 'Đã thêm cấu hình Website!');
        }
    }
}