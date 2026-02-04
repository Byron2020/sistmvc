import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { FormsModule } from '@angular/forms';

import {
  IonApp,
  IonSplitPane,
  IonMenu,
  IonContent,
  IonList,
  IonSelect,
  IonSelectOption,
  IonListHeader,
  IonNote,
  IonMenuToggle,
  IonItem,
  IonIcon,
  IonLabel,
  IonRouterOutlet,
  IonRouterLink,
} from '@ionic/angular/standalone';

import { addIcons } from 'ionicons';
import {
  homeOutline,
  homeSharp,
  bookmarkOutline,
  bookmarkSharp,
  peopleOutline,
  peopleCircleOutline,
  personAddOutline
} from 'ionicons/icons';
import { AuthService } from './services/auth-service';

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
  imports: [
    FormsModule,
    CommonModule,
    RouterLink,
    RouterLinkActive,
    IonApp,
    IonSplitPane,
    IonMenu,
    IonContent,
    IonList,
    IonSelect,
    IonSelectOption,
    IonListHeader,
    IonNote,
    IonMenuToggle,
    IonItem,
    IonIcon,
    IonLabel,
    IonRouterLink,
    IonRouterOutlet,
  ],
})
export class AppComponent {
  labels = ['Family', 'Friends', 'Work', 'Travel', 'Reminders'];
  user: any = null;
  usuarios: any[] = [];
  selectedUser: string = '';

  appPages = [
    { title: 'Inicio', url: '/home', ios: 'home-outline', md: 'home-sharp' },
    // Puedes agregar más páginas aquí
  ];

  constructor(public auth: AuthService) {
    //CArgar datos sin recargar la pagina
    this.auth.user$.subscribe((u) => {
      this.user = u;
      // SOLO cuando ya hay usuario
      if (u) {
        this.cargarUsuarios();
      }
    });
    // Registrar íconos
    addIcons({
      homeOutline,
      homeSharp,
      bookmarkOutline,
      bookmarkSharp,
      peopleOutline,
      peopleCircleOutline,
      personAddOutline
    });
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      this.user = JSON.parse(storedUser);
    }
  }
  ngOnInit() {
    this.loadUsuarios();
  }
  cargarUsuarios() {
    this.auth.getUsuarios().subscribe((response) => {
      this.usuarios = response.filter((u) => u.id_usuario !== this.user.id);
    });
  }
  loadUsuarios() {
    this.auth.getUsuarios().subscribe((data) => {
      if (!this.user) {
        return;
      }
      this.usuarios = data.filter((u) => u.id_usuario !== this.user.id);
    });
  }
  logout() {
    this.auth.logout();
  }

  get isLogged() {
    return !!this.user;
  }
}
