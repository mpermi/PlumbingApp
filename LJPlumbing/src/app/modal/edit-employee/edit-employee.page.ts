import { Component, OnInit } from '@angular/core';
import {AlertController, ModalController, ToastController} from '@ionic/angular';
import { EmployeeService } from '../../services/employee.service';
import { TitleService } from '../../services/title.service';

@Component({
  selector: 'app-edit-employee',
  templateUrl: './edit-employee.page.html',
  styleUrls: ['./edit-employee.page.scss'],
})
export class EditEmployeePage implements OnInit {
  employeeForm;
  titles;
  toast = null;

  constructor(
    private modalCtrl: ModalController,
    private alertCtrl: AlertController,
    private employeeService: EmployeeService,
    private titleService: TitleService,
    private toastCtrl: ToastController) { }

  ngOnInit() {
    this.titleService.getTitles().subscribe(result => {
      this.titles = result.data;
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

  saveEmployee() {
    if (this.employeeForm.employee_id === undefined) {
      this.employeeService.addEmployee(this.employeeForm).subscribe(result => {
        if (result.status =='success') {
          this.modalCtrl.dismiss();
        } else {
          this.showAlert('There was an error creating this employee', 'danger');
        }
      });
    } else {
      this.employeeService.updateEmployee(this.employeeForm).subscribe(result => {
        if (result.status =='success') {
          this.modalCtrl.dismiss();
        } else {
          this.showAlert('There was an error editing this employee', 'danger');
        }
      });
    }

  }

  close() {
    this.modalCtrl.dismiss();
  }
}
