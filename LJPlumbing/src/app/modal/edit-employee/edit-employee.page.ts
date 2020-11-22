import { Component, OnInit } from '@angular/core';
import {AlertController, ModalController } from '@ionic/angular';
import { EmployeeService } from '../../services/employee.service';
import { TitleService } from '../../services/title.service';

@Component({
  selector: 'app-edit-employee',
  templateUrl: './edit-employee.page.html',
  styleUrls: ['./edit-employee.page.scss'],
})
export class EditEmployeePage implements OnInit {
  employee;
  title;

  constructor(
    private modalCtrl: ModalController,
    private alertCtrl: AlertController,
    private employeeService: EmployeeService,
    private titleService: TitleService) { }

  ngOnInit() {
    this.titleService.getTitles().subscribe(result => {
      this.title = result.data;
    });
  }

  close() {
    this.modalCtrl.dismiss();
  }
}
