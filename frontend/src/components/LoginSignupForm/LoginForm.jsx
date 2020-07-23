import React from 'react';

export default function LoginForm({
  onSubmit,
  email,
  password,
  onChangeEmail,
  onChangePassword,
  onChangeMode,
}) {
  return (
    <>
      <div className="form-title">Login</div>
      <form onSubmit={onSubmit}>
        <div className="form-row">
          <label htmlFor="email" className="label">
            E-mail
          </label>
          <input
            type="email"
            name="email"
            className="imput-field"
            value={email}
            onChange={onChangeEmail}
          />
        </div>
        <div className="form-row">
          <label htmlFor="password" className="label">
            Password
          </label>
          <input
            type="password"
            name="password"
            className="imput-field"
            value={password}
            onChange={onChangePassword}
          />
        </div>
      </form>
      <button className="btn" onClick={onChangeMode}>
        Signup
      </button>
    </>
  );
}
