<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    {{-- Logo --}}
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-shoe-prints"></i>
        </div>
        <div class="sidebar-brand-text mx-3">KLShoes Admin</div>
    </a>

    <hr class="sidebar-divider my-0">

    {{-- =========================================================== --}}
    {{-- 1. THỐNG KÊ DOANH THU: Chỉ Admin(1) và Giám đốc(4) thấy --}}
    {{-- =========================================================== --}}
    @if(Auth::user()->role_as == '1' || Auth::user()->role_as == '4')
    <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/revenue') }}">
            <i class="fas fa-fw fa-chart-line"></i> 
            <span>Thống kê Doanh thu</span></a>
    </li>
    <hr class="sidebar-divider">
    @endif


    {{-- =========================================================== --}}
    {{-- 2. QUẢN LÝ CỬA HÀNG: Chỉ Admin(1), Quản lý(2), Nhân viên(3) --}}
    {{-- =========================================================== --}}
    @if(Auth::user()->role_as == '1' || Auth::user()->role_as == '2' || Auth::user()->role_as == '3')
    
    <div class="sidebar-heading">
        Quản lý Cửa hàng
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-box-open"></i>
            <span>Quản lý Giày</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Chức năng:</h6>
                <a class="collapse-item" href="{{ url('admin/products') }}">Danh sách Giày</a>
                <a class="collapse-item" href="{{ url('admin/products/create') }}">Thêm Giày Mới</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/category') }}">
            <i class="fas fa-fw fa-list"></i>
            <span>Quản lý Loại giày</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/brand') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>Quản lý Thương hiệu</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/orders') }}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Quản lý Đơn hàng</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/reviews') }}">
            <i class="fas fa-fw fa-star"></i>
            <span>Quản lý Đánh giá</span>
        </a>
    </li>
    @endif


    {{-- =========================================================== --}}
    {{-- 3. MARKETING & GIAO DIỆN: Chỉ Admin(1) và Quản lý(2) thấy --}}
    {{-- =========================================================== --}}
    @if(Auth::user()->role_as == '1' || Auth::user()->role_as == '2')
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Marketing & Giao diện</div>
    
    <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/coupons') }}">
            <i class="fas fa-fw fa-ticket-alt"></i>
            <span>Quản lý Mã Giảm Giá</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/sliders') }}">
            <i class="fas fa-fw fa-images"></i>
            <span>Quản lý Banner</span>
        </a>
    </li>
    @endif


    {{-- =========================================================== --}}
    {{-- 4. HỆ THỐNG: Chỉ Admin(1) thấy --}}
    {{-- =========================================================== --}}
    @if(Auth::user()->role_as == '1')
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Hệ thống</div>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/users') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Quản lý Tài khoản</span>
        </a>
    </li>

    {{-- 👇👇👇 MỚI THÊM: CẤU HÌNH WEBSITE CHỈ ADMIN THẤY 👇👇👇 --}}
    <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/settings') }}">
            <i class="fas fa-fw fa-cog"></i>
            <span>Cấu hình Website</span>
        </a>
    </li>
    {{-- 👆👆👆 ------------------------------ --}}

    @endif
    
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}">
            <i class="fas fa-fw fa-home"></i>
            <span>Về trang chủ Shop</span>
        </a>
    </li>

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>