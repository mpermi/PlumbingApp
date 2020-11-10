import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-view-message',
  templateUrl: './view-message.page.html',
  styleUrls: ['./view-message.page.scss'],
})
export class ViewMessagePage implements OnInit {
	messages = [
		{	
			customer_id: 0,
			employee_id: 0,
			to_phone: '410-232-2222',
			from_phone: '410-555-6666',
			message: 'Hello',
			date: '10/21/2020',
			direction: 'incoming'
		},
		{
			customer_id: 0,
			employee_id: 0,
			to_phone: '410-232-2222',
			from_phone: '410-555-6666',
			message: 'This is a test message',
			date: '10/22/2020',
			direction: 'incoming'
		},
		{
			customer_id: 0,
			employee_id: 1,
			to_phone: '410-555-6666',
			from_phone: '410-232-2222',
			message: 'Hello. Message received',
			date: '10/22/2020',
			direction: 'outgoing'
		}
	];

	phoneNumber = '410-232-2222';  //change this to the phone number assigned to our company
	sendMessage = '';
	
  constructor() { }

  ngOnInit() {
  }

}
