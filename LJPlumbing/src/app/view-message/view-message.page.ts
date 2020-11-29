import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { MessageService } from '../services/message.service';
import { ToastController} from '@ionic/angular';

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

  constructor(
  	private activatedRoute: ActivatedRoute, 
  	private router: Router,
  	private messageService: MessageService,
  	private toastCtrl: ToastController) { 

    this.activatedRoute.queryParams.subscribe(params => {
      if (params && params.from_phone) {
        this.from_phone = JSON.parse(params.from_phone);
        this.customer_id = JSON.parse(params.customer_id);
      }
    });
  }

  ngOnInit() {
  	this.messageService.getConversation(this.from_phone).subscribe(result => {
      this.messages = result.data;
    });
  }

  public loadConversation() {
    console.log('dfsdfsdf');
  	this.messageService.getConversation(this.from_phone).subscribe(result => {
      this.messages = result.data;
    });
  }

  sendMesssage() {
  	//TODO replace with a logged in employee id
    this.messageService.sendMessage('2', this.from_phone, this.customer_id, this.sendMessageText).subscribe(result => {
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
