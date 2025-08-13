<?php
/*********************************************************************************
 *       Filename: Account.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "Account.php";



check_security(1);

$tpl = new Template($app_path);
$tpl->load_file("Account.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);


$sAccountErr = "";

$sAction = get_param("FormAction");
$sForm = get_param("FormName");
switch ($sForm) {
  case "Account":
    Account_action($sAction);
  break;
}Header_show();
Footer_show();
Account_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************



function Account_action($sAction)
{
  global $db;
  global $tpl;
  global $sForm;
  global $sAccountErr;
  
  $sParams = "";
  $sActionFileName = ".php";

  

  $sWhere = "";
  $bErr = false;

  if($sAction == "cancel")
    header("Location: " . $sActionFileName); 

  

  // Load all form fields into variables
  
  $fldUserID = get_session("UserID");

  $sSQL = "";
  // Create SQL statement
  
  // Execute SQL statement
  if(strlen($sAccountErr)) return;
  $db->query($sSQL);
  
  header("Location: " . $sActionFileName);
  
}

function Account_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sAccountErr;

  $sWhere = "";
  
  $bPK = true;
  $fldmember_id = "";
  $fldmember_login = "";
  $fldfirst_name = "";
  $fldlast_name = "";
  $fldemail = "";
  

  if($sAccountErr == "")
  {
    // Load primary key and form parameters
    $tpl->set_var("AccountError", "");
  }
  else
  {
    // Load primary key, form parameters and form fields
    $fldmember_id = strip(get_param("member_id"));
    $tpl->set_var("sAccountErr", $sAccountErr);
    $tpl->parse("AccountError", false);
  }

  
  $pmember_id = get_session("UserID");
  if( !strlen($pmember_id)) $bPK = false;
  
  $sWhere .= "member_id=" . tosql($pmember_id, "Number");
  $tpl->set_var("PK_member_id", $pmember_id);

  $sSQL = "select * from members where " . $sWhere;

  

  if($bPK && !($sAction == "insert" && $sForm == "Account"))
  {
    // Execute SQL statement
    $db->query($sSQL);
    $db->next_record();
    
    $fldmember_id = $db->f("member_id");
    $fldmember_login = $db->f("member_login");
    $fldfirst_name = $db->f("first_name");
    $fldlast_name = $db->f("last_name");
    $fldemail = $db->f("email");
    $tpl->set_var("AccountDelete", "");
    $tpl->set_var("AccountUpdate", "");
    $tpl->set_var("AccountInsert", "");
  }
  else
  {
    if($sAccountErr == "")
    {
      $fldmember_id = tohtml(get_session("UserID"));
    }
    $tpl->set_var("AccountEdit", "");
    $tpl->set_var("AccountInsert", "");
  }
  $tpl->set_var("AccountCancel", "");

  // Show form field
  
    $tpl->set_var("member_id", tohtml($fldmember_id));
      $tpl->set_var("member_login", tohtml($fldmember_login));
      $tpl->set_var("member_login_URLLink", "MemberRecord.php");
      $tpl->set_var("first_name", tohtml($fldfirst_name));
      $tpl->set_var("last_name", tohtml($fldlast_name));
      $tpl->set_var("email", tohtml($fldemail));
  $tpl->parse("FormAccount", false);
  

}

?>