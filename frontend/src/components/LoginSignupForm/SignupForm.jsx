import React from 'react';

export default function SignupForm({
  onSubmit,
  username,
  email,
  password,
  onChangeUserName,
  onChangeEmail,
  onChangePassword,
  onChangeMode,
}) {
  return (
    <>
      <div className="form-title">Signup</div>
      <form onSubmit={onSubmit}>
        <div className="form-row">
          <label htmlFor="username" className="label">
            UserName
          </label>
          <input
            type="text"
            name="username"
            className="imput-field"
            value={username}
            onChange={onChangeUserName}
            placeholder="Username can use alphabets, - ande _."
          />
        </div>
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
        Login
      </button>
    </>
  );
}
