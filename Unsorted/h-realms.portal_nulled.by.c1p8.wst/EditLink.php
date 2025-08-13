<?php
/*********************************************************************************
 *       Filename: EditLink.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "EditLink.php";




$tpl = new Template($app_path);
$tpl->load_file("EditLink.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);


$slinksErr = "";

$sAction = get_param("FormAction");
$sForm = get_param("FormName");
switch ($sForm) {
  case "links":
    links_action($sAction);
  break;
}Header_show();
Footer_show();
links_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************



function links_action($sAction)
{
  global $db;
  global $tpl;
  global $sForm;
  global $slinksErr;
  
  $sParams = "";
  $sActionFileName = "AdminLinks.php";

  

  $sWhere = "";
  $bErr = false;

  if($sAction == "cancel")
    header("Location: " . $sActionFileName); 

  
  // Create WHERE statement
  if($sAction == "update" || $sAction == "delete") 
  {
    $pPKlink_id = get_param("PK_link_id");
    if( !strlen($pPKlink_id)) return;
    $sWhere = "link_id=" . tosql($pPKlink_id, "Number");
  }

  // Load all form fields into variables
  
  $fldUserID = get_session("UserID");
  $fldlink_name = get_param("link_name");
  $fldlink_url = get_param("link_url");
  $flddate_added = get_param("date_added");
  $fldaddress = get_param("address");
  $fldapproved = get_checkbox_value(get_param("approved"), "1", "0", "Number");
  $fldapproved_by = get_param("approved_by");
  $fldcategory_id = get_param("category_id");
  $flddate_approved = get_param("date_approved");
  $fldlink_desc = get_param("link_desc");
  // Validate fields
  if($sAction == "insert" || $sAction == "update") 
  {
    if(!strlen($fldcategory_id))
      $slinksErr .= "The value in field Category is required.<br>";
    
    if(!is_number($fldapproved_by))
      $slinksErr .= "The value in field Approved By is incorrect.<br>";
    
    if(!is_number($fldcategory_id))
      $slinksErr .= "The value in field Category is incorrect.<br>";
    

    if(strlen($slinksErr)) return;
  }
  

  $sSQL = "";
  // Create SQL statement
  
  switch(strtolower($sAction)) 
  {
    case "insert":
      
        $sSQL = "insert into links (" . 
          "added_by," . 
          "link_name," . 
          "link_url," . 
          "date_added," . 
          "address," . 
          "approved," . 
          "approved_by," . 
          "category_id," . 
          "date_approved," . 
          "link_desc)" . 
          " values (" . 
          tosql($fldUserID, "Number") . "," .
          tosql($fldlink_name, "Text") . "," .
          tosql($fldlink_url, "Text") . "," .
          tosql($flddate_added, "Date") . "," .
          tosql($fldaddress, "Text") . "," .
          $fldapproved . "," .
          tosql($fldapproved_by, "Number") . "," .
          tosql($fldcategory_id, "Number") . "," .
          tosql($flddate_approved, "Date") . "," .
          tosql($fldlink_desc, "Text") . ")";    
    break;
    case "update":
      
        $sSQL = "update links set " .
          "link_name=" . tosql($fldlink_name, "Text") .
          ",link_url=" . tosql($fldlink_url, "Text") .
          ",date_added=" . tosql($flddate_added, "Date") .
          ",address=" . tosql($fldaddress, "Text") .
          ",approved=" . $fldapproved .
          ",approved_by=" . tosql($fldapproved_by, "Number") .
          ",category_id=" . tosql($fldcategory_id, "Number") .
          ",date_approved=" . tosql($flddate_approved, "Date") .
          ",link_desc=" . tosql($fldlink_desc, "Text");
        $sSQL .= " where " . $sWhere;
    break;
    case "delete":
      
        $sSQL = "delete from links where " . $sWhere;
    break;
  }
  // Execute SQL statement
  if(strlen($slinksErr)) return;
  $db->query($sSQL);
  
  header("Location: " . $sActionFileName);
  
}

function links_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $slinksErr;

  $sWhere = "";
  
  $bPK = true;
  $fldlink_id = "";
  $fldlink_name = "";
  $fldlink_url = "";
  $flddate_added = "";
  $fldaddress = "";
  $fldapproved = "";
  $fldadded_by = "";
  $fldapproved_by = "";
  $fldcategory_id = "";
  $flddate_approved = "";
  $fldlink_desc = "";
  

  if($slinksErr == "")
  {
    // Load primary key and form parameters
    $fldlink_id = get_param("link_id");
    $plink_id = get_param("link_id");
    $tpl->set_var("linksError", "");
  }
  else
  {
    // Load primary key, form parameters and form fields
    $fldlink_id = strip(get_param("link_id"));
    $fldlink_name = strip(get_param("link_name"));
    $fldlink_url = strip(get_param("link_url"));
    $flddate_added = strip(get_param("date_added"));
    $fldaddress = strip(get_param("address"));
    $fldapproved = strip(get_param("approved"));
    $fldapproved_by = strip(get_param("approved_by"));
    $fldcategory_id = strip(get_param("category_id"));
    $flddate_approved = strip(get_param("date_approved"));
    $fldlink_desc = strip(get_param("link_desc"));
    $plink_id = get_param("PK_link_id");
    $fldadded_by = get_session("UserID");
    $tpl->set_var("slinksErr", $slinksErr);
    $tpl->parse("linksError", false);
  }

  
  if( !strlen($plink_id)) $bPK = false;
  
  $sWhere .= "link_id=" . tosql($plink_id, "Number");
  $tpl->set_var("PK_link_id", $plink_id);

  $sSQL = "select * from links where " . $sWhere;

  

  if($bPK && !($sAction == "insert" && $sForm == "links"))
  {
    // Execute SQL statement
    $db->query($sSQL);
    $db->next_record();
    
    $fldlink_id = $db->f("link_id");
    $fldadded_by = $db->f("added_by");
    if($slinksErr == "") 
    {
      // Load data from recordset when form displayed first time
      $fldlink_name = $db->f("link_name");
      $fldlink_url = $db->f("link_url");
      $flddate_added = $db->f("date_added");
      $fldaddress = $db->f("address");
      $fldapproved = $db->f("approved");
      $fldapproved_by = $db->f("approved_by");
      $fldcategory_id = $db->f("category_id");
      $flddate_approved = $db->f("date_approved");
      $fldlink_desc = $db->f("link_desc");
    }
    $tpl->set_var("linksInsert", "");
    $tpl->parse("linksEdit", false);
  }
  else
  {
    if($slinksErr == "")
    {
      $fldlink_id = tohtml(get_param("link_id"));
      $fldadded_by = tohtml(get_session("UserID"));
    }
    $tpl->set_var("linksEdit", "");
    $tpl->parse("linksInsert", false);
$flddate_added=date("Y-m-d");
  }
  $tpl->parse("linksCancel", false);
  // Set lookup fields
  $fldadded_by = dlookup("members", "member_login", "member_id=" . tosql($fldadded_by, "Number"));

  // Show form field
  
    $tpl->set_var("link_id", tohtml($fldlink_id));
    $tpl->set_var("link_name", tohtml($fldlink_name));
    $tpl->set_var("link_url", tohtml($fldlink_url));
    $tpl->set_var("date_added", tohtml($flddate_added));
    $tpl->set_var("address", tohtml($fldaddress));
      if(strtolower($fldapproved) == strtolower("1")) 
        $tpl->set_var("approved_CHECKED", "CHECKED");
      else
        $tpl->set_var("approved_CHECKED", "");

      $tpl->set_var("added_by", tohtml($fldadded_by));
    $tpl->set_var("LBapproved_by", "");
    $tpl->set_var("ID", "");
    $tpl->set_var("Value", "");
    $tpl->parse("LBapproved_by", true);
    $dbapproved_by = new DB_Sql();
    $dbapproved_by->Database = DATABASE_NAME;
    $dbapproved_by->User     = DATABASE_USER;
    $dbapproved_by->Password = DATABASE_PASSWORD;
    $dbapproved_by->Host     = DATABASE_HOST;
  
    
    $dbapproved_by->query("select member_id, member_login from members order by 2");
    while($dbapproved_by->next_record())
    {
      $tpl->set_var("ID", $dbapproved_by->f(0));
      $tpl->set_var("Value", $dbapproved_by->f(1));
      if($dbapproved_by->f(0) == $fldapproved_by)
        $tpl->set_var("Selected", "SELECTED" );
      else 
        $tpl->set_var("Selected", "");
      $tpl->parse("LBapproved_by", true);
    }
    
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
    
    $tpl->set_var("date_approved", tohtml($flddate_approved));
    $tpl->set_var("link_desc", tohtml($fldlink_desc));
  $tpl->parse("Formlinks", false);
  

}

?>