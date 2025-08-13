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


## LOGIN AS A CLIENT THIS_USER!

        $sql = "SELECT * FROM client_info WHERE client_id=$id";
        $result = mysql_query($sql,$dbh) or die (mysql_error());
            if (!$result || mysql_num_rows($result) < 1){
                Header("Location: http://$standard_url?op=logout");
                exit;
            } else {
                session_register('this_user');
                $this_user = mysql_fetch_array($result);
                Header("Location: $https://$secure_url"."$user_page?op=menu&tile=myinfo&".session_id()."");
                exit;
            }
?>