FROM node:7-alpine

WORKDIR /apiomui-live

RUN apk update && apk add ca-certificates && update-ca-certificates

RUN apk add ruby ruby-dev build-base libffi-dev

RUN npm install -g grunt-cli
RUN gem install --no-ri --no-rdoc compass sass
RUN apk del build-base libffi-dev ruby-dev
