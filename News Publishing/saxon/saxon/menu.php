<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: menu.php
// Version 4.6
// Display All News for a web page
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
// Commercial Site: www.blackwidows.co.uk
/************************************************************************/
?>
<div id="menu">
<ul>
<li><a href="add.php" title="Add a fresh news item">Add</a></li>
<li><a href="edit.php" title="Amend existing news items">Edit</a></li>
<li><a href="delete.php" title="Delete existing news items">Delete</a></li>
<li><a href="preview.php" title="View all items currently in the database">Preview All</a></li>
<li><a href="<?php echo $news_url; ?>" title="View the main news page on your site">News Page</a></li>
<?php
if($_SESSION['super_user']=="Y")
{
?>
<li><a href="admin/manage.php" title="Manage your users">Admin</a></li>
<?php
}
?>
<li><a href="help.php">Help</a></li>
<li><a href="logout.php">Logout</a></li>
</ul>
</div>

<!-- start content -->
<div id="content">
<a id="main-content" href="#main-content"></a>
