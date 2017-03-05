FROM go1com/php:7-nginx
COPY . /app
RUN rm -rf /app/.git/ && chmod -Rf +w /app/cache/
WORKDIR /app
