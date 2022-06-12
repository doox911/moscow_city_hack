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
        name: Roles.Admin + 'Profile',
        component: () => import('pages/AdminProfilePage.vue'),
      },
      {
        path: '/' + Roles.Admin + '/search',
        name: Roles.Admin + 'Search',
        component: () => import('pages/SearchPage.vue'),
      },
      {
        path: '/' + Roles.Government,
        name: Roles.Government,
        component: () => import('pages/GovernmentPage.vue'),
      },
      {
        path: '/' + Roles.Government + '/profile',
        name: Roles.Government + 'Profile',
        component: () => import('pages/GovernmentProfilePage.vue'),
      },
      {
        path: '/' + Roles.Government + '/search',
        name: Roles.Government + 'Search',
        component: () => import('pages/SearchPage.vue'),
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
      {
        path: '/' + Roles.Owner + '/search',
        name: Roles.Owner + 'Search',
        component: () => import('pages/SearchPage.vue'),
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
