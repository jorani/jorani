<?php
/**
 * This Class contains all the business logic and the persistence layer for the overtime requests.
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/jorani/jorani
 * @since   0.1.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This Class contains all the business logic and the persistence layer for the overtime requests.
 * It encompasses the notions of:
 *  - Extra (when requested by an employee).
 *  - Overtime (when sumitted to a manager for a validation).
 */
class Overtime_model extends CI_Model
{

    /**
     * Default constructor
     */
    public function __construct()
    {

    }

    /**
     * Get the list of all overtime requests or one overtime request
     * @param int $id Id of the overtime request
     * @return array list of records
     */
    public function getExtras($id = 0)
    {
        $this->db->select('overtime.*');
        $this->db->select('status.name as status_name');
        $this->db->from('overtime');
        $this->db->join('status', 'overtime.status = status.id');
        if ($id === 0) {
            return $this->db->get()->result_array();
        }
        $this->db->where('overtime.id', $id);
        return $this->db->get()->row_array();
    }

    /**
     * Get the the list of overtime requested by a given employee
     * @param int $employee ID of the employee
     * @return array list of records
     */
    public function getExtrasOfEmployee($employee)
    {
        $this->db->select('overtime.*');
        $this->db->select('status.name as status_name');
        $this->db->from('overtime');
        $this->db->join('status', 'overtime.status = status.id');
        $this->db->where('overtime.employee', $employee);
        $this->db->order_by('overtime.id', 'desc');
        return $this->db->get()->result_array();
    }

    /**
     * Create an overtime request. Data are coming from an HTTP POSTed form
     * @return int id of the overtime request into the db
     */
    public function setExtra(): int
    {
        $data = [
            'date' => $this->input->post('date'),
            'employee' => $this->session->userdata('id'),
            'duration' => $this->input->post('duration'),
            'cause' => $this->input->post('cause'),
            'status' => $this->input->post('status')
        ];
        $this->db->insert('overtime', $data);
        return $this->db->insert_id();
    }

    /**
     * Update an overtime request in the database. Data are coming from an HTTP POSTed form
     * @param int $id overtime request identifier
     * @return bool result of update in database
     */
    public function updateExtra($id)
    {
        $data = [
            'date' => $this->input->post('date'),
            'duration' => $this->input->post('duration'),
            'cause' => $this->input->post('cause'),
            'status' => $this->input->post('status')
        ];
        $this->db->where('id', $id);
        return $this->db->update('overtime', $data);
    }

    /**
     * Accept an overtime request
     * @param int $id overtime request identifier
     */
    public function acceptExtra($id): void
    {
        $this->db->where('id', $id);
        $this->db->update('overtime', ['status' => 3]);

        //Add entitlement
        $extra = $this->getExtras($id);

        //Two cases:
        // - has contract: we take the start and end dates from the contract.
        // - no contract: boundaries are current year.
        $this->load->model('contracts_model');
        $startentdate = NULL;
        $endentdate = NULL;
        $hasContract = $this->contracts_model->getBoundaries($id, $startentdate, $endentdate, date("Y-m-d H:i:s"));
        if (!$hasContract) {
            $startentdate = date("Y-m-d", strtotime('first day of january this year'));
            $endentdate = date("Y-m-d", strtotime('last day of december this year'));
        }
        $data = [
            'employee' => $extra['employee'],
            'startdate' => $startentdate,
            'enddate' => $endentdate,
            'days' => $extra['duration'],
            'type' => 0,
            'overtime' => $id,
            'description' => 'Catch up ' . $extra['date']
        ];
        $this->db->insert('entitleddays', $data);
    }

    /**
     * Reject an overtime request
     * @param int $id overtime request identifier
     * @return bool TRUE if update query was successful, FALSE otherwise
     */
    public function rejectExtra($id)
    {
        //delete linked entitlement
        $this->db->delete('entitleddays', ['overtime' => $id]);
        $this->db->where('id', $id);
        return $this->db->update('overtime', ['status' => 4]);
    }

    /**
     * Delete an overtime request from the database
     * @param int $id overtime request identifier
     * @return bool TRUE if delete query was successful, FALSE otherwise
     */
    public function deleteExtra($id): bool
    {
        //delete linked entitlement
        $this->db->delete('entitleddays', ['overtime' => $id]);
        return $this->db->delete('overtime', ['id' => $id]);
    }

    /**
     * Delete overtime rquests attached to a user (when it is deleted)
     * @param int $id identifier of an employee
     */
    public function deleteExtrasCascadeUser($id): void
    {
        $this->db->delete('overtime', ['employee' => $id]);
    }

    /**
     * List all overtime requests submitted to the connected user (or if delegate of a manager)
     * Can be filtered with "Requested" status.
     * @param int $user_id connected user
     * @param bool $all TRUE all requests, FALSE otherwise
     * @return array Recordset (can be empty if no requests or not a manager)
     */
    public function requests($user_id, $all = FALSE): array
    {
        $this->load->model('delegations_model');
        $ids = $this->delegations_model->listManagersGivingDelegation($user_id);
        $this->db->select('overtime.id as id, users.*, overtime.*');
        $this->db->select('status.name as status_name');
        $this->db->join('status', 'overtime.status = status.id');
        $this->db->join('users', 'users.id = overtime.employee');
        if (count($ids) > 0) {
            array_push($ids, $user_id);
            $this->db->where_in('users.manager', $ids);
        } else {
            $this->db->where('users.manager', $user_id);
        }
        if ($all == FALSE) {
            $this->db->where('status', 2);
        }
        $this->db->order_by('date', 'desc');
        $query = $this->db->get('overtime');
        return $query->result_array();
    }

    /**
     * Count extra requests submitted to the connected user (or if delegate of a manager)
     * @param int $manager connected user
     * @return int number of requests
     */
    public function countExtraRequestedToManager($manager): int
    {
        $this->load->model('delegations_model');
        $ids = $this->delegations_model->listManagersGivingDelegation($manager);
        $this->db->select('count(*) as number', FALSE);
        $this->db->join('users', 'users.id = overtime.employee');
        $this->db->where('status', 2);

        if (count($ids) > 0) {
            array_push($ids, $manager);
            $this->db->where_in('users.manager', $ids);
        } else {
            $this->db->where('users.manager', $manager);
        }
        $result = $this->db->get('overtime');
        return $result->row()->number;
    }

    /**
     * Purge the table by deleting the records prior $toDate
     * @param string $toDate date in format Y-m-d
     * @return int number of affected rows
     */
    public function purgeOvertime($toDate): int
    {
        $this->db->where(' <= ', $toDate);
        return $this->db->delete('overtime');
    }

    /**
     * Count the number of rows into the table
     * @return int number of rows
     */
    public function count(): int
    {
        $this->db->select('count(*) as number', FALSE);
        $this->db->from('overtime');
        $result = $this->db->get();
        return $result->row()->number;
    }

    /**
     * Detect overtime with a negative duration. This is a warning as it substract entitled days to user.
     * @return array list of invalid requests
     */
    public function detectNegativeOvertime(): array
    {
        $this->db->select('overtime.*, CONCAT(users.firstname, \' \', users.lastname) as user_label', FALSE);
        $this->db->select('status.name as status_label');
        $this->db->join('users', 'users.id = overtime.employee');
        $this->db->join('status', 'overtime.status = status.id', 'inner');
        $this->db->where('overtime.duration < 0');
        return $this->db->get('overtime')->result_array();
    }
}
