<?php
/*********************************************************************************
 *       Filename: EditOfficer.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "EditOfficer.php";




$tpl = new Template($app_path);
$tpl->load_file("EditOfficer.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);


$sEditOfficerErr = "";

$sAction = get_param("FormAction");
$sForm = get_param("FormName");
switch ($sForm) {
  case "EditOfficer":
    EditOfficer_action($sAction);
  break;
}Header_show();
Footer_show();
EditOfficer_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************



function EditOfficer_action($sAction)
{
  global $db;
  global $tpl;
  global $sForm;
  global $sEditOfficerErr;
  
  $sParams = "";
  $sActionFileName = "AdminOfficers.php";

  

  $sWhere = "";
  $bErr = false;

  if($sAction == "cancel")
    header("Location: " . $sActionFileName); 

  
  // Create WHERE statement
  if($sAction == "update" || $sAction == "delete") 
  {
    $pPKofficer_id = get_param("PK_officer_id");
    if( !strlen($pPKofficer_id)) return;
    $sWhere = "officer_id=" . tosql($pPKofficer_id, "Number");
  }

  // Load all form fields into variables
  
  $fldofficer_name = get_param("officer_name");
  $fldofficer_position = get_param("officer_position");
  $fldofficer_email = get_param("officer_email");
  // Validate fields
  if($sAction == "insert" || $sAction == "update") 
  {

    if(strlen($sEditOfficerErr)) return;
  }
  

  $sSQL = "";
  // Create SQL statement
  
  switch(strtolower($sAction)) 
  {
    case "insert":
      
        $sSQL = "insert into officers (" . 
          "officer_name," . 
          "officer_position," . 
          "officer_email)" . 
          " values (" . 
          tosql($fldofficer_name, "Text") . "," .
          tosql($fldofficer_position, "Text") . "," .
          tosql($fldofficer_email, "Text") . ")";    
    break;
    case "update":
      
        $sSQL = "update officers set " .
          "officer_name=" . tosql($fldofficer_name, "Text") .
          ",officer_position=" . tosql($fldofficer_position, "Text") .
          ",officer_email=" . tosql($fldofficer_email, "Text");
        $sSQL .= " where " . $sWhere;
    break;
    case "delete":
      
        $sSQL = "delete from officers where " . $sWhere;
    break;
  }
  // Execute SQL statement
  if(strlen($sEditOfficerErr)) return;
  $db->query($sSQL);
  
  header("Location: " . $sActionFileName);
  
}

function EditOfficer_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sEditOfficerErr;

  $sWhere = "";
  
  $bPK = true;
  $fldofficer_id = "";
  $fldofficer_name = "";
  $fldofficer_position = "";
  $fldofficer_email = "";
  

  if($sEditOfficerErr == "")
  {
    // Load primary key and form parameters
    $fldofficer_id = get_param("officer_id");
    $pofficer_id = get_param("officer_id");
    $tpl->set_var("EditOfficerError", "");
  }
  else
  {
    // Load primary key, form parameters and form fields
    $fldofficer_id = strip(get_param("officer_id"));
    $fldofficer_name = strip(get_param("officer_name"));
    $fldofficer_position = strip(get_param("officer_position"));
    $fldofficer_email = strip(get_param("officer_email"));
    $pofficer_id = get_param("PK_officer_id");
    $tpl->set_var("sEditOfficerErr", $sEditOfficerErr);
    $tpl->parse("EditOfficerError", false);
  }

  
  if( !strlen($pofficer_id)) $bPK = false;
  
  $sWhere .= "officer_id=" . tosql($pofficer_id, "Number");
  $tpl->set_var("PK_officer_id", $pofficer_id);

  $sSQL = "select * from officers where " . $sWhere;

  

  if($bPK && !($sAction == "insert" && $sForm == "EditOfficer"))
  {
    // Execute SQL statement
    $db->query($sSQL);
    $db->next_record();
    
    $fldofficer_id = $db->f("officer_id");
    if($sEditOfficerErr == "") 
    {
      // Load data from recordset when form displayed first time
      $fldofficer_name = $db->f("officer_name");
      $fldofficer_position = $db->f("officer_position");
      $fldofficer_email = $db->f("officer_email");
    }
    $tpl->set_var("EditOfficerInsert", "");
    $tpl->parse("EditOfficerEdit", false);
  }
  else
  {
    if($sEditOfficerErr == "")
    {
      $fldofficer_id = tohtml(get_param("officer_id"));
    }
    $tpl->set_var("EditOfficerEdit", "");
    $tpl->parse("EditOfficerInsert", false);
  }
  $tpl->set_var("EditOfficerCancel", "");

  // Show form field
  
    $tpl->set_var("officer_id", tohtml($fldofficer_id));
    $tpl->set_var("officer_name", tohtml($fldofficer_name));
    $tpl->set_var("officer_position", tohtml($fldofficer_position));
    $tpl->set_var("officer_email", tohtml($fldofficer_email));
  $tpl->parse("FormEditOfficer", false);
  

}

?>