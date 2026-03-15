<?php

$config = [
    'baseurlpath' => 'simplesaml/',

    'certdir' => 'cert/',
    'loggingdir' => 'log/',
    'datadir' => 'data/',

    'secretsalt' => 'change-this-secret-salt-for-tests',
    'auth.adminpassword' => 'admin',
    'admin.protectindexpage' => false,

    'technicalcontact_name' => 'Test Admin',
    'technicalcontact_email' => 'admin@example.local',

    'timezone' => 'Europe/Paris',

    'enable.saml20-idp' => true,

    'module.enable' => [
        'exampleauth' => true,
        'admin' => true,
    ],
];
