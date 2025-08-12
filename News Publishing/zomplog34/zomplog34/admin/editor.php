<?
ob_start();
include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

$user = loadUser($_SESSION['login'],$link,$table_users);
$entry = loadEntry($link,$table);

if(!$_SESSION["loggedIn"]){
?>

You are not allowed to view this page, please log in first.
<?
}
else
{


if($_POST['Submit']){

if(!$_POST[title])
{
$messages[]="please enter a title";
}

if(strlen($_POST[day]) != 2) { 
$messages[]="day should contain two characters"; 
} 

if($_POST[day] > 31){
$messages[]="day should lie between 01 and 31";
}

if(strlen($_POST[month]) != 2) { 
$messages[]="month should contain two characters"; 
} 

if($_POST[month] > 12){
$messages[]="month should lie between 01 and 12";
}

if(strlen($_POST[year]) != 4) { 
$messages[]="year should contain four characters"; 
} 

if($_POST[year] > 2038){
$messages[]="year should be before 2039";
}

if($_POST[year] < 1971){
$messages[]="year should be after 1970";
}

if(strlen($_POST[hours]) != 2) { 
$messages[]="hours should contain two characters"; 
} 

if(strlen($_POST[minutes]) != 2) { 
$messages[]="minutes should contain two characters"; 
} 

 // upload script
include("upload.php");

if(!empty($messages)){
	displayErrors($messages);
}

if(empty($messages)) {

//another dirty date hack
$thedate = "$_POST[year]" . "$_POST[month]" . "$_POST[day]" . "$_POST[hours]" . "$_POST[minutes]" . "00";
    editEntry($link,$table,$image,$imagewidth,$imageheight,$thedate);

header("Location: editor.php?id=$entry[id]&message=2");
ob_end_flush();

	}


}

 if($_GET[message] && empty($messages)){
displayMessage($_GET[message]);
  }

// another gerben timestamp hack
	  $day = substr($entry['date'], 6, 2);
	  $month = substr($entry['date'], 4, 2);
	  $year = substr($entry['date'], 0, 4);
	  $hours = substr($entry['date'], 8, 2);
	  $minutes = substr($entry['date'], 10, 2);

?>

<form name="editform" method="post" enctype="multipart/form-data">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="55%"><table width="379" border="0" cellpadding="0" cellspacing="0" class="text">
        <tr>
          <td colspan="2"><h1><? echo "$lang_edit"; ?></h1></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><input name="id" type="hidden" id="id" value="<? echo "$_GET[id]"; ?>"></td>
        </tr>
        <tr>
          <td class="title"><? echo "$lang_title"; ?></td>
          <td class="title">&nbsp;</td>
        </tr>
        <tr>
          <td width="218" valign="top"><input name="title" type="text" id="title" value="<? echo "$entry[title]"; ?>"></td>
          <td width="278" valign="top">&nbsp;			</td>
        </tr>
        <tr>
          <td colspan="2" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" valign="top"><span class="title"><? echo "$lang_date"; ?></span></td>
        </tr>
        <tr>
          <td colspan="2" valign="top">day
            <input name="day" type="text" size="2" value="<? echo "$day"; ?>">
month
<input name="month" type="text" size="2" value="<? echo "$month"; ?>">
year
<input name="year" type="text" size="4" value="<? echo "$year"; ?>">
hours
<input name="hours" type="text" id="hours" value="<? echo "$hours"; ?>" size="2">
minutes
<input name="minutes" type="text" id="minutes" value="<? echo "$minutes"; ?>" size="2"></td>
        </tr>
        <tr>
          <td colspan="2" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" valign="top" class="title"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35%" class="title"><? echo "$lang_text"; ?></td>
                <td width="65%"><input type="button" class="button" value="bold" name="bold" onMouseDown="javascript:tag_construct('bold','text'); return false;"/>
            <input type="button" class="button" value="italic" name="italic" onMouseDown="javascript:tag_construct('italic','text'); return false;"/>
            <input type="button" class="button" value="underline" name="underline" onMouseDown="javascript:tag_construct('underline','text'); return false;"/>
            <input type="button" class="button" value="url" name="url" onMouseDown="javascript:tag_construct('link','text'); return false;"/>
            <input type="button" class="button" value="img" name="img" onMouseDown="javascript:tag_construct('image','text'); return false;"/></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="2" valign="top"><textarea name="text" cols="60" rows="15" id="text"><? echo "$entry[text]"; ?></textarea></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" class="title"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35%" class="title"><? echo "$lang_extended"; ?></td>
                <td width="65%"><input type="button" class="button" value="bold" name="bold" onMouseDown="javascript:tag_construct('bold','extended'); return false;"/>
            <input type="button" class="button" value="italic" name="italic" onMouseDown="javascript:tag_construct('italic','extended'); return false;"/>
            <input type="button" class="button" value="underline" name="underline" onMouseDown="javascript:tag_construct('underline','extended'); return false;"/>
            <input type="button" class="button" value="url" name="url" onMouseDown="javascript:tag_construct('link','extended'); return false;"/>
            <input type="button" class="button" value="img" name="img" onMouseDown="javascript:tag_construct('image','extended'); return false;"/></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="2"><textarea name="extended" cols="60" rows="15" id="extended"><? echo "$entry[extended]"; ?></textarea></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><span class="title"><? echo "$lang_upload_image"; ?></span></td>
        </tr>
        <tr>
          <td colspan="2"><?
		  if($entry[image]){
echo "<table width='100%' border='0' align='left' cellspacing='0'>";
$images = explode(";", $entry[image]);
foreach($images as $image){
echo "<tr><td width='25%'><div class='img-shadow'><img src='../thumbs/$image'></div></td>
<td width='75%' class='text'>&nbsp;</td></tr>";
}
echo "</table><br />";
}
?></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <? if($settings[use_upload]){ ?>
        <tr>
          <td colspan="2" class="title"><? echo "$lang_replace_image"; ?></td>
        </tr>
        <tr>
          <td colspan="2">
		  
		  
		  	<?
	if($user==TRUE){ 
        ?> 

        <? echo "$lang_number_of_images"; ?> <SELECT name="forms" onchange="javascript:document.editform.submit();"> 
        <? 
        for($i=1;$i<21;$i++){ 
            ?> 
            <option value="<?=$i?>"><?=$i?></option> 
            <? 
        } 
        ?> 
        </SELECT> 

        <? 
    } 
    ?> 
<br /><br />
    <? 
	if(!$_POST[forms]){
	$forms = 1;
	}
	else
	{
	$forms = $_POST['forms'];
	}
	
    for($i=0;$i<$forms;$i++){ 
        ?> 
        <INPUT TYPE="file" value="1" NAME="image[<?=$i?>]" \><br \> 
        <? 
    } 


?>

</td>
        </tr>
        <? if ($settings[use_mediafile]){ ?>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><span class="title"><? echo "$lang_media"; ?></span></td>
        </tr>
        <tr>
          <td colspan="2"><input name="mediafile" type="text" value="<? echo "$entry[mediafile]"; ?>" size="35">
              <select name="mediatype">
                <?
	switch($entry[mediatype]){
	
	case 0:
	$selected = "$lang_choose_type";
	$value = "0";
	break;
	
	case 1:
	$selected = "mp3";
	$value = "1";
	break;
	
	case 2:
	$selected = "Quicktime movie";
	$value = "2";
	break;

	case 3:
	$selected = "Realplayer movie";
	$value = "3";
	break;

	case 4:
	$selected = "Windows media movie";
	$value = "4";
	break;
	}
	
		?>
                <option value="<? echo "$value"; ?>" selected><? echo "$selected"; ?></option>
                <option value="1">mp3</option>
                <option value="2">Quicktime movie</option>
                <option value="3">Realplayer movie</option>
                <option value="4">Windows Media movie</option>
            </select></td>
        </tr>
        <? } ?>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <?
	}
  if($settings[categories]){
  ?>
        <tr>
          <td colspan="2" class="title"><? echo "$lang_category"; ?></td>
        </tr>
        <tr>
          <td colspan="2"><select name="catid" size="1"
 onChange="setOptions(document.myform.catid.options[document.myform.catid.selectedIndex].value);">
              <?
 $query = "SELECT * FROM $table_cat WHERE id = $entry[catid]";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$chosencat = mysql_fetch_array($result,MYSQL_ASSOC);

 if(!$chosencat){
  ?>
              <option value="0" selected="selected"><? echo "$lang_choosemaincat" ?></option>
              <?
 }
 else
 {
 ?>
              <option value="<? echo "$entry[catid]"; ?>" selected="selected"><? echo "$chosencat[name]"; ?></option>
              <?
		}
$query = "SELECT * FROM $table_cat WHERE id != $entry[catid]";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$categories = arrayMaker($result,MYSQL_ASSOC);
foreach ($categories as $cat){

echo '<option value="'.$cat["id"].'">'.$cat["name"].'</option>';
}
?>
            </select>
              <?
  }
  ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" name="Submit" value="Submit"></td>
        </tr>
      </table></td>
      <td width="3%">&nbsp;</td>
      <td width="42%" valign="top"><?php include('menu.php'); ?>&nbsp;</td>
    </tr>
  </table>
</form>
<?
}



include ("footer.php");
?>