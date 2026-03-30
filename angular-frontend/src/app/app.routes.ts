import { Routes } from '@angular/router';
import { LandingPageComponent } from './modules/landing-page-component/landing-page-component';
import { TasksPageComponent } from './modules/tasks-page-component/tasks-page-component';
import { LoginComponent } from './modules/auth/login-component/login-component';
import { RegisterComponent } from './modules/auth/register-component/register-component';

export const routes: Routes = [
  { path: '', component: LandingPageComponent },
  { path: 'tasks', component: TasksPageComponent },
  { path: 'login', component: LoginComponent },
  { path: 'register', component: RegisterComponent },
];
