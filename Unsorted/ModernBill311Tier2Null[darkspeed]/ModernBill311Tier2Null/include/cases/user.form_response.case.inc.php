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


        $db_table = "client_info";
        if(!testlogin()) header_location("index.php","top");
        validate_table($db_table,1); if(isset($error)) return;
        $oops=NULL;
        if ($db_table!="faq_questions") {
            include("include/misc/validate_form_input.inc.php");
        }
        $submit=1;
        $do="edit";
        include("include/db_attributes.inc.php");
        $i=0;
        foreach ($args as $value) {
          if (!${$value["column"]}&&$args[$i]["required"]==1) {
            $oops.="[".REQUIRED."] ".$args[$i]["title"]."<br>";
          }
        $i++;
        }
        $sql=($do=="edit") ? $client_update_sql : $insert_sql ;
        $hidden="<input type=hidden and name=do value=$do><input type=hidden name=id value=$id>";
        if($debug)echo SFB.$sql.EF."<br>";
        if(isset($oops)){
                start_html();
                  echo "<form method=post action=$page?op=form_response&tile=$tile&".session_id().">";
                     user_heading($tile);
                     start_table(NULL,$u_tile_width,"center","#999999");
                     echo "<tr><td colspan=2><center>".SFB.PLEASEFILLIN."</center><br><br>$oops<hr size=1>".EF."</td></tr>";
                     build_form($args,$result);
                     echo "<tr><td colspan=2><center>".SUBMIT_IMG."</center>$hidden</td></tr>";
                    stop_table();
                  stop_form();
                stop_html();
        } elseif (!mysql_query($sql,$dbh)) {
          echo mysql_errno(). ": ".mysql_error(). "<br>"; return;
        } else {
          header("Location: $page?op=details&tile=myinfo&".session_id()."");
        }
?>