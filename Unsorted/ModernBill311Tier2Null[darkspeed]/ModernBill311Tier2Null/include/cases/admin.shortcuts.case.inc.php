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

start_html();
start_table(EMAILSHORTCUTS,"600");
echo "<tr><td>";
include("include/html/email_shortcuts.inc.php");
echo "</td></tr>";
stop_table();
stop_html(0);
?>
