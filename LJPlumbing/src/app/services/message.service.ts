import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class MessageService {
	apiURL = 'https://354c1d3d5fd8.ngrok.io/api/message.php/';

  constructor(private http: HttpClient, private authService: AuthService) { }

  getMessages(): Observable<any> {
  	return this.http.get(this.apiURL + '?request=find&token=' + this.authService.token);
  }

  getMessagesByDirection(direction): Observable<any> {
  	return this.http.get(this.apiURL + '?request=find_by_direction&direction=' + direction + '&token=' + this.authService.token);
  }

  getUnreadTotal(): Observable<any> {
  	return this.http.get(this.apiURL + '?request=unread_total&token=' + this.authService.token);
  }

  getConversation(phone): Observable<any> {
    return this.http.get(this.apiURL + '?request=get_conversation&phone=' + phone + '&token=' + this.authService.token);
  }

  sendMessage(employee_id, phone, customer_id, message): Observable<any> {
    let headers = new HttpHeaders({'Content-Type' : 'application/x-www-form-urlencoded'});
    let body = new HttpParams().set('employee_id', employee_id).set('phone', phone).set('customer_id', customer_id).set('message', message).set('token', this.authService.token).toString();

    return this.http.post('https://354c1d3d5fd8.ngrok.io/api/plivo/send.php/', body, {headers: headers});
  }

  markRead(message_id): Observable<any> {
    let headers = new HttpHeaders({'Content-Type' : 'application/x-www-form-urlencoded'});
    let body = new HttpParams().set('request', 'update').set('message_id', message_id).set('read', '1').set('token', this.authService.token).toString();

    return this.http.post(this.apiURL, body, {headers: headers});
  }     
}
