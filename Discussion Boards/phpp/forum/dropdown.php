<div id="drop">
<form name="drop" action="#" method="post">
<select name="forum" onchange="javascript:go()" class="forumchoose">
<option value="#"><? echo $txt_jumpto; ?></option>

<?

$admincheckq = mysql_query("SELECT * FROM ${table_prefix}userrights WHERE userid='$logincookie[user]' ");
$admincheck = mysql_result($admincheckq, 0, "admin");
$overalladmin = mysql_result($admincheckq, 0, "overalladmin");

$forums = mysql_query("SELECT * FROM ${table_prefix}forums ORDER BY fororder ASC");
for ($i = 0; $i < mysql_num_rows($forums); $i++) {

$restricted = mysql_result($forums, $i, "restricted");
$rights = mysql_query("SELECT * FROM ${table_prefix}userrights WHERE userid='$logincookie[user]'");
$rights = mysql_result($rights, 0, "access");
$j = $i + 1;
if(strstr($rights, " $j,")) {
$allowed = 1;
}
else {
$allowed = 0;
}

$forumno = mysql_result($forums, $i, "forumno");
$forumnm = mysql_result($forums, $i, "forumname");

if($restricted == 0 || ($admincheck == 1 || $allowed == 1)) {

echo "<option value=\"view.php?forum=$forumno\">$forumnm</option>";

}
}
?>
</select>
</form>
</div>