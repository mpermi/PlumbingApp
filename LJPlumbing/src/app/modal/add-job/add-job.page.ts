import { Component, AfterViewInit } from '@angular/core';
import { ModalController } from '@ionic/angular';

@Component({
  selector: 'app-add-job',
  templateUrl: './add-job.page.html',
  styleUrls: ['./add-job.page.scss'],
})
export class AddJobPage implements AfterViewInit {
  calendar = {
    mode: 'month',
    currentDate: new Date()
  };
  viewTitle: string;
  
  event = {
    customer: '',
    phone: '',
    address: '',
    city: '',
    state: '',
    zip: '',
    startTime: null,
    endTime: '',
    issue: '',
    allDay: false
  };
 
  modalReady = false;

  constructor(private modalCtrl: ModalController) { }

  ngAfterViewInit() {
    setTimeout(() => {
      this.modalReady = true;      
    }, 0);
  }
 
  save() {    
    this.modalCtrl.dismiss({event: this.event})
  }
 
  onViewTitleChanged(title) {
    this.viewTitle = title;
  }
 
  onTimeSelected(ev) {    
    this.event.startTime = new Date(ev.selectedTime);
  }
 
  close() {
    this.modalCtrl.dismiss();
  }

}
