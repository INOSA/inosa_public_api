#!/usr/bin/env sh

set -eux

docker-compose -f docker-compose.test.yml exec -T testapi sh ./scripts/lint.sh