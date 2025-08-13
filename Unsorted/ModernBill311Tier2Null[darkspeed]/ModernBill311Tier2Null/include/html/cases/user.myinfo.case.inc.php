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

include_once("include/functions.inc.php");
if(!$dbh)dbconnect();

$num_clients=$num_active_clients=$num_inactive_clients=0;
$my_info=mysql_fetch_array(mysql_query("SELECT * FROM client_info WHERE client_id=$this_user[0]"));
$active = ($my_info["client_status"]==2) ? ACTIVE : INACTIVE ;
?>
        <tr>
          <td>
            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr><td><?=LFH?><b><?=MYSTATS?>:</b><?=EF?></td><td><?=LFH?><b><?=MYMENU?>:</b><?=EF?></td></tr>
             <tr>
               <td width=50% valign=top>
                 <table>
                  <tr><td>
                       <?=MFB?>
                       <?=MYSTATUS?>:<br>
                       <?=MEMBERSINCE?>:<br>
                       <?=EF?>
                      </td>
                      <td>
                       <?=MFB?>
                       <b>
                       <?=$active?><br>
                       <?=stamp_to_date($my_info["client_stamp"])?><br>
                       </b>
                       <?=EF?>
                      </td>
                   </tr>
                  </table>
               </td>
               <td width=50% valign=top>
                 <?=MFB?>
                   &nbsp;&#149;&nbsp;<a href=<?=$page?>?op=details&tile=<?=$tile?>><?=VIEWMYINFO?></a><br>
                   &nbsp;&#149;&nbsp;<a href=<?=$page?>?op=form&tile=<?=$tile?>><?=UPDATEMYINFO?></a><br>
                   &nbsp;&#149;&nbsp;<a href=<?=$page?>?op=change_pw&tile=<?=$tile?>><?=CHANGEMYPASSWORD?></a><br>
                 <? if ($my_info['billing_method']==1) { ?>
                   &nbsp;&#149;&nbsp;<a href=<?=$page?>?op=update_cc&tile=<?=$tile?>><?=UPDATEMYCC?></a><br>
                 <? } ?>
                 <?=EF?>
               </td>
             </tr>
            </table>
          </td>
        </tr>