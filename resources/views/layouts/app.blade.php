<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{--  LẤY DỮ LIỆU CẤU HÌNH TỪ DATABASE  --}}
    @php
        $setting = App\Models\Setting::first();
    @endphp

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{--  SEO ĐỘNG TỪ ADMIN  --}}
    <title>{{ $setting->page_title ?? 'KLShoes - Shop Giày' }}</title>
    <meta name="description" content="{{ $setting->meta_description ?? '' }}">
    <meta name="keywords" content="{{ $setting->meta_keyword ?? '' }}">

    {{--  ICON LOGO --}}
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL('images1/icon-logo.png') }}">

    {{-- CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <style>
        a { text-decoration: none; }
        .dropdown-menu { right: 0; left: auto; }
        @media (max-width: 991px) {
            .navbar-collapse form { margin: 10px 0; width: 100% !important; max-width: 100% !important; }
        }
        
        /*  ĐÁP ỨNG YÊU CẦU THẦY: HIỆU ỨNG HOVER SẢN PHẨM  */
        .product-card { 
            transition: all 0.3s ease-in-out; 
            border: 1px solid #eee;
        }
        .product-card:hover { 
            transform: translateY(-8px); /* Cho sản phẩm bay lên 8px */
            box-shadow: 0 15px 25px rgba(0,0,0,0.15); /* Thêm bóng đổ phía sau */
            border-color: #ffc107; /* Đổi viền sang màu vàng */
            z-index: 1;
        }

        /* CSS CHO NÚT LIÊN HỆ NỔI */
        .floating-contact {
            position: fixed; bottom: 20px; right: 20px; z-index: 9999;
            display: flex; flex-direction: column; gap: 12px;
        }
        .contact-icon {
            width: 50px; height: 50px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; text-decoration: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            transition: all 0.3s ease; font-size: 24px; position: relative;
        }
        .contact-icon:hover { transform: scale(1.1); color: white; }
        .btn-zalo { background: #0068ff; border: 2px solid white; }
        .btn-messenger { background: #0084ff; border: 2px solid white; }
        .btn-phone { background: #dc3545; border: 2px solid white; animation: ring 1.5s infinite; }
        @keyframes ring {
            0% { transform: rotate(0deg); }
            10% { transform: rotate(10deg); }
            20% { transform: rotate(-10deg); }
            30% { transform: rotate(10deg); }
            40% { transform: rotate(-10deg); }
            50% { transform: rotate(0deg); }
            100% { transform: rotate(0deg); }
        }
        .contact-icon::before {
            content: attr(data-tooltip); position: absolute;
            right: 60px; top: 50%; transform: translateY(-50%);
            background: #333; color: #fff; padding: 5px 10px;
            border-radius: 5px; font-size: 12px; white-space: nowrap;
            opacity: 0; visibility: hidden; transition: 0.3s; pointer-events: none;
        }
        .contact-icon:hover::before { opacity: 1; visibility: visible; }

        /* CSS cho Pop-up Khuyến Mãi */
        .popup-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(3px);
            z-index: 99999; display: flex; justify-content: center; align-items: center;
            animation: fadeIn 0.4s ease-in-out;
        }
        .popup-content {
            background: #fff; padding: 30px 25px; border-radius: 12px;
            text-align: center; max-width: 380px; width: 90%; position: relative;
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
            animation: popUp 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .popup-close {
            position: absolute; top: 10px; right: 15px;
            font-size: 28px; cursor: pointer; color: #aaa; line-height: 1;
        }
        .popup-close:hover { color: #e74c3c; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes popUp { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    {{-- MENU (Đã XÓA mb-4 ĐỂ MẤT KHOẢNG TRẮNG TRÊN BANNER) --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow mb-0">
        <div class="container">
            {{-- TÊN WEB ĐỘNG --}}
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">{{ $setting->website_name ?? 'KLSHOES' }} 👟</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                
                {{--  ĐÃ THU NHỎ THANH TÌM KIẾM LẠI  --}}
                <form class="d-flex mx-auto" style="max-width: 300px; width: 100%;" action="{{ url('search') }}" method="GET">
                    <div class="input-group">
                        <input class="form-control border-0" type="search" name="keyword" placeholder="Tìm giày gì?..." required>
                        <button class="btn btn-warning text-dark fw-bold" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>

                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/') }}">Trang chủ</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Sản phẩm
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ url('all-products') }}">Tất cả sản phẩm</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @if(isset($appCategories))
                                @foreach($appCategories as $cateItem)
                                    <li><a class="dropdown-item" href="{{ url('collections/'.$cateItem->slug) }}">{{ $cateItem->name }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link text-danger fw-bold pe-3" href="{{ url('sale-off') }}"><i class="fas fa-fire"></i> Sale</a></li>
                    
                    {{--  ĐỔI THÀNH ICON CHO GỌN KHÔNG RỚT DÒNG  --}}
                    <li class="nav-item border-start ps-3">
                        <a class="nav-link" href="{{ url('track-order') }}" title="Tra cứu đơn hàng">
                            <i class="fas fa-truck fa-lg"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('cart') }}" title="Giỏ hàng">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                        </a>
                    </li>

                    @guest
                        {{--  GOM ĐĂNG NHẬP/ĐĂNG KÝ VÀO 1 NÚT ICON HÌNH NGƯỜI  --}}
                        <li class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle btn btn-outline-secondary border-0" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fa-lg"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (Route::has('login'))
                                    <li><a class="dropdown-item" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-2"></i> Đăng nhập</a></li>
                                @endif
                                @if (Route::has('register'))
                                    <li><a class="dropdown-item" href="{{ route('register') }}"><i class="fas fa-user-plus me-2"></i> Đăng ký</a></li>
                                @endif
                            </ul>
                        </li>
                    @else
                        <li class="nav-item dropdown ms-3">
                            <a id="navbarDropdownUser" class="nav-link dropdown-toggle btn btn-secondary text-white px-3 d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset(Auth::user()->avatar) }}" class="rounded-circle me-2" style="width: 28px; height: 28px; object-fit: cover; border: 2px solid #fff;">
                                @else
                                    <i class="fas fa-user me-2"></i>
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                                @if(Auth::user()->role_as != '0') 
                                    <li><a class="dropdown-item text-danger fw-bold" href="{{ url('admin') }}"><i class="fas fa-tools me-2"></i> Trang Quản trị</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ url('my-profile') }}"><i class="fas fa-user-circle me-2"></i> Hồ sơ</a></li>
                                <li><a class="dropdown-item" href="{{ url('my-orders') }}"><i class="fas fa-list me-2"></i> Đơn hàng</a></li>
                                <li><a class="dropdown-item" href="{{ url('wishlist') }}"><i class="fas fa-heart text-danger me-2"></i> Yêu thích</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        {{--  Đã xóa class mt-3 gây khoảng trắng  --}}
        <div class="container">
            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="fa fa-check-circle"></i> <strong>Thông báo:</strong> {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        @yield('content')
    </main>

    <footer class="bg-dark text-white pt-5 pb-3 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-uppercase text-warning">{{ $setting->website_name ?? 'KL Shoes' }} 👟</h5>
                    <p class="small">Nâng niu bàn chân Việt. Chúng tôi cam kết mang đến những đôi giày chất lượng nhất.</p>
                    <div>
                        @if(isset($setting->facebook) && $setting->facebook != '')
                            <a href="{{ $setting->facebook }}" target="_blank" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        @endif
                        @if(isset($setting->tiktok) && $setting->tiktok != '')
                            <a href="{{ $setting->tiktok }}" target="_blank" class="text-white me-3"><i class="fab fa-tiktok fa-lg"></i></a>
                        @endif
                        @if(isset($setting->youtube) && $setting->youtube != '')
                            <a href="{{ $setting->youtube }}" target="_blank" class="text-white me-3"><i class="fab fa-youtube fa-lg"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-uppercase text-warning">Liên Kết Nhanh</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/') }}" class="text-white text-decoration-none">Trang Chủ</a></li>
                        <li class="mb-2"><a href="{{ url('all-products') }}" class="text-white text-decoration-none">Sản Phẩm</a></li>
                        <li class="mb-2"><a href="{{ url('track-order') }}" class="text-white text-decoration-none">Tra Cứu Đơn Hàng</a></li>
                        <li class="mb-2">
                            <a href="{{ url('chinh-sach-bao-hanh') }}" class="text-white text-decoration-none text-warning">
                                <i class="fas fa-shield-alt me-1"></i> Chính sách Bảo hành
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-uppercase text-warning">Thông Tin Liên Hệ</h5>
                    <div class="small">
                        <p class="mb-2"><i class="fas fa-map-marker-alt me-2 text-warning"></i> {{ $setting->address ?? 'TP. Long Xuyên, An Giang' }}</p>
                        <p class="mb-2"><i class="fas fa-phone-alt me-2 text-warning"></i> <a href="tel:{{ $setting->phone1 ?? '0783177977' }}" class="text-white text-decoration-none">{{ $setting->phone1 ?? '0783.177.977' }}</a></p>
                        <p class="mb-2"><i class="fas fa-envelope me-2 text-warning"></i> <a href="mailto:{{ $setting->email1 ?? 'cskh.klshoes@gmail.com' }}" class="text-white text-decoration-none">{{ $setting->email1 ?? 'cskh.klshoes@gmail.com' }}</a></p>
                    </div>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center small">
                <p class="mb-0">&copy; 2026 {{ $setting->website_name ?? 'KLShoes' }}. All rights reserved. Designed by <span class="fw-bold text-warning">Kiên Lưu</span></p>
            </div>
        </div>
    </footer>

    {{-- BỘ NÚT LIÊN HỆ NỔI --}}
    <div class="floating-contact">
        <a href="https://zalo.me/{{ $setting->phone1 ?? '0783177977' }}" target="_blank" class="contact-icon btn-zalo" data-tooltip="Chat Zalo"><b style="font-family: Arial, sans-serif; font-weight: 900; font-size: 14px;">Zalo</b></a>
        @if(isset($setting->facebook) && $setting->facebook != '')
            <a href="{{ $setting->facebook }}" target="_blank" class="contact-icon btn-messenger" data-tooltip="Chat Facebook"><i class="fab fa-facebook-messenger"></i></a>
        @endif
        <a href="tel:{{ $setting->phone1 ?? '0783177977' }}" class="contact-icon btn-phone" data-tooltip="Gọi ngay: {{ $setting->phone1 ?? '0783.177.977' }}"><i class="fas fa-phone-alt"></i></a>
    </div>

    {{-- POP-UP KHUYẾN MÃI --}}
    <div id="klshoes-popup" class="popup-overlay" style="display: none;">
        <div class="popup-content">
            <span class="popup-close" onclick="closePopup()">&times;</span>
            <img src="https://cdn-icons-png.flaticon.com/512/4213/4213958.png" alt="Sale" style="width: 80px; margin-bottom: 10px;">
            <h2 style="color: #e74c3c; margin-top: 0; font-family: sans-serif;">🎉 CHÀO BẠN MỚI!</h2>
            <p style="color: #555; font-size: 16px;">Tặng ngay mã giảm giá cực sốc cho đơn hàng đầu tiên tại {{ $setting->website_name ?? 'KLShoes' }}.</p>
            <div class="coupon-box" style="background: #f1f2f6; padding: 10px; border: 2px dashed #e74c3c; font-size: 28px; font-weight: bold; letter-spacing: 4px; margin: 15px 0; color: #333;">KL50</div>
            <button onclick="copyCoupon()" style="background: #e74c3c; color: white; border: none; padding: 12px 20px; font-size: 16px; font-weight: bold; border-radius: 5px; cursor: pointer; width: 100%; box-shadow: 0 4px 6px rgba(231, 76, 60, 0.3);">Sao chép mã & Mua ngay</button>
        </div>
    </div>

    {{-- CHATBOT AI GEMINI --}}
    <div id="ai-chat-btn" onclick="toggleChat()" style="position: fixed; bottom: 20px; left: 20px; width: 60px; height: 60px; background: #212529; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 5px 15px rgba(0,0,0,0.3); z-index: 9998; border: 2px solid #ffc107; transition: 0.3s;">
        <i class="fas fa-robot text-warning" style="font-size: 28px;"></i>
        <span style="position: absolute; top: -5px; right: -5px; background: #dc3545; width: 15px; height: 15px; border-radius: 50%; border: 2px solid #fff;"></span>
    </div>

    <div id="ai-chat-window" style="display: none; position: fixed; bottom: 90px; left: 20px; width: 380px; height: 500px; background: #fff; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); z-index: 9999; flex-direction: column; overflow: hidden; border: 1px solid #ddd;">
        <div style="background: #212529; color: #ffc107; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-robot" style="font-size: 24px;"></i>
                <div>
                    <h6 style="margin: 0; font-weight: bold;">KL-Bot (Powered by AI)</h6>
                    <small style="color: #ccc; font-size: 11px;">🟢 Đang trực tuyến</small>
                </div>
            </div>
            <i class="fas fa-times" onclick="toggleChat()" style="cursor: pointer; font-size: 20px; color: #fff;"></i>
        </div>
        <div id="chat-messages" style="flex: 1; padding: 15px; overflow-y: auto; background: #f8f9fa; display: flex; flex-direction: column; gap: 10px;">
            <div style="align-self: flex-start; background: #e9ecef; padding: 10px 15px; border-radius: 15px 15px 15px 0; max-width: 85%; font-size: 14px; color: #333; word-wrap: break-word;">
                Chào bạn! Mình là trợ lý AI thông minh của {{ $setting->website_name ?? 'KLShoes' }} 👟. Bạn có thắc mắc gì về giày hay chính sách của shop cứ hỏi mình nhé!
            </div>
        </div>
        <div style="padding: 10px; background: #fff; border-top: 1px solid #eee; display: flex; gap: 10px;">
            <input type="text" id="chat-input" placeholder="Nhập câu hỏi của bạn..." onkeypress="handleEnter(event)" style="flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 14px;">
            <button onclick="sendMessage()" style="background: #ffc107; color: #212529; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('frontend/js/custom.js') }}"></script>
    
    <script>
        setTimeout(function() {
            if (!sessionStorage.getItem('klshoes_popup_closed')) {
                document.getElementById('klshoes-popup').style.display = 'flex';
            }
        }, 2000);
        function closePopup() {
            document.getElementById('klshoes-popup').style.display = 'none';
            sessionStorage.setItem('klshoes_popup_closed', 'true');
        }

        function copyCoupon() {
            var maGiamGia = "KL50"; 
            var tempInput = document.createElement("input");
            tempInput.value = maGiamGia;
            tempInput.style.position = "fixed";
            tempInput.style.left = "-9999px"; 
            tempInput.style.top = "0";
            document.body.appendChild(tempInput);
            
            tempInput.focus();
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); 
            
            try {
                document.execCommand("copy");
                alert(" Tuyệt vời! Bạn đã sao chép mã '" + maGiamGia + "' thành công. Bắt đầu mua sắm thôi!");
            } catch (err) {
                alert(" Có lỗi xảy ra, bạn vui lòng copy tay mã: " + maGiamGia + " nhé!");
            }
            
            document.body.removeChild(tempInput);
            closePopup();
        }
    </script>
    
    <script>
        const GEMINI_API_KEY = "AIzaSyAqT5q2HPqTusRYXcYK9J4YUe4BjE-yCLE"; 

        window.toggleChat = function() {
            const chatWin = document.getElementById('ai-chat-window');
            chatWin.style.display = chatWin.style.display === 'none' ? 'flex' : 'none';
        };

        window.handleEnter = function(e) {
            if (e.key === 'Enter') window.sendMessage();
        };

        window.sendMessage = async function() {
            const input = document.getElementById('chat-input');
            const message = input.value.trim();
            if (!message) return;

            const chatBox = document.getElementById('chat-messages');

            chatBox.innerHTML += `
                <div style="align-self: flex-end; background: #212529; color: #ffc107; padding: 10px 15px; border-radius: 15px 15px 0 15px; max-width: 85%; font-size: 14px; word-wrap: break-word;">
                    ${message}
                </div>
            `;
            input.value = '';
            chatBox.scrollTop = chatBox.scrollHeight;

            const typingId = 'typing-' + Date.now();
            chatBox.innerHTML += `
                <div id="${typingId}" style="align-self: flex-start; background: #e9ecef; padding: 10px 15px; border-radius: 15px 15px 15px 0; font-size: 14px; color: #888; font-style: italic;">
                    KL-Bot đang suy nghĩ... <i class="fas fa-circle-notch fa-spin"></i>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;

            let msgLower = message.toLowerCase();
            const badWords = ['ngu', 'óc', 'điên', 'khùng', 'đần', 'chó', 'cứt', 'đm', 'vcl', 'vl', 'đéo', 'cc', 'dmm', 'cmm', 'cmn'];
            let isBadWord = badWords.some(word => msgLower.includes(word));

            if (isBadWord) {
                document.getElementById(typingId).remove();
                chatBox.innerHTML += `
                    <div style="align-self: flex-start; background: #e9ecef; padding: 10px 15px; border-radius: 15px 15px 15px 0; max-width: 90%; font-size: 14px; color: #333; line-height: 1.6; word-wrap: break-word;">
                        Dạ, bạn vui lòng chat văn minh hơn đi ạ. KL-Bot xin cảm ơn! 😇
                    </div>
                `;
                chatBox.scrollTop = chatBox.scrollHeight;
                return;
            }

            const systemPrompt = `Bạn là trợ lý AI tư vấn cực kỳ thông minh của cửa hàng giày {{ $setting->website_name ?? 'KLShoes' }}. Cửa hàng ở {{ $setting->address ?? 'TP. Long Xuyên, An Giang' }}. Hotline/Zalo: {{ $setting->phone1 ?? '0783.177.977' }}. Phí ship toàn quốc 30k, freeship từ 2 đôi. Bảo hành keo đế 6 tháng, 1 đổi 1 trong 7 ngày nếu lỗi. Giày chuẩn form, hỗ trợ đổi size tận nhà. Hàng chính hãng 100%, phát hiện fake đền x10. Cho phép khách kiểm tra và thử hàng trước khi thanh toán. Trả lời ngắn gọn, tự nhiên như người thật. TUYỆT ĐỐI KHÔNG dùng định dạng in đậm Markdown, hãy xuống dòng bằng phím Enter bình thường. Trả lời câu hỏi sau của khách: "${message}"`;

            const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=${GEMINI_API_KEY}`;

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        contents: [{ parts: [{ text: systemPrompt }] }]
                    })
                });

                const data = await response.json();
                document.getElementById(typingId).remove();

                if (!response.ok) {
                    console.error("Lỗi từ Google:", data);
                    chatBox.innerHTML += `
                        <div style="align-self: flex-start; background: #ffcccc; padding: 10px 15px; border-radius: 15px 15px 15px 0; max-width: 90%; font-size: 14px; color: #d8000c;">
                            <b>Lỗi API Google:</b> ${data.error ? data.error.message : 'Lỗi kết nối API!'}
                        </div>
                    `;
                    chatBox.scrollTop = chatBox.scrollHeight;
                    return;
                }

                let reply = "Dạ hệ thống AI đang quá tải một chút, bạn vui lòng gọi Hotline {{ $setting->phone1 ?? '0783.177.977' }} nhé! 😅";
                if(data.candidates && data.candidates.length > 0) {
                    reply = data.candidates[0].content.parts[0].text;
                    reply = reply.replace(/\n/g, '<br>'); 
                }

                chatBox.innerHTML += `
                    <div style="align-self: flex-start; background: #e9ecef; padding: 10px 15px; border-radius: 15px 15px 15px 0; max-width: 90%; font-size: 14px; color: #333; line-height: 1.6; word-wrap: break-word;">
                        ${reply}
                    </div>
                `;
                setTimeout(() => { chatBox.scrollTop = chatBox.scrollHeight; }, 50);

            } catch (error) {
                document.getElementById(typingId).remove();
                chatBox.innerHTML += `
                    <div style="align-self: flex-start; background: #ffeeba; padding: 10px 15px; border-radius: 15px 15px 15px 0; max-width: 90%; font-size: 14px; color: #856404;">
                        Lỗi kết nối mạng. Vui lòng liên hệ Hotline {{ $setting->phone1 ?? '0783.177.977' }} ạ.
                    </div>
                `;
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        };
    </script>
    
    @yield('scripts') 

</body>
</html>