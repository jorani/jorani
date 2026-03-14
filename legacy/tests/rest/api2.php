<?php
/*
How to use this test file :
 - Insert the test dataset into DB :
    INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) VALUES ("testclient", "testpass", "http://fake/");
 - Uncomment / comment the parts you want to test
 - Execute from the command line (i.e. php api.php)

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

//____________________________________________________________________________________________________
//Create a leave request
$url = 'http://localhost/jorani/api/createleave';
$data = array('access_token' => $token,
				'startdate' => '2015-05-10',
				'enddate' => '2015-05-10',
				'status' => 1,
				'employee' => 1,
				'cause' => 'Automatic',
				'startdatetype' => 'Morning',
				'enddatetype' => 'Afternoon',
				'duration' => 1,
				'type' => 0,
				);
				
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

//Parse the response in order to extract the new leave ID
$result_int = json_decode($result);
echo var_dump($result_int);

//____________________________________________________________________________________________________
//Create an employee and display its ID
//Language contains the english language name (eg 'french', 'italian', etc.)
//Here, we pass TRUE as we want to send an e-mail to the new employee
//It is recommended to ommit the password (it'll be generated on server side
/*$url = 'http://localhost/jorani/api/createuser/TRUE';
$data = array('access_token' => $token,
        'firstname' => 'Toto',
        'lastname' => 'TATA',
        'login' => 'ttata',
        'email' => 'ttata@free.fr',
        'role' => 2,
		'password' => 'ttata',		
		);
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

//Parse the response in order to extract the new user ID
$result_int = json_decode($result);
echo var_dump($result_int);*/

//____________________________________________________________________________________________________
//Delete an employee (this is not recommended. Consider put the employee in an archive entity of your org.)
/*$url = 'http://localhost/jorani/api/deleteuser/35';
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

//Parse the response in order to extract the status
$result_str = json_decode($result);
echo var_dump($result_str);*/

//____________________________________________________________________________________________________
//Update an employee
/*$url = 'http://localhost/jorani/api/updateuser/195';
$data = array('access_token' => $token,
				'firstname' => 'Benjamin',
				);
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

//Parse the response in order to extract the status
$result_str = json_decode($result);
echo var_dump($result_str);*/

//____________________________________________________________________________________________________
//Get the monthly presence stats
//we need to pass the UTC timestamp of the reference date (here today)
/*$url = 'http://localhost/jorani/api/monthlypresence/1/2/2015';
echo var_dump($url);
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

//Parse the response in order to extract the report
$result_object = json_decode($result);
echo var_dump($result_object);*/
