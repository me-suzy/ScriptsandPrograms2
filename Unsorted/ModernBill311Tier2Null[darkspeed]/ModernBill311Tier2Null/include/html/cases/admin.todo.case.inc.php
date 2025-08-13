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
//array("1"=>"New","2"=>"WIP","3"=>"Pending","4"=>"Completed","5"=>"PostPoned");
$num_todo=$num_new=$num_wip=$num_pen=$num_com=$num_pos=0;
list($num_new)=mysql_fetch_row(mysql_query("SELECT count(todo_id) FROM todo_list WHERE todo_status=1"));
list($num_wip)=mysql_fetch_row(mysql_query("SELECT count(todo_id) FROM todo_list WHERE todo_status=2"));
list($num_pen)=mysql_fetch_row(mysql_query("SELECT count(todo_id) FROM todo_list WHERE todo_status=3"));
list($num_com)=mysql_fetch_row(mysql_query("SELECT count(todo_id) FROM todo_list WHERE todo_status=4"));
list($num_pos)=mysql_fetch_row(mysql_query("SELECT count(todo_id) FROM todo_list WHERE todo_status=5"));
$num_todo=$num_new+$num_wip+$num_pen+$num_com+$num_pos;
  ?>
<tr>
 <td><?=LFH?><b><?=TODOSTATS?>:</b><?=EF.SFB?> [<a href=<?=$page?>?op=form&db_table=todo_list&tile=<?=$tile?>><b><?=ADD?></b></a>]<?=EF?></td>
 <td><?=LFH?><b><?=TODOSEARCH?>:</b><?=EF.SFB?> [<a href=<?=$page?>?op=view&db_table=todo_list&tile=<?=$tile?>><b><?=VIEWALL?></b></a>]<?=EF?></td>
</tr>
<tr>
 <td width=50% valign=top>
   <table>
    <tr><td>
         <?=MFB?>
         <?=TOTALTODO?>:<br>
         <?=TOTALNEW?>:<br>
         <?=TOTALWIP?>:<br>
         <?=TOTALPENDING?>:<br>
         <?=TOTALCOMPLETED?>:<br>
         <?=TOTALPOSTPONED?>:<br>
         <?=EF?>
        </td>
        <td align=right>
         <?=MFB?>
         [<a href=<?=$page?>?op=view&db_table=todo_list&tile=<?=$tile?>><?=$num_todo?></a>]<br>
         [<a href=<?=$page?>?op=view&db_table=todo_list&tile=<?=$tile?>&where=<?=urlencode("WHERE todo_status=1")?>><?=$num_new?></a>]<br>
         [<a href=<?=$page?>?op=view&db_table=todo_list&tile=<?=$tile?>&where=<?=urlencode("WHERE todo_status=2")?>><?=$num_wip?></a>]<br>
         [<a href=<?=$page?>?op=view&db_table=todo_list&tile=<?=$tile?>&where=<?=urlencode("WHERE todo_status=3")?>><?=$num_pen?></a>]<br>
         [<a href=<?=$page?>?op=view&db_table=todo_list&tile=<?=$tile?>&where=<?=urlencode("WHERE todo_status=4")?>><?=$num_com?></a>]<br>
         [<a href=<?=$page?>?op=view&db_table=todo_list&tile=<?=$tile?>&where=<?=urlencode("WHERE todo_status=5")?>><?=$num_pos?></a>]<br>
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
    <input type=hidden name=db_table value=todo_list>
    <tr><td colspan=2><?=todo_search_select_box();?></td></tr>
    <tr><td><input type=text name=query size=15 maxlength=25></td><td><?=SEARCH_IMG?></td></tr>
    </form>
    </table>
 </td>
</tr>
<tr>
  <td colspan=2>
  <?
  start_box(TOTALNEW.": ".$num_new);
  switch ($type) {
          default:
               $db_table     = "todo_list";
               $recursive    = 0;
               $details_view = 1;
               $selectlimit  = 10;
               $where        = "WHERE todo_status = 1";
               include("include/db_attributes.inc.php");
               start_table(NULL,"100%");
               echo "<tr><td>";
               $details_view = 1;
               display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
               echo "</td></tr>";
               if ($this_num_results > $selectlimit) echo "<tr><td align=center><b>".MFB."<a href=$page?op=view&db_table=$db_table&tile=$tile&where=".urlencode($where).">".VIEWALL." $this_num_results</a>".EF."</b></td></tr>";
               stop_table();
               $recursive=$recursive_sql=NULL;
          break;
  }
  stop_box();
  ?>
  </td>
</tr>