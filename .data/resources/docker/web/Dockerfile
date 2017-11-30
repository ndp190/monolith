FROM registry.code.go1.com.au/apiom/apiom-ui:master as ui
FROM registry.code.go1.com.au/web/go1web:master as website

FROM go1com/php:7-nginx

RUN apk add --update supervisor php7-pear php7-dev autoconf build-base

ADD https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh /
RUN chmod a+x /wait-for-it.sh

COPY supervisord.conf /etc/supervisord.conf

COPY entrypoint.sh /entrypoint.sh
RUN chmod a+x /entrypoint.sh

COPY --from=ui /apiomui /apiomui
COPY --from=website /usr/local/apache2/htdocs /website

COPY fix-apiom-ui.php /fix-apiom-ui.php
COPY fix-website.php /fix-website.php

RUN php /fix-apiom-ui.php
RUN php /fix-website.php

RUN pecl install xdebug

RUN echo "zend_extension=/usr/lib/php7/modules/xdebug.so" > /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=1" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_connect_back=0" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_cookie_expire_time=86400" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9000" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_host=host" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=1" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.profiler_enable=0" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /etc/php7/conf.d/xdebug.ini

RUN apk del php7-pear php7-dev autoconf build-base

CMD ["/entrypoint.sh"]
