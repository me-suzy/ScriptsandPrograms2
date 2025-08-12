<?php
/* Written by Gerben Schmidt, http://scripts.zomp.nl */

ob_start();
include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

if(!$_SESSION["loggedIn"]){
?>

You are not allowed to view this page, please log in first.
<?
}
else
{

if($_POST['Submit']){

if(!$_POST[date])
{
$messages[]="please enter a date format, otherwise zomplog will not function properly";
}

if(!$_POST['max'])
{
$messages[]="please enter a value for the posts per page field";
}

if(!$_POST[scroll])
{
$messages[]="please enter a value for the scroll field";
}

if(!$_POST[max_upload])
{
$messages[]="please enter a value for the maximum upload size field";
}


if(!empty($messages)){
	displayErrors($messages);
}

if(empty($messages)) {
		

	$query="UPDATE $table_settings SET weblog_title = '$_POST[weblog_title]', comments = '$_POST[comments]', categories = '$_POST[categories]',
	 pages = '$_POST[pages]', language = '$_POST[language]', skin = '$_POST[skin]', max = '$_POST[max]', scroll = '$_POST[scroll]',
	 use_upload = '$_POST[use_upload]', max_upload = '$_POST[max_upload]', date = '$_POST[date]', use_mediafile = '$_POST[use_mediafile]',
	 admin_welcome = '$_POST[admin_welcome]', site_welcome = '$_POST[site_welcome]', use_join = '$_POST[use_join]', img_width = '$_POST[img_width]', img_fullwidth = '$_POST[img_fullwidth]'";
	$result=mysql_query($query, $link) or die("Died inserting data into db.  Error returned if any: ".mysql_error());

header("Location: settings.php?message=5");
ob_end_flush();

	}


}

if($_GET[message]){ 
displayMessage($_GET[message]);
  }


if(!$_POST['Submit']){

?>


<form name="myform" method="post" enctype="multipart/form-data">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="62%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
        <tr>
          <td width="47%"><h1><? echo "$lang_system_settings"; ?></h1></td>
          <td width="53%">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="title"><? echo "$lang_main_config"; ?></span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><? echo "$lang_weblog_title"; ?></td>
          <td><input name="weblog_title" type="text" id="weblog_title" value="<? echo "$settings[weblog_title]" ?>"></td>
        </tr>
        <tr>
          <td><? echo "$lang_choose_language"; ?></td>
          <td><select name="language" size="1">
 onChange="setOptions(document.myform.language.options[document.myform.language.selectedIndex].value);">
              <option value="<? echo "$settings[language]"; ?>" selected="selected"><? echo "$settings[language]"; ?></option>
              <?
$path = "../language/";
foreach (glob("$path" . "*.php") as $filename){
$file = basename($filename, ".php");
if($file == $settings[language]){
// do nothing
}
else
{
echo '<option value="'.$file.'">'.$file.'</option>';
	}
 }
	?>
          </select></td>
        </tr>
        <tr>
          <td><? echo "$lang_choose_skin"; ?></td>
          <td><select name="skin" size="1">
              <option value="<? echo "$settings[skin]"; ?>" selected="selected"><? echo "$settings[skin]"; ?></option>
              <?
$path = "../skins/";
$dp = opendir($path);
while($item = readdir($dp)){
if(substr($item,0,1)!='.'){
if($item == $settings[skin]){
// do nothing
}
else
{
echo '<option value="'.$item.'">'.$item.'</option>';
	}
 	}
 }
	?>
          </select></td>
        </tr>
        <tr>
          <td><? echo "$lang_posts_per_page"; ?></td>
          <td><input name="max" type="text" id="max" value="<? echo "$settings[max]" ?>" size="3"></td>
        </tr>
        <tr>
          <td><? echo "$lang_scroll"; ?></td>
          <td><input name="scroll" type="text" id="scroll" value="<? echo "$settings[scroll]" ?>" size="3">              </td>
        </tr>
        <tr>
          <td><? echo "$lang_date_time_format"; ?></td>
          <td><select name="date">
            <option value="<? echo "$settings[date]"; ?>"><? $today = date("$settings[date]"); echo "$today"; ?></option>
            <option value="F j, Y"><? $today = date("F j, Y"); echo "$today"; ?></option>
            <option value="F j, Y, g:i a"><? $today = date("F j, Y, g:i a"); echo "$today"; ?></option>
			<option value="F j, Y, G:i"><? $today = date("F j, Y, G:i"); echo "$today"; ?></option>
            <option value="D M j Y"><? $today = date("D M j Y"); echo "$today"; ?></option>			
			<option value="D M j Y G:i:s"><? $today = date("D M j Y G:i:s"); echo "$today"; ?></option>
            <option value="m d Y"><? $today = date("m d Y"); echo "$today"; ?></option>
            <option value="d m Y"><? $today = date("d m Y"); echo "$today"; ?></option>
			<option value="m d Y, G:i"><? $today = date("m d Y, G:i"); echo "$today"; ?></option>
            <option value="d m Y, G:i"><? $today = date("d m Y, G:i"); echo "$today"; ?></option>
		  </select></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="title"><? echo "$lang_activate_site_parts"; ?></span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><? echo "$lang_comments_on"; ?></td>
          <td><? if($settings[comments]){
	?>
              <input name="comments" type="checkbox" id="comments" value="1" checked>
              <?
	}
	else
	{
	?>
              <input name="comments" type="checkbox" id="comments" value="1">
              <?
	}
	?></td>
        </tr>
        <tr>
          <td><? echo "$lang_categories_on"; ?></td>
          <td><? if($settings[categories]){
	?>
              <input name="categories" type="checkbox" id="categories" value="1" checked>
              <?
	}
	else
	{
	?>
              <input name="categories" type="checkbox" id="categories" value="1">
              <?
	}
	?></td>
        </tr>
        <tr>
          <td><? echo "$lang_upload_on"; ?></td>
          <td><? if($settings[use_upload]){
	?>
              <input name="use_upload" type="checkbox" id="use_upload" value="1" checked>
              <?
	}
	else
	{
	?>
              <input name="use_upload" type="checkbox" id="use_upload" value="1">
              <?
	}
	?></td>
        </tr>
        <tr>
          <td><? echo "$lang_mediafile_on"; ?></td>
          <td><? if($settings[use_mediafile]){
	?>
              <input name="use_mediafile" type="checkbox" id="use_mediafile" value="1" checked>
              <?
	}
	else
	{
	?>
              <input name="use_mediafile" type="checkbox" id="use_mediafile" value="1">
              <?
	}
	?></td>
        </tr>
        <tr>
          <td><? echo "$lang_pages_admin"; ?></td>
          <td><? if($settings[pages]){
	?>
              <input name="pages" type="checkbox" id="pages" value="1" checked>
              <?
	}
	else
	{
	?>
              <input name="pages" type="checkbox" id="pages" value="1">
              <?
	}
	?>
              <? echo "$lang_pages_admin_only"; ?></td>
        </tr>
        <tr>
          <td><? echo "$lang_allow_register"; ?></td>
          <td><? if($settings[use_join]){
	?>
            <input name="use_join" type="checkbox" id="use_join" value="1" checked>
            <?
	}
	else
	{
	?>
            <input name="use_join" type="checkbox" id="use_join" value="1">
            <?
	}
	?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="title"><? echo "$lang_upload_image"; ?></span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><? echo "$lang_max_upload"; ?></td>
          <td><input name="max_upload" type="text" id="max_upload" value="<? echo "$settings[max_upload]" ?>" size="8">
    (102400 = 100 kb) </td>
        </tr>
        <tr>
          <td><? echo "$lang_thumbnail_width"; ?></td>
          <td><input name="img_width" type="text" id="img_width" value="<? echo "$settings[img_width]" ?>" size="8"></td>
        </tr>
        <tr>
          <td><? echo "$lang_thumbnail_fullwidth"; ?></td>
          <td><input name="img_fullwidth" type="text" id="img_fullwidth" value="<? echo "$settings[img_fullwidth]" ?>" size="8"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="title"><? echo "$lang_messages"; ?></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><? echo "$lang_admin_welcome_message"; ?></td>
          <td><textarea name="admin_welcome" cols="30" rows="7" id="admin_welcome"><? echo "$settings[admin_welcome]" ?></textarea></td>
        </tr>
        <tr>
          <td valign="top"><? echo "$lang_site_welcome_message"; ?></td>
          <td><textarea name="site_welcome" cols="30" rows="7" id="site_welcome"><? echo "$settings[site_welcome]" ?></textarea></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input type="submit" name="Submit" value="Submit"></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
      <td width="38%" valign="top"><?php include('menu.php'); ?>&nbsp;</td>
    </tr>
  </table>
</form>


<?
}
}
include('footer.php');
?>