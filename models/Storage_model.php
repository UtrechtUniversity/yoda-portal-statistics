<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Storage_model extends CI_Model {

    var $CI = NULL;

    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getResource($name)
    {
      $inputParams = array('*resourceName' => $name);
      $outputParams = array('*data', '*status', '*statusInfo');

      $this->CI->load->library('irodsrule');
      $rule = $this->irodsrule->make('uuFrontEndGetResourceStatisticData', $inputParams, $outputParams);
      $result = $rule->execute();
      $data = $result['*data'];

      return array('resourceName' => $data['resourceName'], 'resourceTier' => $data['org_storage_tier']);
    }

    function setResourceTier($resource, $value)
    {

      $inputParams = array('*resourceName' => $resource, '*tierName' => $value);
      $outputParams = array('*data', '*status', '*statusInfo');

      $this->CI->load->library('irodsrule');
      $rule = $this->irodsrule->make('uuFrontEndSetResourceTier', $inputParams, $outputParams);
      $result = $rule->execute();
      $status = $result['*status'];

      return $status;
    }

    // Get list of all groups a user is entitled to
    function getGroupsOfCurrentUser()
    {
        $inputParams = array();
        $outputParams = array('*data', '*status', '*statusInfo');

        $this->CI->load->library('irodsrule');
        $rule = $this->irodsrule->make('uuFrontEndGetUserGroupsForStatistics', $inputParams, $outputParams);

        $result = $rule->execute();

        if ($result['*status'] == 'Success') { // Bring list back to only research groups
            $allResearchGroups = array();
            foreach($result['*data'] as $group) {
                if (substr($group,0,strlen('research-'))=='research-') {
                    $allResearchGroups[] = $group;
                }
            }

            return array('*status' => $result['*status'],
                '*statusInfo' => $result['*statusInfo'],
                '*data' => $allResearchGroups
            );
        }

        return $result;
    }


    // Per tier, per month get a full twelve months of storage data for the group
    // taken from last month up until 12 months back
    function getFullYearDataForGroupPerTierPerMonth($groupName)
    {
        $currentMonth = date('m');
        $inputParams = array('*groupName'=>$groupName, '*currentMonth' => $currentMonth);
        $outputParams = array('*data', '*status', '*statusInfo');

        $this->CI->load->library('irodsrule');
        $rule = $this->irodsrule->make('uuFrontEndGetYearStatisticsForGroup', $inputParams, $outputParams);

        $result = $rule->execute();

        if ($result['*status'] == 'Success') {
            $tiers = array(); // to build seperated lists with tiers as a basis
            $receivedData = array();
            $fullYearData = array();
            $totalStorage = 0;

            if (is_array($result['*data'])) {
                //print_r($result['*data']);exit;

                foreach($result['*data'] as $data) {
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
            return array('*status' => $result['*status'],
                   '*statusInfo' => $result['*statusInfo'],
                    '*data' => array( 'tiers' => $fullYearData,
                    'months' => $monthsOrder,
                    'totalStorage' => $totalStorage
                )
            );
        }
        return $result;
    }
}
