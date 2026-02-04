import { Injectable } from '@angular/core';
import {
  SQLiteConnection,
  SQLiteDBConnection,
} from '@capacitor-community/sqlite';
import { Capacitor } from '@capacitor/core';

declare global {
  interface Window {
    CapacitorSQLite: any;
  }
}

@Injectable({
  providedIn: 'root',
})
export class Database {
  private sqlite!: SQLiteConnection;
  private db!: SQLiteDBConnection;
  private DB_NAME = 'data_local.db';

  constructor() {
    if (Capacitor.isNativePlatform()) {
      this.sqlite = new SQLiteConnection(window.CapacitorSQLite);
    }
  }

  async openDatabase() {
  this.db = await this.sqlite.createConnection(
    this.DB_NAME,
    false,
    'no-encryption',
    1,
    false
  );

  await this.db.open();
  //id, cuent, contr, porci, mru, secto
  await this.db.execute(`
    CREATE TABLE IF NOT EXISTS datos (
      id INTEGER PRIMARY KEY,
      cuent TEXT,
      contr TEXT,
      porci TEXT,
      mru TEXT,
      secto TEXT
    );
  `);

  await this.db.execute(`
    CREATE INDEX IF NOT EXISTS idx_campo1 ON datos(cuent);
  `);

  return this.db;
}

}
