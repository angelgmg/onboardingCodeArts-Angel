// src/app/modules/tasks/tasks-page-component.ts
import { Component, ViewChild } from '@angular/core';
import { CommonModule } from '@angular/common';
import { BackToLandingButtonComponent } from '../../shared/components/back-to-landing-button/back-to-landing-button';
import { PageTitleComponent } from '../../shared/components/page-title/page-title';
import { TaskFormComponent } from './components/task-form-component/task-form-component';
import { TaskListComponent } from './components/task-list-component/task-list-component';
import { TaskApiService } from '../../features/tasks/data/task-api';
import { TaskPayload } from '../../shared/interfaces/tasks';
import { ToastService } from '../../shared/services/toast-service';

interface Task {
  title: string;
  description?: string;
  status: 'pendiente' | 'en progreso' | 'completada';
  dueDate: string | null;
}

type TaskFormPayload = {
  titulo: string;
  descripcion: string;
  estado: 'pendiente' | 'en progreso' | 'completada';
  fechaLimite: string | null;
};

@Component({
  selector: 'app-tasks-page',
  standalone: true,
  imports: [
    CommonModule,
    BackToLandingButtonComponent,
    PageTitleComponent,
    TaskFormComponent,
    TaskListComponent,
  ],
  templateUrl: './tasks-page-component.html',
})
export class TasksPageComponent {
  @ViewChild(TaskListComponent) list?: TaskListComponent; // referencia para refrescar la lista

  constructor(
    private readonly api: TaskApiService,
    private readonly toast: ToastService,
  ) {}

  onTaskSubmitted(payload: TaskPayload) {
    this.api.createTask(payload).subscribe({
      next: () => {
        this.toast.success('Tarea guardada');
        this.list?.loadTasks();
      },
      error: () => this.toast.error('No se pudo guardar la tarea'),
    });
  }
  onFiltersApply(f: {
    q?: string;
    estado?: 'pendiente' | 'en progreso' | 'completada';
    fechaDesde?: string | null;
    fechaHasta?: string | null;
  }) {
    this.list?.loadTasks({ q: f.q, estado: f.estado });
  }
}
