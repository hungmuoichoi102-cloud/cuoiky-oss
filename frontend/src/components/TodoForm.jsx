import React, { useState, useEffect } from "react";
import "./TodoForm.css";

function TodoForm({ onSubmit, editingTodo, onCancel }) {
  const [title, setTitle] = useState("");
  const [description, setDescription] = useState("");
  const [errors, setErrors] = useState({});

  useEffect(() => {
    if (editingTodo) {
      setTitle(editingTodo.title);
      setDescription(editingTodo.description || "");
    } else {
      setTitle("");
      setDescription("");
    }
    setErrors({});
  }, [editingTodo]);

  const validateForm = () => {
    const newErrors = {};

    if (!title.trim()) {
      newErrors.title = "Title is required";
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    if (!validateForm()) {
      return;
    }

    onSubmit({
      title: title.trim(),
      description: description.trim(),
    });

    setTitle("");
    setDescription("");
    setErrors({});
  };

  const handleCancel = () => {
    setTitle("");
    setDescription("");
    setErrors({});
    onCancel?.();
  };

  return (
    <form onSubmit={handleSubmit} className="todo-form">
      <div className="form-group">
        <label htmlFor="title">Title *</label>
        <input
          id="title"
          type="text"
          value={title}
          onChange={(e) => setTitle(e.target.value)}
          placeholder="What do you need to do?"
          className={errors.title ? "error" : ""}
        />
        {errors.title && <span className="error-text">{errors.title}</span>}
      </div>

      <div className="form-group">
        <label htmlFor="description">Description</label>
        <textarea
          id="description"
          value={description}
          onChange={(e) => setDescription(e.target.value)}
          placeholder="Add details (optional)"
          rows="3"
        />
      </div>

      <div className="form-actions">
        <button type="submit" className="btn-submit">
          {editingTodo ? "Update Todo" : "Add Todo"}
        </button>
        {editingTodo && (
          <button type="button" onClick={handleCancel} className="btn-cancel">
            Cancel
          </button>
        )}
      </div>
    </form>
  );
}

export default TodoForm;
