import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { AddJobPageRoutingModule } from './add-job-routing.module';

import { AddJobPage } from './add-job.page';

import { NgCalendarModule  } from 'ionic2-calendar';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    AddJobPageRoutingModule,
    NgCalendarModule
  ],
  declarations: [AddJobPage]
})
export class AddJobPageModule {}
