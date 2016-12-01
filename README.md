GO1 monolith
====

## Dependencies

- git
- php7
    - composer
- golang:
    - `composer global require symfony/yaml`
    - glide: `curl https://glide.sh/get | sh`
    - `export PATH="/path/to/monolith/bin:$PATH"`

## Usage

- `php ./scripts/build.php`: Build the code base.
    - `--skip-php`: Don't run composer commands. 
    - `--skip-web`: Don't run npm commands.
    - `--skip-drupal`: Don't build drupal code base.
    - `--skip-go`: Don't build golang code base.
    - `--pull`: Pull latest code. 
- `php start.php`
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
