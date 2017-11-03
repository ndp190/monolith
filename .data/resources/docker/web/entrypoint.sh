#!/bin/sh

chown -Rf nginx:www-data /app/staff/cache/
/scripts/wait-for-it.sh queue:5672 -t 0 -- /usr/bin/supervisord -n -c /etc/supervisord.conf
