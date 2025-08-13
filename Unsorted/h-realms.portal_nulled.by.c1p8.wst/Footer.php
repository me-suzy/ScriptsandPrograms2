<?php
//********************************************************************************


function Footer_show()
{
  
  global $tpl;
  // Set URLs
  $fldField3 = "Default.php";
  $fldField2 = "Events.php";
  $fldField1 = "Links.php";
  $fldField4 = "Officers.php";
  $fldField6 = "Members.php";
  $fldField5 = "AdminMenu.php";
  // Show fields
  $tpl->set_var("Field3", $fldField3);
  $tpl->set_var("Field2", $fldField2);
  $tpl->set_var("Field1", $fldField1);
  $tpl->set_var("Field4", $fldField4);
  $tpl->set_var("Field6", $fldField6);
  $tpl->set_var("Field5", $fldField5);
  $tpl->parse("FormFooter", false);
}

?>