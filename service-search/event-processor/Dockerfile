FROM php:8.2-cli-alpine3.18 as build

# Setup PHP extensions
RUN --mount=type=bind,from=mlocati/php-extension-installer:1.5,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
     install-php-extensions pdo_mysql opcache zip xsl dom exif intl pcntl bcmath sockets && \
     apk del --no-cache ${PHPIZE_DEPS} ${BUILD_DEPENDS}

RUN mkdir /service-search
RUN mkdir /service-search/event-processor
WORKDIR /service-search/event-processor

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

COPY ./lib-common ../../lib-common
COPY ./service-search/lib-common ../lib-common
COPY ./lib-api-framework ../../lib-api-framework

COPY ./service-search/event-processor/src ./src
COPY ./service-search/event-processor/composer.json .
COPY ./service-search/event-processor/composer.lock .
COPY ./service-search/event-processor/env.php .

RUN composer install

FROM php:8.2-cli-alpine3.18 as serve

# Setup PHP extensions
RUN --mount=type=bind,from=mlocati/php-extension-installer:1.5,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
     install-php-extensions opcache zip xsl dom exif intl pcntl bcmath sockets pdo_mysql && \
     apk del --no-cache ${PHPIZE_DEPS} ${BUILD_DEPENDS}

RUN mkdir /app
RUN mkdir /app/event-processor
WORKDIR /app/event-processor

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

COPY ./lib-common ../../lib-common
COPY ./service-search/lib-common ../lib-common
COPY ./lib-api-framework ../../lib-api-framework

COPY --from=build /service-search/event-processor/src ./src
COPY --from=build /service-search/event-processor/vendor ./vendor
COPY --from=build /service-search/event-processor/composer.json .
COPY --from=build /service-search/event-processor/composer.lock .
COPY --from=build /service-search/event-processor/env.php .

# Install roadrunner
COPY --from=ghcr.io/roadrunner-server/roadrunner:2023.3.8 /usr/bin/rr /app/event-processor

EXPOSE 8080/tcp

COPY ./service-search/event-processor/roadrunner ./roadrunner
COPY ./service-search/event-processor/.rr.yaml .

RUN mkdir /app/event-processor/logs
RUN composer dump-autoload -o

# Run RoadRunner server
CMD ./rr serve -c .rr.yaml
