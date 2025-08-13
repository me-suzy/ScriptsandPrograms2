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
$db_table  = "client_register";

$sum_debits = mysql_one_data("SELECT sum(reg_bill) FROM $db_table");
$sum_credit = mysql_one_data("SELECT sum(reg_payment) FROM $db_table");
$balance = $sum_credit - $sum_debits
?>
<tr>
  <td>
    <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
     <tr><td><?=LFH?><b><?=ACCOUNTREGISTER?>:</b><?=EF?></td>
         <td><?=LFH?><b><?=SEARCHTRANSACTIONS?>:</b><?=EF.SFB?> [<a href=<?=$page?>?op=view&db_table=<?=$db_table?>&tile=<?=$tile?>><b><?=VIEWALL?></b></a>]<?=EF?></td></tr>
     <tr>
       <td width=50% valign=top>
         <table>
          <tr><td>
               <?=MFB?>
               <?=DEBITS?>:<br>
               <?=CREDITS?>:<br>
               <?=TOTAL?>:<br>
               <?=EF?>
              </td>
              <td align=right>
               <?=MFB?>
               [<a href=<?=$page?>?op=view&db_table=<?=$db_table?>&tile=<?=$tile?>&where=<?=urlencode("WHERE reg_bill > 0")?>><?=display_currency($sum_debits)?></a>]<br>
               [<a href=<?=$page?>?op=view&db_table=<?=$db_table?>&tile=<?=$tile?>&where=<?=urlencode("WHERE reg_payment > 0")?>><?=display_currency($sum_credit)?></a>]<br>
               [<a href=<?=$page?>?op=view&db_table=<?=$db_table?>&tile=<?=$tile?>><?=display_currency($balance)?></a>]<br>
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
          <tr><td colspan=2><?=register_select_box();?></td></tr>
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
  <?=display_account_register($db_table,$where,$order,$sort,$offset,$limit)?>
  </td>
</tr>