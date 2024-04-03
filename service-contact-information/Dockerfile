FROM php:8.2-cli-alpine3.18 as build

# Setup PHP extensions
RUN --mount=type=bind,from=mlocati/php-extension-installer:1.5,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
     install-php-extensions pdo_mysql opcache zip xsl dom exif intl pcntl bcmath sockets && \
     apk del --no-cache ${PHPIZE_DEPS} ${BUILD_DEPENDS}

RUN mkdir /service-contact-information
WORKDIR /service-contact-information

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

COPY ./lib-api-framework ../lib-api-framework
COPY ./lib-common ../lib-common

COPY ./service-contact-information/composer.json .
COPY ./service-contact-information/composer.lock .

RUN composer install
RUN composer dump-autoload -o

COPY ./service-contact-information/bin ./bin
COPY ./service-contact-information/src ./src
COPY ./service-contact-information/tests ./tests
COPY ./service-contact-information/env.php .
COPY ./service-contact-information/migrations.php .
COPY ./service-contact-information/phpinsights.php .
COPY ./service-contact-information/phpunit.xml .
COPY ./service-contact-information/service-setup.sh .

RUN composer run lint
RUN composer run tests

FROM php:8.2-cli-alpine3.18 as serve

# Setup PHP extensions
RUN --mount=type=bind,from=mlocati/php-extension-installer:1.5,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
     install-php-extensions opcache zip xsl dom exif intl pcntl bcmath sockets pdo_mysql && \
     apk del --no-cache ${PHPIZE_DEPS} ${BUILD_DEPENDS}

WORKDIR /app

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

COPY ./lib-api-framework ../lib-api-framework
COPY ./lib-common ../lib-common

COPY --from=build /service-contact-information/vendor ./vendor
COPY --from=build /service-contact-information/composer.json .
COPY --from=build /service-contact-information/composer.lock .

# Install roadrunner
COPY --from=ghcr.io/roadrunner-server/roadrunner:2023.3.8 /usr/bin/rr /app

EXPOSE 8080/tcp

COPY --from=build /service-contact-information/bin ./bin
COPY --from=build /service-contact-information/src ./src

COPY --from=build /service-contact-information/env.php .
COPY --from=build /service-contact-information/migrations.php .
COPY --from=build /service-contact-information/service-setup.sh .

COPY /service-contact-information/roadrunner ./roadrunner
COPY /service-contact-information/.rr.yaml .

RUN mkdir /app/logs

# Run RoadRunner server
CMD ./rr serve -c .rr.yaml
