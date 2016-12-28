<?php

use GuzzleHttp\Client;

require_once __DIR__ . '/../../../php/vendor/go1.autoload.php';

return call_user_func(function () use ($argv) {
    if (!$token = getenv('CODE_TOKEN')) {
        throw new RuntimeException('Please provide access token to #code: https://code.go1.com.au/profile/personal_access_tokens');
    }

    $client = new Client(['debug' => false]);
    $headers = ['PRIVATE-TOKEN' => $token, 'Content-Type' => 'application/json'];
    $projects = require __DIR__ . '/../../_projects.php';
    foreach ($projects['php'] as $path) {
        $id = str_replace(['/', '.git'], ['%2F', ''], explode(':', $path)[1]);
        $url = "https://code.go1.com.au/api/v3/projects/{$id}/repository/compare?from=staging&to=production";
        $res = $client->get($url, ['http_errors' => false, 'headers' => $headers]);
        $result = json_decode($res->getBody()->getContents());
        if (isset($result->commit)) {
            $client->post(
                "https://code.go1.com.au/api/v3/projects/{$id}/merge_requests",
                [
                    'headers' => $headers,
                    'json'    => [
                        'source_branch' => 'staging',
                        'target_branch' => 'production',
                        'title'         => 'WIP: [Deploy] staging to production',
                    ],
                ]
            );
        }
    }
});
