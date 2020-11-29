import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class CustomerService {

  constructor(private http: HttpClient) { }
	apiURL = 'https://354c1d3d5fd8.ngrok.io/api/customer.php/';

  getCustomers(): Observable<any> {
  	return this.http.get(this.apiURL + '?request=find');
  }

  deleteCustomer(customer_id): Observable<any> {
  	let headers = new HttpHeaders({'Content-Type' : 'application/x-www-form-urlencoded'});
  	let body = new HttpParams().set('request', 'delete').set('customer_id', customer_id).toString();

  	return this.http.post(this.apiURL, body, {headers: headers});
  } 

  addCustomer(customerForm): Observable<any> {
  	customerForm.request='add';
  	let headers = new HttpHeaders({'Content-Type' : 'application/x-www-form-urlencoded'});
  	let body = new HttpParams({fromObject: customerForm}).toString();

  	return this.http.post(this.apiURL, body, {headers: headers});
  } 

  updateCustomer(customerForm): Observable<any> {
  	customerForm.request='update';
  	let headers = new HttpHeaders({'Content-Type' : 'application/x-www-form-urlencoded'});
  	let body = new HttpParams({fromObject: customerForm}).toString();

  	return this.http.post(this.apiURL, body, {headers: headers});
  }  
}
