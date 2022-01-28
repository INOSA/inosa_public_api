#!/usr/bin/env sh

set -eux

composer clear-cache

echo "${ENV}"

if [ "${ENV}" == "dev" ] || [ ${ENV} == "test" ]
then
  echo "Installing dev/test dependencies"

  composer install --no-cache
  composer dump-autoload

  bin/console doctrine:migrations:migrate -n
  bin/console doctrine:fixtures:load -n
else
  composer dump-autoload --optimize
  bin/console doctrine:migrations:migrate -n
fi
