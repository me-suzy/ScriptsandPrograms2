<?php
/*********************************************************************************
 *       Filename: Events.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "Events.php";




$tpl = new Template($app_path);
$tpl->load_file("Events.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);

Header_show();
Footer_show();
Search_show();
Events_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************


function Search_show()
{
  global $db;
  global $tpl;
  
  $tpl->set_var("ActionPage", "Events.php");
	
  // Set variables with search parameters
  $flds_event_desc = strip(get_param("s_event_desc"));
    // Show fields
    $tpl->set_var("s_event_desc", tohtml($flds_event_desc));
  $tpl->parse("FormSearch", false);
}



function Events_show()
{

  
  global $tpl;
  global $db;
  global $sEventsErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $HasParam = false;

  
  $tpl->set_var("TransitParams", "s_event_desc=" . tourl(strip(get_param("s_event_desc"))) . "&");
  $tpl->set_var("FormParams", "s_event_desc=" . tourl(strip(get_param("s_event_desc"))) . "&");
  // Build WHERE statement
  
  $ps_event_desc = get_param("s_event_desc");
  if(strlen($ps_event_desc))
  {
    $HasParam = true;
    $sWhere = "e.event_desc like " . tosql("%".$ps_event_desc ."%", "Text") . " or " . "e.event_name like " . tosql("%".$ps_event_desc ."%", "Text") . " or " . "e.presenter like " . tosql("%".$ps_event_desc ."%", "Text");
  }
  
  if($HasParam)
    $sWhere = " WHERE (" . $sWhere . ")";

  $sDirection = "";
  $sSortParams = "";
  
  // Build ORDER statement
  $sOrder = " order by e.date_start Desc";
  $iSort = get_param("FormEvents_Sorting");
  $iSorted = get_param("FormEvents_Sorted");
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
      $sSortParams = "FormEvents_Sorting=" . $iSort . "&FormEvents_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "FormEvents_Sorting=" . $iSort . "&FormEvents_Sorted=" . "&";
    }
    
    if ($iSort == 1) $sOrder = " order by e.date_start" . $sDirection;
    if ($iSort == 2) $sOrder = " order by e.event_name" . $sDirection;
    if ($iSort == 3) $sOrder = " order by e.presenter" . $sDirection;
    if ($iSort == 4) $sOrder = " order by e.location" . $sDirection;
  }

  // Build full SQL statement
  
  $sSQL = "select e.date_start as e_date_start, " . 
    "e.event_desc as e_event_desc, " . 
    "e.event_name as e_event_name, " . 
    "e.location as e_location, " . 
    "e.presenter as e_presenter " . 
    " from events e ";
  
  $sSQL .= $sWhere . $sOrder;
  $tpl->set_var("SortParams", $sSortParams);

  // Execute SQL statement
  $db->query($sSQL);
  
  // Select current page
  $iPage = get_param("FormEvents_Page");
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
			$flddate_start = $db->f("e_date_start");
			$fldevent_name = $db->f("e_event_name");
			$fldpresenter = $db->f("e_presenter");
			$fldlocation = $db->f("e_location");
      $tpl->set_var("date_start", tohtml($flddate_start));
      $tpl->set_var("event_name", tohtml($fldevent_name));
      $tpl->set_var("presenter", tohtml($fldpresenter));
      $tpl->set_var("location", tohtml($fldlocation));
      $tpl->parse("DListEvents", true);
      $iCounter++;
    } while($iCounter < $RecordsPerPage &&$db->next_record());
  }
  else
  {
    // No Records in DB
    $tpl->set_var("DListEvents", "");
    $tpl->parse("EventsNoRecords", false);
    $tpl->set_var("EventsScroller", "");
    $tpl->parse("FormEvents", false);
    return;
  }
  
  // Parse scroller
  if(@$db->next_record())
  {
    if ($iPage == 1)
    {
      $tpl->set_var("EventsScrollerPrevSwitch", "_");
    }
    else
    {
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("EventsScrollerPrevSwitch", "");
    }
    $tpl->set_var("NextPage", ($iPage + 1));
    $tpl->set_var("EventsScrollerNextSwitch", "");
    $tpl->set_var("EventsCurrentPage", $iPage);
    $tpl->parse("EventsScroller", false);
  }
  else
  {
    if ($iPage == 1)
    {
      $tpl->set_var("EventsScroller", "");
    }
    else
    {
      $tpl->set_var("EventsScrollerNextSwitch", "_");
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("EventsScrollerPrevSwitch", "");
      $tpl->set_var("EventsCurrentPage", $iPage);
      $tpl->parse("EventsScroller", false);
    }
  }
  $tpl->set_var("EventsNoRecords", "");
  $tpl->parse("FormEvents", false);
  
}

?>