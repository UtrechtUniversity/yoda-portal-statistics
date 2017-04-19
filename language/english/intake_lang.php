<?php
/***********************************************
			GENERAL INTAKE LANGUAGE
***********************************************/
$lang['intake_project'] = "project";
$lang['intake_dataset'] = "datapackage";
$lang['intake_directory'] = "directory";
$lang['intake_and'] 	= 'and';
$lang['intake_bytes'] = 'bytes';
$lang['intake_total'] = 'total';
$lang['intake_info_not_available'] = 'N/A';
$lang['intake_button_back'] = "back";


/***********************************************
			INTAKE MAIN VIEW
***********************************************/
$lang['intake_head_files_not_recognised'] = "Unrecognised files";
$lang['intake_files_not_recognized'] = "<p>To be able to backup or move the files listed below to the vault, please move them inside a directory which supports versioning first.";
$lang['intake_dataset_locked'] = "<p>This directory and all its contents are currently locked because a new version is being created.</p><p>The system has locked this directory to ensure no files are changed before the version is completely finished.</p>";
$lang['intake_button_create_snapshot'] = "Save as Version";
$lang['intake_button_unlock_snapshot'] = "Unlock";

/***********************************************
			INTAKE CONFIG DEFINED STRINGS
***********************************************/
$lang['intake_config_project'] = 'project';
$lang['intake_config_projects'] = 'projects';
$lang['intake_config_study'] = 'study';
$lang['intake_config_studies'] = 'studies';
$lang['intake_config_dataset'] = 'datapackage';
$lang['intake_config_datasets'] = 'datapackages';
$lang['intake_config_directories'] = 'directories';

/***********************************************
			INTAKE FILE OVERVIEW
***********************************************/
$lang['intake_tab_file_overview'] = "files";
$lang['intake_tab_details'] = "previous versions";
$lang['intake_name'] = "name";
$lang['intake_size'] = "size";
$lang['intake_files'] 		= "files";
$lang['intake_folders'] = "folders";
$lang['intake_created'] = "created";
$lang['intake_modified'] = "latest changes";
$lang['intake_snapshot_latest'] = "latest version";
$lang['intake_comment'] = "comment";
$lang['intake_n_files_in_n_dirs'] = '%1$d (in %2$d directories)';
$lang['intake_no_snapshots_text'] = "never";
$lang['intake_latest_snapshot_by'] = "<b>v%s</b> created %s by %s";
$lang['intake_rodspath'] = 'path to dataset';
$lang['intake_version_current'] = 'current version';
$lang['intake_version'] = 'version';
$lang['intake_based_on'] = 'based on';
$lang['intake_snapshot_head_summary'] = "information";
$lang['intake_snapshot_vault_path'] = "vault path";
$lang['intake_snapshot_created_at_by'] = 'Version created by user <b>%2$s</b> at <b>%1$s</b>';
$lang['intake_snapshot_depends'] = 'derived from';
$lang['intake_snapshot_depends_version_path'] = 'version <b>%1$s</b> (Vault path: %2$s)';
$lang['intake_snapshot_first_version'] = 'this version was created from scratch';


$lang['intake_head_dataset_information'] = '%1$s information';
$lang['intake_head_snapshot_history'] = "version creation history";


/***********************************************
			INTAKE META DATA
***********************************************/
$lang['intake_header_metadata'] = "meta data";
$lang['intake_metadata_key'] = "name";
$lang['intake_metadata_value'] = "value";
$lang['intake_metadata_dir_invalid'] = 'The directory <i>%1$s</i> does not seem to exist. The meta data could not be updated';
$lang['intake_metadata_no_permission'] = 'You do not have permission to update the meta data for <i>%1$s</i>';
$lang['intake_metadata_no_access'] = 'You do not have permission to view or edit metadata for this directory';
$lang['intake_metadata_update_success'] = 'Metadata saved';
$lang['intake_metadata_input_invalid'] = "Incorrect input";
$lang['intake_metadata_update_failed_general'] = "Something went wrong updating the meta data:</b></p>";
$lang['intake_metadata_update_failed_details'] = '<p>nt:Could not %1$s the values for %2$s';
$lang['intake_metadata_button_add_value'] = 'Add value';
$lang['intake_metadata_error_no_schema'] = 'There is no meta data schema defined. Please contact your system administrator';
$lang['intake_metadata_button_edit'] = 'Edit';
$lang['intake_metadata_button_cancel'] = "Cancel editting and undo changes";



/***********************************************
				ACTIONS
***********************************************/
$lang['intake_actions_snapshot_path_invalid'] = '%1$s path does not seem to be a valid directory. No version could be created.';
$lang['intake_actions_no_permission'] = 'You do not have permission to create a version for this directory.';
$lang['intake_actions_no_selected_folder'] = 'No directories where selected so no version could be created';
$lang['intake_actions_snapshots_in_progress'] = 'New versions are being created of the directory <i>%1$s</i>. This may take a while.';
$lang['intake_actions_snapshot_in_progress'] = 'A version is currently being created of the directory <i>%1$s</i>';
$lang['intake_actions_snapshot_general_error'] = "Some versions could not be prepared to be moved to the vault. Please try again later. If the problem persists, please contact your system administrator";
$lang['intake_actions_unlock_no_valid_directory'] = 'The path <i>%1$s</i> does not seem to be a valid directory. It could not be unlocked';
$lang['intake_actions_snapshot_cancelled'] = 'Directory <i>%1$s</i> unlocked. No version was created';
$lang['intake_actions_snapshot_cancel_fail'] = 'The directory <i>%1$s</s> could not be unlocked, because the system has already initiated the process of creating a version. After this process is complete, this directory will automatically be unlocked.';



/***********************************************
				DATATABLES
***********************************************/
$lang["intake_datatable_decimal"] = "";
$lang["intake_datatable_emptyStudies"] = "You are not a member of any projects";
$lang["intake_datatable_emptyFiles"] = "This directory does not contain any files";
$lang["intake_datatable_emptyFolders"] = "This directory does not contain any folders";
$lang["intake_datatable_emptyMetadata"] = "No metadata available";
$lang["intake_datatable_info"] = "Showing _START_ to _END_ of _TOTAL_ entries";
$lang["intake_datatable_infoEmpty"] = "Showing 0 to 0 of 0 entries";
$lang["intake_datatable_infoFiltered"] = "(filtered from _MAX_ total entries)";
$lang["intake_datatable_infoPostFix"] = "";
$lang["intake_datatable_thousands"] = ",";
$lang["intake_datatable_lengthMenu"] = "Show _MENU_ entries";
$lang["intake_datatable_loadingRecords"] = "Loading...";
$lang["intake_datatable_processing"] = "Processing...";
$lang["intake_datatable_search"] = "Search:";
$lang["intake_datatable_zeroRecords"] = "No matching records found";
$lang["intake_datatable_paginate_first"] = "First";
$lang["intake_datatable_paginate_last"] = "Last";
$lang["intake_datatable_paginate_next"] = "Next";
$lang["intake_datatable_paginate_previous"] = "Previous";
$lang["intake_datatable_sortAscending"] = "activate to sort column ascending";
$lang["intake_datatable_sortDescending"] = "activate to sort column descending";


if (file_exists(dirname(__FILE__) . '/intake_local_lang.php'))
	include(    dirname(__FILE__) . '/intake_local_lang.php');