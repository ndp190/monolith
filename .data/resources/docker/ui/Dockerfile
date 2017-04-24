FROM node:7-alpine

WORKDIR /apiomui-live

RUN apk add --update ca-certificates && update-ca-certificates

RUN apk add ruby ruby-dev build-base libffi-dev

RUN npm install --silent --depth 0 -g bower grunt-cli
RUN gem install --no-ri --no-rdoc compass sass

RUN apk del build-base libffi-dev ruby-dev

RUN chmod u+s /bin/ping
RUN chown -R node.node /apiomui-live
USER node
ENV HOME /home/node
