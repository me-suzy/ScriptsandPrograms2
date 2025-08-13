<?php
/*********************************************************************************
 *       Filename: EditMembers.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "EditMembers.php";



check_security(3);

$tpl = new Template($app_path);
$tpl->load_file("EditMembers.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);


$sMembersErr = "";

$sAction = get_param("FormAction");
$sForm = get_param("FormName");
switch ($sForm) {
  case "Members":
    Members_action($sAction);
  break;
}Header_show();
Footer_show();
Members_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************



function Members_action($sAction)
{
  global $db;
  global $tpl;
  global $sForm;
  global $sMembersErr;
  
  $sParams = "";
  $sActionFileName = "AdminMembers.php";

  

  $sWhere = "";
  $bErr = false;

  if($sAction == "cancel")
    header("Location: " . $sActionFileName); 

  
  // Create WHERE statement
  if($sAction == "update" || $sAction == "delete") 
  {
    $pPKmember_id = get_param("PK_member_id");
    if( !strlen($pPKmember_id)) return;
    $sWhere = "member_id=" . tosql($pPKmember_id, "Number");
  }

  // Load all form fields into variables
  
  $fldmember_login = get_param("member_login");
  $fldmember_password = get_param("member_password");
  $fldsecurity_level_id = get_param("security_level_id");
  $fldfirst_name = get_param("first_name");
  $fldlast_name = get_param("last_name");
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
  $fldemail = get_param("email");
  $flddate_created = get_param("date_created");
  // Validate fields
  if($sAction == "insert" || $sAction == "update") 
  {
    if(!strlen($fldmember_login))
      $sMembersErr .= "The value in field Login is required.<br>";
    
    if(!strlen($fldmember_password))
      $sMembersErr .= "The value in field Password is required.<br>";
    
    if(!strlen($fldsecurity_level_id))
      $sMembersErr .= "The value in field Security Level is required.<br>";
    
    if(!is_number($fldsecurity_level_id))
      $sMembersErr .= "The value in field Security Level is incorrect.<br>";
    
    if(!is_number($fldcountry_id))
      $sMembersErr .= "The value in field Country is incorrect.<br>";
    
    if(strlen($fldmember_login) )
    {
      $iCount = 0;

      if($sAction == "insert")
        $iCount = dlookup("members", "count(*)", "member_login=" . tosql($fldmember_login, "Text"));
      else if($sAction == "update")
        $iCount = dlookup("members", "count(*)", "member_login=" . tosql($fldmember_login, "Text") . " and not(" . $sWhere . ")");
  
      if($iCount > 0)
        $sMembersErr .= "The value in field Login is already in database.<br>";
    }                                                                               
    

    if(strlen($sMembersErr)) return;
  }
  

  $sSQL = "";
  // Create SQL statement
  
  switch(strtolower($sAction)) 
  {
    case "insert":
      
        $sSQL = "insert into members (" . 
          "member_login," . 
          "member_password," . 
          "security_level_id," . 
          "first_name," . 
          "last_name," . 
          "address1," . 
          "address2," . 
          "address3," . 
          "city," . 
          "state_id," . 
          "zip," . 
          "country_id," . 
          "phone_day," . 
          "phone_evn," . 
          "fax," . 
          "email," . 
          "date_created)" . 
          " values (" . 
          tosql($fldmember_login, "Text") . "," .
          tosql($fldmember_password, "Text") . "," .
          tosql($fldsecurity_level_id, "Number") . "," .
          tosql($fldfirst_name, "Text") . "," .
          tosql($fldlast_name, "Text") . "," .
          tosql($fldaddress1, "Text") . "," .
          tosql($fldaddress2, "Text") . "," .
          tosql($fldaddress3, "Text") . "," .
          tosql($fldcity, "Text") . "," .
          tosql($fldstate_id, "Text") . "," .
          tosql($fldzip, "Text") . "," .
          tosql($fldcountry_id, "Number") . "," .
          tosql($fldphone_day, "Text") . "," .
          tosql($fldphone_evn, "Text") . "," .
          tosql($fldfax, "Text") . "," .
          tosql($fldemail, "Text") . "," .
          tosql($flddate_created, "Date") . ")";    
    break;
    case "update":
      
        $sSQL = "update members set " .
          "member_login=" . tosql($fldmember_login, "Text") .
          ",member_password=" . tosql($fldmember_password, "Text") .
          ",security_level_id=" . tosql($fldsecurity_level_id, "Number") .
          ",first_name=" . tosql($fldfirst_name, "Text") .
          ",last_name=" . tosql($fldlast_name, "Text") .
          ",address1=" . tosql($fldaddress1, "Text") .
          ",address2=" . tosql($fldaddress2, "Text") .
          ",address3=" . tosql($fldaddress3, "Text") .
          ",city=" . tosql($fldcity, "Text") .
          ",state_id=" . tosql($fldstate_id, "Text") .
          ",zip=" . tosql($fldzip, "Text") .
          ",country_id=" . tosql($fldcountry_id, "Number") .
          ",phone_day=" . tosql($fldphone_day, "Text") .
          ",phone_evn=" . tosql($fldphone_evn, "Text") .
          ",fax=" . tosql($fldfax, "Text") .
          ",email=" . tosql($fldemail, "Text") .
          ",date_created=" . tosql($flddate_created, "Date");
        $sSQL .= " where " . $sWhere;
    break;
    case "delete":
      
        $sSQL = "delete from members where " . $sWhere;
    break;
  }
  // Execute SQL statement
  if(strlen($sMembersErr)) return;
  $db->query($sSQL);
  
  header("Location: " . $sActionFileName);
  
}

function Members_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sMembersErr;

  $sWhere = "";
  
  $bPK = true;
  $fldmember_id = "";
  $fldmember_login = "";
  $fldmember_password = "";
  $fldsecurity_level_id = "";
  $fldfirst_name = "";
  $fldlast_name = "";
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
  $fldemail = "";
  $flddate_created = "";
  

  if($sMembersErr == "")
  {
    // Load primary key and form parameters
    $fldmember_id = get_param("member_id");
    $pmember_id = get_param("member_id");
    $tpl->set_var("MembersError", "");
  }
  else
  {
    // Load primary key, form parameters and form fields
    $fldmember_id = strip(get_param("member_id"));
    $fldmember_login = strip(get_param("member_login"));
    $fldmember_password = strip(get_param("member_password"));
    $fldsecurity_level_id = strip(get_param("security_level_id"));
    $fldfirst_name = strip(get_param("first_name"));
    $fldlast_name = strip(get_param("last_name"));
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
    $fldemail = strip(get_param("email"));
    $flddate_created = strip(get_param("date_created"));
    $pmember_id = get_param("PK_member_id");
    $tpl->set_var("sMembersErr", $sMembersErr);
    $tpl->parse("MembersError", false);
  }

  
  if( !strlen($pmember_id)) $bPK = false;
  
  $sWhere .= "member_id=" . tosql($pmember_id, "Number");
  $tpl->set_var("PK_member_id", $pmember_id);

  $sSQL = "select * from members where " . $sWhere;

  

  if($bPK && !($sAction == "insert" && $sForm == "Members"))
  {
    // Execute SQL statement
    $db->query($sSQL);
    $db->next_record();
    
    $fldmember_id = $db->f("member_id");
    if($sMembersErr == "") 
    {
      // Load data from recordset when form displayed first time
      $fldmember_login = $db->f("member_login");
      $fldmember_password = $db->f("member_password");
      $fldsecurity_level_id = $db->f("security_level_id");
      $fldfirst_name = $db->f("first_name");
      $fldlast_name = $db->f("last_name");
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
      $fldemail = $db->f("email");
      $flddate_created = $db->f("date_created");
    }
    $tpl->set_var("MembersInsert", "");
    $tpl->parse("MembersEdit", false);
  }
  else
  {
    if($sMembersErr == "")
    {
      $fldmember_id = tohtml(get_param("member_id"));
      $fldsecurity_level_id= "1";
    }
    $tpl->set_var("MembersEdit", "");
    $tpl->parse("MembersInsert", false);
$flddate_created=date("Y-m-d");
  }
  $tpl->parse("MembersCancel", false);

  // Show form field
  
    $tpl->set_var("member_id", tohtml($fldmember_id));
    $tpl->set_var("member_login", tohtml($fldmember_login));
    $tpl->set_var("member_password", tohtml($fldmember_password));
    $tpl->set_var("LBsecurity_level_id", "");
    $LOV = split(";", "0;New;1;Member;3;Admin");
  
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
    $tpl->set_var("first_name", tohtml($fldfirst_name));
    $tpl->set_var("last_name", tohtml($fldlast_name));
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
    $tpl->set_var("email", tohtml($fldemail));
    $tpl->set_var("date_created", tohtml($flddate_created));
  $tpl->parse("FormMembers", false);
  

}

?>