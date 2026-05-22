<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    // 1. Hiển thị danh sách
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // 2. Mở form Thêm mới
    public function create()
    {
        return view('admin.users.create');
    }

    // 3. Xử lý Lưu tài khoản mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role_as' => 'required|integer',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Mã hóa mật khẩu
            'role_as' => $request->role_as,
        ]);

        return redirect('admin/users')->with('message', 'Thêm tài khoản thành công!');
    }

    // 4. Mở form Sửa
    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);
        return view('admin.users.edit', compact('user'));
    }

    // 5. Xử lý Cập nhật tài khoản
    public function update(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'role_as' => 'required|integer',
            'avatar' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048', // Validate ảnh
        ]);

        $user->name = $request->name;
        $user->role_as = $request->role_as;

        // Xử lý Mật khẩu
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        //  XỬ LÝ UPLOAD ẢNH AVATAR 
        if ($request->hasFile('avatar')) {
            
            // Xóa ảnh cũ nếu có (để đỡ rác server)
            $path = 'uploads/users/'.$user->avatar;
            if(File::exists($path)){
                File::delete($path);
            }

            // Lưu ảnh mới
            $file = $request->file('avatar');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;
            
            $file->move('uploads/users/', $filename);
            $user->avatar = 'uploads/users/'.$filename;
        }
        //  KẾT THÚC XỬ LÝ ẢNH 

        $user->update();

        return redirect('admin/users')->with('message', 'Cập nhật tài khoản thành công!');
    }

    // 6. Xóa tài khoản
    public function destroy($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->delete();
        return redirect('admin/users')->with('message', 'Đã xóa tài khoản!');
    }
}