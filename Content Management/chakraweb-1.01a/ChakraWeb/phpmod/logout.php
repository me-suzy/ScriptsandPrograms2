<?php 
// ----------------------------------------------------------------------
// ModName: logout.php
// Purpose: Process logout from website
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

UserLogout();

Header("Location: /index.html");

?>
