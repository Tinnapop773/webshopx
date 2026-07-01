import '@/styles/globals.css';
import { useEffect } from 'react';
import useAuthStore from '@/lib/store';

export default function RootLayout({ children }) {
  const loadUser = useAuthStore((state) => state.loadUser);

  useEffect(() => {
    loadUser();
  }, [loadUser]);

  return (
    <html lang="th">
      <body>
        {children}
      </body>
    </html>
  );
}
