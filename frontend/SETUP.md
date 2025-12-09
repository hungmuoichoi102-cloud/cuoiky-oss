# Frontend Setup Guide - React Todo App

## 📋 Mục đích

File này chứa hướng dẫn chi tiết để cài đặt và chạy frontend React.

## ⚙️ Các bước cài đặt

### Bước 1: Cài đặt Node Dependencies

```bash
cd frontend
npm install
```

Điều này sẽ cài đặt tất cả packages từ `package.json`:

- `react`: Library chính
- `react-dom`: Render React vào DOM
- `axios`: HTTP client để gọi API
- `@vitejs/plugin-react`: Plugin React cho Vite
- `vite`: Build tool

### Bước 2: Sao chép và Cấu hình .env

```bash
cp .env.example .env
```

File `.env` chứa:

```
VITE_API_BASE_URL=http://localhost:8000/api
```

**Lưu ý:**

- Nếu Laravel backend chạy trên port khác, cần update URL này
- Tất cả biến Vite phải bắt đầu với `VITE_`
- Biến này được truy cập qua `import.meta.env.VITE_API_BASE_URL` trong code

### Bước 3: Chạy Development Server

```bash
npm run dev
```

Dev server sẽ khởi động tại `http://localhost:5173`

Vite cung cấp Hot Module Replacement (HMR), nghĩa là khi bạn sửa code, trang sẽ tự động reload mà không mất state.

## 📚 Cấu trúc Code

### Components (`src/components/`)

#### TodoList.jsx

Component chính quản lý toàn bộ logic ứng dụng.

**State:**

- `todos`: Danh sách tất cả todos
- `loading`: Trạng thái loading
- `error`: Thông báo lỗi
- `editingTodo`: Todo đang được chỉnh sửa
- `filter`: Filter hiện tại (all, completed, pending)

**Functions:**

- `fetchTodos()`: Lấy danh sách todos từ API
- `handleAddTodo()`: Tạo todo mới
- `handleUpdateTodo()`: Cập nhật todo
- `handleDeleteTodo()`: Xóa todo
- `handleToggleTodo()`: Chuyển đổi trạng thái
- `handleEditTodo()`: Bắt đầu chỉnh sửa
- `getFilteredTodos()`: Lọc todos theo filter

#### TodoForm.jsx

Component form để tạo/sửa todo.

**Props:**

- `onSubmit`: Callback khi submit form
- `editingTodo`: Todo đang chỉnh sửa (nếu có)
- `onCancel`: Callback khi hủy chỉnh sửa

**Validation:**

- `title`: Bắt buộc, không được để trống
- `description`: Tùy chọn

#### TodoItem.jsx

Component để hiển thị một todo item.

**Props:**

- `todo`: Dữ liệu todo
- `onToggle`: Callback khi click checkbox
- `onDelete`: Callback khi click nút xóa
- `onEdit`: Callback khi click nút sửa

### Services (`src/services/`)

#### todoService.js

Module gọi API từ backend.

**Methods:**

- `getAllTodos()`: GET /todos
- `getTodo(id)`: GET /todos/{id}
- `createTodo(data)`: POST /todos
- `updateTodo(id, data)`: PUT /todos/{id}
- `deleteTodo(id)`: DELETE /todos/{id}
- `toggleTodo(id)`: PATCH /todos/{id}/toggle

Sử dụng axios để gọi API và tự động thêm `Content-Type: application/json` header.

### App.jsx

Root component của ứng dụng. Hiện tại chỉ render `TodoList` component.

### main.jsx

Entry point của ứng dụng React. Render root component vào element có id="root".

### index.html

HTML template. React sẽ render vào element `<div id="root"></div>`.

## 🎨 Styling

Tất cả component đều có file CSS riêng (CSS modules pattern).

### Color Scheme

- Primary: `#007bff` (Blue)
- Success: `#28a745` (Green)
- Warning: `#ffc107` (Yellow)
- Danger: `#dc3545` (Red)
- Gray: `#6c757d`
- Light Gray: `#f5f5f5`

### Responsive Design

- Breakpoint: 600px
- Sử dụng flexbox cho layout
- Mobile-first approach

## 🚀 Available Scripts

```bash
# Development server
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview

# Lint code
npm run lint
```

## 🔄 Data Flow

```
User Input (Form)
        ↓
TodoForm Component
        ↓
TodoList.handleAddTodo()
        ↓
todoService.createTodo() (API call)
        ↓
Backend Response
        ↓
Update todos state
        ↓
Re-render TodoList
        ↓
Display updated todos
```

## 🧪 Testing API Calls

### Using Browser DevTools

1. Mở DevTools (F12)
2. Chọn tab "Network"
3. Thực hiện action trên app
4. Xem request/response trong Network tab

### Using Console

```javascript
// Lấy tất cả todos
import { todoService } from "./src/services/todoService.js";
todoService.getAllTodos().then((res) => console.log(res.data));

// Tạo todo
todoService
  .createTodo({
    title: "Test Todo",
    description: "Test Description",
  })
  .then((res) => console.log(res.data));
```

## 🔧 Configuration

### Vite Config (vite.config.js)

```javascript
export default defineConfig({
  plugins: [react()],
  server: {
    port: 5173,
    proxy: {
      "/api": {
        target: "http://localhost:8000",
        changeOrigin: true,
      },
    },
  },
});
```

**Proxy Setup:**

- Tất cả request đến `/api` sẽ được proxy đến `http://localhost:8000`
- `changeOrigin: true` giúp set origin header đúng
- Điều này giúp tránh CORS issues

## 📦 Dependencies Explanation

### react (^18.2.0)

Core React library để build UI components.

### react-dom (^18.2.0)

Package để render React components vào DOM.

### axios (^1.6.0)

HTTP client library để gọi API.

Tại sao không dùng `fetch`?

- Axios có request/response interceptors
- Tự động transform data
- Better error handling
- Request cancellation support

## 🐛 Debugging

### React DevTools

Cài đặt extension "React Developer Tools" cho Chrome/Firefox.

Lợi ích:

- Xem component tree
- Inspect props và state
- Time-travel debugging
- Profiling

### Console Logging

```javascript
console.log("Debug message:", variable);
console.error("Error message:", error);
console.warn("Warning message:", warning);
```

### Network Debugging

Sử dụng DevTools Network tab để:

- Xem tất cả API calls
- Kiểm tra request/response
- Xem status codes và headers
- Xem response time

## 📝 Error Handling

Tất cả API calls được wrap trong try-catch:

```javascript
try {
  const response = await todoService.getAllTodos();
  setTodos(response.data.data);
} catch (err) {
  setError("Failed to load todos. Please try again.");
  console.error("Error:", err);
}
```

Error messages được hiển thị cho user thông qua error state.

## 🚀 Build for Production

```bash
npm run build
```

Lệnh này sẽ:

1. Optimize code (minification, tree-shaking)
2. Bundle assets
3. Output vào thư mục `dist/`

Output có thể được deploy lên:

- Netlify
- Vercel
- GitHub Pages
- Static hosting services

## 🔐 Environment Variables

Biến môi trường được load từ file `.env` vào `import.meta.env`.

**Production deployment:**

```bash
VITE_API_BASE_URL=https://api.example.com
```

Update URL của backend production trước khi build.

## 🌐 CORS Configuration

Nếu backend và frontend trên domain khác:

Backend cần enable CORS. Thêm vào Laravel:

```php
// app/Http/Middleware/HandleCors.php
Header('Access-Control-Allow-Origin: *');
Header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH');
Header('Access-Control-Allow-Headers: Content-Type');
```

## 📞 Support

Nếu gặp vấn đề:

1. Kiểm tra Network tab xem API call có được gửi không
2. Kiểm tra backend console có lỗi gì không
3. Đảm bảo API URL trong `.env` đúng
4. Xem browser console có error không
