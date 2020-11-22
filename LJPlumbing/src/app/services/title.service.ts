import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class TitleService {
	apiURL = 'https://047a9a44549b.ngrok.io/api/title.php/';

  constructor(private http: HttpClient) { }

  getTitles(): Observable<any> {
  	return this.http.get(this.apiURL + '?request=find');
  }
}
