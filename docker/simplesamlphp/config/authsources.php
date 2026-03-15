<?php

$config = [
    'admin' => [
        'core:AdminPassword',
    ],

    'example-userpass' => [
        'exampleauth:UserPass',

        'jdoe:jdoe' => [
            'uid' => ['jdoe'],
            'mail' => ['jdoe@example.org'],
            'displayName' => ['John Doe'],
            'givenName' => ['John'],
            'sn' => ['Doe'],
        ],

        'alice:alice' => [
            'uid' => ['alice'],
            'mail' => ['alice@example.org'],
            'displayName' => ['Alice Doe'],
            'givenName' => ['Alice'],
            'sn' => ['Doe'],
        ],

        'bob:bob' => [
            'uid' => ['bob'],
            'mail' => ['bob@example.org'],
            'displayName' => ['Bob Dylan'],
            'givenName' => ['Bob'],
            'sn' => ['Dylan'],
        ],
    ],
];
