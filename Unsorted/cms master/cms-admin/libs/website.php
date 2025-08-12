<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/libs/website.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
class Website {

    function update_description($frm) {
      global $Db, $Base, $Lang_website;
      $Db->query("UPDATE `cms_site` SET `description`='$frm[description]'");
      $Base->msg_js_show($Lang_website->msg_update_description_ok);
    }

    function edit_description() {
      global $CFG, $Lang_website, $Db;
      $frm = $Db->fetch_array($Db->query("SELECT * FROM `cms_site`"));
      $frm["mode"] = "update_description";
      $frm["button"] = $Lang_website->button_update;
      include("$CFG->dir_admin_templates/website-description-form.php");
    }

    function update_keywords($frm) {
      global $Db, $Base, $Lang_website;
      $Db->query("UPDATE `cms_site` SET `keywords`='$frm[keywords]'");
      $Base->msg_js_show($Lang_website->msg_update_keywords_ok);
    }

    function edit_keywords() {
      global $CFG, $Lang_website, $Db;
      $frm = $Db->fetch_array($Db->query("SELECT * FROM `cms_site`"));
      $frm["mode"] = "update_keywords";
      $frm["button"] = $Lang_website->button_update;
      include("$CFG->dir_admin_templates/website-keywords-form.php");
    }

    function update_title($frm) {
      global $Db, $Base, $Lang_website;
      $Db->query("UPDATE `cms_site` SET `title`='$frm[title]'");
      $Base->msg_js_show($Lang_website->msg_update_title_ok);
    }

    function edit_title() {
      global $CFG, $Lang_website, $Db;
      $frm = $Db->fetch_array($Db->query("SELECT * FROM `cms_site`"));
      $frm["mode"] = "update_title";
      $frm["button"] = $Lang_website->button_update;
      include("$CFG->dir_admin_templates/website-title-form.php");
    }

    function print_current_options() {
      global $CFG, $Lang_website, $Db;
      $website = $Db->fetch_array($Db->query("SELECT * FROM `cms_site`"));
      include("$CFG->dir_admin_templates/website-current-options.php");
    }

}

?>