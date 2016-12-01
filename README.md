GO1 monolith
====

## TODO

- Build the scripts as a single phar file.
- Check #ui build again.

## Dependencies

- git
- php7
    - composer
- golang:
    - `composer global require symfony/yaml`
    - glide: `curl https://glide.sh/get | sh`
    - `export PATH="/path/to/monolith/scripts:$PATH"`

## Usage

- `php ./scripts/build.php`: Build the code base.
    - `--skip-php`: Don't run composer commands. 
    - `--skip-web`: Don't run npm commands.
    - `--skip-drupal`: Don't build drupal code base.
    - `--skip-go`: Don't build golang code base.
    - `--pull`: Pull latest code. 
- `php start.php`, then try some links:
    - http://localhost/v3/
    - http://localhost/GO1/user/
- Run test cases:
    - `./scripts/test.php php/api`
    - `./scripts/test.php php/lo/tests/domain/tag/`
    - `./scripts/test.php php/api/tests/ProxyTest.php --filter=testStatusOfBlockedService`
    - `./scripts/test.php drupal/gc/modules/applications/aduro/modules/lms/lms_services/tests/Apiom/Course/CourseAccountsServicesTest.php`
- Run test cases without Docker:
    - `./scripts/phpunit php/outcome/`

To avoid PHPStorm to index too much, exclude these directory:

- .data
- drupal/gc/test
- php/adminer
- web/ui (if you're not #ui dev)
