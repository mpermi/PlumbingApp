import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class TitleService {
	apiURL = 'https://354c1d3d5fd8.ngrok.io/api/title.php/';

  constructor(private http: HttpClient, private authService: AuthService) { }

  getTitles(): Observable<any> {
  	return this.http.get(this.apiURL + '?request=find&token=' + this.authService.token);
  }
}
