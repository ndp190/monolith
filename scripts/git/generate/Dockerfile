FROM busybox
COPY . /app
RUN rm -rf /app/.git/ && \
    chmod -Rf +w /app/cache/
WORKDIR /app
VOLUME ["/app"]
