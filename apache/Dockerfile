FROM php:8.1-apache

ENV PHP_MEMORY_LIMIT=256M
ENV TIRRENO_VERSION=v0.9.11

RUN a2enmod rewrite

RUN set -ex; \
	apt-get update; \
	apt-get install -y --no-install-recommends cron libpq-dev libzip-dev pkg-config; \
    docker-php-ext-configure zip --with-zip; \
    docker-php-ext-install pdo pdo_pgsql pgsql zip; \
	apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
	rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/etc/php/conf.d/php-tirreno.ini
COPY tirreno-cron /etc/cron.d/tirreno-cron

# https://secure.php.net/manual/en/opcache.installation.php
RUN { \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=2'; \
		echo 'opcache.fast_shutdown=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini

RUN curl -fsSL -o tirreno.tar.gz "https://github.com/tirrenotechnologies/tirreno/archive/refs/tags/${TIRRENO_VERSION}.tar.gz" && \
    tar -xzf tirreno.tar.gz --strip-components=1 -C /var/www/html/ && rm tirreno.tar.gz

RUN touch /var/www/html/assets/logs/error.log && touch /var/www/html/assets/logs/blacklist.log

RUN chown -R www-data:www-data /var/www/html

RUN chmod 0644 /etc/cron.d/tirreno-cron \
    && crontab /etc/cron.d/tirreno-cron \
    && touch /var/log/cron.log

CMD ["sh", "-c", "cron && apache2-foreground"]
