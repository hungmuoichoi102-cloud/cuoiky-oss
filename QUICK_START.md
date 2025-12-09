# 🚀 Quick Start Guide - Todo App Setup

Hướng dẫn này sẽ giúp bạn khởi động ứng dụng Todo App nhanh chóng.

## Backend Setup (Laravel)

### 1. Mở Terminal mới và điều hướng đến backend:

```bash
cd backend
```

### 2. Cài đặt dependencies (nếu chưa làm):

```bash
composer install
```

### 3. Tạo database (nếu chưa làm):

```bash
php setup-db.php
```

### 4. Khởi động server:

```bash
php -S localhost:8000 -t public
```

**Backend sẽ chạy trên:** `http://localhost:8000`

Bạn sẽ thấy output như:

```
[date] PHP Development Server started at Mon Jan 01 12:00:00 2025
Local: http://localhost:8000
Press Ctrl-C to quit
```

### Test API:

```bash
curl http://localhost:8000/api/todos
```

---

## Frontend Setup (React)

### 1. Mở Terminal mới (giữ backend terminal mở) và điều hướng đến frontend:

```bash
cd frontend
```

### 2. Cài đặt dependencies (nếu chưa làm):

```bash
npm install
```

### 3. Khởi động dev server:

```bash
npm run dev
```

**Frontend sẽ chạy trên:** `http://localhost:5173`

Bạn sẽ thấy output như:

```
  ➜  Local:   http://localhost:5173/
  ➜  press h to show help
```

---

## ✅ Kiểm tra Kết nối

Mở browser và truy cập: `http://localhost:5173`

Nếu ứng dụng hoạt động, bạn sẽ thấy:

- ✅ Trang Todo List hiển thị
- ✅ Form để tạo todo
- ✅ Danh sách trống (hoặc có todos nếu đã tạo)

---

## 🆘 Troubleshooting

### Error: Port Already in Use

Nếu port 8000 đã được sử dụng:

```bash
php -S localhost:8001 -t public
```

Sau đó cập nhật `VITE_API_BASE_URL` trong `frontend/.env` thành `http://localhost:8001/api`

### Error: database.sqlite Not Found

```bash
php setup-db.php
```

### Error: Module not found (Frontend)

```bash
npm install
```

### Clear Cache (Backend):

```bash
php -r "is_dir('storage/framework/cache/data') && array_map('unlink', array_filter((array) glob('storage/framework/cache/data/*'), 'is_file'));"
```

---

## 📝 Tính năng Test

Sau khi cả backend và frontend chạy:

1. **Tạo Todo:**

   - Nhập tiêu đề vào form
   - Click "Add Todo"

2. **Hoàn thành Todo:**

   - Click vào checkbox

3. **Sửa Todo:**

   - Click nút ✏️
   - Thay đổi thông tin
   - Click "Update Todo"

4. **Xóa Todo:**

   - Click nút 🗑️
   - Xác nhận

5. **Lọc Todo:**
   - Click các nút filter: All, Pending, Completed

---

## 📚 Tài liệu Thêm

- **API Documentation:** Xem file `API.md`
- **Backend Setup Chi Tiết:** Xem file `backend/SETUP.md`
- **Frontend Setup Chi Tiết:** Xem file `frontend/SETUP.md`
- **README Tổng Quát:** Xem file `README.md`

---

## 🎯 Tóm Tắt Commands

**Terminal 1 (Backend):**

```bash
cd backend
php -S localhost:8000 -t public
```

**Terminal 2 (Frontend):**

```bash
cd frontend
npm install  # Chỉ lần đầu
npm run dev
```

Thế là xong! 🎉 Ứng dụng của bạn đã sẵn sàng sử dụng.
