import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import {
  IonButton,
  IonButtons,
  IonMenuButton,
  IonInput,
  IonItem,
  IonLabel,
  IonContent,
  IonHeader,
  IonToolbar,
  IonTitle,
  IonToast,
  IonSpinner,
} from '@ionic/angular/standalone';

import { AuthService } from 'src/app/services/auth-service';
import { SincService } from 'src/app/services/sinc-service';
import { Database } from 'src/app/services/database';
import { firstValueFrom } from 'rxjs';
import { Capacitor } from '@capacitor/core';

@Component({
  selector: 'app-home',
  templateUrl: './home.page.html',
  styleUrls: ['./home.page.scss'],
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    FormsModule,
    IonButtons,
    IonMenuButton,
    IonButton,
    IonInput,
    IonItem,
    IonLabel,
    IonContent,
    IonHeader,
    IonToolbar,
    IonTitle,
    IonToast,
    IonSpinner,
  ],
})
export class HomePage implements OnInit {
  loading = false;
  progress = 0;
  searchText = '';
  resultados: any[] = [];

  constructor(
    private auth: AuthService,
    private sincService: SincService,
    private database: Database,
  ) {}

  ngOnInit() {
    this.syncDatos();
    this.sincService.getDatos().subscribe((res) => {
      console.log('Total registros:', res.total);
      console.log('Primer lote:', res.data.length);
    });
  }
  logout() {
    this.auth.logout();
  }
  //Funcion prncipal
  async syncDatos() {
    this.loading = true;

    const db = await this.database.openDatabase();

    let offset = 0;
    const limit = 1000;
    let imported = 0;
    let total = 0;

    do {
      const res = await firstValueFrom(
        this.sincService.getDatos(limit, offset),
      );

      total = res.total;

      await db.execute('BEGIN TRANSACTION');
      //id, cuent, contr, porci, mru, secto
      for (const r of res.data) {
        await db.run(
          `INSERT OR REPLACE INTO datos (id, cuent, contr, porci, mru, secto)
         VALUES (?, ?, ?, ?, ?, ?)`,
          [r.id, r.campo1, r.campo2, r.campo3],
        );
      }

      await db.execute('COMMIT');

      imported += res.data.length;
      offset += limit;

      this.progress = Math.round((imported / total) * 100);
      console.log(`⏳ ${this.progress}%`);
    } while (imported < total);

    this.loading = false;
    console.log('✅ Importación completa');
  }
  async buscar() {
    if (!this.searchText.trim()) {
      this.resultados = [];
      return;
    }

    const db = await this.database.openDatabase();

    const res = await db.query(
      `SELECT * FROM t_datos
     WHERE cuent LIKE ?
     OR secto LIKE ?
     LIMIT 50`,
      [`%${this.searchText}%`, `%${this.searchText}%`],
    );

    this.resultados = res.values ?? [];
  }
}
