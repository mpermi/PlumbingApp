import { CalendarComponent } from 'ionic2-calendar';
import { Component, ViewChild, OnInit } from '@angular/core';
import { AlertController, ModalController, ToastController } from '@ionic/angular';
import { formatDate } from '@angular/common';
import { EditJobPage } from '../modal/edit-job/edit-job.page';
import { JobService } from '../services/job.service';

@Component({
  selector: 'app-calendar',
  templateUrl: './calendar.page.html',
  styleUrls: ['./calendar.page.scss'],
})
export class CalendarPage implements OnInit {
	eventSource = [];
  viewTitle: string;
  toast = null;

  calendar = {
    mode: 'month',
    currentDate: new Date(),
  };

  selectedDate: Date;

  @ViewChild(CalendarComponent) calendarComponent: CalendarComponent;

  constructor(
  	private alertCtrl: AlertController,
    private modalCtrl: ModalController,
    private jobService: JobService,
    private toastCtrl: ToastController
  ) { }

  ngOnInit() {
    this.loadEvents();
  }

  next() {
    this.calendarComponent.slideNext();
  }
 
  back() {
    this.calendarComponent.slidePrev();
  }
 
  onViewTitleChanged(title) {
    this.viewTitle = title;
  }

  //select calendar event
  async viewEvent(event) {
    let start = formatDate(event.startTime, 'EE, M/d/yy, h:mm a', 'en-US');

    const alert = await this.alertCtrl.create({
      header: event.customer,
      subHeader: event.issue,
      message: '<b>Time:</b> ' + start + '<br> <b>Employee:</b> ' + event.employee + '<br> <b>Address:</b> ' + event.address + '<br> <b>Phone:</b> ' + event.phone,
      buttons: [
        {text: 'Ok'},
        {
          text: 'Edit Job',
          handler: () => {
            this.editJob(event);
          }
        },        
        {
          text: 'Cancel Job',
          handler: () => {
            this.deleteJob(event.job_id);
          }
        },
      ],
    });
    alert.present();
  }

  loadEvents () {
    this.jobService.getJobs().subscribe(result => {
      if (result.status == 'success') {
        this.eventSource = [];

        result.data.forEach(event => {
          let calendarEvent = {
            title: event.customer_first_name + ' ' + event.customer_last_name,
            customer: event.customer_first_name + ' ' + event.customer_last_name,
            customer_id: event.customer_id,
            employee: event.employee_first_name + ' ' + event.employee_last_name,
            employee_id: event.employee_id,
            phone: event.customer_phone,
            address: event.customer_address1 + ' ' + event.customer_address2 + ' <br>' + event.customer_city + ' ' + event.customer_state + ' ' + event.customer_zipcode,
            startTime: new Date(event.date),
            endTime: new Date(event.date),
            issue: event.issue,
            job_id: event.job_id,
            allDay: false
          }

          let start = calendarEvent.startTime;
          calendarEvent.startTime = new Date(
            Date.UTC(
              start.getUTCFullYear(),
              start.getUTCMonth(),
              start.getUTCDate(),
              start.getUTCHours(),
              start.getUTCMinutes()
            )
          );        

          calendarEvent.endTime = new Date(
            Date.UTC(
              start.getUTCFullYear(),
              start.getUTCMonth(),
              start.getUTCDate(),
              start.getUTCHours() + 1,
              start.getUTCMinutes()
            )
          );

          this.eventSource.push(calendarEvent);
          
        });
        this.calendarComponent.loadEvents();
      } else {
        this.showAlert(result.data, 'danger');
      }
    });
  }
  //add a job to  the calendar
	async addJob() {
	  const modal = await this.modalCtrl.create({
	    component: EditJobPage,
      componentProps: {
        jobForm: {
          customer_id: '',
          issue: '',
          date: '',
          employee_id: ''}
      },
	    backdropDismiss: false
	  });
	 
	  await modal.present();
	 
	  modal.onDidDismiss().then((result) => {
      this.loadEvents();
	  });
	}

  async deleteJob(job_id) {
    this.jobService.deleteJob(job_id).subscribe(result => {
      if (result.status =='success') {
        this.loadEvents();
      } else {
        this.showAlert('There was an error deleting this job', 'danger');
      }
    });
  }

  async editJob(event) {
    const modal = await this.modalCtrl.create({
      component: EditJobPage,
      componentProps: {
        jobForm: {
          customer_id: event.customer_id,
          customer: event.customer,
          issue: event.issue,
          date: event.startTime.toISOString(),
          employee_id: event.employee_id,
          employee: event.employee,
          job_id: event.job_id}
      },
      backdropDismiss: false
    });
   
    await modal.present();
   
    modal.onDidDismiss().then((result) => {
      this.loadEvents();
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
