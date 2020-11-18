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

        $this->load->library('pathlibrary');
        $this->load->library('api');
    }

    /**
     * Inefficient but "temporary", pending refactor.
     */
    private function obj_to_array($o)
    {
        return json_decode(json_encode($o), true);
    }

    public function index()
    {
        $userType = $this->api->call('resource_user_get_type')->data;
        $isDatamanager = $this->api->call('resource_user_is_datamanager')->data;

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
            $result = $this->obj_to_array($this->api->call('resource_resource_and_tier_data'));
            $resources = $result['data'];

            $result = $this->obj_to_array($this->api->call('resource_monthly_stats'));
            $storageTableData = array('data' => $result['data']);

            $storageTable = $this->load->view('storage_table', $storageTableData, true);
            $storageTableAdmin = $storageTable;
        }

        $groups = array();  // used for both datamanager as well as researchers
        // Storage table for datamanager
        if ($isDatamanager == 'yes') {
            $result = $this->obj_to_array($this->api->call('resource_monthly_stats_dm'));
            $storageTableData = array('data' => $result['data']);

            $storageTable = $this->load->view('storage_table', $storageTableData, true);
            $storageTableDatamanager = $storageTable;

            $result = $this->obj_to_array($this->api->call('resource_groups_dm'));
            $groups = $result['data'];
        }
        else {
            // Researcher - get group data.
            if ($isResearcher == 'yes') {
                // Alleen research groups!!
                $groups = $this->api->call('resource_user_research_groups')->data;
            }
        }

        $viewParams = array(
            'styleIncludes' => array('css/statistics.css', 'lib/select2/css/select2.min.css'),
            'scriptIncludes' => array('js/statistics.js', 'lib/chartjs/chart.min.js', 'lib/select2/js/select2.full.min.js'),
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

        $result = $this->api->call('resource_tier', ['res_name' => $resourceName]);
        // FIXME: Keep assuming success for now.
        $html = $this->load->view('detail', ['name' => $resourceName, 'tier' => $result->data], true);
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
        $storageData = $this->_getFullYearDataForGroupPerTierPerMonth($groupName);

        $viewData = array('name' => $groupName, 'storageData' => $storageData['data'], 'showStorage' => $showStorage);
        $html = $this->load->view('group_details', $viewData, true);
        $output = array('status' => 'success',
	                'html' => $html,
			'storageData' => $storageData['data']);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    public function get_tiers()
    {
        $tiers = $this->api->call('resource_get_tiers')->data;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($tiers));
    }

//    public function edit_tier()
//    {
//        $resource = $this->input->post('resource');
//        $value = $this->input->post('value');
//
//        $status = $this->api->call('resource_save_tier', ['resource_name'=> $resource, 'tier_name'=> $value])->status;
//        $output = array('status' => $status);
//
//        $this->output
//            ->set_content_type('application/json')
//            ->set_output(json_encode($output));
//    }

    // Export of storage information
    // Differentiation for rodsadmin and datamanager.
    public function export()
    {
        $delimiter = ';';
        $zone = $this->config->item('rodsServerZone');

//        $userType = $this->User_model->getType();
        $userType = $this->api->call('resource_user_get_type')->data;

        //$isDatamanager = $this->User_model->isDatamanager();
        $isDatamanager = $this->api->call('resource_user_is_datamanager')->data;

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

                $result = $this->obj_to_array($this->api->call('resource_monthly_category_stats_export_dm'));

                // Process the storage data
                // COnvert to array in which can be easlily indexed on month
                $totalData = array();
                $index = 0;
                foreach ($result['data'] as $row) {
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

                $result = $this->obj_to_array($this->api->call('resource_monthly_stats'));

                foreach ($result['data'] as $row) {
                    $row = array($zone, $row['category'], $row['tier'], $row['storage']);
                    fputcsv($output, $row, $delimiter);
                }
            }

            fclose($output);
            // Send the output buffer.
            ob_flush();
        }
    }

    // Per tier, per month get a full twelve months of storage data for the group
    // taken from last month up until 12 months back
    private function _getFullYearDataForGroupPerTierPerMonth($groupName)
    {
        $currentMonth = date('m');
        $result = $this->obj_to_array($this->api->call('resource_full_year_group_data', ['group_name'=>$groupName, 'current_month'=>intval($currentMonth)]));

        if ($result['status'] == 'ok') {
            $tiers = array(); // to build seperated lists with tiers as a basis
            $receivedData = array();
            $fullYearData = array();
            $totalStorage = 0;

            if (is_array($result['data'])) {
                //print_r($result['*data']);exit;

                foreach($result['data'] as $data) {
                    foreach($data as $key=>$storage) {
                        // key contains month & tiername -> decipher
                        // month=12-tier=blabla
                        $parts = explode('-tier=', $key);
                        $monthParts = explode('=', $parts[0]);
                        $month = $monthParts[1];
                        $tier = $parts[1];

                        // Build a list of tiers that come by in statistics
                        if(!in_array($tier, $tiers)) {
                            $tiers[] = $tier;
                        }

                        $this->config->load('config');
                        $this->load->helper('bytes');
                        $chartShowStorage = $this->config->item('chartShowStorage');
                        if ($chartShowStorage == 'TB') {
                            $storage = roundUpBytes(bytesToTerabytes((int) $storage), 1);
                        }

                        $receivedData[$tier][$month] = $storage;
                        $totalStorage += $storage;
                    }
                }
            }

            // Build an array with all months present and separated by tiers as
            // Step back in time
            foreach ($tiers as $tier){
                for ($i=0; $i<12; $i++) {
                    $storageMonth = $currentMonth - $i;
                    if($storageMonth<1) {
                        $storageMonth += 12;
                    }
                    $fullYearData[$tier][$storageMonth] = isset($receivedData[$tier][$storageMonth]) ? $receivedData[$tier][$storageMonth] : 0;
                }
            }

            // supporting info for the frontend
            $monthsOrder = array();
            for ($i=0; $i<12; $i++) {
                $storageMonth = $currentMonth - $i;
                $monthsOrder[11-$i] = ( ($storageMonth)<1?($storageMonth+12):$storageMonth  ); // reverse the order of months
            }
            return array('status' => $result['status'],
                    'status_info' => $result['status_info'],
                    'data' => array( 'tiers' => $fullYearData,
                    'months' => $monthsOrder,
                    'totalStorage' => $totalStorage
                )
            );
        }
        return $result;
    }
}
