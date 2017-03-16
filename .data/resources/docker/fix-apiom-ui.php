<?php

$config = file_get_contents('/apiomui/scripts/config.apiom.js');
if (!strpos('localhost/GO1/', $config)) {
    $config = str_replace('api-dev.mygo1.com', 'localhost/GO1', $config);
    $config = str_replace('https://localhost/GO1/v3/', 'http://localhost/GO1/', $config);
    $config = str_replace('signup_wizard_domain:\'.mygo1.com\'', 'signup_wizard_domain:\'.go1.local\'', $config);
    file_put_contents('/apiomui/scripts/config.apiom.js', $config);
}

$html = file_get_contents('/apiomui/index.html');
if (!strpos('#MONOLITH', $html)) {
    $local = "\n\n<!-- ====================================== #MONOLITH ==================================================== -->\n";
    $local .= '<script src="scripts/config.apiom.js"></script>' . "\n";

    $matches = [];
    preg_match_all(
        '`(<script src="//cdn.go1static.com/assets/[0-9\-]+/js/scripts.[0-9a-z]+.js"></script>)`i',
        $html,
        $matches
    );

    file_put_contents('/apiomui/index.html', str_replace($matches[1][0], $matches[1][0] . $local, $html));
}
