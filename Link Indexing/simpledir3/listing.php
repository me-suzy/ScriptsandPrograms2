<?php

# SimpleDir 3.0
# Copyright 2003-2004 Tara, http://gurukitty.com/star. All rights reserved.
# Released June 19, 2004

# SimpleDir 3.0 is linkware and can be used or modified as long all notes on the SimpleDir 3.0 files remain intact, unaltered, and a link is placed on all pages used by the SimpleDir 3.0 script to http://gurukitty.com/star so others can find out about this script as well. You may modify this script to suit your wishes, but please follow my requests and do not distribute it.

# All I ask of you is the above and to not sell or distribute SimpleDir 3.0 without my permission.
# All risks of using SimpleDir 3.0 are the user's responsibility, not the creator of the script.
# For further information and updates, visit the SimpleDir 3.0 site at http://gurukitty.com/star.
# Thank you for downloading SimpleDir 3.0.

require("config.php");
require("common.php");

// Setup Smarty
require_once('smarty/Smarty.class.php');
$smarty = new Smarty;
// Assign general variables
$smarty->assign("headfile",$headfile);
$smarty->assign("footfile",$footfile);
$smarty->assign("sdversion",$sdversion);
$smarty->assign("self",$self);
$smarty->assign("sitename",$sitename);
$smarty->assign("adminname",$adminname);
$smarty->assign("adminemail",$adminemail);
$smarty->assign("siteurl",$siteurl);

// List categories, link to listings
if ($query == "")
{
  // Display cats header
  $smarty->display('cats_header.tpl');

  echo "<p>";
  // Query table
  $select = mysql_query ("SELECT * FROM $tbcats ORDER BY catname");
  if (mysql_num_rows($select) == 0)
    echo "There are no categories.";
  else
  {
    do
    {
      if ($row['catname'] != "")
      {
        // Calculate number of links per category
        $catID = $row['catID'];
        $catname = stripslashes($row['catname']);
        $catDesc = stripslashes($row['catDesc']);
        $query1 = "SELECT linkID FROM $tblinks WHERE relCatID = $catID AND linkstatus = '1'";
        $result1  = mysql_query($query1);
        $catnumlinks = mysql_numrows($result1);
        // Assign category variables
        $smarty->assign("catID",$catID);
        $smarty->assign("catname",$catname);
        $smarty->assign("catnumlinks",$catnumlinks);
        $smarty->assign("catDesc",$catDesc);
        // Output
        if ($catnumlinks != 0)
        {
          if ($catnumlinks > 1)
            $smarty->display('cats_multlinks.tpl');
          else
            $smarty->display('cats_1link.tpl');
        }
      }
    }
    while($row = mysql_fetch_array($select));
  }
  echo "</p>";
  // Display cats footer
  $smarty->display('cats_footer.tpl');
}

// Listing of links by category
else
{
  // Set query string variables
  $cat = $_REQUEST['cat'];
  $page = $_REQUEST['page'];
  $num_per_page = $numperpage;
  // Select category name and output it
  $select = mysql_query("SELECT catname, catDesc FROM $tbcats WHERE catID = '$cat'");
  if (mysql_num_rows($select) == 0)
  {
    echo "<p>This category does not exist.</p>";
    $catexist = "N";
  }
  else
  {
    $catexist = "Y";
    do
    {
      if ($row['catname'] != "")
      {
        $catname = stripslashes($row['catname']);
        $catDesc = stripslashes($row['catDesc']);
        $smarty->assign("catname",$catname);
        $smarty->assign("catDesc",$catDesc);
      }
    }
    while($row = mysql_fetch_array($select));
  }
  // Display links header
  $smarty->display('links_header.tpl');
  if ($catexist == "Y")
  {
    // Find total rows in $tblinks
    $selectall = mysql_query("SELECT linkID FROM $tblinks WHERE linkstatus = '1' AND relCatID = '$cat'");
    $total_links = mysql_num_rows($selectall);
    if ($total_links == 0)
      echo "<p>There are no links in this category.</p>";
    // Find total pages
    $total_pages = ceil($total_links / $num_per_page);
    // Set default page #
    if (!isset($_REQUEST['page']))
      $page = 0;
    // Set start limit
    $start_limit = $num_per_page * $page;
  }
  // If descriptions disabled
  if ($allowdesc == "N" && $catexist == "Y" && $total_links != 0)
  {
    // Query $tblinks
    $select = mysql_query("SELECT * FROM $tblinks WHERE linkstatus = '1' AND relCatID = '$cat' ORDER BY linkname ASC LIMIT $start_limit, $num_per_page");
    do
    {
      if ($row['linkname'] != "")
      {
        // Set variables
        $linkname = stripslashes($row['linkname']);
        $linkurl = $row['linkurl'];
        if($linkname == "")
          $linkname = $linkurl;
        $ownername = $row['ownername'];
        $owneremail = $row['owneremail'];
        $linknotes = $row['linknotes'];
        // Assign Smarty variables
        $smarty->assign("linkname",$linkname);
        $smarty->assign("linkurl",$linkurl);
        $smarty->assign("ownername",$ownername);
        $smarty->assign("owneremail",$owneremail);
        $smarty->assign("linknotes",$linknotes);
        // Output
        $smarty->display('links_nodesc.tpl');
      }
    }
    while($row = mysql_fetch_array($select));
  }
  // If descriptions enabled
  if ($allowdesc == "Y" && $catexist == "Y" && $total_links != 0)
  {
    // Query table
    $select = mysql_query("SELECT * FROM $tblinks WHERE linkstatus = '1' AND relCatID = '$cat' ORDER BY linkname ASC LIMIT $start_limit, $num_per_page");
    do
    {
      if ($row['linkname'] != "")
      {
        // Set variables
        $linkname = stripslashes($row['linkname']);
        $linkurl = $row['linkurl'];
        $linkdesc = stripslashes($row['linkdesc']);
        $ownername = $row['ownername'];
        $owneremail = $row['owneremail'];
        $linknotes = $row['linknotes'];
        // Assign Smarty variables
        $smarty->assign("linkname",$linkname);
        $smarty->assign("linkurl",$linkurl);
        $smarty->assign("linkdesc",$linkdesc);
        $smarty->assign("ownername",$ownername);
        $smarty->assign("owneremail",$owneremail);
        $smarty->assign("linknotes",$linknotes);
        // Output
        if($linkdesc != "")
          $smarty->display('links_desc.tpl');
        elseif($linkdesc == "")
          $smarty->display('links_nodesc.tpl');
      }
    }
    while($row = mysql_fetch_array($select));
  }
  // Output page navigation
  if ($catexist == "Y" && $total_pages != 1 && $total_links != 0)
  {
    $nextstart = $start_limit + $num_per_page;
    echo "<p>";
    // Previous page
    if ($page > 0)
    {
      $prevpage = $page - 1;
      echo '<a href="'.$self.'?cat='.$cat.'&page='.$prevpage.'"><< Back</a> ';
    }
    // All pages
    for ($i = 0; $i < $total_pages; $i++)
    {
      $page_no = $i + 1;
      echo '<a href="'.$self.'?cat='.$cat.'&page='.$i.'">'.$page_no.'</a> ';
    }
    // Next page
    if ($nextstart < $total_links)
    {
      $nextpage = $page + 1;
      echo '<a href="'.$self.'?cat='.$cat.'&page='.$nextpage.'">Next >></a>';
    }
    echo "</p>";
  }
  // Display links footer
  $smarty->display('links_footer.tpl');
}

?>