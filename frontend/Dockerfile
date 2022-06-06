FROM node:16-alpine3.14 as frontend

WORKDIR /frontend

COPY . .

RUN yarn global add @quasar/cli

ARG api=http://localhost

ENV APP_BACKEND=$api

# build
FROM frontend as build-frontend

RUN yarn

RUN ls

RUN quasar build

# Nginx Server
FROM nginx:stable-alpine as production

COPY --from=build-frontend /frontend/dist/spa /usr/share/nginx/html/

EXPOSE 80

WORKDIR /usr/share/nginx/html/

CMD ["nginx", "-g", "daemon off;"]
