Manage composer.json for microservice
====

In monolith we use single code base for all services, instead of run `composer require|install|update|…` locally, we can use our tool to run on CI instead — https://j.mp/1compose:

- Edit the composer.json
- Go to CI / CD › Pipeline › Check for built composer.lock file.
