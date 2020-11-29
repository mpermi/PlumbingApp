import { Component, OnInit } from '@angular/core';
import {AlertController, ModalController, ToastController} from '@ionic/angular';
import { CustomerService } from '../../services/customer.service';

@Component({
  selector: 'app-edit-customer',
  templateUrl: './edit-customer.page.html',
  styleUrls: ['./edit-customer.page.scss'],
})
export class EditCustomerPage implements OnInit {
  customerForm;
  toast = null;
      
  constructor(
    private modalCtrl: ModalController,
    private alertCtrl: AlertController,
    private customerService: CustomerService,
    private toastCtrl: ToastController) { }

  ngOnInit() {
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

  saveCustomer() {
    if (this.customerForm.customer_id === undefined) {
      this.customerService.addCustomer(this.customerForm).subscribe(result => {
        if (result.status =='success') {
          this.modalCtrl.dismiss();
        } else {
          this.showAlert('There was an error creating this customer', 'danger');
        }
      });
    } else {
      this.customerService.updateCustomer(this.customerForm).subscribe(result => {
        if (result.status =='success') {
          this.modalCtrl.dismiss();
        } else {
          this.showAlert('There was an error editing this customer', 'danger');
        }
      });
    }
  }

  close() {
    this.modalCtrl.dismiss();
  }
}
