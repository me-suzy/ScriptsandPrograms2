<?php

/******************************************
* File      :   main.loginform.php
* Project   :   Contenido
* Descr     :   Login form
*
*
* Author    :   Jan Lengowski
* Created   :   21.01.2003
* Modified  :   21.01.2003
*
* © four for business AG
******************************************/

global $cfg, $username;

cInclude ("includes", 'functions.i18n.php');
?>
<html>
<head>
    <title>:: :: :: :: Contenido Login</title>
    <link rel="stylesheet" type="text/css" href="styles/contenido.css"></style>
	<link REL="SHORTCUT ICON" HREF="<?php echo $cfg["path"]["contenido_fullhtml"]."favicon.ico"; ?>">    
    <script type="text/javascript" src="scripts/md5.js"></script>
    
    <script language="javascript"> 
	if(top!=self) 
	{
		top.location="index.php";
	} 
	</script> 
  
    <script type="text/javascript">

        function doChallengeResponse() {

            str = document.login.username.value + ":" +
                  MD5(document.login.password.value) + ":" +
                  document.login.challenge.value;

            document.login.response.value = MD5(str);
            document.login.password.value = "";
            document.login.submit();
        }

    </script>
</head>
<body>
<form name="login" method="post" action="<?php echo $this->url() ?>">

<table width="100%" cellspacing="0" cellpadding="0" border="0">

    
    <tr height="70" style="height: 70px">
        <td style="background-image:url(images/background.jpg); border-bottom: 1px solid #000000">
            <img src="images/conlogo.gif">
        </td>
    </tr>

    <tr height="400">
        <td align="center" valign="middle">


            

                <table cellspacing="0" cellpadding="3" border="0" style="background-color: <?php echo $cfg['color']['table_light'] ?>; border: 1px solid <?php echo $cfg['color']['table_border'] ?>">

                    <tr>
                        <td colspan="2" style="background-color: <?php echo $cfg["color"]["table_header"] ?>; border-bottom: 1px solid <?php echo $cfg["color"]["table_border"] ?>" class="textw_medium">Contenido <?php echo $cfg['version']; ?> Login</td>
                    </tr>

                    <tr>
                        <td colspan="2"></td>
                    </tr>

                    <?php if ( isset($username) ) { ?>
                    <tr>
                        <td colspan="2" class="text_error">Invalid Login or Password!</td>
                    </tr>
                    <?php } else { ?>
                    <tr>
                        <td colspan="2" class="text_error">&nbsp;</td>
                    </tr>
                    <?php } ?>

                    <tr>
                        <td colspan="2"></td>
                    </tr>

                    <tr>
                        <td align="right" class="text_medium">Login:</td>
                        <td align="left"><input type="text" class="text_medium" name="username" size="20" maxlength="32" value="<?php echo ( isset($this->auth["uname"]) ) ? $this->auth["uname"] : ""  ?>"></td>
                    </tr>
                    
                    <tr>
                        <td align="right" class="text_medium">Password:</td>
                        <td align="left"><input type="password" class="text_medium" name="password" size="20" maxlength="32">
                            <input type="hidden" name="vaction" value="login">
                            <input type="hidden" name="formtimestamp" value="<?php echo time(); ?>">
                            </td>
                    </tr>
                    <tr>
                        <td align="right" class="text_medium">Language:</td>
                        <td align="left"><select name="belang" class="text_medium">
						<?php
						
						$langs = i18nStripAcceptLanguages($_SERVER['HTTP_ACCEPT_LANGUAGE']);
						
						foreach ($langs as $value)
						{
							$encoding = i18nMatchBrowserAccept($value);
							if ($encoding !== false)
							{
								break;
							}
						}
						
						$available_languages = i18nGetAvailableLanguages();
						
						foreach ($available_languages as $code => $entry)
						{
							list($language, $country, $codeset,$acceptTag) = $entry;
							if ($code == $encoding)
							{
								$selected = 'SELECTED="SELECTED"';
							} else {
								$selected = '';
							}
							
							echo '<option value="'.$code.'"'.$selected.'>'.$language.' ('.$country.')</option>';
						}

						?>
						</select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right">
                            <input type="image" title="Login" alt="Login" src="images/but_ok.gif">
                        </td>
                    </tr>

                </table>

            

        </td>
    </tr>

</table>

</form>

<script type="text/javascript">

    if (document.login.username.value == '') {
        document.login.username.focus();
        
    } else {
        document.login.password.focus();
        
    }

</script>

</body>
</html>
