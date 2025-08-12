<?
ob_start();
include_once("admin/functions.php");
include('admin/config.php');
include('admin/session.php');
include('admin/loadsettings.php');
include("language/$settings[language].php");
include("skins/$settings[skin]/header.php");
?>
                        <script language="javascript" type="text/javascript">
function OpenLarge (c) {
    window.open(c,
                    'large',
                    'width=<? echo"$settings[img_fullwidth]"; ?>,height=300,scrollbars=yes,status=yes');
}
</script>

<? 




if($_POST["submit"]){

	if(!$_POST[name])
	{
	$messages[]="Please fill out your name";
	}

	if(!$_POST[email])
	{
	$messages[]="Please fill out your email-adress";
	}
	
	if(empty($messages)) {
		
		// Send email
		$thename = htmlspecialchars($_POST[name], ENT_QUOTES);
		$theemail = htmlspecialchars($_POST[email], ENT_QUOTES);
		$theremarks = htmlspecialchars($_POST[remarks], ENT_QUOTES);
		
		$body = "name: $thename\n\n email: $theemail \n\n remarks: $theremarks";
		mail("$page[form_email]", "Email from your site $settings[weblog_title]", "$body", "From: $theemail");

		
header("Location: page.php?id=$page[id]&sent=1");
ob_end_flush();

	}
}

if(!empty($messages)){
	displayErrors($messages);
}


function mainPage(){
global $page, $link, $table_pages, $lang_your_email_sent, $lang_contact, $lang_name_mail, $lang_email_mail, $lang_additional_remarks;

$thepageid = 1; // if the parameter is not numeric (possible hacking attempt), the script defaults to 1
if(is_numeric($_GET['id'])){ 
$thepageid =  $_GET['id'];
}


$query = "SELECT * FROM $table_pages WHERE id = '$thepageid'";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$page = mysql_fetch_array($result,MYSQL_ASSOC);
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
	<?
	if($_GET[sent]){
	?>
	     <tr>
        <td><? echo "$lang_your_email_sent"; ?></td>
      </tr>
	  <?
	  }
	  ?>
	
	
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><?
// start of mainpage content

$title = nl2br($page[title]);		
echo "<div class='title'>$title</div>";

$text = nl2br($page[text]);
echo "$text";
?>
<tr>
        <td>&nbsp;</td>
      </tr>
	  </table>
	  <?
	  if($page[use_form]){
?>
	  <form action="<? echo "page.php?id=$page[id]"; ?>" method="POST">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">  
<tr>
  <td><strong><? echo "$lang_contact"; ?></strong></td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
<tr>
  <td><? echo "$lang_name_mail"; ?></td>
</tr>
<tr>
  <td><input name="name" type="text" id="name"></td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
<tr>
  <td><? echo "$lang_email_mail"; ?></td>
</tr>
<tr>
  <td><input name="email" type="text" id="email"></td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
<tr>
  <td><? echo "$lang_additional_remarks"; ?></td>
</tr>
<tr>
  <td><textarea name="remarks" cols="45" rows="7" id="remarks"></textarea></td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
<tr>
  <td><input type="submit" name="submit" value="Send Email"></td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
    </table></form>
	<? }
	}
	
include("skins/$settings[skin]/mainpage.php");
include("skins/$settings[skin]/footer.php");
?>	