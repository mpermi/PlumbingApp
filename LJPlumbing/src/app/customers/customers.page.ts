import { Component, OnInit } from '@angular/core';
import { AlertController, ModalController } from '@ionic/angular';
import { EditCustomerPage } from '../modal/edit-customer/edit-customer.page';

@Component({
  selector: 'app-customers',
  templateUrl: './customers.page.html',
  styleUrls: ['./customers.page.scss'],
})
export class CustomersPage implements OnInit {

  constructor(
  	private alertCtrl: AlertController,
    private modalCtrl: ModalController
  ) { }

  ngOnInit() {
  }

  async addCustomer() {
	  const modal = await this.modalCtrl.create({
	    component: EditCustomerPage,
	    backdropDismiss: false
	  });
	 
	  await modal.present();
	 
	  modal.onDidDismiss().then((result) => {

	  });
	} 

}
