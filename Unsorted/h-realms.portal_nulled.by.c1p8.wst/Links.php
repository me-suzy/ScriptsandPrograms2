<?php
/*********************************************************************************
 *       Filename: Links.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "Links.php";




$tpl = new Template($app_path);
$tpl->load_file("Links.html", "main");
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
  
  $tpl->set_var("ActionPage", "Links.php");
	
  // Set variables with search parameters
  $fldsearch = strip(get_param("search"));
    // Show fields
    $tpl->set_var("search", tohtml($fldsearch));
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

  
  $tpl->set_var("TransitParams", "search=" . tourl(strip(get_param("search"))) . "&");
  $tpl->set_var("FormParams", "search=" . tourl(strip(get_param("search"))) . "&");
  // Build WHERE statement
  
  $psearch = get_param("search");
  if(strlen($psearch))
  {
    $HasParam = true;
    $sWhere = "l.link_name like " . tosql("%".$psearch ."%", "Text") . " or " . "l.link_desc like " . tosql("%".$psearch ."%", "Text");
  }
  
  if($HasParam)
    $sWhere = " WHERE (approved=1) AND (" . $sWhere . ")";
  else
    $sWhere = " WHERE approved=1";
  

  $sDirection = "";
  $sSortParams = "";
  
  // Build ORDER statement
  $sOrder = " order by l.link_name Desc";
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
    
    if ($iSort == 1) $sOrder = " order by l.link_name" . $sDirection;
    if ($iSort == 2) $sOrder = " order by l.link_desc" . $sDirection;
    if ($iSort == 3) $sOrder = " order by l.address" . $sDirection;
  }

  // Build full SQL statement
  
  $sSQL = "select l.address as l_address, " . 
    "l.link_desc as l_link_desc, " . 
    "l.link_name as l_link_name, " . 
    "l.link_url as l_link_url " . 
    " from links l ";
  
  $sSQL .= $sWhere . $sOrder;
  $tpl->set_var("FormAction", "AddLink.php");
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

  if($db->next_record())
  {  
    // Show main table based on SQL query
    do
    {
			$fldlink_name = $db->f("l_link_name");
			$fldlink_desc = $db->f("l_link_desc");
			$fldaddress = $db->f("l_address");
      $tpl->set_var("link_name", tohtml($fldlink_name));
      $tpl->set_var("link_name_URLLink", $db->f("l_link_url"));
      $tpl->set_var("link_desc", tohtml($fldlink_desc));
      $tpl->set_var("address", tohtml($fldaddress));
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