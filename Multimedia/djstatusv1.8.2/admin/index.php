
<?php
//////////////////////////////////////////////////////////////////////////////
// DJ Status v1.8.2															//
// ©2005 Nathan Bolender www.nathanbolender.com								//
// Free to use on any website												//
//////////////////////////////////////////////////////////////////////////////

include ("../config.php");	
include ("header.inc");
?>	
<p><strong>Please input the administrator password below to enter</strong></p>
<form action="main.php" method="post" name="login" id="login">
  <input name="pass" type="password" id="pass">
  <input type="submit" name="Submit" value="Go">
</form>
<p><strong> </strong></p>
<?php
include ("footer.inc");
 ?>