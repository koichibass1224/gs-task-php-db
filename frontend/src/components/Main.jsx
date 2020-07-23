import React from 'react';

export default function Main({ isAuthenticated, handleSignup, handleLogin }) {
  return (
    <div className="main" role="main">
      {isAuthenticated ? <>Post Form</> : <>Login / Signup Form</>}
    </div>
  );
}
