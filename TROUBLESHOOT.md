# Troubleshooting WebSocket Connection Issues

## Lỗi kết nối WebSocket - Hướng dẫn khắc phục

### ✅ Danh sách kiểm tra

#### 1. **Đảm bảo Reverb Server đang chạy**

```bash
php artisan reverb:start
```

Bạn sẽ thấy:
```
INFO  Starting Reverb server...
INFO  Reverb server started successfully.
```

#### 2. **Kiểm tra Vite Dev Server**

```bash
npm run dev
```

Hoặc build assets:
```bash
npm run build
```

#### 3. **Xóa cache và rebuild**

```bash
php artisan config:cache
php artisan view:clear
php artisan cache:clear
npm run build
```

#### 4. **Kiểm tra biến môi trường**

Mở file `.env` và đảm bảo:
```dotenv
BROADCAST_CONNECTION=reverb
REVERB_HOST=127.0.0.1        # Hoặc IP của server nếu không local
REVERB_PORT=8080
REVERB_SCHEME=http            # http cho local, https cho production
VITE_REVERB_HOST="127.0.0.1"
VITE_REVERB_PORT="8080"
```

#### 5. **Kiểm tra browser console**

Mở DevTools (F12) → Console tab, tìm lỗi:

**Lỗi: "WebSocket connection failed"**
- Kiểm tra Reverb server đang chạy
- Kiểm tra port 8080 không bị block
- Kiểm tra firewall

**Lỗi: "Failed to load module"**
- Chạy: `npm run build`
- Clear cache: `php artisan view:clear`

**Lỗi về CORS hoặc WebSocket**
- Đảm bảo REVERB_HOST không phải "localhost"
- Sử dụng "127.0.0.1" hoặc IP thực của server

#### 6. **Kiểm tra Network tab**

Mở DevTools → Network tab → Filter: "ws" hoặc "wss"

**Bình thường:** Sẽ thấy WebSocket connection với status "101 Switching Protocols"

**Nếu Pending:** 
- Reverb server chưa chạy
- Port sai hoặc bị block

### 🔍 Lệnh kiểm tra chi tiết

```bash
# 1. Kiểm tra Reverb đang chạy
netstat -an | findstr 8080

# 2. Kiểm tra Laravel config
php artisan tinker
>>> config('reverb')

# 3. Test kết nối WebSocket
# Dùng tool như wscat: npm install -g wscat
wscat -c ws://127.0.0.1:8080
```

### 📋 Quy trình khởi động đầy đủ

**Terminal 1 - Laravel Server:**
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

**Terminal 2 - Reverb WebSocket:**
```bash
php artisan reverb:start --host=127.0.0.1 --port=8080
```

**Terminal 3 (tùy chọn) - Vite Dev:**
```bash
npm run dev
```

Sau đó truy cập: `http://127.0.0.1:8000`

### 🚨 Các vấn đề thường gặp

| Lỗi | Nguyên nhân | Giải pháp |
|-----|-----------|----------|
| WebSocket pending | Reverb server chưa chạy | Chạy `php artisan reverb:start` |
| CORS error | Host mismatch | Đổi localhost → 127.0.0.1 |
| Failed to connect | Port 8080 bị block | Kiểm tra firewall |
| Echo is undefined | Vite chưa build | Chạy `npm run build` |
| Event not broadcasting | Queue chưa xử lý | Kiểm tra QUEUE_CONNECTION |

### 💡 Production Setup

Nếu triển khai production:

1. Sử dụng domain thực thay vì IP
2. Cấu hình SSL/TLS
3. Sử dụng `REVERB_SCHEME=https`
4. Port: 443 cho wss

```dotenv
REVERB_HOST=your-domain.com
REVERB_PORT=443
REVERB_SCHEME=https
VITE_REVERB_HOST="your-domain.com"
VITE_REVERB_SCHEME="https"
```

### 📞 Kiểm tra cuối cùng

- [ ] Reverb server chạy (Terminal 2)
- [ ] Laravel server chạy (Terminal 1)  
- [ ] Assets được build (npm run build)
- [ ] .env có cấu hình Reverb đúng
- [ ] Browser DevTools → Network → WebSocket status "101"
- [ ] DevTools → Console không có error
