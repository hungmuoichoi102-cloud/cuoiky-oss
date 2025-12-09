import React from "react";
import "./TodoItem.css";

function TodoItem({ todo, onToggle, onDelete, onEdit }) {
  return (
    <div className="todo-item">
      <div className="todo-content">
        <input
          type="checkbox"
          checked={todo.is_completed}
          onChange={() => onToggle(todo.id)}
          className="todo-checkbox"
        />
        <div className="todo-text">
          <h3 className={todo.is_completed ? "completed" : ""}>{todo.title}</h3>
          {todo.description && (
            <p className={todo.is_completed ? "completed" : ""}>
              {todo.description}
            </p>
          )}
        </div>
      </div>
      <div className="todo-actions">
        <button onClick={() => onEdit(todo)} className="btn-edit" title="Edit">
          ✏️
        </button>
        <button
          onClick={() => onDelete(todo.id)}
          className="btn-delete"
          title="Delete"
        >
          🗑️
        </button>
      </div>
    </div>
  );
}

export default TodoItem;
