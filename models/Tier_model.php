<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Tier_model extends CI_Model {

    var $CI = NULL;

    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function listTiers()
    {
        $inputParams = array();
        $outputParams = array('*data', '*status', '*statusInfo');
        $this->CI->load->library('irodsrule');
        $rule = $this->irodsrule->make('uuFrontEndListResourceTiers', $inputParams, $outputParams);
        $result = $rule->execute();
        $data = $result['*data'];

        return $data;
    }
}
