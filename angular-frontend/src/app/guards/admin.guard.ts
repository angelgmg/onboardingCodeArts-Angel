//angular-frontend/src/app/guards/admin.guard.ts
import { CanActivateFn, Router } from '@angular/router';
import { inject } from '@angular/core';
import { AuthStore } from '../shared/services/auth-store';
import { ToastService } from '../shared/services/toast-service';
import { catchError, map, of } from 'rxjs';

export const adminGuard: CanActivateFn = () => {
  const store = inject(AuthStore);
  const router = inject(Router);
  const toast = inject(ToastService);

  if (!store.token()) {
    toast.info('Inicia sesión para continuar');
    return router.createUrlTree(['/login']);
  }

  if (store.user()) {
    if (store.isAdmin()) return true;
    toast.error('No tienes permisos de administrador');
    return router.createUrlTree(['/tasks']);
  }

  return store.loadMe().pipe(
    map(() => (store.isAdmin() ? true : router.createUrlTree(['/tasks']))),
    catchError(() => {
      toast.error('No se pudo verificar permisos');
      return of(router.createUrlTree(['/tasks']));
    }),
  );
};
