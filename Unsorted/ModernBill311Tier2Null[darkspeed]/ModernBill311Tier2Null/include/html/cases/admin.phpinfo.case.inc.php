<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

$result = (!minimum_version("4.0.6")) ? "<font color=RED>".FAIL."</font>" : "<font color=GREEN>".PASS."</font>" ;
?>
<tr>
  <td>
    <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
     <tr>
       <td valign=top>
        <?=LFH?><b><?=PHPINFORMATION?>:</b><?=EF?>
        <br>
        <ul>
            <li> <a href=<?=$page?>?op=menu&tile=<?=$tile?>&type=PHP_INFO_ALL><b><?=PHP_INFO_ALL?></b></a></li>
            <li> <a href=<?=$page?>?op=menu&tile=<?=$tile?>&type=PHP_INFO_GENERAL><b><?=PHP_INFO_GENERAL?></b></a></li>
            <li> <a href=<?=$page?>?op=menu&tile=<?=$tile?>&type=PHP_INFO_CONFIGURATION><b><?=PHP_INFO_CONFIGURATION?></b></a></li>
            <li> <a href=<?=$page?>?op=menu&tile=<?=$tile?>&type=PHP_INFO_MODULES><b><?=PHP_INFO_MODULES?></b></a></li>
            <li> <a href=<?=$page?>?op=menu&tile=<?=$tile?>&type=PHP_INFO_ENVIRONMENT><b><?=PHP_INFO_ENVIRONMENT?></b></a></li>
            <li> <a href=<?=$page?>?op=menu&tile=<?=$tile?>&type=PHP_INFO_VARIABLES><b><?=PHP_INFO_VARIABLES?></b></a></li>
        </ul>
       </td>
       <td valign=top>
        <?=LFH?><b><?=PHPSETTINGS?>:</b><?=EF?>
        <br>
        <ul>
            <li> [<?=CURRENTPHPVERSION?>: <?=phpversion()." <b><i>$result</i></b>"?>]</li>
            <li> [safe_mode: <i><b><?=(ini_get("safe_mode"))?"<font color=RED>".ON."</font>":"<font color=GREEN>".OFF."</font>";?></b></i>]</li>
            <li> [safe_mode_exec_dir: <i><b><?=(ini_get("safe_mode_exec_dir"))?"<font color=RED>".ini_get("safe_mode_exec_dir")."</font>":"<font color=GREEN>".NOVALUE."</font>";?></b></i>]</li>
            <li> [session.use_cookies: <i><b><?=(ini_get("session.use_cookies"))?"<font color=GREEN>".ON."</font>":"<font color=RED>".OFF."</font>";?></b></i>]</li>
            <li> [php_sapi_name: <i><b><?=php_sapi_name()?></b></i>]</li>
        </ul>
       </td>
     </tr>
    </table>
  </td>
</tr>
<tr>
  <td>
<?
switch ($type) {
        case PHP_INFO_ALL:           echo phpinfo(INFO_ALL); break;
        case PHP_INFO_GENERAL:       echo phpinfo(INFO_GENERAL); break;
        case PHP_INFO_CONFIGURATION: echo phpinfo(INFO_CONFIGURATION); break;
        case PHP_INFO_MODULES:       echo phpinfo(INFO_MODULES); break;
        case PHP_INFO_ENVIRONMENT:   echo phpinfo(INFO_ENVIRONMENT); break;
        case PHP_INFO_VARIABLES:     echo phpinfo(INFO_VARIABLES); break;
}
?>
  </td>
</tr>