<?php
/*********************************************************************************
 *       Filename: AdminLinks.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "AdminLinks.php";




$tpl = new Template($app_path);
$tpl->load_file("AdminLinks.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);

Header_show();
Footer_show();
Search_show();
Links_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************


function Search_show()
{
  global $db;
  global $tpl;
  
  $tpl->set_var("ActionPage", "AdminLinks.php");
	
  // Set variables with search parameters
  $fldlink_name = strip(get_param("link_name"));
  $fldlink_url = strip(get_param("link_url"));
  $fldapproved = strip(get_param("approved"));
    // Show fields
    $tpl->set_var("link_name", tohtml($fldlink_name));
    $tpl->set_var("link_url", tohtml($fldlink_url));
    $tpl->set_var("LBapproved", "");
    $LOV = split(";", ";;1;Yes;0;No");
  
    if(sizeof($LOV)%2 != 0) 
      $array_length = sizeof($LOV) - 1;
    else
      $array_length = sizeof($LOV);
    reset($LOV);
    for($i = 0; $i < $array_length; $i = $i + 2)
    {
      $tpl->set_var("ID", $LOV[$i]);
      $tpl->set_var("Value", $LOV[$i + 1]);
      if($LOV[$i] == $fldapproved) 
        $tpl->set_var("Selected", "SELECTED");
      else
        $tpl->set_var("Selected", "");
      $tpl->parse("LBapproved", true);
    }
  $tpl->parse("FormSearch", false);
}



function Links_show()
{

  
  global $tpl;
  global $db;
  global $sLinksErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $HasParam = false;

  
  $tpl->set_var("TransitParams", "link_name=" . tourl(strip(get_param("link_name"))) . "&link_url=" . tourl(strip(get_param("link_url"))) . "&approved=" . tourl(strip(get_param("approved"))) . "&");
  $tpl->set_var("FormParams", "link_name=" . tourl(strip(get_param("link_name"))) . "&link_url=" . tourl(strip(get_param("link_url"))) . "&approved=" . tourl(strip(get_param("approved"))) . "&");
  // Build WHERE statement
  
  $papproved = get_param("approved");
  if(is_number($papproved) && strlen($papproved))
    $papproved = round($papproved);
  else 
    $papproved = "";
  if(strlen($papproved)) 
  {
    $HasParam = true;
    $sWhere .= "l.approved=" . $papproved;
  }
  $plink_name = get_param("link_name");
  if(strlen($plink_name)) 
  {
    if ($sWhere != "") $sWhere .= " and ";
    $HasParam = true;
    $sWhere .= "l.link_name like " . tosql("%".$plink_name ."%", "Text");
  }
  $plink_url = get_param("link_url");
  if(strlen($plink_url)) 
  {
    if ($sWhere != "") $sWhere .= " and ";
    $HasParam = true;
    $sWhere .= "l.link_url like " . tosql("%".$plink_url ."%", "Text");
  }
  if($HasParam)
    $sWhere = " WHERE (" . $sWhere . ")";

  $sDirection = "";
  $sSortParams = "";
  
  // Build ORDER statement
  $sOrder = " order by l.date_added Desc";
  $iSort = get_param("FormLinks_Sorting");
  $iSorted = get_param("FormLinks_Sorted");
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
      $sSortParams = "FormLinks_Sorting=" . $iSort . "&FormLinks_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "FormLinks_Sorting=" . $iSort . "&FormLinks_Sorted=" . "&";
    }
    
    if ($iSort == 1) $sOrder = " order by l.date_added" . $sDirection;
    if ($iSort == 2) $sOrder = " order by l.link_name" . $sDirection;
    if ($iSort == 3) $sOrder = " order by l.link_url" . $sDirection;
    if ($iSort == 4) $sOrder = " order by l.approved" . $sDirection;
  }

  // Build full SQL statement
  
  $sSQL = "select l.approved as l_approved, " . 
    "l.date_added as l_date_added, " . 
    "l.link_id as l_link_id, " . 
    "l.link_name as l_link_name, " . 
    "l.link_url as l_link_url " . 
    " from links l ";
  
  $sSQL .= $sWhere . $sOrder;
  $tpl->set_var("FormAction", "EditLink.php");
  $tpl->set_var("SortParams", $sSortParams);

  // Execute SQL statement
  $db->query($sSQL);
  
  // Select current page
  $iPage = get_param("FormLinks_Page");
  if(!strlen($iPage)) $iPage = 1;
  $RecordsPerPage = 20;
  if(($iPage - 1) * $RecordsPerPage != 0)
    $db->seek(($iPage - 1) * $RecordsPerPage);
  $iCounter = 0;
  $aapproved = split(";", "0;No;1;Yes");

  if($db->next_record())
  {  
    // Show main table based on SQL query
    do
    {
			$flddate_added = $db->f("l_date_added");
			$fldlink_name = $db->f("l_link_name");
			$fldlink_url = $db->f("l_link_url");
			$fldapproved = $db->f("l_approved");
      $tpl->set_var("date_added", tohtml($flddate_added));
      $tpl->set_var("link_name", tohtml($fldlink_name));
      $tpl->set_var("link_name_URLLink", "EditLink.php");
      $tpl->set_var("Prm_link_id", tourl($db->f("l_link_id"))); 
      $tpl->set_var("link_url", tohtml($fldlink_url));
      $tpl->set_var("link_url_URLLink", $db->f("l_link_url"));
      $fldapproved = get_lov_value($fldapproved, $aapproved);
      $tpl->set_var("approved", tohtml($fldapproved));
      $tpl->parse("DListLinks", true);
      $iCounter++;
    } while($iCounter < $RecordsPerPage &&$db->next_record());
  }
  else
  {
    // No Records in DB
    $tpl->set_var("DListLinks", "");
    $tpl->parse("LinksNoRecords", false);
    $tpl->set_var("LinksScroller", "");
    $tpl->parse("FormLinks", false);
    return;
  }
  
  // Parse scroller
  if(@$db->next_record())
  {
    if ($iPage == 1)
    {
      $tpl->set_var("LinksScrollerPrevSwitch", "_");
    }
    else
    {
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("LinksScrollerPrevSwitch", "");
    }
    $tpl->set_var("NextPage", ($iPage + 1));
    $tpl->set_var("LinksScrollerNextSwitch", "");
    $tpl->set_var("LinksCurrentPage", $iPage);
    $tpl->parse("LinksScroller", false);
  }
  else
  {
    if ($iPage == 1)
    {
      $tpl->set_var("LinksScroller", "");
    }
    else
    {
      $tpl->set_var("LinksScrollerNextSwitch", "_");
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("LinksScrollerPrevSwitch", "");
      $tpl->set_var("LinksCurrentPage", $iPage);
      $tpl->parse("LinksScroller", false);
    }
  }
  $tpl->set_var("LinksNoRecords", "");
  $tpl->parse("FormLinks", false);
  
}

?>