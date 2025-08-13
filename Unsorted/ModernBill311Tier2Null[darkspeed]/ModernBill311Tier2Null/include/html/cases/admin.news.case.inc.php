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

## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

if(!$dbh)dbconnect();

switch ($do) {
   case addnews:
         $iddata = mysql_query("SELECT * FROM client_news ORDER BY ID DESC");
         $a = 0;
         do {
             $b = mysql_fetch_array($iddata);
             $id_new = $b['ID'];
             if($id_new=="") {
                $id_new = 0;
             }
             $a++;
         } while($a==0);

         $id_new++;
         $email = $row['Email'];
         if($sourcename=="") {
            $sourceurl = "";
         }
         $date = date("F d - Y");
         $time = date("g:i A");
         $headlinedate = date("d/m");
         $datetime = date("F Y");
         $text = addslashes($text);
         $subject = addslashes($subject);
         $sourcename = addslashes($sourcename);
         mysql_query("INSERT INTO client_news (ID, Subject, Post_user, Post_email, Date, Time, Headline_date, Date_time, Text) VALUES ('$id_new', '$subject', '$postname', '$postemail', '$date', '$time', '$headlinedate', '$datetime', '$text')");
   break;

   case editnews:
         $email = $row['Email'];
         if($sourcename=="") {
         $sourceurl = "";
         }
         $date = date("F d - Y, g:i a");
         $text = addslashes($text);
         mysql_query("UPDATE client_news SET Subject='$subject',
                                             Post_User='$postname',
                                             Post_Email='$postemail',
                                             Text='$text',
                                             Modify_date='$date',
                                             Modify_user='$postname' WHERE ID='$newsid'");
     break;

   case deletenews:
        mysql_query("DELETE FROM client_news WHERE ID='$newsid'");
   break;

   case clearnews:
        mysql_query("DELETE FROM client_news");
   break;
}

?>
<tr>
  <td>
   <?=LFH?><b><?=NEWSADMIN?>:</b><?=EF?>&nbsp;<br>
   <center>
   <b>
   <?=SFB?>
   [<a href="<?=$page?>?op=menu&tile=news&type=add&<?=session_id()?>"><?=ADDNEWS?></a>]
   [<a href="<?=$page?>?op=menu&tile=news&type=edit&<?=session_id()?>"><?=EDITNEWS?></a>]
   [<a href="<?=$page?>?op=menu&tile=news&type=deleteall&<?=session_id()?>"><?=DELETENEWS?></a>]
   [<a href="<?=$page?>?op=menu&tile=news&<?=session_id()?>"><?=MAINNEWS?></a>]
   <?=EF?>
   </b>
   </center>
  </td>
</tr>
<tr><td><hr size=1></td></tr>
<tr>
  <td>
<?
switch ($type) {
   case add:
        ?>
        <form method="POST" action="<?=$page?>?op=menu&tile=news&do=addnews&<?=session_id()?>">
        <?=start_box(ADDNEWS)?>
        <br>
        <table border="0" cellpadding="2" cellspacing="2" align=center>
        <tr>
          <td width="30%" align="right"><b><?=SUBJECT?></b>:</td>
          <td width="70%" align="left"><input type="text" name="subject" size="40" maxlength="255"></td>
        </tr>
        <tr>
          <td align="right"><b><?=BY?></b>:</td>
          <td align="left"><input type="text" name="postname" value="<?=$this_admin[admin_realname]?>" size="25" maxlength="255"></td>
        </tr>
        <tr>
          <td align="right"><b><?=EMAIL?></b>:</td>
          <td align="left"><input type="text" name="postemail" value="<?=$this_admin[admin_email]?>"  size="25" maxlength="255"></td>
        </tr>
        <tr>
          <td colspan="2"><b><?=MESSAGE?></b>:</td>
        </tr>
        <tr>
          <td colspan="2"><textarea name="text" rows="12" cols="70" maxlength="10000"></textarea></td>
        </tr>
        <tr>
          <td colspan="2" align="center"><br><?=SUBMIT_IMG?></td>
        </tr>
        </table>
        <?=stop_box()?>
        </form>
        <?
   break;

   case edit:
        if (!$selected) {
         $howMany = ($howMany) ? $howMany : "30";
         $offset  = ($offset) ? $offset : $howMany;
         $onset   = $offset - $howMany;
         $monthToday = date("F Y");
         if(!$monthSelected) { $monthSelected = $monthToday; }

         $editResult = mysql_query("SELECT * FROM client_news WHERE Date_time Like '$monthSelected' ORDER BY ID DESC");

         while($list = mysql_fetch_array($editResult))
         {
             if($month != $list[Date_time]) {
                ?>
                <table border="0" cellspacing="0" width="90%">
                 <tr>
                  <td><?=LFH?><b><?=$list[Date_time]?></b><?=EF?></td>
                 </tr>
                 <?
                 $date = $list[Date_time];
                 $editData = mysql_query("SELECT * FROM client_news WHERE Date_time='$date' ORDER BY ID DESC LIMIT $onset,$offset");
                 while($edit = mysql_fetch_array($editData))
                 {
                     ?>
                     <tr>
                      <td>
                       <?=$edit[Time]?> <?=$edit[Headline_date]?> - <a href="<?=$page?>?op=menu&tile=news&type=edit&selected=true&id=<?=$edit[ID]?>"><b><?=MFB.$edit[Subject].EF?></b></a>&nbsp;
                       <a href="<?=$page?>?op=menu&tile=news&type=edit&selected=true&id=<?=$edit[ID]?>"><?=EDIT_IMG?></a>&nbsp;
                       <a href="<?=$page?>?op=menu&tile=news&type=delete&selected=true&id=<?=$edit[ID]?>"><?=DELETE_IMG?></a>
                       <br>
                       <i><?=SFB?><?=USERNAME?>: <?=$edit[Post_user]?>, <?=LASTMODIFIED?>: <?=$edit[Modify_date]?> <?=BY?> <?=$edit[Modify_user]?><?=EF?></i>
                       <br>
                       <? for($i=1;$i<=30;$i++){ echo "- "; }?>
                      </td>
                     </tr>
                     <?
                }
                echo "</table>";
                $month = $list[Date_time];
             }
         }

         ?>
         <table border="0" cellpadding="2" cellspacing="2" align=center>
          <tr>
           <td width="100%">
            <form method="POST" action="<?=$page?>?op=menu&tile=news&type=edit&<?=session_id()?>">
            <b><?=HOWMANYNEWS?>: <input type="text" name="howMany" value="<?=$howMany?>" size="4" maxlength="255">
            <?=MONTH?>: <select name="monthSelected">
            <?
            $monthsResult = mysql_query("SELECT * FROM client_news ORDER BY ID DESC");
            while($rowMonth = mysql_fetch_array($monthsResult))
            {
                if($monthExist != $rowMonth[Date_time]) {
                   ?>
                   <option value="<?=$rowMonth[Date_time]?>"><?=$rowMonth[Date_time]?></option>
                   <?
                   $monthExist = $rowMonth[Date_time];
                }
            }
            ?>
            </select>
            <?=SUBMIT_IMG?>
            </b>
            </form>
           </td>
          </tr>
         </table>
         <?
         }

         if ($selected) {
         $editResult = mysql_query("SELECT * FROM client_news WHERE ID = '$id'");
         $editData   = mysql_fetch_array($editResult);
         $subject    = stripslashes($editData['Subject']);
         $text       = stripslashes($editData['Text']);

         ?>
         <form method="POST" action="<?=$page?>?op=menu&tile=news&do=editnews&newsid=<?=$id?>&<?=session_id()?>">
         <?=start_box(EDITNEWS)?>
         <br>
         <table border="0" cellpadding="2" cellspacing="2" align="center">
         <tr>
          <td width="100%" colspan="2">
           <img src="images/news_big.gif"> - <?=MFB?><b><?=stripslashes($editData['Subject'])?></b><?=EF?>
           <br>
           <?=SFB?><?=BY?>: <?=stripslashes($editData[Post_user])?> <?=stripslashes($editData['Date'])?><?=EF?>
          </td>
         </tr>
         <tr>
          <td width="30%" align="right"><b><?=SUBJECT?></b>:</td>
          <td width="70%" align="left"><input type="text" name="subject" size="40" value="<?=$subject?>"></td>
         </tr>
         <tr>
          <td align="right"><b><?=BY?></b>:</td>
          <td align="left"><input type="text" name="postname" size="25"  value="<?=$editData[Post_user]?>"></td>
         </tr>
         <tr>
          <td align="right"><b><?=EMAIL?></b>:</td>
          <td align="left"><input type="text" name="postemail" size="25" value="<?=$editData[Post_email]?>"></td>
         </tr>
         <tr>
          <td colspan="2"><b><?=MESSAGE?></b>:</td>
         </tr>
         <tr>
          <td colspan="2"><textarea rows="12" name="text" cols="60"><?=$text?></textarea></td>
         </tr>
         <tr>
          <td colspan="2" align="center"><br><?=SUBMIT_IMG?></td>
         </tr>
         </table>
         <?=stop_box()?>
         </form>
         <?
         }
   break;

   case delete:
        if ($selected) {
            ?>
            <form method="POST" action="<?=$page?>?op=menu&tile=news&do=deletenews&newsid=<?=$id?>&<?=session_id()?>">
            <b><?=AREYOUSUREDELETE?></b><input type="submit" value="<?=YES?>" name="yes">
            </form>
            <?
        }
   break;

   case deleteall:
        ?>
        <form method="POST" action="<?=$page?>?op=menu&tile=news&do=clearnews&<?=session_id()?>">
        <b><?=AREYOUSURECLEAR?></b> <input type="submit" name="yes" value="<?=YES?>">
        </form>
        <?
   break;

   default:
         $num_new = mysql_one_data("SELECT count(ID) FROM client_news WHERE mainid=0");
         ?>
         <table width="100%" border="0" cellspacing="0" cellpadding="10">
         <tr>
          <td align="center" valign="absmiddle">
           <img src="images/news_big.gif" width="32" height="32">
           <li><?=TOTALNEWS?> <b>[<?=$num_new?>]</b></li>
          </td>
         </tr>
         </table>
         <?
   break;


}
?>
  </td>
</tr>
<tr><td><hr size=1></td></tr>
<tr>
 <td align="center">News Client Addon (NCA) Produced By: <a href="http://www.fastburst.com" target="_blank">FastBurst Communications</a><br><b></td>
</tr>