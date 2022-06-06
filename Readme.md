# Перед работой

## На рабочей машине должны быть установлены:

- Node.js
- Npm
- Yarn (`npm install --global yarn`)
- Docker

## После клонирования

### Backend

```bash
# Linux and MacOS
cd backend 

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs

# Windows
wsl 

# Дальше как для Linux
```

### Frontend

```bash
# Linux and MacOS
cd frontend && yarn

# Windows
cd frontend
yarn

# or
wsl

cd frontend && yarn
```

# Инструкция для разработки

## Backend

```bash
# Linux and MacOS
cd backend && ./vendor/bin/sail up

# Windows
wsl

cd backend && ./vendor/bin/sail up
```

## Frontend

Запуск dev-сервера:

```bash
# Linux and MacOS
cd frontend && yarn quasar dev

# Windows
wsl

cd frontend && yarn quasar dev
```
> На *Windows* есть конфликты с локальным хостом. Quasar предлагает альтернативные хосты.

# Инструкция для демонстрации

## Backend

## Frontend

### Собираем:

```bash
docker build -t moscow_hack ./frontend --no-cache
```

### Запускаем:

```bash
docker run -p 5000:80  moscow_hack
```