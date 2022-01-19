#!/usr/bin/env sh
set -eux

composer install
bin/console doctrine:migrations:migrate -n --allow-no-migration --all-or-nothing --no-debug