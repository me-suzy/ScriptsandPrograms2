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

/*
** NCA Mod Addon from FastBurst Communciations
** http://www.fastburst.com
** Questions? support@fastburst.com
*/

?>
<tr>
  <td>
   <?=LFH?><b><?=NEWSANNOUNCE?>:</b><?=EF?>
  </td>
</tr>
<tr><td><hr size=1></td></tr>
<tr>
  <td>
   <ul>
   <?
   if(!$dbh)dbconnect();
   $newsresult = mysql_query("SELECT * FROM client_news ORDER BY ID DESC");
   while($newsrow = mysql_fetch_array($newsresult))
   {
      ?>
       <li type=square><b><?=$newsrow[Date]?> - <?=$newsrow[Time]?></b> <i><?=POSTED?>: [<a href="mailto:<?=$newsrow[Post_email]?>"><?=$newsrow[Post_user]?></a>]</i>
       <br>
       <br>
       <?=MFB.SUBJECT?>: <b><?=$newsrow[Subject]?></b><?=EF?>
       <br>
       <?=SFB.$newsrow[Text].EF?>
       <br>
       <br>
       <? for($i=1;$i<=30;$i++){ echo "- "; }?>
       <br>
       <br>
      <?
   }
   ?>
   </ul>
  </td>
</tr>
<tr><td><hr size=1></td></tr>