CHú ý: chỉ được thêm, sửa code ở thư mục packages\Zplus\vipos



# POS Package Development Checklist

## 0. Package Setup & Integration
- [x] Tạo ServiceProvider cho ViPOS package
- [x] Đăng ký menu ViPOS trong sidebar admin
- [x] Tạo routes cơ bản cho admin POS
- [x] Tạo controllers cơ bản
- [x] Tạo views cơ bản cho admin interface
- [x] Cấu hình autoload và đăng ký package

## 1. Database Setup
- [x] Tạo migration cho bảng `pos_sessions`
- [x] Tạo migration cho bảng `pos_transactions`
- [x] Tạo seeder cho dữ liệu test POS

## 2. Backend Development

### Models
- [ ] Tạo model `PosSession` với relationships
- [ ] Tạo model `PosTransaction` với relationships
- [ ] Thêm relationships vào model `Sale` (hasMany pos_transactions)
- [ ] Thêm relationships vào model `User` (hasMany pos_sessions)

### Controllers
- [x] Tạo `PosController`
  - [x] Implement method `index()`
  - [ ] Implement method `openSession()`
  - [ ] Implement method `closeSession()`
  - [ ] Implement method `getCurrentSession()`
- [x] Tạo `PosTransactionController`
  - [ ] Implement method `checkout()`
  - [ ] Implement method `getProducts()`
  - [ ] Implement method `searchCustomers()`
  - [ ] Implement method `quickCreateCustomer()`

### API & Routes
- [x] Thêm routes cho POS trong `routes/web.php` hoặc `routes/api.php`
- [ ] Tạo middleware kiểm tra POS session
- [ ] Tạo API resources cho POS data

### Services
- [ ] Tạo `PosService` để xử lý business logic
  - [ ] Method xử lý checkout
  - [ ] Method quản lý session
  - [ ] Method tính toán giảm giá, thuế
- [ ] Tạo `InventoryService` để cập nhật tồn kho

## 3. Frontend Development

### Vue Components
- [ ] Tạo component `PosLayout.vue`
- [ ] Tạo component `PosHeader.vue`
  - [ ] Nút back to admin
  - [ ] Hiển thị thông tin session
- [ ] Tạo component `CartPanel.vue`
  - [ ] Component `ProductSearch.vue`
  - [ ] Component `CartItems.vue`
  - [ ] Component `CartSummary.vue`
- [ ] Tạo component `ProductPanel.vue`
  - [ ] Component `CustomerSearch.vue`
  - [ ] Component `CategoryFilter.vue`
  - [ ] Component `ProductGrid.vue`
- [ ] Tạo component `CheckoutModal.vue`

### Vuex Store
- [ ] Tạo module `pos` trong Vuex
  - [ ] State: cart, customer, products, session
  - [ ] Actions: addToCart, removeFromCart, updateQuantity
  - [ ] Actions: setCustomer, clearCart, processCheckout
  - [ ] Getters: cartTotal, cartItemsCount, appliedDiscount

### Styling
- [ ] CSS cho full-screen layout
- [ ] Responsive design cho tablet
- [ ] Dark mode support (optional)

## 4. Features Implementation

### Cart Management
- [ ] Thêm sản phẩm vào giỏ
- [ ] Cập nhật số lượng
- [ ] Xóa sản phẩm khỏi giỏ
- [ ] Tính toán tự động subtotal

### Discount & Tax
- [ ] Giảm giá theo %
- [ ] Giảm giá theo số tiền
- [ ] Tính thuế VAT
- [ ] Hiển thị total

### Customer Management
- [ ] Search customer với autocomplete
- [ ] Quick create customer form
- [ ] Display selected customer info

### Product Management
- [ ] Load products với pagination
- [ ] Filter by category
- [ ] Search products
- [ ] Show inventory status

### Checkout Process
- [ ] Validate cart không rỗng
- [ ] Chọn payment method
- [ ] Process payment
- [ ] Generate receipt

## 5. Integration

### Sale Package Integration
- [ ] Tạo Sale record khi checkout
- [ ] Tạo SaleItems từ cart
- [ ] Link với pos_transactions

### Inventory Update
- [ ] Giảm tồn kho sau khi checkout
- [ ] Check tồn kho trước khi thêm vào giỏ

## 6. Security & Permissions

### Permissions Setup
- [ ] Tạo permission `pos.access`
- [ ] Tạo permission `pos.manage_sessions`
- [ ] Tạo permission `pos.process_payment`
- [ ] Assign permissions cho roles

### Validation
- [ ] Validate input data
- [ ] Check user permissions
- [ ] Validate session status

## 7. Testing

### Unit Tests
- [ ] Test PosService methods
- [ ] Test models và relationships
- [ ] Test API endpoints

### Feature Tests
- [ ] Test checkout flow
- [ ] Test session management
- [ ] Test permission checks

### Frontend Tests
- [ ] Test Vue components
- [ ] Test Vuex actions
- [ ] Test user interactions

## 8. Performance Optimization

- [ ] Implement product caching
- [ ] Optimize database queries
- [ ] Lazy load images
- [ ] Implement local storage for cart

## 9. Additional Features

### Keyboard Shortcuts
- [ ] Implement F2 - Focus product search
- [ ] Implement F3 - Focus customer search
- [ ] Implement F9 - Open checkout
- [ ] Implement ESC - Cancel action
- [ ] Implement Ctrl+N - New customer
- [ ] Implement Ctrl+P - Print receipt

### Reports
- [ ] Daily sales report
- [ ] Session summary report
- [ ] Product sales report

## 10. Documentation

- [ ] API documentation
- [ ] User manual
- [ ] Installation guide
- [ ] Troubleshooting guide

## 11. Deployment

- [ ] Build production assets
- [ ] Database migration on production
- [ ] Permission seeding
- [ ] User training

## Priority Order

1. **Phase 1 - Core** (Week 1)
   - Database setup
   - Basic models & controllers
   - Basic UI layout

2. **Phase 2 - Features** (Week 2)
   - Cart functionality
   - Product display
   - Customer search

3. **Phase 3 - Checkout** (Week 3)
   - Payment processing
   - Receipt generation
   - Inventory update

4. **Phase 4 - Polish** (Week 4)
   - Performance optimization
   - Testing
   - Documentation