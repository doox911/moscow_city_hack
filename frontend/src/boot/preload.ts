import ApiRequest from 'src/api/ApiRequest';
import AuthService from 'src/services/auth.service';
import { apiMenuList } from 'src/api/menu'

/**
 * Store
 */
import { menuStore } from 'Stores/menuStore';

export default async ({ app, router, store }: any) => {
  ApiRequest.beforeRequest = (request) => {
    request.config.headers = {
      'content-type': 'application/json',
      'Authorization': `Bearer ${AuthService.getToken()}`,
    };
  };

  await AuthService.init();

  const { setMenu } = menuStore();

  setMenu(await apiMenuList());
}