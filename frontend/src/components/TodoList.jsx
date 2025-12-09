import React, { useState, useEffect } from "react";
import TodoForm from "./TodoForm";
import TodoItem from "./TodoItem";
import { todoService } from "../services/todoService";
import "./TodoList.css";

function TodoList() {
  const [todos, setTodos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [editingTodo, setEditingTodo] = useState(null);
  const [filter, setFilter] = useState("all"); // all, completed, pending

  // Fetch todos on component mount
  useEffect(() => {
    fetchTodos();
  }, []);

  const fetchTodos = async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await todoService.getAllTodos();
      setTodos(response.data.data);
    } catch (err) {
      setError("Failed to load todos. Please try again.");
      console.error("Error fetching todos:", err);
    } finally {
      setLoading(false);
    }
  };

  const handleAddTodo = async (data) => {
    try {
      const response = await todoService.createTodo(data);
      setTodos([response.data.data, ...todos]);
      setError(null);
    } catch (err) {
      setError("Failed to create todo. Please try again.");
      console.error("Error creating todo:", err);
    }
  };

  const handleUpdateTodo = async (data) => {
    try {
      const response = await todoService.updateTodo(editingTodo.id, data);
      setTodos(
        todos.map((todo) =>
          todo.id === editingTodo.id ? response.data.data : todo
        )
      );
      setEditingTodo(null);
      setError(null);
    } catch (err) {
      setError("Failed to update todo. Please try again.");
      console.error("Error updating todo:", err);
    }
  };

  const handleDeleteTodo = async (id) => {
    if (window.confirm("Are you sure you want to delete this todo?")) {
      try {
        await todoService.deleteTodo(id);
        setTodos(todos.filter((todo) => todo.id !== id));
        setError(null);
      } catch (err) {
        setError("Failed to delete todo. Please try again.");
        console.error("Error deleting todo:", err);
      }
    }
  };

  const handleToggleTodo = async (id) => {
    try {
      const response = await todoService.toggleTodo(id);
      setTodos(
        todos.map((todo) => (todo.id === id ? response.data.data : todo))
      );
      setError(null);
    } catch (err) {
      setError("Failed to toggle todo. Please try again.");
      console.error("Error toggling todo:", err);
    }
  };

  const handleEditTodo = (todo) => {
    setEditingTodo(todo);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  const handleFormSubmit = (data) => {
    if (editingTodo) {
      handleUpdateTodo(data);
    } else {
      handleAddTodo(data);
    }
  };

  const getFilteredTodos = () => {
    switch (filter) {
      case "completed":
        return todos.filter((todo) => todo.is_completed);
      case "pending":
        return todos.filter((todo) => !todo.is_completed);
      default:
        return todos;
    }
  };

  const filteredTodos = getFilteredTodos();
  const completedCount = todos.filter((todo) => todo.is_completed).length;
  const pendingCount = todos.filter((todo) => !todo.is_completed).length;

  return (
    <div className="todo-list-container">
      <div className="todo-header">
        <h1>📝 My Todo List</h1>
        <p className="subtitle">Stay organized and track your tasks</p>
      </div>

      {error && <div className="error-message">{error}</div>}

      <TodoForm
        onSubmit={handleFormSubmit}
        editingTodo={editingTodo}
        onCancel={() => setEditingTodo(null)}
      />

      <div className="todo-stats">
        <div className="stat">
          <span className="stat-label">Total:</span>
          <span className="stat-value">{todos.length}</span>
        </div>
        <div className="stat">
          <span className="stat-label">Completed:</span>
          <span className="stat-value completed">{completedCount}</span>
        </div>
        <div className="stat">
          <span className="stat-label">Pending:</span>
          <span className="stat-value pending">{pendingCount}</span>
        </div>
      </div>

      <div className="filter-buttons">
        <button
          className={`filter-btn ${filter === "all" ? "active" : ""}`}
          onClick={() => setFilter("all")}
        >
          All ({todos.length})
        </button>
        <button
          className={`filter-btn ${filter === "pending" ? "active" : ""}`}
          onClick={() => setFilter("pending")}
        >
          Pending ({pendingCount})
        </button>
        <button
          className={`filter-btn ${filter === "completed" ? "active" : ""}`}
          onClick={() => setFilter("completed")}
        >
          Completed ({completedCount})
        </button>
      </div>

      <div className="todos-section">
        {loading ? (
          <div className="loading">Loading todos...</div>
        ) : filteredTodos.length === 0 ? (
          <div className="empty-state">
            {todos.length === 0 ? (
              <>
                <p>No todos yet!</p>
                <p className="small">Add your first todo to get started</p>
              </>
            ) : (
              <>
                <p>No {filter} todos</p>
                <p className="small">Try a different filter</p>
              </>
            )}
          </div>
        ) : (
          <div className="todos-list">
            {filteredTodos.map((todo) => (
              <TodoItem
                key={todo.id}
                todo={todo}
                onToggle={handleToggleTodo}
                onDelete={handleDeleteTodo}
                onEdit={handleEditTodo}
              />
            ))}
          </div>
        )}
      </div>
    </div>
  );
}

export default TodoList;
