<?php
//********************************************************************************


function Header_show()
{
  
  global $tpl;
  // Set URLs
  $fldField3 = "Default.php";
  $fldField6 = "Registration.php";
  $fldField1 = "Events.php";
  $fldField4 = "Links.php";
  $fldField5 = "Officers.php";
  $fldField8 = "Members.php";
  $fldField7 = "Account.php";
  $fldField9 = "Login.php";
  $fldField2 = "AdminMenu.php";
  // Show fields
  $tpl->set_var("Field3", $fldField3);
  $tpl->set_var("Field6", $fldField6);
  $tpl->set_var("Field1", $fldField1);
  $tpl->set_var("Field4", $fldField4);
  $tpl->set_var("Field5", $fldField5);
  $tpl->set_var("Field8", $fldField8);
  $tpl->set_var("Field7", $fldField7);
  $tpl->set_var("Field9", $fldField9);
  $tpl->set_var("Field2", $fldField2);
  $tpl->parse("FormHeader", false);
}

?>