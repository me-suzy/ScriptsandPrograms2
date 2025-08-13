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


/* ---- CHANGE CC NUMBER/EXP DATE RESPONSE ----*/
// UPDATE CC (PART 2)
// D O   N O T   M O D I F Y
        GLOBAL $data;

        // validate and clean form input
        include("include/misc/validate_form_input.inc.php");

        // validate client password
        $result = mysql_query("SELECT * FROM client_info WHERE client_id='".$this_user[0]."' AND client_password='".md5(strip_tags($password))."'",$dbh) or die (mysql_error());
        if (!$result || mysql_num_rows($result) != 1) $oops = "[".ERROR."] ".INVALIDPASSWORD."!<br>";
        if (!$billing_cc_exp) $oops .= "[".REQUIRED."] ".EXPIRATIONDATE."<br>";

           // validate credit card type, encrypt cc
           $billing_cc_type=validate_cc_input($billing_cc_num,NULL);
           if ($billing_cc_type&&$data) {
               $sql="SELECT * FROM client_info WHERE client_id=$this_user[0]";
               if($debug)echo SFB.$sql.EF."<br>";
               $result = mysql_query($sql,$dbh);
               $this_client = mysql_fetch_array($result);
               $billing_cc_num=encrpyt($this_client['client_stamp'],$data);
           } else {
               $oops .= "[".ERROR."] ".CCNUMINVALID."<br>";
           }

        if ($oops) {
             start_html();
             echo "<form method=post action=$page?op=update_cc_response&".session_id().">";
             user_heading($tile);
             start_table(UPDATECC,$u_tile_width);
                  echo "<tr><td colspan=2>".SFB.$oops.EF."<hr noshade></td></tr>";
                  echo "<tr><td align=right width=35%>".SFB."<b>".NEWCC.":</b>".EF."</td>
                          <td><input type=TEXT name=billing_cc_num size=16 maxlength=20> ".SFB."(".$we_accept.")".EF."</td></tr>";
                  echo "<tr><td align=right width=35%>".SFB."<b>".NEWEXPDATE.":</b>".EF."</td>
                          <td><input type=TEXT name=billing_cc_exp size=7 maxlength=7> ".SFB."(".DATEFORMAT.")".EF."</td></tr>";
                  echo "<!--<tr><td align=right width=35%>".SFB."<b>CVV2/CVC2:</b>".EF."</td>
                          <td><input type=TEXT name=billing_cc_code size=4 maxlength=3> ".SFB."(".THREEDIGIT.")".EF."</td></tr>-->";
                  echo "<tr><td align=right width=35%>".SFB."<b>".YOURPW.":</b>".EF."</td>
                          <td><input type=password name=password size=15 maxlength=15></td></tr>";
                  echo "<tr><td colspan=2><center>".SUBMIT_IMG."</center></td></tr>";
             stop_table();
             stop_form();
             stop_html();
        } else {
            $cc_sql = "UPDATE client_info SET billing_cc_type='$billing_cc_type', billing_cc_num='$billing_cc_num', billing_cc_exp='$billing_cc_exp', billing_cc_code='$billing_cc_code' WHERE client_id=$this_user[0]";
            if ($debug) echo SFB.$cc_sql.EF."<br>";
            if (!mysql_query($cc_sql,$dbh)) {
               echo mysql_errno(). ": ".mysql_error(). "<br>"; return;
            } else {
               ## ENTER TODO NOTE :: NO NEED TO TRANSLATE
               $todo_title = "*UPDATE CC*";
               $client_id  = $this_user[0];
               $todo_stamp = mktime();
               $todo_desc  = "UPDATE CC BY CLIENT [$client_id]\nPlease <a href=$admin_page?op=quick_encrypt&tile=todo&id=$client_id&stamp=$todo_stamp>click here</a> or paste this url [$admin_page?op=quick_encrypt&tile=todo&id=$client_id&stamp=$todo_stamp] to encrypt $client_fname $client_lname\'s new credit card before the next batch!";
               $insert_sql = "INSERT INTO todo_list (todo_id,todo_title,todo_desc,admin_id,todo_status,todo_due,todo_stamp) VALUES (NULL,'$todo_title','$todo_desc','$client_id','1','$todo_stamp','$todo_stamp')";
               @mysql_query($insert_sql,$dbh);
               /*
               $todo_title = "** UPDATE CC NUMBER **";
               $todo_desc  = "Please <a href=$admin_page?op=quick_encrypt&tile=todo&id=$this_user[0]>click here</a> or paste this url [$admin_page?op=quick_encrypt&tile=todo&id=$this_user[0]] to encrypt ".$this_user["client_fname"]." ".$this_user["client_lname"]."\'s new credit card before the next batch!";
               $insert_sql = "INSERT INTO todo_list (todo_id,
                                                     todo_title,
                                                     todo_desc,
                                                     admin_id,
                                                     todo_status,
                                                     todo_due,
                                                     todo_stamp) VALUES (NULL,
                                                                        '$todo_title',
                                                                        '$todo_desc',
                                                                        '1',
                                                                        '1',
                                                                        '".mktime()."',
                                                                        '".mktime()."')";
               @mysql_query($insert_sql,$dbh);
               */
               header("Location: $page?op=menu&tile=myinfo&".session_id()."");
            }
        }
?>