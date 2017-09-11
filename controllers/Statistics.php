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
        $this->load->library('pathlibrary');

        // check rights
        /*
        $method = $this->router->fetch_method();
        $userType = $this->User_model->getType();
        $isDatamanager = $this->User_model->isDatamanager();
        print_r($userType);
        exit;

        if (($userType != 'rodsadmin' && $isDatamanager != 'yes') && $method != 'not_allowed') {
            return redirect('statistics/not_allowed');
        }
        */
    }

    public function index()
    {
        $userType = $this->User_model->getType();
        $isDatamanager = $this->User_model->isDatamanager();
        $isRodsAdmin = 'no';
        $isResearcher = 'no';
        if ($userType == 'rodsadmin') {
            $isRodsAdmin = 'yes';
            $isResearcher = 'yes';
        } else if ($userType == 'rodsuser') {
            $isResearcher = 'yes';
        }

        $storageTableAdmin = false;
        $storageTableDatamanager = false;
        $resources = false;

        // Storage table for rods admin
        $this->load->helper('bytes');
        if ($isRodsAdmin == 'yes') {
            $resources = $this->Storage_model->getResources();
            $storageData = $this->Storage_model->getMonthlyCategoryStorage();
            $storageTableData = array('data' => $storageData['*result']);
            $storageTable = $this->load->view('storage_table', $storageTableData, true);
            $storageTableAdmin = $storageTable;
        }

        // Storage table for datamanager
        if ($isDatamanager == 'yes') {
            $storageData = $this->Storage_model->getMonthlyCategoryStorageDatamanager();
            $storageTableData = array('data' => $storageData['*result']);
            $storageTable = $this->load->view('storage_table', $storageTableData, true);
            $storageTableDatamanager = $storageTable;
        }

        // Researcher - get group data.
        $groups = array();
        if ($isResearcher == 'yes') {
            $result = $this->Storage_model->getGroupsOfCurrentUser();
            $groups = $result['*data'];
        }


        $viewParams = array(
            'styleIncludes' => array('css/statistics.css'),
            'scriptIncludes' => array('js/statistics.js', 'lib/chartjs/chart.min.js'),
            'activeModule'   => 'statistics',
            'isDatamanager'  => $isDatamanager,
            'isRodsAdmin'  => $isRodsAdmin,
            'isResearcher' => $isResearcher,
            'storageTableAdmin' => $storageTableAdmin,
            'storageTableDatamanager' => $storageTableDatamanager,
            'resources' => $resources,
            'groups' => $groups,
        );

        loadView('start', $viewParams);
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

    public function group_details()
    {
        $groupName = $this->input->get('group');
        $storageData = $this->Storage_model->getFullYearDataForGroupPerTierPerMonth($groupName);
        $viewData = array('name' => $groupName, 'storageData' => $storageData['*data']);
        $html = $this->load->view('group_details', $viewData, true);

        echo json_encode(array('status' => 'success', 'html' => $html, 'storageData' => $storageData['*data']));
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

    /*
    public function not_allowed()
    {
        $viewParams = array(
            'styleIncludes' => array('css/statistics.css'),
            'scriptIncludes' => array('js/statistics.js'),
            'activeModule'   => 'statistics',
        );

        loadView('not_allowed', $viewParams);
    }
    */

}