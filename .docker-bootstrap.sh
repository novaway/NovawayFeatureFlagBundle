#!/bin/bash

apt-get update && apt-get install -y git zlib1g-dev

docker-php-ext-configure zip && docker-php-ext-install zip

echo "date.timezone = Europe/Paris" >> /usr/local/etc/php/php.ini
echo "short_open_tag = Off" >> /usr/local/etc/php/php.ini
