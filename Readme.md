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

# Windows
wsl

cd backend
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