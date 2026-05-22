@extends('admin.index') {{-- 👈 Sửa thành admin.index --}}

@section('admin_content') {{-- 👈 Sửa thành admin_content --}}

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>Sửa Banner
                    <a href="{{ url('admin/sliders') }}" class="btn btn-danger btn-sm text-white float-end">Quay lại</a>
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/sliders/'.$slider->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label>Tiêu đề</label>
                        <input type="text" name="title" value="{{ $slider->title }}" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Mô tả</label>
                        <textarea name="description" class="form-control" rows="3">{{ $slider->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>Hình ảnh</label>
                        <input type="file" name="image" class="form-control">
                        <br>
                        <img src="{{ asset($slider->image) }}" width="100px" height="100px" alt="Slider Image">
                    </div>

                    <div class="mb-3">
                        <label>Trạng thái</label> <br/>
                        <input type="checkbox" name="status" {{ $slider->status == '1' ? 'checked':'' }} style="width: 20px; height: 20px;" /> 
                        <span class="ms-2">Check = Ẩn, Không check = Hiện</span>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary text-white">Cập nhật</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection