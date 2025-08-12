<?php
include('../config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="en-us" />
<?php
if($mainsite_name != '')
{echo ($mainsite_name.' - ');}
if($parentsite_name != '')
{echo ($parentsite_name.' - ');}
echo $site_name; 
?>  - Administration</title>
<link rel="stylesheet" href="../style.css" type="text/css" />
<meta name="Description" content="<?php echo ($meta_description); ?>" />
<meta name="Keywords" content="<?php echo ($meta_keywords); ?>" />
<meta name="Generator" content="<?php echo ($meta_generator); ?>" />
<script type="text/javascript">
//<![CDATA[
function register_pwd(pwd)
{
opener.document.forms['contacts_form'].elements['<?php echo $users_table_password_field; ?>'].value = document.forms['encrypter'].elements['encrypted'].value;
self.close();
}
//]]>
</script>
</head>
<body>
<?php
    echo "<table summary=\"none\" class=\"main_table\" cellpadding=\"5\"><tr><td valign=\"top\"><b>Password - generator</b></td></tr><tr><td valign=\"top\">";
    echo $login_messages_ar['pwd_explain_text'];
    echo "<form action=\"pwd.php\" name=\"pwd_gen\" id=\"pwd_gen\" method=\"POST\">";
    echo "<p><input type=\"text\" name=\"pwd\" idname=\"pwd\" value=\"";
    if (isset($_POST['pwd'])) {
    	echo $_POST['pwd'];
    } // end if
    echo "\" size=\"40\" /><br />";
    echo "<input type=\"submit\" name=\"\" idname=\"\" value=\"".$login_messages_ar['pwd_encrypt_button_text']."\" /></p>";
    echo "</form>";
    if(isset($_POST['pwd'])){
    	$encrypted = md5($_POST['pwd']);
    	echo "<form name=\"encrypter\" id=\"encrypter\">";
    	echo "<p><input type=\"text\" name=\"encrypted\" idname=\"encrypted\" value=\"$encrypted\" size=\"40\" /><br />";
    	echo "<input type=\"button\" name=\"encrypt-it\" idname=\"encrypt-it\" value=\"".$login_messages_ar['pwd_register_button_text']."\" onclick=\"register_pwd('".$encrypted."')\" />";
    	echo "</p></form>";
    	echo $login_messages_ar['pwd_suggest_email_sending']." ( <a href=\"mailto:?subject=password&amp;body=".$_POST['pwd']."\">".$login_messages_ar['pwd_send_link_text']."</a> )";
    }
    echo "</td></tr></table>";
    ?>
</body>
</html>
