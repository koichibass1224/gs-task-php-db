import React, { useCallback } from 'react';
import { useDebounceCallback } from '../../hooks/useDebounceCallback';
import ErrorNote from '../Form/ErrorNote';

export default function InputUsername({ username, error, setUsername, setError, children }) {
  const checkUserNameCallback = useCallback((username) => {
    // TODO: check username already exists.
    console.log('checkUserName', username);
  }, []);

  const [onCheckUserName, cancelCheckUserName] = useDebounceCallback(checkUserNameCallback, 500);

  const onChangeUserName = useCallback(
    (e) => {
      const username = e.target.value;
      // validate username
      if (!/^([\w-]+)$/.test(username)) {
        cancelCheckUserName();
        setError({ username: 'User name can include alphabets, `-` and `_`.' });
      } else {
        setError({ username: null });
        onCheckUserName(username);
      }

      setUsername(username);
    },
    [setUsername, onCheckUserName, cancelCheckUserName, setError],
  );

  return (
    <>
      <label htmlFor="username" className="label">
        {children}
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
      {error && <ErrorNote>{error}</ErrorNote>}
    </>
  );
}
