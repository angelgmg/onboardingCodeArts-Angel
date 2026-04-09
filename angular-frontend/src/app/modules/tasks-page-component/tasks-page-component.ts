// src/app/modules/tasks/tasks-page-component.ts
import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { BackToLandingButtonComponent } from '../../shared/components/back-to-landing-button/back-to-landing-button';
import { PageTitleComponent } from '../../shared/components/page-title/page-title';

// Definimos una interfaz para tipar nuestras tareas
interface Task {
  title: string;
  status: 'pendiente' | 'en progreso' | 'completada';
  dueDate: string | null;
}

@Component({
  selector: 'app-tasks-page',
  standalone: true,
  imports: [
    CommonModule,
    BackToLandingButtonComponent,
    PageTitleComponent, // Añadimos el componente a los imports
  ],
  templateUrl: './tasks-page-component.html',
})
export class TasksPageComponent {
  // Creamos un array de tareas de ejemplo
  tasks: Task[] = [
    {
      title: 'Aprender Angular',
      status: 'en progreso',
      dueDate: '2025-11-15',
    },
    {
      title: 'Practicar con TypeScript',
      status: 'pendiente',
      dueDate: '2025-11-20',
    },
    {
      title: 'Estudiar Tailwind',
      status: 'completada',
      dueDate: null,
    },
  ];
}
