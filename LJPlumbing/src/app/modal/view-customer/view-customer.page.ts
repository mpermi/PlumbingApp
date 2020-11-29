import { Component, OnInit } from '@angular/core';
import {AlertController, ModalController } from '@ionic/angular';
import { CustomerService } from '../../services/customer.service';

@Component({
  selector: 'app-view-customer',
  templateUrl: './view-customer.page.html',
  styleUrls: ['./view-customer.page.scss'],
})
export class ViewCustomerPage implements OnInit {
	customerForm;

  constructor(
    private modalCtrl: ModalController,
    private alertCtrl: AlertController,
    private customerService: CustomerService) { }

  ngOnInit() {
  }

  close() {
    this.modalCtrl.dismiss();
  }
}
