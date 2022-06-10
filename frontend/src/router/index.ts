import { route } from 'quasar/wrappers';
import {
  createMemoryHistory,
  createRouter,
  createWebHashHistory,
  createWebHistory,
} from 'vue-router';
import AuthService from '../services/auth.service';
import { userStore } from '../stores/userStore';
import routes from './routes';
import { storeToRefs } from 'pinia'

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Router instance.
 */

export default route(function (/* { store, ssrContext } */) {
  const createHistory = process.env.SERVER
    ? createMemoryHistory
    : process.env.VUE_ROUTER_MODE === 'history'
    ? createWebHistory
    : createWebHashHistory;

  const Router = createRouter({
    scrollBehavior: () => ({ left: 0, top: 0 }),
    routes,

    // Leave this as is and make changes in quasar.conf.js instead!
    // quasar.conf.js -> build -> vueRouterMode
    // quasar.conf.js -> build -> publicPath
    history: createHistory(
      process.env.MODE === 'ssr' ? void 0 : process.env.VUE_ROUTER_BASE,
    ),
  });

  Router.beforeEach((to, from, next) => {
    const { user } = storeToRefs(userStore());
    console.log(AuthService.isAuthenticated, user, to)
    // Если пользователь уже зашел, скидывать на /main
    if (to.matched.some((record) => record.meta.guest)) {
      if (AuthService.isAuthenticated) return next({ path: user.value.role });
      next();
    }
    // Если для страницы нужна авторизация
    else if (to.matched.some((record) => record.meta.role)) {
      if (AuthService.isAuthenticated) {
        return next({ path: user.value.role });
      }
      next({ path: '/login' });
    } else next();
  });

  return Router;
});
