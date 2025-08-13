<?
include_once("../include/config/config.locale.php");
?>
<center><b>Your Current Database Settings:</b></center><br><br>
<b>FILE:</b> path_to_modernbill/include/config/<b>config.locale.php</b><br><br>
<table>
<tr><td>$locale_db_host  </td><td>= "<b><?=$locale_db_host?></b>"; </td><td># <-- Your DB HOST</td></tr>
<tr><td>$locale_db_login </td><td>= "<b><?=$locale_db_login?></b>";</td><td># <-- Your DB LOGIN NAME</td></tr>
<tr><td>$locale_db_pass  </td><td>= "<b><?=$locale_db_pass?></b>"; </td><td># <-- Your DB LOGIN PASSWORD</td></tr>
<tr><td>$locale_db_name  </td><td>= "<b><?=$locale_db_name?></b>"; </td><td># <-- Your DB NAME</td></tr>
</table>
<br>
<b>Results:</b>
<ul>
<?
if (mysql_pconnect($locale_db_host,$locale_db_login,$locale_db_pass))
{
    echo "<li>--> <font color=blue>$locale_db_host database connection <b>OK</b>.</font>";
}
else
{
    echo "<li>--> <font color=red>ERROR: $locale_db_host database connection <b>NOT OK</b>.</font>";
    $stop=1;
}
if (mysql_select_db($locale_db_name))
{
    echo "<li>--> <font color=blue>$locale_db_name database connection <b>OK</b>.</font>";
}
else
{
    echo "<li>--> <font color=red>ERROR: $locale_db_name database connection <b>NOT OK</b>.</font>";
    $stop=1;
}
?>
</ul>
<center><b>
<? if ($stop) { ?>
<font color=red>You must correct the error(s) above before you can continue!</font>
<? } else { ?>
<font color=blue>No errors detected. Your database is ready to go!</font>
<? } ?>
</b></center>
<br>
