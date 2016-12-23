<?php

use GuzzleHttp\Client;

require_once __DIR__ . '/../../php/vendor/go1.autoload.php';

return call_user_func(function () use ($argv) {
    if (!$token = getenv('CODE_TOKEN')) {
        throw new RuntimeException('Please provide access token to #code: https://code.go1.com.au/profile/personal_access_tokens');
    }

    $client = new Client(['debug' => false]);
    $headers = ['PRIVATE-TOKEN' => $token, 'Content-Type' => 'application/json'];

    $projects = require __DIR__ . '/../_projects.php';
    foreach ($projects['php'] as $name => $path) {
        echo "Update configuration for $name\n";

        $id = str_replace(['/', '.git'], ['%2F', ''], explode(':', $path)[1]);
        $client
            ->put(
                "https://code.go1.com.au/api/v3/projects/{$id}", [
                'http_errors' => false,
                'headers'     => $headers,
                'json'        => [
                    'default_branch'                                   => 'master',
                    'request_access_enabled'                           => false,
                    'issues_enabled'                                   => false,
                    'wiki_enabled'                                     => false,
                    'snippets_enabled'                                 => false,
                    'lfs_enabled'                                      => false,
                    'container_registry_enabled'                       => true,
                    'visibility_level'                                 => 0,
                    'public_builds'                                    => false,
                    'only_allow_merge_if_build_succeeds'               => true,
                    'only_allow_merge_if_all_discussions_are_resolved' => true,
                ],
            ])
            ->getBody()
            ->getContents();

        foreach (['master', 'staging', 'production'] as $branch) {
            $client->put(
                "https://code.go1.com.au/api/v3/projects/{$id}/repository/branches/{$branch}/protect",
                [
                    'http_errors' => false,
                    'headers'     => $headers,
                ]
            );
        }
    }
});
