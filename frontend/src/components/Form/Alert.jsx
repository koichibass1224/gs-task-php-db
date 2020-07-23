import React from 'react';
import classNames from '@kikiki_kiki/class-names';

export default function Alert({ className, children }) {
  const cx = classNames('alert', className);
  return <div className={cx}>{children}</div>;
}
