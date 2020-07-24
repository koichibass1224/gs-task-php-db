import React, { useCallback } from 'react';
import { useDebounceCallback } from '../../hooks/useDebounceCallback';
import ErrorNote from '../Form/ErrorNote';

export default function InputEmail({ email, error, setEmail, children }) {
  const checkEmailCallback = useCallback((email) => {
    // TODO: check email already exists.
    console.log('checkEmail', email);
  }, []);

  const [onCheckEmail] = useDebounceCallback(checkEmailCallback, 500);

  const onChangeEmail = useCallback(
    (e) => {
      const email = e.target.value;
      onCheckEmail(email);
      setEmail(email);
    },
    [setEmail, onCheckEmail],
  );

  return (
    <>
      <label htmlFor="email" className="label">
        {children}
      </label>
      <input
        type="email"
        name="email"
        className="imput-field"
        value={email}
        onChange={onChangeEmail}
        required={true}
      />
      {error && <ErrorNote>{error}</ErrorNote>}
    </>
  );
}
