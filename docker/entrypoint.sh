#!/usr/bin/env bash
 
composer install --ignore-platform-reqs -n
composer require symfony/polyfill-intl-messageformatter
bin/console make:migration
bin/console doc:mig:mig --no-interaction
 
exec "$@"