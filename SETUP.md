# Counter Realtime - Laravel Reverb

Giao diện đếm số realtime sử dụng Laravel Reverb.

## Yêu cầu

- PHP 8.1+
- Node.js 16+
- Laravel 11

## Cài đặt

1. **Cài đặt PHP dependencies:**
```bash
composer install
```

2. **Cài đặt Node dependencies:**
```bash
npm install
```

3. **Build assets:**
```bash
npm run build
```

Hoặc chạy dev mode (theo dõi thay đổi):
```bash
npm run dev
```

## Chạy ứng dụng

### Terminal 1 - Laravel Server:
```bash
php artisan serve
```

### Terminal 2 - Reverb WebSocket Server:
```bash
php artisan reverb:start
```

### Terminal 3 (tùy chọn) - Vite Dev Server:
```bash
npm run dev
```

## Sử dụng

1. Mở trình duyệt: http://localhost:8000
2. Bạn có thể:
   - Nhập số và click "Cộng" để cộng số đó
   - Click các nút nhanh: +1, +5, +10
   - Click "Đặt lại" để đặt lại bộ đếm về 0
3. Mở nhiều tab để xem cập nhật realtime giữa các tab

## Tính năng

✅ Giao diện đơn giản, đẹp mắt  
✅ Realtime updates qua WebSocket  
✅ Hỗ trợ multiple tabs/devices  
✅ Responsive design  
✅ Nút cộng nhanh  
✅ Chức năng đặt lại  

## Cấu trúc

- `app/Http/Controllers/CounterController.php` - Logic xử lý
- `app/Events/CounterUpdated.php` - Broadcast event
- `resources/views/counter.blade.php` - Giao diện
- `resources/js/counter.js` - JavaScript xử lý realtime
- `routes/api.php` - API endpoints
- `routes/web.php` - Web routes

## Ports

- Laravel: 8000 (mặc định)
- Reverb: 8080 (mặc định)
