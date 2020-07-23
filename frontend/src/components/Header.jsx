import React from 'react';

function LoginedNav({ user, handleLogout }) {
  return (
    <>
      {user.username}
      <button className="btn" onClick={handleLogout}>
        LOGOUT
      </button>
    </>
  );
}

export default function Header({ isAuthenticated, ...props }) {
  return <header className="global-header">{isAuthenticated && <LoginedNav {...props} />}</header>;
}
