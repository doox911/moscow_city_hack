import { apiGetUserInfo, apiLogin, apiLogoutAccess, apiSignupUser } from '../api/users';
import { userStore } from '../stores/userStore';
import { Token } from '../classes/token';
import { ResponseTokens } from '../types';

export class AuthService {
  accessToken: Token = new Token('access_token', 0);
  /**
   * Запрос пользователя, если есть токен
   */
  async init() {
    if (this.isAuthenticated)
      await this.updateUserInfo();
  }
  /**
   * Получить текущий access токен
   */
  getToken() {
    return this.accessToken?.token;
  }
  /**
   * Получить статус, авторизован ли пользователь
   */
  get isAuthenticated(): boolean {
    return this.accessToken?.token ? true : false;
  }
  /**
   * Осуществляется запрос входа в систему
   */
  async login({ email, password }: UserDataForLogin): Promise<any> {
    const { access_token } = await apiLogin({
      email,
      password,
    });

    this.accessToken.save(access_token);

    await this.updateUserInfo();
  }
  async updateUserInfo() {
    const data = await apiGetUserInfo();

    const { setUser } = userStore();

    data && setUser(data);
  }
  /**
   * Осуществляется запрос на деактивацию токена
   */
  async logout(): Promise<any> {
    await apiLogoutAccess();

    this.clearToken();

    const { removeUser } = userStore();

    removeUser();
  }
  /**
   * Удаление данных из localStorage
   */
  clearToken() {
    this.accessToken.clear();
  }
}

export default new AuthService();

interface UserDataForSave {
  second_name: string;
  name: string;
  patronymic: string;
  email: string;
  role: string;
  owner?: string;
  password: string;
}

interface UserDataForLogin {
  email: string;
  password: string;
}
