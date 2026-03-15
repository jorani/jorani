<?php

$metadata['http://localhost:8080/simplesaml/saml2/idp/metadata.php'] = [
    'host' => '__DEFAULT__',
    'privatekey' => 'my-idp.pem',
    'certificate' => 'my-idp.crt',
    'auth' => 'example-userpass',
    'authproc' => [
        20 => [
            'class' => 'saml:AttributeNameID',
            'attribute' => 'mail',
            'Format' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
        ],
    ],
];
