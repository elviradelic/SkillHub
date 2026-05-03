function RegisterForm({ formData, onChange, onSubmit, error, success }) {
  return (
    <form onSubmit={onSubmit}>
      {error && <p className="error-msg">{error}</p>}
      {success && (
        <p className="success-msg">
            Registration successful! You can now login.
        </p>
      )}

      <div className="form-group">
        <label>Full Name</label>
        <input
          type="text"
          name="name"
          value={formData.name}
          onChange={onChange}
          placeholder="Enter your full name"
        />
      </div>

      <div className="form-group">
        <label>Email</label>
        <input
          type="email"
          name="email"
          value={formData.email}
          onChange={onChange}
          placeholder="Enter your email"
        />
      </div>

      <div className="form-group">
        <label>Password</label>
        <input
          type="password"
          name="password"
          value={formData.password}
          onChange={onChange}
          placeholder="Create a password"
        />
      </div>

      <button type="submit" className="btn-submit">
        Register
      </button>
    </form>
  );
}

export default RegisterForm;