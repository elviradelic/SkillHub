import { useState } from "react";
import skillHubFacade from "../services/skillHubFacade";
import "./RegisterPage.css";

function RegisterPage() {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    password: "",
  });
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(false);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    setError(null);

    if (!formData.name || !formData.email || !formData.password) {
      setError("Please fill in all fields.");
      return;
    }

    skillHubFacade
      .registerUser(formData)
      .then(() => {
        setSuccess(true);
      })
      .catch(() => {
        setError("Registration failed. Please try again.");
      });
  };

  return (
    <div className="register-page">
      <div className="register-card">
        <h1>Register</h1>
        <p>Create your account and start learning today.</p>

        {error && <p className="error-msg">{error}</p>}
        {success && (
          <p className="success-msg">
            Registration successful! You can now login.
          </p>
        )}

        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label>Full Name</label>
            <input
              type="text"
              name="name"
              value={formData.name}
              onChange={handleChange}
              placeholder="Enter your full name"
            />
          </div>

          <div className="form-group">
            <label>Email</label>
            <input
              type="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              placeholder="Enter your email"
            />
          </div>

          <div className="form-group">
            <label>Password</label>
            <input
              type="password"
              name="password"
              value={formData.password}
              onChange={handleChange}
              placeholder="Create a password"
            />
          </div>

          <button type="submit" className="btn-submit">
            Register
          </button>
        </form>

        <p className="redirect-text">
          Already have an account? <a href="/login">Login here</a>
        </p>
      </div>
    </div>
  );
}

export default RegisterPage;