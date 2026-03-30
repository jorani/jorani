<?php
/**
 * This controller allows to manage the list of leave types
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.1.0
 */

use App\Traits\DoctrineBridge;
use App\Traits\TranslationBridge;
use App\Entity\Type;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This class allows to manage the list of leave types
 * @property CI_Config $config
 * @property CI_Lang $lang
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property Contracts_model $contracts_model
 * @property Dayoffs_model $dayoffs_model
 * @property Entitleddays_model $entitleddays_model
 * @property Leaves_model $leaves_model
 * @property OAuthClients_model $oauthclients_model
 * @property Organization_model $organization_model
 * @property Positions_model $positions_model
 * @property Overtime_model $overtime_model
 * @property Users_model $users_model
 */
class LeaveTypes extends CI_Controller
{
    use DoctrineBridge;
    use TranslationBridge;

    /**
     * Default constructor
     */
    public function __construct()
    {
        parent::__construct();
        setUserContext($this);
        $this->lang->load('leavetypes', $this->language);
        $this->initDoctrine();
    }

    /**
     * Display the list of leave types
     */
    public function index(): void
    {
        $this->auth->checkIfOperationIsAllowed('leavetypes_list');
        $data = getUserContext($this);
        $data['leavetypes'] = $this->em->getRepository(Type::class)->findAll();
        $data['title'] = lang('leavetypes_type_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_edit_leave_type');
        $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('leavetypes/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Display a form that allows adding a leave type
     */
    public function create(): void
    {
        $this->auth->checkIfOperationIsAllowed('leavetypes_create');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('leavetypes_popup_create_title');
        $data['leavetypes'] = $this->em->getRepository(Type::class)->findAll();
        $this->form_validation->set_rules('name', lang('leavetypes_popup_create_field_name'), 'required|strip_tags');

        if ($this->form_validation->run() === false) {
            $this->load->view('leavetypes/create', $data);
        } else {
            $name = $this->input->post('name', true);
            $existingType = $this->em->getRepository(Type::class)->findOneBy(['name' => $name]);
            // Check if the leave type already exists
            if ($existingType === null) {
                $type = new Type();
                $type->setName($name);
                $type->setDeductDaysOff(($this->input->post('deduct_days_off') == 'on') ? true : false);
                $type->setAcronym($this->input->post('acronym', true));
                $this->em->persist($type);
                $this->em->flush();
                $this->session->set_flashdata('msg', lang('leavetypes_popup_create_flash_msg'));
            }
            redirect('leavetypes');
        }
    }

    /**
     * Display a form that allows editing a leave type
     * @param int $id Identitier of the leave type
     */
    public function edit(int $id): void
    {
        $this->auth->checkIfOperationIsAllowed('leavetypes_edit');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('leavetypes_popup_update_title');
        $data['id'] = $id;
        $data['leavetypes'] = $this->em->getRepository(Type::class)->findAll();
        $data['leavetype'] = $this->em->getRepository(Type::class)->find($id);

        if ($data['leavetype'] === null) {
            redirect('leavetypes');
        }

        $this->form_validation->set_rules('name', lang('leavetypes_popup_update_field_name'), 'required|strip_tags');

        if ($this->form_validation->run() === false) {
            $this->load->view('leavetypes/edit', $data);
        } else {
            $name = $this->input->post('name', true);
            $existingType = $this->em->getRepository(Type::class)->findOneBy(['name' => $name]);

            if ($existingType === null || $existingType->getId() === $id) {
                $deduct = ($this->input->post('deduct_days_off') == 'on') ? true : false;
                $data['leavetype']->setName($name);
                $data['leavetype']->setDeductDaysOff($deduct);
                $data['leavetype']->setAcronym(mb_substr($this->input->post('acronym', true), 0, 10));

                $this->em->flush();
                $this->session->set_flashdata('msg', lang('leavetypes_popup_update_flash_msg'));
            }
            redirect('leavetypes');
        }
    }

    /**
     * Action: delete a leave type
     * @param int $id leave type identifier
     */
    public function delete(int $id): void
    {
        $this->auth->checkIfOperationIsAllowed('leavetypes_delete');
        if ($id != 0) {
            /** @var \App\Repository\TypeRepository $typeRepo */
            $typeRepo = $this->em->getRepository(Type::class);

            if ($typeRepo->usage($id) > 0) {
                $this->session->set_flashdata('msg', lang('leavetypes_popup_delete_flash_forbidden'));
            } else {
                $type = $typeRepo->find($id);
                if ($type !== null) {
                    $this->em->remove($type);
                    $this->em->flush();
                }
                $this->session->set_flashdata('msg', lang('leavetypes_popup_delete_flash_msg'));
            }
        } else {
            $this->session->set_flashdata('msg', lang('leavetypes_popup_delete_flash_error'));
        }
        redirect('leavetypes');
    }

    /**
     * Action: export the list of all leave types into an Excel file
     */
    public function export(): void
    {
        $this->auth->checkIfOperationIsAllowed('leavetypes_export');
        $data['leavetypes'] = $this->em->getRepository(Type::class)->findAll();
        $this->load->view('leavetypes/export', $data);
    }
}
