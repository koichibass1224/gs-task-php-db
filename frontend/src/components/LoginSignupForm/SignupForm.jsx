import React, { useState, useCallback } from 'react';
import classNames from '@kikiki_kiki/class-names';
import { useDebounceCallback } from '../../hooks/useDebounceCallback';
import Alert from '../Form/Alert';

function Error({children}) {
  return <small className="text-error">{children}</small>
}

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
  const [errors, setErrors] = useState({});

  const checkUserNameCallback = useCallback((username) => {
    // TODO: check username already exists.
    console.log('checkUserName', username);
  }, []);

  const [onCheckUserName, cancelCheckUserName] = useDebounceCallback(checkUserNameCallback, 500);

  const checkEmailCallback = useCallback((email) => {
    // TODO: check email already exists.
    console.log('checkEmail', email);
  }, []);

  const [onCheckEmail] = useDebounceCallback(checkEmailCallback, 500);

  const onChangeUserName = useCallback((e) => {
    const username = e.target.value;
    // validate username
    if (!/^([\w\-]+)$/.test(username)) {
      cancelCheckUserName();
      errors.username = 'User name can include alphabets, `-` and `_`.';
    } else {
      errors.username = null;
      onCheckUserName(username);
    }

    setUsername(username);
  }, [onCheckUserName, cancelCheckUserName]);

  const onChangeEmail = useCallback((e) => {
    const email = e.target.value;
    onCheckEmail(email);
    setEmail(email);
  }, [setEmail]);

  const onChangePassword = useCallback((e) => {
    const password = e.target.value;
    // Check Password
    if (password.length < 6) {
      errors.password = 'Please enter a password with at least 6 characters.';
    } else if (!/^([a-z\d!-\/:-@[-`{-~]+)$/.test(password)) {
      errors.password = 'Contains can not used characters.';
    } else {
      errors.password = null;
    }
    setPassword(password);
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
      if (err.response && err.response.data) {
        setErrors(() => (err.response.data.errors));
      }
    }
  }, [username, email, password, submitHandler]);

  const disabled = !username || !email || !password;
  const formError = 'has-error';
  const usernameError = errors.username;
  const emailError = errors.email;
  const passwordError = errors.password;

  return (
    <>
      <div className="form-title">Signup</div>
      {error && <Alert className="alert-error">{error}</Alert>}
      <form onSubmit={onSubmit}>
        <div className={classNames("form-row", { [formError]: usernameError})}>
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
          {usernameError && <Error>{usernameError}</Error>}
        </div>
        <div className={classNames("form-row", { [formError]: emailError })}>
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
          {emailError && <Error>{emailError}</Error>}
        </div>
        <div className={classNames("form-row", { [formError]: passwordError })}>
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
          {passwordError && <Error>{passwordError}</Error>}
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
