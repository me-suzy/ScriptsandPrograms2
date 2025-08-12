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

global $cfg, $idcat, $username;



?>
<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
    <title>:: :: :: :: Contenido Login</title>
    <link rel="stylesheet" type="text/css" href="../contenido/styles/contenido.css"></style>
    <script type="text/javascript" src="scripts/md5.js"></script>

    <script language="javascript">
	if(top!=self)
	{
		top.location="index.php";
	}
	</script>


</head>
<body>

<table width="100%" cellspacing="0" cellpadding="0" border="0">

    <!--
    <tr height="70" style="height: 70px">
        <td style="background-image:url(images/background.jpg); border-bottom: 1px solid #000000">
            <img src="images/conlogo.gif">
        </td>
    </tr>-->

    <tr height="400">
        <td align="center" valign="middle">


            <form name="login" method="post" action="front_content.php">

                <table cellspacing="0" cellpadding="3" border="0" style="background-color: <?php echo $cfg['color']['table_light'] ?>; border: 1px solid <?php echo $cfg['color']['table_border'] ?>">

                    <tr>
                        <td colspan="2" style="background-color: <?php echo $cfg["color"]["table_header"] ?>; border-bottom: 1px solid <?php echo $cfg["color"]["table_border"] ?>" colspan="2" class="textw_medium">Login</td>
                    </tr>

                    <tr>
                        <td colspan="2"></td>
                    </tr>

                    <?php if ( isset($username) ) { ?>
                    <tr>
                        <td colspan="2" class="text_error">Invalid Username or Password!</td>
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
                        <td class="text_medium">Username:</td>
                        <td><input type="text" class="text_medium" name="username" size="20" maxlenth="32" value="<?php echo ( isset($this->auth["uname"]) ) ? $this->auth["uname"] : ""  ?>"></td>
                    </tr>

                    <tr>
                        <td class="text_medium">Password:</td>
                        <td><input type="password" class="text_medium" name="password" size="20" maxlenth="32">
                            <input type="hidden" name="vaction" value="login">
                            <input type="hidden" name="formtimestamp" value="<?php echo time(); ?>">
							<input type="hidden" name="idcat" value="<?php echo $idcat; ?>">
                            </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right">
                            <input type="image" title="Login" alt="Login" src="../contenido/images/but_ok.gif">
                        </td>
                    </tr>

                </table>

            </form>

        </td>
    </tr>

</table>

<script type="text/javascript">

    if (document.login.username.value == '') {
        document.login.username.focus();

    } else {
        document.login.password.focus();

    }

</script>

</body>
</html>
