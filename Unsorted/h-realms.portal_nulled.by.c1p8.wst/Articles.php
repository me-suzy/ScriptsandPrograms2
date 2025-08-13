<?php
/*********************************************************************************
 *       Filename: Articles.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "Articles.php";




$tpl = new Template($app_path);
$tpl->load_file("Articles.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);

Header_show();
Footer_show();
Search_show();
Articles_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************


function Search_show()
{
  global $db;
  global $tpl;
  
  $tpl->set_var("ActionPage", "Articles.php");
	
  // Set variables with search parameters
  $fldsearch = strip(get_param("search"));
  $fldcategory_id = strip(get_param("category_id"));
    // Show fields
    $tpl->set_var("search", tohtml($fldsearch));
    $tpl->set_var("LBcategory_id", "");
    $tpl->set_var("ID", "");
    $tpl->set_var("Value", "All");
    $tpl->parse("LBcategory_id", true);
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
    
  $tpl->parse("FormSearch", false);
}



function Articles_show()
{

  
  global $tpl;
  global $db;
  global $sArticlesErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $HasParam = false;

  
  $tpl->set_var("TransitParams", "search=" . tourl(strip(get_param("search"))) . "&category_id=" . tourl(strip(get_param("category_id"))) . "&");
  $tpl->set_var("FormParams", "search=" . tourl(strip(get_param("search"))) . "&category_id=" . tourl(strip(get_param("category_id"))) . "&");
  // Build WHERE statement
  
  $pcategory_id = get_param("category_id");
  if(is_number($pcategory_id) && strlen($pcategory_id))
    $pcategory_id = round($pcategory_id);
  else 
    $pcategory_id = "";
  if(strlen($pcategory_id)) 
  {
    $HasParam = true;
    $sWhere .= "a.category_id=" . $pcategory_id;
  }
  $psearch = get_param("search");
  if(strlen($psearch)) 
  {
    if ($sWhere != "") $sWhere .= " and ";
    $HasParam = true;
    $sWhere .= "a.article_desc like " . tosql("%".$psearch ."%", "Text");
  }
  if($HasParam)
    $sWhere = " AND (" . $sWhere . ")";

  $sDirection = "";
  $sSortParams = "";
  
  // Build ORDER statement
  $sOrder = " order by a.article_title Asc";
  $iSort = get_param("FormArticles_Sorting");
  $iSorted = get_param("FormArticles_Sorted");
  if(!$iSort)
  {
    $tpl->set_var("Form_Sorting", "");
  }
  else
  {
    if($iSort == $iSorted)
    {
      $tpl->set_var("Form_Sorting", "");
      $sDirection = " DESC";
      $sSortParams = "FormArticles_Sorting=" . $iSort . "&FormArticles_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "FormArticles_Sorting=" . $iSort . "&FormArticles_Sorted=" . "&";
    }
    
    if ($iSort == 1) $sOrder = " order by a.article_title" . $sDirection;
    if ($iSort == 2) $sOrder = " order by c.category_desc" . $sDirection;
    if ($iSort == 3) $sOrder = " order by a.article_desc" . $sDirection;
  }

  // Build full SQL statement
  
  $sSQL = "select a.article_desc as a_article_desc, " . 
    "a.article_title as a_article_title, " . 
    "a.category_id as a_category_id, " . 
    "c.category_id as c_category_id, " . 
    "c.category_desc as c_category_desc " . 
    " from articles a, categories c" . 
    " where c.category_id=a.category_id  ";
  
  $sSQL .= $sWhere . $sOrder;
  $tpl->set_var("SortParams", $sSortParams);

  // Execute SQL statement
  $db->query($sSQL);
  
  // Select current page
  $iPage = get_param("FormArticles_Page");
  if(!strlen($iPage)) $iPage = 1;
  $RecordsPerPage = 20;
  if(($iPage - 1) * $RecordsPerPage != 0)
    $db->seek(($iPage - 1) * $RecordsPerPage);
  $iCounter = 0;

  if($db->next_record())
  {  
    // Show main table based on SQL query
    do
    {
			$fldarticle_title = $db->f("a_article_title");
			$fldcategory_id = $db->f("c_category_desc");
			$fldarticle_desc = $db->f("a_article_desc");
      $tpl->set_var("article_title", tohtml($fldarticle_title));
      $tpl->set_var("category_id", tohtml($fldcategory_id));
      $tpl->set_var("article_desc", $fldarticle_desc);
      $tpl->parse("DListArticles", true);
      $iCounter++;
    } while($iCounter < $RecordsPerPage &&$db->next_record());
  }
  else
  {
    // No Records in DB
    $tpl->set_var("DListArticles", "");
    $tpl->parse("ArticlesNoRecords", false);
    $tpl->set_var("ArticlesScroller", "");
    $tpl->parse("FormArticles", false);
    return;
  }
  
  // Parse scroller
  if(@$db->next_record())
  {
    if ($iPage == 1)
    {
      $tpl->set_var("ArticlesScrollerPrevSwitch", "_");
    }
    else
    {
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("ArticlesScrollerPrevSwitch", "");
    }
    $tpl->set_var("NextPage", ($iPage + 1));
    $tpl->set_var("ArticlesScrollerNextSwitch", "");
    $tpl->set_var("ArticlesCurrentPage", $iPage);
    $tpl->parse("ArticlesScroller", false);
  }
  else
  {
    if ($iPage == 1)
    {
      $tpl->set_var("ArticlesScroller", "");
    }
    else
    {
      $tpl->set_var("ArticlesScrollerNextSwitch", "_");
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("ArticlesScrollerPrevSwitch", "");
      $tpl->set_var("ArticlesCurrentPage", $iPage);
      $tpl->parse("ArticlesScroller", false);
    }
  }
  $tpl->set_var("ArticlesNoRecords", "");
  $tpl->parse("FormArticles", false);
  
}

?>