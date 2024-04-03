FROM php:8.2-cli-alpine3.18 as build

# Setup PHP extensions
RUN --mount=type=bind,from=mlocati/php-extension-installer:1.5,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
     install-php-extensions pdo_mysql opcache zip xsl dom exif intl pcntl bcmath sockets && \
     apk del --no-cache ${PHPIZE_DEPS} ${BUILD_DEPENDS}

RUN mkdir /service-search
RUN mkdir /service-search/api
WORKDIR /service-search/api

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

COPY ./lib-common ../../lib-common
COPY ./service-search/lib-common ../lib-common
COPY ./lib-api-framework ../../lib-api-framework

COPY ./service-search/api/composer.json .
COPY ./service-search/api/composer.lock .

RUN composer install
RUN composer dump-autoload -o

COPY ./service-search/api/bin ./bin
COPY ./service-search/api/src ./src
COPY ./service-search/api/tests ./tests
COPY ./service-search/api/env.php .
COPY ./service-search/api/migrations.php .
COPY ./service-search/api/phpinsights.php .
COPY ./service-search/api/phpunit.xml .
COPY ./service-search/api/service-setup.sh .

RUN composer run lint
RUN composer run tests

FROM php:8.2-cli-alpine3.18 as serve

# Setup PHP extensions
RUN --mount=type=bind,from=mlocati/php-extension-installer:1.5,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
     install-php-extensions opcache zip xsl dom exif intl pcntl bcmath sockets pdo_mysql && \
     apk del --no-cache ${PHPIZE_DEPS} ${BUILD_DEPENDS}

RUN mkdir /app
RUN mkdir /app/api
WORKDIR /app/api

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

COPY ./lib-common ../../lib-common
COPY ./service-search/lib-common ../lib-common
COPY ./lib-api-framework ../../lib-api-framework

COPY --from=build /service-search/api/vendor ./vendor
COPY --from=build /service-search/api/composer.json .
COPY --from=build /service-search/api/composer.lock .

# Install roadrunner
COPY --from=ghcr.io/roadrunner-server/roadrunner:2023.3.8 /usr/bin/rr /app/api

EXPOSE 8080/tcp

COPY --from=build /service-search/api/bin ./bin
COPY --from=build /service-search/api/src ./src

COPY --from=build /service-search/api/env.php .
COPY --from=build /service-search/api/migrations.php .
COPY --from=build /service-search/api/service-setup.sh .

COPY /service-search/api/roadrunner ./roadrunner
COPY /service-search/api/.rr.yaml .

RUN mkdir /app/api/logs

# Run RoadRunner server
CMD ./rr serve -c .rr.yaml
