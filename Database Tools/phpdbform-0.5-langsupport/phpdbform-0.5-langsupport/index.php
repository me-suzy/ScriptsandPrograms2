<?php
// Copyright 2002 - Coral Informática Ltda
// Paulo Assis
require_once( "phpdbform/phpdbform_main.php" );

// By Iko (2004-10-17): Language support: _LANGMAINPAGE
draw_adm_header( _LANGMAINPAGE );
if( !$phpdbform_main->access->check_login(0) ) {
?>
<br>
<table border="0" cellpadding="4" cellspacing="0" width="80%" align="center">
<form action="index.php" method="post">
<tr>
<td>
<?php if( strlen($erro)>0 ) print "<div class=\"erro\">$erro</div><br>"; ?>
<?php 
// By Iko (2004-10-17): Language support: _LANGLOGINUSER
echo _LANGLOGINUSER;
?>:<br>
<input type="text" name="admLogin" size="30" maxlength="30" class="fieldtextbox"><br>
<?php 
// By Iko (2004-10-17): Language support: _LANGPASSWORD
echo _LANGPASSWORD;
?>:<br>
<input type="password" name="admPasswd" size="30" maxlength="30" class="fieldtextbox"><br><br>
<input type="submit" name="admSubmit" value="
<?php 
// By Iko (2004-10-17): Language support: _LANGLOGIN
echo _LANGLOGIN; ?>" class="fieldbutton">
</td>
</tr>
</form>
</table>
<?php
} else {
?>
<p><?=_LANGWELCOME?> <?=$phpdbform_main->access->get_user()?>,
<br><br>
<?=_LANGWELCOMEMSG?></p>
<?php
}
draw_adm_footer();
?>
