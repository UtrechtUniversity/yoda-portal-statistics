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

        $this->config->load('config');

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

        $groups = array();  // used for both datamanager as well as researchers
        // Storage table for datamanager
        if ($isDatamanager == 'yes') {
            $storageData = $this->Storage_model->getMonthlyCategoryStorageDatamanager();
            $storageTableData = array('data' => $storageData['*result']);
            $storageTable = $this->load->view('storage_table', $storageTableData, true);
            $storageTableDatamanager = $storageTable;

            $result = $this->Storage_model->getGroupsOfCurrentDatamanager();
            $groups = $result['*data'];
        }
        else {
            // Researcher - get group data.
            if ($isResearcher == 'yes') {
                $result = $this->Storage_model->getGroupsOfCurrentUser();
                $groups = $result['*data'];
            }
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

        $output = array('status' => 'success', 'html' => $html);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    public function group_details()
    {
        $this->config->load('config');
        $chartShowStorage = $this->config->item('chartShowStorage');
        $showStorage = 'Bytes';
        if ($chartShowStorage == 'TB') {
            $showStorage = 'Terabytes';
        }

        $groupName = $this->input->get('group');
        $storageData = $this->Storage_model->getFullYearDataForGroupPerTierPerMonth($groupName);
        $viewData = array('name' => $groupName, 'storageData' => $storageData['*data'], 'showStorage' => $showStorage);
        $html = $this->load->view('group_details', $viewData, true);
        $output = array('status' => 'success',
	                'html' => $html,
			'storageData' => $storageData['*data']);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    public function get_tiers()
    {
        $tiers = $this->Tier_model->listTiers();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($tiers));
    }

    public function edit_tier()
    {
        $resource = $this->input->post('resource');
        $value = $this->input->post('value');

        $result = $this->Storage_model->setResourceTier($resource, $value);
        $output = array('status' => $result);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    // Export of storage information
    // Differentiation for rodsadmin and datamanager.
    public function export()
    {
        $delimiter = ';';
        $zone = $this->config->item('rodsServerZone');

        $userType = $this->User_model->getType();
        $isDatamanager = $this->User_model->isDatamanager();

        if ($userType == 'rodsadmin' || $isDatamanager == 'yes') {
            // create a file pointer connected to the output stream
            $output = fopen('php://output', 'w');

            // Start output buffering.
            ob_clean(); // clear the output buffer as now config_local introduces extra line at the moment
            ob_start();

            // Output headers so that the file is downloaded rather than displayed.
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . date('Y-m-d') . ' - ' . $zone . '.csv"');

            // Create output header depending on role
            if ($isDatamanager == 'yes' ) {
                // CSV heading for datamanager data
                $row = array('category name', 'subcategory', 'groupname', 'tier');
                $curMonth = intval(date('m'));
                $months = array('January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December');

                // Add month names in proper order
                for ($i=11; $i>=0; $i--) {
                    $month = ((($curMonth - $i)<=0) ?  ($curMonth-$i + 12): ($curMonth-$i)  );
                    $row[] = $months[$month-1];
                }

                fputcsv($output, $row, $delimiter);

                $storageData = $this->Storage_model->getExportDMCategoryStorageFullYear();
                // Process the storage data
                // COnvert to array in which can be easlily indexed on month
                $totalData = array();
                $index = 0;
                foreach ($storageData['*result'] as $row) {
                    $category = $row['category'];
                    $groupName = $row['groupname'];
                    $subcategory = $row['subcategory'];  // is not a distinguising item but descriptive - add to groupname
                    $tier = $row['tier'];
                    $month = $row['month'];  // 01-12
                    $storage = $row['storage'];
                    // subcat && groupname can be added together for now to simplify matters here
                    $totalData[$category][$subcategory . '*' . $groupName][$tier][$month] = $storage;
                    $index++;
                }

                // now aggregate where all knoon months for a category/group/tier are assigned to same row.
                foreach ($totalData as $category => $subCatGroups) {
                    foreach ($subCatGroups as $subCatGroup => $tiers) {
                        foreach ($tiers as $tier => $monthStorageData) {
                            $temp = explode('*', $subCatGroup);
                            $subCat = $temp[0];
                            $groupName = $temp[1];
                            $row = array($category, $subCat, $groupName, $tier);

                            // Add month storage in proper order
                            for ($i=11; $i>=0; $i--) {
                                $month = ((($curMonth - $i)<=0) ?  ($curMonth-$i + 12): ($curMonth-$i)  );
                                $row[] = (isset($monthStorageData[$month])? $monthStorageData[$month] : '0' );
                            }

                            fputcsv($output, $row, $delimiter);
                        }
                    }
                }
            }
            else { // Rodsamin
                $row = array('instance name (zone)', 'category name', 'tier', 'amount of storage in use in bytes');
                fputcsv($output, $row, $delimiter);

                $storageData = $this->Storage_model->getMonthlyCategoryStorage();

                foreach ($storageData['*result'] as $row) {
                    $row = array($zone, $row['category'], $row['tier'], $row['storage']);
                    fputcsv($output, $row, $delimiter);
                }
            }

            fclose($output);
            // Send the output buffer.
            ob_flush();
        }
    }
}