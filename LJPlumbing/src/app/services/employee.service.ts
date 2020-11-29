import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class EmployeeService {
	apiURL = 'https://354c1d3d5fd8.ngrok.io/api/employee.php/';

  constructor(private http: HttpClient) { }

  getEmployees(): Observable<any> {
  	return this.http.get(this.apiURL + '?request=find');
  }

  deleteEmployee(employee_id): Observable<any> {
  	let headers = new HttpHeaders({'Content-Type' : 'application/x-www-form-urlencoded'});
  	let body = new HttpParams().set('request', 'delete').set('employee_id', employee_id).toString();

  	return this.http.post(this.apiURL, body, {headers: headers});
  } 

  addEmployee(employeeForm): Observable<any> {
  	employeeForm.request='add';
  	let headers = new HttpHeaders({'Content-Type' : 'application/x-www-form-urlencoded'});
  	let body = new HttpParams({fromObject: employeeForm}).toString();

  	return this.http.post(this.apiURL, body, {headers: headers});
  } 

  updateEmployee(employeeForm): Observable<any> {
  	employeeForm.request='update';
  	let headers = new HttpHeaders({'Content-Type' : 'application/x-www-form-urlencoded'});
  	let body = new HttpParams({fromObject: employeeForm}).toString();

  	return this.http.post(this.apiURL, body, {headers: headers});
  }    
}
