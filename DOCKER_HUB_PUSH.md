# Push To Docker Hub Guide

## Chuẩn Bị

### 1. Đăng Ký Docker Hub

- Tạo tài khoản tại: https://hub.docker.com
- Ghi nhớ Docker Hub username

### 2. Đăng Nhập Docker CLI

```bash
docker login
# Nhập Docker Hub username và password
```

### 3. Cấu Hình Environment Variables

Sửa file `.env.docker` với Docker Hub username và repository của bạn:

```bash
DOCKER_REGISTRY=hungmuchoi
DOCKER_REPO=todolist
VERSION=latest
```

Hoặc set environment variables trước khi chạy:

**Windows PowerShell:**

```powershell
$env:DOCKER_REGISTRY = "hungmuchoi"
$env:DOCKER_REPO = "todolist"
$env:VERSION = "latest"
```

**Linux/Mac:**

```bash
export DOCKER_REGISTRY="hungmuchoi"
export DOCKER_REPO="todolist"
export VERSION="latest"
```

## Push Images Lên Docker Hub

### Option 1: Dùng docker-compose

Load từ `.env.docker`:

```bash
# Windows PowerShell
Get-Content .env.docker | ForEach-Object {
    if ($_) { $env:$($_ -split '=')[0] = $($_ -split '=')[1] }
}

# Build images
docker-compose build

# Push images
docker push ${env:DOCKER_REGISTRY}/todolist-backend:${env:VERSION}
docker push ${env:DOCKER_REGISTRY}/todolist-frontend:${env:VERSION}
```

### Option 2: Build và Push Riêng Lẻ

```bash
# Backend
docker build -t hungmuchoi/todolist-backend:latest ./backend
docker push hungmuchoi/todolist-backend:latest

# Frontend
docker build -t hungmuchoi/todolist-frontend:latest ./frontend
docker push hungmuchoi/todolist-frontend:latest
```

## Verify Trên Docker Hub

1. Vào https://hub.docker.com
2. Kiểm tra repositories:
   - `hungmuchoi/todolist-backend`
   - `hungmuchoi/todolist-frontend`

## Sử Dụng Images Từ Docker Hub

### Download và Chạy

```bash
# Cấu hình environment
export DOCKER_REGISTRY="hungmuchoi"
export DOCKER_REPO="todolist"
export VERSION="latest"

# Start từ Docker Hub images
docker-compose -f docker-compose.yml up -d
```

### Docker Run Trực Tiếp

```bash
# Run backend
docker run -d -p 8000:80 --name todo-backend \
  hungmuchoi/todolist-backend:latest

# Run frontend
docker run -d -p 80:80 --name todo-frontend \
  hungmuchoi/todolist-frontend:latest
```

## Version Management

### Tạo Multiple Versions

```bash
# Version 1.0.0
docker tag hungmuchoi/todolist-backend:latest \
  hungmuchoi/todolist-backend:1.0.0
docker push hungmuchoi/todolist-backend:1.0.0

# Version 1.0.1
docker tag hungmuchoi/todolist-backend:latest \
  hungmuchoi/todolist-backend:1.0.1
docker push hungmuchoi/todolist-backend:1.0.1
```

### Sử Dụng Specific Version

```yaml
# docker-compose.yml
services:
  backend:
    image: hungmuchoi/todolist-backend:1.0.0
  frontend:
    image: hungmuchoi/todolist-frontend:1.0.0
```

## Cleanup Local Images (Optional)

```bash
# Xóa local images sau khi push
docker image remove your-dockerhub-username/todo-backend:latest
docker image remove your-dockerhub-username/todo-frontend:latest

# Xóa tất cả unused images
docker image prune -a
```

## Troubleshooting

### Lỗi: "unauthorized: authentication required"

- Kiểm tra: `docker login` thành công?
- Kiểm tra credentials: `cat ~/.docker/config.json` (Linux/Mac)

### Lỗi: "name unknown"

- Kiểm tra image name đúng format: `username/repository:tag`
- Kiểm tra repository công khai trên Docker Hub

### Lỗi: "tag invalid"

- Tag phải lowercase
- Không có spaces hoặc ký tự đặc biệt (trừ `-` và `_`)

## GitHub Actions CI/CD (Advanced)

Để tự động push khi commit:

```yaml
# .github/workflows/docker-push.yml
name: Push to Docker Hub

on:
  push:
    branches:
      - main

jobs:
  push:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2

      - name: Login to Docker Hub
        uses: docker/login-action@v1
        with:
          username: ${{ secrets.DOCKER_HUB_USERNAME }}
          password: ${{ secrets.DOCKER_HUB_PASSWORD }}

      - name: Build and Push Backend
        uses: docker/build-push-action@v2
        with:
          context: ./backend
          push: true
          tags: hungmuchoi/todolist-backend:latest

      - name: Build and Push Frontend
        uses: docker/build-push-action@v2
        with:
          context: ./frontend
          push: true
          tags: hungmuchoi/todolist-frontend:latest
```

Lưu Docker Hub credentials vào GitHub Secrets:

- `DOCKER_HUB_USERNAME` (set giá trị: `hungmuchoi`)
- `DOCKER_HUB_PASSWORD`

## Chú Ý

- Đảm bảo Dockerfiles không chứa sensitive information (credentials, API keys)
- Kiểm tra `.dockerignore` để không include unnecessary files
- Sử dụng specific version tags thay vì `latest` cho production
- Xem xét sử dụng private repositories cho sensitive projects
