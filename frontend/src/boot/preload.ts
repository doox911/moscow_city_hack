import ApiRequest from 'src/api/ApiRequest';
import AuthService from 'src/services/auth.service';
import { userStore } from '../stores/userStore';

export default async ({ app, router, store }: any) => {
  ApiRequest.beforeRequest = (request) => {
    request.config.headers = {
      'content-type': 'application/json',
      'Authorization': `Bearer ${AuthService.getToken()}`,
    };
  };

  await AuthService.init();

  const { loadAllUser } = userStore();

  await loadAllUser();
}