<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_566; ?> (<?PHP print $lang_436; ?>) </strong></font></p>
  <?PHP
  if ($val == ""){
  ?>
<form name="form1" method="post" action="main.php">
  <table width="450" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="60" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_567; ?></strong></font></div></td>
      <td colspan="2" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif">
        <input name="type" type="radio" value="html" checked>
        <?PHP print $lang_485; ?><br>
        <input name="type" type="radio" value="text">
        <?PHP print $lang_486; ?> </font></td>
    </tr>
    <tr>
      <td width="60"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_568; ?></strong>
          </font></div></td>
      <td width="60"><font size="2" face="Arial, Helvetica, sans-serif">
        <input name="hours" type="text" id="hours" value="0" size="5">
        </font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_569; ?></font></td>
      <?PHP
                                          $result213 = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
                                                 limit 1
                       ");
$listinfo = mysql_fetch_array($result213);

                $cnumc = 0;
                while($cnumc !=11){
                if ($listinfo["field$cnumc"] != ""){
                ?>
    </tr>
    <?PHP
                }
                $cnumc = $cnumc + 1;
                }
                ?>
    <tr>
      <td colspan="3"> <p><font size="2"><font face="Arial, Helvetica, sans-serif">
          <br>
          <input type="submit" name="Submit" value="<?PHP print $lang_247; ?>">
          <input name="val" type="hidden" id="val" value="2">
          </font><font face="Arial, Helvetica, sans-serif"> </font><font face="Arial, Helvetica, sans-serif">
          <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
          </font><font size="2" face="Arial, Helvetica, sans-serif">
          <input name="page" type="hidden" id="page" value="list_respond_add">
          </font></font></p></td>
    </tr>
  </table>
  </form>

<?PHP
  }
  if ($val == "2"){
  ?>
<form name="form1" method="post" action="main.php">
  <table width="100%" border="0" cellspacing="0" cellpadding="8">
    <tr>
      <td width="60" height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_31; ?></b></font><b><font color="#336699" size="4" face="Arial, Helvetica, sans-serif">
        </font></b><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><b>
        </b></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif"><a href="#"  onclick="MM_openBrWindow('http://www./support/docs/12all/view.php?id=3154','Help','scrollbars=yes,width=316,height=350')">[?]</a></font></td>
      <td height="30" colspan="2" bgcolor="#F3F3F3"> <font size="2" face="Arial, Helvetica, sans-serif">
        <input name="subject" type="text" size="64">
        </font></td>
    </tr>
    <tr>
      <td width="60" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_35; ?>
        </b></font><font size="2" face="Arial, Helvetica, sans-serif"><a href="#"  onclick="MM_openBrWindow('http://www./support/docs/12all/view.php?id=3155','Help','scrollbars=yes,width=316,height=350')">[?]</a></font><font size="2" face="Arial, Helvetica, sans-serif"><b><br>
        </b></font></td>
      <td height="30" colspan="2"> <table width="425" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%"><font size="2" face="Arial, Helvetica, sans-serif">
              <input name="fromn" type="text" id="fromn" size="29">
              </font></td>
            <td width="50%"><font size="2" face="Arial, Helvetica, sans-serif">
              <input name="frome" type="text" id="frome" value="<?PHP
                          if ($pfrom == ""){
                                                  $check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
                                                 limit 1
                               ");
                                                $chk = mysql_fetch_array($check);
                                print $chk["email"];
                          }
                          else{
                          print $pfrom;
                          } ?>" size="29">
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
        <input name="hours" type="text" id="hours" value="<?PHP print $hours; ?>" size="5">
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
          </font><font size="1" face="Arial, Helvetica, sans-serif"> <a href="#"  onClick="MM_openBrWindow('http://www./support/12all_kb_min/index.php?page=index_v2&id=68&c=2','Help','scrollbars=yes,width=350,height=375')">[
          ? ] <?PHP print $lang_251; ?></a></font> <font size="1" face="Arial, Helvetica, sans-serif">
          &nbsp;&nbsp;-&nbsp;&nbsp; </font><font size="2" face="Arial, Helvetica, sans-serif">
          <?PHP } ?>
          </font><font size="1" face="Arial, Helvetica, sans-serif"><a href="#"  onClick="MM_openBrWindow('http://www./support/12all_kb_min/index.php?page=index_v2&id=69&c=2','Help','scrollbars=yes,width=350,height=375')">[
          ? ] <?PHP print $lang_252; ?></a></font></p></td>
    </tr>
  </table>
  <?PHP
        $visEdit_root = __FILE__ ;
        $visEdit_root = str_replace('\\', '/', $visEdit_root);
        $visEdit_root = str_replace('list_respond_add.php', 'e_data/', $visEdit_root);
        //$visEdit_root = 'e_data/';
        include $visEdit_root.'visEdit_control.class.php';
        include $visEdit_root.'/lib/lang/en/en_lang_data.inc.php';

        $visEdit_dropdown_data['style']['default'] = 'No styles';

        // Generate pre existing content
        $sw = new visEdit_Wysiwyg('Content' /*name*/,stripslashes($ccon) /*value*/,
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
    <textarea name="Content" cols="65" rows="8" id="Content"></textarea>
  </p>
  <?PHP } ?>
  <font size="2"><font face="Arial, Helvetica, sans-serif">
  <input type="submit" name="Submit" value="<?PHP print $lang_247; ?>">
  <input name="val" type="hidden" id="val" value="3">
  </font><font face="Arial, Helvetica, sans-serif"> </font><font face="Arial, Helvetica, sans-serif">
  <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
  </font><font face="Arial, Helvetica, sans-serif">
  <input name="page" type="hidden" id="page" value="list_respond_add">
  </font><font face="Arial, Helvetica, sans-serif">
  <input name="type" type="hidden" id="type" value="<?PHP print $type; ?>">
  </font><font size="2" face="Arial, Helvetica, sans-serif"> </font></font>
</form>
<?PHP
  }
  if ($val == "3"){
  $Content = addslashes($Content);
  $subject = addslashes($subject);
  $fromn = addslashes($fromn);
  mysql_query ("INSERT INTO 12all_Respond (nl, type, subject, fromn, frome, time, content) VALUES ('$nl' ,'$type' ,'$subject' ,'$fromn' ,'$frome' ,'$hours' ,'$Content')");
  ?>
<font size="2" face="Arial, Helvetica, sans-serif"><?PHP print "$lang_180 $lang_566"; ?>, <?PHP   $subject = stripslashes($subject); print stripslashes($subject); ?>,
<?PHP print $lang_13; ?> </font>
<?PHP
  }
  ?>