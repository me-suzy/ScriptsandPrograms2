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


/* ---- CHANGE CC NUMBER/EXP DATE ----*/
// UPDATE CC (PART 1)
// D O   N O T   M O D I F Y

        // validate and clean form input
        include("include/misc/validate_form_input.inc.php");

        // Get client info
        $id=explode("|",$id);
        $sql="SELECT * FROM client_info WHERE $id[0]=$id[1]";
        if($debug)echo SFB.$sql.EF."<br>";
        addslashes($result = mysql_query($sql,$dbh));
        $this_client = mysql_fetch_array($result);
        $show_this_client = MFB."<b>".CLIENTID." [<a href=$page?op=client_details&db_table=$db_table&tile=$tile&id=$id[0]|$id[1]>".$id[1]."</a>]: ".$this_client['client_fname']." ".$this_client['client_lname']." <a href=mailto:".$this_client['client_email'].">".$this_client['client_email']."</a></b>".EF;


        // validate admin password
        $result = mysql_query("SELECT * FROM admin WHERE admin_username='".$this_admin["admin_username"]."' AND admin_password='".md5(strip_tags($password))."'",$dbh) or die (mysql_error());
        if (!$result || mysql_num_rows($result) != 1) $oops = "[".ERROR."] ".YOURPWINVALID."!<br>";
        if (strlen(strval($client_password))<6) $oops .= "[".ERROR."] ".CLIENTPWTOOSHORT."!<br>";
        if ($client_password!=$client_password_2) $oops .= "[".ERROR."] ".CLIENTPWNOMATCH."<br>";

        if ($oops) {
             start_html();
              start_form("change_pw_response",NULL);
               admin_heading($tile);
               start_table(CHANGECLIENTPW,$a_tile_width);
                echo "<tr><td align=center colspan=2>$show_this_client<hr noshade></td></tr>";
                echo "<tr><td>".SFB.$oops.EF."</td></tr>";
                echo "<tr><td align=right width=35%>".SFB."<b>".NEWPW.":</b>".EF."</td>
                          <td><input type=password name=client_password size=15 maxlength=15> ".SFB.PWFORMAT.EF."</td></tr>";
                echo "<tr><td align=right width=35%>".SFB."<b>".VERIFYPW.":</b>".EF."</td>
                          <td><input type=password name=client_password_2 size=15 maxlength=15></td></tr>";
                echo "<tr><td align=right width=35%>".SFB."<b>".YOURPW.":</b>".EF."</td>
                          <td><input type=password name=password value=\"$password\" size=15 maxlength=15></td></tr>";
                echo "<tr><td colspan=2><center>".SUBMIT_IMG."</center><input type=hidden name=id value=$id[0]|$id[1]><input type=hidden name=db_table value=$db_table></td></tr>";

               stop_table();
              stop_form();
             stop_html();
        } else {
          $cc_sql = "UPDATE client_info SET client_password='".md5($client_password)."',client_real_pass='$client_password' WHERE client_id = $id[1]";
          if($debug)echo SFB.$cc_sql.EF."<br>";
          if (!mysql_query($cc_sql,$dbh)) {
               echo mysql_errno(). ": ".mysql_error(). "<br>"; return;
          } else {
               Header("Location: $page?op=client_details&db_table=client_info&id=$id[0]|$id[1]&".session_id()."");
          }
        }
?>