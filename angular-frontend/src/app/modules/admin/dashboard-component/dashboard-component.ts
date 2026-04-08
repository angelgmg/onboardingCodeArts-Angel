//angular-frontend/src/app/modules/admin/dashboard-component/dashboard-component.ts
import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AuthStore } from '../../../shared/services/auth-store';

@Component({
  selector: 'app-admin-dashboard',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './dashboard-component.html',
})
export class AdminDashboardComponent {
  constructor(public readonly store: AuthStore) {}
}
