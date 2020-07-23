import React, { useState, useCallback } from 'react';
import Alert from '../Form/Alert';

export default function LoginForm({
  email,
  password,
  setEmail,
  setPassword,
  submitHandler,
  onChangeMode,
}) {
  const [error, setError] = useState(false);

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
        email,
        password,
      });
      console.log(res);
    } catch(err) {
      const errMessage = (err.response && err.response.data.errors) || err.message;
      setError(errMessage);
    }
  }, [email, password, submitHandler]);

  const disabled = !email || !password;

  return (
    <>
      <div className="form-title">Login</div>
      {error && <Alert className="alert-error">{error}</Alert>}
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
          <button type="submit" disabled={disabled}>LOGIN</button>
        </div>
      </form>
      <hr />
      <button className="btn" onClick={onChangeMode}>
        Signup
      </button>
    </>
  );
}
