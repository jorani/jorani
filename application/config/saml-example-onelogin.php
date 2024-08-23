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

//You shouldn't change the 'sp' sub-array, but only the content of 'idp' sub-array
//Sp is specific to the application (Jorani)
//Idp is specific to your identity provider
$samlSettings = array(
    'sp' => array(
        'entityId' => base_url() . 'api/metadata',
        'assertionConsumerService' => array(
            'url' => base_url() . 'api/acs',
        ),
        'singleLogoutService' => array(
            'url' => base_url() . 'api/sls',
        ),
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
    ),
    'idp' => array(
        'entityId' => 'https://app.onelogin.com/saml/metadata/561670',
        'singleSignOnService' => array(
            'url' => 'https://jorani.onelogin.com/trust/saml2/http-post/sso/561670',
        ),
        'singleLogoutService' => array(
            'url' => 'https://jorani.onelogin.com/trust/saml2/http-redirect/slo/561670',
        ),
        'x509cert' => '-----BEGIN CERTIFICATE-----
MIIEETCCAvmgAwIBAgIUAlUlf+CytDcqaV/rFFR5LxkXxAUwDQYJKoZIhvcNAQEF
BQAwVjELMAkGA1UEBhMCVVMxDzANBgNVBAoMBkpvcmFuaTEVMBMGA1UECwwMT25l
TG9naW4gSWRQMR8wHQYDVQQDDBZPbmVMb2dpbiBBY2NvdW50IDg3MzUzMB4XDTE2
MDcwODEyMzYxMVoXDTIxMDcwOTEyMzYxMVowVjELMAkGA1UEBhMCVVMxDzANBgNV
BAoMBkpvcmFuaTEVMBMGA1UECwwMT25lTG9naW4gSWRQMR8wHQYDVQQDDBZPbmVM
b2dpbiBBY2NvdW50IDg3MzUzMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKC
AQEAs0erB7z+dN/CBeuPakhPRhK8mtT1PikMeYbRSh6W1cHGZVR9iKxIJR29tKX7
8IuifNKXDSwWH3sumva8ADgoyvJBZYFx7ryk2I0/vh3FPYpCdJRNAWH6vcWobgHm
IlJngHZf2dZqvx8FnLhddfxv3YdDegDp8pPe5ny+fum7I6zKec26ilUGLvTzL7uD
gLRccrXDkXFQ8ohpTkqAKaV4aW+p8bYBNEpSwpBZLN/RaWxjcx/yQNIlbXu3ezqG
I7vQupcxpDkyauh7/yLEsvkkXjFlvEFmlezPTu9UW5rKiWKefLz+4LVZD4HPUNcj
jPbQN15YN5QQ98pAbtc/vKyhsQIDAQABo4HWMIHTMAwGA1UdEwEB/wQCMAAwHQYD
VR0OBBYEFPpQe2bBNyf9/2fduw82NuenQmiGMIGTBgNVHSMEgYswgYiAFPpQe2bB
Nyf9/2fduw82NuenQmiGoVqkWDBWMQswCQYDVQQGEwJVUzEPMA0GA1UECgwGSm9y
YW5pMRUwEwYDVQQLDAxPbmVMb2dpbiBJZFAxHzAdBgNVBAMMFk9uZUxvZ2luIEFj
Y291bnQgODczNTOCFAJVJX/gsrQ3Kmlf6xRUeS8ZF8QFMA4GA1UdDwEB/wQEAwIH
gDANBgkqhkiG9w0BAQUFAAOCAQEAjo54wsSrfB0AQ57VAwNECU6zP+fr4IedRSP0
FgboAz0y3ujrkgdd+1aorXy1DhNPuUcIEJOX3Aa97ahPSBd/8hB6wzx1K/7Q4V96
3unfSJ07cMCeEEmip3mdlX0m9cul0XRoUkgpTusVYGeD+0go3/d6BWRJ4kF4XGTm
nqjxFIEueI9tsQXWaWLxh+ccjkKzzDHJFYX/mCygkM3Ho7cOfQuBrQpPbkbgWmKA
TP+yhnSo3b8aYe9VAEJIdK0d0daHIGFNGEFQXHqN+jEh55fVLwoRJtp1CzO5/Ebv
LnraIOHdhP7OCkyZ3FwpUnlYeFsp4IBQEdp2ooPouFyNE50wzQ==
-----END CERTIFICATE-----',
    ),

    /////////////////////////////////////////////////////////////////////////////////
    // Advanced settinge
    /////////////////////////////////////////////////////////////////////////////////

    // Compression settings
    'compress' => array(
        'requests' => true,
        'responses' => true
    ),
    // Security settings
    'security' => array(

        /** signatures and encryptions offered */

        // Indicates that the nameID of the <samlp:logoutRequest> sent by this SP
        // will be encrypted.
        'nameIdEncrypted' => false,

        // Indicates whether the <samlp:AuthnRequest> messages sent by this SP
        // will be signed.  [Metadata of the SP will offer this info]
        'authnRequestsSigned' => false,

        // Indicates whether the <samlp:logoutRequest> messages sent by this SP
        // will be signed.
        'logoutRequestSigned' => false,

        // Indicates whether the <samlp:logoutResponse> messages sent by this SP
        // will be signed.
        'logoutResponseSigned' => false,

        /* Sign the Metadata
         False || True (use sp certs) || array(
                                                    keyFileName => 'metadata.key',
                                                    certFileName => 'metadata.crt'
                                                )
        */
        'signMetadata' => false,

        /** signatures and encryptions required **/

        // Indicates a requirement for the <samlp:Response>, <samlp:LogoutRequest>
        // and <samlp:LogoutResponse> elements received by this SP to be signed.
        'wantMessagesSigned' => false,

        // Indicates a requirement for the <saml:Assertion> elements received by
        // this SP to be encrypted.
        'wantAssertionsEncrypted' => false,

        // Indicates a requirement for the <saml:Assertion> elements received by
        // this SP to be signed. [Metadata of the SP will offer this info]
        'wantAssertionsSigned' => false,

        // Indicates a requirement for the NameID element on the SAMLResponse
        // received by this SP to be present.
        'wantNameId' => true,

        // Indicates a requirement for the NameID received by
        // this SP to be encrypted.
        'wantNameIdEncrypted' => false,

        // Authentication context.
        // Set to false and no AuthContext will be sent in the AuthNRequest.
        // Set true or don't present this parameter and you will get an AuthContext 'exact' 'urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport'.
        // Set an array with the possible auth context values: array('urn:oasis:names:tc:SAML:2.0:ac:classes:Password', 'urn:oasis:names:tc:SAML:2.0:ac:classes:X509').
        'requestedAuthnContext' => true,

        // Indicates if the SP will validate all received xmls.
        // (In order to validate the xml, 'strict' and 'wantXMLValidation' must be true).
        'wantXMLValidation' => true,

        // If true, SAMLResponses with an empty value at its Destination
        // attribute will not be rejected for this fact.
        'relaxDestinationValidation' => false,

        // Algorithm that the toolkit will use on signing process. Options:
        //    'http://www.w3.org/2000/09/xmldsig#rsa-sha1'
        //    'http://www.w3.org/2000/09/xmldsig#dsa-sha1'
        //    'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256'
        //    'http://www.w3.org/2001/04/xmldsig-more#rsa-sha384'
        //    'http://www.w3.org/2001/04/xmldsig-more#rsa-sha512'
        // Notice that rsa-sha1 is a deprecated algorithm and should not be used
        'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',

        // Algorithm that the toolkit will use on digest process. Options:
        //    'http://www.w3.org/2000/09/xmldsig#sha1'
        //    'http://www.w3.org/2001/04/xmlenc#sha256'
        //    'http://www.w3.org/2001/04/xmldsig-more#sha384'
        //    'http://www.w3.org/2001/04/xmlenc#sha512'
        // Notice that sha1 is a deprecated algorithm and should not be used
        'digestAlgorithm' => 'http://www.w3.org/2001/04/xmlenc#sha256',

        // ADFS URL-Encodes SAML data as lowercase, and the toolkit by default uses
        // uppercase. Turn it True for ADFS compatibility on signature verification
        'lowercaseUrlencoding' => false,
    ),

    // Contact information template, it is recommended to supply a
    // technical and support contacts.
    /*'contactPerson' => array(
        'technical' => array(
            'givenName' => '',
            'emailAddress' => ''
        ),
        'support' => array(
            'givenName' => '',
            'emailAddress' => ''
        ),
    ),*/

    // Organization information template, the info in en_US lang is
    // recomended, add more if required.
    /*'organization' => array(
        'en-US' => array(
            'name' => '',
            'displayname' => '',
            'url' => ''
        ),
    ),*/
);
