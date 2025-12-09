import axios from "axios";

const API_BASE_URL =
  import.meta.env.VITE_API_BASE_URL || "http://localhost:8000/api";

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    "Content-Type": "application/json",
  },
});

export const todoService = {
  // Get all todos
  getAllTodos: () => api.get("/todos"),

  // Get a single todo
  getTodo: (id) => api.get(`/todos/${id}`),

  // Create a new todo
  createTodo: (data) => api.post("/todos", data),

  // Update a todo
  updateTodo: (id, data) => api.put(`/todos/${id}`, data),

  // Delete a todo
  deleteTodo: (id) => api.delete(`/todos/${id}`),

  // Toggle todo completion status
  toggleTodo: (id) => api.patch(`/todos/${id}/toggle`),
};

export default api;
