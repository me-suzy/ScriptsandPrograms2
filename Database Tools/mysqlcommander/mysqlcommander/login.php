<?php 
include "./ressourcen/config.php";
include "./ressourcen/dbopen.php";

$page->kopf();
$page->page_start();
$page->page_pic();

$page->page_email();
$page->page_mitte();

$title = "MySQL Commander ".$config->commander_version;
$line1 = "";
$line2 = $funcs->text("Willkommen", "Welcome");
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

$content->html_black();
$content->config->bgcolor="#ebebeb";
$content->html_headtext($funcs->text("Login", "Login"), "txtblaufett");
$content->config->bgcolor="White";
$content->html_text($funcs->text("Trage hier den Username und das Passwort ein, welches in der Konfiguration bestimmt wurde.", "Fill out the username and password, defined in the configuration."));
$content->html_text("<form name='loginform' method='post' action='./index.php'>
User: <input type='text' name='commander_user' size='15' style='width:180' class='txtkl' value='".$HTTP_SESSION_VARS['commander_user']."'>
&nbsp;&nbsp;
Pass: <input type='password' name='commander_pass' size='15' style='width:180' class='txtkl' value='".$HTTP_SESSION_VARS['commander_pass']."'>
&nbsp;&nbsp;
<a href='javascript:document.loginform.submit();'><img src='./img/pfeil_blau_rund.gif' alt='' width='15' height='15' border='0'><input type='image' src='./img/pixel.gif'></a>
<input type='hidden' name='checklogin' value='1'>
");
$content->html_br();


// ###############################################################################
$content->html_br();

$page->page_stop();
?>
</form>
<?php 
$page->fuss();
?>
