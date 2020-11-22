import { Component, OnInit } from '@angular/core';
import { AlertController, ModalController } from '@ionic/angular';
import { EditEmployeePage } from '../modal/edit-employee/edit-employee.page';
import { EmployeeService } from '../services/employee.service';

@Component({
  selector: 'app-employees',
  templateUrl: './employees.page.html',
  styleUrls: ['./employees.page.scss'],
})
export class EmployeesPage implements OnInit {
	employees = null;

  constructor(
  	private alertCtrl: AlertController,
    private modalCtrl: ModalController,
    private employeeService: EmployeeService
   ) { }

  ngOnInit() {
  	this.employeeService.getEmployees().subscribe(result => {
      this.employees = result.data;
    });

  }

  async editEmployee(employee) {
	  const modal = await this.modalCtrl.create({
	    component: EditEmployeePage,
	    componentProps: {
        employee: employee
      },
	    backdropDismiss: false
	  });
	 
	  await modal.present();
	 
	  modal.onDidDismiss().then((result) => {

	  });
	} 

}
