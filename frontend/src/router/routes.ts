import { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('layouts/WelcomeLayout.vue'),
    children: [
      {
        path: '',
        component: () => import('pages/IndexPage.vue'),
        meta: { guest: true },
      },
      {
        path: '/main',
        component: () => import('pages/MainPage.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: '/login',
        component: () => import('pages/LoginPage.vue'),
        meta: { guest: true },
      },
      {
        path: '/registration',
        component: () => import('pages/RegistrationPage.vue'),
        meta: { guest: true },
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
];

export default routes;
