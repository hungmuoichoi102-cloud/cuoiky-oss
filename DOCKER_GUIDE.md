# Docker Deployment Guide

## Tổng Quan

Dự án todo-list đã được cấu hình để chạy trên Docker với Docker Compose. Cả frontend (React + Nginx) và backend (PHP + Apache) được containerized.

## Yêu Cầu

- Docker Desktop (hoặc Docker Engine + Docker Compose)
- Cài đặt hợp lệ: [https://docs.docker.com/get-docker/](https://docs.docker.com/get-docker/)

## Cấu Trúc Docker

### Backend Container

- **Image Base:** `php:8.1-apache`
- **Cổng:** 8000:80
- **Database:** SQLite (volume: `./backend/database.sqlite`)
- **Modules:** PDO SQLite, Apache Rewrite

### Frontend Container

- **Image Base:** `node:18-alpine` (build) → `nginx:alpine` (runtime)
- **Cổng:** 80:80
- **Build Process:** Vite production build
- **Proxy:** Điều hướng `/api/` → backend

## Hướng Dẫn Sử Dụng

### 1. Build và Start Containers

```bash
# Build images
docker-compose build

# Khởi động toàn bộ stack
docker-compose up -d
```

### 2. Kiểm Tra Trạng Thái

```bash
# Xem danh sách containers đang chạy
docker-compose ps

# Xem logs từ tất cả services
docker-compose logs -f

# Xem logs từ backend
docker-compose logs -f backend

# Xem logs từ frontend
docker-compose logs -f frontend
```

### 3. Dừng Containers

```bash
# Dừng nhưng giữ lại containers
docker-compose stop

# Dừng và xóa containers
docker-compose down

# Dừng và xóa containers + volumes
docker-compose down -v
```

### 4. Rebuild Images

```bash
# Rebuild tất cả images (sau khi sửa code)
docker-compose build --no-cache

# Rebuild và khởi động lại
docker-compose up --build -d
```

## Truy Cập Ứng Dụng

- **Frontend:** http://localhost (cổng 80)
- **Backend API:** http://localhost:8000 (cổng 8000)
  - GET /api/todos
  - POST /api/todos
  - PUT /api/todos/{id}
  - DELETE /api/todos/{id}
  - PATCH /api/todos/{id}/toggle

## Quản Lý Database

Database SQLite được lưu tại `backend/database.sqlite` và được mount như một volume, vì vậy dữ liệu sẽ được giữ lại ngay cả sau khi dừng containers.

### Reset Database

```bash
# Xóa container và volume
docker-compose down -v

# Rebuild và restart
docker-compose up --build -d
```

## Phát Triển (Development Mode)

Nếu muốn chạy ở chế độ phát triển với hot reload:

```bash
# Dừng containers
docker-compose down

# Chạy backend
cd backend
php -S localhost:8000 -t public

# Chạy frontend (terminal khác)
cd frontend
npm run dev
```

## Troubleshooting

### Backend không khởi động

```bash
docker-compose logs backend
# Kiểm tra lỗi từ PHP/Apache
```

### Frontend không kết nối được tới API

```bash
# Kiểm tra nginx configuration
docker-compose exec frontend cat /etc/nginx/conf.d/default.conf

# Kiểm tra kết nối mạng
docker-compose exec frontend ping backend
```

### Database không được khởi tạo

```bash
# Chạy setup-db.php thủ công
docker-compose exec backend php setup-db.php
```

### Xóa hết để reset

```bash
docker-compose down -v
docker system prune -a
docker-compose up --build -d
```

## Cấu Hình Tùy Chỉnh

### Thay Đổi Cổng Backend

Sửa `docker-compose.yml`:

```yaml
backend:
  ports:
    - "9000:80" # Thay 8000 thành 9000
```

### Thay Đổi Cổng Frontend

Sửa `docker-compose.yml`:

```yaml
frontend:
  ports:
    - "3000:80" # Thay 80 thành 3000
```

## Network Giữa Containers

Các containers giao tiếp qua network `todo-network`:

- Backend có thể truy cập từ: `http://backend:80`
- Frontend proxy /api requests tới backend

## Production Deployment

### Sử Dụng Docker Hub

```bash
# Build và tag image
docker build -t yourusername/todo-backend:latest ./backend
docker build -t yourusername/todo-frontend:latest ./frontend

# Push lên Docker Hub
docker push yourusername/todo-backend:latest
docker push yourusername/todo-frontend:latest
```

### Deploy với Docker Compose trên Server

```bash
# SSH vào server
ssh user@your-server

# Clone repo
git clone <your-repo-url>
cd cuoiky-oss

# Pull latest images
docker-compose pull

# Start containers
docker-compose up -d
```

## Lợi Ích Của Docker

✅ Environment consistency (Dev ≠ Prod)
✅ Dễ dàng scale (chạy nhiều instances)
✅ Isolation (các service tách biệt)
✅ Dễ dàng backup/restore (volumes)
✅ CI/CD integration (GitHub Actions, GitLab CI, etc.)

## Thêm Thông Tin

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Reference](https://docs.docker.com/compose/compose-file/)
- [Best Practices](https://docs.docker.com/develop/dev-best-practices/)
