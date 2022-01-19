#!/usr/bin/env sh

set -eux

composer clear-cache
bin/console doctrine:migrations:migrate -n --allow-no-migration --all-or-nothing --no-debug

if [ "${ENV}" == "dev" ] || [ ${ENV} == "test" ]
then
  composer install
  bin/console doctrine:fixtures:load -n
else
  composer install --no-dev
fi
