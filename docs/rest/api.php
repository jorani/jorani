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

/*
//____________________________________________________________________________________________________
//Get the list of positions
$url = 'http://localhost/jorani/api/positions';
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

//Parse the response
$result_array = json_decode($result);
echo var_dump($result_array);

//____________________________________________________________________________________________________
//Get the list of types
$url = 'http://localhost/jorani/api/leavetypes';
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

//Parse the response
$result_array = json_decode($result);
echo var_dump($result_array);
*/
//____________________________________________________________________________________________________
//Get the list of employees
$url = 'http://localhost/jorani/api/users';
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
/*
//____________________________________________________________________________________________________
//Get the department of a user (label and ID)
$url = 'http://localhost/jorani/api/userdepartment/' . $user;
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

//Parse the response
$result_array = json_decode($result);
echo var_dump($result_array);

//____________________________________________________________________________________________________
//Get the list of contracts
$url = 'http://localhost/jorani/api/contracts';
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

//Parse the response in order to extract the first contract ID
$result_array = json_decode($result);
$contract = $result_array[0]->id;
echo "Contract (" . $contract . "):" . $result_array[0]->name . PHP_EOL;

//____________________________________________________________________________________________________
//Add some entitled days to this contract
$url = 'http://localhost/jorani/api/addentitleddayscontract/' . $contract;
$data = array('access_token' => $token,
        'startdate' => '2014-01-01',
        'enddate' => '2014-12-31',
        'days' => 1,
        'type' => 0,
        'description' => 'Automated REST API',);
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

//Get the result (of the SQL execution) => it should be the last inserted ID
$result_array = json_decode($result);
echo var_dump($result_array);            

//____________________________________________________________________________________________________
//Now, list the entitled days credited to this contract
$url = 'http://localhost/jorani/api/entitleddayscontract/' . $contract;
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

//Parse the response in order to extract the first contract ID
$result_array = json_decode($result);
echo var_dump($result_array);


//____________________________________________________________________________________________________
//Add some entitled days to an employee
$url = 'http://localhost/jorani/api/addentitleddaysemployee/' . $user;
$data = array('access_token' => $token,
        'startdate' => '2014-01-01',
        'enddate' => '2014-12-31',
        'days' => 1,
        'type' => 0,
        'description' => 'Automated REST API',);
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

//Get the result (of the SQL execution) => it should be the last inserted ID
$result_array = json_decode($result);
echo var_dump($result_array);

//____________________________________________________________________________________________________
//Now, list the entitled days credited to this employee
$url = 'http://localhost/jorani/api/entitleddaysemployee/' . $user;
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

//Parse the response in order to extract the first contract ID
$result_array = json_decode($result);
echo var_dump($result_array);

//____________________________________________________________________________________________________
//Get the leaves counter for a given employee
//we need to pass the UTC timestamp of the reference date (here today)
//$url = 'http://localhost/jorani/api/leavessummary/' . $user . '/' . time(); //You can pass a timestamp
//$url = 'http://localhost/jorani/api/leavessummary/' . $user; //Or ask the API to use the current date
$url = 'http://localhost/jorani/api/leavessummary/' . $user . '/1419980400'; //Or use a date in the past
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

//Parse the response in order to extract the first contract ID
$result_array = json_decode($result);
echo var_dump($result_array);

//____________________________________________________________________________________________________
//List the leave requests of an employee
$url = 'http://localhost/jorani/api/userleaves/' . $user;
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

//Parse the response
$result_array = json_decode($result);
echo var_dump($result_array);

//____________________________________________________________________________________________________
//List the extra requests of an employee (overtime requests)
$url = 'http://localhost/jorani/api/userextras/' . $user;
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

//Parse the response
$result_array = json_decode($result);
echo var_dump($result_array);

//____________________________________________________________________________________________________
//Get all leaves between two dates
//we need to pass the UTC timestamp of the reference date (here today)
$url = 'http://localhost/jorani/api/leaves/1419980400/' . time(); //You can pass a timestamp
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

//Parse the response in order to extract the first contract ID
$result_array = json_decode($result);
echo var_dump($result_array);
*/