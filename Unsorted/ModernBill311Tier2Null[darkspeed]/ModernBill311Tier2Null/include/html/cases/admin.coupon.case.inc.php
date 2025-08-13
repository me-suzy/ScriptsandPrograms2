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
$db_table = "coupon_codes";
$num_coupons=0;
list($num_coupons)=mysql_fetch_row(mysql_query("SELECT count(coupon_id) FROM $db_table"));
list($num_active)=mysql_fetch_row(mysql_query("SELECT count(coupon_id) FROM $db_table WHERE coupon_status = 2"));
list($num_inactive)=mysql_fetch_row(mysql_query("SELECT count(coupon_id) FROM $db_table WHERE coupon_status = 1"));
$num_coupons=$num_coupons;
?>
<tr>
  <td>
    <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
     <tr><td><?=LFH?><b><?=COUPONSTATS?>:</b><?=EF.SFB?> [<a href=<?=$page?>?op=menu&tile=<?=$tile?>&type=graph><?=VIEWGRAPH?></a>]<?=EF?></td><td><?=LFH?><b><?=COUPONSEARCH?>:</b><?=EF.SFB?> [<a href=<?=$page?>?op=view&db_table=<?=$db_table?>&tile=<?=$tile?>><?=VIEWALL?></a>]<?=EF?></td></tr>
     <tr>
       <td width=50% valign=top>
         <table>
          <tr><td>
               <?=MFB?>
                 <?=TOTAL?>:<br>
                 <?=ACTIVE?>:<br>
                 <?=INACTIVE?>:<br>
               <?=EF?>
              </td>
              <td align=right>
               <?=MFB?>
                 [<a href=<?=$page?>?op=view&db_table=<?=$db_table?>&tile=<?=$tile?>><?=$num_coupons?></a>]<br>
                 [<a href=<?=$page?>?op=view&db_table=<?=$db_table?>&tile=<?=$tile?>&where=<?=urlencode("WHERE coupon_status = 2")?>><?=$num_active?></a>]<br>
                 [<a href=<?=$page?>?op=view&db_table=<?=$db_table?>&tile=<?=$tile?>&where=<?=urlencode("WHERE coupon_status = 1")?>><?=$num_inactive?></a>]<br>
               <?=EF?>
              </td>
              <td align=right>
               <?=MFB?>
               [<a href=<?=$page?>?op=form&db_table=<?=$db_table?>&tile=<?=$tile?>><b><?=ADD?></b></a>]<br>
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
          <input type=hidden name=db_table value=<?=$db_table?>>
          <tr><td colspan=2><?=coupon_search_select_box();?></td></tr>
          <tr><td><input type=text name=query size=15 maxlength=25></td><td><?=SEARCH_IMG?></td></tr>
          </form>
          </table>
       </td>
     </tr>
    </table>
  </td>
</tr>
<tr>
  <td>
  <?
  switch ($type) {
          case graph:
               $result = mysql_query("SELECT coupon_code,coupon_count FROM $db_table");
               while(list($coupon_code,$coupon_count) = mysql_fetch_array($result))
               {
                   //Monday:10|Tuesday:10|Wednesday:10|Thursday:10|Friday:10
                   $tempdata .= "$coupon_code:$coupon_count|";
               }
               print_graph(substr($tempdata,0,-1),COUPONSTATS,300,15);
          break;

          default:
               $recursive    = 0;
               $details_view = 1;
               $selectlimit  = 1000;
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