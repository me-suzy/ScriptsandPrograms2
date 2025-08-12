<script language="JavaScript">
<!--
function GP_popupConfirmMsg(msg) {
  document.MM_returnValue = confirm(msg);
}
//-->
</script>
<META HTTP-EQUIV="Refresh" CONTENT="25; URL=main.php?page=list_queue&nl=<?PHP print $nl; ?>">
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_221; ?></strong></font></p>
<p><font face="Arial, Helvetica, sans-serif" size="2"><a href="javascript:window.location.reload()"><b> 
  <?PHP print $lang_230; ?></b></a> <?PHP print $lang_231; ?></font></p>
<table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#BFD2E8">
  <tr> 
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF" align="center">
<?PHP 

if ($val == "pause"){
	mysql_query("UPDATE Messages SET status = 3 WHERE id = '$id'");
}
if ($val == "stop"){
	mysql_query("UPDATE Messages SET completed = 1 WHERE id = '$id'");
}

$result = mysql_query ("SELECT * FROM Messages
						WHERE nl LIKE '$nl'
						AND completed LIKE '0'
						AND status != '4'
                       	ORDER BY mdate DESC, mtime DESC, subject
");
if ($c1 = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
?>
        <tr bgcolor="#FFFFFF"> 
          <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"> <table width="100%" border="0" cellpadding="8" cellspacing="0" background="media/queue_back.gif">
              <tr bgcolor="#BFD2E8"> 
                <td colspan="2"><font size="4" face="Arial, Helvetica, sans-serif"><b> 
                  <?PHP 
					print $row["subject"]; 
					 $nsent = $row["sent"]; 
					 $ntotal = $row["amt"]; 
					 @$nvs = round(($nsent / $ntotal),4);
					 @$nvs = round(($nvs * 100),4);
					?>
                  </b></font></td>
              </tr>
              <tr valign="top" background="media/invis.gif"> 
                <td height="50" background="media/invis.gif"><p><font size="4" face="Arial, Helvetica, sans-serif"><b><font size="1"> 
                    </font></b></font> </p>
                  <table width="100%" border="0" cellspacing="0" cellpadding="1">
                    <tr> 
                      <td height="35" valign="top"> <table width="260" border="0" cellpadding="2" cellspacing="0" background="media/queue_percent.gif">
                          <tr> 
                            <td> <div align="right"></div>
                              <font size="4" face="Arial, Helvetica, sans-serif"><b><?PHP if($nvs >= 5){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 10){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 15){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 20){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 25){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 30){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 35){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 40){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 45){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 50){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 55){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 60){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 65){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 70){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 75){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 80){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 85){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 90){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 95){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="13" height="16"><?PHP } ?><?PHP if($nvs >= 100){ ?><img src="media/invis.gif" width="13" height="16"><?PHP } else { ?><img src="media/invis_w.gif" width="10.9" height="16"><?PHP } ?>
                              </b></font></td>
                          </tr>
                        </table>
                        <table width="261" border="0" cellpadding="1" cellspacing="0" bgcolor="#264972">
                          <tr> 
                            <td><table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#FFFFFF">
                                <tr> 
                                  <td><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><?PHP print $nvs; ?>% 
                                    <?PHP print $lang_224; ?><b></b></font></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr> 
                      <td height="35"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $row["sent"]; ?> 
                        <?PHP print $lang_225; ?> <?PHP print $row["amt"]; ?> <?PHP print $lang_226; ?></font></td>
                    </tr>
                  </table>
                  
                </td>
                <td width="260" height="50"><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#BFD2E8">
                    <tr> 
                      <td><table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
                          <tr> 
                            <td bgcolor="#FFFFFF"><div align="center"> 
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr> 
                                    <td width="12%"> <font size="2" face="Arial, Helvetica, sans-serif"> 
                                      <?PHP if ($row["status"] == "0"){ ?>
                                      <a href="main.php?page=list_queue&id=<?PHP print $row["id"]; ?>&val=pause&nl=<?PHP print $nl; ?>"><img src="media/queue_pause.gif" width="23" height="23" border="0"></a> 
                                      <?PHP } if ($row["status"] == "3"){ ?>
                                      <a href="main.php?page=list_send3_pre&id=<?PHP print $row["id"]; ?>&sendval=resend&nl=<?PHP print $nl; ?>&cursent=<?PHP print $row["sent"]; ?>"><img src="media/queue_resume.gif" width="23" height="23" border="0"></a> 
                                      <?PHP } ?>
                                      </font></td>
                                    <td width="21%"> <font size="2" face="Arial, Helvetica, sans-serif"> 
                                      <?PHP if ($row["status"] == "0"){ ?>
                                      <a href="main.php?page=list_queue&id=<?PHP print $row["id"]; ?>&val=pause&nl=<?PHP print $nl; ?>"><?PHP print $lang_573; ?></a> 
                                      <?PHP } if ($row["status"] == "3"){ ?>
                                      <a href="main.php?page=list_send3_pre&id=<?PHP print $row["id"]; ?>&sendval=resend&nl=<?PHP print $nl; ?>&cursent=<?PHP print $row["sent"]; ?>"><?PHP print $lang_574; ?></a> 
                                      <?PHP } ?>
                                      </font></td>
                                    <td width="12%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_queue&id=<?PHP print $row["id"]; ?>&val=stop&nl=<?PHP print $nl; ?>"  onClick="GP_popupConfirmMsg('THIS CAN NOT BE UNDONE!\r\rAre you sure that you would like to force stop this mailing?\r\rThis will stop sending and will not let you finish sending this mailing.');return document.MM_returnValue"><img src="media/queue_stop.gif" width="23" height="23" border="0"></a></font></div></td>
                                    <td width="15%"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_queue&id=<?PHP print $row["id"]; ?>&val=stop&nl=<?PHP print $nl; ?>"  onClick="GP_popupConfirmMsg('THIS CAN NOT BE UNDONE!\r\rAre you sure that you would like to force stop this mailing?\r\rThis will stop sending and will not let you finish sending this mailing.');return document.MM_returnValue"><?PHP print $lang_575; ?></a></font></td>
                                    <td width="16%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;<font color="#999999"> 
                                        <?PHP
							$check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
			$chk = mysql_fetch_array($check);
			if ($chk["a_rq"] == "0") {
			?>
                                        </font><a href="main.php?page=list_send3_pre&id=<?PHP print $row["id"]; ?>&sendval=resend&nl=<?PHP print $nl; ?>&cursent=<?PHP print $row["sent"]; ?>"><img src="media/queue_requeue.gif" width="23" height="23" border="0"></a><font color="#999999"> 
                                        <?PHP } ?>
                                        </font></font></div></td>
                                    <td width="23%"><font color="#999999" size="2" face="Arial, Helvetica, sans-serif"> 
                                      <?PHP
							$check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
			$chk = mysql_fetch_array($check);
			if ($chk["a_rq"] == "0") {
			?>
                                      </font><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_send3_pre&id=<?PHP print $row["id"]; ?>&sendval=resend&nl=<?PHP print $nl; ?>&cursent=<?PHP print $row["sent"]; ?>"><?PHP print $lang_576; ?></a><font color="#999999"> 
                                      <?PHP } ?>
                                      </font></font></td>
                                  </tr>
                                </table>
                                <font color="#999999" size="2" face="Arial, Helvetica, sans-serif"> 
                                </font></div></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
                  <br>
                  <table width="100%" border="0" cellspacing="0" cellpadding="1">
                    <tr valign="top"> 
                      <td height="35"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_222; ?>:</font></td>
                      <td width="120" height="35"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $row["mdate"]; ?></font></td>
                    </tr>
                    <tr valign="top"> 
                      <td><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_223; ?></font></td>
                      <td width="120"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $row["mtime"]; ?></font></td>
                    </tr>
                  </table>
                  
                </td>
              </tr>
            </table></td>
        </tr>
        <?PHP
}

} else {
?>
        <font color="#336699" size="2" face="Arial, Helvetica, sans-serif"> 
        <?PHP
print "$lang_229";
		  ?>
        </font> 
        <?PHP
		  } ?>
      </table></td>
  </tr>
</table>
<p><font color="#999999" size="2" face="Arial, Helvetica, sans-serif">
  <?PHP
							$check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
			$chk = mysql_fetch_array($check);
			if ($chk["a_rq"] == "0") {
			?>
  <font size="1"><?PHP print $lang_227; ?> <?PHP print $lang_228; ?></font> 
  <?PHP } ?>
  </font></p>
