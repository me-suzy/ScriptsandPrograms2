<?
if ($action != "login" && $action != "logout") {
echo "<div class=\"loginline\">";
if (isset($logincookie[user])) {
echo "$txt_loggedas <font class=\"emph\">$logincookie[user]</font> $split ";

if ($overalladmin == 1) {
echo "<a href=\"admin.php\" class=\"log\">$txt_admincentre</a> $split ";
}

echo "<a href=\"logged.php?action=logout\" class=\"log\">$txt_logout</a>";
}
else {
echo "<a href=\"login.php\" class=\"log\">$txt_login</a>";
}
echo "</div>";
}
?>