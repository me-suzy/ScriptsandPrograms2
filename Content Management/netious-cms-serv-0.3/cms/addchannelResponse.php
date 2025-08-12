<?
require("../db.php");
require("include.php");
DBinfo();

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");



$SUID=f_ip2dec($REMOTE_ADDR);
if (!session_id($SUID))
session_start();

$username=$_SESSION['uname'];
$password=$_SESSION['pass'];

$result=mysql_query("SELECT AdminId FROM mycmsadmin WHERE username='$username' and password='".sha1($password)."'");
$row=mysql_fetch_row($result);
$num_rows = mysql_num_rows($result);
$id=$row[0];



if ($_SESSION['signed_in']!='indeed' || $num_rows!=1 || $id!=1){
Header( "Location: index.php?action=2");
}else{


/* In case the tables do not exist... */
mysql_query("CREATE TABLE IF NOT EXISTS `rsschannel` (
  `RssId` int(3) NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `language` varchar(20) NOT NULL default '',
  `copyright` varchar(255) NOT NULL default '',
  `managingEditor` varchar(255) NOT NULL default '',
  `webMaster` varchar(255) NOT NULL default '',
  `pubDate` varchar(255) NOT NULL default '',
  `lastBuildDate` varchar(255) NOT NULL default '',
  `ttl` varchar(11) NOT NULL default '',
  `img_url` varchar(255) NOT NULL default '',
  `img_title` varchar(255) NOT NULL default '',
  `img_link` varchar(255) NOT NULL default '',
  `img_width` varchar(4) NOT NULL default '',
  `img_height` varchar(4) NOT NULL default '',
  `img_description` text NOT NULL,
  UNIQUE KEY `RssId` (`RssId`)
) TYPE=MyISAM AUTO_INCREMENT=1") or die("Something went wrong (create rsschannel:".mysql_error()); 

mysql_query("CREATE TABLE IF NOT EXISTS `rssitem` (
  `ItemId` int(5) NOT NULL auto_increment,
  `RefId` int(4) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `author` varchar(255) NOT NULL default '',
  `pubDate` varchar(50) NOT NULL default '',
  UNIQUE KEY `ItemId` (`ItemId`)
) TYPE=MyISAM AUTO_INCREMENT=1") or die("Something went wrong (create rssitem):".mysql_error());

/* And the rss directory! */

if (!file_exists("../rss"))
	{mkdir("../rss",0775);
	chmod("../rss",0775);}



if (!isset($name)) {$name="";} 
else {$result=mysql_query("SELECT * FROM rsschannel WHERE Name='$name'");
	$num_names=mysql_num_rows($result);}
if (!isset($chnltitle)) $chnltitle="";
if (!isset($link)) $link="";
if (!isset($chnldescription)) $chnldescription="";
if (!isset($language)) $language="en";
if (!isset($copyright)) $copyright="";
if (!isset($managingEditor)) $managingEditor="";
if (!isset($webMaster)) $webMaster="";
if (!isset($ttl)) $ttl="60";
if (!isset($img_width)) $img_width="";
if (!isset($img_height)) $img_height="";
if (!isset($img_description)) $img_description="";




/* Checks whether the * - fields were filled */

if ($name=="") {Header("Location:addchannel.php?action=1&chnltitle=$chnltitle&link=$link&chnldescription=$chnldescription&language=$language&copyright=$copyright&managingEditor=$managingEditor&webMaster=$webMaster&img_width=$img_width&img_height=$img_height&img_description=$img_description&ttl=$ttl");}
elseif ($chnltitle=="") {Header("Location:addchannel.php?action=2&name=$name&link=$link&chnldescription=$chnldescription&language=$language&copyright=$copyright&managingEditor=$managingEditor&webMaster=$webMaster&img_width=$img_width&img_height=$img_height&img_description=$img_description&ttl=$ttl");}
elseif ($chnldescription=="") {Header("Location:addchannel.php?action=3&name=$name&link=$link&chnltitle=$chnltitle&language=$language&copyright=$copyright&managingEditor=$managingEditor&webMaster=$webMaster&img_width=$img_width&img_height=$img_height&img_description=$img_description&ttl=$ttl");}
elseif ($link=="") {Header("Location:addchannel.php?action=4&name=$name&chnltitle=$chnltitle&chnldescription=$chnldescription&language=$language&copyright=$copyright&managingEditor=$managingEditor&webMaster=$webMaster&img_width=$img_width&img_height=$img_height&img_description=$img_description&ttl=$ttl");}
elseif ($num_names!=0) {Header("Location:addchannel.php?action=5&name=$name&link=$link&chnltitle=$chnltitle&chnldescription=$chnldescription&language=$language&copyright=$copyright&managingEditor=$managingEditor&webMaster=$webMaster&img_width=$img_width&img_height=$img_height&img_description=$img_description&ttl=$ttl");}
else {

/* prepare the data to be inserted into the DB and save the image, if it is uploaded */


/* Read out the date */
$pubDate=date("r");
$lastBuildDate=date("r");

/* create the channel dir */

$directory="../rss/$name";
if (!file_exists($directory))
	{mkdir($directory,0777);
	chmod($directory,0777);
	}



$image=$_FILES['img_file']['tmp_name'];
$image_name=$_FILES['img_file']['name'];

if ($image!="")
	{$img_location="$directory/$image_name";
	copy ($image,$img_location);
	
	$img_url="$thisurl/rss/$name/$image_name";
	$img_title=$chnltitle;
	$img_link=$link;
	} else {$img_url="";
	$img_width="";
	$img_height="";	
	$img_description="";
	$img_title="";
	$img_link="";}

/* insert into... */



mysql_query("INSERT INTO rsschannel VALUES ('','$name','$chnltitle','$link','$chnldescription','$language','$copyright','$managingEditor','$webMaster','$pubDate','$lastBuildDate','$ttl','$img_url','$img_title','$img_link','$img_width','$img_height','$img_description')") or die("Something went wrong".mysql_error());





if (!isset($f)) $f="rss";
if (!isset($sf)) $sf="addchnl";


commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);


echo "
<br><br>
<table>
<tr>
	<td valign=\"top\" width=\"20%\">";

submenu($f,$sf);

echo "
	</td>
	<td>
	<center><h2>RSS editor - add an RSS channel.</h2></center><br /><br />
	<b>Channel added successfully. Note that the channel file will be created (and visible) after the first items are added.</b><br />
<br />
	</td>
</tr>
</table>
 ";

bodyend();
commonfooter();


}
}
?>
