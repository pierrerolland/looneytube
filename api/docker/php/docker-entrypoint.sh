#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
	# Install the project the first time PHP is started
	# After the installation, the following block can be deleted
	if [ ! -f composer.json ]; then
		CREATION=1

		rm -Rf tmp/
		composer create-project "symfony/skeleton $SYMFONY_VERSION" tmp --stability="$STABILITY" --prefer-dist --no-progress --no-interaction --no-install

		cd tmp
		composer require "php:>=$PHP_VERSION"
		composer config --json extra.symfony.docker 'true'
		cp -Rp . ..
		cd -

		rm -Rf tmp/
	fi

	if [ "$APP_ENV" != 'prod' ]; then
		composer install --prefer-dist --no-progress --no-interaction
		composer dump-autoload --classmap-authoritative
	fi

	if [ ! -f config/jwt/private.pem ]; then
    mkdir -p config/jwt
    openssl genrsa -out config/jwt/private.pem -aes256 -passout pass:"$JWT_PASSPHRASE" 4096
    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem -passin pass:"$JWT_PASSPHRASE"
  fi

  	if [ ! -f /srv/app/public/videos ]; then
      mkdir -p /srv/app/public/videos
    fi

	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var
fi

exec docker-php-entrypoint "$@"
