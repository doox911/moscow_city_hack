import { apiGetUserInfo, apiLogin, apiLogoutAccess, apiSignupUser } from '../api/users';
import { userStore } from '../stores/userStore';
import { Token } from '../classes/token';

/**
 * @TODO Добавить сохранение user в userStore
 * @TODO Добавить в userStore token
 */
export class AuthService {
  accessToken: Token = new Token('access_token', 0);
  /**
   * Запрос пользователя, если есть токен
   */
  init()
  {
    if(this.isAuthenticated)
      this.getUserInfo();
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
  async login({ email, password }: UserData): Promise<any> {
    const { access_token } = await apiLogin({
      email,
      password,
    });
    this.accessToken.save(access_token);

    await this.getUserInfo();
  }
  /**
   * Осуществляется запрос регистрации пользователя
   */
  async registration({
    name,
    email,
    password
  }: UserData): Promise<any> {
    return await apiSignupUser({
      name,
      email,
      password,
    });
  }
  async getUserInfo()
  {
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

interface UserData {
  name?: string;
  email: string;
  password: string;
}
