function LoginForm({ formData, onChange, onSubmit, error, success }) {
  return (
    <form onSubmit={onSubmit}>
      {error && <p className="error-msg">{error}</p>}
      {success && <p className="success-msg">Login successful!</p>}

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
          placeholder="Enter your password"
        />
      </div>

      <button type="submit" className="btn-submit">
        Login
      </button>
    </form>
  );
}

export default LoginForm;