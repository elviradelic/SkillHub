import { useState } from "react";
import LoginForm from "../components/LoginForm";
import "./LoginPage.css";

function LoginPage() {
  const [formData, setFormData] = useState({
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

    if (!formData.email || !formData.password) {
      setError("Please fill in all fields.");
      return;
    }

    // TODO: connect to backend login endpoint when available
    console.log("Login data:", formData);
    setSuccess(true);
  };

  return (
    <div className="login-page">
      <div className="login-card">
        <h1>Login</h1>
        <p>Welcome back! Please login to your account.</p>

        <LoginForm
          formData={formData}
          onChange={handleChange}
          onSubmit={handleSubmit}
          error={error}
          success={success}
        />

        <p className="redirect-text">
          Don't have an account? <a href="/register">Register here</a>
        </p>
      </div>
    </div>
  );
}

export default LoginPage;