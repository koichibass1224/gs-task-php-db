import { useRef, useCallback } from 'react';

export const useDebounceCallback = (callback, deley = 300) => {
  const debounceTimer = useRef(null);

  const dispatch = useCallback(
    (...args) => {
      debounceTimer.current && clearTimeout(debounceTimer.current);
      debounceTimer.current = setTimeout(() => {
        if (typeof callback !== 'function') {
          return args;
        }
        return callback(...args);
      }, deley);
    },
    [callback, deley],
  );

  const cancel = useCallback(() => {
    debounceTimer.current && clearTimeout(debounceTimer.current);
  }, []);

  return [dispatch, cancel];
};
