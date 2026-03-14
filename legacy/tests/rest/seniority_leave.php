<?php
/*
 * THIS SCRIPT SHOULDN'T BE USED IN PRODUCTION. THIS IS A CODE SAMPLE.
 * 
 * This script is an example that illustrates how to use Jorani's REST API so as to add
 * a seniority entitled day to an employee based on his entry date.
 * 
 * Of course, we don't pretend to cover all use cases as this bonus varies from one 
 * organization to another. In this example, we will add one day of seniority leave to
 * any employee being in the company since more than a year (for the current year).
 * You need to adapt the script to your needs (for example, some companies add
 * 1 extra day for 5 years, etc.).
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

//Number of days to be added. In this example, one day is a very simple rule...
//You might need to calculate this value employee by employee.
$days = (float) 1;  //Most of organizations use a complicated rule (eg. extra after 5 years, extra bonus after 10 yers etc.)

//Type of leave (pick a number in the IDs defined in Jorani, eg http://localhost/jorani/leavetypes)
$type = 1;      //You must use a dedicated leave type for seniority bonus for this script to work

//Condition tested against date hired field ' - 365 day'
$condition = ' - 1 year';

//Description of the credit line
$description = 'Seniority bonus added by robot - ' . date("D M d, Y G:i");

//Define entitlment period
//CURRENT_YEAR           The entitlement can be taken only during the current year (most current case)
//CURRENT_PERIOD        The entitlement can be taken only during the current yearly period
//'FROM_MONTH              The entitlement can be taken from the current month to the end of yearly period
//CURRENT_MONTH        The entitlement can be taken only during the current month
$period = JoraniAPI::CURRENT_YEAR;

//---------------------------------------------------------------------------------------------------------------------------------------------------
// End of configuration
//---------------------------------------------------------------------------------------------------------------------------------------------------

//Connect to the REST API
$api = new JoraniAPI($url, $user, $password);
//Iterate on the employees list (datehired > $condition). Most of the time, last year.
$floorDate = new DateTime(date('Y-m-d',strtotime(date("Y-m-d", time()) . $condition)));
//Get the list of employees
$employees = $api->getEmployees();

foreach ($employees as $employee){
    $datehired = new DateTime($employee->datehired);
    if ($datehired < $floorDate) {
        echo "senior employee  #" . $employee->id . PHP_EOL;
        //Check if we already credited seniority entitled days for the current period
        $entitled_days = $api->getEntitledDaysListForEmployee($employee->id);
        $contract = $api->getContracts($employee->contract);
        $startdate = $api->getStartDate($contract, $period);
        $enddate = $api->getEndDate($contract, $period);
        $hasSeniority = $api->hasEntitlementInPeriod($employee->id, $type, $startdate, $enddate);
        if (!$hasSeniority) {
                echo "The employee has no seniority bonus." . PHP_EOL;
                if ($employee->active == 1) {
                    $api->addEntitledDaysEmployee($employee->id, $startdate, $enddate, $days, $type, $description);
                    echo 'Added ' . $days . ' day(s) to employee #' . $employee->id . PHP_EOL;
                } else {
                    echo 'No credit to inactiveemployee #' . $employee->id . PHP_EOL;
                }
        } else {
            echo "The employee already has seniority bonus." . PHP_EOL;
        }
    } else {
        echo "recent employee #" . $employee->id . PHP_EOL;
    }
}

