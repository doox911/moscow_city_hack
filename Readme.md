# Перед работой

## На рабочей машине должны быть установлены:

- Node.js
- Npm
- Yarn (`npm install --global yarn`)
- Docker

# Инструкция для демонстрации

## Backend

### Linux and MacOS:
```bash
cd backend 

cp .env-example .env

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs

./vendor/bin/sail up
```
### Windows:

Используем `wsl`

> Может попросить **сгенерировать ключ**, кнопка в правом верхнем углу.

## Frontend

Windows, Linux, MacOS:

```bash
docker build -t moscow_hack ./frontend --no-cache
```

### Запускаем:

```bash
docker run -p 5000:80  moscow_hack
```

# Инструкция для разработки

## Backend

- Подготовка

    Linux, MacOS:

    ```bash
    cd backend 

    cp .env-example .env

    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/var/www/html \
        -w /var/www/html \
        laravelsail/php81-composer:latest \
        composer install --ignore-platform-reqs

    # or 
    docker run --rm -u "$(id -u):$(id -g)" -v $(pwd):/var/www/html -w /var/www/html laravelsail/php81-composer:latest composer install --ignore-platform-reqs
    ```
    ### Windows:

    Используем `wsl`

    > Может попросить **сгенерировать ключ**, кнопка в правом верхнем углу.

- Работа

    ```bash
    ./vendor/bin/sail up

    # В фоне
    ./vendor/bin/sail up -d
    ```

## Frontend
- Подготовка

    Linux and MacOS:
    ```bash
    cd frontend && yarn

    cp .env-example .env
    ```
    Windows:

    ```bash
    wsl

    cd frontend && yarn

    cp .env-example .env
    ```

- Работа

    ```bash
    cd frontend && yarn quasar dev
    ```

> На *Windows* есть конфликты с локальным хостом. Quasar предлагает альтернативные хосты.