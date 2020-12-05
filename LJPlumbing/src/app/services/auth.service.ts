import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { Storage } from '@ionic/storage';
import { Platform } from '@ionic/angular';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
	authState = new BehaviorSubject(false);
	token = '';
	apiURL = 'https://354c1d3d5fd8.ngrok.io/api/employee.php/';

  constructor(private http: HttpClient, private storage: Storage, private platform: Platform) { 
    this.platform.ready().then(() => {
      this.checkToken();
    });
  }

  checkToken() {
    this.storage.get('token').then(result => {
      if (result) {
        this.token = result;
        this.authState.next(true);
      }
    })
  }
 
  isLoggedIn() {
    this.storage.get('token').then(result => {
      if (result) {
        this.token = result;
      }
    });

    return this.authState.value;
  }

  logout() {
    this.token = '';
    return this.storage.remove('token').then(() => {
      this.authState.next(false);
    });
  } 

  setToken(token) {
    this.token = token;
    return this.storage.set('token', token).then(() => {
      this.authState.next(true);
    });
  } 

  login(username, password): Observable<any> {
  	let headers = new HttpHeaders({'Content-Type' : 'application/x-www-form-urlencoded'});
  	let body = new HttpParams().set('request', 'login').set('username', username).set('password', password).toString();

  	return this.http.post(this.apiURL, body, {headers: headers});
  }
 
}