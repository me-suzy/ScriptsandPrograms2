<?
include_once("admin/functions.php");
include('admin/config.php');
include('admin/session.php');
include('admin/loadsettings.php');
include("language/$settings[language].php");
include("skins/$settings[skin]/header.php");

?>
<br />
<?

// include moblog system
$moblog = loadMoblogSettings($link,$table_moblog);

if($moblog[use_moblog]){
include("moblog.php");
}

// start of page numbering

if($_GET[catid]){
$thecatid = 1; // if the parameter is not numeric (possible hacking attempt), the script defaults to 1
if(is_numeric($_GET['catid'])){ 
$thecatid =  $_GET['catid'];
}
$dbquery = "SELECT * FROM $table WHERE catid = '$thecatid' ORDER BY date DESC";
}

elseif($_GET[username]){
$dbquery = "SELECT * FROM $table WHERE username = '$_GET[username]' ORDER BY date DESC";
$pagetitle = "$lang_posts_by <b>$_GET[username]</b><br />";
}
elseif($_GET[search]){
$dbquery = "SELECT * FROM $table WHERE title LIKE '%$_GET[search]%' OR text LIKE '%$_GET[search]%' OR extended LIKE '%$_GET[search]%' ORDER BY date DESC";
$pagetitle = "$lang_searched_for <b>$_GET[search]</b><br />";
}
else
{
$dbquery = "SELECT * FROM $table ORDER BY date DESC";
if(!$_GET[show]){
$welcome = "$settings[site_welcome]<br /><br />";
}
}

$query = mysql_query($dbquery,$link);
$numrows = mysql_num_rows ($query);

if (!isset ($_GET[show])) {
$display = 1;
} else {
	$display = $_GET[show];
}

$start = (($display * $limit) - $limit);

$mainquery = "$dbquery LIMIT $start, $limit";
$result = mysql_query($mainquery);
$results = mysql_fetch_array($result);
if(!$results){
$no_results = "$lang_no_results";
}	

// start of mainpage content
// $mainquery has been declared earlier

function mainPage(){
global $mainquery, $link, $table, $table_cat, $table_users, $table_comments, $entry, $cat,
$category, $comments, $numcomments, $settings, $lang_number_comments, $lang_read_more, $lang_listen, $lang_view, $userdate, $postdate;
$result = mysql_query ($mainquery, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$entries = arrayMaker($result,MYSQL_ASSOC);
foreach($entries as $entry){
?>
            <table width="100%" border="0" cellspacing="0" class="text">
              <tr>
                <td><?


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

echo "</table>";
}

?>
  <script type="text/javascript" src="./popup/scripts/init.js"></script>
  <div id="canvasShadow"></div>

<?			
echo "<div class='title'>$entry[title]</div>";

$text = nl2br($entry[text]);
$text = wordwrap($text, 60, "\n", 1);
echo "$text\n";

if($entry[mediafile]){
if($entry[mediatype] == 1){
echo " <a href='detail.php?id=$entry[id]'>$lang_listen</a><br />";
}
else
{
echo " <a href='detail.php?id=$entry[id]'>$lang_view</a><br />";
}
}

if($entry[extended]){
echo " <a href='detail.php?id=$entry[id]'>$lang_read_more</a><br />";
}

echo "<br />";
$query = mysql_query ("SELECT * FROM $table_comments WHERE entry_id = $entry[id]");
$numcomments = mysql_num_rows ($query);

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

if($settings[comments]){
echo " | <a href='detail.php?id=$entry[id]#comments'>$numcomments $lang_number_comments</a>";
}
?>
              <tr>
                      <td>&nbsp;</td>
              </tr>
            </table>
			<div id="canvasShadow"></div>
			<span class="popupContainer"></span>
            <?


}
}

include("skins/$settings[skin]/mainpage.php");
include("skins/$settings[skin]/footer.php");
?>