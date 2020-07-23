import React from 'react';
import UserProvider from './providers/UserProvider';
import UserContext from './contexts/user';

export default function App() {
  return (
    <>
      <UserProvider>
        <UserContext.Consumer>
          {(user) => (
            <>{console.log(user)}</>
          )}
        </UserContext.Consumer>
      </UserProvider>
    </>
  );
}
