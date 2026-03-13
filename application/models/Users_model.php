<?php
/**
 * This file contains the business logic and manages the persistence of users (employees)
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.1.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This model contains the business logic and manages the persistence of users (employees)
 * It is also used by the session controller for the authentication.
 * As of today ther is no distinction between an employee and a user.
 */
class Users_model extends CI_Model
{

    /**
     * Default constructor
     */
    public function __construct()
    {

    }

    /**
     * Get the list of users or one user
     * @param int $id optional id of one user
     * @return ?array record of user(s) or NULL if user was not found
     */
    public function getUsers(int $id = 0): ?array
    {
        if ($id === 0) {
            $query = $this->db->get('users');
            return $query->result_array();
        }
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row_array();
    }

    /**
     * Get the list of users and their roles
     * @return array record of users
     */
    public function getUsersAndRoles(): array
    {
        $this->db->select('users.id, active, firstname, lastname, login, email');
        $this->db->select("GROUP_CONCAT(roles.name SEPARATOR ',') as roles_list", FALSE);
        $this->db->join('roles', 'roles.id = (users.role & roles.id)');
        $this->db->group_by('users.id, active, firstname, lastname, login, email');
        $query = $this->db->get('users');
        return $query->result_array();
    }

    /**
     * Get the list of employees
     * @return array record of users
     */
    public function getAllEmployees(): array
    {
        $this->db->select('id, firstname, lastname, email');
        $query = $this->db->get('users');
        return $query->result_array();
    }

    /**
     * Get the list of employees and the name of their entities
     * @return array record of users
     */
    public function getAllEmployeesAndTheirEntities(): array
    {
        $this->db->select('users.id, firstname, lastname');
        $this->db->select('organization.name as department_name');
        $this->db->from('users');
        $this->db->join('organization', 'users.organization = organization.id');
        $this->db->order_by("lastname", "asc");
        $this->db->order_by("firstname", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get the name of a given user
     * @param int $id Identifier of employee
     * @return string firstname and lastname of the employee
     */
    public function getName(int $id): string
    {
        $record = $this->getUsers($id);
        if (!empty($record)) {
            return $record['firstname'] . ' ' . $record['lastname'];
        }
        return '';
    }

    /**
     * Get the list of employees that are the collaborators of the given user
     * @param int $id identifier of the manager
     * @return array record of users
     */
    public function getCollaboratorsOfManager(int $id = 0): array
    {
        $this->db->select('users.*');
        $this->db->select('organization.name as department_name, positions.name as position_name, contracts.name as contract_name');
        $this->db->from('users');
        $this->db->join('organization', 'users.organization = organization.id');
        $this->db->join('positions', 'positions.id  = users.position', 'left');
        $this->db->join('contracts', 'contracts.id  = users.contract', 'left');
        $this->db->order_by("lastname", "asc");
        $this->db->order_by("firstname", "asc");
        $this->db->where('manager', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Check if an employee is the collaborator of the given user
     * @param int $employeeId identifier of the collaborator
     * @param int $managerId identifier of the manager
     * @return bool TRUE if the employee is a collaborator, FALSE otherwise
     */
    public function isCollaboratorOfManager(int $employeeId, int $managerId): bool
    {
        $this->db->from('users');
        $this->db->where('id', $employeeId);
        $this->db->where('manager', $managerId);
        $result = $this->db->get()->result_array();
        return (count($result) > 0);
    }

    /**
     * Check if a login can be used before creating the user
     * @param string $login login identifier
     * @return bool TRUE if available, FALSE otherwise
     */
    public function isLoginAvailable(string $login): bool
    {
        $this->db->from('users');
        $this->db->where('login', $login);
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Delete a user from the database
     * @param int $id identifier of the user
     */
    public function deleteUser(int $id): void
    {
        $this->db->delete('users', array('id' => $id));
        $this->load->model('entitleddays_model');
        $this->load->model('leaves_model');
        $this->load->model('overtime_model');
        $this->entitleddays_model->deleteEntitledDaysCascadeUser($id);
        $this->leaves_model->deleteLeavesCascadeUser($id);
        $this->overtime_model->deleteExtrasCascadeUser($id);
        //Cascade delete line manager role
        $this->db->where('manager', $id);
        $this->db->update('users', ['manager' => NULL]);
    }

    /**
     * Insert a new user into the database. Inserted data are coming from an HTML form
     * @return string deciphered password (so as to send it by e-mail in clear)
     */
    public function setUsers(): string
    {
        //TODO: remove the stuff about RSA encryption
        //TODO: decouple from CI controller, maybe '..byAPI' method is enough
        $password = $this->input->post('password');
        //Hash the clear password using bcrypt (8 iterations)
        $salt = '$2a$08$' . substr(strtr(base64_encode($this->getRandomBytes(16)), '+', '.'), 0, 22) . '$';
        $hash = crypt($password, $salt);

        //Role field is a binary mask
        $role = 0;
        foreach ($this->input->post("role") as $role_bit) {
            $role = $role | $role_bit;
        }

        $data = [
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'login' => $this->input->post('login'),
            'email' => $this->input->post('email'),
            'password' => $hash,
            'role' => $role,
            'manager' => $this->input->post('manager'),
            'contract' => $this->input->post('contract'),
            'identifier' => $this->input->post('identifier'),
            'language' => $this->input->post('language'),
            'timezone' => $this->input->post('timezone'),
            // for protecting the ICS endpoints
            'random_hash' => rtrim(strtr(base64_encode($this->getRandomBytes(24)), '+/', '-_'), '='),
        ];

        if ($this->input->post('entity') != NULL && $this->input->post('entity') != '') {
            $data['organization'] = $this->input->post('entity');
        }
        if ($this->input->post('position') != NULL && $this->input->post('position') != '') {
            $data['position'] = $this->input->post('position');
        }
        if ($this->input->post('datehired') != NULL && $this->input->post('datehired') != '') {
            $data['datehired'] = $this->input->post('datehired');
        }
        if ($this->config->item('ldap_basedn_db') != FALSE) {
            $data['ldap_path'] = $this->input->post('ldap_path');
        }
        $this->db->insert('users', $data);

        //Deal with user having no line manager
        if ($this->input->post('manager') == -1) {
            $id = $this->db->insert_id();
            $this->db->where('id', $id);
            $this->db->update('users', ['manager' => $id]);
        }
        return $password;
    }

    /**
     * Create a user record in the database. the difference with set_users function is that it doesn't rely
     * on values posted by en HTML form. Can be used by a mass importer for example.
     * @param string $firstname User firstname
     * @param string $lastname User lastname
     * @param string $login User login
     * @param string $email User e-mail
     * @param string $password User password
     * @param int $role role mask (2 for user or 8 for manager)
     * @param ?int $manager Id of the manager or NULL
     * @param ?int $organization Id of the organization or NULL
     * @param ?int $contract Id of the contract or NULL
     * @param ?int $position Id of the position or NULL
     * @param ?string $datehired Date of hiring or NULL
     * @param ?string $identifier Internal identifier or NULL
     * @param ?string $language language code or NULL
     * @param ?string $timezone timezone or NULL
     * @param ?string $ldap_path ldap path or NULL
     * @param ?bool $active Is user active or NULL
     * @param ?string $country country of the employee or NULL
     * @param ?string $calendar calendar path or NULL
     * @param ?string $userProperties JSON encoded user properties or NULL
     * @param ?string $picture Base64 encoded avatar picture or NULL
     * @return int Inserted User Identifier
     */
    public function insertUserByApi(
        string $firstname,
        string $lastname,
        string $login,
        string $email,
        string $password,
        int $role,
        ?int $manager = NULL,
        ?int $organization = NULL,
        ?int $contract = NULL,
        ?int $position = NULL,
        ?string $datehired = NULL,
        ?string $identifier = NULL,
        ?string $language = NULL,
        ?string $timezone = NULL,
        ?string $ldap_path = NULL,
        ?bool $active = NULL,
        ?string $country = NULL,
        ?string $calendar = NULL,
        ?string $userProperties = NULL,
        ?string $picture = NULL
    ): int {

        //Hash the clear password using bcrypt (8 iterations)
        $salt = '$2a$08$' . substr(strtr(base64_encode($this->getRandomBytes(16)), '+', '.'), 0, 22) . '$';
        $hash = crypt($password, $salt);
        $this->db->set('firstname', $firstname);
        $this->db->set('lastname', $lastname);
        $this->db->set('login', $login);
        $this->db->set('email', $email);
        $this->db->set('password', $hash);
        $this->db->set('role', $role);
        $this->db->set('random_hash', rtrim(strtr(base64_encode($this->getRandomBytes(24)), '+/', '-_'), '='));
        if (isset($manager))
            $this->db->set('manager', $manager);
        if (isset($organization))
            $this->db->set('organization', $organization);
        if (isset($contract))
            $this->db->set('contract', $contract);
        if (isset($position))
            $this->db->set('position', $position);
        if (isset($datehired))
            $this->db->set('datehired', $datehired);
        if (isset($identifier))
            $this->db->set('identifier', $identifier);
        if (isset($language))
            $this->db->set('language', $language);
        if (isset($timezone))
            $this->db->set('timezone', $timezone);
        if (isset($ldap_path))
            $this->db->set('ldap_path', $ldap_path);
        if (isset($active))
            $this->db->set('active', $active);
        if (isset($country))
            $this->db->set('country', $country);
        if (isset($calendar))
            $this->db->set('calendar', $calendar);
        if (isset($userProperties))
            $this->db->set('user_properties', $userProperties);
        if (isset($picture))
            $this->db->set('picture', $picture);
        $this->db->insert('users');
        return $this->db->insert_id();
    }

    /**
     * Update a user record in the database. the difference with update_users function is that it doesn't rely
     * on values posted by en HTML form. Can be used by a mass importer for example.
     * @param int $id Id of the user
     * @param array $data Associative array of fields to be updated
     * @return bool TRUE if the SQL query is successful, FALSE otherwise
     */
    public function updateUserByApi(int $id, array $data): bool
    {
        //TODO: this method is not completed
        $password = $data['password'];
        if (isset($password)) {
            //Hash the clear password using bcrypt (8 iterations)
            $salt = '$2a$08$' . substr(strtr(base64_encode($this->getRandomBytes(16)), '+', '.'), 0, 22) . '$';
            $hash = crypt($password, $salt);
            $this->db->set('password', $hash);
        }
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    /**
     * Update a given user in the database. Update data are coming from an HTML form
     * @return bool TRUE if the SQL query is successful, FALSE otherwise
     */
    public function updateUsers(): bool
    {
        //TODO: Decouple from CI controller, maybe 'by API is enough'

        //Role field is a binary mask
        $role = 0;
        foreach ($this->input->post("role") as $role_bit) {
            $role = $role | $role_bit;
        }

        //Deal with user having no line manager
        if ($this->input->post('manager') == -1) {
            $manager = $this->input->post('id');
        } else {
            $manager = $this->input->post('manager');
        }

        $data = array(
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'login' => $this->input->post('login'),
            'email' => $this->input->post('email'),
            'role' => $role,
            'manager' => $manager,
            'contract' => $this->input->post('contract'),
            'identifier' => $this->input->post('identifier'),
            'language' => $this->input->post('language'),
            'timezone' => $this->input->post('timezone')
        );
        if ($this->input->post('entity') != NULL && $this->input->post('entity') != '') {
            $data['organization'] = $this->input->post('entity');
        }
        if ($this->input->post('position') != NULL && $this->input->post('position') != '') {
            $data['position'] = $this->input->post('position');
        }
        if ($this->input->post('datehired') != NULL && $this->input->post('datehired') != '') {
            $data['datehired'] = $this->input->post('datehired');
        }
        if ($this->config->item('ldap_basedn_db') != FALSE) {
            $data['ldap_path'] = $this->input->post('ldap_path');
        }

        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('users', $data);
    }

    /**
     * Update a given user in the database. Update data are coming from an HTML form
     * @param int $userId Id of the user
     * @param string $password Clear password
     * @return bool TRUE if the SQL query is successful, FALSE otherwise
     */
    public function resetPassword(int $userId, string $password): bool
    {
        //Hash the clear password using bcrypt (8 iterations)
        $salt = '$2a$08$' . substr(strtr(base64_encode($this->getRandomBytes(16)), '+', '.'), 0, 22) . '$';
        $hash = crypt($password, $salt);
        $this->db->where('id', $userId);
        return $this->db->update('users', ['password' => $hash]);
    }

    /**
     * Reset a password. Generate a new password and store its hash into db.
     * @param int $id User identifier
     * @return string clear password
     */
    public function resetClearPassword(int $id): string
    {
        //generate a random password of length 10
        $password = $this->randomPassword(10);
        //Hash the clear password using bcrypt (8 iterations)
        $salt = '$2a$08$' . substr(strtr(base64_encode($this->getRandomBytes(16)), '+', '.'), 0, 22) . '$';
        $hash = crypt($password, $salt);
        //Store the new password into db
        $data = array(
            'password' => $hash
        );
        $this->db->where('id', $id);
        $this->db->update('users', $data);
        return $password;
    }

    /**
     * Generate a random password
     * @param int $length length of the generated password
     * @return string generated password
     */
    public function randomPassword(int $length): string
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

    /**
     * Load the profile of a user from the database to the session variables
     * @param object $row database record of a user
     */
    private function loadProfile(object $row): void
    {
        if (((int) $row->role & 1)) {
            $is_admin = TRUE;
        } else {
            $is_admin = FALSE;
        }

        /*
          00000001 1  Admin
          00000100 8  HR Officier / Local HR Manager
          00001000 16 HR Manager
          = 00001101 25 Can access to HR functions
         */
        if (((int) $row->role & 25)) {
            $is_hr = TRUE;
        } else {
            $is_hr = FALSE;
        }

        //Determine if the connected user is a manager or if he has any delegation
        $isManager = FALSE;
        if (count($this->getCollaboratorsOfManager($row->id)) > 0) {
            $isManager = TRUE;
        } else {
            $this->load->model('delegations_model');
            if ($this->delegations_model->hasDelegation($row->id))
                $isManager = TRUE;
        }

        $newdata = array(
            'login' => $row->login,
            'id' => $row->id,
            'firstname' => $row->firstname,
            'lastname' => $row->lastname,
            'is_manager' => $isManager,
            'is_admin' => $is_admin,
            'is_hr' => $is_hr,
            'manager' => $row->manager,
            'random_hash' => $row->random_hash,
            'logged_in' => TRUE
        );
        $this->session->set_userdata($newdata);
    }

    /**
     * Check the provided credentials and load user's profile if they are correct
     * @param string $login user login
     * @param string $password password
     * @return bool TRUE if the user is succesfully authenticated, FALSE otherwise
     */
    public function checkCredentials(string $login, string $password): bool
    {
        $this->db->from('users');
        $this->db->where('login', $login);
        $this->db->where('active = TRUE');
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            //No match found
            return FALSE;
        } else {
            $row = $query->row();
            $hash = crypt($password, $row->password);
            if ($hash == $row->password) {
                // Password does match stored password.
                $this->loadProfile($row);
                return TRUE;
            } else {
                // Password does not match stored password.
                return FALSE;
            }
        }
    }

    /**
     * Check the provided credentials and load user's profile if they are correct
     * It is the LDAP binding operation that checks if Password is correct.
     * @param string $login user login
     * @return bool TRUE if user was found into the database, FALSE otherwise
     */
    public function checkCredentialsLDAP(string $login): bool
    {
        $this->db->from('users');
        $this->db->where('login', $login);
        $this->db->where('active = TRUE');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $this->loadProfile($row);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Check the provided credentials and load user's profile if they are correct
     * Mostly used for alternative signin mechanisms such as SSO
     * @param string $email E-mail address of the user
     * @param string $password Optional password
     * @return bool TRUE if user was found into the database, FALSE otherwise
     */
    public function checkCredentialsEmail(string $email, ?string $password = NULL): bool
    {
        $this->db->from('users');
        $this->db->where('email', $email);
        $this->db->where('active = TRUE');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            if (!is_null($password)) {
                $hash = crypt($password, $row->password);
                if ($hash == $row->password) {
                    $this->loadProfile($row);
                }
            } else {
                $this->loadProfile($row);
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Check the provided credentials and load user's profile if they are correct
     * @param string $login user login (or email for SSO)
     * @param string $type login type could be "internal", "ldap", or "sso"
     * @param string $password password
     * @return ?object user properties if the user is succesfully authenticated, NULL otherwise
     */
    public function checkCredentialsForREST(string $login, string $type = "internal", ?string $password = NULL): ?object
    {
        log_message('debug', '++checkCredentialsForREST / login=' . $login . ' / type=' . $type);
        $this->db->from('users');
        if ($type == "sso") {
            $this->db->where('email', $login);
        } else {
            $this->db->where('login', $login);
        }
        $this->db->where('active = TRUE');
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            log_message('debug', '--checkCredentialsForREST : user not found ' . $login);
            return NULL;
        } else {
            $row = $query->row();
            if ($type != "ldap") {
                $hash = crypt($password, $row->password);
                if ($hash != $row->password) {
                    log_message('debug', '--checkCredentialsForREST : Password does not match stored password');
                    return NULL;
                }
            }
            //We can load the profile
            $user = new stdClass;
            if (((int) $row->role & 1)) {
                $user->isAdmin = TRUE;
            } else {
                $user->isAdmin = FALSE;
            }

            /*
              00000001 1  Admin
              00000100 8  HR Officier / Local HR Manager
              00001000 16 HR Manager
              = 00001101 25 Can access to HR functions
             */
            if (((int) $row->role & 25)) {
                $user->isHr = TRUE;
            } else {
                $user->isHr = FALSE;
            }

            //Determine if the connected user is a manager or if he has any delegation
            $user->isManager = FALSE;
            if (count($this->getCollaboratorsOfManager($row->id)) > 0) {
                $user->isManager = TRUE;
            } else {
                $this->load->model('delegations_model');
                if ($this->delegations_model->hasDelegation($row->id))
                    $user->isManager = TRUE;
            }

            $user->login = $row->login;
            $user->id = $row->id;
            $user->firstname = $row->firstname;
            $user->lastname = $row->lastname;
            $user->manager = $row->manager;
            $user->email = $row->email;
            $user->contract = $row->contract;
            $user->position = $row->position;
            $user->organization = $row->organization;
            log_message('debug', '--checkCredentialsForREST : user #' . $user->id);
            return $user;
        }
    }

    /**
     * Get the LDAP Authentication path of a user
     * @param string $login user login
     * @return string LDAP Authentication path, empty string otherwise
     */
    public function getBaseDN(string $login): string
    {
        $this->db->select('ldap_path');
        $this->db->from('users');
        $this->db->where('login', $login);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->ldap_path;
        } else {
            return "";
        }
    }

    /**
     * Get the list of employees or one employee
     * @param int $id optional id of the entity, all entities if 0
     * @param bool $children TRUE : include sub entities, FALSE otherwise
     * @param string $filterActive "all"; "active" (only), or "inactive" (only)
     * @param ?string $criterion1 "lesser" or "greater" (optional)
     * @param ?string $date1 Date Hired (optional)
     * @param ?string $criterion2 "lesser" or "greater" (optional)
     * @param ?string $date2 Date Hired (optional)
     * @return array record of users
     */
    public function employeesOfEntity(
        int $id = 0,
        bool $children = TRUE,
        string $filterActive = "all",
        ?string $criterion1 = NULL,
        ?string $date1 = NULL,
        ?string $criterion2 = NULL,
        ?string $date2 = NULL
    ): array {
        $this->db->select('users.id as id,'
            . ' users.firstname as firstname,'
            . ' users.lastname as lastname,'
            . ' users.email as email,'
            . ' users.identifier as identifier,'
            . ' users.datehired as datehired,'
            . ' positions.name as position,'
            . ' organization.name as entity,'
            . ' contracts.name as contract,'
            . ' CONCAT_WS(\' \',managers.firstname,  managers.lastname) as manager_name', FALSE);
        $this->db->from('users');
        $this->db->join('contracts', 'contracts.id = users.contract', 'left outer');
        $this->db->join('positions', 'positions.id = users.position', 'left outer');
        $this->db->join('users as managers', 'managers.id = users.manager', 'left outer');
        $this->db->join('organization', 'organization.id = users.organization', 'left outer');

        if ($children == TRUE) {
            $this->load->model('organization_model');
            $list = $this->organization_model->getAllChildren($id);
            $ids = array();
            if (count($list) > 0) {
                if ($list[0]['id'] != '') {
                    $ids = explode(",", $list[0]['id']);
                }
            }
            array_push($ids, $id);
            $this->db->where_in('organization.id', $ids);
        } else {
            $this->db->where('users.organization', $id);
        }

        //Triple value for active filter ("all" = no where criteria)
        if ($filterActive == "active") {
            $this->db->where('users.active', TRUE);
        }
        if ($filterActive == "inactive") {
            $this->db->where('users.active', FALSE);
        }

        if (!is_null($criterion1) && !is_null($date1) && $date1 != "empty" && $date1 != "undefined") {
            $criterion1 = ($criterion1 == "greater" ? ">" : "<");
            $this->db->where("users.datehired " . $criterion1 . " STR_TO_DATE('" . $date1 . "', '%Y-%m-%d')");
        }
        if (!is_null($criterion2) && !is_null($date2) && $date2 != "empty" && $date2 != "undefined") {
            $criterion2 = ($criterion2 == "greater" ? ">" : "<");
            $this->db->where("users.datehired " . $criterion2 . " STR_TO_DATE('" . $date2 . "', '%Y-%m-%d')");
        }

        return $this->db->get()->result();
    }

    /**
     * Update all employees when a contract is deleted (set the field to NULL)
     * @param int $id Contract ID
     * @return bool TRUE if the SQL query is successful, FALSE otherwise
     */
    public function updateUsersCascadeContract(int $id): bool
    {
        $this->db->set('contract', NULL);
        $this->db->where('contract', $id);
        return $this->db->update('users');
    }

    /**
     * Set a user as active (TRUE) or inactive (FALSE)
     * @param int $id User identifier
     * @param bool $active active (TRUE) or inactive (FALSE)
     * @return bool TRUE if the SQL query is successful, FALSE otherwise
     */
    public function setActive(int $id, bool $active): bool
    {
        $this->db->set('active', $active);
        $this->db->where('id', $id);
        return $this->db->update('users');
    }

    /**
     * Check if a user is active (TRUE) or inactive (FALSE)
     * @param string $login login of a user
     * @return bool active (TRUE) or inactive (FALSE)
     */
    public function isActive(string $login): bool
    {
        $this->db->from('users');
        $this->db->where('login', $login);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->active;
        } else {
            return FALSE;
        }
    }

    /**
     * Check if a user is active (TRUE) or inactive (FALSE)
     * @param string $email e-mail of a user
     * @return bool active (TRUE) or inactive (FALSE)
     */
    public function isActiveByEmail(string $email): bool
    {
        $this->db->from('users');
        $this->db->where('email', $email);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->active;
        } else {
            return FALSE;
        }
    }

    /**
     * Try to return the user information from the login field
     * @param string $login Login
     * @return ?object data row or null if no user was found
     */
    public function getUserByLogin(string $login): ?object
    {
        $this->db->from('users');
        $this->db->where('login', $login);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return null;
        } else {
            return $query->row();
        }
    }

    /**
     * Check if a given hash is associated to an existing user
     * @param string $randomHash Random Hash associated to user
     * @return bool TRUE if the user was found, FALSE otherwise
     */
    public function checkUserByHash(string $randomHash): bool
    {
        $this->db->from('users');
        $this->db->where('random_hash', $randomHash);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Generate some random bytes by using openssl, dev/urandom or random
     * @param int $length length of the random string
     * @return string a string of pseudo-random bytes (must be encoded)
     */
    protected function getRandomBytes(int $length): string
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $rnd = openssl_random_pseudo_bytes($length, $strong);
            if ($strong === TRUE)
                return $rnd;
        }
        $sha = '';
        $rnd = '';
        if (file_exists('/dev/urandom')) {
            $fp = fopen('/dev/urandom', 'rb');
            if ($fp) {
                if (function_exists('stream_set_read_buffer')) {
                    stream_set_read_buffer($fp, 0);
                }
                $sha = fread($fp, $length);
                fclose($fp);
            }
        }
        for ($i = 0; $i < $length; $i++) {
            $sha = hash('sha256', $sha . mt_rand());
            $char = mt_rand(0, 62);
            $rnd .= chr(hexdec($sha[$char] . $sha[$char + 1]));
        }
        return $rnd;
    }

    /**
     * Update the manager of a list of employees
     * @param int $managerId DB Identifier of the manager
     * @param array $usersList List of DB ID of the affected employees
     * @return bool TRUE if the SQL query is successful, FALSE otherwise
     */
    public function updateManagerForUserList(int $managerId, array $usersList): bool
    {
        $this->db->where_in('id', $usersList);
        return $this->db->update('users', ['manager' => $managerId]);
    }

    /**
     * Update the entity of a list of employees
     * @param int $entityId DB Identifier of the entity
     * @param array $usersList List of DB ID of the affected employees
     * @return bool TRUE if the SQL query is successful, FALSE otherwise
     */
    public function updateEntityForUserList(int $entityId, array $usersList): bool
    {
        $this->db->where_in('id', $usersList);
        return $this->db->update('users', ['organization' => $entityId]);
    }

    /**
     * Update the contract of a list of employees
     * @param int $contractId DB Identifier of the contract
     * @param array $usersList List of DB ID of the affected employees
     * @return bool TRUE if the SQL query is successful, FALSE otherwise
     */
    public function updateContractForUserList(int $contractId, array $usersList): bool
    {
        $this->db->where_in('id', $usersList);
        return $this->db->update('users', ['contract' => $contractId]);
    }
}
