<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/libs/lang-pages.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
class LangPages {

    var $header_page_list = "List of pages";
    var $header_add_new_page = "Add new page";
    var $header_edit_page_option = "Edit page options";
    var $header_edit_page = "Edit page";
    
    var $table_page_name = "Page";
    var $table_page_cmd = "Action";
    
    var $cmd_page_edit = "Edit page";
    var $cmd_page_option = "Page options";
    var $cmd_page_del = "Delete page";
    var $cmd_page_moveup = "Move page up";
    var $cmd_page_movedown = "Move page down";

    var $menu_page_list = "List of pages";
    var $menu_add_new_page = "Add new page";
    var $menu_edit_home_page = "Edit home page";
    var $menu_edit_home_page_option = "Edit home page options";
    
    var $field_name_menu = "Menu title";
    var $field_name_title = "Window title";
    var $field_name_page = "Page title";
    var $field_name_url = "Page URL";
    var $field_name_url_external = "External URL";
    var $field_parent = "Parent page";
    var $field_redirect = "Redirect";
    var $field_is_visible = "Show in menu";
    var $field_is_url_external = "Use external link";
    var $field_website_title = "Website title";
    var $field_website_keywords = "Page keywords";
    var $field_website_description = "Page description";
    
    var $button_add = "Add page";
    var $button_update = "Update";
    var $button_save = "Save";
    
    var $str_no_redirect = "No redirect";
    
    var $error_dublicated_url = "Error. This Page URL already exists. Please choose another name.";
    var $error_name_menu_empty = "Error. Please type Menu title.";
    var $error_name_page_empty = "Error. Please type Window title.";
    var $error_name_url_empty = "Error.Please type Page URL.";
    var $error_name_url_invalid = "Error. Page URL contains invalid characters.";
    var $error_name_url_external_empty = "Error. Please type External URL.";
    var $error_redirect_invalid = "Error. Please choose another page for redirect, or choose No redirect.";
    var $error_parent_invalid = "Error. Please choose another parent page.";
    var $error_delete_page = "Error. You cannot delete this page.";    
    var $msg_no_pages = "There are no pages on the site.";
    var $msg_add_new_page_ok = "New page added successfully.";
    var $msg_move_page_up_ok = "Page moved up.";
    var $msg_move_page_down_ok = "Page moved down.";
    var $msg_update_page_options_ok = "Page options updated successfully.";
    var $msg_update_page_ok = "Page updated successfully.";
    var $msg_confirm_delete_page = "Confirm Delete page.";
    var $msg_delete_page_ok = "Page deleted successfully";    
    var $notes_add_new_page = "<b><font color=#c80000>Notes:</font></b><br>All fields with asterisk <font color=#c80000>*</font> are required. Please fill in all required fields.";
}

?>