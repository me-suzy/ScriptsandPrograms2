<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: manage.php
// Version 4.6
// Add a new item
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
// Commercial Site: www.blackwidows.co.uk
/************************************************************************/
// stop errors on multiple session_start()
if(session_id() == ""){
  session_start();
}
header("Cache-control: private"); // IE 6 Fix.
include("../functions.php");
include("admin-header.php");
Authenticate();
UserStatus();
include ("../config.php");
include("admin-menu.php");
?>
<h2>Manage SAXON users</h2>
<p>Individuals with SAXON Super User status can use this area to administer existing SAXON users including:</p>
<ul>
<li>Adding new users</li>
<li>Deleting existing users</li>
<li>Changing passwords</li>
<li>Upgrading, or downgrading, users for admin purposes.</li>
</ul>
<p><strong>The original Admin user cannot be deleted! However, in the interests of security, you should change 
the Main Admin password from the original default.</strong></p>
<p>The Main Menu link will take you back to the News Administration pages.</p>
<?php
include("../footer.php");
?>