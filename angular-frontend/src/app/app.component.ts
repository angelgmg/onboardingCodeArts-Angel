// src/app/app.component.ts
import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { ToastComponent } from './shared/components/toast-component/toast-component';
import { AuthStore } from './shared/services/auth-store';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, ToastComponent],
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
})
export class AppComponent {
  constructor(private readonly authStore: AuthStore) {
    if (this.authStore.token()) {
      this.authStore.loadMe().subscribe();
    }
  }
}
