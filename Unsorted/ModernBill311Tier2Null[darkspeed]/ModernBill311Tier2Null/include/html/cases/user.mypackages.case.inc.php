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
$num_packages=$active_packages=$inactive_packages=0;
list($active_packages)=mysql_fetch_row(mysql_query("SELECT count(cp_id) FROM client_package WHERE client_id=$this_user[0] AND cp_status=2"));
list($inactive_packages)=mysql_fetch_row(mysql_query("SELECT count(cp_id) FROM client_package WHERE client_id=$this_user[0] AND cp_status=1"));
$num_packages=$active_packages+$inactive_packages;
?>
        <tr>
          <td>
            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr><td><?=LFH?><b><?=PACKAGESTATS?>:</b><?=EF?></td><td><?=LFH?><b><?=PACKAGEMENU?>:</b><?=EF?></td></tr>
             <tr>
               <td width=50% valign=top>
                 <table>
                  <tr><td>
                       <?=MFB?>
                       <?=MYPACKAGES?>:<br>
                       <?=TOTALACTIVE?>:<br>
                       <?=TOTALINACTIVE?>:<br>
                       <?=EF?>
                      </td>
                      <td align=right>
                       <?=MFB?>
                       [<a href=<?=$page?>?op=view&tile=<?=$tile?>><?=$num_packages?></a>]<br>
                       [<a href=<?=$page?>?op=view&tile=<?=$tile?>&where=<?=urlencode("WHERE cp_status=2")?>><?=$active_packages?></a>]<br>
                       [<a href=<?=$page?>?op=view&tile=<?=$tile?>&where=<?=urlencode("WHERE cp_status=1")?>><?=$inactive_packages?></a>]<br>
                       <?=EF?>
                      </td>
                   </tr>
                  </table>
               </td>
               <td width=50% valign=top>
                   <?=MFB?>
                   &nbsp;&#149;&nbsp;<a href=<?=$page?>?op=view&tile=<?=$tile?>><?=VIEWMYPACKAGES?></a><br>
                   <?=EF?>
               </td>
             </tr>
            </table>
          </td>
        </tr>