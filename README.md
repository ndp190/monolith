GO1 monolith
====

- `php build.php`: Build the code base.
    - `--skip-php`: Don't run composer commands. 
    - `--skip-web`: Don't run npm commands.
    - `--skip-drupal`: Don't build drupal code base.
    - `--pull`: Pull latest code. 
- `php start.php`
- Run test cases:
    - `./test.php php/api`
    - `./test.php php/lo/tests/domain/tag/`
    - `./test.php php/api/tests/ProxyTest.php --filter=testStatusOfBlockedService`
    - `./test.php drupal/gc/modules/applications/aduro/modules/lms/lms_services/tests/Apiom/Course/CourseAccountsServicesTest.php`
- Run test cases without Docker:
    - `./phpunit php/outcome/`

To avoid PHPStorm to index too much, exclude these directory:

- .data
- drupal/gc/test
- php/adminer
- web/ui (if you're not #ui dev)
