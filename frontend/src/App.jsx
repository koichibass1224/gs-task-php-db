import React from 'react';
import UserProvider from './providers/UserProvider';
import UserContext from './contexts/user';
import Header from './components/Header';
import Main from './components/Main';

export default function App() {
  return (
    <>
      <UserProvider>
        <UserContext.Consumer>
          {(user) => (
            <>
              <Header {...user} />
              <Main {...user} />
            </>
          )}
        </UserContext.Consumer>
      </UserProvider>
    </>
  );
}
