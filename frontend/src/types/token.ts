import { apiTokenRefresh } from "../api/users";

export class Token
{
    token:string = "";
    data:any;
    name:string = "";
    time:number = 0;
    private lifeTime:number = 0;
    private timer:any;

    constructor(name:string, lifeTime:number) 
    {
        this.name = name;
        this.lifeTime = lifeTime;
        let localData = localStorage.getItem(name);
        if(localData) {
            this.load(JSON.parse(localData));
            this.startUpdate();
        }
    }
    /**
     * Сохранение токена в localStorage, и получение метаданных
     */
    save(token:string)
    {
        this.time = new Date().getTime() + this.lifeTime * 60 * 1000;
        this.token = token;
        this.data = this.parseJwt(this.token);

        localStorage.setItem(
            this.name, 
            JSON.stringify({
                token: this.token,
                time: this.time
            })
        );
        this.startUpdate();
    }
    /**
     * Загрузка токена из localStorage, и получение метаданных
     */
    load(localData:any)
    {
        this.time = localData.time;
        this.token = localData.token;
        this.data = this.parseJwt(this.token);
    }
    /**
     * Удаление данных из localStorage
     */
    clear()
    {
        this.token = "";
        this.data = null;
        localStorage.removeItem(this.name);
    }
    /**
     * Раз в 10 секунд проверяется актуальность токена
     */
    private update()
    {
        if(this.time > new Date().getTime() + 10000)
            this.timer = setTimeout(() => {
                this.update();
            }, 10000);
        else {
            // надо получить новый токен
            apiTokenRefresh()
                .then((data:any) => {
                    this.save(data.access_token);
                })
        }
    }
    private startUpdate()
    {
        this.stopUpdate();
        if(!this.lifeTime) return;
        this.update();
    }
    private stopUpdate()
    {
        clearTimeout(this.timer);
    }
    /**
     * Парсинг метаданных из токена
     */
    private parseJwt(token:string) 
    {
        var base64Url = token.split('.')[1];
        var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
        return JSON.parse(jsonPayload);
    };
}