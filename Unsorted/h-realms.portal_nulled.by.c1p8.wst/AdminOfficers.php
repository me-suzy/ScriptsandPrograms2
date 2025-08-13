<?php
/*********************************************************************************
 *       Filename: AdminOfficers.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "AdminOfficers.php";



check_security(2);

$tpl = new Template($app_path);
$tpl->load_file("AdminOfficers.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);

Header_show();
Footer_show();
Officers_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************



function Officers_show()
{

  
  global $tpl;
  global $db;
  global $sOfficersErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $HasParam = false;

  
  $tpl->set_var("TransitParams", "");
  $tpl->set_var("FormParams", "");
  // Build WHERE statement
  

  $sDirection = "";
  $sSortParams = "";
  
  // Build ORDER statement
  $sOrder = " order by o.officer_id Asc";
  $iSort = get_param("FormOfficers_Sorting");
  $iSorted = get_param("FormOfficers_Sorted");
  if(!$iSort)
  {
    $tpl->set_var("Form_Sorting", "");
  }
  else
  {
    if($iSort == $iSorted)
    {
      $tpl->set_var("Form_Sorting", "");
      $sDirection = " DESC";
      $sSortParams = "FormOfficers_Sorting=" . $iSort . "&FormOfficers_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "FormOfficers_Sorting=" . $iSort . "&FormOfficers_Sorted=" . "&";
    }
    
    if ($iSort == 1) $sOrder = " order by o.officer_name" . $sDirection;
    if ($iSort == 2) $sOrder = " order by o.officer_position" . $sDirection;
    if ($iSort == 3) $sOrder = " order by o.officer_email" . $sDirection;
  }

  // Build full SQL statement
  
  $sSQL = "select o.officer_email as o_officer_email, " . 
    "o.officer_id as o_officer_id, " . 
    "o.officer_name as o_officer_name, " . 
    "o.officer_position as o_officer_position " . 
    " from officers o ";
  
  $sSQL .= $sWhere . $sOrder;
  $tpl->set_var("FormAction", "EditOfficer.php");
  $tpl->set_var("SortParams", $sSortParams);

  // Execute SQL statement
  $db->query($sSQL);
  
  // Select current page
  $iPage = get_param("FormOfficers_Page");
  if(!strlen($iPage)) $iPage = 1;
  $RecordsPerPage = 20;
  if(($iPage - 1) * $RecordsPerPage != 0)
    $db->seek(($iPage - 1) * $RecordsPerPage);
  $iCounter = 0;

  if($db->next_record())
  {  
    // Show main table based on SQL query
    do
    {
			$fldofficer_name = $db->f("o_officer_name");
			$fldofficer_position = $db->f("o_officer_position");
			$fldofficer_email = $db->f("o_officer_email");
      $tpl->set_var("officer_name", tohtml($fldofficer_name));
      $tpl->set_var("officer_name_URLLink", "EditOfficer.php");
      $tpl->set_var("Prm_officer_id", tourl($db->f("o_officer_id"))); 
      $tpl->set_var("officer_position", tohtml($fldofficer_position));
      $tpl->set_var("officer_email", tohtml($fldofficer_email));
      $tpl->parse("DListOfficers", true);
      $iCounter++;
    } while($iCounter < $RecordsPerPage &&$db->next_record());
  }
  else
  {
    // No Records in DB
    $tpl->set_var("DListOfficers", "");
    $tpl->parse("OfficersNoRecords", false);
    $tpl->set_var("OfficersScroller", "");
    $tpl->parse("FormOfficers", false);
    return;
  }
  
  // Parse scroller
  if(@$db->next_record())
  {
    if ($iPage == 1)
    {
      $tpl->set_var("OfficersScrollerPrevSwitch", "_");
    }
    else
    {
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("OfficersScrollerPrevSwitch", "");
    }
    $tpl->set_var("NextPage", ($iPage + 1));
    $tpl->set_var("OfficersScrollerNextSwitch", "");
    $tpl->set_var("OfficersCurrentPage", $iPage);
    $tpl->parse("OfficersScroller", false);
  }
  else
  {
    if ($iPage == 1)
    {
      $tpl->set_var("OfficersScroller", "");
    }
    else
    {
      $tpl->set_var("OfficersScrollerNextSwitch", "_");
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("OfficersScrollerPrevSwitch", "");
      $tpl->set_var("OfficersCurrentPage", $iPage);
      $tpl->parse("OfficersScroller", false);
    }
  }
  $tpl->set_var("OfficersNoRecords", "");
  $tpl->parse("FormOfficers", false);
  
}

?>