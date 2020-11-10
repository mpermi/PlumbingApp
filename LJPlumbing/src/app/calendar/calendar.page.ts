import { CalendarComponent } from 'ionic2-calendar';
import { Component, ViewChild, OnInit } from '@angular/core';
import { AlertController, ModalController } from '@ionic/angular';
import { formatDate } from '@angular/common';
import { AddJobPage } from '../modal/add-job/add-job.page';

@Component({
  selector: 'app-calendar',
  templateUrl: './calendar.page.html',
  styleUrls: ['./calendar.page.scss'],
})
export class CalendarPage implements OnInit {
	eventSource = [];
  viewTitle: string;

  calendar = {
    mode: 'month',
    currentDate: new Date(),
  };

  selectedDate: Date;

  @ViewChild(CalendarComponent) myCal: CalendarComponent;

  constructor(
  	private alertCtrl: AlertController,
    private modalCtrl: ModalController
  ) { }

  ngOnInit() {
  }

    // Change current month/week/day
  next() {
    this.myCal.slideNext();
  }
 
  back() {
    this.myCal.slidePrev();
  }
 
  onViewTitleChanged(title) {
    this.viewTitle = title;
  }

  //select calendar event
  async viewEvent(event) {
    let start = formatDate(event.startTime, 'medium', 'en-US');

    const alert = await this.alertCtrl.create({
      header: event.customer,
      subHeader: event.issue,
      message: 'Time: ' + start + '<br>',
      buttons: ['OK', 'Cancel Job'],
    });
    alert.present();
  }
 
  removeEvent(event_id) {
    this.eventSource = [];
  }

	async addJob() {
	  const modal = await this.modalCtrl.create({
	    component: AddJobPage,
	    backdropDismiss: false
	  });
	 
	  await modal.present();
	 
	  modal.onDidDismiss().then((result) => {
	    if (result.data && result.data.event) {
	      let event = {
          title: result.data.event.customer,
	      	customer: result.data.event.customer,
          phone: result.data.event.phone,
          address: result.data.event.address,
          city: result.data.event.city,
          state: result.data.event.state,
          zip: result.data.event.zip,
          startTime: new Date(result.data.event.startTime),
          endTime: new Date(result.data.event.startTime),
          issue: result.data.event.issue,
	      	allDay: result.data.event.allDay
	      }
	      result.data.event;

        let start = event.startTime;

        event.startTime = new Date(
          Date.UTC(
            start.getUTCFullYear(),
            start.getUTCMonth(),
            start.getUTCDate(),
            start.getUTCHours(),
            start.getUTCMinutes()
          )
        );      	

        event.endTime = new Date(
          Date.UTC(
            start.getUTCFullYear(),
            start.getUTCMonth(),
            start.getUTCDate(),
            start.getUTCHours() + 1,
            start.getUTCMinutes()
          )
        );

	      this.eventSource.push(event);
	      this.myCal.loadEvents();
	    }
	  });
	}    
}
