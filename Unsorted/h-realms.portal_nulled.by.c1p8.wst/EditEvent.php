<?php
/*********************************************************************************
 *       Filename: EditEvent.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "EditEvent.php";




$tpl = new Template($app_path);
$tpl->load_file("EditEvent.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);


$sEditEventErr = "";

$sAction = get_param("FormAction");
$sForm = get_param("FormName");
switch ($sForm) {
  case "EditEvent":
    EditEvent_action($sAction);
  break;
}Header_show();
Footer_show();
EditEvent_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************



function EditEvent_action($sAction)
{
  global $db;
  global $tpl;
  global $sForm;
  global $sEditEventErr;
  
  $sParams = "";
  $sActionFileName = "AdminEvents.php";

  

  $sWhere = "";
  $bErr = false;

  if($sAction == "cancel")
    header("Location: " . $sActionFileName); 

  
  // Create WHERE statement
  if($sAction == "update" || $sAction == "delete") 
  {
    $pPKevent_id = get_param("PK_event_id");
    if( !strlen($pPKevent_id)) return;
    $sWhere = "event_id=" . tosql($pPKevent_id, "Number");
  }

  // Load all form fields into variables
  
  $flddate_start = get_param("date_start");
  $fldevent_name = get_param("event_name");
  $fldevent_desc = get_param("event_desc");
  $fldlocation = get_param("location");
  $fldpresenter = get_param("presenter");
  $flduser_added = get_param("user_added");
  // Validate fields
  if($sAction == "insert" || $sAction == "update") 
  {
    if(!is_number($flduser_added))
      $sEditEventErr .= "The value in field User Added is incorrect.<br>";
    

    if(strlen($sEditEventErr)) return;
  }
  

  $sSQL = "";
  // Create SQL statement
  
  switch(strtolower($sAction)) 
  {
    case "insert":
      
        $sSQL = "insert into events (" . 
          "date_start," . 
          "event_name," . 
          "event_desc," . 
          "location," . 
          "presenter," . 
          "user_added)" . 
          " values (" . 
          tosql($flddate_start, "Text") . "," .
          tosql($fldevent_name, "Text") . "," .
          tosql($fldevent_desc, "Text") . "," .
          tosql($fldlocation, "Text") . "," .
          tosql($fldpresenter, "Text") . "," .
          tosql($flduser_added, "Number") . ")";    
    break;
    case "update":
      
        $sSQL = "update events set " .
          "date_start=" . tosql($flddate_start, "Text") .
          ",event_name=" . tosql($fldevent_name, "Text") .
          ",event_desc=" . tosql($fldevent_desc, "Text") .
          ",location=" . tosql($fldlocation, "Text") .
          ",presenter=" . tosql($fldpresenter, "Text") .
          ",user_added=" . tosql($flduser_added, "Number");
        $sSQL .= " where " . $sWhere;
    break;
    case "delete":
      
        $sSQL = "delete from events where " . $sWhere;
    break;
  }
  // Execute SQL statement
  if(strlen($sEditEventErr)) return;
  $db->query($sSQL);
  
  header("Location: " . $sActionFileName);
  
}

function EditEvent_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sEditEventErr;

  $sWhere = "";
  
  $bPK = true;
  $fldevent_id = "";
  $flddate_start = "";
  $fldevent_name = "";
  $fldevent_desc = "";
  $fldlocation = "";
  $fldpresenter = "";
  $flduser_added = "";
  

  if($sEditEventErr == "")
  {
    // Load primary key and form parameters
    $fldevent_id = get_param("event_id");
    $pevent_id = get_param("event_id");
    $tpl->set_var("EditEventError", "");
  }
  else
  {
    // Load primary key, form parameters and form fields
    $fldevent_id = strip(get_param("event_id"));
    $flddate_start = strip(get_param("date_start"));
    $fldevent_name = strip(get_param("event_name"));
    $fldevent_desc = strip(get_param("event_desc"));
    $fldlocation = strip(get_param("location"));
    $fldpresenter = strip(get_param("presenter"));
    $flduser_added = strip(get_param("user_added"));
    $pevent_id = get_param("PK_event_id");
    $tpl->set_var("sEditEventErr", $sEditEventErr);
    $tpl->parse("EditEventError", false);
  }

  
  if( !strlen($pevent_id)) $bPK = false;
  
  $sWhere .= "event_id=" . tosql($pevent_id, "Number");
  $tpl->set_var("PK_event_id", $pevent_id);

  $sSQL = "select * from events where " . $sWhere;

  

  if($bPK && !($sAction == "insert" && $sForm == "EditEvent"))
  {
    // Execute SQL statement
    $db->query($sSQL);
    $db->next_record();
    
    $fldevent_id = $db->f("event_id");
    if($sEditEventErr == "") 
    {
      // Load data from recordset when form displayed first time
      $flddate_start = $db->f("date_start");
      $fldevent_name = $db->f("event_name");
      $fldevent_desc = $db->f("event_desc");
      $fldlocation = $db->f("location");
      $fldpresenter = $db->f("presenter");
      $flduser_added = $db->f("user_added");
    }
    $tpl->set_var("EditEventInsert", "");
    $tpl->parse("EditEventEdit", false);
  }
  else
  {
    if($sEditEventErr == "")
    {
      $fldevent_id = tohtml(get_param("event_id"));
    }
    $tpl->set_var("EditEventEdit", "");
    $tpl->parse("EditEventInsert", false);
$flddate_start=date("Y-m-d G:i:s");
  }
  $tpl->set_var("EditEventCancel", "");

  // Show form field
  
    $tpl->set_var("event_id", tohtml($fldevent_id));
    $tpl->set_var("date_start", tohtml($flddate_start));
    $tpl->set_var("event_name", tohtml($fldevent_name));
    $tpl->set_var("event_desc", tohtml($fldevent_desc));
    $tpl->set_var("location", tohtml($fldlocation));
    $tpl->set_var("presenter", tohtml($fldpresenter));
    $tpl->set_var("LBuser_added", "");
    $tpl->set_var("ID", "");
    $tpl->set_var("Value", "");
    $tpl->parse("LBuser_added", true);
    $dbuser_added = new DB_Sql();
    $dbuser_added->Database = DATABASE_NAME;
    $dbuser_added->User     = DATABASE_USER;
    $dbuser_added->Password = DATABASE_PASSWORD;
    $dbuser_added->Host     = DATABASE_HOST;
  
    
    $dbuser_added->query("select member_id, member_login from members order by 2");
    while($dbuser_added->next_record())
    {
      $tpl->set_var("ID", $dbuser_added->f(0));
      $tpl->set_var("Value", $dbuser_added->f(1));
      if($dbuser_added->f(0) == $flduser_added)
        $tpl->set_var("Selected", "SELECTED" );
      else 
        $tpl->set_var("Selected", "");
      $tpl->parse("LBuser_added", true);
    }
    
  $tpl->parse("FormEditEvent", false);
  

}

?>