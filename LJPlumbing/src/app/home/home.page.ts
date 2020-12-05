import { Component } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { MessageService } from '../services/message.service';
import { EmployeeService } from '../services/employee.service';
import { AuthService } from '../services/auth.service';

@Component({
  selector: 'app-home',
  templateUrl: 'home.page.html',
  styleUrls: ['home.page.scss'],
})
export class HomePage {
	unreadMessageTotal = 0;
  toast = null;

  constructor(
  	private messageService: MessageService,
    private employeeService: EmployeeService,
    private toastCtrl: ToastController,
    private authService: AuthService,
  ) {}

  ngOnInit() {
  	this.messageService.getUnreadTotal().subscribe(result => {
      if (result.status == 'success') {
        this.unreadMessageTotal = result.data;
      } else {
        this.showAlert(result.data, 'danger');
      }
    });
  }

  async showAlert(message, color) {
    if (this.toast) {
      this.toast.dismiss();
    }

    this.toast = await this.toastCtrl.create({
      message: message,
      duration:3000,
      position: 'bottom',
      color: color
    });
    this.toast.present();
  }

  logout() {
    this.authService.logout();
  }    
}
