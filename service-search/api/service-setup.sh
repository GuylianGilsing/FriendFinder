#!/bin/sh

# Generate proxies
php ./bin/doctrine orm:generate-proxies

# Migrate database
php ./bin/doctrine migrations:diff --allow-empty-diff && php ./bin/doctrine migrations:migrate --allow-no-migration --no-interaction
