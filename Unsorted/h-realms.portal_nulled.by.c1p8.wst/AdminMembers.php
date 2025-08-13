<?php
/*********************************************************************************
 *       Filename: AdminMembers.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "AdminMembers.php";



check_security(3);

$tpl = new Template($app_path);
$tpl->load_file("AdminMembers.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);

Header_show();
Footer_show();
Search_show();
members_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************


function Search_show()
{
  global $db;
  global $tpl;
  
  $tpl->set_var("ActionPage", "AdminMembers.php");
	
  // Set variables with search parameters
  $fldmember_login = strip(get_param("member_login"));
  $fldfirst_name = strip(get_param("first_name"));
  $fldlast_name = strip(get_param("last_name"));
  $fldsecurity_level_id = strip(get_param("security_level_id"));
    // Show fields
    $tpl->set_var("member_login", tohtml($fldmember_login));
    $tpl->set_var("first_name", tohtml($fldfirst_name));
    $tpl->set_var("last_name", tohtml($fldlast_name));
    $tpl->set_var("LBsecurity_level_id", "");
    $LOV = split(";", ";;0;New;1;Member;3;Admin");
  
    if(sizeof($LOV)%2 != 0) 
      $array_length = sizeof($LOV) - 1;
    else
      $array_length = sizeof($LOV);
    reset($LOV);
    for($i = 0; $i < $array_length; $i = $i + 2)
    {
      $tpl->set_var("ID", $LOV[$i]);
      $tpl->set_var("Value", $LOV[$i + 1]);
      if($LOV[$i] == $fldsecurity_level_id) 
        $tpl->set_var("Selected", "SELECTED");
      else
        $tpl->set_var("Selected", "");
      $tpl->parse("LBsecurity_level_id", true);
    }
  $tpl->parse("FormSearch", false);
}



function members_show()
{

  
  global $tpl;
  global $db;
  global $smembersErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $HasParam = false;

  
  $tpl->set_var("TransitParams", "first_name=" . tourl(strip(get_param("first_name"))) . "&last_name=" . tourl(strip(get_param("last_name"))) . "&member_login=" . tourl(strip(get_param("member_login"))) . "&security_level_id=" . tourl(strip(get_param("security_level_id"))) . "&");
  $tpl->set_var("FormParams", "first_name=" . tourl(strip(get_param("first_name"))) . "&last_name=" . tourl(strip(get_param("last_name"))) . "&member_login=" . tourl(strip(get_param("member_login"))) . "&security_level_id=" . tourl(strip(get_param("security_level_id"))) . "&");
  // Build WHERE statement
  
  $pfirst_name = get_param("first_name");
  if(strlen($pfirst_name)) 
  {
    $HasParam = true;
    $sWhere .= "m.first_name like " . tosql("%".$pfirst_name ."%", "Text");
  }
  $plast_name = get_param("last_name");
  if(strlen($plast_name)) 
  {
    if ($sWhere != "") $sWhere .= " and ";
    $HasParam = true;
    $sWhere .= "m.last_name like " . tosql("%".$plast_name ."%", "Text");
  }
  $pmember_login = get_param("member_login");
  if(strlen($pmember_login)) 
  {
    if ($sWhere != "") $sWhere .= " and ";
    $HasParam = true;
    $sWhere .= "m.member_login like " . tosql("%".$pmember_login ."%", "Text");
  }
  $psecurity_level_id = get_param("security_level_id");
  if(is_number($psecurity_level_id) && strlen($psecurity_level_id))
    $psecurity_level_id = round($psecurity_level_id);
  else 
    $psecurity_level_id = "";
  if(strlen($psecurity_level_id)) 
  {
    if ($sWhere != "") $sWhere .= " and ";
    $HasParam = true;
    $sWhere .= "m.security_level_id=" . $psecurity_level_id;
  }
  if($HasParam)
    $sWhere = " WHERE (" . $sWhere . ")";

  $sDirection = "";
  $sSortParams = "";
  
  // Build ORDER statement
  $sOrder = " order by m.member_login Asc";
  $iSort = get_param("Formmembers_Sorting");
  $iSorted = get_param("Formmembers_Sorted");
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
      $sSortParams = "Formmembers_Sorting=" . $iSort . "&Formmembers_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "Formmembers_Sorting=" . $iSort . "&Formmembers_Sorted=" . "&";
    }
    
    if ($iSort == 1) $sOrder = " order by m.member_login" . $sDirection;
    if ($iSort == 2) $sOrder = " order by m.first_name" . $sDirection;
    if ($iSort == 3) $sOrder = " order by m.last_name" . $sDirection;
    if ($iSort == 4) $sOrder = " order by m.security_level_id" . $sDirection;
  }

  // Build full SQL statement
  
  $sSQL = "select m.first_name as m_first_name, " . 
    "m.last_name as m_last_name, " . 
    "m.member_id as m_member_id, " . 
    "m.member_login as m_member_login, " . 
    "m.security_level_id as m_security_level_id " . 
    " from members m ";
  
  $sSQL .= $sWhere . $sOrder;
  $tpl->set_var("FormAction", "EditMembers.php");
  $tpl->set_var("SortParams", $sSortParams);

  // Execute SQL statement
  $db->query($sSQL);
  
  // Select current page
  $iPage = get_param("Formmembers_Page");
  if(!strlen($iPage)) $iPage = 1;
  $RecordsPerPage = 20;
  if(($iPage - 1) * $RecordsPerPage != 0)
    $db->seek(($iPage - 1) * $RecordsPerPage);
  $iCounter = 0;
  $asecurity_level_id = split(";", "0;New;1;Member;3;Admin");

  if($db->next_record())
  {  
    // Show main table based on SQL query
    do
    {
			$fldmember_login = $db->f("m_member_login");
			$fldfirst_name = $db->f("m_first_name");
			$fldlast_name = $db->f("m_last_name");
			$fldsecurity_level_id = $db->f("m_security_level_id");
      $fldmember_id= "Edit";
      $tpl->set_var("member_id", tohtml($fldmember_id));
      $tpl->set_var("member_id_URLLink", "EditMembers.php");
      $tpl->set_var("Prm_member_id", tourl($db->f("m_member_id"))); 
      $tpl->set_var("member_login", tohtml($fldmember_login));
      $tpl->set_var("first_name", tohtml($fldfirst_name));
      $tpl->set_var("last_name", tohtml($fldlast_name));
      $fldsecurity_level_id = get_lov_value($fldsecurity_level_id, $asecurity_level_id);
      $tpl->set_var("security_level_id", tohtml($fldsecurity_level_id));
      $tpl->parse("DListmembers", true);
      $iCounter++;
    } while($iCounter < $RecordsPerPage &&$db->next_record());
  }
  else
  {
    // No Records in DB
    $tpl->set_var("DListmembers", "");
    $tpl->parse("membersNoRecords", false);
    $tpl->set_var("membersScroller", "");
    $tpl->parse("Formmembers", false);
    return;
  }
  
  // Parse scroller
  if(@$db->next_record())
  {
    if ($iPage == 1)
    {
      $tpl->set_var("membersScrollerPrevSwitch", "_");
    }
    else
    {
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("membersScrollerPrevSwitch", "");
    }
    $tpl->set_var("NextPage", ($iPage + 1));
    $tpl->set_var("membersScrollerNextSwitch", "");
    $tpl->set_var("membersCurrentPage", $iPage);
    $tpl->parse("membersScroller", false);
  }
  else
  {
    if ($iPage == 1)
    {
      $tpl->set_var("membersScroller", "");
    }
    else
    {
      $tpl->set_var("membersScrollerNextSwitch", "_");
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("membersScrollerPrevSwitch", "");
      $tpl->set_var("membersCurrentPage", $iPage);
      $tpl->parse("membersScroller", false);
    }
  }
  $tpl->set_var("membersNoRecords", "");
  $tpl->parse("Formmembers", false);
  
}

?>