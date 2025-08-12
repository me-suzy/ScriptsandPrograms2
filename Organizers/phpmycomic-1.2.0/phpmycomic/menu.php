<?php

// Get the menu items and links
$tpl->assign("menu_index", 'index.php');
$tpl->assign("menu_search", 'search.php');
$tpl->assign("menu_addcomic", 'addcomic.php?a=Unknown Series&b=0&c=0&d=0&e=0&f=Unknown Publisher&g=Ongoing Series&h=Unknown Genre&i=Softcover&j=Mint&k=0.00&l=0.00&m=Unknown Writer&n=Unknown Inker&o=Unknown Penciler&p=Unknown Coverartist&q=Unknown Colorist&r=noimage.jpg&s=English&t=&u=Unknown Story&v=None&w=&x=&y=&z=Unknown Letterer&cur=USD');
$tpl->assign("menu_addartist", 'addartist.php');
$tpl->assign("menu_addmulti", 'addmulti.php?a=Unknown Series&f=Unknown Publisher&g=Ongoing Series&h=Unknown Genre&i=Softcover&s=English&cur=USD');
$tpl->assign("menu_tools", 'tools.php');
$tpl->assign("menu_about", 'about.php');
$tpl->assign("menu_login", 'login.php');
$tpl->assign("menu_logout", 'function.php?cmd=logout');

?>