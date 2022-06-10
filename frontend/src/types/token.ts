import { apiTokenRefresh } from "../api/users";

export class Token
{
    token: string = "";
    time: number = 0;
    private timer: any;

    constructor(private name: string, private lifeTime: number = 0) 
    {
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
        console.log("save", this.token)
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
    }
    /**
     * Удаление данных из localStorage
     */
    clear()
    {
        this.token = "";
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
}