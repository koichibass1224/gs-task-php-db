import React, { useState, useCallback } from 'react';
import LoginForm from './LoginForm';
import SignupForm from './SignupForm';

export default function LoginSignupForm({ handleSignup, handleLogin }) {
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

  const state = {
    username,
    email,
    password,
    setUsername,
    setEmail,
    setPassword,
  };

  return mode ? (
    <SignupForm submitHandler={handleSignup} onChangeMode={onChangeMode(false)} {...state} />
  ) : (
    <LoginForm submitHandler={handleLogin} onChangeMode={onChangeMode(true)} {...state} />
  );
}
