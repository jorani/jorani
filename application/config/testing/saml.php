<?php
/**
 * SAML Configuration file (for Onelogin PHP Library)
 * Full documentation is available at https://developers.onelogin.com/saml/php
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.5.0
 */

//You must switch $config['saml_enabled'] to TRUE into config/config.php prior using SAML

//Field Mapping : how to get the e-mail
$samlMailMap = 'User.email';

//You shouldn't change the 'sp' sub-array, but only the content of 'idp' sub-array
//Sp is specific to the application (Jorani)
//Idp is specific to your identity provider
$settingsInfo = array(
    'sp' => array(
        'entityId' => base_url() . 'metadata',
        'assertionConsumerService' => array(
            'url' => base_url() . 'api/acs',
        ),
        'singleLogoutService' => array(
            'url' => base_url() . 'api/sls',
        ),
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
    ),
    'idp' => array(
        'entityId' => '',
        'singleSignOnService' => array(
            'url' => '',
        ),
        'singleLogoutService' => array(
            'url' => '',
        ),
        'x509cert' => '',
    ),
);
