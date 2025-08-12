<?PHP
if ($cval == "8"){
                $result = mysql_query ("SELECT * FROM Messages
                         WHERE id LIKE '$id'
                                                 limit 1
                       ");
                $row = mysql_fetch_array($result);
                if($cursent != $row["sent"]){
                print "Unable to requeue.  Mailing appears to be sending currently.";
                }
                else {
mysql_query("UPDATE Messages SET status = 0 WHERE id = '$id'");

?>
<META HTTP-EQUIV="Refresh" CONTENT="0; URL=main.php?page=list_send3&id=<?PHP print $id; ?>&sendval=resend&nl=<?PHP print $nl; ?>&cursent=<?PHP print $cursent; ?>&cval=8">
<?PHP
}
}
else{
?>
<SCRIPT LANGUAGE="JavaScript">
var g_iCount = new Number();
var g_iCount = 50;

function startCountdown(){
       if((g_iCount - 1) >= 0){
               g_iCount = g_iCount - 1;
               numberCountdown.innerText = '' + g_iCount;
               setTimeout('startCountdown()',1000);
       }
}
</script>
<script language="JavaScript">
<!--
function GP_popupConfirmMsg(msg) {
  document.MM_returnValue = confirm(msg);
}
//-->
</script>
<BODY onLoad="startCountdown()">
<META HTTP-EQUIV="Refresh" CONTENT="50; URL=main.php?page=list_send3_pre&id=<?PHP print $id; ?>&sendval=resend&nl=<?PHP print $nl; ?>&cursent=<?PHP print $cursent; ?>&cval=8">
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_221; ?></strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_501; ?></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/queue_bar.gif" width="346" height="35"></font></p>
<table width="200" border="0" cellpadding="1" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="6">
        <tr bgcolor="#FFFFFF">
          <td><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_500; ?>:</font></td>
          <td width="55"> <div align="center"> <font size="2" face="Arial, Helvetica, sans-serif">
              <div id="numberCountdown"> </div>
              </font></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<p>&nbsp;</p>
<p><font color="#666666" size="2" face="Arial, Helvetica, sans-serif">+ <a href="main.php?page=list_send3_pre&id=<?PHP print $id; ?>&sendval=resend&nl=<?PHP print $nl; ?>&cursent=<?PHP print $cursent; ?>&cval=8"  onClick="GP_popupConfirmMsg('THIS CAN NOT BE UNDONE!\r\rAre you sure that you would like to force requeue this mailing?\r\rThis may cause duplicates to be sent if the mailing is still in progress.');return document.MM_returnValue"><?PHP print $lang_502; ?> </a></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"></font>
  <?PHP
}
?>
</p>