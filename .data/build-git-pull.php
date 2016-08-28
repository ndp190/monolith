<?php

namespace at\labs;

return function ($pwd, $pull, $projects) {
  foreach ($projects as $lang => $services) {
    foreach ($services as $name => $path) {
      if (!is_dir("$pwd/$lang/$name")) {
        print_r("git clone -q --branch=master $path $pwd/$lang/$name\n");
        passthru("git clone -q --branch=master $path $pwd/$lang/$name");
      }
      elseif ($pull) {
        print_r("git pull -q origin master\n");
        passthru("cd $pwd/$lang/$name && git pull -q origin master && cd $pwd");
      }

      if ('php' === $lang) {
        passthru("mkdir -p $pwd/$lang/$name/vendor");
        file_put_contents(
          "$pwd/$lang/$name/vendor/autoload.php",
          '<?php return require_once "/autoload/autoload.php";'
        );
      }
    }
  }
};
