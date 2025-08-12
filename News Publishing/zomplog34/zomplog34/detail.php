<?
include_once("admin/functions.php");
include('admin/config.php');
include('admin/session.php');
include('admin/loadsettings.php');
include("language/$settings[language].php");
include("skins/$settings[skin]/header.php");


$query = "SELECT ip FROM $table_banned";
$result = mysql_query($query,$link) or die("Could not load banned ip information.");
$banned = mysql_fetch_array($result,MYSQL_ASSOC);

if($banned){
if( in_array( $_SERVER['REMOTE_ADDR'], $banned ) ) { exit("<div class='text'>You're banned from this page.</div>"); }
}


?>
<br />
<?

if($_POST[addreview]){

if(!$_POST[name])
{
$messages[]="$lang_error_name";
}

if(!$_POST[comment])
{
$messages[]="$lang_error_comment";
}

if(!empty($messages)){
	displayErrors($messages);
}

if(empty($messages)) {

		newComment($link,$table_comments,$date);
		
echo "<div class='good'>$lang_error_comment_success</div><br />";

	}

	}

function mainPage(){
global $link, $table, $table_cat, $table_users, $table_comments, $entry, $cat,
$numcomments, $settings, $comment, $lang_on, $lang_no_comments_found, $lang_name, $lang_comment, $lang_write_comment, $userdate;
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><?
// start of mainpage content

$entry = loadEntry($link,$table);



if($entry[image]){

echo "<table width='130' border='0' align='left' cellspacing='0'>";
$images = explode(";", $entry[image]);
$imagewidth = explode(";", $entry[imagewidth]);
$imageheight = explode(";", $entry[imageheight]);

if(!$entry[imagewidth]){
// old style popups, for updating users and moblog users
for ($i = 0, $size = count ($images); $i < $size; $i++){
echo "<tr><td width='83' height='63'><a href='image.php?image=$images[$i]'
onclick='OpenLarge(this.href); return false'><img src='upload/thumbnail.php?gd=2&src=$images[$i]&maxw=130' border='0'></a></td>
<td width='13'>&nbsp;</td></tr>";
}
}
else
{
// new style enlargement
for ($i = 0, $size = count ($images); $i < $size; $i++){
echo "<tr><td width='83' height='63'><a href='upload/$images[$i]?$imagewidth[$i],$imageheight[$i]'
class='thumbnail'><img src='thumbs/$images[$i]' border='0'></a></td>
<td width='13'>&nbsp;</td></tr>";
}
}

echo "</table><br />";
}

?>
  <script type="text/javascript" src="./popup/scripts/init.js"></script>
  <div id="canvasShadow"></div>

<?
	
echo "<div class='title'>$entry[title]</div>";

$text = nl2br($entry[text]);
$text = wordwrap($text, 60, "\n", 1);
echo "$text";
if($entry[extended]){
$extended = nl2br($entry[extended]);
echo "<br /><br />";
echo "$extended";
}
if($entry[mediafile]){
// mp3 embed
if($entry[mediatype] == 1){
?>
<br /><br />
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="209" height="35">
  <param name="movie" value="<? echo "mp3player.swf?src=$entry[mediafile]"; ?>">
  <param name="quality" value="high">
  <embed src="<? echo "mp3player.swf?src=$entry[mediafile]"; ?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="209" height="35"></embed>
</object>
<?		  
}
// quicktime embed
if($entry[mediatype] == 2){
?>

<br />
<br />
<embed src="<? echo "$entry[mediafile]"; ?>" width=320 height=240 autoplay=true 
	controller=true loop=false scale=ASPECT cache='true' bgcolor='white' 
	pluginspage='http://www.apple.com/quicktime/'></embed>
<?
}
// RealPlayer embed
if($entry[mediatype] == 3){
?>
<br /><br />
<embed src="<? echo "$entry[mediafile]"; ?>" width="310" height="240" console="two" controls="ImageWindow" nojava="true" autostart="true"></embed>
 <br>
 <embed src="<? echo "$entry[mediafile]"; ?>" width="310" height="26" console="two" controls="ControlPanel" nojava="true" autostart="true"></embed>

<?
}
// Windows media embed
if($entry[mediatype] == 4){
?>
<br /><br />
<embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" name="mediaplayer1" 
autostart="true" height="300" width="320" transparentstart="1" loop="0" controller="true" 
src="<? echo "$entry[mediafile]"; ?>"></embed> 
<?
}
}

// converting timestamp to current user-formatted date
$q = mysql_query("SELECT date, UNIX_TIMESTAMP(date) AS timestamp FROM $table WHERE id = '$entry[id]'");
$row = mysql_fetch_array($q);

// then use PHP's date() function :
$postdate = date("$userdate", $row['timestamp']);

echo "<br /><a href='get.php?username=$entry[username]'>$entry[username]</a> | $postdate";
if($entry[catid]){
$query = "SELECT * FROM $table_cat WHERE id = $entry[catid]";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$cat = mysql_fetch_array($result,MYSQL_ASSOC);
echo " | <a href='get.php?catid=$cat[id]'>$cat[name]</a>";
}

	?>
        </td>
      </tr>
      <?
  if($settings[comments]){
  ?>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="title"><? echo "$lang_write_comment"; ?><a name="comments">&nbsp;</a><br />
            <br /></td>
      </tr>
      <tr>
        <td><form method="POST" action="<? echo"detail.php?id=$_GET[id]"; ?>">
        </td>
      </tr>
      <tr>
        <td><? echo "$lang_name"; ?></td>
      </tr>
      <tr>
        <td><input name="name" type="text" id="name"></td>
      </tr>
      <tr>
        <td class="title">&nbsp;</td>
      </tr>
      <tr>
        <td><? echo "$lang_comment"; ?></td>
      </tr>
      <tr>
        <td><textarea name="comment" cols="40" rows="5" id="comment"></textarea>
        </td>
      </tr>
      <tr>
        <td><input name="ip" type="hidden" value="<? echo "$_SERVER[REMOTE_ADDR]"; ?>"></td>
      </tr>
      <tr>
        <td><input type="submit" name="addreview" value="Submit"></form></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="title"><? echo "$lang_comments"; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <?
$comments = loadComments($entry[id],$link,$table_comments);


if(!$comments){
echo "<tr><td>$lang_no_comments_found</td></tr>";
}
else
{
foreach ($comments as $comment){

?>
      <tr>
        <td><?
		$title = wordwrap($comment[comment], 60, "\n", 1);
		
		// converting timestamp to current user-formatted date
$q = mysql_query("SELECT date, UNIX_TIMESTAMP(date) AS timestamp FROM $table_comments WHERE id = '$comment[id]'");
$row = mysql_fetch_array($q);

// then use PHP's date() function :
$postdate = date("$userdate", $row['timestamp']);

		 echo "<b>$comment[name]</b> $lang_on $postdate"; ?></td>
      </tr>
      <tr>
        <td><? 
		$text = wordwrap($comment[comment], 60, "\n", 1);
		
		echo "$text"; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <?
  }
  }
  }
?>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
<?
}



include("skins/$settings[skin]/mainpage.php");
include("skins/$settings[skin]/footer.php");
?>	