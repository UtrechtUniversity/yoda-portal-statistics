<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class User_model extends CI_Model {

    var $CI = NULL;

    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getType()
    {
        $account = $this->CI->rodsuser->getRodsAccount();
        $inputParams = array('*user' => $account->user);
        $outputParams = array('*userType');

        $this->CI->load->library('irodsrule');
        $rule = $this->irodsrule->make('uuGetUserType', $inputParams, $outputParams);
        $result = $rule->execute();

        return $result['*userType'];
    }
}

