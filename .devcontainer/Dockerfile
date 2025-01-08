FROM mapcentia/gc2core8:mapserver

RUN export DEBIAN_FRONTEND=noninteractive
ENV DEBIAN_FRONTEND noninteractive

# Increase the number of rows scanned when getting keys in redis
ADD fix/redisDriver.php /var/www/geocloud2/app/vendor/phpfastcache/phpfastcache/lib/Phpfastcache/Drivers/Redis/Driver.php
ADD fix/redisclusterDriver.php /var/www/geocloud2/app/vendor/phpfastcache/phpfastcache/lib/Phpfastcache/Drivers/Rediscluster/Driver.php

# opcache + xdebug
RUN apt-get update
RUN apt-get -y install php8.3-xdebug redis-tools
COPY xdebug.ini  /etc/php/8.3/mods-available/
COPY opcache.ini  /etc/php/8.3/mods-available/
RUN phpenmod xdebug

# Add Supervisor config and run the deamon
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
