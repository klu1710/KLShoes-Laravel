<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    {{-- Nút 3 gạch (Mobile) --}}
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>
        
        {{-- Phần Thông tin User (Admin/Manager/Staff) --}}
        <li class="dropdown nav-item">
            <a class="nav-link dropdown-toggle" type="button" id="userDropdown" data-mdb-toggle="dropdown" aria-expanded="false" >
                
                {{-- 1. Hiển thị Tên người dùng đang đăng nhập --}}
                <span class="badge bg-primary">
                    {{ Auth::user()->name }}
                </span>&ensp;

                {{-- 2. Logic hiển thị Avatar --}}
                @if(Auth::user()->avatar)
                    {{-- Nếu có avatar thì hiện avatar --}}
                    <img class="img-profile rounded-circle" 
                         style="width: 40px; height: 40px; object-fit: cover;"
                         src="{{ asset(Auth::user()->avatar) }}">
                @else
                    {{-- Nếu chưa có thì hiện logo mặc định --}}
                    <img class="img-profile rounded-circle" 
                         style="width: 40px; height: 40px; object-fit: cover;"
                         src="{{ asset('images1/icon-logo.png') }}">
                @endif

            </a>

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start" aria-labelledby="userDropdown">
                
                {{-- Link về trang chủ --}}
                <li><a class="dropdown-item" href="{{ url('/') }}">
                    <i class="fas fa-home me-2 text-gray-400"></i> Trang chủ shop
                </a></li>
                
                <div class="dropdown-divider"></div>

                {{-- Nút Đăng xuất --}}
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2 text-gray-400"></i> Đăng xuất
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
                
            </ul>
        </li>

    </ul>

</nav>