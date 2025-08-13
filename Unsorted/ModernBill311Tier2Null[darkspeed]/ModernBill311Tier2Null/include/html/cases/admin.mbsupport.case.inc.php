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

$file = "";
$fp   = @fopen($file,"r");
while($line = fgets($fp,1024))
{
      $line = ereg_replace("#.*$","",$line);
      list($name,$value,$date) = explode("|",$line);
      $mb_info[trim($name)] = array(trim($value),trim($date));
}
fclose($fp);

$upgrade = ($version < $mb_info[current_version][0]) ? "<font color=red>".UPGRADETEXT."</font>" : "<font color=green>".UPGRADENOTNEEDED."</font>" ;

$file = "";
$fp   = @fopen($file,"r");
while($line = fgets($fp,1024))
{
      $line = ereg_replace("#.*$","",$line);
      list($date,$news) = explode("|",$line);
      if ($date!=""&&$news!="")
          $mb_news[] = array(trim($date),trim($news));
}
fclose($fp);

?>
<tr>
  <td>
    <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
     <tr>
       <td valign=top>
        <?=LFH?><b><?=MBSUPPORT?>:</b><?=EF?>
        <br>
        <ul>
           <li> <b>N/A</b></li>
	   <li> <b>N/A</b></li>
	   <li> <b>N/A</b></li>
           <li> <b>N/A</b></li>
        </ul>
       </td>
       <td valign=top>
        <?=LFH?><b><?=MBINFO?>:</b><?=EF?>
        <br>
        <ul>
            <li> <?=MBVERSION?>: <b><i><?=$mb_info[current_version][0]?></i></b> (<?=$mb_info[current_version][1]?>)</li>
            <li> <?=YOURVERSION?>: <b><i><?=$version?></i></b></li>
            <li> <b><?=$upgrade?></b></li>
        </ul>
       </td>
     </tr>
     <tr>
       <td valign=top colspan=2>
        <?=LFH?><b><?=MBNEWS?>:</b><?=EF?>
        <br>
        <ul>
         <?
         foreach($mb_news as $value)
                 echo "<li> <b>".$value[0].":</b> ".$value[1]."</li><br>";
         ?>
        </ul>
       </td>
     </tr>
    </table>
  </td>
</tr>

