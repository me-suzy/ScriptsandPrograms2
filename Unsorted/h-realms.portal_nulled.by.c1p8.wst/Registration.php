<?php
/*********************************************************************************
 *       Filename: Registration.php
 *       PHP & Templates build 03/28/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "Registration.php";




$tpl = new Template($app_path);
$tpl->load_file("Registration.html", "main");
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
  $sActionFileName = "Default.php";

  

  $sWhere = "";
  $bErr = false;

  if($sAction == "cancel")
    header("Location: " . $sActionFileName); 

  

  // Load all form fields into variables
  
  $fldmember_login = get_param("member_login");
  $fldmember_password = get_param("member_password");
  $fldfirst_name = get_param("first_name");
  $fldlast_name = get_param("last_name");
  $fldemail = get_param("email");
  $fldaddress1 = get_param("address1");
  $fldaddress2 = get_param("address2");
  $fldaddress3 = get_param("address3");
  $fldcity = get_param("city");
  $fldstate_id = get_param("state_id");
  $fldzip = get_param("zip");
  $fldcountry_id = get_param("country_id");
  $fldphone_day = get_param("phone_day");
  $fldphone_evn = get_param("phone_evn");
  $fldfax = get_param("fax");
  // Validate fields
  if($sAction == "insert" || $sAction == "update") 
  {
    if(!strlen($fldmember_login))
      $sFormErr .= "The value in field Login* is required.<br>";
    
    if(!strlen($fldmember_password))
      $sFormErr .= "The value in field Password* is required.<br>";
    
    if(!strlen($fldfirst_name))
      $sFormErr .= "The value in field First Name* is required.<br>";
    
    if(!strlen($fldlast_name))
      $sFormErr .= "The value in field Last Name* is required.<br>";
    
    if(!strlen($fldemail))
      $sFormErr .= "The value in field Email* is required.<br>";
    
    if(!is_number($fldcountry_id))
      $sFormErr .= "The value in field Country is incorrect.<br>";
    
    if(strlen($fldmember_login) )
    {
      $iCount = 0;

      if($sAction == "insert")
        $iCount = dlookup("members", "count(*)", "member_login=" . tosql($fldmember_login, "Text"));
      else if($sAction == "update")
        $iCount = dlookup("members", "count(*)", "member_login=" . tosql($fldmember_login, "Text") . " and not(" . $sWhere . ")");
  
      if($iCount > 0)
        $sFormErr .= "The value in field Login* is already in database.<br>";
    }                                                                               
    

    if(strlen($sFormErr)) return;
  }
  

  $sSQL = "";
  // Create SQL statement
  
  switch(strtolower($sAction)) 
  {
    case "insert":
      
        $sSQL = "insert into members (" . 
          "member_login," . 
          "member_password," . 
          "first_name," . 
          "last_name," . 
          "email," . 
          "address1," . 
          "address2," . 
          "address3," . 
          "city," . 
          "state_id," . 
          "zip," . 
          "country_id," . 
          "phone_day," . 
          "phone_evn," . 
          "fax)" . 
          " values (" . 
          tosql($fldmember_login, "Text") . "," .
          tosql($fldmember_password, "Text") . "," .
          tosql($fldfirst_name, "Text") . "," .
          tosql($fldlast_name, "Text") . "," .
          tosql($fldemail, "Text") . "," .
          tosql($fldaddress1, "Text") . "," .
          tosql($fldaddress2, "Text") . "," .
          tosql($fldaddress3, "Text") . "," .
          tosql($fldcity, "Text") . "," .
          tosql($fldstate_id, "Text") . "," .
          tosql($fldzip, "Text") . "," .
          tosql($fldcountry_id, "Number") . "," .
          tosql($fldphone_day, "Text") . "," .
          tosql($fldphone_evn, "Text") . "," .
          tosql($fldfax, "Text") . ")";    
    break;
  }
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
  $fldmember_password = "";
  $fldfirst_name = "";
  $fldlast_name = "";
  $fldemail = "";
  $fldaddress1 = "";
  $fldaddress2 = "";
  $fldaddress3 = "";
  $fldcity = "";
  $fldstate_id = "";
  $fldzip = "";
  $fldcountry_id = "";
  $fldphone_day = "";
  $fldphone_evn = "";
  $fldfax = "";
  

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
    $fldmember_login = strip(get_param("member_login"));
    $fldmember_password = strip(get_param("member_password"));
    $fldfirst_name = strip(get_param("first_name"));
    $fldlast_name = strip(get_param("last_name"));
    $fldemail = strip(get_param("email"));
    $fldaddress1 = strip(get_param("address1"));
    $fldaddress2 = strip(get_param("address2"));
    $fldaddress3 = strip(get_param("address3"));
    $fldcity = strip(get_param("city"));
    $fldstate_id = strip(get_param("state_id"));
    $fldzip = strip(get_param("zip"));
    $fldcountry_id = strip(get_param("country_id"));
    $fldphone_day = strip(get_param("phone_day"));
    $fldphone_evn = strip(get_param("phone_evn"));
    $fldfax = strip(get_param("fax"));
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
    if($sFormErr == "") 
    {
      // Load data from recordset when form displayed first time
      $fldmember_login = $db->f("member_login");
      $fldmember_password = $db->f("member_password");
      $fldfirst_name = $db->f("first_name");
      $fldlast_name = $db->f("last_name");
      $fldemail = $db->f("email");
      $fldaddress1 = $db->f("address1");
      $fldaddress2 = $db->f("address2");
      $fldaddress3 = $db->f("address3");
      $fldcity = $db->f("city");
      $fldstate_id = $db->f("state_id");
      $fldzip = $db->f("zip");
      $fldcountry_id = $db->f("country_id");
      $fldphone_day = $db->f("phone_day");
      $fldphone_evn = $db->f("phone_evn");
      $fldfax = $db->f("fax");
    }
    $tpl->set_var("FormDelete", "");
    $tpl->set_var("FormUpdate", "");
    $tpl->set_var("FormInsert", "");
  }
  else
  {
    $tpl->set_var("FormEdit", "");
    $tpl->parse("FormInsert", false);
  }
  $tpl->set_var("FormCancel", "");

  // Show form field
  
    $tpl->set_var("member_id", tohtml($fldmember_id));
    $tpl->set_var("member_login", tohtml($fldmember_login));
    $tpl->set_var("member_password", tohtml($fldmember_password));
    $tpl->set_var("first_name", tohtml($fldfirst_name));
    $tpl->set_var("last_name", tohtml($fldlast_name));
    $tpl->set_var("email", tohtml($fldemail));
    $tpl->set_var("address1", tohtml($fldaddress1));
    $tpl->set_var("address2", tohtml($fldaddress2));
    $tpl->set_var("address3", tohtml($fldaddress3));
    $tpl->set_var("city", tohtml($fldcity));
    $tpl->set_var("LBstate_id", "");
    $tpl->set_var("ID", "");
    $tpl->set_var("Value", "");
    $tpl->parse("LBstate_id", true);
    $dbstate_id = new DB_Sql();
    $dbstate_id->Database = DATABASE_NAME;
    $dbstate_id->User     = DATABASE_USER;
    $dbstate_id->Password = DATABASE_PASSWORD;
    $dbstate_id->Host     = DATABASE_HOST;
  
    
    $dbstate_id->query("select state_id, state_desc from lookup_states order by 2");
    while($dbstate_id->next_record())
    {
      $tpl->set_var("ID", $dbstate_id->f(0));
      $tpl->set_var("Value", $dbstate_id->f(1));
      if($dbstate_id->f(0) == $fldstate_id)
        $tpl->set_var("Selected", "SELECTED" );
      else 
        $tpl->set_var("Selected", "");
      $tpl->parse("LBstate_id", true);
    }
    
    $tpl->set_var("zip", tohtml($fldzip));
    $tpl->set_var("LBcountry_id", "");
    $tpl->set_var("ID", "");
    $tpl->set_var("Value", "");
    $tpl->parse("LBcountry_id", true);
    $dbcountry_id = new DB_Sql();
    $dbcountry_id->Database = DATABASE_NAME;
    $dbcountry_id->User     = DATABASE_USER;
    $dbcountry_id->Password = DATABASE_PASSWORD;
    $dbcountry_id->Host     = DATABASE_HOST;
  
    
    $dbcountry_id->query("select country_id, country_desc from lookup_countries order by 2");
    while($dbcountry_id->next_record())
    {
      $tpl->set_var("ID", $dbcountry_id->f(0));
      $tpl->set_var("Value", $dbcountry_id->f(1));
      if($dbcountry_id->f(0) == $fldcountry_id)
        $tpl->set_var("Selected", "SELECTED" );
      else 
        $tpl->set_var("Selected", "");
      $tpl->parse("LBcountry_id", true);
    }
    
    $tpl->set_var("phone_day", tohtml($fldphone_day));
    $tpl->set_var("phone_evn", tohtml($fldphone_evn));
    $tpl->set_var("fax", tohtml($fldfax));
  $tpl->parse("FormForm", false);
  

}

?>