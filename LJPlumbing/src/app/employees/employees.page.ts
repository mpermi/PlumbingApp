import { Component, OnInit } from '@angular/core';
import { AlertController, ModalController, ToastController } from '@ionic/angular';
import { EditEmployeePage } from '../modal/edit-employee/edit-employee.page';
import { EmployeeService } from '../services/employee.service';
import { ViewEmployeePage } from '../modal/view-employee/view-employee.page';

@Component({
  selector: 'app-employees',
  templateUrl: './employees.page.html',
  styleUrls: ['./employees.page.scss'],
})
export class EmployeesPage implements OnInit {
	employees = null;
  toast = null;

  constructor(
  	private alertCtrl: AlertController,
    private modalCtrl: ModalController,
    private employeeService: EmployeeService,
    private toastCtrl: ToastController
   ) { }

  ngOnInit() {
  	this.employeeService.getEmployees().subscribe(result => {
      this.employees = result.data;
    });

  }

  async viewEmployee(employee) {
    const modal = await this.modalCtrl.create({
      component: ViewEmployeePage,
      componentProps: {
        employeeForm: employee
      },
      backdropDismiss: false
    });
   
    await modal.present();
   
    modal.onDidDismiss().then((result) => {

    });
  } 

  async editEmployee(employee) {
	  const modal = await this.modalCtrl.create({
	    component: EditEmployeePage,
	    componentProps: {
        employeeForm: employee
      },
	    backdropDismiss: false
	  });
	 
	  await modal.present();
	 
	  modal.onDidDismiss().then((result) => {
      this.loadEmployees();
	  });
	} 

  async addEmployee() {
    const modal = await this.modalCtrl.create({
      component: EditEmployeePage,
      componentProps: {
        employeeForm: {
          first_name: '',
          last_name: '',
          username: '',
          password: '',
          phone: '',
          title: '',
          title_id: ''
        }
      },
      backdropDismiss: false
    });
   
    await modal.present();
   
    modal.onDidDismiss().then((result) => {
      this.loadEmployees();
    });
  }

  async deleteEmployeeAlert(employee) {
    const alert = await this.alertCtrl.create({
      header: 'Delete Employee',
      message: 'Are you sure you want to delete this employee?',
      buttons: [
      {
        text: 'No',
        role: 'cancel'
      },
      {
        text: 'Yes',
        handler: () => {
          this.deleteEmployee(employee.employee_id);
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

  public loadEmployees() {
    this.employeeService.getEmployees().subscribe(result => {
      this.employees = result.data;
    });
  }

  public deleteEmployee(employee_id) {
    this.employeeService.deleteEmployee(employee_id).subscribe(result => {
      if (result.status =='success') {
        this.loadEmployees();
      } else {
        this.showAlert('There was an error deleting this employee', 'danger');
      }
    });
  }

}
