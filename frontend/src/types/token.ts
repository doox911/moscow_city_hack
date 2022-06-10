export class Token {
  token: string = '';
  time: number = 0;

  constructor(private name: string, private lifeTime: number = 0) {
    let localData = localStorage.getItem(name);
    if (localData) {
      this.load(JSON.parse(localData));
    }
  }
  /**
   * Сохранение токена в localStorage, и получение метаданных
   */
  save(token: string) {
    this.time = new Date().getTime() + this.lifeTime * 60 * 1000;
    this.token = token;

    localStorage.setItem(
      this.name,
      JSON.stringify({
        token: this.token,
        time: this.time,
      }),
    );
  }
  /**
   * Загрузка токена из localStorage, и получение метаданных
   */
  load(localData: any) {
    this.time = localData.time;
    this.token = localData.token;
  }
  /**
   * Удаление данных из localStorage
   */
  clear() {
    this.token = '';
    localStorage.removeItem(this.name);
  }
}
