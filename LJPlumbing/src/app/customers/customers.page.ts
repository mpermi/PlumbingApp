import { Component, OnInit } from '@angular/core';
import { AlertController, ModalController, ToastController } from '@ionic/angular';
import { EditCustomerPage } from '../modal/edit-customer/edit-customer.page';
import { CustomerService } from '../services/customer.service';
import { ViewCustomerPage } from '../modal/view-customer/view-customer.page';

@Component({
  selector: 'app-customers',
  templateUrl: './customers.page.html',
  styleUrls: ['./customers.page.scss'],
})
export class CustomersPage implements OnInit {
	customers = null;
  toast = null;

  constructor(
  	private alertCtrl: AlertController,
    private modalCtrl: ModalController,
    private customerService: CustomerService,
    private toastCtrl: ToastController
  ) { }

  ngOnInit() {
  	this.customerService.getCustomers().subscribe(result => {
      this.customers = result.data;
    });
  }

  async viewCustomer(customer) {
    const modal = await this.modalCtrl.create({
      component: ViewCustomerPage,
      componentProps: {
        customerForm: customer
      },
      backdropDismiss: false
    });
   
    await modal.present();
   
    modal.onDidDismiss().then((result) => {

    });
  } 

  async editCustomer(customer) {
	  const modal = await this.modalCtrl.create({
	    component: EditCustomerPage,
	    componentProps: {
        customerForm: customer
      },
	    backdropDismiss: false
	  });
	 
	  await modal.present();
	 
	  modal.onDidDismiss().then((result) => {
      this.loadCustomers();
	  });
	} 

  async addCustomer() {
	  const modal = await this.modalCtrl.create({
	    component: EditCustomerPage,
	    componentProps: {
        customerForm: {
          first_name: '',
          last_name: '',
          phone: '',
          address_id: '',
          address1: '',
          address2: '',
          city: '',
          state: '',
          zip: ''
        }
      },
	    backdropDismiss: false
	  });
	 
	  await modal.present();
	 
	  modal.onDidDismiss().then((result) => {
	  	this.loadCustomers();
	  });
	}

  async deleteCustomerAlert(customer) {
    const alert = await this.alertCtrl.create({
      header: 'Delete Customer',
      message: 'Are you sure you want to delete this customer?',
      buttons: [
      {
        text: 'No',
        role: 'cancel'
      },
      {
        text: 'Yes',
        handler: () => {
          this.deleteCustomer(customer.customer_id);
        }
      }
      ]
    });
    await alert.present();
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

  public loadCustomers() {
    this.customerService.getCustomers().subscribe(result => {
      this.customers = result.data;
    });
  }

  public deleteCustomer(customer_id) {
    this.customerService.deleteCustomer(customer_id).subscribe(result => {
      if (result.status =='success') {
        this.loadCustomers();
      } else {
        this.showAlert('There was an error deleting this customer', 'danger');
      }
    });
  }	 

}
