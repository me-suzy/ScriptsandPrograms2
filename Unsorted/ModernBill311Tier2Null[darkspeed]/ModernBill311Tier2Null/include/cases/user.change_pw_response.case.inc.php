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

## Validate that the user
if (!testlogin()||!$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }


/* ---- CHANGE CC NUMBER/EXP DATE ----*/
// UPDATE CC (PART 1)
// D O   N O T   M O D I F Y

        // validate and clean form input
        include("include/misc/validate_form_input.inc.php");

        $result = mysql_query("SELECT * FROM client_info WHERE client_id='".$this_user[0]."' AND client_password='".md5(strip_tags($password))."'",$dbh) or die (mysql_error());
        if (!$result || mysql_num_rows($result) != 1) $oops = "[".ERROR."] ".YOURPWINVALID."!<br>";
        if (strlen(strval($client_password))<6) $oops .= "[".ERROR."] ".NEWPWSHORT."<br>";
        if ($client_password!=$client_password_2) $oops .= "[".ERROR."]".NEWPWMATCH."!<br>";

        if ($oops) {
            start_html();
             echo "<form method=post action=$page?op=change_pw_response&".session_id().">";
               user_heading(CHANGEMYPASSWORD);
               start_table($title,$u_tile_width);
                echo "<tr><td colspan=2>".SFB.$oops.EF."<hr noshade></td></tr>";
                echo "<tr><td align=right width=35%>".SFB."<b>".NEWPW.":</b>".EF."</td>
                          <td><input type=password name=client_password size=15 maxlength=15> ".SFB.PWFORMAT.EF."</td></tr>";
                echo "<tr><td align=right width=35%>".SFB."<b>".VERIFYPW.":</b>".EF."</td>
                          <td><input type=password name=client_password_2 size=15 maxlength=15></td></tr>";
                echo "<tr><td align=right width=35%>".SFB."<b>".CURRENTPW.":</b>".EF."</td>
                          <td><input type=password name=password size=15 maxlength=15></td></tr>";
                echo "<tr><td colspan=2><center>".SUBMIT_IMG."</center></td></tr>";
               stop_table();
              stop_form();
             stop_html();
        } else {
          $sql = "UPDATE client_info SET client_password='".md5($client_password)."' WHERE client_id=$this_user[0]";
          if($debug)echo SFB.$sql.EF."<br>";
          if (!mysql_query($sql,$dbh)) {
               echo mysql_errno(). ": ".mysql_error(). "<br>"; return;
            } else {
               header("Location: $page?op=menu&tile=myinfo&".session_id()."");
            }
        }
?>