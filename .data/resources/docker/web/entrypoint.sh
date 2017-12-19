#!/bin/sh

find /app -type d -name cache -exec chown -Rf www-data:www-data {} \;
chmod +x /app/quiz/bin/console
/wait-for-it.sh queue:5672 -t 0 -- /usr/bin/supervisord -n -c /etc/supervisord.conf
