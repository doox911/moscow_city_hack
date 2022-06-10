import { RouteRecordRaw } from 'vue-router';
import { Roles } from '../constants';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('layouts/WelcomeLayout.vue'),
    meta: { guest: true },
    children: [
      {
        path: '',
        component: () => import('pages/IndexPage.vue'),
      },
      {
        path: 'login', redirect: '/'
      }
    ]
  },
  {
    path: "/" + Roles.Admin,
    component: () => import('pages/AdminPage.vue'),
    meta: { role: Roles.Admin },
    children: [
      {
        path: 'registration',
        component: () => import('pages/RegistrationPage.vue'),
      },
    ]
  },
  {
    path: "/" + Roles.Government,
    component: () => import('pages/GovernmentPage.vue'),
    meta: { role: Roles.Government },
  },
  {
    path: "/" + Roles.Owner,
    component: () => import('pages/OwnerPage.vue'),
    meta: { role: Roles.Owner },
  },
  /* {
    path: "/" + Roles.Guest,
    redirect: "/", */
    /* component: () => import('pages/GuestPage.vue'),
    meta: { role: Roles.Guest }, */
  /* }, */

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
];

export default routes;
