#!/bin/bash

chown www-data:www-data /var/www/geocloud2/app/tmp/ &&\
chown www-data:www-data /var/www/geocloud2/app/wms/mapfiles/ &&\
chown www-data:www-data /var/www/geocloud2/app/wms/mapcache/ &&\
chown www-data:www-data /var/www/geocloud2/app/wms/files/ &&\
chown www-data:www-data /var/www/geocloud2/app/wms/qgsfiles/ &&\
chown www-data:www-data /var/www/geocloud2/public/logs/ &&\
chmod 737 /var/lib/php/sessions
chmod +t /var/lib/php/sessions # Sticky bit
touch /var/www/geocloud2/app/wms/mapcache/mapcache.conf

# Set time zone if passed
if [ -n "$TIMEZONE" ]; then
    echo $TIMEZONE | tee /etc/timezone
    dpkg-reconfigure -f noninteractive tzdata
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf