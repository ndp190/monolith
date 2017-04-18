FROM go1com/php:7-nginx

RUN apk add --update supervisor php7-dev autoconf build-base

COPY supervisord.conf /etc/supervisord.conf

RUN wget http://pecl.php.net/get/xdebug-2.5.2.tgz \
    && tar -xzf xdebug-2.5.2.tgz \
    && cd xdebug-2.5.2/ \
    && phpize \
    && ./configure --enable-xdebug \
    && make \
    && cp modules/xdebug.so /usr/lib/ \
    && cd .. \
    && rm -rf xdebug-2.5.2

RUN echo "zend_extension=/usr/lib/xdebug.so" > /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=1" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_connect_back=0" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_cookie_expire_time=86400" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9000" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_host=xdebug" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=1" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.profiler_enable=1" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /etc/php7/conf.d/xdebug.ini

RUN apk del php7-dev autoconf build-base
