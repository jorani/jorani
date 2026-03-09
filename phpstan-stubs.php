<?php

// CodeIgniter magic properties
abstract class CI_Model
{
    /** @var CI_DB_query_builder */
    public $db;
    /** @var CI_Loader */
    public $load;
    /** @var CI_Session */
    public $session;
    /** @var CI_Input */
    public $input;
    /** @var CI_Config */
    public $config;
    /** @var CI_Lang */
    public $lang;
    /** @var Users_model */
    public $leaves_model;
    /** @var Users_model */
    public $entitleddays_model;
    /** @var Users_model */
    public $overtime_model;
    /** @var Users_model */
    public $delegations_model;
    /** @var Users_model */
    public $organization_model;

    /** @var Overtime_model */
    public $contracts_model;

    /** @var Leaves_model */
    public $types_model;
    /** @var Leaves_model */
    public $users_model;
    /** @var Leaves_model */
    public $history_model;

    /** @var Dayoffs_model */
    public $contracts_model;
    /** @var users_model */
    public $dayoffs_model;

    /** @var Leaves_model */
    public $lists_model;
}

abstract class CI_Controller
{
    /** @var CI_DB_query_builder */
    public $db;
    /** @var CI_Loader */
    public $load;
    /** @var CI_Session */
    public $session;
    /** @var CI_Input */
    public $input;
    /** @var CI_Lang */
    public $lang;
}
