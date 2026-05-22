$(document).ready(function () {

    //  CẤU HÌNH ĐƯỜNG DẪN GỐC (ĐÃ SỬA CHO HOSTING) 
    // Đã xóa '/shop-giay/public' vì trên hosting tên miền trỏ thẳng vào web
    var BASE_URL = '';
    //  ------------------------------------------ 

    // ==========================================
    // 1. CÁC HÀM HỖ TRỢ
    // ==========================================
    function formatCurrency(number) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);
    }

    function recalculateCart() {
        var total = 0;
        $('.select-item:checked').each(function () {
            var price = $(this).data('price');
            var qty = $(this).closest('.product_data').find('.qty-input').val();
            total += price * qty;
        });
        $('#total-price-display').text(formatCurrency(total));
    }

    // ==========================================
    // 2. XỬ LÝ TĂNG/GIẢM SỐ LƯỢNG
    // ==========================================
    $(document).off('click', '.increment-btn').on('click', '.increment-btn', function (e) {
        e.preventDefault();
        var $input = $(this).closest('.product_data').find('.qty-input');
        var value = parseInt($input.val(), 10);
        value = isNaN(value) ? 0 : value;
        if (value < 10) {
            value++;
            $input.val(value);
            recalculateCart();
        }
    });

    $(document).off('click', '.decrement-btn').on('click', '.decrement-btn', function (e) {
        e.preventDefault();
        var $input = $(this).closest('.product_data').find('.qty-input');
        var value = parseInt($input.val(), 10);
        value = isNaN(value) ? 0 : value;
        if (value > 1) {
            value--;
            $input.val(value);
            recalculateCart();
        }
    });

    // ==========================================
    // 3. XỬ LÝ CHECKBOX VÀ CHỌN TẤT CẢ
    // ==========================================
    $(document).off('change', '.select-item').on('change', '.select-item', function () {
        recalculateCart();
        if ($(this).prop('checked') == false) {
            $('#selectAll').prop('checked', false);
        }
        if ($('.select-item:checked').length == $('.select-item').length) {
            $('#selectAll').prop('checked', true);
        }
    });

    $(document).off('click', '#selectAll').on('click', '#selectAll', function () {
        if ($(this).is(':checked')) {
            $('.select-item').prop('checked', true);
        } else {
            $('.select-item').prop('checked', false);
        }
        recalculateCart();
    });

    // ==========================================
    // 4. XỬ LÝ XÓA SẢN PHẨM
    // ==========================================
    $(document).off('click', '.delete-cart-item').on('click', '.delete-cart-item', function (e) {
        e.preventDefault();
        var $btn = $(this);
        $btn.prop('disabled', true);

        var product_id = $btn.closest('.product_data').find('.prod_id').val();

        if (!confirm("Bạn có chắc muốn xóa sản phẩm này không?")) {
            $btn.prop('disabled', false);
            return;
        }

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $.ajax({
            method: "POST",
            url: BASE_URL + "/delete-cart-item",
            data: { 'product_id': product_id },
            success: function (response) {
                alert(response.status);
                window.location.reload();
            },
            error: function () {
                alert("Có lỗi xảy ra khi xóa!");
                $btn.prop('disabled', false);
            }
        });
    });

    // ==========================================
    // 5. XỬ LÝ CHUYỂN HƯỚNG THANH TOÁN
    // ==========================================
    $(document).off('click', '.checkOutBtn').on('click', '.checkOutBtn', function (e) {
        e.preventDefault();
        var selectedProducts = [];
        $('.select-item:checked').each(function () {
            selectedProducts.push($(this).val());
        });

        if (selectedProducts.length === 0) {
            alert("Vui lòng tích chọn ít nhất 1 sản phẩm để thanh toán!");
            return;
        }
        // Chuyển hướng kèm theo BASE_URL (Giờ là rỗng nên sẽ bay thẳng sang /checkout)
        window.location.href = BASE_URL + "/checkout?items=" + selectedProducts.join(',');
    });

    // ==========================================
    // 6. XỬ LÝ ÁP DỤNG MÃ GIẢM GIÁ (COUPON)
    // ==========================================
    $(document).off('click', '.apply-coupon-btn').on('click', '.apply-coupon-btn', function (e) {
        e.preventDefault();

        var coupon_code = $('#coupon_code').val();
        var order_total = $('#raw_total_price').length ? $('#raw_total_price').val() : 0;

        if ($.trim(coupon_code).length == 0) {
            $('#coupon_message').html('<span class="text-danger">Vui lòng nhập mã giảm giá!</span>');
            return;
        }

        $.ajax({
            method: "POST",
            url: BASE_URL + "/check-coupon-code",
            data: {
                'coupon_code': coupon_code,
                'order_total': order_total,
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.status == 200 || response.status == 'success') {
                    $('#coupon_message').html('<span class="text-success"><i class="fa fa-check"></i> ' + response.message + '</span>');

                    $('.discount-price').text(response.discount_amount_text);
                    $('.grand-total').text(response.final_total_text);

                } else {
                    $('#coupon_message').html('<span class="text-danger"><i class="fa fa-times"></i> ' + response.message + '</span>');

                    $('.discount-price').text('- 0đ');
                    var formattedTotal = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(order_total).replace('₫', 'đ');
                    $('.grand-total').text(formattedTotal);
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    });

});