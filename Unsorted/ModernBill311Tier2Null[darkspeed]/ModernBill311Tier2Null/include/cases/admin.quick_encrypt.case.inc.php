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

if ($this_admin[admin_level]<8)
{
    start_short_html($title);
    echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>".MFB.ACCESSDENIED.EF."</b>&nbsp;&nbsp;&nbsp;&nbsp;";
    stop_short_html();
    exit;
}
/* ---- ENCRYPT CC NUMBER ----*/
// D O   N O T   M O D I F Y

        $db_table = "client_info";
        $sql      = "SELECT * FROM $db_table WHERE client_id=$id";
        if($debug) echo SFB.$sql.EF."<br>";
        addslashes($result = mysql_query($sql,$dbh));
        $this_client = mysql_fetch_array($result);
        $this_client = MFB."<b>".CLIENTID." [<a href=$page?op=client_details&db_table=$db_table&tile=$tile&id=client_id|$id>".$id."</a>]: ".$this_client['client_fname']." ".$this_client['client_lname']." &lt;<a href=mailto:".$this_client['client_email'].">".$this_client['client_email']."</a>&gt;</b>".EF;
             start_html();
             start_form("quick_encrypt_response",$db_table);
             admin_heading($tile);
             start_table(ENCRYPTCC,$a_tile_width);
                  echo "<tr><td align=center colspan=2>$this_client<hr noshade></td></tr>";
                  echo "<tr><td align=right width=35%>".SFB."<b>".ENCRYPTIONKEY.":</b>".EF."</td>
                          <td><textarea name=encryption_key rows=8 cols=40 maxlength=1000></textarea></td></tr>";
                  echo "<tr><td align=right width=35%>".SFB."<b>".YOURPW.":</b>".EF."</td>
                          <td><input type=password name=password value=\"$ap\" size=15 maxlength=15></td></tr>";
                  echo "<tr><td colspan=2><center>".SUBMIT_IMG."</center>
                             <input type=hidden name=id value=client_id|$id>
                             <input type=hidden name=stamp value=$stamp></td></tr>";
             stop_table();
             stop_form();
             stop_html();
?>