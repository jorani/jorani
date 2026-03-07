<?php
/*
 * This file is part of Jorani.
 *
 * Jorani is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jorani is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jorani.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * JoraniAPI is a wrapper for the REST API of Jorani
 * 
 * Please note that oauth_clients table must be initialised prior using the REST API
 * 
 * 
 */
class JoraniAPI
{
    private $base_url = 'http://localhost/jorani/';
    private $token = NULL;

    const CURRENT_PERIOD = 1;          //The entitlement can be taken only during the current yearly period
    const FROM_MONTH = 2;                //The entitlement can be taken from the current month to the end of yearly period
    const CURRENT_MONTH = 3;         //The entitlement can be taken only during the current month
    const CURRENT_YEAR = 4;              //The entitlement can be taken only during the current year

    /**
     * Constructor of JoraniAPI. Set a base URL and get an OAtuh2 token
     * @param string $url base URL of the REST API
     * @param string $username username of OAtuh2 user
     * @param string $password password of OAtuh2 user
     */
    function __construct($url, $username, $password)
    {
        $this->base_url = $url;
        $this->token = $this->getToken($username, $password);
    }

    /**
     * Get an OAuth2 token
     * @param string $username username of OAtuh2 user
     * @param string $password password of OAtuh2 user
     * @return string Authentication Token
     * 
     */
    private function getToken($username, $password)
    {
        $url = $this->base_url . 'api/token';
        $data = array('grant_type' => 'client_credentials');
        $cred = sprintf('Authorization: Basic %s', base64_encode("$username:$password"));
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n" . $cred . "\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result_array = json_decode($result);
        $token = $result_array->access_token;
        return $token;
    }

    /**
     * Get a list of employees or an employee
     * @param int $employee Identifier of the employee or NULL to get the list of all employees
     * @return array list of employees
     * 
     */
    public function getEmployees($employee = NULL)
    {
        if (is_null($employee)) {
            $url = $this->base_url . 'api/users';
        } else {
            $url = $this->base_url . 'api/users/' . $employee;
        }
        $data = array('access_token' => $this->token);
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result_array = json_decode($result);
        return $result_array;
    }

    /**
     * Return the list of entitled days credited to an employee
     * @param int $employee Identifier of the employee into the database
     * @return array list of entitled days
     * 
     */
    public function getEntitledDaysListForEmployee($employee)
    {
        $url = $this->base_url . 'api/entitleddaysemployee/' . $employee;
        $data = array('access_token' => $this->token);
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result_array = json_decode($result);
        return $result_array;
    }

    /**
     * Add entitled days to an employee
     * @param int $employee Identifier of the employee into the database
     * @param string $startdate String formatted start date
     * @param string $enddate String formatted end date
     * @param int $days Number of days to be credited
     * @param int $type Leave type
     * @param string $description Description of the record (eg 'inserted by Robot')
     * @return int Id of the inserted record
     * 
     */
    public function addEntitledDaysEmployee($employee, $startdate, $enddate, $days, $type, $description)
    {
        $url = $this->base_url . 'api/addentitleddaysemployee/' . $employee;
        $data = array('access_token' => $this->token,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'days' => $days,
            'type' => $type,
            'description' => $description, );
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        //Get the result (of the SQL execution) => it should be the last inserted ID
        $result_int = json_decode($result);
        return $result_int;
    }

    /**
     * Add entitled days to a contract
     * @param int $contract Identifier of the contract into the database
     * @param string $startdate String formatted start date
     * @param string $enddate String formatted end date
     * @param int $days Number of days to be credited
     * @param int $type Leave type
     * @param string $description Description of the record (eg 'inserted by Robot')
     * @return int Id of the inserted record
     * 
     */
    public function addEntitledDaysContract($contract, $startdate, $enddate, $days, $type, $description)
    {
        $url = $this->base_url . 'api/addentitleddayscontract/' . $contract;
        $data = array('access_token' => $this->token,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'days' => $days,
            'type' => $type,
            'description' => $description, );
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        //Get the result (of the SQL execution) => it should be the last inserted ID
        $result_int = json_decode($result);
        return $result_int;
    }

    /**
     * Get the list of all contracts or a given contract by its ID
     * @param int $contract Identifier of the contract or NULL to get the list of all contracts
     * 
     */
    public function getContracts($contract = NULL)
    {
        if (is_null($contract)) {
            $url = $this->base_url . 'api/contracts';
        } else {
            $url = $this->base_url . 'api/contracts/' . $contract;
        }
        $data = array('access_token' => $this->token);
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result_array = json_decode($result);
        return $result_array;
    }

    /**
     * Get the list of employees attached to a given entity
     * @param int $entity Identifier of the entity
     * @param bool $children If TRUE, we include sub-entities, FALSE otherwise
     * 
     */
    public function getListOfEmployeesInEntity($entity, $children = TRUE)
    {
        $url = $this->base_url . 'api/getListOfEmployeesInEntity/' . $entity . '/' . (($children === TRUE) ? 'true' : 'false');
        $data = array('access_token' => $this->token);
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result_array = json_decode($result);
        return $result_array;
    }

    /**
     * Compute a start date of entitlment, by using the contract of the employee and predefined constants
     * @param object $contract Contract
     * @param int $period Entitlment period
     * @return string start date of entitlment (in MySQL format YYYY-MM-DD)
     */
    public function getStartDate($contract, $period = self::CURRENT_PERIOD)
    {
        switch ($period) {
            case self::CURRENT_PERIOD:
                $startdate = date('Y') . '-' . str_replace('/', '-', $contract->startentdate);
                break;
            case self::FROM_MONTH:
            case self::CURRENT_MONTH:
                $startdate = date('Y-m-01');
                break;
            default://CURRENT_YEAR
                $startdate = date('Y') . '-01-01';
        }
        return $startdate;
    }

    /**
     * Compute an end date of entitlment, by using the contract of the employee and predefined constants
     * @param object $contract Contract
     * @param int $period Entitlment period
     * @return string end date of entitlment (in MySQL format YYYY-MM-DD)
     */
    public function getEndDate($contract, $period = self::CURRENT_PERIOD)
    {
        switch ($period) {
            case self::CURRENT_PERIOD:
            case self::FROM_MONTH:
                $enddate = date('Y') . '-' . str_replace('/', '-', $contract->endentdate);
                break;
            case self::CURRENT_MONTH:
                $enddate = date('Y-m-t');
                break;
            default://CURRENT_YEAR
                $enddate = date('Y') . '-12-31';
        }
        return $enddate;
    }

    /**
     * Check if an employee has an entitlment in a given period
     * @param int $employee Identifier of the employee
     * @param int $type Identifier of the leave type
     * @param string $startdate start date of entitlment (in MySQL format YYYY-MM-DD)
     * @param string $enddate end date of entitlment (in MySQL format YYYY-MM-DD)
     * @return boolean an entitlment has been credited between $startdate and $enddate
     */
    function hasEntitlementInPeriod($employee, $type, $startdate, $enddate)
    {
        $startdate = DateTime::createFromFormat('Y-m-d', $startdate);
        $enddate = DateTime::createFromFormat('Y-m-d', $enddate);
        $entitled_days = $this->getEntitledDaysListForEmployee($employee);
        foreach ($entitled_days as $credit) {
            if ($credit->type == $type) {
                $creditStartdate = DateTime::createFromFormat('Y-m-d', $credit->startdate);
                $creditEnddate = DateTime::createFromFormat('Y-m-d', $credit->enddate);
                if (($creditStartdate >= $startdate) && ($creditEnddate <= $enddate)) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
}
