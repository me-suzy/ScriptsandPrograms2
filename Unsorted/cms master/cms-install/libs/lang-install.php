<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-install/lang-install.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
class LangInstall {

    var $header_install_list = "List of installed components";
    var $header_license = "License agreement";
    var $header_options_form = "System setup";
    var $header_process_installation = "System installation";
    
    var $table_name = "Name";
    var $table_cmd = "Action";
    
    var $component_title = "CMS Master";
    var $component_description = "Content management system";

    var $cmd_install = "Install module";
    var $cmd_delete = "Delete module";
    var $cmd_cant_delete = "You cannot delete this module";
    
    var $button_accept = "Accept";
    var $button_install = "Install";
    var $button_back = "Back";
    var $button_login = "Login";
    
    var $field_db_server = "DB server";
    var $field_db_name = "DB name";
    var $field_db_user = "DB user";
    var $field_db_pass = "DB password";
    var $field_admin_pass = "Administrator password";
    var $field_admin_retype_pass = "Retype password";
    var $field_admin_email = "Administrator email";
    
    var $notes_options_form = "Place for notes";
    
    var $error_db_server_empty = "Error. Please type DB server.";
    var $error_db_name_empty = "Error. Please type DB name.";
    var $error_db_user_empty = "Error. Please type DB user.";
    var $error_db_pass_empty = "Error. Please type DB password.";
    var $error_admin_email_empty = "Error. Please type Administrator email.";
    var $error_admin_pass_empty = "Error. Please type Administrator password.";
    var $error_password_incorrect = "Error. Your password entries do not match. Please retype them.";    
    
    var $msg_check_htaccess = "Check write access to /.htaccess";
    var $msg_check_cms_config = "Check write access to /cms-config.php";
    var $msg_check_cms_images = "Check write access to folder /cms-images";
    var $msg_check_cms_files = "Check write access to folder /cms-files";
    var $msg_check_cms_pages = "Check write access to filder /cms-pages";
    var $msg_check_db = "Check database";
    
    var $str_ok = "Done";
    var $str_error = "Error";
    
    var $notes_htaccess = "No write access to /.htaccess, use FTP-client to change file access rights, or contact your system administrator.";
    var $notes_cms_config = "No write access to /cms-config.php, use FTP-client to change file access rights, or contact your system administrator.";
    var $notes_cms_images = "No write access to folder /cms-images, use FTP-client to change folder access rights, or contact your system administrator.";
    var $notes_cms_files = "No write access to folder /cms-files, use FTP-client to change folder access rights, or contact your system administrator.";
    var $notes_cms_pages = "No write access to folder /cms-pages, use FTP-client to change folder access rights, or contact your system administrator.";
    var $notes_db = "Database connection failed. Please check the information you entered: server, database name, username and password. Correct entered data, or contact your system administrator.";
}

?>