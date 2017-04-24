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
               $output[] = array('name' => $resource['resourceName'], 'id' => $resource['resourceId'], 'tier' => $resource['org_storageTierName']);
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

      return array('resourceName' => $data['resourceName'], 'resourceTier' => $data['org_storageTierName']);
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
}

