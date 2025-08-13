<?php
/*********************************************************************************
 *       Filename: AddLink.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "AddLink.php";



check_security(1);

$tpl = new Template($app_path);
$tpl->load_file("AddLink.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);


$sLinksErr = "";

$sAction = get_param("FormAction");
$sForm = get_param("FormName");
switch ($sForm) {
  case "Links":
    Links_action($sAction);
  break;
}Header_show();
Footer_show();
Links_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************



function Links_action($sAction)
{
  global $db;
  global $tpl;
  global $sForm;
  global $sLinksErr;
  
  $sParams = "";
  $sActionFileName = "AddLink.php";

  

  $sWhere = "";
  $bErr = false;

  if($sAction == "cancel")
    header("Location: " . $sActionFileName); 

  

  // Load all form fields into variables
  
  $fldUserID = get_session("UserID");
  $fldlink_name = get_param("link_name");
  $fldlink_desc = get_param("link_desc");
  $fldaddress = get_param("address");
  $fldlink_url = get_param("link_url");
  $fldcategory_id = get_param("category_id");
  $flddate_added = get_param("date_added");
  // Validate fields
  if($sAction == "insert" || $sAction == "update") 
  {
    if(!strlen($fldcategory_id))
      $sLinksErr .= "The value in field Category is required.<br>";
    
    if(!is_number($fldcategory_id))
      $sLinksErr .= "The value in field Category is incorrect.<br>";
    

    if(strlen($sLinksErr)) return;
  }
  

  $sSQL = "";
  // Create SQL statement
  
  switch(strtolower($sAction)) 
  {
    case "insert":
      
        $sSQL = "insert into links (" . 
          "added_by," . 
          "link_name," . 
          "link_desc," . 
          "address," . 
          "link_url," . 
          "category_id," . 
          "date_added)" . 
          " values (" . 
          tosql($fldUserID, "Number") . "," .
          tosql($fldlink_name, "Text") . "," .
          tosql($fldlink_desc, "Text") . "," .
          tosql($fldaddress, "Text") . "," .
          tosql($fldlink_url, "Text") . "," .
          tosql($fldcategory_id, "Number") . "," .
          tosql($flddate_added, "Date") . ")";    
    break;
  }
  // Execute SQL statement
  if(strlen($sLinksErr)) return;
  $db->query($sSQL);
  
  header("Location: " . $sActionFileName);
  
}

function Links_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sLinksErr;

  $sWhere = "";
  
  $bPK = true;
  $fldlink_id = "";
  $fldlink_name = "";
  $fldlink_desc = "";
  $fldaddress = "";
  $fldlink_url = "";
  $fldcategory_id = "";
  $flddate_added = "";
  

  if($sLinksErr == "")
  {
    // Load primary key and form parameters
    $plink_id = get_param("link_id");
    $tpl->set_var("LinksError", "");
  }
  else
  {
    // Load primary key, form parameters and form fields
    $fldlink_id = strip(get_param("link_id"));
    $fldlink_name = strip(get_param("link_name"));
    $fldlink_desc = strip(get_param("link_desc"));
    $fldaddress = strip(get_param("address"));
    $fldlink_url = strip(get_param("link_url"));
    $fldcategory_id = strip(get_param("category_id"));
    $flddate_added = strip(get_param("date_added"));
    $plink_id = get_param("PK_link_id");
    $tpl->set_var("sLinksErr", $sLinksErr);
    $tpl->parse("LinksError", false);
  }

  
  if( !strlen($plink_id)) $bPK = false;
  
  $sWhere .= "link_id=" . tosql($plink_id, "Number");
  $tpl->set_var("PK_link_id", $plink_id);

  $sSQL = "select * from links where " . $sWhere;

  

  if($bPK && !($sAction == "insert" && $sForm == "Links"))
  {
    // Execute SQL statement
    $db->query($sSQL);
    $db->next_record();
    
    $fldlink_id = $db->f("link_id");
    if($sLinksErr == "") 
    {
      // Load data from recordset when form displayed first time
      $fldlink_name = $db->f("link_name");
      $fldlink_desc = $db->f("link_desc");
      $fldaddress = $db->f("address");
      $fldlink_url = $db->f("link_url");
      $fldcategory_id = $db->f("category_id");
      $flddate_added = $db->f("date_added");
    }
    $tpl->set_var("LinksDelete", "");
    $tpl->set_var("LinksUpdate", "");
    $tpl->set_var("LinksInsert", "");
  }
  else
  {
    $tpl->set_var("LinksEdit", "");
    $tpl->parse("LinksInsert", false);
$flddate_added=date("Y-m-d");
  }
  $tpl->set_var("LinksCancel", "");

  // Show form field
  
    $tpl->set_var("link_id", tohtml($fldlink_id));
    $tpl->set_var("link_name", tohtml($fldlink_name));
    $tpl->set_var("link_desc", tohtml($fldlink_desc));
    $tpl->set_var("address", tohtml($fldaddress));
    $tpl->set_var("link_url", tohtml($fldlink_url));
    $tpl->set_var("LBcategory_id", "");
    $dbcategory_id = new DB_Sql();
    $dbcategory_id->Database = DATABASE_NAME;
    $dbcategory_id->User     = DATABASE_USER;
    $dbcategory_id->Password = DATABASE_PASSWORD;
    $dbcategory_id->Host     = DATABASE_HOST;
  
    
    $dbcategory_id->query("select category_id, category_desc from categories order by 2");
    while($dbcategory_id->next_record())
    {
      $tpl->set_var("ID", $dbcategory_id->f(0));
      $tpl->set_var("Value", $dbcategory_id->f(1));
      if($dbcategory_id->f(0) == $fldcategory_id)
        $tpl->set_var("Selected", "SELECTED" );
      else 
        $tpl->set_var("Selected", "");
      $tpl->parse("LBcategory_id", true);
    }
    
    $tpl->set_var("date_added", tohtml($flddate_added));
  $tpl->parse("FormLinks", false);
  

}

?>