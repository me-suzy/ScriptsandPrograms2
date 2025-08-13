<?php
/*********************************************************************************
 *       Filename: Default.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "Default.php";




$tpl = new Template($app_path);
$tpl->load_file("Default.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);

Header_show();
Footer_show();
Articles_show();
Search_show();
Links_show();
Events_show();
News_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************



function Articles_show()
{

  
  global $tpl;
  global $db;
  global $sArticlesErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $HasParam = false;

  
  $tpl->set_var("TransitParams", "");
  $tpl->set_var("FormParams", "");
  // Build WHERE statement
  

  $sDirection = "";
  $sSortParams = "";
  
  // Build ORDER statement
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
    if ($iSort == 2) $sOrder = " order by a.article_desc" . $sDirection;
  }

  // Build full SQL statement
  
  $sSQL = "select a.article_desc as a_article_desc, " . 
    "a.article_id as a_article_id, " . 
    "a.article_title as a_article_title " . 
    " from articles a ";
  
  $sSQL .= $sWhere . $sOrder;
  $tpl->set_var("SortParams", $sSortParams);

  // Execute SQL statement
  $db->query($sSQL);
  
  // Select current page
  $iPage = get_param("FormArticles_Page");
  if(!strlen($iPage)) $iPage = 1;
  $RecordsPerPage = 3;
  if(($iPage - 1) * $RecordsPerPage != 0)
    $db->seek(($iPage - 1) * $RecordsPerPage);
  $iCounter = 0;

  if($db->next_record())
  {  
    // Show main table based on SQL query
    do
    {
			$fldarticle_id = $db->f("a_article_id");
			$fldarticle_title = $db->f("a_article_title");
			$fldarticle_desc = $db->f("a_article_desc");
    $tpl->set_var("article_id", tohtml($fldarticle_id));
      $tpl->set_var("article_title", tohtml($fldarticle_title));
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



function Links_show()
{

  
  global $tpl;
  global $db;
  global $sLinksErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $HasParam = false;

  
  $tpl->set_var("TransitParams", "");
  $tpl->set_var("FormParams", "");
  // Build WHERE statement
  
  $sWhere = " WHERE approved=1";
  

  $sDirection = "";
  $sSortParams = "";
  
  // Build ORDER statement
  $iSort = get_param("FormLinks_Sorting");
  $iSorted = get_param("FormLinks_Sorted");
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
      $sSortParams = "FormLinks_Sorting=" . $iSort . "&FormLinks_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "FormLinks_Sorting=" . $iSort . "&FormLinks_Sorted=" . "&";
    }
    
    if ($iSort == 1) $sOrder = " order by l.link_name" . $sDirection;
  }

  // Build full SQL statement
  
  $sSQL = "select l.link_name as l_link_name, " . 
    "l.link_url as l_link_url " . 
    " from links l ";
  
  $sSQL .= $sWhere . $sOrder;
  $tpl->set_var("SortParams", $sSortParams);

  // Execute SQL statement
  $db->query($sSQL);
  
  // Select current page
  $iPage = get_param("FormLinks_Page");
  if(!strlen($iPage)) $iPage = 1;
  $RecordsPerPage = 10;
  if(($iPage - 1) * $RecordsPerPage != 0)
    $db->seek(($iPage - 1) * $RecordsPerPage);
  $iCounter = 0;

  if($db->next_record())
  {  
    // Show main table based on SQL query
    do
    {
			$fldlink_name = $db->f("l_link_name");
      $tpl->set_var("link_name", tohtml($fldlink_name));
      $tpl->set_var("link_name_URLLink", $db->f("l_link_url"));
      $tpl->parse("DListLinks", true);
      $iCounter++;
    } while($iCounter < $RecordsPerPage &&$db->next_record());
  }
  else
  {
    // No Records in DB
    $tpl->set_var("DListLinks", "");
    $tpl->parse("LinksNoRecords", false);
    $tpl->set_var("LinksScroller", "");
    $tpl->parse("FormLinks", false);
    return;
  }
  
  // Parse scroller
  if(@$db->next_record())
  {
    if ($iPage == 1)
    {
      $tpl->set_var("LinksScrollerPrevSwitch", "_");
    }
    else
    {
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("LinksScrollerPrevSwitch", "");
    }
    $tpl->set_var("NextPage", ($iPage + 1));
    $tpl->set_var("LinksScrollerNextSwitch", "");
    $tpl->set_var("LinksCurrentPage", $iPage);
    $tpl->parse("LinksScroller", false);
  }
  else
  {
    if ($iPage == 1)
    {
      $tpl->set_var("LinksScroller", "");
    }
    else
    {
      $tpl->set_var("LinksScrollerNextSwitch", "_");
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("LinksScrollerPrevSwitch", "");
      $tpl->set_var("LinksCurrentPage", $iPage);
      $tpl->parse("LinksScroller", false);
    }
  }
  $tpl->set_var("LinksNoRecords", "");
  $tpl->parse("FormLinks", false);
  
}



function Events_show()
{

  
  global $tpl;
  global $db;
  global $sEventsErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $HasParam = false;

  
  $tpl->set_var("TransitParams", "");
  $tpl->set_var("FormParams", "");
  // Build WHERE statement
  

  $sDirection = "";
  $sSortParams = "";
  

  // Build full SQL statement
  
  $sSQL = "select e.event_name as e_event_name " . 
    " from events e ";
  
  $sSQL .= $sWhere . $sOrder;

  // Execute SQL statement
  $db->query($sSQL);
  
  // Select current page
  $iPage = get_param("FormEvents_Page");
  if(!strlen($iPage)) $iPage = 1;
  $RecordsPerPage = 5;
  if(($iPage - 1) * $RecordsPerPage != 0)
    $db->seek(($iPage - 1) * $RecordsPerPage);
  $iCounter = 0;

  if($db->next_record())
  {  
    // Show main table based on SQL query
    do
    {
			$fldevent_name = $db->f("e_event_name");
      $tpl->set_var("event_name", tohtml($fldevent_name));
      $tpl->set_var("event_name_URLLink", "Events.php");
      $tpl->parse("DListEvents", true);
      $iCounter++;
    } while($iCounter < $RecordsPerPage &&$db->next_record());
  }
  else
  {
    // No Records in DB
    $tpl->set_var("DListEvents", "");
    $tpl->parse("EventsNoRecords", false);
    $tpl->set_var("EventsScroller", "");
    $tpl->parse("FormEvents", false);
    return;
  }
  
  // Parse scroller
  if(@$db->next_record())
  {
    if ($iPage == 1)
    {
      $tpl->set_var("EventsScrollerPrevSwitch", "_");
    }
    else
    {
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("EventsScrollerPrevSwitch", "");
    }
    $tpl->set_var("NextPage", ($iPage + 1));
    $tpl->set_var("EventsScrollerNextSwitch", "");
    $tpl->set_var("EventsCurrentPage", $iPage);
    $tpl->parse("EventsScroller", false);
  }
  else
  {
    if ($iPage == 1)
    {
      $tpl->set_var("EventsScroller", "");
    }
    else
    {
      $tpl->set_var("EventsScrollerNextSwitch", "_");
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("EventsScrollerPrevSwitch", "");
      $tpl->set_var("EventsCurrentPage", $iPage);
      $tpl->parse("EventsScroller", false);
    }
  }
  $tpl->set_var("EventsNoRecords", "");
  $tpl->parse("FormEvents", false);
  
}



function News_show()
{

  
  global $tpl;
  global $db;
  global $sNewsErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $HasParam = false;

  
  $tpl->set_var("TransitParams", "");
  $tpl->set_var("FormParams", "");
  // Build WHERE statement
  
  $sWhere = " WHERE news_id=1";
  

  $sDirection = "";
  $sSortParams = "";
  

  // Build full SQL statement
  
  $sSQL = "select n.news_html as n_news_html " . 
    " from news n ";
  
  $sSQL .= $sWhere . $sOrder;

  // Execute SQL statement
  $db->query($sSQL);
  

  if($db->next_record())
  {  
    // Show main table based on SQL query
    do
    {
			$fldnews_html = $db->f("n_news_html");
      $tpl->set_var("news_html", $fldnews_html);
      $tpl->parse("DListNews", true);
    } while($db->next_record());
  }
  else
  {
    // No Records in DB
    $tpl->set_var("DListNews", "");
    $tpl->parse("NewsNoRecords", false);
    $tpl->parse("FormNews", false);
    return;
  }
  
  $tpl->set_var("NewsNoRecords", "");
  $tpl->parse("FormNews", false);
  
}

?>