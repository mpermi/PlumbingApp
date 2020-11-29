import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  {
    path: 'home',
    loadChildren: () => import('./home/home.module').then( m => m.HomePageModule)
  },
  {
    path: '',
    redirectTo: 'home',
    pathMatch: 'full'
  },
  {
    path: 'login',
    loadChildren: () => import('./login/login.module').then( m => m.LoginPageModule)
  },
  {
    path: 'employees',
    loadChildren: () => import('./employees/employees.module').then( m => m.EmployeesPageModule)
  },
  {
    path: 'customers',
    loadChildren: () => import('./customers/customers.module').then( m => m.CustomersPageModule)
  },
  {
    path: 'calendar',
    loadChildren: () => import('./calendar/calendar.module').then( m => m.CalendarPageModule)
  },
  {
    path: 'messages',
    loadChildren: () => import('./messages/messages.module').then( m => m.MessagesPageModule)
  },
  {
    path: 'view-message',
    loadChildren: () => import('./view-message/view-message.module').then( m => m.ViewMessagePageModule)
  },
  {
    path: 'edit-employee',
    loadChildren: () => import('./modal/edit-employee/edit-employee.module').then( m => m.EditEmployeePageModule)
  },
  {
    path: 'edit-customer',
    loadChildren: () => import('./modal/edit-customer/edit-customer.module').then( m => m.EditCustomerPageModule)
  },
  {
    path: 'view-employee',
    loadChildren: () => import('./modal/view-employee/view-employee.module').then( m => m.ViewEmployeePageModule)
  },
  {
    path: 'view-customer',
    loadChildren: () => import('./modal/view-customer/view-customer.module').then( m => m.ViewCustomerPageModule)
  },
  {
    path: 'edit-job',
    loadChildren: () => import('./modal/edit-job/edit-job.module').then( m => m.EditJobPageModule)
  },
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
