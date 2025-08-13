<?php
/*********************************************************************************
 *       Filename: MemberInfo.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "MemberInfo.php";




$tpl = new Template($app_path);
$tpl->load_file("MemberInfo.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);


$sFormErr = "";

$sAction = get_param("FormAction");
$sForm = get_param("FormName");
switch ($sForm) {
  case "Form":
    Form_action($sAction);
  break;
}Header_show();
Footer_show();
Form_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************



function Form_action($sAction)
{
  global $db;
  global $tpl;
  global $sForm;
  global $sFormErr;
  
  $sParams = "";
  $sActionFileName = ".php";

  

  $sWhere = "";
  $bErr = false;

  if($sAction == "cancel")
    header("Location: " . $sActionFileName); 

  

  // Load all form fields into variables
  

  $sSQL = "";
  // Create SQL statement
  
  // Execute SQL statement
  if(strlen($sFormErr)) return;
  $db->query($sSQL);
  
  header("Location: " . $sActionFileName);
  
}

function Form_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sFormErr;

  $sWhere = "";
  
  $bPK = true;
  $fldmember_id = "";
  $fldmember_login = "";
  $fldfirst_name = "";
  $fldlast_name = "";
  $fldemail = "";
  $fldcity = "";
  $fldstate_id = "";
  $fldcountry_id = "";
  $fldphone_day = "";
  $fldphone_evn = "";
  

  if($sFormErr == "")
  {
    // Load primary key and form parameters
    $pmember_id = get_param("member_id");
    $tpl->set_var("FormError", "");
  }
  else
  {
    // Load primary key, form parameters and form fields
    $fldmember_id = strip(get_param("member_id"));
    $pmember_id = get_param("PK_member_id");
    $tpl->set_var("sFormErr", $sFormErr);
    $tpl->parse("FormError", false);
  }

  
  if( !strlen($pmember_id)) $bPK = false;
  
  $sWhere .= "member_id=" . tosql($pmember_id, "Number");
  $tpl->set_var("PK_member_id", $pmember_id);

  $sSQL = "select * from members where " . $sWhere;

  

  if($bPK && !($sAction == "insert" && $sForm == "Form"))
  {
    // Execute SQL statement
    $db->query($sSQL);
    $db->next_record();
    
    $fldmember_id = $db->f("member_id");
    $fldmember_login = $db->f("member_login");
    $fldfirst_name = $db->f("first_name");
    $fldlast_name = $db->f("last_name");
    $fldemail = $db->f("email");
    $fldcity = $db->f("city");
    $fldstate_id = $db->f("state_id");
    $fldcountry_id = $db->f("country_id");
    $fldphone_day = $db->f("phone_day");
    $fldphone_evn = $db->f("phone_evn");
    $tpl->set_var("FormDelete", "");
    $tpl->set_var("FormUpdate", "");
    $tpl->set_var("FormInsert", "");
  }
  else
  {
    $tpl->set_var("FormEdit", "");
    $tpl->set_var("FormInsert", "");
  }
  $tpl->set_var("FormCancel", "");
  // Set lookup fields
  $fldstate_id = dlookup("lookup_states", "state_desc", "state_id=" . tosql($fldstate_id, "Text"));
  $fldcountry_id = dlookup("lookup_countries", "country_desc", "country_id=" . tosql($fldcountry_id, "Number"));

  // Show form field
  
    $tpl->set_var("member_id", tohtml($fldmember_id));
      $tpl->set_var("member_login", tohtml($fldmember_login));
      $tpl->set_var("first_name", tohtml($fldfirst_name));
      $tpl->set_var("last_name", tohtml($fldlast_name));
      $tpl->set_var("email", tohtml($fldemail));
      $tpl->set_var("city", tohtml($fldcity));
      $tpl->set_var("state_id", tohtml($fldstate_id));
      $tpl->set_var("country_id", tohtml($fldcountry_id));
      $tpl->set_var("phone_day", tohtml($fldphone_day));
      $tpl->set_var("phone_evn", tohtml($fldphone_evn));
  $tpl->parse("FormForm", false);
  

}

?>