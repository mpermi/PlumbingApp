import { Component } from '@angular/core';
import { MessageService } from '../services/message.service';

@Component({
  selector: 'app-home',
  templateUrl: 'home.page.html',
  styleUrls: ['home.page.scss'],
})
export class HomePage {
	unreadMessageTotal = 0;

  constructor(
  	private messageService: MessageService) {}

  ngOnInit() {
  	this.messageService.getUnreadTotal().subscribe(result => {
      this.unreadMessageTotal = result.data;
    });
  }
}
