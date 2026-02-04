import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { environment } from 'src/environments/environment';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private userSubject = new BehaviorSubject<any>(null);
  user$ = this.userSubject.asObservable();

  constructor(
    private http: HttpClient,
    private router: Router,
  ) {
    const user = localStorage.getItem('user');
    if (user) {
      this.userSubject.next(JSON.parse(user));
    }
  }

  setUser(user: any) {
    localStorage.setItem('user', JSON.stringify(user));
    this.userSubject.next(user);
  }

  getUsuarios() {
    return this.http.get<any[]>(environment.apiUrl + 'usuarios.php');
  }

  login(cedula: string, password: string) {
    return this.http.post<any>(
      environment.apiUrl + 'login.php',
      {
        cedula: cedula,
        password: password,
      },
      {
        headers: { 'Content-Type': 'application/json' },
      },
    );
  }

  saveToken(token: string) {
    localStorage.setItem('token', token);
  }

  getToken(): string | null {
    return localStorage.getItem('auth_token');
  }

  logout() {
    localStorage.clear();
    this.userSubject.next(null);
    this.router.navigate(['/login']);
  }
  getUser() {
    return this.userSubject.value;
  }

  isLogged() {
    return !!this.userSubject.value;
  }
}
