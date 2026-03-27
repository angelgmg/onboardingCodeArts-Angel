// src/app/shared/components/back-to-landing-button/back-to-landing-button-component.ts
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-back-to-landing-button',
  standalone: true,
  imports: [RouterLink], // Necesario para usar routerLink
  templateUrl: './back-to-landing-button.html',
})
export class BackToLandingButtonComponent {}
