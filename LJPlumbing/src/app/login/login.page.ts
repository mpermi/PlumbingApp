import { Component, OnInit } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { ToastController } from '@ionic/angular';

@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {
	username = '';
  password = '';
  toast = null;

  constructor(
  	private authService: AuthService,
  	private toastCtrl: ToastController
  ) { }

  ngOnInit() {
  }

  login() {
    this.authService.login(this.username, this.password).subscribe(result => {
      console.log(result);
      if (result.status =='success') {
        this.authService.setToken(result.data);
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
}
