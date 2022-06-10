import { apiLogin, apiLogoutAccess, apiSignupUser } from '../api/users';
import { TextError, TextMessage } from '../types/message';
import { userStore } from '../stores/userStore';
import { Token } from '../types/token';

/**
 * @TODO Добавить сохранение user в userStore
 * @TODO Добавить в userStore token
 */
export class AuthService {
  accessToken: Token = new Token('access_token', 0);

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
    if (email == '') throw new TextError(TextMessage.LoginError);
    if (password == '') throw new TextError(TextMessage.PasswordError);
    const { access_token, refresh_token } = await apiLogin({
      email,
      password,
    });
    this.accessToken.save(access_token);
  }
  /**
   * Осуществляется запрос регистрации пользователя
   */
  async registration({
    name,
    email,
    password,
    confirmPassword,
  }: UserData): Promise<any> {
    if (name == '') throw new TextError(TextMessage.LoginError);
    if (email == '') throw new TextError(TextMessage.EmailError);
    if (password == '') throw new TextError(TextMessage.PasswordError);
    if (password !== confirmPassword)
      throw new TextError(TextMessage.ConfirmError);
    return await apiSignupUser({
      name,
      email,
      password,
    });
  }
  /**
   * Осуществляется запрос на деактивацию токена
   */
  async logout(): Promise<any> {
    await apiLogoutAccess();
    this.clearToken();
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
  confirmPassword?: string;
}
