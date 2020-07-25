import React, { useState, useCallback, useRef } from 'react';
import classNames from '@kikiki_kiki/class-names';
import Alert from '../Form/Alert';
import InputUsername from './InputUsername';
import InputEmail from './InputEmail';
import InputPassword from './InputPassword';

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
  const formErrors = useRef({
    username: null,
    email: null,
    password: null,
  });

  const setFormErrors = useCallback(({ username, email, password }) => {
    const error = { username, email, password };

    const updateErrors = Object.keys(error).reduce((obj, key) => {
      if (error[key] === undefined) {
        return obj;
      }
      return {
        ...obj,
        [key]: error[key],
      };
    }, {});

    return (formErrors.current = {
      ...formErrors.current,
      ...updateErrors,
    });
  }, []);

  const onSubmit = useCallback(
    async (e) => {
      e.preventDefault();
      try {
        const res = await submitHandler({
          username,
          email,
          password,
        });
        console.log(res);
      } catch (err) {
        let errMessage = err.message;
        if (err.response && err.response.data) {
          errMessage = err.response.data.message || errMessage;

          if (err.response.data.errors) {
            setFormErrors(err.response.data.errors);
          }
        }
        setError(errMessage);
      }
    },
    [username, email, password, submitHandler, setFormErrors],
  );

  const formError = 'has-error';
  const {
    username: usernameError,
    email: emailError,
    password: passwordError,
  } = formErrors.current;

  const disabled = !username || !email || !password || usernameError || emailError || passwordError;

  return (
    <>
      <div className="form-title">Signup</div>
      {error && <Alert className="alert-error">{error}</Alert>}
      <form onSubmit={onSubmit}>
        <div className={classNames('form-row', { [formError]: usernameError })}>
          <InputUsername
            username={username}
            error={usernameError}
            setUsername={setUsername}
            setError={setFormErrors}
          >
            Username
          </InputUsername>
        </div>
        <div className={classNames('form-row', { [formError]: emailError })}>
          <InputEmail email={email} error={emailError} setEmail={setEmail}>
            Email
          </InputEmail>
        </div>
        <div className={classNames('form-row', { [formError]: passwordError })}>
          <InputPassword
            password={password}
            error={passwordError}
            setPassword={setPassword}
            setError={setFormErrors}
          >
            Password
          </InputPassword>
        </div>
        <div className="form-row form-action">
          <button type="submit" disabled={disabled}>
            SIGNUP
          </button>
        </div>
      </form>
      <hr />
      <button className="btn" onClick={onChangeMode}>
        Login
      </button>
    </>
  );
}
