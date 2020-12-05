import { Component, OnInit } from '@angular/core';
import { AlertController, ModalController, ToastController } from '@ionic/angular';
import { MessageService } from '../services/message.service';
import { Router, NavigationExtras } from '@angular/router';

@Component({
  selector: 'app-messages',
  templateUrl: './messages.page.html',
  styleUrls: ['./messages.page.scss'],
})
export class MessagesPage implements OnInit {
	incomingMessages = null;
	toast = null;
	
  constructor(
  	private alertCtrl: AlertController,
    private modalCtrl: ModalController,
    private messageService: MessageService,
    private toastCtrl: ToastController,
    private router: Router) { }

  ngOnInit() {
  	this.messageService.getMessagesByDirection('incoming').subscribe(result => {
      if (result.status == 'success') {
        this.incomingMessages = result.data;
      } else {
        this.showAlert(result.data, 'danger');
      }
    });
  }

  public viewConversation(message_id, from_phone, customer_id) {
  	this.messageService.markRead(message_id).subscribe(result => {
      if (result.status =='success') {
		    let navParams: NavigationExtras = {
		      queryParams: {
		        from_phone: JSON.stringify(from_phone),
		        customer_id: JSON.stringify(customer_id)
		      }
		    };

		    this.router.navigate(['view-message'], navParams);
      } else {
        this.showAlert('There was an error viewing this message', 'danger');
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
