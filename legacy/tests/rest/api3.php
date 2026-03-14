<?php
/*
 * This test is focused on the scope "users"
 * 
 * How to use this test file :
 *  - Insert the test dataset into DB :
 * INSERT INTO oauth_clients (client_id, client_secret, redirect_uri, scope) VALUES ("testclient", "testpass", "http://fake/", "users");
 *  - Execute from the command line (i.e. php api.php)
 *  
 */

//____________________________________________________________________________________________________
//Get a token
//curl -u testclient:testpass http://localhost/jorani/api/token -d "grant_type=client_credentials"
$url = 'http://localhost/jorani/api/token';
$data = array('grant_type' => 'client_credentials');
$username = "testclient";
$password = "testpass";
$cred = sprintf('Authorization: Basic %s', base64_encode("$username:$password"));
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n" . $cred ."\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

//Parse the response in order to extract the token
$result_array = json_decode($result);
$token = $result_array->access_token;
echo "Token = " . $token . PHP_EOL;

//Get the list of users (with all fields)
$url = 'http://localhost/jorani/api/users/ext';
$data = array('access_token' => $token);
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

//Parse the response in order to extract the first user ID
$result_array = json_decode($result);
$user = $result_array[0]->id;
echo "Employee (" . $user . "):" . $result_array[0]->firstname  . " " . $result_array[0]->lastname . PHP_EOL;
echo "Password = " . $result_array[0]->password . PHP_EOL;
