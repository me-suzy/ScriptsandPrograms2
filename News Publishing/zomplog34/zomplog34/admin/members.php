<?php
ob_start();
/* Written by Gerben Schmidt, http://scripts.zomp.nl */

include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

$user = loadUser($_SESSION['login'],$link,$table_users);

if($_GET[message]){ 
displayMessage($_GET[message]);
  }
  ?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
      <tr>
        <td valign="top" class="text"><h1><? echo "$lang_members_area"; ?></h1></td>
      </tr>
      <tr>
        <td valign="top" class="text">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" class="text"><table width="94%"  border="0" cellspacing="0" cellpadding="0" class="text">
          <tr>
            <td colspan="3" valign="top"><? echo "$settings[admin_welcome]"; ?> </td>
            </tr>
          <tr>
            <td colspan="3" valign="top">&nbsp;</td>
            </tr>
			
			
          <tr>
            <td width="15%" rowspan="2" valign="top"><img src="icons/stats.jpg" width="33" height="40"></td>
            <td width="41%" valign="top"><span class="title"><? echo "$lang_site_statistics"; ?></span>
			</td>
            <td width="44%" valign="top"><span class="title"><? echo "$lang_your_statistics"; ?></span></td>
          </tr>
          <tr>
            <td valign="top"><? 	
	
	$query = "SELECT * FROM $table ORDER BY id DESC";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	$numposts = mysql_num_rows($result);
	
	$query = "SELECT * FROM $table_pages ORDER BY id DESC";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	$numpages = mysql_num_rows($result);
	
	$query = "SELECT * FROM $table_cat ORDER BY id DESC";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	$numcat = mysql_num_rows($result);
	
	$query = "SELECT * FROM $table_users ORDER BY id DESC";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	$numusers = mysql_num_rows($result);
	
	$query = "SELECT * FROM $table_comments ORDER BY id DESC";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	$numcomments = mysql_num_rows($result);
	
	echo "$lang_total_number_posts: $numposts<br />";
	echo "$lang_total_number_pages: $numpages<br />";
	echo "$lang_total_number_users: $numusers<br />";
	if($settings[categories]){
	echo "$lang_total_number_categories: $numcat<br />";
	}
	if($settings[comments]){
	echo "$lang_total_number_comments: $numcomments<br />";
	}
	
	?></td>
            <td valign="top"><? 	
	
	$query = "SELECT * FROM $table WHERE username = '$user[login]' ORDER BY id DESC";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	$numposts = mysql_num_rows($result);
	
	$query = "SELECT * FROM $table WHERE username = '$user[login]' ORDER BY id DESC LIMIT 1";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	$lastpost = mysql_fetch_array($result);
	
	
	echo "$lang_total_number_posts: $numposts<br />";
	if($numposts){

// converting timestamp to current user-formatted date
$q = mysql_query("SELECT date, UNIX_TIMESTAMP(date) AS timestamp FROM $table WHERE id = '$lastpost[id]'");
$row = mysql_fetch_array($q);

// then use PHP's date() function :
$postdate = date("$userdate", $row['timestamp']);

	echo "$lang_lastpost: <br /> <a href='editor.php?id=$lastpost[id]'>$lastpost[title]</a> $lang_on $postdate<br />";
	}
	?></td>
          </tr>
		  
		  <?
			if($settings[rss]){
			?>
          <tr>
            <td valign="top">&nbsp;</td>
            <td colspan="2" valign="top">&nbsp;</td>
          </tr>
			<?
			}
			?>
        </table></td>
      </tr>
      <tr>
        <td valign="top" class="text">&nbsp;</td>
      </tr>

    </table></td>
    <td width="20%" valign="top"><?php include('menu.php'); ?></td>
  </tr>
</table>
<?
ob_end_flush();
include('footer.php');

?>
