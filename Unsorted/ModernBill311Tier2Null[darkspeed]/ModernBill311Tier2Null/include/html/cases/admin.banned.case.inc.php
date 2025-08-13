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
$db_table  = "banned_config";
?>
<tr>
  <td>
  <?=LFH?><b><?=BANNEDIP?>:</b><?=EF?> [<a href=<?=$page?>?op=form&db_table=<?=$db_table?>&tile=<?=$tile?>><b><?=ADD?></b></a>]&nbsp;<!--[<a href=<?=$page?>?op=menu&tile=<?=$tile?>&type=stats><b><?=STATS?></b></a>]--><br>
  <?
  switch ($type) {
          case stats:

          break;

          default:
               $recursive    = 0;
               $details_view = 1;
               $selectlimit  = 20;
               $where        = "WHERE ban_type = '1' ";
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
  ?>
  </td>
</tr>
<tr><td><hr size=1></td></tr>
<tr>
  <td>
  <?=LFH?><b><?=BANNEDEMAIL?>:</b><?=EF?> [<a href=<?=$page?>?op=form&db_table=<?=$db_table?>&tile=<?=$tile?>><b><?=ADD?></b></a>]&nbsp;<!--[<a href=<?=$page?>?op=menu&tile=<?=$tile?>&type=stats><b><?=STATS?></b></a>]--><br>
  <?
  switch ($type) {
          case stats:

          break;

          default:
               $recursive    = 0;
               $details_view = 1;
               $selectlimit  = 20;
               $where        = "WHERE ban_type = '2' ";
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
  ?>
  </td>
</tr>