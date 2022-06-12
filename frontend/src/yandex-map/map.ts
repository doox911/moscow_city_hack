declare var ymaps: any;

class YandexMap {
  private mapElement: HTMLElement = document.createElement('div');
  private map: any;
  private markers: any[] = [];
  init() {
    ymaps.ready(() => this.onMapInit());
    document.body.appendChild(this.mapElement);
  }
  show(lat: number, lon: number, title: string = ''): HTMLElement {
    (this.mapElement.style as any).display = null;
    this.map.setCenter([lat, lon]);

    this.markers.forEach(marker => {
      this.map
        .geoObjects
        .remove(marker);
    });
    if (title) {
      const marker = new ymaps.Placemark([lat, lon], {
        iconCaption: title
      }, {
        preset: 'islands#greenDotIconWithCaption'
      })
      this.map
        .geoObjects
        .add(marker);
      this.markers.push(marker);
    }
    return this.mapElement;
  }
  hide() {
    this.mapElement.style.display = "none";
  }

  private onMapInit() {
    this.mapElement.style.width = "560px";
    this.mapElement.style.height = "460px";
    this.mapElement.style.position = "relative";
    this.mapElement.style.display = "none";
    this.map = new ymaps.Map(this.mapElement, {
      center: [55.76, 37.64],
      zoom: 16
    }, {
      searchControlProvider: 'yandex#search'
    });
  }
}

export default new YandexMap();