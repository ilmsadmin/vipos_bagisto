<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction ?? 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ViPOS - Hệ thống bán hàng</title>    <!-- External CSS -->
    <link rel="stylesheet" href="{{ asset('packages/Zplus/vipos/assets/css/pos-fullscreen.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/Zplus/vipos/assets/css/hover-add-effect.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/Zplus/vipos/assets/css/cart-notification.css') }}">
</head>
<body>
    <div class="pos-container">        <!-- Header liền mạch trên cùng -->
        <header class="pos-header">            <div class="header-left">
                <div class="header-logo">
                    <div class="header-icon">P</div>
                    ViPOS
                </div>
                <div class="header-info">Hệ thống bán hàng hiện đại</div>                <div class="shift-status" id="shift-status">
                    <span class="shift-indicator">🔴</span>
                    <span class="shift-text">Ca đóng</span>
                </div>
                <a href="{{ route('admin.vipos.dashboard') }}" class="back-to-dashboard ml-3 px-3 py-1 rounded bg-gray-700 text-white text-sm hover:bg-gray-800 transition-colors">
                    <span>← Quay lại dashboard</span>
                </a>
            </div>
            
            <div class="header-right">
                <div class="header-stats">
                    <div class="stat-item">
                        <span class="stat-value" id="daily-sales">0</span>
                        <span>Đơn ca này</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value" id="daily-revenue">0₫</span>
                        <span>Doanh thu ca</span>
                    </div>
                </div>
                
                <div class="header-actions">
                    <button class="shift-btn" id="shift-btn" onclick="toggleShift()">
                        <span id="shift-btn-text">Mở ca</span>
                    </button>
                </div>
                
                <div class="header-user">
                    <div class="user-avatar">A</div>
                    <div>
                        <div style="font-weight: 600;">Admin</div>
                        <div style="font-size: 0.8rem; opacity: 0.8;" id="current-time"></div>
                    </div>
                </div>
            </div>        </header>

        <!-- No Active Shift Screen -->
        <div class="no-shift-screen" id="no-shift-screen">

            <h2 class="no-shift-title">Chưa có ca làm việc</h2>
            <p class="no-shift-message">
                Bạn cần mở ca làm việc trước khi có thể sử dụng hệ thống POS. 
                Vui lòng nhấn nút "Mở ca" ở góc trên phải để bắt đầu.
            </p>
            <button class="no-shift-action" onclick="toggleShift()">
                🚀 Mở ca làm việc
            </button>
            
            <div class="no-shift-features">
                <div class="no-shift-feature">
                    <div class="feature-icon">💰</div>
                    <div class="feature-title">Quản lý tiền mặt</div>
                    <div class="feature-description">Theo dõi số tiền đầu ca và cuối ca một cách chính xác</div>
                </div>
                <div class="no-shift-feature">
                    <div class="feature-icon">📊</div>
                    <div class="feature-title">Báo cáo ca làm việc</div>
                    <div class="feature-description">Xem báo cáo doanh thu và thống kê chi tiết theo ca</div>
                </div>
                <div class="no-shift-feature">
                    <div class="feature-icon">🛡️</div>
                    <div class="feature-title">Bảo mật dữ liệu</div>
                    <div class="feature-description">Dữ liệu được lưu trữ an toàn theo từng ca làm việc</div>
                </div>
            </div>
        </div>

        <!-- Main content area - chia đôi màn hình -->
        <div class="pos-main" id="pos-main" style="display: none;">
            <!-- Left side - Products -->
            <div class="pos-left">
                <!-- Search Section -->
                <div class="search-section">
                    <div class="search-container">
                        <div class="search-icon">🔍</div>
                        <input type="text" class="search-input" placeholder="Tìm kiếm sản phẩm theo tên, mã, barcode..." id="product-search">
                    </div>
                </div>

                <!-- Categories Section -->
                <div class="categories-section">
                    <div class="categories-scroll">
                        <button class="category-btn active" data-category="all">🛍️ Tất cả</button>
                        <button class="category-btn" data-category="food">🍔 Thức ăn</button>
                        <button class="category-btn" data-category="drink">🥤 Đồ uống</button>
                        <button class="category-btn" data-category="snack">🍿 Snack</button>
                        <button class="category-btn" data-category="electronics">📱 Điện tử</button>
                        <button class="category-btn" data-category="fashion">👕 Thời trang</button>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="products-section">
                    <div class="products-grid" id="products-grid">
                        <!-- Sample Products -->                        <div class="product-card" data-id="1" data-category="drink">
                            <div class="product-image">☕</div>
                            <div class="product-name">Cà phê đen đá</div>
                            <div class="product-price">25,000₫</div>
                            <div class="product-overlay">
                                <button class="add-to-cart-btn" onclick="addToCart(1, 'Cà phê đen đá', 25000)">+</button>
                            </div>
                        </div>
                          <div class="product-card" data-id="2" data-category="food">
                            <div class="product-image">🍞</div>
                            <div class="product-name">Bánh mì thịt nướng</div>
                            <div class="product-price">20,000₫</div>
                            <div class="product-overlay">
                                <button class="add-to-cart-btn" onclick="addToCart(2, 'Bánh mì thịt nướng', 20000)">+</button>
                            </div>
                        </div>
                        
                        <div class="product-card" data-id="3" data-category="drink">
                            <div class="product-image">🥤</div>
                            <div class="product-name">Nước cam tươi</div>
                            <div class="product-price">15,000₫</div>
                            <div class="product-overlay">
                                <button class="add-to-cart-btn" onclick="addToCart(3, 'Nước cam tươi', 15000)">+</button>
                            </div>
                        </div>
                        
                        <div class="product-card" data-id="4" data-category="snack">
                            <div class="product-image">🍿</div>
                            <div class="product-name">Bỏng ngô vị bơ</div>
                            <div class="product-price">12,000₫</div>
                            <div class="product-overlay">
                                <button class="add-to-cart-btn" onclick="addToCart(4, 'Bỏng ngô vị bơ', 12000)">+</button>
                            </div>
                        </div>
                        
                        <div class="product-card" data-id="5" data-category="food">
                            <div class="product-image">🍜</div>
                            <div class="product-name">Phở bò tái chín</div>
                            <div class="product-price">45,000₫</div>
                            <div class="product-overlay">
                                <button class="add-to-cart-btn" onclick="addToCart(5, 'Phở bò tái chín', 45000)">+</button>
                            </div>
                        </div>
                          <div class="product-card" data-id="6" data-category="drink">
                            <div class="product-image">🧋</div>
                            <div class="product-name">Trà sữa trân châu</div>
                            <div class="product-price">30,000₫</div>
                            <div class="product-overlay">
                                <button class="add-to-cart-btn" onclick="addToCart(6, 'Trà sữa trân châu', 30000)">+</button>
                            </div>
                        </div>
                        
                        <div class="product-card" data-id="7" data-category="electronics">
                            <div class="product-image">🎧</div>
                            <div class="product-name">Tai nghe Bluetooth</div>
                            <div class="product-price">299,000₫</div>
                            <div class="product-overlay">
                                <button class="add-to-cart-btn" onclick="addToCart(7, 'Tai nghe Bluetooth', 299000)">+</button>
                            </div>
                        </div>
                        
                        <div class="product-card" data-id="8" data-category="fashion">
                            <div class="product-image">👕</div>
                            <div class="product-name">Áo thun cotton</div>
                            <div class="product-price">199,000₫</div>
                            <div class="product-overlay">
                                <button class="add-to-cart-btn" onclick="addToCart(8, 'Áo thun cotton', 199000)">+</button>
                            </div>
                        </div>
                        
                        <div class="product-card" data-id="9" data-category="food">
                            <div class="product-image">🍕</div>
                            <div class="product-name">Pizza hải sản</div>
                            <div class="product-price">120,000₫</div>
                            <div class="product-overlay">
                                <button class="add-to-cart-btn" onclick="addToCart(9, 'Pizza hải sản', 120000)">+</button>
                            </div>
                        </div>
                        
                        <div class="product-card" data-id="10" data-category="snack">
                            <div class="product-image">🥨</div>
                            <div class="product-name">Bánh quy socola</div>
                            <div class="product-price">18,000₫</div>
                            <div class="product-overlay">
                                <button class="add-to-cart-btn" onclick="addToCart(10, 'Bánh quy socola', 18000)">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side - Cart -->
            <div class="pos-right">
                <!-- Cart Header -->
                <div class="cart-header">
                    <div class="cart-title">
                        🛒 Giỏ hàng
                    </div>
                    <div class="cart-subtitle" id="cart-count">0 sản phẩm</div>
                </div>

                <!-- Cart Items -->
                <div class="cart-items" id="cart-items">
                    <div class="empty-cart">
                        <div class="empty-cart-icon">🛒</div>
                        <div class="empty-cart-text">Giỏ hàng trống</div>
                        <div class="empty-cart-subtitle">Chọn sản phẩm để bắt đầu</div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">                    <!-- Customer Section -->
                    <div class="customer-section">
                        <div class="customer-header">
                            <span class="customer-title">👤 Khách hàng</span>
                            <button class="customer-new-btn" onclick="showNewCustomerForm()">+ Mới</button>
                        </div>
                        <div class="customer-search-container">
                            <input type="text" class="customer-search" placeholder="Tìm khách hàng (SĐT, tên, email)" id="customer-search" oninput="searchCustomers(this.value)">
                            <div class="customer-suggestions" id="customer-suggestions" style="display: none;"></div>
                        </div>
                        <div class="selected-customer" id="selected-customer" style="display: none;">
                            <div class="customer-info">
                                <div class="customer-name" id="customer-name-display"></div>
                                <div class="customer-phone" id="customer-phone-display"></div>
                            </div>
                            <button class="customer-remove" onclick="clearCustomer()">×</button>
                        </div>
                    </div>

                    <!-- Discount Section -->
                    <div class="discount-section">
                        <div class="discount-header">
                            <span class="discount-title">💰 Giảm giá</span>
                            <button class="discount-toggle" onclick="toggleDiscount()">Thêm</button>
                        </div>
                        <div class="discount-controls" id="discount-controls">
                            <select class="discount-type" id="discount-type">
                                <option value="percent">%</option>
                                <option value="amount">VND</option>
                            </select>
                            <input type="number" class="discount-value" id="discount-value" placeholder="0" min="0">
                            <button class="discount-apply" onclick="applyDiscount()">Áp dụng</button>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="summary-row">
                        <span>Tạm tính:</span>
                        <span id="subtotal">0₫</span>
                    </div>
                    <div class="summary-row" id="discount-row" style="display: none; color: #059669;">
                        <span>Giảm giá:</span>
                        <span id="discount-amount">-0₫</span>
                    </div>
                    <div class="summary-row">
                        <span>Thuế (10%):</span>
                        <span id="tax">0₫</span>
                    </div>
                    <div class="summary-total">
                        <span>Tổng cộng:</span>
                        <span id="total">0₫</span>
                    </div>

                    <!-- Checkout Buttons -->
                    <div class="checkout-section" style="margin-top: 2rem;">
                        <button class="checkout-btn btn-secondary" onclick="clearCart()">🗑️ Xóa tất cả</button>
                        <button class="checkout-btn btn-primary" onclick="checkout()" id="checkout-btn" disabled>💳 Thanh toán</button>
                    </div>
                </div>
            </div>        </div>
    </div>

    <!-- Modal for new customer -->
    <div class="modal-overlay" id="customer-modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tạo khách hàng mới</h3>
                <button class="modal-close" onclick="hideNewCustomerForm()">×</button>
            </div>
            <div class="modal-body">
                <form id="new-customer-form">
                    <div class="form-group">
                        <label>Họ và tên *</label>
                        <input type="text" name="first_name" required placeholder="Nhập họ tên" class="form-input">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại *</label>
                        <input type="tel" name="phone" required placeholder="Nhập số điện thoại" class="form-input">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Nhập email (không bắt buộc)" class="form-input">
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <textarea name="address" placeholder="Nhập địa chỉ (không bắt buộc)" class="form-textarea"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="hideNewCustomerForm()">Hủy</button>
                <button class="btn-primary" onclick="createNewCustomer()">Tạo khách hàng</button>
            </div>
        </div>
    </div>

    <!-- Modal for shift management -->
    <div class="modal-overlay" id="shift-modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="shift-modal-title">Mở ca làm việc</h3>
                <button class="modal-close" onclick="hideShiftModal()">×</button>
            </div>
            <div class="modal-body">
                <div id="shift-open-form">
                    <div class="form-group">
                        <label>Tiền mặt đầu ca *</label>
                        <input type="number" id="opening-cash" required placeholder="Nhập số tiền mặt đầu ca" class="form-input" min="0" step="1000">
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea id="opening-note" placeholder="Ghi chú mở ca (không bắt buộc)" class="form-textarea"></textarea>
                    </div>
                </div>
                <div id="shift-close-form" style="display: none;">
                    <div class="shift-summary">
                        <div class="summary-item">
                            <span>Thời gian mở ca:</span>
                            <span id="shift-start-time">--</span>
                        </div>
                        <div class="summary-item">
                            <span>Tiền mặt đầu ca:</span>
                            <span id="shift-opening-cash">--</span>
                        </div>
                        <div class="summary-item">
                            <span>Tổng số đơn:</span>
                            <span id="shift-total-orders">--</span>
                        </div>
                        <div class="summary-item">
                            <span>Doanh thu ca:</span>
                            <span id="shift-total-revenue">--</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tiền mặt cuối ca *</label>
                        <input type="number" id="closing-cash" required placeholder="Nhập số tiền mặt cuối ca" class="form-input" min="0" step="1000">
                    </div>
                    <div class="form-group">
                        <label>Ghi chú đóng ca</label>
                        <textarea id="closing-note" placeholder="Ghi chú đóng ca (không bắt buộc)" class="form-textarea"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="hideShiftModal()">Hủy</button>
                <button class="btn-primary" id="shift-action-btn" onclick="processShiftAction()">Mở ca</button>
            </div>
        </div>    </div>

    <!-- Cart Notification -->
    <div class="add-to-cart-notification" id="cart-notification">
        <div class="notification-icon">✓</div>
        <div class="notification-content">
            <div class="notification-title">Đã thêm vào giỏ hàng</div>
            <div class="notification-message" id="notification-product-info"></div>
        </div>
    </div>

    <!-- External JavaScript -->
    <script src="{{ asset('packages/Zplus/vipos/assets/js/pos-fullscreen.js') }}"></script>
</body>
</html>
