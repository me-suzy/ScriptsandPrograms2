<?php
# choose a banner

include_once("../includes/db_connect.php");
include("$CONST_INCLUDE_ROOT/Templates/maintemplate.header.inc.php");
?>

<?php

if(!isset($_SESSION['Sess_Password']))
{
	?>
		  <form name="form1" method="post" action="login.php">
			<div align="center">Password
				<input name='txtPassword' type="password" value="">
				<input name="Submit" type="submit" class="button" value="Submit">
			</div>
		  </form>
	<?php
}
else
	include('../includes/admin_header.php');
?>

<?include("$CONST_INCLUDE_ROOT/Templates/maintemplate.footer.inc.php");?>