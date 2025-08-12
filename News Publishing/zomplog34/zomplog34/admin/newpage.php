<?
ob_start();
include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");
?>


<?
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

if(!$_POST[text])
{
$messages[]="please enter a text";
}

if(!empty($messages)){
	displayErrors($messages);
}

if(empty($messages)) {
		

	$query="INSERT INTO $table_pages (title, text, use_form, form_email) VALUES ('$_POST[title]', '$_POST[text]', '$_POST[use_form]', '$_POST[form_email]')";
	$result=mysql_query($query, $link) or die("Died inserting login info into db.  Error returned if any: ".mysql_error());
		
header("Location: $_SERVER[php_self]?message=10");
ob_end_flush();

	}
	}


}

if($_GET[message]){ 
displayMessage($_GET[message]);
  }

?>


<form method="post" name="editform" enctype="multipart/form-data">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="55%" valign="top"><table width="379" border="0" cellpadding="0" cellspacing="0" class="text">
  <tr>
    <td class="title"><h1><? echo "$lang_newpage"; ?></h1></td>
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
    <td class="title"><? echo "$lang_upload_image"; ?></td>
  </tr>
  <tr>
    <td>Use the <a href="customupload.php" onclick='OpenLarge(this.href); return false'>Custom Upload Tool </a> to add images to your pages, anywhere you like!</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="title"><? echo "$lang_add_contact_form"; ?></td>
  </tr>
  <tr>
    <td><input name="use_form" type="checkbox" id="use_form" value="1"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="title"><? echo "$lang_form_email"; ?></td>
  </tr>
  <tr>
    <td><input name="form_email" type="text" id="form_email" value="<? echo "$_POST[form_email]"; ?>"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input type="submit" name="Submit" value="Submit"></td>
  </tr>
      </table></td>
      <td width="45%" valign="top">&nbsp;
      <?php include('menu.php'); ?></td>
    </tr>
  </table>
</form>
<?
include ("footer.php");
?>