#!/usr/bin/env sh

set -eux

docker-compose -f docker-compose.test.yml -f docker-compose.local.test.override.yml down -v --rmi local