<script language="JavaScript" type="text/JavaScript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
function GP_popupConfirmMsg(msg) {
  document.MM_returnValue = confirm(msg);
}
</script>
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_566; ?>
  (<?PHP print $lang_570; ?>) </strong></font></p>

<?PHP
  if ($val == ""){
  ?>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td bgcolor="#FFFFFF" width="43"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;<?PHP print $lang_192; ?></font></div></td>
    <td bgcolor="#FFFFFF" width="50"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_193; ?></font></div></td>
    <td width="56" bgcolor="#D5E2F0"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><b>&nbsp;&nbsp;</b><strong><?PHP print $lang_567; ?></strong></font></div></td>
    <td width="56" bgcolor="#D5E2F0"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_30; ?>*</strong></font></div></td>
    <td bgcolor="#D5E2F0"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_31; ?></font></b></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td bgcolor="#D5E2F0"> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center">
        <?PHP
$result = mysql_query ("SELECT * FROM 12all_Respond
                                                WHERE nl LIKE '$nl'
                               ORDER BY time, subject
                                                ");
if ($c1 = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
?>
        <tr bgcolor="#FFFFFF">
          <td width="35" bordercolor="#CCCCCC" bgcolor="#FFFFFF"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_respond_manage&nl=<?PHP print $nl; ?>&val=2&id=<?PHP print $row["id"]; ?>"><img src="media/edit.gif" border="0"></a>
              </font></div></td>
          <td bordercolor="#CCCCCC" width="50"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_respond_manage&nl=<?PHP print $nl; ?>&val=6&id=<?PHP print $row["id"]; ?>" onClick="GP_popupConfirmMsg('THIS CAN NOT BE UNDONE!\r\rAre you sure that you would like to remove this auto responder?\r\rThis will remove the atuo reponder and cannot be undone.');return document.MM_returnValue"><img src="media/del.gif" width="11" height="7" border="0"></a>
              </font></div></td>
          <td width="50" bordercolor="#CCCCCC"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP

                        print $row["type"];
                        ?>
              </font></div></td>
          <td width="50" bordercolor="#CCCCCC"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP

                        print $row["time"];
                        ?>
              </font></div></td>
          <td bordercolor="#CCCCCC"><font size="2" face="Arial, Helvetica, sans-serif">
            <?PHP

                        print stripslashes($row["subject"]);
                        ?>
            </font></td>
        </tr>
        <?PHP
}

} else {print "$lang_32";} ?>
      </table></td>
  </tr>
</table>
<p><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><em>*<?PHP print $lang_571; ?></em></font></p>
<p>
  <?PHP
  }
  if ($val == "2"){
    $result = mysql_query ("SELECT * FROM 12all_Respond
                                                WHERE id LIKE '$id'
                                                LIMIT 1
                                                ");
        $row = mysql_fetch_array($result);
          $type = $row["type"];
  ?>
</p>
<form name="form1" method="post" action="main.php">
  <table width="100%" border="0" cellspacing="0" cellpadding="8">
    <tr>
      <td width="60" height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_31; ?></b></font><b><font color="#336699" size="4" face="Arial, Helvetica, sans-serif">
        </font></b><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><b>
        </b></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif"><a href="#"  onclick="MM_openBrWindow('http://www./support/docs/12all/view.php?id=3154','Help','scrollbars=yes,width=316,height=350')">[?]</a></font></td>
      <td height="30" colspan="2" bgcolor="#F3F3F3"> <font size="2" face="Arial, Helvetica, sans-serif">
        <input name="subject" type="text" value="<?PHP print stripslashes($row["subject"]); ?>" size="64">
        </font></td>
    </tr>
    <tr>
      <td width="60" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_35; ?>
        </b></font><font size="2" face="Arial, Helvetica, sans-serif"><a href="#"  onclick="MM_openBrWindow('http://www./support/docs/12all/view.php?id=3155','Help','scrollbars=yes,width=316,height=350')">[?]</a></font><font size="2" face="Arial, Helvetica, sans-serif"><b><br>
        </b></font></td>
      <td height="30" colspan="2"> <table width="425" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%"><font size="2" face="Arial, Helvetica, sans-serif">
              <input name="fromn" type="text" id="fromn" value="<?PHP print stripslashes($row["fromn"]); ?>" size="29">
              </font></td>
            <td width="50%"><font size="2" face="Arial, Helvetica, sans-serif">
              <input name="frome" type="text" id="frome" value="<?PHP print stripslashes($row["frome"]); ?>" size="29">
              </font></td>
          </tr>
          <tr>
            <td width="50%" height="19"><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><?PHP print "$lang_35 $lang_4 ($lang_204)"; ?></font></td>
            <td width="50%"><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><?PHP print "$lang_35 $lang_5"; ?></font></td>
          </tr>
        </table></td>
    </tr>
    <tr bgcolor="#F3F3F3">
      <td width="60" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_568; ?></strong></font></td>
      <td width="60" height="30"><font size="2" face="Arial, Helvetica, sans-serif">
        <input name="hours" type="text" id="hours" value="<?PHP print stripslashes($row["time"]); ?>" size="5">
        </font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_569; ?></font></td>
    </tr>
  </table>
  <p>
    <?PHP
  if ($type == "html"){
  ?>
  </p>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="250" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_36; ?></b></font></td>
      <td height="30"><p align="right"><font size="2" face="Arial, Helvetica, sans-serif">
          <?PHP
                        $check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
                                                 limit 1
                       ");
                        $chk = mysql_fetch_array($check);
                        if ($chk["a_pz"] == "0") {
                        ?>
          </font><font size="1" face="Arial, Helvetica, sans-serif"> <a href="#"  onClick="MM_openBrWindow('http://www./support/docs/12all/view.php?id=3156','Help','scrollbars=yes,width=316,height=350')">[
          ? ] <?PHP print $lang_251; ?></a></font> <font size="1" face="Arial, Helvetica, sans-serif">
          &nbsp;&nbsp;-&nbsp;&nbsp; </font><font size="2" face="Arial, Helvetica, sans-serif">
          <?PHP } ?>
          </font><font size="1" face="Arial, Helvetica, sans-serif"><a href="#"  onClick="MM_openBrWindow('http://www./support/docs/12all/view.php?id=3157','Help','scrollbars=yes,width=316,height=350')">[
          ? ] <?PHP print $lang_252; ?></a></font></p></td>
    </tr>
  </table>
  <?PHP
        $visEdit_root = __FILE__ ;
        $visEdit_root = str_replace('\\', '/', $visEdit_root);
        $visEdit_root = str_replace('list_respond_manage.php', 'e_data/', $visEdit_root);
        //$visEdit_root = 'e_data/';
        include $visEdit_root.'visEdit_control.class.php';
        include $visEdit_root.'/lib/lang/en/en_lang_data.inc.php';

        $visEdit_dropdown_data['style']['default'] = 'No styles';
        $content = $row["content"];
        // Generate pre existing content
        $sw = new visEdit_Wysiwyg('Content' /*name*/,stripslashes($content) /*value*/,
                       'en' /*language*/, 'full' /*toolbar mode*/, 'default' /*theme*/);
        $sw->show();
}
?>
  <p> <font size="2" face="Arial, Helvetica, sans-serif"><b>
    <?PHP
  if ($type == "text"){
  ?>
    <?PHP print $lang_37; ?></b></font><br>
    <font size="1" face="Arial, Helvetica, sans-serif"><a href="#"  onclick="MM_openBrWindow('http://www./support/docs/12all/view.php?id=3156','Help','scrollbars=yes,width=316,height=350')"><?PHP print $lang_251; ?></a></font>
    <br>
    <textarea name="Content" cols="65" rows="8" id="Content"><?PHP print stripslashes($row["content"]); ?></textarea>
  </p>
  <?PHP } ?>
  <font size="2"><font face="Arial, Helvetica, sans-serif">
  <input type="submit" name="Submit" value="<?PHP print $lang_247; ?>">
  <input name="val" type="hidden" id="val" value="3">
  </font><font face="Arial, Helvetica, sans-serif"> </font><font face="Arial, Helvetica, sans-serif">
  <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
  </font><font face="Arial, Helvetica, sans-serif">
  <input name="page" type="hidden" id="page" value="list_respond_manage">
  </font><font face="Arial, Helvetica, sans-serif">
  <input name="type" type="hidden" id="type" value="<?PHP print $type; ?>">
  <input name="id" type="hidden" id="id" value="<?PHP print $id; ?>">
  </font><font size="2" face="Arial, Helvetica, sans-serif"> </font></font>
</form>
<?PHP
  }
  if ($val == "3"){
  $Content = addslashes($Content);
  $subject = addslashes($subject);
  $fromn = addslashes($fromn);
  mysql_query("UPDATE 12all_Respond SET subject='$subject',fromn='$fromn',frome='$frome',time='$hours',content='$Content' WHERE (id='$id')");
  ?>
<font size="2" face="Arial, Helvetica, sans-serif"><?PHP print "$lang_180 $lang_566"; ?>,
<?PHP   $subject = stripslashes($subject); print stripslashes($subject); ?>
, <?PHP print $lang_13; ?> </font>
<?PHP
  }
  if ($val == "6"){
        mysql_query ("DELETE FROM 12all_Respond
                                WHERE id = '$id'
                                                                LIMIT 1
                                                                ");
        print $lang_572;
  }
  ?>