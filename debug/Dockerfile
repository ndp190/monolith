FROM go1com/php:7-nginx

RUN apk add --update php7-xdebug \
    && echo "zend_extension=/usr/lib/php7/modules/xdebug.so" > /etc/php7/php.ini \
    && echo "xdebug.remote_enable=1" >> /etc/php7/php.ini \
    && echo "xdebug.remote_connect_back=0" >> /etc/php7/php.ini \
    && echo "xdebug.remote_cookie_expire_time=86400" >> /etc/php7/php.ini \
    && echo "xdebug.remote_port=9000" >> /etc/php7/php.ini \
    && echo "xdebug.remote_host=172.17.0.1" >> /etc/php7/php.ini \
    && echo "xdebug.remote_autostart=1" >> /etc/php7/php.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /etc/php7/php.ini
