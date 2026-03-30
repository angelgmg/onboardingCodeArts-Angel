// src/app/modules/tasks-page-component/components/task-form-component/task-form-component.ts
import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { NgIf, NgClass } from '@angular/common';

type TaskPayload = {
  titulo: string;
  descripcion: string;
  estado: 'pendiente' | 'en progreso' | 'completada';
  fechaLimite: string | null;
};
type Task = TaskPayload;

@Component({
  selector: 'app-task-form-component',
  standalone: true,
  imports: [ReactiveFormsModule, NgIf, NgClass],
  templateUrl: './task-form-component.html',
})
export class TaskFormComponent implements OnInit {
  @Input() initialValue?: Partial<Task>;
  @Input() loading = false;
  @Output() submitted = new EventEmitter<TaskPayload>();

  form!: FormGroup;

  constructor(private readonly fb: FormBuilder) {}

  ngOnInit(): void {
    this.form = this.fb.group({
      titulo: ['', [Validators.required, Validators.minLength(3)]],
      descripcion: ['', [Validators.maxLength(500)]],
      estado: ['pendiente', [Validators.required]],
      fechaLimite: [null], // sin validación personalizada; el input type="date" ayuda
    });

    if (this.initialValue) {
      this.form.patchValue(this.initialValue);
    }
  }

  onSubmit(): void {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }
    this.submitted.emit(this.form.getRawValue() as TaskPayload);
  }
}
