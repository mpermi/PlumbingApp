import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';
import { AuthGuard } from './guards/auth.guard';

const routes: Routes = [
  {
    path: 'home',
    canActivate: [AuthGuard],
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
    canActivate: [AuthGuard],
    loadChildren: () => import('./employees/employees.module').then( m => m.EmployeesPageModule)
  },
  {
    path: 'customers',
    canActivate: [AuthGuard],
    loadChildren: () => import('./customers/customers.module').then( m => m.CustomersPageModule)
  },
  {
    path: 'calendar',
    canActivate: [AuthGuard],
    loadChildren: () => import('./calendar/calendar.module').then( m => m.CalendarPageModule)
  },
  {
    path: 'messages',
    canActivate: [AuthGuard],
    loadChildren: () => import('./messages/messages.module').then( m => m.MessagesPageModule)
  },
  {
    path: 'view-message',
    canActivate: [AuthGuard],
    loadChildren: () => import('./view-message/view-message.module').then( m => m.ViewMessagePageModule)
  },
  {
    path: 'edit-employee',
    canActivate: [AuthGuard],
    loadChildren: () => import('./modal/edit-employee/edit-employee.module').then( m => m.EditEmployeePageModule)
  },
  {
    path: 'edit-customer',
    canActivate: [AuthGuard],
    loadChildren: () => import('./modal/edit-customer/edit-customer.module').then( m => m.EditCustomerPageModule)
  },
  {
    path: 'view-employee',
    canActivate: [AuthGuard],
    loadChildren: () => import('./modal/view-employee/view-employee.module').then( m => m.ViewEmployeePageModule)
  },
  {
    path: 'view-customer',
    canActivate: [AuthGuard],
    loadChildren: () => import('./modal/view-customer/view-customer.module').then( m => m.ViewCustomerPageModule)
  },
  {
    path: 'edit-job',
    canActivate: [AuthGuard],
    loadChildren: () => import('./modal/edit-job/edit-job.module').then( m => m.EditJobPageModule)
  },
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules, onSameUrlNavigation: 'reload' })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
