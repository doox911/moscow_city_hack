import ApiRequest from 'src/api/ApiRequest';
import AuthService from 'src/services/auth.service';
import YandexMap from 'src/yandex-map/map';
import { okvedStore } from 'Stores/okvedStore';

export default async ({ app, router, store }: any) => {

  YandexMap.init();

  const { loadOKVED } = okvedStore();

  ApiRequest.beforeRequest = (request) => {
    request.config.headers = {
      'content-type': 'application/json',
      'Authorization': `Bearer ${AuthService.getToken()}`,
    };
  };

  await AuthService.init();

  await loadOKVED();
}