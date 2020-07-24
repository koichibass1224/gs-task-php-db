import React, { useCallback } from 'react';
import ErrorNote from '../Form/ErrorNote';

export default function InputPassword({ password, error, setPassword, setError, children }) {
  const onChangePassword = useCallback(
    (e) => {
      const password = e.target.value;
      // Check Password
      if (password.length < 6) {
        setError({ password: 'Please enter a password with at least 6 characters.' });
      } else if (!/^([a-zA-Z\d!-/:-@[-`{-~]+)$/.test(password)) {
        setError({ password: 'Contains can not used characters.' });
      } else {
        setError({ password: null });
      }
      setPassword(password);
    },
    [setPassword, setError],
  );

  return (
    <>
      <label htmlFor="password" className="label">
        {children}
      </label>
      <input
        type="password"
        name="password"
        className="imput-field"
        value={password}
        onChange={onChangePassword}
        required={true}
      />
      {error && <ErrorNote>{error}</ErrorNote>}
    </>
  );
}
