# Cách chạy code

## Chạy trên localhost

1. **Git clone code** về: `../xampp/htdocs/WEBSPINCM`  
   *(Đã đặt mặc định thư mục localhost là `WEBSPINCM`. Nếu bạn đặt tên khác, hãy vào `libs/Function.php` và sửa 3 function đầu tiên thành tên thư mục bạn đặt)*

2. **Tải database** từ thư mục `database`

3. **Tạo cơ sở dữ liệu** trên phpMyAdmin

4. **Chỉnh sửa file `.env`** với các thông tin kết nối đã tạo trước đó

5. **Chạy dự án** trên trình duyệt

---

## Chạy trên hosting

1. **Tải code**, sau đó upload code và database lên hosting

2. Với những hosting có PHP version thấp, **vào cPanel để nâng cấp lên PHP 8.2**

3. **Chỉnh sửa file `.env`** để cập nhật thông tin kết nối hosting

---

## Lấy thông tin dịch vụ

- **Thông tin nạp tiền (Auto Bank):** `domain/cron/bank.php`  
- **Thông tin dịch vụ API:** `domain/cron/spin.php`

---

## Tài khoản admin login website demo

- **Tài khoản:** `adminnguyen`  
- **Mật khẩu:** `nguyen36123`
