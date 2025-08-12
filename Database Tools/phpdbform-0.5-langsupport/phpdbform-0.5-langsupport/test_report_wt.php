<?php
// this test should be used on a phpwebthings database
// if want to see a simple report, take a look at test_report.php
include_once("phpdbform/phpdbform_main.php");
include_once("phpdbform/phpdbform_mysql.php");
include_once("phpdbform/phpdbform_report.php");
// don't check login here.
//check_login();

$db = new phpdbform_db( $dbcfg["database"], $dbcfg["host"], $dbcfg["admuser"], $dbcfg["admpasswd"] );
$db->connect();
$stmt = "select uid, name, realname, country, sex, dateactivated, lastvisit, logins from wt_users where active='Y' order by country, logins desc, name";
$form = new phpdbform_report( $db, $stmt, "phpWebThings Users" );
//uncomment bellow to see phpdbform ruler
$form->draw_ruler = true;

$form->group_field = "country";
$form->group_title = "Country: ";

// testing events
// this will be called for every row, just after loading its values and before print it
// you can access the values and even cancel the printing returning false
function onrow( &$lform )
{
    // $lform contains the form that called this event
    // the $lform object contains the current row

    // changing a value
    if( $lform->fields["country"]->value == "Brazil" ) $lform->fields["country"]->value = "Brasil";
    
    // refusing to print a row (this is just an example, if possible implement these checks at the sql level)
    // this will change the count of the rows printed at a page
    if( $lform->fields["logins"]->value == 0 ) return false;
    return true;
}

// this will be called just before printing the field
// the value set at $field is already formatted if format was set
// to get raw data, use the onrow report event
function onsexprint( &$field )
{
    if( $field->value != "Male" && $field->value != "Female" ) $field->value = "****";
}
// uncomment bellow to see the event on action
//$form->onloadrow = "onrow";

// uncomment bellow to change the number of rows per page
//$form->rows = 100;

draw_adm_header( "Testing report", $emptyHeader );

$form->process();
// do field customizations here:
// I'm using for the widths:
//      width = total - (cellpadding*2)
$form->fields["uid"]->title = "Id";
$form->fields["uid"]->format = "0,."; // number of decimals, decimal separator, thousands separator
$form->fields["uid"]->width = 36;
$form->fields["name"]->title = "Login";
$form->fields["name"]->width = 106;
$form->fields["realname"]->title = "Name";
$form->fields["realname"]->width = 146;
$form->fields["sex"]->title = "Sex";
$form->fields["sex"]->onprint = "onsexprint";
$form->fields["sex"]->width = 36;
$form->fields["dateactivated"]->title = "Activated";
$form->fields["dateactivated"]->format = "d/m/Y H:i:s";
$form->fields["dateactivated"]->width = 126;
$form->fields["lastvisit"]->title = "Last visit";
$form->fields["lastvisit"]->format = "d/m/Y";
$form->fields["lastvisit"]->width = 66;
$form->fields["logins"]->title = "Logins";
// don't print country, as we are grouping by it
$form->fields["country"]->print = false;

$form->draw();
$form->free();

draw_adm_footer();
?>