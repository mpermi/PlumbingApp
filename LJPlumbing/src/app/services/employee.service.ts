import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class EmployeeService {
	apiURL = 'https://047a9a44549b.ngrok.io/api/employee.php/';

  constructor(private http: HttpClient) { }

  getEmployees(): Observable<any> {
  	return this.http.get(this.apiURL + '?request=find');
  }
}
