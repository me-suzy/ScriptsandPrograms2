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


/* ---- DISPLAY ALL FORMS ----*/
// THIS IS THE MASTER CASE THAT WILL DISPLAY ALL FORMS
// AND READS THE $ARGS ARRAY FOR EACH TABLE
        validate_table($db_table,1); if (isset($error)) return;
        $hidden=NULL;

        // If $id isset, we are editing and need to SELECT
        if(isset($id)){
           $id = explode("|",$id);
           $sql = "SELECT * FROM $db_table WHERE $id[0]='$id[1]'";
           if ($debug) echo SFB.$sql.EF."<br>";
           addslashes($result = mysql_query($sql,$dbh));
           $hidden.="<input type=hidden name=id value=$id[0]|$id[1]>";
           $do = "edit";
        } else {
           $do = "add";
        }

        // Build The form Dynamically with our without the SELECT variables
        start_html();
        admin_heading($tile);
        start_form("form_response",$db_table);
        $do_disp = ($do=="add") ? DOADD : DOEDIT ;
        start_table(FORM.": $title [$do_disp]",$a_tile_width);
             if ($do=="edit"&&$table_no_edit) { // Editing NOT Allowed
                 echo "<tr><td colspan=2 align=center>".SFB.THETABLE1." [$db_table] ".THETABLE2.EF."</td></tr>";
             } else {
                 build_form($args,$result);
                 echo "<tr><td colspan=2 align=center>".SUBMIT_IMG."$hidden</td></tr>";
                 echo "<input type=hidden name=do value=\"$do\">";
                 echo "<input type=hidden name=from value=\"$from\">";
                 echo "<input type=hidden name=tile value=\"$tile\">";
             }
        stop_table();
        stop_form();
        stop_html();
?>