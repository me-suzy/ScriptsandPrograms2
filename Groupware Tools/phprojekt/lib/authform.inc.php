<?php

// authform.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: authform.inc.php,v 1.24.2.1 2005/09/21 08:29:20 fgraf Exp $

if (!defined('lib_included')) die('Please use index.php!');

if (!$path_pre) $path_pre = '../';


if (!$langua) {
    $langua = getenv('HTTP_ACCEPT_LANGUAGE');
    $found  = false;
    foreach ($languages as $langua1) {
        if (eregi($langua1, $langua)) {
            $langua = $langua1;
            $found  = true;
            break;
        }
    }
    if ($found) {
        include_once($lang_path.'/'.$langua.'.inc.php');
    }
    else {
        $langua = 'en';
        include_once($lang_path.'/en.inc.php');
    }
}

$support_html = '';
$css_style    = '';

if (!strstr($_SERVER['QUERY_STRING'], 'module=logout')) {
    $return_path = urlencode('/'.$_SERVER['REQUEST_URI']);
}
else {
    $return_path = 'index.php';
}

$module = "login";
echo set_page_header();

if ($_SERVER['PHP_SELF'] == '/'.PHPR_INSTALL_DIR.'index.php') {
    echo '
<br /><br />
<div style="background-color:#DC6417;height:41px;width:100%;">
    <img src="/'.PHPR_INSTALL_DIR.'layout/default/img/logo.png" alt="PHProjekt Logo" />
</div>
';
}

?>

<br />

<div class="center">
    <div id="logo" class="center"></div>
    <form action="<?php echo $path_pre; ?>index.php" method="post" name="frm">
        <input type="hidden" name="loginform" value="1" />
        <input type="hidden" size="100" name="return_path" value="<?php echo $return_path; ?>" />
        <fieldset class="login">
            <legend><?php echo __('Log in, please'); ?></legend>
            <label for="loginstring" class="login"><?php echo __('Login'); ?></label>
            <input class="left" type="text" tabindex="1" name="loginstring" id="loginstring" size="33" title="<?php echo __('Please enter your user name here.'); ?>" /><br />
            <label for="user_pw" class="login"><?php echo __('Password'); ?></label>
            <input class="left" type="password" tabindex="2" name="user_pw" id="user_pw" size="33" title="<?php echo __('Please enter your password here.'); ?>" /><br />
            <input class="login" type="submit" value="<?php echo __('go'); ?>" title="<?php echo __('Click here to login.'); ?>" />
        </fieldset>
    </form>
</div>

<script type="text/javascript">
<!--
if (document.frm.loginstring.value == "") {
    document.frm.loginstring.focus();
}
//-->
</script>

</body>
</html>
