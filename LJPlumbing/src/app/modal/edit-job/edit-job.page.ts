import { Component, AfterViewInit } from '@angular/core';
import { ModalController, ToastController } from '@ionic/angular';
import { EmployeeService } from '../../services/employee.service';
import { CustomerService } from '../../services/customer.service';
import { JobService } from '../../services/job.service';
import { FormGroup, FormControl, Validators, FormBuilder } from '@angular/forms';

@Component({
  selector: 'app-edit-job',
  templateUrl: './edit-job.page.html',
  styleUrls: ['./edit-job.page.scss'],
})
export class EditJobPage implements AfterViewInit {
  calendar = {
    mode: 'month',
    currentDate: new Date()
  };
  viewTitle: string;
  modalReady = false;
  jobForm;
  customers;
  employees;
  eventForm: FormGroup;
  toast = null;

  constructor(
    private modalCtrl: ModalController,
    private employeeService: EmployeeService,
    private customerService: CustomerService,
    private jobService: JobService,
    private formBuilder: FormBuilder,
    private toastCtrl: ToastController) { 

    this.eventForm = this.formBuilder.group({
      customer_id: ['', Validators.compose([Validators.required])],
      date: ['', Validators.compose([Validators.required])],
      issue: ['', Validators.compose([Validators.required])],
      employee_id: ['', Validators.compose([Validators.required])],
    });
  }

  ngAfterViewInit() {
    this.customerService.getCustomers().subscribe(result => {
      this.customers = result.data;
    });

    this.employeeService.getEmployees().subscribe(result => {
      this.employees = result.data;
    });    
    setTimeout(() => {
      this.modalReady = true;      
    }, 0);
  }
 
  saveJob() {
    if (this.jobForm.job_id === undefined) {
      this.jobService.addJob(this.jobForm).subscribe(result => {
        if (result.status =='success') {
          this.modalCtrl.dismiss({event: this.jobForm});
        } else {
          this.showAlert('There was an error creating this job', 'danger');
        }
      });
    } else {
      this.jobForm.updateJob(this.jobForm).subscribe(result => {
        if (result.status =='success') {
          this.modalCtrl.dismiss({event: this.jobForm});
        } else {
          this.showAlert('There was an error editing this job', 'danger');
        }
      });
    } 
  }
 
  onViewTitleChanged(title) {
    this.viewTitle = title;
  }
 
  onTimeSelected(ev) {    
    this.jobForm.date = new Date(ev.selectedTime);
  }
 
  close() {
    this.modalCtrl.dismiss();
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
