<?php

$L = array(

//----------------------------
// pM Imoprt
//----------------------------

"pmachine_import_utitity" =>
"pMachine Import Utility",

"pmachine_import_welcome" =>
"This utility enables you to import pMachine data into ExpressionEngine",

"pmachine_import_disclaimer" =>
"If you are not using pMachine you will not need this utility.",

"pmachine_import_information" =>
"Import Guide",

"pmachine_import_removal" =>
"Note: Do not use this utility if you are not currently using pMachine.",

"configuration_blurb" =>
"In order to use this utility, you must first submit your pMachine database information.",

"configuration_blurb_cont" =>
"You will find this information in your pMachine config.php file.",

"database_info" =>
"Database Info",

"sql_server" =>
"MySQL Server Address",

"sql_username" =>
"MySQL Username",

"sql_password" =>
"MySQL Password",

"sql_database" =>
"MySQL Database",

"sql_prefix" =>
"Table Prefix",

"leave_prefix_blank" =>
"Leave blank unless you are using a custom prefix",

"configure" =>
"Configure",

"empty_field_warning" =>
"You left some fields empty",

"no_database_connection" =>
"Unable to establish a database connection with the settings you specified",

"table_name" =>
"Table Name",

"table_rows" =>
"Table rows",

"table_action" =>
"Action",

"table_status" =>
"Status",

"completed" =>
"Completed",

"no_rows_exist" =>
"No import needed",

"pending" =>
"Ready to import",

"pending_stats" =>
"Ready to process",

"import_now" =>
"Import Data",

"recalculate" =>
"Process data",

"step_one" =>
"Step One",

"step_two" =>
"Step Two",

"step_three" =>
"Step Three",

"step_four" =>
"Step Four",

"step_five" =>
"Step Five",

"import_options" =>
"Import Options",

"import_members" =>
"Import Members",

"import_weblog_entries" =>
"Import Weblog Entries",

"recalculate_statistics" =>
"Recount Statistics",

"click_to_reset_statistics" =>
"Click here to update the statistics",

"reset_statistics_info" =>
"You will be transfered to the recount utility.  When you are finished, return to this page to perform step five",

"members" =>
"Members",

"member_stats" =>
"Member stats",

"weblog_stats" =>
"Weblog stats",

"configure" =>
"Configure",

"total_members" =>
"Total number of members in your pMachine database:",

"select_your_account" =>
"Select the pMachine account that belongs to you",

"ignore_instructions" =>
"Since you already have an ExpressionEngine membership account, it doesn\'t make sense to create another one based on your pMachine information.  Doing so will give you duplicate accounts.  If you select your existing pMachine account, your weblog entries will instead be assigned to your current ExpressionEngine account",

"i_have_no_account" =>
"I do not have a pMachine account",

"members_per_cycle" =>
"Number of members per cycle",

"cycle_instructions" =>
"In order to prevent timeout problems we will import the pMachine data in limited batches.  Please indicate the number of records per batch you would like to import.  1000 is a safe number for most servers.  If you have a high performance, or dedicated server you can import several thousand at a time.",

"blog_cycle_instructions" =>
"The weblog import routine is processor intensive, particularly if your entries have many comments and trackbacks. In order to prevent timeout problems we will import the pMachine weblog entries in limited batches.  100 is a safe number for most servers.  If you have a high performance, or dedicated server, or if your entries do not contain many comments, you can import a higher number.",

"save_settings" =>
"Save Settings",

"member_import_complete" =>
"The member import routine has been completed.",

"return_to_overview" =>
"Return to main import page",

"start_member_import" =>
"Ready to begin",

"return_to_overview" =>
"Start member import routine",

"members_remaining" =>
"Total members remaining to import:",

"click_to_import_members" =>
"Import members %x through %y",

"no_table_rows" =>
"No rows exist",

"default_member_group" =>
"Member group assignment",

"member_group_instructions" =>
"Your imported members must be assigned to a member group.  Please choose which group you would like your members to be assigned to.",

"members_imported" =>
"All members have been imported",

"return_to_main_menu" =>
"Return to Main Menu",

"import_weblog_entries" =>
"Import Weblog Entries",

"weblog_name" =>
"Weblog name:",

"total_weblog_entries" =>
"Total number of weblog entries in this weblog:",

"select_destination_blog" =>
"Select the weblog destination",

"destination_instructions" =>
"Please choose the ExpressionEngine weblog where you would like the pMachine entries imported into.",

"destination_note" =>
"Note: If a weblog doesn't exist you will have to go into the ADMIN page and create it first.",

"entries_per_cycle" =>
"Number of weblog entries per cycle",

"select_destination_fields" =>
"Select the destination fields",

"field_destination_instructions" =>
"Please select each ExpressionEngine field where you would like your pMachine fields to be imported into.",

"field_destination_instructions_two" =>
"Choose \"Do not import\" to omit any field from being imported.",

"fields_not_unique_warning" =>
"You can not assign more than one pMachine field into the same ExpressionEngine field",

"no_fields_assigned" =>
"The weblog you have chosen does not have a field group assigned to it.  Please go to the ADMIN page and assign a field group to this weblog.",

"no_textarea_fields" =>
"The field group assigned to the weblog you have chosen does not contain any textare fields",

"pmachine_field" =>
"pMachine Field",

"ee_field" =>
"ExpressionEngine Field",

"none" =>
"Do not import",

"blurb" =>
"Blurb",

"body" =>
"Body",

"more" =>
"More",

"custom1" =>
"Custom1",

"custom2" =>
"Custom2",

"custom3" =>
"Custom3",

"you_must_select_fields" =>
"You did not select any field assignments",

"entries_remaining" =>
"Entries Remaining:",

"entries_imported" =>
"Weblog entries have been imported",

"click_to_import_entries" =>
"Import weblog entries %x through %y",

"batch_complete" =>
"Batch Completed...",

"select_upload_blog" =>
"Select the image upload destination",

"upload_instructions" =>
"If your pMachine weblog entries contain images, we need to remap the current location to the new location.",

"upload_note" =>
"Note:  This import utility will not move your existing images.  You will need to manually move them to your chosen destination.",

"note_regarding_categories" =>
"Regarding Weblog Categories",

"category_note" =>
"If your pMachine weblog entries are assigned to categories, you must have identical categories available in ExpressionEngine.  During the import process, this script will attempt to match each pMachine category with the corresponding ExpressionEngine category.  If it can't make a match, the category will be ignored for that entry.",

"import_mailinglist" =>
"Import Mailing List",

"total_emails" =>
"Total Number of Emails:",

"emails_remaining" =>
"Total Emails Remaining:",

"emails_per_cycle" =>
"Number of Email Addresses Per Cycle",

"email_instructions" =>
"In order to prevent timeout problems we will import the email addresses in limited batches.  This routine is not processor intensive, so 5000 emails per batch is a good number for an average server. Please indicate the number of records per batch you would like to import. ",

"mailinglist" =>
"Mailing List",

"mailinglist_imported" =>
"The Mailing List has been imported",

"click_to_import_mailinglist" =>
"Import emails %x through %y",


"clear_preferences" =>
"Clear Preferences",

"click_to_clear_prefs" =>
"Click here to clear the preferences",

"clear_preferences_info" =>
"During this import process we saved all your preferences to your main config file.  Since you are finished importing, it is recommended that you clear this data.",

"you_are_done_importing" =>
"You have successfully completed the import process!",


// END
''=>''
);
?>