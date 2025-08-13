<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
$tables = mysql_list_tables($db_name);
while (list($table_name) = mysql_fetch_array($tables)) {
$sql = "OPTIMIZE TABLE $table_name";
mysql_query($sql) or exit(mysql_error());
}
*/
## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }
$ap = ($this_admin["admin_level"]==9) ? $this_admin["ap"] : NULL ;
?>
<tr>
  <td>
    <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
     <tr>
       <td valign=top>
         <?=LFH?><b><?=CREATEYOUROWN?>:</b><?=EF?>
         <br>
         <ul>
            <table border=0 cellpadding=2 cellspacing=2>
            <form method=post action=<?=$page?>?op=exp_data>
            <tr>
              <td align=right width=35%>
               <?=SFB?><b><?=DBTABLE?>:</b><?=EF?>
              </td>
              <td>
               <select name=db_table>
               <?
               $tables = mysql_list_tables($locale_db_name);
               while (list($table_name) = mysql_fetch_array($tables))
                      echo "<option>$table_name</option>";
               ?>
               </select>
              </td>
            </tr>
            <tr>
              <td align=right width=35%>
               <?=SFB?><b><?=YOURPW?>:</b><?=EF?>
              </td>
              <td>
               <input type=password name=password value="<?=$ap?>" size=15 maxlength=15> <? if ($pw) { echo SFB."<font color=red>".INVALIDPASSWORD."</font>".EF; } ?>
              </td>
            </tr>
            <tr>
              <td align=right width=35%>
               <?=SFB?><b><?=ENCRYPTIONKEY?>:</b><?=EF?>
              </td>
              <td>
               <textarea name=decrypt_key rows=4 cols=30 maxlength=5000></textarea>
               <br>
               <?=SFB?>(<?=ENCRYPTIONKEYREASON?>)<?=EF?>
              </td>
            </tr>
            <tr>
              <td align=right width=35%>
               <?=SFB?><b><?=FORMAT?>:</b><?=EF?>
              </td>
              <td>
               <input type=radio name=output value=excel CHECKED>&nbsp;<img src=images/icons/excel.gif border=0 valign=middle>&nbsp;<?=EXCEL?><br>
               <input type=radio name=output value=word>&nbsp;<img src=images/icons/word.gif border=0 valign=middle>&nbsp;<?=WORD?><br>
               <input type=radio name=output value=csv>&nbsp;<img src=images/icons/notepad.gif border=0 valign=middle>&nbsp;<?=CSV?>: <?=DELIM?> <input type=text name=delim value="," size=2 maxlength=1><br>
              </td>
            </tr>
            <tr>
             <td colspan=2 align=center>
              <?=SUBMIT_IMG?>
             </td>
            </tr>
            <input type=hidden name=tile value=<?=$tile?>>
            </form>
            </table>
         </ul>
       </td>
     </tr>
     </table>
  </td>
</tr>