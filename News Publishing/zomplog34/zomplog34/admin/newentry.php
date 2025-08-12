<?
ob_start();
include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

$user = loadUser($_SESSION['login'],$link,$table_users);

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

// upload script
include("upload.php");


if(!empty($messages)){
	displayErrors($messages);
}

if(empty($messages)) {



		newEntry($_SESSION[login],$image,$imagewidth,$imageheight,$date);
	
header("Location: $_SERVER[php_self]?message=1");
ob_end_flush();

	}
}
}

 if($_GET[message] && empty($messages)){
displayMessage($_GET[message]);
  }
  

?>


<form method="POST" name="editform" enctype="multipart/form-data">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="55%" valign="top"><table width="379" border="0" cellpadding="0" cellspacing="0" class="text">
  <tr>
    <td><h1><? echo "$lang_newentry"; ?></h1></td>
  </tr>
  <tr>
    <td class="title">&nbsp;</td>
  </tr>
  <td width="164" class="title"><? echo "$lang_title"; ?></td>
  </tr>
  <tr>
    <td valign="top"><input name="title" type="text" id="title" value="<? echo "$_POST[title]"; ?>"></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" class="title">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" class="title"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
    <td valign="top"><textarea rows="15" cols="60" name="text"><? echo "$_POST[text]"; ?></textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="title"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
    <td><textarea name="extended" cols="60" rows="15" id="extended"><? echo "$_POST[extended]"; ?></textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <? if($settings[use_upload]){ ?>
  <tr>
    <td class="title"><? echo "$lang_upload_image"; ?></td>
  </tr>
  <tr>
    <td>
	<?
	if($user==TRUE){ 
        ?> 

         <? echo "$lang_number_of_images"; ?>  <SELECT name="forms" onchange="javascript:document.editform.submit();"> 
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

} 
?>  </td>
  </tr>
  <? if ($settings[use_mediafile]){ ?>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><span class="title"><? echo "$lang_media"; ?></span></td>
  </tr>
  <tr>
    <td><input name="mediafile" type="text" size="35" value="<? echo "$_POST[mediafile]"; ?>">
        <select name="mediatype">
          <option value="0" selected><? echo "$lang_choose_type"; ?></option>
          <option value="1">mp3</option>
          <option value="2">Quicktime movie</option>
          <option value="3">Realplayer movie</option>
          <option value="4">Windows Media movie</option>
      </select></td>
  </tr>
  <? } ?>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <?

  if($settings[categories]){
  ?>
  <tr>
    <td class="title"><? echo "$lang_category"; ?></td>
  </tr>
  <tr>
    <td><select name="catid" size="1"
 onChange="setOptions(document.myform.catid.options[document.myform.catid.selectedIndex].value);">
        <option value="0" selected="selected"><? echo "$lang_choosemaincat"; ?></option>
        <?
$categories = loadCat($link,$table_cat);
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
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input type="submit" name="Submit" value="Submit"></td>
  </tr>
      </table></td>
      <td width="3%" valign="top">&nbsp;</td>
      <td width="42%" valign="top"><?php include('menu.php'); ?>        &nbsp;</td>
    </tr>
  </table>
</form>
<?
include ("footer.php");
?>