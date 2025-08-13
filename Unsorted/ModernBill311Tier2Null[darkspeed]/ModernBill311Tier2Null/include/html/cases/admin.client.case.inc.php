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

if(!$dbh)dbconnect();

if(isset($submit)&&isset($check_id)&&isset($client_status))
{
   for($i=0;$i<=count($check_id)-1;$i++){
       $old_status = mysql_one_data("SELECT client_status FROM client_info WHERE client_id = '$check_id[$i]'");
       $result     = mysql_query("UPDATE client_info SET client_status = '$client_status' WHERE client_id = '$check_id[$i]'");
       $details_view = 1;
       $log_comments = UPDATESTATUS.": ".status_select_box($old_status,NULL)." - ".status_select_box($client_status,NULL)." - ".$this_admin['admin_realname'];
       log_event($check_id[$i],$log_comments,1);
       $details_view = 0;
   }
}

$num_clients=$num_active_clients=$num_inactive_clients=$num_canceled_clients=$num_fraud_clients=0;
list($num_inactive_clients)=mysql_fetch_row(mysql_query("SELECT count(client_id) FROM client_info WHERE client_status=1"));
list($num_active_clients)=mysql_fetch_row(mysql_query("SELECT count(client_id) FROM client_info WHERE client_status=2"));
list($num_pending_clients)=mysql_fetch_row(mysql_query("SELECT count(client_id) FROM client_info WHERE client_status=3"));
list($num_canceled_clients)=mysql_fetch_row(mysql_query("SELECT count(client_id) FROM client_info WHERE client_status=4"));
list($num_fraud_clients)=mysql_fetch_row(mysql_query("SELECT count(client_id) FROM client_info WHERE client_status=5"));
$num_clients=$num_active_clients+$num_inactive_clients+$num_pending_clients+$num_canceled_clients+$num_fraud_clients;
?>
<tr>
  <td>
    <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
     <tr><td><?=LFH?><b><?=CLIENTSTATS?>:</b><?=EF?> [<a href=<?=$page?>?op=form&db_table=client_info&tile=<?=$tile?>><b><?=ADD?></b></a>]</td><td><?=LFH?><b><?=CLIENTSEARCH?>:</b><?=EF.SFB?> [<a href=<?=$page?>?op=view&db_table=client_info&tile=<?=$tile?>><b><?=VIEWALL?></b></a>]<?=EF?></td></tr>
     <tr>
       <td width=50% valign=top>
         <table>
          <tr><td>
               <?=MFB?>
               <?=CLIENTS?>:<br>
               <?=ACTIVE?>:<br>
               <?=INACTIVE?>:<br>
               <?=PENDING?>:<br>
               <?=CANCELED?>:<br>
               <?=FRAUD?>:<br>
               <?=EF?>
              </td>
              <td align=right>
               <?=MFB?>
               [<a href=<?=$page?>?op=view&db_table=client_info&tile=<?=$tile?>><?=$num_clients?></a>]<br>
               [<a href=<?=$page?>?op=view&db_table=client_info&tile=<?=$tile?>&where=<?=urlencode("WHERE client_status=2")?>><?=$num_active_clients?></a>]<br>
               [<a href=<?=$page?>?op=view&db_table=client_info&tile=<?=$tile?>&where=<?=urlencode("WHERE client_status=1")?>><?=$num_inactive_clients?></a>]<br>
               [<a href=<?=$page?>?op=view&db_table=client_info&tile=<?=$tile?>&where=<?=urlencode("WHERE client_status=3")?>><?=$num_pending_clients?></a>]<br>
               [<a href=<?=$page?>?op=view&db_table=client_info&tile=<?=$tile?>&where=<?=urlencode("WHERE client_status=4")?>><?=$num_canceled_clients?></a>]<br>
               [<a href=<?=$page?>?op=view&db_table=client_info&tile=<?=$tile?>&where=<?=urlencode("WHERE client_status=5")?>><?=$num_fraud_clients?></a>]<br>
               <?=EF?>
              </td>
              <td align=right>
               <?=MFB?>
               <br>
               &nbsp;<br>
               &nbsp;<br>
               &nbsp;<br>
               &nbsp;<br>
               &nbsp;<br>
               <?=EF?>
              </td>
           </tr>
          </table>
       </td>
       <td width=50% valign=top>
          <table>
          <form method=post action=<?=$page?>>
          <input type=hidden name=op value=view>
          <input type=hidden name=search value=1>
          <input type=hidden name=tile value=<?=$tile?>>
          <input type=hidden name=db_table value=client_info>
          <tr><td colspan=2><?=search_select_box();?></td></tr>
          <tr><td><input type=text name=query size=15 maxlength=25></td><td><?=GO_IMG?></td></tr>
          </form>
          </table>
          <br>

       </td>
     </tr>
    </table>
   <hr size=1 width=98%>
  </td>
</tr>
<tr>
  <td>
  <?=start_box($num_pending_clients." ".PENDING)?>
  <?
  switch ($type) {
          default:
               $recursive    = 0;
               $details_view = 1;
               $selectlimit  = 15;
               $db_table     = "client_info";
               $where        = "WHERE client_status=3";
               $order        = "client_id";
               $sort         = "DESC";
               include("include/db_attributes.inc.php");
               start_table(NULL,"100%");
               echo "<form method=post action=$page?op=menu&tile=client&".session_id().">";
               echo "<tr><td>";
               $details_view = 1;
               $display_checkboxes = 1;
               display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
               echo "</td></tr>";
               if ($this_num_results > $selectlimit) echo "<tr><td align=center><b>".MFB."<a href=$page?op=view&db_table=$db_table&tile=$tile&where=".urlencode($where).">".VIEWALL." $this_num_results</a>".EF."</b></td></tr>";
               $details_view = 0;
               if ($this_num_results) echo "<tr><td align=left>".status_select_box(2,"client_status")."&nbsp;<input type=submit name=submit value=\"".UPDATESTATUS."\"></td></tr>";
               echo "</form>";
               stop_table();
               $recursive=$recursive_sql=NULL;
          break;
  }
  ?>
  <?=stop_box()?>
  </td>
</tr>