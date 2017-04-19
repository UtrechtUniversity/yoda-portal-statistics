<?php

$lang['intake_error_no_projects'] = "<p>You are not a member of any studies. Ask the owner of a study you need access to, to add you through the group manager.";
$lang['intake_error_study_no_exist'] = 'The directory <i>%1$s</i> does not seem to exist.</p>';
$lang['intake_error_no_access'] = "You are not authorized to access the study '<i>%s</i>'";

if (file_exists(dirname(__FILE__) . '/errors_local_lang.php'))
	include(    dirname(__FILE__) . '/errors_local_lang.php');