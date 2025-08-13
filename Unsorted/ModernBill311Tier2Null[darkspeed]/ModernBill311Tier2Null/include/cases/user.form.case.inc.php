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
if (!testlogin()||!$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }


        $db_table = "client_info";
        include("include/db_attributes.inc.php");

        validate_table($db_table,1); if(isset($error)) return;
        $hidden=NULL;
        start_html();
          echo "<form method=post action=$page?op=form_response&".session_id().">";
          user_heading($tile);
          start_table(NULL,$u_tile_width,"center","#999999");
        if(isset($this_user[0])){
           $sql="SELECT * FROM $db_table WHERE client_id=$this_user[0]";
           if($debug)echo SFB.$sql.EF."<br>"; //exit;
           addslashes($result = mysql_query($sql,$dbh));
           $do="edit"; $hidden.="<input type=hidden and name=id value=$this_user[0]>";
          if($table_no_edit) {
            echo "<tr><td colspan=2 align=center>".SFB.THETABLE1." [$db_table] ".THETABLE2.EF."</td></tr>";
          } else {
            build_form($args,$result);
            echo "<tr><td colspan=2 align=center>".SUBMIT_IMG."$hidden</td></tr>";
            echo "<input type=hidden name=tile value=$tile>";
          }
        } else {
        echo "<tr><td align=center>
                  <blockquote>
                  ".MFB.ERRORPLEASELOGIN.EF."
                  </blocquote>
             </td></tr>";
        }
          stop_table();
         stop_form();
        stop_html();
?>