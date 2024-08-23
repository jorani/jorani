<?php
/**
 * This class contains the business logic and manages the persistence of contracts
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class contains the business logic and manages the persistence of contracts.
 */
class Contracts_model extends CI_Model {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {

    }

    /**
     * Get the list of contracts or one contract
     * @param int $id optional id of a contract
     * @return array records of contracts
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getContracts($id = 0) {
        if ($id === 0) {
            $this->db->order_by("name", "asc");
            $query = $this->db->get('contracts');
            return $query->result_array();
        }
        $query = $this->db->get_where('contracts', array('id' => $id));
        return $query->row_array();
    }

    /**
     * Get the name of a given contract
     * @param int $id Unique identifier of a contract
     * @return string name of the contract
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getName($id) {
        $record = $this->getContracts($id);
        if (!empty($record)) {
            return $record['name'];
        } else {
            return '';
        }
    }

    /**
     * Insert a new contract into the database. Inserted data are coming from an HTML form
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function setContracts() {
        $startentdate = str_pad($this->input->post('startentdatemonth'), 2, "0", STR_PAD_LEFT) .
                "/" . str_pad($this->input->post('startentdateday'), 2, "0", STR_PAD_LEFT);
        $endentdate = str_pad($this->input->post('endentdatemonth'), 2, "0", STR_PAD_LEFT) .
                "/" . str_pad($this->input->post('endentdateday'), 2, "0", STR_PAD_LEFT);
        $data = array(
            'name' => $this->input->post('name'),
            'startentdate' => $startentdate,
            'endentdate' => $endentdate,
            'default_leave_type' => $this->input->post('default_leave_type')
        );
        return $this->db->insert('contracts', $data);
    }

    /**
     * Delete a contract from the database
     * @param int $id identifier of the contract
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function deleteContract($id) {
        $this->db->delete('contracts', array('id' => $id));
        $this->load->model('users_model');
        $this->load->model('entitleddays_model');
        $this->load->model('dayoffs_model');
        $this->entitleddays_model->deleteEntitledDaysCascadeContract($id);
        $this->dayoffs_model->deleteDaysOffCascadeContract($id);
        $this->users_model->updateUsersCascadeContract($id);
    }

    /**
     * Update a given contract in the database. Update data are coming from an HTML form
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function updateContract() {
        $startentdate = str_pad($this->input->post('startentdatemonth'), 2, "0", STR_PAD_LEFT) .
                "/" . str_pad($this->input->post('startentdateday'), 2, "0", STR_PAD_LEFT);
        $endentdate = str_pad($this->input->post('endentdatemonth'), 2, "0", STR_PAD_LEFT) .
                "/" . str_pad($this->input->post('endentdateday'), 2, "0", STR_PAD_LEFT);
        $data = array(
            'name' => $this->input->post('name'),
            'startentdate' => $startentdate,
            'endentdate' => $endentdate,
            'default_leave_type' => $this->input->post('default_leave_type')
        );
        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('contracts', $data);
    }

    /**
     * Computes the boundaries (current leave period) of the contract of a user
     * Modifies the start and end dates passed as parameter
     * @param int Unique identifier of a user
     * @param &date start date of the current leave period
     * @param &date end date of the current leave period
     * @param string $refDate tmp of the Date of reference (or current date if NULL)
     * @return bool TRUE means that the user has a contract, FALSE otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getBoundaries($userId, &$startentdate, &$endentdate, $refDate = NULL) {
        $this->db->select('startentdate, endentdate');
        $this->db->from('contracts');
        $this->db->join('users', 'users.contract = contracts.id');
        $this->db->where('users.id', $userId);
        $boundaries = $this->db->get()->result_array();

        if ($refDate == NULL) {
            $refDate = date("Y-m-d");
        }
        $refYear = substr($refDate, 0, 4);
        $refMonth = substr($refDate, 5, 2);
        $nextYear = strval(intval($refYear) + 1);
        $lastYear = strval(intval($refYear) - 1);

        if (count($boundaries) != 0) {
            $startmonth = intval(substr($boundaries[0]['startentdate'], 0, 2));
            if ($startmonth == 1 ) {
                $startentdate = $refYear . "-" . str_replace("/", "-", $boundaries[0]['startentdate']);
                $endentdate =  $refYear . "-" . str_replace("/", "-", $boundaries[0]['endentdate']);
            } else {
                if (intval($refMonth) < 6) {
                    $startentdate = $lastYear . "-" . str_replace("/", "-", $boundaries[0]['startentdate']);
                    $endentdate = $refYear . "-" . str_replace("/", "-", $boundaries[0]['endentdate']);
                } else {
                    $startentdate = $refYear . "-" . str_replace("/", "-", $boundaries[0]['startentdate']);
                    $endentdate = $nextYear . "-" . str_replace("/", "-", $boundaries[0]['endentdate']);
                }
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Detect not used contracts (maybe duplicated)
     * @return array list of unused contracts
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function notUsedContracts() {
        //SELECT contracts.* FROM `contracts` LEFT OUTER JOIN users ON contracts.id = users.contract
        // WHERE users.contract IS NULL
        $this->db->select('contracts.*');
        $this->db->join('users', 'contracts.id = users.contract', 'left outer');
        $this->db->where('users.contract IS NULL');
        return $this->db->get('contracts')->result_array();
    }

    /**
     * Get the list of included leave types in a contract
     * @param int $id identifier of the contract
     * @return array Associative array of types (id, name)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getListOfIncludedTypes($id) {
        $listOfTypes = array();
        $this->db->select('types.id as id, types.name as name');
        $this->db->from('types');
        $this->db->join('excluded_types',
                'excluded_types.type_id = types.id AND excluded_types.contract_id = ' . $this->db->escape($id),
                'left');
        $this->db->where('excluded_types.type_id IS NULL');
        $this->db->order_by("types.name", "asc");
        $rows = $this->db->get()->result_array();
        foreach ($rows as $row) {
            $listOfTypes[$row['id']] = $row['name'];
        }
        return $listOfTypes;
    }

    /**
     * Get the list of excluded leave types in a contract
     * @param int $id identifier of the contract
     * @return array Associative array of types (id, name)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getListOfExcludedTypes($id) {
        $listOfTypes = array();
        $this->db->select('types.id as id, types.name as name');
        $this->db->from('excluded_types');
        $this->db->join('types', 'excluded_types.type_id = types.id');
        $this->db->order_by("types.name", "asc");
        $this->db->where('excluded_types.contract_id', $id);
        $rows = $this->db->get()->result_array();
        foreach ($rows as $row) {
            $listOfTypes[$row['id']] = $row['name'];
        }
        return $listOfTypes;
    }

    /**
     * Get the usage of leave types for a given contract
     * @param int $id identifier of the contract
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getTypeUsageForContract($id) {
        //Intit the list usage with zero values
        $usageArray = array();
        $this->load->model('types_model');
        $allTypes = $this->types_model->getTypes();
        foreach ($allTypes as $row) {
            $usageArray[$row['id']] = (int) 0;
        }

        //Find out the actual types usage for the contract
        $this->db->select('types.id as type_id, count(types.id) as type_usage', FALSE);
        $this->db->from('types');
        $this->db->join('leaves', 'types.id = leaves.type');
        $this->db->join('users', 'leaves.employee = users.id');
        $this->db->join('contracts', 'users.contract = contracts.id');
        $this->db->where('contracts.id', $id);
        $this->db->group_by('types.id');

        //Complete the associative array type:usage
        $rows = $this->db->get()->result_array();
        foreach ($rows as $row) {
            $usageArray[$row['type_id']] = (int) $row['type_usage'];
        }
        return $usageArray;
    }

    /**
     * Get an object representing - for an employee:
     *  - The Default leave type (config or contract)
     *  - Credit for the default leave ype (entitlments - taken) or for the selected type
     *  - Ordered (by name) collection of leave types, for each item:
     *    * ID
     *    * Name
     * @param int $userId identifier of the user
     * @param int $leaveType identifier of the selected leave type or NULL
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getLeaveTypesDetailsOTypesForUser($userId, $leaveType = NULL) {
        $this->load->model('users_model');
        $this->load->model('types_model');
        $this->load->model('leaves_model');

        //What is the default type ?
        //First of all, we need infos about the user (namely its contract)
        $user = $this->users_model->getUsers($userId);
        $contract = $this->getContracts($user['contract']);
        //If a default leave type is set on the contract, it overwrites what is set in config file
        $defaultType = $this->config->item('default_leave_type');
        $defaultType = (($defaultType == FALSE) || (is_null($defaultType))) ? 0 : $defaultType;
        if (!is_null($contract)) {
            if (array_key_exists('default_leave_type', $contract)) {
                if (!is_null($contract['default_leave_type'])) {
                    $defaultType = $contract['default_leave_type'];
                }
            }
        }

        //Build the list of types
        $types = $this->types_model->getTypesAsArray();
        //Compute the credit of entitlment for the default leave type
        if (is_null($leaveType)) {
            $credit = $this->leaves_model->getLeavesTypeBalanceForEmployee($userId, $types[$defaultType]);
        } else {
            $credit = $this->leaves_model->getLeavesTypeBalanceForEmployee($userId, $types[$leaveType]);
        }

        //Filter this array by removing the excluded types
        $excludedTypes = $this->contracts_model->getListOfExcludedTypes($user['contract']);
        $types = array_diff($types, $excludedTypes);

        //Let's return an anonymous object containing all these details
        $leaveTypesDetails = new stdClass;
        $leaveTypesDetails->defaultType = $defaultType;
        $leaveTypesDetails->credit = $credit;
        $leaveTypesDetails->types = $types;
        return $leaveTypesDetails;
    }

    /**
     * Exclude a leave type for a contract
     * @param int $contractId identifier of the contract
     * @param int $typeId identifier of the leave type
     * @return string OK: possible or OK impossible to perform the operation
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function excludeLeaveTypeForContract($contractId, $typeId) {
        //TODO we should check what is the default type and if it is used by any leave request
        $data = array(
            'contract_id' => $contractId,
            'type_id' => $typeId
        );
        $this->db->insert('excluded_types', $data);
        return "OK";
    }

    /**
     * Exclude a leave type for a contract
     * @param int $contractId identifier of the contract
     * @param int $typeId identifier of the leave type
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function includeLeaveTypeInContract($contractId, $typeId) {
        $this->db->delete('excluded_types', array('contract_id' => $contractId, 'type_id' => $typeId));
    }
}
