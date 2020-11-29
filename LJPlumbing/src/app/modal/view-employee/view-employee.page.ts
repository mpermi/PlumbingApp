import { Component, OnInit } from '@angular/core';
import {AlertController, ModalController } from '@ionic/angular';
import { EmployeeService } from '../../services/employee.service';
import { TitleService } from '../../services/title.service';

@Component({
  selector: 'app-view-employee',
  templateUrl: './view-employee.page.html',
  styleUrls: ['./view-employee.page.scss'],
})
export class ViewEmployeePage implements OnInit {
	employeeForm;
  titles;

  constructor(
  	private modalCtrl: ModalController,
    private alertCtrl: AlertController,
    private employeeService: EmployeeService,
    private titleService: TitleService) { }

  ngOnInit() {
  }

  close() {
    this.modalCtrl.dismiss();
  }
}
