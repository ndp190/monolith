FROM go1com/php:7-nginx

RUN apk add --update php7-xdebug \
    && echo "zend_extension=/usr/lib/php7/modules/xdebug.so" > /etc/php7/php.ini \
    && echo "xdebug.remote_enable=1" >> /etc/php7/php.ini \
    && echo "xdebug.remote_connect_back=0" >> /etc/php7/php.ini \
    && echo "xdebug.remote_cookie_expire_time=86400" >> /etc/php7/php.ini \
    && echo "xdebug.remote_port=9000" >> /etc/php7/php.ini \
    && echo "xdebug.remote_host=xdebug" >> /etc/php7/php.ini \
    && echo "xdebug.remote_autostart=1" >> /etc/php7/php.ini \
    && echo "xdebug.profiler_enable=1" >> /etc/php7/php.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /etc/php7/php.ini

RUN echo -e "\n\n[program:quiz-worker]" >> /etc/supervisord.conf \
    && echo "command=/app/quiz/bin/worker2 -e=monolith" >> /etc/supervisord.conf \
    && echo "autostart=true" >> /etc/supervisord.conf \
    && echo "autorestart=true" >> /etc/supervisord.conf \
    && echo "priority=15" >> /etc/supervisord.conf \
    && echo "stdout_logfile=/dev/stdout" >> /etc/supervisord.conf \
    && echo "stdout_logfile_maxbytes=0" >> /etc/supervisord.conf \
    && echo "stderr_logfile=/dev/stderr" >> /etc/supervisord.conf \
    && echo "stderr_logfile_maxbytes=0" >> /etc/supervisord.conf
