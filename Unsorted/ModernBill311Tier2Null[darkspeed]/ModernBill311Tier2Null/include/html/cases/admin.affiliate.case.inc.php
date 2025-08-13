<?php
// +----------------------------------------------------------------------+
// | ModernBill [TM] .:. Client Billing System                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001-2002 ModernGigabyte, LLC                          |
// +----------------------------------------------------------------------+
// | This source file is subject to the ModernBill End User License       |
// | Agreement (EULA), that is bundled with this package in the file      |
// | LICENSE, and is available at through the world-wide-web at           |
// | http://www.modernbill.com/extranet/LICENSE.txt                       |
// | If you did not receive a copy of the ModernBill license and are      |
// | unable to obtain it through the world-wide-web, please send a note   |
// | to license@modernbill.com so we can email you a copy immediately.    |
// +----------------------------------------------------------------------+
// | Authors: ModernGigabyte, LLC <info@moderngigabyte.com>               |
// | Support: http://www.modernsupport.com/modernbill/                    |
// +----------------------------------------------------------------------+
// | ModernGigabyte and ModernBill are trademarks of ModernGigabyte, LLC. |
// +----------------------------------------------------------------------+

## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

if(!$dbh)dbconnect();
$db_table  = "affiliate_config";
?>
<tr>
  <td>
  <?=LFH?><b><?=AFFILIATES?>:</b><?=EF?>  [<a href=<?=$page?>?op=form&db_table=<?=$db_table?>&tile=<?=$tile?>><b><?=ADD?></b></a>]&nbsp;[<a href=<?=$page?>?op=menu&tile=<?=$tile?>&type=stats><b><?=STATS?></b></a>]<br>
  <?
  switch ($type) {
          case stats:
                  echo "<br>";
                  start_box(AFFHITS);
                  $tempdata = NULL;
                  $sql      = "SELECT * FROM affiliate_config ORDER BY aff_hits DESC";
                  $result   = mysql_query($sql,$dbh);
                  while($this_aff = mysql_fetch_assoc($result))
                  {
                      $tempdata .= "<a href=\"$page?op=details&db_table=affiliate_config&tile=affiliate&id=aff_id|".$this_aff[aff_id]."\">".$this_aff[aff_code]."</a>:".$this_aff[aff_hits]."~";
                  }
                  print_graph(substr($tempdata,0,-1),NULL,400,10,"left","#EEEEEE","~");
                  stop_box();

                  echo "<br>";
                  start_box(AFFCOUNT);
                  $tempdata = NULL;
                  $sql      = "SELECT * FROM affiliate_config ORDER BY aff_count DESC";
                  $result   = mysql_query($sql,$dbh);
                  while($this_aff = mysql_fetch_assoc($result))
                  {
                      $tempdata .= "<a href=\"$page?op=details&db_table=affiliate_config&tile=affiliate&id=aff_id|".$this_aff[aff_id]."\">".$this_aff[aff_code]."</a>:".$this_aff[aff_count]."~";
                  }
                  print_graph(substr($tempdata,0,-1),NULL,400,10,"left","#EEEEEE","~");
                  stop_box();

                  echo "<br>";
                  start_box(AFFPAYSUM);
                  $tempdata = NULL;
                  $sql      = "SELECT * FROM affiliate_config ORDER BY aff_pay_sum DESC";
                  $result   = mysql_query($sql,$dbh);
                  while($this_aff = mysql_fetch_assoc($result))
                  {
                      $tempdata .= "<a href=\"$page?op=details&db_table=affiliate_config&tile=affiliate&id=aff_id|".$this_aff[aff_id]."\">".$this_aff[aff_code]."</a>:".$this_aff[aff_pay_sum]."~";
                  }
                  print_graph(substr($tempdata,0,-1),NULL,400,10,"left","#EEEEEE","~");
                  stop_box();
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