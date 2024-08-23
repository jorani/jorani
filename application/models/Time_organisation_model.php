<?php
/**
 * This class contains the business logic and manages the persistence of time organisations
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class contains the business logic and manages the persistence of time organisations.
 */
class time_organisation_model extends CI_Model {

    /**
     * Default constructor
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function __construct() {

    }

    /**
     * Get the list of time organisations or one time organisation
     * @param int $id optional id of a time organisation
     * @return array records of time organisations
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTimeOrganisations($id = 0) {
        $this->db->select('time_organisation.*');
        $this->db->select('users.firstname, users.lastname');
        $this->db->from('time_organisation');
        $this->db->join('users', 'time_organisation.employee = users.id');
        if ($id === 0) {
            return $this->db->get()->result_array();
        }
        $this->db->where('time_organisation.id', $id);
        return $this->db->get()->row_array();
    }
    
    /**
     * Get the list of users with and without time organisation
     * @return array records of users with and without time organisation
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTimeOrganisationsForExport() {
        $this->db->select('time_organisation.*');
        $this->db->select('users.id as employee_id, users.firstname, users.lastname');
        $this->db->from('users');
        $this->db->join('time_organisation', 'time_organisation.employee = users.id', 'left outer');
        return $this->db->get()->result_array();
    }

    /**
     * Insert a new time organisation into the database. Inserted data are coming from an HTML form
     * @return int number of affected rows
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function setTimeOrganisations() {
        $data = array(
            'employee' => $this->input->post('employee'),
            'duration' => str_replace(',', '.', $this->input->post('duration')),
            'day' => $this->input->post('day'),
            'daytype' => $this->input->post('daytype'),
            'recurrence' => $this->input->post('recurrence')
        );
        $timeorganisation = $this->getTimeOrganisationForEmployee($data['employee']);
        if (empty($timeorganisation))
            return $this->db->insert('time_organisation', $data);
        else {
            $this->db->where('employee', $data['employee']);
            return $this->db->update('time_organisation', $data);
        }
    }
    
    /**
     * Insert a new time organisation into the database. Inserted data are coming from an imported file
     * @return int number of affected rows
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function setTimeOrganisationsFromImport($data) {
        if($data['duration'] > 0 && $data['day'] != NULL && $data['daytype'] != NULL && $data['recurrence'] != NULL){
            $timeorganisation = $this->getTimeOrganisationForEmployee($data['employee']);
            if (empty($timeorganisation))
                return $this->db->insert('time_organisation', $data);
            else {
                $this->db->where('employee', $data['employee']);
                return $this->db->update('time_organisation', $data);
            }
        } 
    }

    /**
     * Update a given time organisation in the database. Update data are coming from an HTML form
     * @param int $id identifier of the time organisation
     * @return int number of affected rows
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function updateTimeOrganisation($id) {
        $data = array(
            'duration' => str_replace(',', '.', $this->input->post('duration')),
            'day' => $this->input->post('day'),
            'daytype' => $this->input->post('daytype'),
            'recurrence' => $this->input->post('recurrence')
        );
        $this->db->where('id', $id);
        return $this->db->update('time_organisation', $data);
    }   
    
    /**
     * Delete a time organisation from the database
     * @param int $id identifier of the time organisation
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function deleteTimeOrganisation($id) {
        $this->db->delete('time_organisation', array('id' => $id));
    }
    
    /**
     * Get duration of organised time for the given employee id
     * @param int $id id of an employee
     * @return float duration of organised time 
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTimeOrganisationForEmployee($id) {
        $this->db->select('duration, day, daytype, recurrence');
        $this->db->from('time_organisation');
        $this->db->where('employee', $id);

        return $this->db->get()->row_array();
    }
    
    /**
     * Get list of employees for organised time
     * @return list of employees for organised time
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTimeOrganisationEmployees() {
        $this->db->select('employee');
        $this->db->from('time_organisation');
        
        return $this->db->get()->result();
    }
    
    /**
     * List of all time organisation dates for a given employee for valid campaign periods.
     * @param int $id Employee identifier
     * @return array List of all time organisation dates for a given employee for valid campaign periods
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTimeOrganisationDates($id) {
        $dates = array();
        $this->load->model('time_organisation_model');
        $timeorganisation = $this->time_organisation_model->getTimeOrganisationForEmployee($id);
        if ($timeorganisation) {
            $day = $timeorganisation['day'];
            $this->load->model('telework_campaign_model');
            $campaigndates = $this->telework_campaign_model->getValidCampaignDates();
            foreach ($campaigndates as $campaigndate) {
                array_push($dates, list_days_for_campaign($campaigndate->startdate, $campaigndate->enddate, $day));
            }
            if ($timeorganisation['recurrence'] == 'Even' || $timeorganisation['recurrence'] == 'Odd') {
                for ($i = 0; $i < count($dates); $i ++) {
                    for ($ii = 0; $ii < count($dates[$i]); $ii ++) {
                        if ($timeorganisation['recurrence'] == 'Odd' && (new DateTime($dates[$i][$ii]))->format('W') % 2 == 0)
                            array_splice($dates[$i], $ii, 1);
                        if ($timeorganisation['recurrence'] == 'Even' && (new DateTime($dates[$i][$ii]))->format('W') % 2 != 0)
                            array_splice($dates[$i], $ii, 1);
                    }
                }
            }
        }
        return $dates;
    }
    
    /**
     * List of all time organisation dates for a given employee for valid campaign periods.
     * @param int $id Employee identifier
     * @param int $month Month number
     * @param int $year Year number
     * @return array List of all time organisation dates for a given employee for valid campaign periods
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTimeOrganisationDatesToCalendar($id, $month, $year) {
        $result = array();
        $dates = $this->getTimeOrganisationDates($id);
        $this->load->model('time_organisation_model');
        $timeorganisation = $this->time_organisation_model->getTimeOrganisationForEmployee($id);
        if ($timeorganisation) {
            $this->lang->load('calendar', $this->language);
            $daytype = $timeorganisation['daytype'];
            if ($daytype == 'Morning')
                $display = 2;
            else if ($daytype == 'Afternoon')
                $display = 3;
            else
                $display = 1;

            foreach ($dates as $date) {
                for ($i = 0; $i < count($date); $i ++) {
                    if ($year . '-' . sprintf("%02d", $month) == substr($date[$i], 0, 7))
                        $result[ltrim(substr($date[$i], 8, 2), '0')] = array(
                            'id' => 0,
                            'type' => 'Time organisation',
                            'acronym' => lang('time_organisation_acronym'),
                            'status' => 3,
                            'display' => $display
                        );
                }
            }
        }
        return $result;
    }
    
    /**
     * List of all time organisation dates for a given employee for valid campaign periods for a department/list.
     * @param int $entity_id Entity identifier (the department)
     * @param int $list_id List identifier
     * @param string $start Unix timestamp / Start date displayed on calendar
     * @param string $end Unix timestamp / End date displayed on calendar
     * @param bool $children Include sub department in the query
     * @return array List of all time organisation dates for a given employee for valid campaign periods
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTimeOrganisationDatesToEntityOrList($entity_id = -1, $list_id = -1, $start, $end, $children = FALSE) {
        $result = $ids = array();
        $id = 0;
        $this->load->model('time_organisation_model');
        $this->load->model('users_model');
        $timeorganisations = $this->time_organisation_model->getTimeOrganisations();
        if ($entity_id != - 1) {
            if ($children === TRUE) {
                $this->load->model('organization_model');
                $list = $this->organization_model->getAllChildren($entity_id);
                if ($list[0]['id'] != '') {
                    $ids = explode(",", $list[0]['id']);
                    array_push($ids, $entity_id);
                } else {
                    $ids[] = $entity_id;
                }
            } else {
                $ids[] = $entity_id;
            }
        }

        if ($list_id != - 1) {
            $this->load->model('lists_model');
            $list = $this->lists_model->getListOfEmployees($list_id);
            for ($n = 0; $n < count($list); $n ++) {
                array_push($ids, $list[$n]['id']);
            }
        }
        for ($n = 0; $n < count($timeorganisations); $n ++) {
            $id = $timeorganisations[$n]['employee'];
            $dates = $this->getTimeOrganisationDates($id);
            $timeorganisation = $this->time_organisation_model->getTimeOrganisationForEmployee($id);
            if ($timeorganisation) {
                $this->lang->load('calendar', $this->language);
                $daytype = $timeorganisation['daytype'];
                if ($daytype == 'Morning' || $daytype == 'Afternoon')
                    $startdatetype = $enddatetype = $daytype;
                else {
                    $startdatetype = 'Morning';
                    $enddatetype = 'Afternoon';
                }
                $user = $this->users_model->getUsers($id);

                foreach ($dates as $date) {
                    for ($i = 0; $i < count($date); $i ++) {
                        if (strtotime($start) <= strtotime($date[$i]) && strtotime($end) >= strtotime($date[$i]) && ((in_array($user['organization'], $ids) && $entity_id != - 1) || (in_array($user['id'], $ids) && $list_id != - 1)))
                            $result[] = array(
                                'firstname' => $user['firstname'],
                                'lastname' => $user['lastname'],
                                'manager' => $user['manager'],
                                'id' => 0,
                                'startdate' => $date[$i],
                                'enddate' => $date[$i],
                                'status' => 3,
                                'employee' => $id,
                                'cause' => '',
                                'startdatetype' => $startdatetype,
                                'enddatetype' => $enddatetype,
                                'duration' => $timeorganisation['duration'],
                                'type' => 'Time organisation',
                                'comments' => '',
                                'document' => '',
                                'acronym' => lang('time_organisation_acronym')
                            );
                    }
                }
            }
        }
        return $result;
    }
    
    /**
     * List of all time organisation dates for a given employee for valid campaign periods for a department/list.
     * @param int $entity_id Entity identifier (the department)
     * @param int $list_id List identifier
     * @param string $start Unix timestamp / Start date displayed on calendar
     * @param string $end Unix timestamp / End date displayed on calendar
     * @param bool $children Include sub department in the query
     * @return JSON array of all time organisation dates for a given employee for valid campaign periods
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTimeOrganisationDatesToJson($entity_id, $list_id, $start, $end, $children = FALSE) {
        $jsonevents = array();
        $data = $this->getTimeOrganisationDatesToEntityOrList($entity_id, $list_id, $start, $end, $children);

        for ($i = 0; $i < count($data); $i ++) {
            if ($data[$i]['startdatetype'] == "Morning") {
                $startdate = $data[$i]['startdate'] . 'T07:00:00';
            } else {
                $startdate = $data[$i]['startdate'] . 'T12:00:00';
            }

            if ($data[$i]['enddatetype'] == "Morning") {
                $enddate = $data[$i]['enddate'] . 'T12:00:00';
            } else {
                $enddate = $data[$i]['enddate'] . 'T18:00:00';
            }

            $allDay = FALSE;
            $startdatetype = $data[$i]['startdatetype'];
            $enddatetype = $data[$i]['enddatetype'];
            if ($startdatetype != $enddatetype)
                $allDay = TRUE;

            $color = '#9d159d';
            $title = $data[$i]['acronym'] . ' - ' . $data[$i]['firstname'] . ' ' . $data[$i]['lastname'];

            // Create the JSON representation of the event
            $jsonevents[] = array(
                'id' => $data[$i]['id'],
                'title' => $title,
                'imageurl' => '',
                'start' => $startdate,
                'color' => $color,
                'allDay' => $allDay,
                'end' => $enddate,
                'startdatetype' => $startdatetype,
                'enddatetype' => $enddatetype,
                'url' => ''
            );
        }

        return $jsonevents;
    }
}
