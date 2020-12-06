import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { MessageService } from '../services/message.service';
import { ToastController} from '@ionic/angular';
import { EmployeeService } from '../services/employee.service';

@Component({
  selector: 'app-view-message',
  templateUrl: './view-message.page.html',
  styleUrls: ['./view-message.page.scss'],
})
export class ViewMessagePage implements OnInit {
	from_phone: any;
	customer_id: any;
	messages = null;
	phoneNumber = '14102614888';
	sendMessageText = '';
	sendMesssageForm;
	toast = null;
  currentEmployee = null;

  constructor(
  	private activatedRoute: ActivatedRoute, 
  	private router: Router,
  	private messageService: MessageService,
  	private toastCtrl: ToastController,
    private employeeService: EmployeeService,) { 

    this.activatedRoute.queryParams.subscribe(params => {
      if (params && params.from_phone) {
        this.from_phone = JSON.parse(params.from_phone);
        this.customer_id = JSON.parse(params.customer_id);
      }
    });
  }

  ngOnInit() {
  	this.messageService.getConversation(this.from_phone).subscribe(result => {
      if (result.status == 'success') {
        this.messages = result.data;
      } else {
        this.showAlert(result.data, 'danger');
      }
    });

    this.employeeService.getLoggedInEmployee().subscribe(result => {
      if (result.status == 'success') {
        this.currentEmployee = result.data;
      } else {
        this.showAlert(result.data, 'danger');
      }
    });      
  }

  public loadConversation() {
  	this.messageService.getConversation(this.from_phone).subscribe(result => {
      if (result.status == 'success') {
        this.messages = result.data;
      } else {
        this.showAlert(result.data, 'danger');
      } 
    });
  }

  sendMesssage() {
    this.messageService.sendMessage(this.currentEmployee.employee_id, this.from_phone, this.customer_id, this.sendMessageText).subscribe(result => {
      if (result.status =='success') {
      	this.sendMessageText = '';
        this.loadConversation();
      } else {
        this.showAlert('There was an error sending this message', 'danger');
      }
    });
  }

  async showAlert(message, color) {
    if (this.toast) {
      this.toast.dismiss();
    }

    this.toast = await this.toastCtrl.create({
      message: message,
      duration: 3000,
      position: 'bottom',
      color: color
    });
    this.toast.present();
  }  
}
