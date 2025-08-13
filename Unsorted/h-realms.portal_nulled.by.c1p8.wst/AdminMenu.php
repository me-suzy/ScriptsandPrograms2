<?php
/*********************************************************************************
 *       Filename: AdminMenu.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "AdminMenu.php";



check_security(2);

$tpl = new Template($app_path);
$tpl->load_file("AdminMenu.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);

Header_show();
Footer_show();
AdminMenu_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************


function AdminMenu_show()
{
  
  global $tpl;
  // Set URLs
  $fldField2 = "AdminArticles.php";
  $fldField1 = "AdminEvents.php";
  $fldField3 = "AdminOfficers.php";
  $fldField4 = "AdminLinks.php";
  $fldField5 = "AdminMembers.php";
  $fldField6 = "AdminNews.php";
  // Show fields
  $tpl->set_var("Field2", $fldField2);
  $tpl->set_var("Field1", $fldField1);
  $tpl->set_var("Field3", $fldField3);
  $tpl->set_var("Field4", $fldField4);
  $tpl->set_var("Field5", $fldField5);
  $tpl->set_var("Field6", $fldField6);
  $tpl->parse("FormAdminMenu", false);
}

?>