FROM mapcentia/gc2core8:mapserver

RUN export DEBIAN_FRONTEND=noninteractive
ENV DEBIAN_FRONTEND noninteractive

# Install npm packages run Grunt
#RUN	cd /var/www/geocloud2 &&\
#	npm install &&\
#	grunt production --force

# Install dashboard
RUN	mkdir -p /var/www/geocloud2/public/dashboard && mkdir /dashboardtmp && cd /dashboardtmp &&\
    git clone https://github.com/mapcentia/dashboard.git && cd /dashboardtmp/dashboard &&\
    npm install && cp ./app/config.js.sample ./app/config.js && cp ./.env.production ./.env &&\
    npm run build && cp -R ./build/* /var/www/geocloud2/public/dashboard/ &&\
    rm -R /dashboardtmp

# opcache + xdebug
RUN apt-get update
RUN apt-get -y install php8.3-xdebug redis-tools
COPY xdebug.ini  /etc/php/8.3/mods-available/
COPY opcache.ini  /etc/php/8.3/mods-available/
RUN phpenmod xdebug

# Add Supervisor config and run the deamon
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
