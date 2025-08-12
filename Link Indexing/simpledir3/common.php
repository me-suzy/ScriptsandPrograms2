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

/**********VARIABLES**********/

/* Query MySQL database */
$queryA = "SELECT * FROM $tbconfig WHERE id = '1'";
$resultA = mysql_query($queryA) or die(mysql_error());
$cfg = mysql_fetch_array($resultA) or die(mysql_error());

/* Set admin options to variables */
$dirpass = $cfg['dirpass'];
$sitename = stripslashes($cfg['sitename']);
$adminname = stripslashes($cfg['adminname']);
$visitoradd = $cfg['visitoradd'];
$pend = $cfg['pend'];
$allowdesc = $cfg['allowdesc'];
$adminemail = $cfg['adminemail'];
$emailnotify = $cfg['emailnotify'];
$siteurl = $cfg['siteurl'];
$numperpage = $cfg['numperpage'];
$usemanager = $cfg['usemanager'];
$sitepath = $cfg['sitepath'];
$header = $cfg['header'];
$footer = $cfg['footer'];
$catselect = $cfg['catselect'];
$headfile = $sitepath.'/'.$header;
$footfile = $sitepath.'/'.$footer;

/* If options are not set, set them to the default value */
if(($emailnotify == "") || ($adminemail == ""))
  $emailnotify = "N";
if(($numperpage == "") || ($numperpage == 0))
  $numperpage = 25;
if(($usemanager == "") || ($sitepath == ""))
  $usemanager = "N";
if($header == "")
  $header = $headerv;
if($footer == "")
  $footer = $footerv;

/* Misc. variables */
$self = $_SERVER['PHP_SELF'];
$query = $_SERVER['QUERY_STRING'];
$sdversion = "3.0";

/******************************************************************************/

/**********FUNCTIONS**********/

function admin_legend()
// Displays the legend for the editing of links
{
  echo "<p><u>Legend:</u><br>";
  echo "<b>E</b> - edit<br>";
  echo "<b>M</b> - email<br>";
  echo "<b>X</b> - delete";
  echo "</p>";
}

/******************************************************************************/

function admin_listlinks()
// Displays a link
{
  global $linkID;
  global $linkname;
  echo '<font size="+1"><a href="admin.php?action=editlinks&id='.$linkID.'">E</a> <a href="admin.php?action=email&id='.$linkID.'">M</a> <a href="admin.php?action=dellinks&id='.$linkID.'">X</a></font> - '.$linkID.'. '.$linkname.'<BR>';
}

/******************************************************************************/

function changeCats($action)
// Displays the list of categories
{
  global $tbcats;
  
  if ($action == "editcats")
    echo "<p align=\"center\"><u>Edit A Category</u></p>";
  elseif ($action == "delcats")
    echo "<p align=\"center\"><u>Delete A Category</u></p>";
    
  $selectCats = mysql_query ("SELECT * FROM $tbcats ORDER BY catID ASC");
  if(mysql_num_rows($selectCats) == 0)
    echo "<p>There are no categories.</p>";
  else
  {
    echo "<ol>\n";
    do {
     if ($cats['catname'] != "")
      {
        $catname = stripslashes($cats['catname']);
        $catDesc = stripslashes($cats['catDesc']);
        if ($cats['catDesc'] != "")
          echo '<li><a href="admin.php?action='.$action.'&id='.$cats['catID'].'">'.$catname.'</a> - '.$catDesc.'<br>';
        else
          echo '<li><a href="admin.php?action='.$action.'&id='.$cats['catID'].'">'.$catname.'</a><br>';
      }
    }
    while ($cats = mysql_fetch_array($selectCats));
    echo "</ol>\n";
  }
}

/******************************************************************************/

function displayLogin()
// Displays the pop-up login form
{
  header("WWW-Authenticate: Basic realm=\"SimpleDir Admin\"");
  header("HTTP/1.0 401 Unauthorized");
  echo "<p><b>Authentication Failure</b></p>\n";
  echo "The username and password provided did not work. Please refresh the page and try again.";
  exit;
}

/******************************************************************************/

function editTPLs($filename)
// Displays the contents of a template to edit
{
  extract($GLOBALS);
  $filepath = $sitepath.'/templates/';
  $thefile = $sitepath.'/templates/'.$filename;
  if((is_file($thefile)) && (is_readable($thefile)) && (is_writable($thefile)))
  {
    $fileh = fopen($thefile, "r");
    $read = fread($fileh, filesize($thefile));
    $contents = htmlspecialchars($read);
    fclose($fileh);
    echo $contents;
  }
  else
    echo "<p>File <b>".$filename."</b> in ".$filepath." is either not CHMODed correctly or does not exist. Please check to make sure that it exists, is in the correct folder, and is CHMODed to 666 or 766.</p>";
}

/******************************************************************************/

function printCats($catType)
// Prints out the categories in either a drop down menu or radio buttons style
{
  global $tbcats, $catselect;
  
  $select = mysql_query ("SELECT catname, catID FROM $tbcats ORDER BY catname");
  if (mysql_num_rows($select) == 0)
    echo "There are no categories.";
  else
  {
    if ($catselect == 'D')
      echo '<select name="'.$catType.'">'."\n";
    do
    {
      $catname = stripslashes($row['catname']);
      if (($row['catname'] != "") && ($catselect == 'R'))
        echo '<input type="radio" name="'.$catType.'" value="'.$row['catID'].'"> '.$catname.'<br>';
      if (($row['catname'] != "") && ($catselect == 'D'))
        echo '<option value="'.$row['catID'].'">'.$catname.'</option>\n';
    }
    while($row = mysql_fetch_array($select));
    if ($catselect == 'D')
      echo "</select>\n";
  }
}

/******************************************************************************/

function saveTPLS($filename,$textName,$first,$last)
// Saves a template file
{
  extract($GLOBALS);
  // Set variables
  $filepath = $sitepath.'/templates/';
  $thefile = $sitepath.'/templates/'.$filename;
  if ($first == 'Y' && $last == 'Y')
    $both = 'Y';
  // If writable, write to file
  if(is_writable($thefile))
  {
    $contents = stripslashes($textName);
    $fileh = fopen($thefile, "w");
    fwrite($fileh, $contents);
    fclose($fileh);
    // print success message
    if ($both == 'Y')
      echo "<p>Template <b>".$filename."</b> has been saved.</p><p><a href=\"admin.php?do=tplslist\">Return to the templates listing</a></p>\n";
    else
    {
      if ($first == 'Y')
        echo "<p>Templates(s)";
      if ($last == 'N')
        echo " <b>".$filename."</b>,";
      elseif ($last == 'Y')
        echo " and <b>".$filename.'</b> have been saved.</p><p><a href="admin.php?do=tplslist">Return to the templates listing</a></p>'."\n";
    }
  }
  else
    echo "<p>File <b>".$filename."</b> in ".$filepath.' is not writable. Please make sure that it is CHMODed to 766 or 666.</p><p><a href="admin.php?do=tplslist">Return to the templates listing</a></p>'."\n";
}

/******************************************************************************/

function snippetRandLink(&$linkname, &$linkurl)
// Selects a random link from table $tblinks
{
  global $tblinks;
  
  $query = mysql_query("SELECT linkname, linkurl FROM $tblinks WHERE linkstatus = '1' ORDER BY rand() LIMIT 0,1");
  $row = mysql_fetch_array($query);
  $linkname = stripslashes($row['linkname']);
  $linkurl = $row['linkurl'];
}

/******************************************************************************/

function snippetRandLinkCat($cat, &$linkname, &$linkurl)
// Selects a random link from a specified category
{
  global $tblinks;
  
  $query1 = "SELECT linkID FROM $tblinks WHERE relCatID = $cat AND linkstatus = '1'";
  $result1 = mysql_query($query1);
  $catnumlinks = mysql_numrows($result1);
  if ($catnumlinks == 0)
    snippetRandLink($linkname,$linkurl);
  elseif ($catnumlinks > 0)
  {
    $query2 = mysql_query("SELECT linkname, linkurl FROM $tblinks WHERE relCatID = $cat AND linkstatus = '1' ORDER BY rand() LIMIT 0,1");
    $row = mysql_fetch_array($query2);
    $linkname = stripslashes($row['linkname']);
    $linkurl = $row['linkurl'];
  }
}

/******************************************************************************/

function snippetStatus($status)     // $status == 0 or 1
// Calculates the number of pending links
{
  global $tblinks;
  
  $query = "SELECT linkID FROM $tblinks WHERE linkstatus = '$status'";
  $result = mysql_query($query);
  $pendnum = mysql_numrows($result);
  
  return $pendnum;
}

/******************************************************************************/

function snippetTotal($type)        // $type == 'L' (links) || 'C' (categories)
// Calculates the total number of links
{
  global $tblinks, $tbcats;
  
  if ($type == 'L')
    $query = "SELECT linkID FROM $tblinks";
  elseif ($type == 'C')
    $query = "SELECT catID FROM $tbcats";
  $result = mysql_query($query);
  $num = mysql_numrows($result);
  
  return $num;
}

?>