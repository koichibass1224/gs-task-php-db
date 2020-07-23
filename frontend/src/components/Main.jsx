import React from 'react';
import LoginSignupForm from './LoginSignupForm';

export default function Main({ isAuthenticated, handleSignup, handleLogin }) {
  return (
    <div className="main" role="main">
      {isAuthenticated ? (
        <>Post Form</>
      ) : (
        <LoginSignupForm onSignup={handleSignup} onLogin={handleLogin} />
      )}
    </div>
  );
}
