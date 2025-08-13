<?php
/*********************************************************************************
 *       Filename: AdminNews.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "AdminNews.php";



check_security(2);

$tpl = new Template($app_path);
$tpl->load_file("AdminNews.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);

Header_show();
Footer_show();
News_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************



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
  

  $sDirection = "";
  $sSortParams = "";
  
  // Build ORDER statement
  $sOrder = " order by n.news_id Asc";
  $iSort = get_param("FormNews_Sorting");
  $iSorted = get_param("FormNews_Sorted");
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
      $sSortParams = "FormNews_Sorting=" . $iSort . "&FormNews_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "FormNews_Sorting=" . $iSort . "&FormNews_Sorted=" . "&";
    }
    
    if ($iSort == 1) $sOrder = " order by n.news_html" . $sDirection;
  }

  // Build full SQL statement
  
  $sSQL = "select n.news_html as n_news_html, " . 
    "n.news_id as n_news_id " . 
    " from news n ";
  
  $sSQL .= $sWhere . $sOrder;
  $tpl->set_var("FormAction", "EditNews.php");
  $tpl->set_var("SortParams", $sSortParams);

  // Execute SQL statement
  $db->query($sSQL);
  
  // Select current page
  $iPage = get_param("FormNews_Page");
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
			$fldnews_html = $db->f("n_news_html");
      $fldnews_id= "Edit";
      $tpl->set_var("news_id", tohtml($fldnews_id));
      $tpl->set_var("news_id_URLLink", "EditNews.php");
      $tpl->set_var("Prm_news_id", tourl($db->f("n_news_id"))); 
      $tpl->set_var("news_html", tohtml($fldnews_html));
      $tpl->parse("DListNews", true);
      $iCounter++;
    } while($iCounter < $RecordsPerPage &&$db->next_record());
  }
  else
  {
    // No Records in DB
    $tpl->set_var("DListNews", "");
    $tpl->parse("NewsNoRecords", false);
    $tpl->set_var("NewsScroller", "");
    $tpl->parse("FormNews", false);
    return;
  }
  
  // Parse scroller
  if(@$db->next_record())
  {
    if ($iPage == 1)
    {
      $tpl->set_var("NewsScrollerPrevSwitch", "_");
    }
    else
    {
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("NewsScrollerPrevSwitch", "");
    }
    $tpl->set_var("NextPage", ($iPage + 1));
    $tpl->set_var("NewsScrollerNextSwitch", "");
    $tpl->set_var("NewsCurrentPage", $iPage);
    $tpl->parse("NewsScroller", false);
  }
  else
  {
    if ($iPage == 1)
    {
      $tpl->set_var("NewsScroller", "");
    }
    else
    {
      $tpl->set_var("NewsScrollerNextSwitch", "_");
      $tpl->set_var("PrevPage", ($iPage - 1));
      $tpl->set_var("NewsScrollerPrevSwitch", "");
      $tpl->set_var("NewsCurrentPage", $iPage);
      $tpl->parse("NewsScroller", false);
    }
  }
  $tpl->set_var("NewsNoRecords", "");
  $tpl->parse("FormNews", false);
  
}

?>