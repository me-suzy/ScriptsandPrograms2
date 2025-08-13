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

/* ---- DELETE ----*/
if(!$dbh)dbconnect();
@mysql_query("DELETE FROM authnet_batch",$dbh);
Header("Location: $page?op=menu&tile=billing&".session_id()."");
?>