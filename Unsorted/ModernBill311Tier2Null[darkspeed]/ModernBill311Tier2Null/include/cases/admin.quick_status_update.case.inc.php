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

        // validate and clean form input
        include("include/misc/validate_form_input.inc.php");

        if (!$client_id||!$new_status_id||!$this_status_id)
        {
            header("Location: $admin_page?".session_id()."");
        }
        else
        {
            $sql    = "UPDATE client_info SET client_status='$new_status_id' WHERE client_id='$client_id'";
            $result = mysql_query($sql,$dbh);
            $details_view = 1;
            $log_comments = UPDATESTATUS.": ".status_select_box($this_status_id,NULL)." - ".status_select_box($new_status_id,NULL)." - ".$this_admin['admin_realname'];
            log_event($client_id,$log_comments,1);
            header("Location: $page?op=client_details&db_table=client_info&tile=$tile&id=client_id|$client_id&".session_id()."");
        }

?>