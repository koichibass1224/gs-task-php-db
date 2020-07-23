import React, { useState, useCallback } from 'react';
import LoginForm from './LoginForm';
import SignupForm from './SignupForm';

export default function LoginSignupForm({ onSignup, onLogin }) {
  const [username, setUsername] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [mode, setMode] = useState(true);

  const onChangeMode = useCallback(
    (mode) => () => {
      setMode(mode);
    },
    [],
  );

  const onChangeUserName = useCallback((e) => {
    // TODO: validate
    setUsername(e.target.value);
  }, []);

  const onChangeEmail = useCallback((e) => {
    // TODO: validate
    setEmail(e.target.value);
  }, []);

  const onChangePassword = useCallback((e) => {
    // TODO: validate
    setPassword(e.target.value);
  }, []);

  const state = {
    username,
    email,
    password,
    onChangeUserName,
    onChangeEmail,
    onChangePassword,
  };

  return mode ? (
    <SignupForm onSubmit={onSignup} onChangeMode={onChangeMode(false)} {...state} />
  ) : (
    <LoginForm onSubmit={onLogin} onChangeMode={onChangeMode(true)} {...state} />
  );
}
