import { RouteRecordRaw } from 'vue-router';
import { Roles } from '../constants';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'index',
    component: () => import('layouts/MainLayout.vue'),
    meta: { guest: true },
    children: [
      {
        path: '/' + Roles.Admin,
        name: Roles.Admin,
        component: () => import('pages/AdminPage.vue'),
      },
      {
        path: '/' + Roles.Admin + '/profile',
        component: () => import('pages/AdminProfilePage.vue'),
        redirect: { name: Roles.Admin + 'Profile' },
        children: [
          {
            path: '',
            name: Roles.Admin + 'Profile',
            component: () => import('pages/profile/ProfilePage.vue'),
          },
          {
            path: 'request',
            component: () => import('pages/profile/RequestPage.vue'),
          },
          {
            path: 'edit',
            component: () => import('pages/profile/EditPage.vue'),
          },
          {
            path: 'owner',
            component: () => import('pages/profile/OwnerPage.vue'),
          },
          {
            path: 'registration',
            component: () => import('pages/profile/RegistrationPage.vue'),
          },
        ]
      },
      {
        path: '/' + Roles.Government,
        name: Roles.Government,
        component: () => import('pages/GovernmentPage.vue'),
      },
      {
        path: '/' + Roles.Government + '/profile',
        component: () => import('pages/GovernmentProfilePage.vue'),
        redirect: { name: Roles.Government + 'Profile' },
        children: [
          {
            path: '',
            name: Roles.Government + 'Profile',
            component: () => import('pages/profile/ProfilePage.vue'),
          }
        ]
      },
      {
        path: '/' + Roles.Owner,
        name: Roles.Owner,
        component: () => import('pages/OwnerPage.vue'),
      },
      {
        path: '/' + Roles.Owner + '/profile',
        name: Roles.Owner + 'Profile',
        component: () => import('pages/OwnerProfilePage.vue'),
      },
    ]
  },
  {
    path: '/login' ,
    name: 'login',
    component: () => import('layouts/WelcomeLayout.vue'),
    children: [
      {
        path: '',
        name: 'loginPage',
        component: () => import('pages/IndexPage.vue'),
      },
      {
        path: '/' + Roles.Guest,
        name: Roles.Guest,
        component: () => import('pages/IndexPage.vue'),
      }
    ]
  },
  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
];

export default routes;
