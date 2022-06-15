#!/usr/bin/env sh

echo "Running in ${APP_ENV} env."

set -eux
API_PREFIX="${PUBLIC_API_PREFIX_URL:-public-api}"

composer clear-cache

if [ "${APP_ENV}" == "dev" ] || [ ${APP_ENV} == "test" ]
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

echo "Building public api documentation for $API_PREFIX"
mkdir -p /var/www/html/public/$API_PREFIX

bin/console assets:install public/$API_PREFIX
bin/console cache:warmup

chmod 777 -R var/cache/

echo "List available public api routes"
bin/console debug:router
