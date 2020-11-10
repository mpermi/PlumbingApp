import { Component, OnInit } from '@angular/core';
import { AlertController, ModalController } from '@ionic/angular';
import { EditEmployeePage } from '../modal/edit-employee/edit-employee.page';

@Component({
  selector: 'app-employees',
  templateUrl: './employees.page.html',
  styleUrls: ['./employees.page.scss'],
})
export class EmployeesPage implements OnInit {

  constructor(
  	private alertCtrl: AlertController,
    private modalCtrl: ModalController
   ) { }

  ngOnInit() {
  }

  async addEmployee() {
	  const modal = await this.modalCtrl.create({
	    component: EditEmployeePage,
	    backdropDismiss: false
	  });
	 
	  await modal.present();
	 
	  modal.onDidDismiss().then((result) => {

	  });
	} 

}
