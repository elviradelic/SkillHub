import { useState } from "react";
import skillHubFacade from "../services/skillHubFacade";
import RegisterForm from "../components/RegisterForm";
import "./RegisterPage.css";

function RegisterPage() {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    password: "",
    role: "student",
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

        <RegisterForm
          formData={formData}
          onChange={handleChange}
          onSubmit={handleSubmit}
          error={error}
          success={success}
        />

        <p className="redirect-text">
          Already have an account? <a href="/login">Login here</a>
        </p>
      </div>
    </div>
  );
}

export default RegisterPage;