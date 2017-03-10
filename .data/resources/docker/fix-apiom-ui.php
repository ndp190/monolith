<?php

$config = file_get_contents('/apiomui/scripts/config.apiom.js');
if (!strpos('#MONOLITH', $config)) {
    $config = str_replace('api-dev.mygo1.com', 'localhost/GO1', $config);
    $config = str_replace('https://localhost/GO1/v3/', 'localhost/v3/', $config);
    file_put_contents('/apiomui/scripts/config.apiom.js', $config);
}

if (true) {
    return null;
}

$html = file_get_contents('/apiomui/index.html');
if (!strpos('#MONOLITH', $html)) {
    $local = "\n\n<!-- ====================================== #MONOLITH ==================================================== -->\n";
    $local .= '<script src="scripts/config.apiom.js"></script>' . "\n";
    $local .= '<script src="scripts/app.js"></script>' . "\n";
    $local .= '<script src="scripts/config.js"></script>' . "\n";
    $local .= "<!-- ========================================================================================== -->\n\n";

    $matches = [];
    preg_match_all(
        '`(<script src="//cdn.go1static.com/assets/[0-9\-]+/js/vendor.[0-9a-z]+.js"></script>)`i',
        $html,
        $matches
    );

    $html = str_replace($matches[1][0], $matches[1][0] . $local, $html);

    file_put_contents('/apiomui/index.html', $html);
}
