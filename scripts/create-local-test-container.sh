#!/usr/bin/env sh

set -eux

OVERRIDE="docker-compose -f docker-compose.test.yml -f docker-compose.local.test.override.yml"

DOCKER_BUILDKIT=1 $OVERRIDE build --no-cache
$OVERRIDE up -d --build

while ! $OVERRIDE logs testdb | grep 'DATABASE IS READY'; do sleep 1; done

$OVERRIDE exec -T testapi composer install
$OVERRIDE exec -T testapi bin/console doctrine:migrations:migrate -n
$OVERRIDE exec -T testapi bin/console doctrine:fixtures:load -n