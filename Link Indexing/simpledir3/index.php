<?php
// Include SimpleDir stuff & header
require("config.php");
require("common.php");
// Smarty stuff
require_once('smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign("headfile",$headfile);
$smarty->assign("footfile",$footfile);
$smarty->assign("sdversion",$sdversion);
$smarty->assign("self",$self);
$smarty->assign("pend",$pend);
$smarty->assign("allowdesc",$allowdesc);
$smarty->assign("sitename",$sitename);
$smarty->assign("adminname",$adminname);
$smarty->assign("adminemail",$adminemail);
$smarty->assign("siteurl",$siteurl);
// Include header
$smarty->display("header.tpl");
?>

<p>Welcome to <?=$sitename?>. This is a site directory.</p>
<p><a href="add.php">Add A Link</a> | <a href="add.php?modify">Modify A Link</a> | <a href="listing.php">View Links</a></p>

<?php
$linkname = '';     $linkurl = '';
snippetRandLink($linkname,$linkurl);
?>
<p>Random link: <a href="<?=$linkurl?>" target="_blank"><?=$linkname?></a></p>

<?php $smarty->display("footer.tpl"); ?>