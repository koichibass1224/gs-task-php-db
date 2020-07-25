import React, { useState, useCallback } from 'react';
import ErrorNote from '../Form/ErrorNote';

const passwordLevel = (password) => {
  let level = 0;
  if (password.length < 6) {
    return 0;
  }

  const weight = password.length >= 12 ? 2 : 1;

  if (password.length >= 16) {
    level += 2;
  } else if (password.length >= 10) {
    level += 1;
  }

  // include Number
  if (/^(?=.*?[a-zA-Z])(?=.*?\d{2})[a-zA-Z\d!-/:-@[-`{-~]+$/.test(password)) {
    level += 1 * weight;
  } else if (/^(?=.*?[a-zA-Z])(?=.*\d)[a-zA-Z\d!-/:-@[-`{-~]+$/.test(password)) {
    level += 1;
  }

  // include Uppercase and Lowercase
  if (/^(?=.*?[a-z])(?=.*?[A-Z])[a-zA-Z\d!-/:-@[-`{-~]+$/.test(password)) {
    level += 1;
  }

  // include some symbol
  if (/^(?=.*?[a-zA-Z])(?=.*?\d)(?=.*?[!-/:-@[-`{-~])[!-~]+$/.test(password)) {
    level += 1 * weight;
  } else if (/^(?=.*?[a-zA-Z\d])(?=.*?[!-/:-@[-`{-~])[!-~]+$/.test(password)) {
    level += 1;
  }
  return level;
};

export default function InputPassword({ password, error, setPassword, setError, children }) {
  const [level, setLevel] = useState(0);

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

      // Update Password Level
      setLevel(() => passwordLevel(password));

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
      <div className={`password-level level-{level}`}>{level}</div>
      {error && <ErrorNote>{error}</ErrorNote>}
    </>
  );
}
