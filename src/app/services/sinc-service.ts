import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root',
})
export class SincService {
  constructor(private http: HttpClient) {}

  // datos masivos (70k aprox)
 
  getDatos(limit = 1000, offset = 0) {
  return this.http.get<any>(
    `${environment.apiUrl}datos.php?limit=${limit}&offset=${offset}`
  );
}
}
