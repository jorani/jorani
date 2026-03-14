<?php

// CodeIgniter stubs

class MY_Loader extends CI_Loader
{
    /**
     * @param string $path
     * @param string $view
     * @param array $data
     */
    public function customView(string $path, string $view, array $data): void
    {
    }
}

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

    /** @var Contracts_model */
    public $contracts_model;
    /** @var Dayoffs_model */
    public $dayoffs_model;
    /** @var Delegations_model */
    public $delegations_model;
    /** @var Entitleddays_model */
    public $entitleddays_model;
    /** @var History_model */
    public $history_model;
    /** @var Leaves_model */
    public $leaves_model;
    /** @var Lists_model */
    public $lists_model;
    /** @var Organization_model */
    public $organization_model;
    /** @var Overtime_model */
    public $overtime_model;
    /** @var Types_model */
    public $types_model;
    /** @var Users_model */
    public $users_model;
}

abstract class CI_Controller
{
    /** @var CI_DB_query_builder */
    public $db;
    /** @var MY_Loader */
    public $load;
    /** @var CI_Session */
    public $session;
    /** @var CI_Input */
    public $input;
    /** @var CI_Lang */
    public $lang;

    public function __construct()
    {
    }
}
