import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class JobService {
	apiURL = 'https://354c1d3d5fd8.ngrok.io/api/job.php/';

  constructor(private http: HttpClient) { }

  getJobs(): Observable<any> {
  	return this.http.get(this.apiURL + '?request=find');
  }

  addJob(jobForm): Observable<any> {
  	jobForm.request='add';
  	let headers = new HttpHeaders({'Content-Type' : 'application/x-www-form-urlencoded'});
  	let body = new HttpParams({fromObject: jobForm}).toString();

  	return this.http.post(this.apiURL, body, {headers: headers});
  }

  deleteJob(job_id): Observable<any> {
  	let headers = new HttpHeaders({'Content-Type' : 'application/x-www-form-urlencoded'});
  	let body = new HttpParams().set('request', 'delete').set('job_id', job_id).toString();

  	return this.http.post(this.apiURL, body, {headers: headers});
  }   
}
