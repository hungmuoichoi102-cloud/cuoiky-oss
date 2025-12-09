# API Documentation - Todo API

## 📋 Base URL

```
http://localhost:8000/api
```

## 🔗 Endpoints

### 1. Get All Todos

**Endpoint:** `GET /todos`

**Description:** Lấy danh sách tất cả todos, sắp xếp theo thời gian tạo mới nhất

**Request:**

```http
GET http://localhost:8000/api/todos
Content-Type: application/json
```

**Response (200 OK):**

```json
{
  "data": [
    {
      "id": 1,
      "title": "Learn React",
      "description": "Complete React tutorial on YouTube",
      "is_completed": false,
      "created_at": "2024-01-01T10:30:00.000000Z",
      "updated_at": "2024-01-01T10:30:00.000000Z"
    },
    {
      "id": 2,
      "title": "Build a project",
      "description": null,
      "is_completed": true,
      "created_at": "2024-01-02T14:20:00.000000Z",
      "updated_at": "2024-01-02T15:45:00.000000Z"
    }
  ]
}
```

---

### 2. Create a Todo

**Endpoint:** `POST /todos`

**Description:** Tạo một todo mới

**Request:**

```http
POST http://localhost:8000/api/todos
Content-Type: application/json

{
  "title": "Learn Laravel",
  "description": "Study Laravel framework and build REST API"
}
```

**Validation Rules:**

- `title`: Bắt buộc, string, max 255 ký tự
- `description`: Tùy chọn, string

**Response (201 Created):**

```json
{
  "data": {
    "id": 3,
    "title": "Learn Laravel",
    "description": "Study Laravel framework and build REST API",
    "is_completed": false,
    "created_at": "2024-01-03T12:00:00.000000Z",
    "updated_at": "2024-01-03T12:00:00.000000Z"
  },
  "message": "Todo created successfully"
}
```

**Error Response (422 Unprocessable Entity):**

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": ["The title field is required."]
  }
}
```

---

### 3. Get a Single Todo

**Endpoint:** `GET /todos/{id}`

**Description:** Lấy chi tiết một todo cụ thể

**Request:**

```http
GET http://localhost:8000/api/todos/1
Content-Type: application/json
```

**Path Parameters:**

- `id` (required): ID của todo

**Response (200 OK):**

```json
{
  "data": {
    "id": 1,
    "title": "Learn React",
    "description": "Complete React tutorial on YouTube",
    "is_completed": false,
    "created_at": "2024-01-01T10:30:00.000000Z",
    "updated_at": "2024-01-01T10:30:00.000000Z"
  }
}
```

**Error Response (404 Not Found):**

```json
{
  "message": "No query results found for model [App\\Models\\Todo]."
}
```

---

### 4. Update a Todo

**Endpoint:** `PUT /todos/{id}`

**Description:** Cập nhật thông tin một todo

**Request:**

```http
PUT http://localhost:8000/api/todos/1
Content-Type: application/json

{
  "title": "Learn Advanced React",
  "description": "Study hooks, context, and performance optimization",
  "is_completed": false
}
```

**Path Parameters:**

- `id` (required): ID của todo

**Request Body:**

- `title` (sometimes): string, max 255 ký tự
- `description` (nullable): string
- `is_completed` (sometimes): boolean

**Lưu ý:** Chỉ cần gửi các field cần cập nhật

**Response (200 OK):**

```json
{
  "data": {
    "id": 1,
    "title": "Learn Advanced React",
    "description": "Study hooks, context, and performance optimization",
    "is_completed": false,
    "created_at": "2024-01-01T10:30:00.000000Z",
    "updated_at": "2024-01-03T16:45:00.000000Z"
  },
  "message": "Todo updated successfully"
}
```

**Error Response (422 Unprocessable Entity):**

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": ["The title must not exceed 255 characters."]
  }
}
```

---

### 5. Delete a Todo

**Endpoint:** `DELETE /todos/{id}`

**Description:** Xóa một todo

**Request:**

```http
DELETE http://localhost:8000/api/todos/1
Content-Type: application/json
```

**Path Parameters:**

- `id` (required): ID của todo

**Response (200 OK):**

```json
{
  "message": "Todo deleted successfully"
}
```

**Error Response (404 Not Found):**

```json
{
  "message": "No query results found for model [App\\Models\\Todo]."
}
```

---

### 6. Toggle Todo Completion Status

**Endpoint:** `PATCH /todos/{id}/toggle`

**Description:** Chuyển đổi trạng thái hoàn thành của todo (nếu true thành false, ngược lại)

**Request:**

```http
PATCH http://localhost:8000/api/todos/1/toggle
Content-Type: application/json
```

**Path Parameters:**

- `id` (required): ID của todo

**Response (200 OK):**

```json
{
  "data": {
    "id": 1,
    "title": "Learn React",
    "description": "Complete React tutorial on YouTube",
    "is_completed": true,
    "created_at": "2024-01-01T10:30:00.000000Z",
    "updated_at": "2024-01-03T17:00:00.000000Z"
  },
  "message": "Todo toggled successfully"
}
```

**Error Response (404 Not Found):**

```json
{
  "message": "No query results found for model [App\\Models\\Todo]."
}
```

---

## 🔄 Request/Response Format

### Headers

Tất cả requests nên bao gồm:

```
Content-Type: application/json
```

### Request Body Format

Tất cả request bodies phải là valid JSON:

```json
{
  "key": "value",
  "number": 123,
  "boolean": true,
  "null": null
}
```

### Response Format

Tất cả responses đều có cấu trúc:

```json
{
  "data": {...},
  "message": "..."
}
```

hoặc trong trường hợp lỗi:

```json
{
  "message": "Error message",
  "errors": {
    "field": ["error message"]
  }
}
```

---

## 📊 Data Types

| Field        | Type           | Description                           |
| ------------ | -------------- | ------------------------------------- |
| id           | Integer        | ID duy nhất, tự tăng                  |
| title        | String         | Tiêu đề todo, bắt buộc                |
| description  | String \| Null | Mô tả chi tiết, tùy chọn              |
| is_completed | Boolean        | Trạng thái hoàn thành, mặc định false |
| created_at   | DateTime       | Thời gian tạo (ISO 8601 format)       |
| updated_at   | DateTime       | Thời gian cập nhật lần cuối           |

---

## ⚠️ Status Codes

| Code | Description                                |
| ---- | ------------------------------------------ |
| 200  | OK - Request thành công                    |
| 201  | Created - Resource mới được tạo thành công |
| 400  | Bad Request - Request không hợp lệ         |
| 404  | Not Found - Resource không tồn tại         |
| 422  | Unprocessable Entity - Validation failed   |
| 500  | Internal Server Error - Lỗi server         |

---

## 🧪 Example Requests

### Using cURL

```bash
# Get all todos
curl http://localhost:8000/api/todos

# Create todo
curl -X POST http://localhost:8000/api/todos \
  -H "Content-Type: application/json" \
  -d '{"title":"New Todo","description":"Task description"}'

# Update todo
curl -X PUT http://localhost:8000/api/todos/1 \
  -H "Content-Type: application/json" \
  -d '{"title":"Updated Title","is_completed":true}'

# Delete todo
curl -X DELETE http://localhost:8000/api/todos/1

# Toggle todo
curl -X PATCH http://localhost:8000/api/todos/1/toggle
```

### Using JavaScript (Axios)

```javascript
import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8000/api",
});

// Get all todos
api.get("/todos").then((res) => console.log(res.data));

// Create todo
api
  .post("/todos", {
    title: "New Todo",
    description: "Task description",
  })
  .then((res) => console.log(res.data));

// Update todo
api
  .put("/todos/1", {
    title: "Updated Title",
    is_completed: true,
  })
  .then((res) => console.log(res.data));

// Delete todo
api.delete("/todos/1").then((res) => console.log(res.data));

// Toggle todo
api.patch("/todos/1/toggle").then((res) => console.log(res.data));
```

### Using Postman

1. **Import Collection:**

   - Sao chép nội dung dưới vào file `.json`
   - Vào Postman → Import

2. **Create Requests:**
   - Create → Request
   - Chọn HTTP method
   - Nhập URL
   - Thêm headers và body nếu cần

```json
{
  "info": {
    "name": "Todo API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Get All Todos",
      "request": {
        "method": "GET",
        "url": "http://localhost:8000/api/todos"
      }
    },
    {
      "name": "Create Todo",
      "request": {
        "method": "POST",
        "header": [{ "key": "Content-Type", "value": "application/json" }],
        "body": {
          "mode": "raw",
          "raw": "{\"title\":\"New Todo\",\"description\":\"Task description\"}"
        },
        "url": "http://localhost:8000/api/todos"
      }
    }
  ]
}
```

---

## 🔐 Security

### Input Validation

- Tất cả inputs được validate trên server
- Validation rules được định nghĩa rõ ràng
- Lỗi validation được trả về client

### SQL Injection Prevention

- Sử dụng Eloquent ORM (parameterized queries)
- Không bao giờ xây dựng raw SQL queries

### XSS Prevention

- Tất cả outputs được escape
- Sử dụng Laravel's built-in security features

---

## 📝 Best Practices

1. **Luôn bao gồm Content-Type header:**

   ```
   Content-Type: application/json
   ```

2. **Kiểm tra response status code:**

   - 200/201 = Success
   - 400s = Client error
   - 500s = Server error

3. **Xử lý errors trong client:**

   ```javascript
   try {
     const response = await api.get("/todos");
   } catch (error) {
     console.error("Error:", error.response?.data);
   }
   ```

4. **Validate data trên client trước khi gửi:**
   - Tránh gửi invalid data
   - Tăng user experience

---

## 🐛 Troubleshooting

### CORS Error

- Đảm bảo frontend và backend cùng origin hoặc backend enable CORS

### 404 Not Found

- Kiểm tra ID của todo có tồn tại không
- Kiểm tra URL endpoint có đúng không

### 422 Validation Error

- Kiểm tra request body có đúng format không
- Xem message error để biết field nào lỗi

### 500 Internal Server Error

- Kiểm tra backend logs
- Đảm bảo database đã được setup đúng

---

## 📞 Support

Nếu gặp vấn đề:

1. Kiểm tra logs backend
2. Kiểm tra request body có valid JSON không
3. Kiểm tra URL endpoint có đúng không
4. Kiểm tra server có đang chạy không
