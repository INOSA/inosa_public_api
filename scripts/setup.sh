#!/usr/bin/env sh

echo "Running in ${APP_ENV} env."

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

whoami

bin/console cache:warmup

chmod 777 -R var/cache/