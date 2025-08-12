<?
include_once("admin/functions.php");
include('admin/config.php');
include('admin/session.php');
include('admin/loadsettings.php');
include("language/$settings[language].php");
include("skins/$settings[skin]/header.php");


function mainPage(){
global $mainquery, $query, $link, $table, $table_cat, $table_users, $table_comments, $entry, $cat,
$category, $comments, $numcomments, $settings, $lang_number_comments, $lang_read_more, $lang_listen, $lang_view,
$MAANDEN, $jaar, $maand, $row, $user, $userdate;

?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign='top'>
	
	<div class='title'>Archive</div> <? if($_GET[userid]){ 
		$query = "SELECT * FROM $table_users WHERE id = '$_GET[userid]'";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$user = mysql_fetch_array($result);
		
		echo "of $user[login]";
		}
		
	
	
	$MAANDEN = array ('01' => "January", '02' => "February", '03' => "March", '04' => "April", '05' => "May", '06' => "June", '07'  => "July", '08' => "August", '09' => "September", '10' => "October", '11' => "November", '12' => "December");


	$jaar = 0;
	$maand = 0;
	if($_GET[userid]){
	$mainquery = mysql_query ("SELECT * FROM $table WHERE userid = '$_GET[userid]' AND date LIKE '%$_GET[year]%' ORDER BY date DESC");
	}
	else
	{
	$mainquery = mysql_query ("SELECT * FROM $table ORDER BY date DESC");
	}
	while ($row = mysql_fetch_array ($mainquery))
	{
	// nasty gerben hack to split up unix timestamp
	  $row['jaar'] = substr($row['date'], 0, 4);
	  $row['maand'] = substr($row['date'], 4, 2);
	  $row['dag'] = substr($row['date'], 6, 2);
 	 if ($jaar != $row['jaar'])
 	 {
 	   $jaar = $row['jaar'];
 	   echo "<div class='title'><br/>$jaar<br/></div>";
	  }
 	 if($maand != $row['maand'])
 	 {
 	   $maand = $row['maand'];
  	  echo "<div class='title'><br/>$MAANDEN[$maand]<br/></div></div>";
  	}
$myquery = mysql_query ("SELECT * FROM $table_comments WHERE entry_id = $row[id]");
$numcomments = mysql_num_rows ($myquery);

$query = "SELECT * FROM $table_users WHERE login = '$row[username]'";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$user = mysql_fetch_array($result);

$q = mysql_query("SELECT date, UNIX_TIMESTAMP(date) AS timestamp FROM $table WHERE id = '$row[id]'");
$myrow = mysql_fetch_array($q);

// then use PHP's date() function :
$postdate = date("$userdate", $myrow['timestamp']);

  	echo "<div class='text'>$postdate: <a href='detail.php?id=$row[id]'>$row[title]</a> - $user[login]<br/>\n</div>";
	}
	
	?></td>
  </tr>
</table>
<?
}
include("skins/$settings[skin]/mainpage.php");
include("skins/$settings[skin]/footer.php");
?>