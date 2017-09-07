<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Storage_model extends CI_Model {

    var $CI = NULL;

    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

   function getResources()
   {
       $output = array();

       $outputParams = array('*data', '*status', '*statusInfo');
       $inputParams = array();

       $this->CI->load->library('irodsrule');
       $rule = $this->irodsrule->make('uuFrontEndListResourcesAndStatisticData', $inputParams, $outputParams);
       $result = $rule->execute();
       $data = $result['*data'];

       if (count($data)) {
           foreach ($data as $resource) {
               $output[] = array('name' => $resource['resourceName'], 'id' => $resource['resourceId'], 'tier' => $resource['org_storage_tier']);
           }
       }

       return $output;
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

  function getMonthlyCategoryStorage()
  {
      $inputParams = array();
      $outputParams = array('*result', '*status', '*statusInfo');

      $this->CI->load->library('irodsrule');
      $rule = $this->irodsrule->make('uuGetMonthlyCategoryStorageOverview', $inputParams, $outputParams);

      $result = $rule->execute();
      return $result;
  }

    function getMonthlyCategoryStorageDatamanager()
    {
        $inputParams = array();
        $outputParams = array('*result', '*status', '*statusInfo');

        $this->CI->load->library('irodsrule');
        $rule = $this->irodsrule->make('uuGetMonthlyCategoryStorageOverviewDatamanager', $inputParams, $outputParams);

        $result = $rule->execute();
        return $result;
    }


    // Get list of all groups a user is entitled to
    function getGroupsOfCurrentUser($iRodsAccount)
    {
        return array('research-initial', );
    }

    // Per tier, per month get a full twelve months of storage data for the group
    // taken from last month up until 12 months back
    function getFullYearDataForGroupPerTierPerMonth($iRodsAccount, $groupName)
    {
        $tierData1 = array(
            '08' => 10.1,
            '07' => 9.1,
            '06' => 9.0,
            '05' => 8.5,
            '04' => 8.0,
            '03' => 10.1,
            '02' => 20.0,
            '01' => 1.0,
            '12' => 7.6,
            '11' => 6.6,
            '10' => 4.0,
            '09' => 3.2,
        );
        $tierData2 = array(
            '08' => 30.1,
            '07' => 20.1,
            '06' => 19.0,
            '05' => 13.5,
            '04' => 8.0,
            '03' => 0.1,
            '02' => 0.0,
            '01' => 0.0,
            '12' => 0.0,
            '11' => 0.0,
            '10' => 0.0,
            '09' => 0.0,
        );

        return array(
            'tier 1' => $tierData1,
            'tier 2' => $tierData2
        );
    }
}

