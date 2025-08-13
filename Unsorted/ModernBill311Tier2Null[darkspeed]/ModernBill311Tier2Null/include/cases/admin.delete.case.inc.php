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
$ap = ($this_admin["admin_level"]==9) ? $this_admin["ap"] : NULL ;

/* ---- DELETE ----*/
// THIS IS THE MASTER CASE THAT WILL HANDLE ALL DELETES
        validate_table($db_table,1); if(isset($error)) return;
        if(isset($id)){
           $id=explode("|",$id);
           ${$id[0]}=$id[1];
           include("include/db_attributes.inc.php");
           for($i=0;$i<=count($delete_sql);$i++){
            $to_be_deleted.=$delete_sql[$i]."<br>";
           }
        } else {
           return;
        }
        start_html();
        if ($debug) echo SFB.$to_be_deleted.EF."<br>";
        admin_heading($tile);
        start_table(VERIFYDEL,$a_tile_width);
        start_form("delete_response",$db_table);
             echo "<tr><td align=right width=50%>".SFB.PWREQUIRED.EF."</td><td><input type=password name=password value=\"$ap\" size=15 maxlength=15></td></tr>";
             echo "<tr><td colspan=2>".SFB.$to_be_deleted.EF."<center>".CONT2_IMG."</center></td></tr>";
             echo "<input type=hidden name=vars value=\"$db_table|$id[0]|$id[1]\">";
             echo "<input type=hidden name=tile value=\"$tile\">";
             echo "<input type=hidden name=from value=\"$from\">";
        stop_form();
        start_form("view",$db_table);
             echo "<tr><td colspan=2><hr size=1></td></tr>";
             echo "<tr><td colspan=2><center>".CANCEL_IMG."</center></td></tr>";
        stop_form();
        stop_table();
        stop_html();
?>