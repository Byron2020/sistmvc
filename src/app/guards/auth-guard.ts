import { CanActivateFn, Router} from '@angular/router';
import { inject } from '@angular/core';


export const authGuard: CanActivateFn = (route, state) => {
  const router = inject(Router);

  const token = localStorage.getItem('auth_token');
  const expires = localStorage.getItem('auth_expires');
  const userData = localStorage.getItem('user');
  const nowMs = Date.now();

  // parseInt(exp) es en segundos → lo convertimos a ms
  const expiryMs = expires ? parseInt(expires, 10) * 1000 : 0;
  const isExpired = nowMs > expiryMs;

  if (!token || isExpired || !userData) {
    console.log(' Decision: false — redirigiendo a /login');

    localStorage.clear();
    router.navigate(['/login']); // Redirección
    return false;
  }

  console.log(' Decision: true — acceso permitido');
  return true;
};
