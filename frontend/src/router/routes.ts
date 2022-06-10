import { RouteRecordRaw } from 'vue-router';
import { Roles } from '../constants';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'index',
    component: () => import('layouts/WelcomeLayout.vue'),
    meta: { guest: true },
    children: [
      {
        path: '/',
        name: 'home',
        component: () => import('pages/IndexPage.vue'),
      },
      {
        path: '/' + Roles.Guest,
        name: Roles.Guest,
        component: () => import('pages/IndexPage.vue'),
      },
      {
        path: '/' + Roles.Admin,
        name: Roles.Admin,
        component: () => import('pages/AdminPage.vue'),
        meta: { role: Roles.Admin },
      },
      {
        path: '/registration',
        component: () => import('pages/RegistrationPage.vue'),
      },
      {
        path: '/' + Roles.Government,
        name: Roles.Government,
        component: () => import('pages/GovernmentPage.vue'),
        meta: { role: Roles.Government },
      },
      {
        path: '/' + Roles.Owner,
        name: Roles.Owner,
        component: () => import('pages/OwnerPage.vue'),
        meta: { role: Roles.Owner },
      },
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
