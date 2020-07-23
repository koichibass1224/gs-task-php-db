import React, { useState, useCallback } from 'react';
import Alert from '../Form/Alert';

export default function SignupForm({
  username,
  email,
  password,
  setUsername,
  setEmail,
  setPassword,
  submitHandler,
  onChangeMode,
}) {
  const [error, setError] = useState(false);

  const onChangeUserName = useCallback((e) => {
    setUsername(e.target.value);
  }, [setUsername]);

  const onChangeEmail = useCallback((e) => {
    setEmail(e.target.value);
  }, [setEmail]);

  const onChangePassword = useCallback((e) => {
    setPassword(e.target.value);
  }, [setPassword]);

  const onSubmit = useCallback(async (e) => {
    e.preventDefault();
    try {
      const res = await submitHandler({
        username,
        email,
        password,
      });
      console.log(res);
    } catch(err) {
      const errMessage = (err.response && err.response.data.message) || err.message;
      setError(errMessage);
    }
  }, [username, email, password, submitHandler]);

  const disabled = !username || !email || !password;

  return (
    <>
      <div className="form-title">Signup</div>
      {error && <Alert className="alert-error">{error}</Alert>}
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
            required={true}
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
            required={true}
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
            required={true}
          />
        </div>
        <div className="form-row form-action">
          <button type="submit" disabled={disabled}>SIGNUP</button>
        </div>
      </form>
      <hr />
      <button className="btn" onClick={onChangeMode}>
        Login
      </button>
    </>
  );
}
