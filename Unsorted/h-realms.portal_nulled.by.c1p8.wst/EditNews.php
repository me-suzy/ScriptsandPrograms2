<?php
/*********************************************************************************
 *       Filename: EditNews.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "EditNews.php";




$tpl = new Template($app_path);
$tpl->load_file("EditNews.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);


$sEditNewsErr = "";

$sAction = get_param("FormAction");
$sForm = get_param("FormName");
switch ($sForm) {
  case "EditNews":
    EditNews_action($sAction);
  break;
}Header_show();
Footer_show();
EditNews_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************



function EditNews_action($sAction)
{
  global $db;
  global $tpl;
  global $sForm;
  global $sEditNewsErr;
  
  $sParams = "";
  $sActionFileName = "AdminNews.php";

  

  $sWhere = "";
  $bErr = false;

  if($sAction == "cancel")
    header("Location: " . $sActionFileName); 

  
  // Create WHERE statement
  if($sAction == "update" || $sAction == "delete") 
  {
    $pPKnews_id = get_param("PK_news_id");
    if( !strlen($pPKnews_id)) return;
    $sWhere = "news_id=" . tosql($pPKnews_id, "Number");
  }

  // Load all form fields into variables
  
  $fldnews_html = get_param("news_html");
  // Validate fields
  if($sAction == "insert" || $sAction == "update") 
  {

    if(strlen($sEditNewsErr)) return;
  }
  

  $sSQL = "";
  // Create SQL statement
  
  switch(strtolower($sAction)) 
  {
    case "insert":
      
        $sSQL = "insert into news (" . 
          "news_html)" . 
          " values (" . 
          tosql($fldnews_html, "Text") . ")";    
    break;
    case "update":
      
        $sSQL = "update news set " .
          "news_html=" . tosql($fldnews_html, "Text");
        $sSQL .= " where " . $sWhere;
    break;
    case "delete":
      
        $sSQL = "delete from news where " . $sWhere;
    break;
  }
  // Execute SQL statement
  if(strlen($sEditNewsErr)) return;
  $db->query($sSQL);
  
  header("Location: " . $sActionFileName);
  
}

function EditNews_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sEditNewsErr;

  $sWhere = "";
  
  $bPK = true;
  $fldnews_id = "";
  $fldnews_html = "";
  

  if($sEditNewsErr == "")
  {
    // Load primary key and form parameters
    $fldnews_id = get_param("news_id");
    $pnews_id = get_param("news_id");
    $tpl->set_var("EditNewsError", "");
  }
  else
  {
    // Load primary key, form parameters and form fields
    $fldnews_id = strip(get_param("news_id"));
    $fldnews_html = strip(get_param("news_html"));
    $pnews_id = get_param("PK_news_id");
    $tpl->set_var("sEditNewsErr", $sEditNewsErr);
    $tpl->parse("EditNewsError", false);
  }

  
  if( !strlen($pnews_id)) $bPK = false;
  
  $sWhere .= "news_id=" . tosql($pnews_id, "Number");
  $tpl->set_var("PK_news_id", $pnews_id);

  $sSQL = "select * from news where " . $sWhere;

  

  if($bPK && !($sAction == "insert" && $sForm == "EditNews"))
  {
    // Execute SQL statement
    $db->query($sSQL);
    $db->next_record();
    
    $fldnews_id = $db->f("news_id");
    if($sEditNewsErr == "") 
    {
      // Load data from recordset when form displayed first time
      $fldnews_html = $db->f("news_html");
    }
    $tpl->set_var("EditNewsInsert", "");
    $tpl->parse("EditNewsEdit", false);
  }
  else
  {
    if($sEditNewsErr == "")
    {
      $fldnews_id = tohtml(get_param("news_id"));
    }
    $tpl->set_var("EditNewsEdit", "");
    $tpl->parse("EditNewsInsert", false);
  }
  $tpl->set_var("EditNewsCancel", "");

  // Show form field
  
    $tpl->set_var("news_id", tohtml($fldnews_id));
    $tpl->set_var("news_html", tohtml($fldnews_html));
  $tpl->parse("FormEditNews", false);
  

}

?>