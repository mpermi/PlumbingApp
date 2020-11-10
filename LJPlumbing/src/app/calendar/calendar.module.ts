import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { CalendarPageRoutingModule } from './calendar-routing.module';
import { NgCalendarModule  } from 'ionic2-calendar';
import { AddJobPageModule } from '../modal/add-job/add-job.module';

import { CalendarPage } from './calendar.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    CalendarPageRoutingModule,
    NgCalendarModule,
    AddJobPageModule
  ],
  declarations: [CalendarPage]
})
export class CalendarPageModule {}
