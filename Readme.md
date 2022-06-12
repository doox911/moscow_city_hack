# Перед работой

## На рабочей машине должны быть установлены:

- Docker

# Инструкция для демонстрации

## Сборка

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

# Выполняем миграции и сидеры

./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
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

## Доступы

```bash
# Логины
admin@rosgosplan.ru
government@rosgosplan.ru
owner@rosgosplan.ru
guest@rosgosplan.ru

# Пароль от всех пользователей
password
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