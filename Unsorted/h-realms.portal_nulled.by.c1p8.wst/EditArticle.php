<?php
/*********************************************************************************
 *       Filename: EditArticle.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "EditArticle.php";




$tpl = new Template($app_path);
$tpl->load_file("EditArticle.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);


$sEditArticleErr = "";

$sAction = get_param("FormAction");
$sForm = get_param("FormName");
switch ($sForm) {
  case "EditArticle":
    EditArticle_action($sAction);
  break;
}Header_show();
Footer_show();
EditArticle_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************



function EditArticle_action($sAction)
{
  global $db;
  global $tpl;
  global $sForm;
  global $sEditArticleErr;
  
  $sParams = "";
  $sActionFileName = "AdminArticles.php";

  

  $sWhere = "";
  $bErr = false;

  if($sAction == "cancel")
    header("Location: " . $sActionFileName); 

  
  // Create WHERE statement
  if($sAction == "update" || $sAction == "delete") 
  {
    $pPKarticle_id = get_param("PK_article_id");
    if( !strlen($pPKarticle_id)) return;
    $sWhere = "article_id=" . tosql($pPKarticle_id, "Number");
  }

  // Load all form fields into variables
  
  $fldarticle_title = get_param("article_title");
  $fldcategory_id = get_param("category_id");
  $fldarticle_desc = get_param("article_desc");
  $flddate_posted = get_param("date_posted");
  $flddate_expire = get_param("date_expire");
  // Validate fields
  if($sAction == "insert" || $sAction == "update") 
  {
    if(!strlen($fldcategory_id))
      $sEditArticleErr .= "The value in field Category is required.<br>";
    
    if(!is_number($fldcategory_id))
      $sEditArticleErr .= "The value in field Category is incorrect.<br>";
    

    if(strlen($sEditArticleErr)) return;
  }
  

  $sSQL = "";
  // Create SQL statement
  
  switch(strtolower($sAction)) 
  {
    case "insert":
      
        $sSQL = "insert into articles (" . 
          "article_title," . 
          "category_id," . 
          "article_desc," . 
          "date_posted," . 
          "date_expire)" . 
          " values (" . 
          tosql($fldarticle_title, "Text") . "," .
          tosql($fldcategory_id, "Number") . "," .
          tosql($fldarticle_desc, "Text") . "," .
          tosql($flddate_posted, "Date") . "," .
          tosql($flddate_expire, "Text") . ")";    
    break;
    case "update":
      
        $sSQL = "update articles set " .
          "article_title=" . tosql($fldarticle_title, "Text") .
          ",category_id=" . tosql($fldcategory_id, "Number") .
          ",article_desc=" . tosql($fldarticle_desc, "Text") .
          ",date_posted=" . tosql($flddate_posted, "Date") .
          ",date_expire=" . tosql($flddate_expire, "Text");
        $sSQL .= " where " . $sWhere;
    break;
    case "delete":
      
        $sSQL = "delete from articles where " . $sWhere;
    break;
  }
  // Execute SQL statement
  if(strlen($sEditArticleErr)) return;
  $db->query($sSQL);
  
  header("Location: " . $sActionFileName);
  
}

function EditArticle_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sEditArticleErr;

  $sWhere = "";
  
  $bPK = true;
  $fldarticle_id = "";
  $fldarticle_title = "";
  $fldcategory_id = "";
  $fldarticle_desc = "";
  $flddate_posted = "";
  $flddate_expire = "";
  

  if($sEditArticleErr == "")
  {
    // Load primary key and form parameters
    $fldarticle_id = get_param("article_id");
    $particle_id = get_param("article_id");
    $tpl->set_var("EditArticleError", "");
  }
  else
  {
    // Load primary key, form parameters and form fields
    $fldarticle_id = strip(get_param("article_id"));
    $fldarticle_title = strip(get_param("article_title"));
    $fldcategory_id = strip(get_param("category_id"));
    $fldarticle_desc = strip(get_param("article_desc"));
    $flddate_posted = strip(get_param("date_posted"));
    $flddate_expire = strip(get_param("date_expire"));
    $particle_id = get_param("PK_article_id");
    $tpl->set_var("sEditArticleErr", $sEditArticleErr);
    $tpl->parse("EditArticleError", false);
  }

  
  if( !strlen($particle_id)) $bPK = false;
  
  $sWhere .= "article_id=" . tosql($particle_id, "Number");
  $tpl->set_var("PK_article_id", $particle_id);

  $sSQL = "select * from articles where " . $sWhere;

  

  if($bPK && !($sAction == "insert" && $sForm == "EditArticle"))
  {
    // Execute SQL statement
    $db->query($sSQL);
    $db->next_record();
    
    $fldarticle_id = $db->f("article_id");
    if($sEditArticleErr == "") 
    {
      // Load data from recordset when form displayed first time
      $fldarticle_title = $db->f("article_title");
      $fldcategory_id = $db->f("category_id");
      $fldarticle_desc = $db->f("article_desc");
      $flddate_posted = $db->f("date_posted");
      $flddate_expire = $db->f("date_expire");
    }
    $tpl->set_var("EditArticleInsert", "");
    $tpl->parse("EditArticleEdit", false);
  }
  else
  {
    if($sEditArticleErr == "")
    {
      $fldarticle_id = tohtml(get_param("article_id"));
    }
    $tpl->set_var("EditArticleEdit", "");
    $tpl->parse("EditArticleInsert", false);
  }
  $tpl->set_var("EditArticleCancel", "");

  // Show form field
  
    $tpl->set_var("article_id", tohtml($fldarticle_id));
    $tpl->set_var("article_title", tohtml($fldarticle_title));
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
    
    $tpl->set_var("article_desc", tohtml($fldarticle_desc));
    $tpl->set_var("date_posted", tohtml($flddate_posted));
    $tpl->set_var("date_expire", tohtml($flddate_expire));
  $tpl->parse("FormEditArticle", false);
  

}

?>