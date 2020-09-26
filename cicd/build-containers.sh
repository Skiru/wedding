#!/usr/bin/env sh

set -eux

CONTAINER="wedding_purple_clouds_php"

docker-compose down -v --remove-orphans

docker-compose up -d

docker-compose exec ${CONTAINER} sh -c "composer install"