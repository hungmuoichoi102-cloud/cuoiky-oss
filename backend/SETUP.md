# Backend Setup Guide - Laravel Todo API

## 📋 Mục đích

File này chứa hướng dẫn chi tiết để cài đặt và chạy backend Laravel.

## ⚙️ Các bước cài đặt

### Bước 1: Cài đặt Composer Dependencies

```bash
cd backend
composer install
```

Điều này sẽ cài đặt tất cả các package Laravel cần thiết.

### Bước 2: Sao chép và Cấu hình .env

```bash
cp .env.example .env
```

File `.env` chứa các biến môi trường như:

- `APP_NAME`: Tên ứng dụng
- `APP_ENV`: Môi trường (local/production)
- `APP_DEBUG`: Bật/tắt debug mode
- `DB_CONNECTION`: Loại database (sqlite)
- `DB_DATABASE`: Đường dẫn đến SQLite file

### Bước 3: Tạo SQLite Database

```bash
# Trên Windows (PowerShell)
New-Item -ItemType File -Path "database.sqlite"

# Hoặc trên Git Bash/Linux/Mac
touch database.sqlite
```

### Bước 4: Tạo Application Key

```bash
php artisan key:generate
```

Lệnh này tạo `APP_KEY` trong file `.env` dùng cho encryption.

### Bước 5: Chạy Migrations

```bash
php artisan migrate
```

Điều này tạo bảng `todos` và các bảng khác trong database.

### Bước 6: Khởi động Development Server

```bash
php artisan serve
```

Server sẽ khởi động tại `http://localhost:8000`

## 📚 Cấu trúc Code

### Models (`app/Models/Todo.php`)

Đại diện cho bảng `todos` trong database.

**Attributes:**

- `id`: ID duy nhất
- `title`: Tiêu đề (bắt buộc)
- `description`: Mô tả (tùy chọn)
- `is_completed`: Trạng thái hoàn thành (mặc định: false)
- `created_at`: Thời gian tạo
- `updated_at`: Thời gian cập nhật lần cuối

### Controllers (`app/Http/Controllers/Api/TodoController.php`)

Xử lý tất cả logic cho API endpoints.

**Methods:**

- `index()`: Lấy tất cả todos
- `store()`: Tạo todo mới
- `show()`: Lấy một todo cụ thể
- `update()`: Cập nhật todo
- `destroy()`: Xóa todo
- `toggle()`: Chuyển đổi trạng thái hoàn thành

### Routes (`routes/api.php`)

Định nghĩa tất cả API endpoints:

```
GET    /api/todos              → Lấy tất cả todos
POST   /api/todos              → Tạo todo mới
GET    /api/todos/{id}         → Lấy todo theo ID
PUT    /api/todos/{id}         → Cập nhật todo
DELETE /api/todos/{id}         → Xóa todo
PATCH  /api/todos/{id}/toggle  → Chuyển đổi trạng thái
```

### Migrations (`database/migrations/`)

Định nghĩa cấu trúc database.

Bảng `todos`:

- id (Primary Key)
- title (String)
- description (Text, nullable)
- is_completed (Boolean)
- timestamps (created_at, updated_at)

## 🧪 Test API

Bạn có thể test API bằng các tools:

### Sử dụng cURL

```bash
# Lấy tất cả todos
curl http://localhost:8000/api/todos

# Tạo todo
curl -X POST http://localhost:8000/api/todos \
  -H "Content-Type: application/json" \
  -d '{"title":"Learn Laravel","description":"Complete Laravel tutorial"}'

# Cập nhật todo
curl -X PUT http://localhost:8000/api/todos/1 \
  -H "Content-Type: application/json" \
  -d '{"title":"Updated Title","is_completed":true}'

# Xóa todo
curl -X DELETE http://localhost:8000/api/todos/1

# Chuyển đổi trạng thái
curl -X PATCH http://localhost:8000/api/todos/1/toggle
```

### Sử dụng Postman

1. Mở Postman
2. Tạo request mới
3. Chọn HTTP method (GET, POST, PUT, DELETE, PATCH)
4. Nhập URL: `http://localhost:8000/api/todos`
5. Nếu là POST/PUT, chọn Tab "Body" → "raw" → "JSON"
6. Nhập JSON data
7. Click "Send"

### Sử dụng Visual Studio Code REST Client

Cài đặt extension "REST Client" và tạo file `.http`:

```http
### Get all todos
GET http://localhost:8000/api/todos

### Create new todo
POST http://localhost:8000/api/todos
Content-Type: application/json

{
  "title": "Learn Laravel",
  "description": "Complete Laravel tutorial"
}

### Update todo
PUT http://localhost:8000/api/todos/1
Content-Type: application/json

{
  "title": "Updated Title",
  "is_completed": true
}

### Delete todo
DELETE http://localhost:8000/api/todos/1

### Toggle todo
PATCH http://localhost:8000/api/todos/1/toggle
```

## ⚡ Useful Artisan Commands

```bash
# Xem danh sách routes
php artisan route:list

# Tạo một model mới
php artisan make:model ModelName

# Tạo một controller mới
php artisan make:controller ControllerName

# Tạo migration mới
php artisan make:migration migration_name

# Rollback migrations
php artisan migrate:rollback

# Reset database
php artisan migrate:reset

# Refresh database (rollback + migrate)
php artisan migrate:refresh

# Seed database
php artisan db:seed

# Xem danh sách migrations
php artisan migrate:status

# Tinker (interactive shell)
php artisan tinker
```

## 🔒 Validation Rules

Controller sử dụng Laravel validation:

**Tạo Todo:**

- `title`: required, string, max 255 ký tự
- `description`: nullable, string

**Cập nhật Todo:**

- `title`: sometimes, required, string, max 255 ký tự
- `description`: nullable, string
- `is_completed`: sometimes, boolean

## 📝 Logging

Logs được lưu trong `storage/logs/`

Để xem logs in real-time:

```bash
tail -f storage/logs/laravel.log
```

## 🐛 Debugging

### Bật Debug Mode

Sửa file `.env`:

```
APP_DEBUG=true
```

Lúc này Laravel sẽ hiển thị detailed error messages.

### Sử dụng Laravel Debugger

Thêm vào code:

```php
\Log::info('Your message here', ['variable' => $value]);
```

Xem logs:

```bash
tail -f storage/logs/laravel.log
```

## 📤 Deployment

### Prepare for Production

```bash
# Cài đặt dependencies
composer install --optimize-autoloader --no-dev

# Tạo key nếu chưa có
php artisan key:generate

# Xóa cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Setup database
php artisan migrate --force
```

## 📞 Support

Nếu gặp vấn đề:

1. Kiểm tra file `.env` có cấu hình đúng không
2. Chạy `php artisan migrate` lại
3. Xóa cache: `php artisan cache:clear`
4. Kiểm tra error logs trong `storage/logs/`
