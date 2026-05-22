{{-- 👇 Đã sửa đúng tên Layout và Section của trang Admin 👇 --}}
@extends('admin.index') 

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 grid-margin">
            @if(session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            <form action="{{ url('admin/settings') }}" method="POST">
                @csrf
                <div class="card shadow mb-4">
                    <div class="card-header bg-white py-3">
                        <h4 class="text-primary font-weight-bold mb-0">Cấu Hình Website KLShoes</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <h5 class="text-info mb-3 font-weight-bold">Thông Tin Chung</h5>
                                <div class="mb-3">
                                    <label>Tên Website</label>
                                    <input type="text" name="website_name" value="{{ $setting->website_name ?? '' }}" class="form-control" placeholder="KLShoes">
                                </div>
                                <div class="mb-3">
                                    <label>Đường dẫn Web (URL)</label>
                                    <input type="text" name="website_url" value="{{ $setting->website_url ?? '' }}" class="form-control" placeholder="http://ldkxnshoes.kvvanhvu.com">
                                </div>
                                <div class="mb-3">
                                    <label>Tiêu đề trang (Page Title)</label>
                                    <input type="text" name="page_title" value="{{ $setting->page_title ?? '' }}" class="form-control">
                                </div>
                                <h5 class="text-info mt-4 mb-3 font-weight-bold">SEO Google</h5>
                                <div class="mb-3">
                                    <label>Meta Keywords</label>
                                    <textarea name="meta_keyword" class="form-control" rows="3">{{ $setting->meta_keyword ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Meta Description</label>
                                    <textarea name="meta_description" class="form-control" rows="3">{{ $setting->meta_description ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-info mb-3 font-weight-bold">Thông Tin Liên Hệ</h5>
                                <div class="mb-3">
                                    <label>Địa chỉ cửa hàng</label>
                                    <input type="text" name="address" value="{{ $setting->address ?? '' }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Số điện thoại (Hotline)</label>
                                    <input type="text" name="phone1" value="{{ $setting->phone1 ?? '' }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Email liên hệ</label>
                                    <input type="email" name="email1" value="{{ $setting->email1 ?? '' }}" class="form-control">
                                </div>
                                <h5 class="text-info mt-4 mb-3 font-weight-bold">Mạng Xã Hội</h5>
                                <div class="mb-3">
                                    <label>Facebook (URL)</label>
                                    <input type="text" name="facebook" value="{{ $setting->facebook ?? '' }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>TikTok (URL)</label>
                                    <input type="text" name="tiktok" value="{{ $setting->tiktok ?? '' }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>YouTube (URL)</label>
                                    <input type="text" name="youtube" value="{{ $setting->youtube ?? '' }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right bg-white">
                        <button type="submit" class="btn btn-primary text-white">Lưu Cấu Hình</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection