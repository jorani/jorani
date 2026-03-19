<?php
/**
 * This file contains the business logic and manages the persistence of contracts
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.1.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This class contains the business logic and manages the persistence of contracts.
 * @property CI_Config $config
 * @property CI_DB $db
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property Contracts_model $contracts_model
 * @property Dayoffs_model $dayoffs_model
 * @property Entitleddays_model $entitleddays_model
 * @property Types_model $types_model
 * @property Users_model $users_model
 * @property Leaves_model $leaves_model
 */
class Contracts_model extends CI_Model
{

    /**
     * Default constructor
     * 
     */
    public function __construct()
    {

    }

    /**
     * Get the list of contracts or one contract
     * @param int $contractId optional id of a contract
     * @return array<string, mixed>|null list of contract(s) or NULL if contract was not found
     */
    public function getContracts(int $contractId = 0): ?array
    {
        if ($contractId === 0) {
            $this->db->order_by("name", "asc");
            $query = $this->db->get('contracts');
            return $query->result_array();
        }
        $this->db->where('id', $contractId);
        $query = $this->db->get('contracts');
        return $query->row_array();
    }

    /**
     * Get the name of a given contract
     * @param int $contractId Unique identifier of a contract
     * @return string name of the contract
     */
    public function getName(int $contractId): string
    {
        $record = $this->getContracts($contractId);
        if (!empty($record)) {
            return $record['name'];
        } else {
            return '';
        }
    }

    /**
     * Insert a new contract into the database. Inserted data are coming from an HTML form
     * @return bool TRUE if the SQL query is successful, FALSE otherwise
     */
    public function setContracts(): bool
    {
        //TODO: this part is ugly ==> decouple controller from model (in application\controllers\Contracts.php)
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
     * @param int $contractId identifier of the contract
     */
    public function deleteContract(int $contractId): void
    {
        $this->db->delete('contracts', array('id' => $contractId));
        $this->load->model('users_model');
        $this->load->model('entitleddays_model');
        $this->load->model('dayoffs_model');
        $this->entitleddays_model->deleteEntitledDaysCascadeContract($contractId);
        $this->dayoffs_model->deleteDaysOffCascadeContract($contractId);
        $this->users_model->updateUsersCascadeContract($contractId);
    }

    /**
     * Update a given contract in the database. Update data are coming from an HTML form
     * @return bool TRUE if the SQL query is successful, FALSE otherwise
     */
    public function updateContract(): bool
    {
        //TODO: this part is ugly ==> decouple controller from model (in application\controllers\Contracts.php)
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
     * @param int $userId Unique identifier of a user
     * @param string $startentdate start date of the current leave period
     * @param string $endentdate end date of the current leave period
     * @param ?string $refDate tmp of the Date of reference (or current date if NULL)
     * @return bool TRUE means that the user has a contract, FALSE otherwise
     */
    public function getBoundaries(int $userId, string &$startentdate, string &$endentdate, ?string $refDate = NULL): bool
    {
        //TODO: start and en dates are references, we should return a Plain Old PHP Object as it is a business object
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
            if ($startmonth == 1) {
                $startentdate = $refYear . "-" . str_replace("/", "-", $boundaries[0]['startentdate']);
                $endentdate = $refYear . "-" . str_replace("/", "-", $boundaries[0]['endentdate']);
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
     * @return array<string, mixed> list of unused contracts
     */
    public function notUsedContracts(): array
    {
        $this->db->select('contracts.*');
        $this->db->join('users', 'contracts.id = users.contract', 'left outer');
        $this->db->where('users.contract IS NULL');
        return $this->db->get('contracts')->result_array();
    }

    /**
     * Get the list of included leave types in a contract
     * @param int $contractId identifier of the contract
     * @return array<string, mixed> Associative array of types (id, name)
     */
    public function getListOfIncludedTypes(int $contractId): array
    {
        $listOfTypes = array();
        $this->db->select('types.id as id, types.name as name');
        $this->db->from('types');
        $this->db->join(
            'excluded_types',
            'excluded_types.type_id = types.id AND excluded_types.contract_id = ' . $this->db->escape($contractId),
            'left'
        );
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
     * @param int $contractId identifier of the contract
     * @return array<int, string> Associative array of types (id, name)
     */
    public function getListOfExcludedTypes(int $contractId): array
    {
        $listOfTypes = [];
        $this->db->select('types.id as id, types.name as name');
        $this->db->from('excluded_types');
        $this->db->join('types', 'excluded_types.type_id = types.id');
        $this->db->order_by("types.name", "asc");
        $this->db->where('excluded_types.contract_id', $contractId);
        $rows = $this->db->get()->result_array();
        foreach ($rows as $row) {
            $listOfTypes[(int) $row['id']] = $row['name'];
        }
        return $listOfTypes;
    }

    /**
     * Get the usage of leave types for a given contract
     * @param int $contractId identifier of the contract
     * @return array<int, int> Associative array of types (id, name)
     */
    public function getTypeUsageForContract(int $contractId): array
    {
        //Intit the list usage with zero values
        $usageArray = [];
        $this->load->model('types_model');
        $allTypes = $this->types_model->getTypes();
        foreach ($allTypes as $row) {
            $usageArray[(int) $row['id']] = (int) 0;
        }

        //Find out the actual types usage for the contract
        $this->db->select('types.id as type_id, count(types.id) as type_usage', FALSE);
        $this->db->from('types');
        $this->db->join('leaves', 'types.id = leaves.type');
        $this->db->join('users', 'leaves.employee = users.id');
        $this->db->join('contracts', 'users.contract = contracts.id');
        $this->db->where('contracts.id', $contractId);
        $this->db->group_by('types.id');

        //Complete the associative array type:usage
        $rows = $this->db->get()->result_array();
        foreach ($rows as $row) {
            $usageArray[(int) $row['type_id']] = (int) $row['type_usage'];
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
     */
    public function getLeaveTypesDetailsOTypesForUser(int $userId, ?int $leaveType = NULL): object
    {
        //TODO: return a Plain Old PHP Object as it is a business object
        $this->load->model('users_model');
        $this->load->model('types_model');
        $this->load->model('leaves_model');

        //What is the default type ?
        //First of all, we need infos about the user (namely its contract)
        $user = $this->users_model->getUsers($userId);
        $contract = $this->getContracts($user['contract']);
        //If a default leave type is set on the contract, it overwrites what is set in config file
        $defaultType = $this->config->item('default_leave_type');
        $defaultType = (($defaultType == FALSE) || (is_null($this->config->item('default_leave_type')))) ? 0 : $defaultType;
        if (!empty($contract)) {
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
     * @return string OK: if it was possible to perform the operation
     */
    public function excludeLeaveTypeForContract(int $contractId, int $typeId): string
    {
        //TODO: we should check what is the default type and if it is used by any leave request
        //TODO: this function always returns OK, Only used by a unique Ajax endpoint
        $data = [
            'contract_id' => $contractId,
            'type_id' => $typeId
        ];
        $this->db->insert('excluded_types', $data);
        return "OK";
    }

    /**
     * Exclude a leave type for a contract
     * @param int $contractId identifier of the contract
     * @param int $typeId identifier of the leave type
     */
    public function includeLeaveTypeInContract(int $contractId, int $typeId): void
    {
        $this->db->delete('excluded_types', ['contract_id' => $contractId, 'type_id' => $typeId]);
    }
}
