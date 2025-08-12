<?
ob_start();
include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

$user = loadUser($_SESSION['login'],$link,$table_users);
$query = "SELECT * FROM $table_pages WHERE id = $_GET[id]";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$entry = mysql_fetch_array($result);

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

if(!$_POST[text])
{
$messages[]="please enter a text";
}


if(!empty($messages)){
	displayErrors($messages);
}

if(empty($messages)) {
		
    $query="UPDATE $table_pages SET title = '$_POST[title]', text = '$_POST[text]', use_form = '$_POST[use_form]', form_email = '$_POST[form_email]' WHERE id = $entry[id]";
	$result=mysql_query($query, $link) or die("Died inserting data into db.  Error returned if any: ".mysql_error());

header("Location: editor_pages.php?id=$entry[id]&message=11");
ob_end_flush();
	}
	}


if($_GET[message]){ 
displayMessage($_GET[message]);
  }



?>

<form name="editform" method="post" enctype="multipart/form-data">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="55%" valign="top"><table width="379" border="0" cellpadding="0" cellspacing="0" class="text">
        <tr>
          <td><h1><? echo "$lang_edit"; ?></h1></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input name="id" type="hidden" id="id" value="<? echo "$_GET[id]"; ?>"></td>
        </tr>
        <tr>
          <td width="164" class="title"><? echo "$lang_title"; ?></td>
        </tr>
        <tr>
          <td valign="top"><input name="title" type="text" id="title" value="<? echo "$entry[title]"; ?>"></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
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
          <td valign="top"><textarea name="text" cols="60" rows="15" id="text"><? echo "$entry[text]"; ?></textarea></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="title"><? echo "$lang_upload_image"; ?></td>
        </tr>
        <tr>
          <td>Use the <a href="customupload.php" onclick='OpenLarge(this.href); return false'>Custom Upload Tool </a> to add images to your pages, anywhere you like!</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="title"><? echo "$lang_add_contact_form"; ?></span></td>
        </tr>
        <tr>
          <td><?
	if($entry[use_form]){
	?>
              <input name="use_form" type="checkbox" id="use_form" value="1" checked>
              <?
	}
	else
	{
	?>
              <input name="use_form" type="checkbox" id="use_form" value="1">
              <?
	}
	?>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="title"><? echo "$lang_form_email"; ?></span></td>
        </tr>
        <tr>
          <td><input type="text" name="form_email" value="<? echo "$entry[form_email]"; ?>">
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input type="submit" name="Submit" value="Submit"></td>
        </tr>
      </table></td>
      <td width="45%" valign="top"><?php include('menu.php'); ?>&nbsp;</td>
    </tr>
  </table>
</form>
<?
}
include ("footer.php");
?>