<?php
/*
 * THIS SCRIPT SHOULDN'T BE USED IN PRODUCTION. THIS IS A CODE SAMPLE.
 * 
 * This script is an example that illustrates how to use Jorani's REST API so as to add
 * entitled days to employees on various levels (contract, user-specific or entities).
 * 
 * Of course, we don't pretend to cover all use cases as the entitlment varies from one 
 * organization to another.
 * 
 * You need to adapt the script to your needs and the applicable labor law.
 * 
 * Then you can launch this script using a cron tab or by any other tool that allows
 *  you to start it on a regular basis.
 * 
 * How to use this test file :
 *  - Insert an OAuth2 client into DB (feel free to change user and password) :
 *    INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) VALUES ("testclient", "testpass", "http://fake/");
 * 
 */
require("JoraniAPI.php");

//---------------------------------------------------------------------------------------------------------------------------------------------------
// Configuration of the script
//---------------------------------------------------------------------------------------------------------------------------------------------------
$url = 'http://localhost/jorani/';  //URL of REST API
$user = 'testclient';                   //OAuth2 login
$password = 'testpass';             //OAuth2 password

//This script works with the IDs that you have defined in Jorani (i.e. column ID), see for example:
// http://localhost/jorani/hr/employees or http://localhost/jorani/users to get a list of employee ids
// http://localhost/jorani/contracts to get a list of contract ids
// Unfortunately, you must query the database to get a list of entity ids.
// Then fill the array of objects you want to update, e.g. :
//      $employee_ids = array(1,2,3); will update the etitled days of employees 1, 2 and 3
$employee_ids = array();    //Update based on a list of employee ids (leave empty if you work otherwise)
$contract_ids = array();        //Update based on a list of contract ids (leave empty if you work otherwise)
$entity_ids = array();         //Update based on a list of entity ids (leave empty if you work otherwise)

//Include sub-entities
$includeChildren = TRUE;

//Number of days to be added
$days = (float) 1;

//Type of leave (pick a number in the IDs defined in Jorani, eg http://localhost/jorani/leavetypes)
$type = 1;

//Description of the credit line
$description = 'Credit line added by robot - ' . date("D M d, Y G:i");

//Define entitlment period
//CURRENT_PERIOD        The entitlement can be taken only during the current yearly period (recommended)
//'FROM_MONTH              The entitlement can be taken from the current month to the end of yearly period
//CURRENT_MONTH        The entitlement can be taken only during the current month
//CURRENT_YEAR           The entitlement can be taken only during the current year
$period = JoraniAPI::CURRENT_PERIOD;

//---------------------------------------------------------------------------------------------------------------------------------------------------
// End of configuration
//---------------------------------------------------------------------------------------------------------------------------------------------------

//Connect to the REST API
$api = new JoraniAPI($url, $user, $password);

//Get the list of employee ids and add the entitled days
foreach ($employee_ids as $employee_id){
    $employee = $api->getEmployees($employee_id);
    $contract = $api->getContracts($employee->contract);
    $startdate = $api->getStartDate($contract, $period);
    $enddate = $api->getEndDate($contract, $period);
    if ($employee->active == 1) {
        $api->addEntitledDaysEmployee($employee->id, $startdate, $enddate, $days, $type, $description);
        echo 'Added ' . $days . ' day(s) to employee #' . $employee->id . PHP_EOL;
    } else {
        echo 'No credit to inactiveemployee #' . $employee->id . PHP_EOL;
    }
}

//Get the list of contract ids and add the entitled days
foreach ($contract_ids as $contract_id){
    $contract = $api->getContracts($contract_id);
    $startdate = $api->getStartDate($contract, $period);
    $enddate = $api->getEndDate($contract, $period);
    $api->addEntitledDaysContract($contract->id, $startdate, $enddate, $days, $type, $description);
    echo 'Added ' . $days . ' day(s) to contract #' . $contract_id . PHP_EOL;
}

//Get the list of entity ids and add the entitled days
foreach ($entity_ids as $entity_id){
    $list_employees = $api->getListOfEmployeesInEntity($entity_id, $includeChildren);
    //Get the list of employee ids and add the entitled days
    foreach ($list_employees as $employee){
        $employee = $api->getEmployees($employee->id);
        $contract = $api->getContracts($employee->contract);
        $startdate = $api->getStartDate($contract, $period);
        $enddate = $api->getEndDate($contract, $period);
        if ($employee->active == 1) {
            $api->addEntitledDaysEmployee($employee->id, $startdate, $enddate, $days, $type, $description);
            echo 'Added ' . $days . ' day(s) to employee #' . $employee->id . PHP_EOL;
        } else {
            echo 'No credit to inactiveemployee #' . $employee->id . PHP_EOL;
        }
    }
}
