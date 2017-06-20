<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // initially no rights for any study
        $this->permissions = array(
            $this->config->item('role:contributor') => FALSE,
            $this->config->item('role:manager') => FALSE,
            $this->config->item('role:reader') => FALSE
        );

        $this->data['userIsAllowed'] = TRUE;

        $this->load->model('Storage_model');
        $this->load->model('User_model');
        $this->load->model('Tier_model');

        $this->load->library('module', array(__DIR__));
        $this->load->library('pathlibrary');

        // check rights
        $method = $this->router->fetch_method();
        $userType = $this->User_model->getType();
        if ($userType != 'rodsadmin' && $method != 'not_allowed') {
            return redirect('statistics/not_allowed');
        }
    }

    public function index()
    {
        $this->load->view('common-start', array(
            'styleIncludes' => array(
                'lib/font-awesome/css/font-awesome.css',
                'css/statistics.css',
            ),
            'scriptIncludes' => array(
                'js/statistics.js'
            ),
            'activeModule'   => $this->module->name(),
            'user' => array(
                'username' => $this->rodsuser->getUsername(),
            ),
        ));

        $this->data['resources'] = $this->Storage_model->getResources();

        // Storage table
        $this->load->helper('bytes');
        $storageData = $this->Storage_model->getMonthlyCategoryStorage();
        $storageTableData = array('data' => $storageData['*result']);
        $storageTable = $this->load->view('storage_table', $storageTableData, true);
        $this->data['storageTable'] = $storageTable;

        $this->load->view('start', $this->data);
        $this->load->view('common-end');
    }

    public function resource_details()
    {
        $rodsaccount = $this->rodsuser->getRodsAccount();
        $pathStart = $this->pathlibrary->getPathStart($this->config);
        $resourceName = $this->input->get('resource');

        $information = $this->Storage_model->getResource($resourceName);

        $viewData = array('name' => $information['resourceName'], 'tier' => $information['resourceTier']);
        $html = $this->load->view('detail', $viewData, true);

        echo json_encode(array('status' => 'success', 'html' => $html));
    }

    public function get_tiers()
    {
        $tiers = $this->Tier_model->listTiers();
        echo json_encode($tiers);
    }

    public function edit_tier()
    {
        $resource = $this->input->post('resource');
        $value = $this->input->post('value');

        $result = $this->Storage_model->setResourceTier($resource, $value);
        echo json_encode(array('status' => $result));
    }

    public function not_allowed()
    {
        $this->load->view('common-start', array(
            'styleIncludes' => array(
                'lib/font-awesome/css/font-awesome.css',
                'css/statistics.css',
            ),
            'scriptIncludes' => array(
                'js/statistics.js'
            ),
            'activeModule'   => $this->module->name(),
            'user' => array(
                'username' => $this->rodsuser->getUsername(),
            ),
        ));

        $this->load->view('not_allowed', $this->data);
        $this->load->view('common-end');
    }

}