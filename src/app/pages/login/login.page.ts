import { Component, OnInit } from '@angular/core';
import {
  FormsModule,
  ReactiveFormsModule,
  FormBuilder,
  FormGroup,
  Validators,
} from '@angular/forms';
import {
  IonButton,
  IonInput,
  IonItem,
  IonLabel,
  IonContent,
  IonHeader,
  IonToolbar,
  IonTitle,
  IonToast
} from '@ionic/angular/standalone';

import { AuthService } from 'src/app/services/auth-service';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';


@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    FormsModule,
    IonButton,
    IonInput,
    IonItem,
    IonLabel,
    IonContent,
    IonHeader,
    IonToolbar,
    IonTitle,
    IonToast,
  ]
})
export class LoginPage implements OnInit {
  password = '';
  loading = false;

  loginForm: FormGroup;
  toastMessage = '';

  constructor(
    private fb: FormBuilder,
    private auth: AuthService,
    private router: Router,
  ) {
    this.loginForm = this.fb.group({
      cedula: [
        '',
        [
          Validators.required,
          Validators.pattern(/^\d{10}$/), // exactamente 10 dígitos
        ],
      ],
      password: ['', Validators.required],
    });
  }

  ngOnInit() {}

  
  login() {
    
    if (this.loginForm.invalid) {
      this.toastMessage = 'Cédula o contraseña inválida';
      return;
    }

    this.loading = true;

    const { cedula, password } = this.loginForm.value;

    this.auth.login(cedula, password).subscribe({
      next: (res) => {
        this.loading = false;

        if (res.success) {
          localStorage.setItem('auth_token', res.token);
          localStorage.setItem('auth_expires', res.expires);
          localStorage.setItem('user', JSON.stringify(res.user));
          this.auth.setUser(res.user); // CLAVE

          this.router.navigate(['/home']);
        } else {
          this.toastMessage = res.message;
        }
      },
      error: (err) => {
        console.error('HTTP ERROR:', err);
        this.loading = false;
        this.toastMessage = 'Error al conectarse al servidor';
      },
    });
  }
}
