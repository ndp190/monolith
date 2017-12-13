<?php

namespace go1\monolith\scripts;

$pwd = dirname(__DIR__);
$custom = $pwd . '/build.json';
$custom = is_file($custom) ? json_decode(file_get_contents($custom), true) : [];
$domain = isset($custom['features']['domain']) ? $custom['features']['domain'] : null;

passthru("php $pwd/scripts/git/pull.php --reset");
call_user_func(require $pwd . '/scripts/fix-web.php', $pwd, $domain);
call_user_func(require $pwd . '/scripts/build-web.php', $pwd);
