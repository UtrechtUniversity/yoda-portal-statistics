<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Yoda Portal Module Statistics configuration {{{

// No module-specific config yet.
$config = array();

$config['chartShowStorage'] = 'TB'; // Show chart storage: TB (terabyte) or B (bytes)

if (file_exists(dirname(__FILE__) . '/config_local.php'))
    include(    dirname(__FILE__) . '/config_local.php');

// }}}