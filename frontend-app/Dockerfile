FROM node:20-bookworm-slim as setup

RUN mkdir /fronted-app
WORKDIR /frontend-app

# Import project files
COPY ./e2e ./e2e
COPY ./public ./public
COPY ./src ./src
COPY ./.env .
COPY ./.eslintrc.cjs .

# ESLint requires that the gitignore is present while linting the code
COPY ./.gitignore .

COPY ./.prettierrc.json .
COPY ./env.d.ts .
COPY ./index.html .
COPY ./package-lock.json .
COPY ./package.json .
COPY ./playwright.config.ts .
COPY ./postcss.config.js .
COPY ./tailwind.config.js .
COPY ./tsconfig.app.json .
COPY ./tsconfig.json .
COPY ./tsconfig.node.json .
COPY ./tsconfig.vitest.json .
COPY ./vite.config.ts .
COPY ./vitest.config.ts .

RUN npm install

FROM node:20-bookworm-slim as test

RUN mkdir /fronted-app
WORKDIR /frontend-app

COPY --from=setup /frontend-app .

RUN npm run lint
RUN npm run tests:unit:ci

FROM node:20-bookworm-slim as build

RUN mkdir /fronted-app
WORKDIR /frontend-app

COPY --from=test /frontend-app .

RUN npm run build

FROM nginx:1.25.2-bookworm as serve

## Clear default nginx files inside web root folder
RUN rm -rf /usr/share/nginx/html/*

COPY --from=build /frontend-app/dist /usr/share/nginx/html
COPY ./nginx/nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
ENTRYPOINT ["nginx", "-g", "daemon off;"]
