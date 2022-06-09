import { apiLogin, apiLogoutAccess, apiSignupUser } from "../api/users";
import { TextError, TextMessage } from "../types/message";
import { userStore } from "../stores/userStore";
import { Token } from "../types/token";

/**
 * @TODO Добавить сохранение user в userStore
 * @TODO Добавить в userStore token
 */
export class AuthService
{
    accessToken: Token | null = null;
    refreshToken: Token | null = null;

    /**
     * Из localStorage подгружаются токены
     */
    init()
    {
        this.refreshToken = new Token("refresh_token", 0);
        this.accessToken = new Token("access_token", 15);
    }
    /**
     * Получить текущий access токен
     */
    getToken()
    {
        return this.accessToken?.token;
    }
    /**
     * Получить текущий refresh токен
     */
    getRefreshToken()
    {
        return this.refreshToken?.token;
    }
    /**
     * Получить статус, авторизован ли пользователь
     */
    get isAuthenticated(): boolean 
    {
        return this.accessToken?.token ? true : false;
    }
    /**
     * Получить статус, является ли пользователь администратором
     */
    get isAdmin(): boolean
    {
        return this.accessToken?.token && this.accessToken?.data.role === "admin" || false;
    }
    /**
     * Осуществляется запрос входа в систему
     */
    async login({ name, password }: UserData): Promise<any>
    {
        if(name == "") throw new TextError(TextMessage.LoginError);
        if(password == "") throw new TextError(TextMessage.PasswordError);
        let out = await apiLogin({
            name,
            password
        });
        
        this.accessToken?.save(out.access_token);
        this.refreshToken?.save(out.refresh_token);
    }
    /**
     * Осуществляется запрос регистрации пользователя
     */
    async registration({ name, email, password, confirmPassword }: UserData): Promise<any>
    {
        if(name == "") throw new TextError(TextMessage.LoginError);
        if(email == "") throw new TextError(TextMessage.EmailError);
        if(password == "") throw new TextError(TextMessage.PasswordError);
        if(password !== confirmPassword) throw new TextError(TextMessage.ConfirmError);
        return await apiSignupUser({
            name,
            email,
            password,
        });
    }
    /**
     * Осуществляется запрос на деактивацию токена
     */
    async logout():Promise<any>
    {
        await apiLogoutAccess(); 
        this.clearToken();
    }
    /**
     * Удаление данных из localStorage
     */
    clearToken()
    {
        this.accessToken?.clear();
        this.refreshToken?.clear();
    }
}

export default new AuthService();

interface UserData
{
    name: string
    email?: string
    password: string
    confirmPassword?: string
}