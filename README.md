# Todo App - React + Laravel

Một ứng dụng web todo-list đơn giản sử dụng **React** cho frontend và **Laravel** cho backend API.

## 🎯 Tính năng

- ✅ Tạo, sửa, xóa todo
- ✅ Đánh dấu todo hoàn thành
- ✅ Lọc todo (Tất cả, Đang làm, Hoàn thành)
- ✅ Lưu trữ dữ liệu trên database
- ✅ Giao diện đẹp và responsive
- ✅ Xử lý lỗi thân thiện

## 📁 Cấu trúc dự án

```
cuoiky-oss/
├── backend/          # Laravel API
│   ├── app/
│   │   ├── Models/
│   │   │   └── Todo.php
│   │   └── Http/
│   │       └── Controllers/
│   │           └── Api/
│   │               └── TodoController.php
│   ├── routes/
│   │   └── api.php
│   ├── database/
│   │   └── migrations/
│   ├── config/
│   └── composer.json
│
└── frontend/         # React App
    ├── src/
    │   ├── components/
    │   │   ├── TodoList.jsx
    │   │   ├── TodoItem.jsx
    │   │   └── TodoForm.jsx
    │   ├── services/
    │   │   └── todoService.js
    │   ├── App.jsx
    │   └── main.jsx
    ├── package.json
    ├── vite.config.js
    └── index.html
```

## 🚀 Cài đặt và Chạy

### Yêu cầu

- **PHP** >= 8.1
- **Node.js** >= 16
- **Composer**
- **npm** hoặc **yarn**

### Backend Setup (Laravel)

#### 1. Cài đặt dependencies

```bash
cd backend
composer install
```

#### 2. Tạo file .env

```bash
cp .env.example .env
```

#### 3. Tạo database

```bash
touch database.sqlite
```

#### 4. Chạy migration

```bash
php artisan migrate
```

#### 5. Tạo application key

```bash
php artisan key:generate
```

#### 6. Khởi động server Laravel

```bash
php artisan serve
```

Server sẽ chạy trên: `http://localhost:8000`

### Frontend Setup (React)

#### 1. Cài đặt dependencies

```bash
cd frontend
npm install
```

#### 2. Tạo file .env

```bash
cp .env.example .env
```

#### 3. Chạy development server

```bash
npm run dev
```

Ứng dụng sẽ mở trên: `http://localhost:5173`

## 🔗 API Endpoints

Tất cả API endpoints bắt đầu với `/api/todos`

### GET `/todos`

Lấy danh sách tất cả todo

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "title": "Learn React",
      "description": "Complete React tutorial",
      "is_completed": false,
      "created_at": "2024-01-01T12:00:00Z",
      "updated_at": "2024-01-01T12:00:00Z"
    }
  ]
}
```

### POST `/todos`

Tạo todo mới

**Request body:**

```json
{
  "title": "Learn React",
  "description": "Complete React tutorial"
}
```

**Response:** HTTP 201 Created

### PUT `/todos/{id}`

Cập nhật todo

**Request body:**

```json
{
  "title": "Updated title",
  "description": "Updated description",
  "is_completed": true
}
```

### DELETE `/todos/{id}`

Xóa todo

**Response:** HTTP 200 OK

### PATCH `/todos/{id}/toggle`

Chuyển đổi trạng thái hoàn thành

**Response:** HTTP 200 OK

## 📝 Hướng dẫn Sử dụng

1. **Tạo Todo**: Nhập tiêu đề và mô tả (tùy chọn), sau đó click "Add Todo"

2. **Đánh dấu Hoàn thành**: Click vào checkbox bên cạnh todo

3. **Sửa Todo**: Click nút "✏️" để chỉnh sửa, sau đó click "Update Todo"

4. **Xóa Todo**: Click nút "🗑️" để xóa (sẽ yêu cầu xác nhận)

5. **Lọc Todo**: Sử dụng các nút lọc ở giữa trang:
   - All: Hiển thị tất cả todo
   - Pending: Hiển thị todo chưa hoàn thành
   - Completed: Hiển thị todo đã hoàn thành

## 🛠️ Công nghệ Sử dụng

### Backend

- **Laravel 10**: PHP web framework
- **SQLite**: Database
- **Eloquent ORM**: Data modeling

### Frontend

- **React 18**: JavaScript library
- **Vite**: Build tool
- **Axios**: HTTP client
- **CSS3**: Styling

## 📊 Database Schema

### Bảng: todos

| Column       | Type         | Nullable | Default        |
| ------------ | ------------ | -------- | -------------- |
| id           | BIGINT       | NO       | AUTO_INCREMENT |
| title        | VARCHAR(255) | NO       | -              |
| description  | TEXT         | YES      | NULL           |
| is_completed | BOOLEAN      | NO       | false          |
| created_at   | TIMESTAMP    | NO       | -              |
| updated_at   | TIMESTAMP    | NO       | -              |

## 🐛 Troubleshooting

### CORS Error

Nếu gặp lỗi CORS, đảm bảo:

- Laravel server đang chạy trên `http://localhost:8000`
- Frontend được cấu hình để gọi API từ đúng URL

### Database Not Found

Nếu database.sqlite không tồn tại:

```bash
cd backend
touch database.sqlite
php artisan migrate
```

### Port Already in Use

Nếu port 8000 đã được sử dụng:

```bash
php artisan serve --port=8001
```

Sau đó update `VITE_API_BASE_URL` trong frontend `.env`

## 📦 Build for Production

### Backend

```bash
cd backend
composer install --optimize-autoloader --no-dev
```

### Frontend

```bash
cd frontend
npm run build
```

Output sẽ nằm trong thư mục `frontend/dist/`

## 📄 License

MIT License

## 👨‍💻 Author

Created as a simple todo app for learning React + Laravel integration.
