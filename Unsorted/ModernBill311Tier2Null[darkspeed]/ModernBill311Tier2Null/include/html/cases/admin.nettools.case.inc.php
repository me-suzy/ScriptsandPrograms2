<?PHP
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

?>
<tr>
  <td>
   <?=LFH?><b><?=NETWORKINGTOOLS?>:</b><?=EF?>
   &nbsp;
   <?=SFB?><b>[<a href="#" target="whoiswin" onClick='window.open("include/html/nettools.popup.php","NetTools","width=650,height=550,menubar=no,scrollbars=yes,toolbar=yes,location=no,directories=no,resizable=yes,status=yes,top=100,left=100"); return false;'><?=LAUNCHTOOLS?></a>]</b><?=EF?>
   <br><br>
   <?=MFB.NETTOOLSTEXT.EF?>
   <br><br>
  </td>
</tr>