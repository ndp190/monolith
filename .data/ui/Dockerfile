FROM node:7-alpine

WORKDIR /apiomui-live

RUN apk add --update ca-certificates && update-ca-certificates

RUN apk add ruby ruby-dev build-base libffi-dev autoconf automake nasm bash libpng-dev git python curl

RUN npm install --silent --depth 0 -g bower grunt-cli
#RUN npm install -g  phantomjs-prebuilt
RUN gem install --no-ri --no-rdoc compass sass

# phantomjs built for alpine.
# @todo Fix 'PhantomJS have not captured in 2000 ms, killing.'
RUN apk update && apk add --no-cache fontconfig \
    && mkdir -p /usr/share \
    && cd /usr/share \
    && curl -L https://github.com/Overbryd/docker-phantomjs-alpine/releases/download/2.11/phantomjs-alpine-x86_64.tar.bz2 | tar xj \
    && ln -s /usr/share/phantomjs/phantomjs /usr/bin/phantomjs \
    && phantomjs --version

ENV PHANTOMJS_BIN /usr/bin/phantomjs
RUN chmod u+s /bin/ping
RUN chown -R node.node /apiomui-live
USER node
ENV HOME /home/node
