<script language="JavaScript" type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="160"><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_232; ?></strong></font></td>
    <td><div align="right"><font size="4" face="Arial, Helvetica, sans-serif"><font size="2"><font color="#999999" size="1"><?PHP print $lang_233; ?> </font> <font color="#999999" size="1">&gt;</font><font color="#<?PHP if ($val == ""){ print "333333"; } else { print "999999"; } ?>" size="1">
        <?PHP print $lang_234; ?> </font><font color="#<?PHP if ($val == "preview"){ print "333333"; } else { print "999999"; } ?>" size="1">
        </font><font size="4" face="Arial, Helvetica, sans-serif"><font size="2"><font color="#999999" size="1">&gt;</font></font></font><font color="#<?PHP if ($val == "preview"){ print "333333"; } else { print "999999"; } ?>" size="1">
        <?PHP print $lang_235; ?> <font size="4" face="Arial, Helvetica, sans-serif"><font size="2"><font color="#999999" size="1">&gt;</font></font></font><font color="#999999">
        <?PHP print $lang_236; ?></font></font></font><font color="#666666" size="1"><b> </b></font></font></div></td>
  </tr>
</table>
<p><font size="4" face="Arial, Helvetica, sans-serif">
  <?PHP
  if ($val == ""){ ?>
  </font>
<html>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<form action="main.php" method="post" name="adminForm">
  <table width="100%" border="0" cellspacing="0" cellpadding="8">
    <tr>
      <td width="90" height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_31; ?></b></font><b><font color="#336699" size="4" face="Arial, Helvetica, sans-serif">
        </font></b><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><b>
        </b></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif"><a href="#"  onclick="MM_openBrWindow('asdasdasdasd/support/12all_kb_min/index.php?page=index_v2&id=70&c=9','Help','scrollbars=yes,width=350,height=375')">[?]</a></font></td>
      <td height="30" bgcolor="#F3F3F3"> <font size="2" face="Arial, Helvetica, sans-serif">
        <input name="subject" type="text" value="<?PHP
                $psubject=urldecode($psubject);
                $psubject=stripslashes($psubject);
                print $psubject;
                ?>" size="64">
        </font></td>
    </tr>
    <tr>
      <td width="90" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_35; ?>
        </b></font><font size="2" face="Arial, Helvetica, sans-serif"><a href="#"  onclick="MM_openBrWindow('asdasdasd/support/12all_kb_min/index.php?page=index_v2&id=71&c=9','Help','scrollbars=yes,width=350,height=375')">[?]</a></font><font size="2" face="Arial, Helvetica, sans-serif"><b><br>
        </b></font></td>
      <td height="30"> <table width="425" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%"><font size="2" face="Arial, Helvetica, sans-serif">
              <input name="fromn" type="text" id="fromn" value="<?PHP print $pfromn; ?>" size="29">
              </font></td>
            <td width="50%"><font size="2" face="Arial, Helvetica, sans-serif">
              <input name="from" type="text" id="from" value="<?PHP
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
  </table>
  <p>
    <?PHP
  if ($format != text){
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
          </font><font size="1" face="Arial, Helvetica, sans-serif"> <a href="#"  onClick="MM_openBrWindow('/support/12all_kb_min/index.php?page=index_v2&id=68&c=2','Help','scrollbars=yes,width=350,height=375')">[
          ? ] <?PHP print $lang_251; ?></a></font> <font size="1" face="Arial, Helvetica, sans-serif">
          &nbsp;&nbsp;-&nbsp;&nbsp; </font><font size="2" face="Arial, Helvetica, sans-serif">
          <?PHP } ?>
          </font><font size="1" face="Arial, Helvetica, sans-serif"><a href="#"  onClick="MM_openBrWindow("/support/12all_kb_min/index.php?page=index_v2&id=69&c=2','Help','scrollbars=yes,width=350,height=375')">[
          ? ] <?PHP print $lang_252; ?></a></font></p></td>
    </tr>
  </table>
    <?PHP
        $srcloc = $htmltemp;
        $previewalpha = "$pcontent";
        $previewalpha2 = "$pcontent2";
                                $check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
                                                 limit 1
                       ");
        $chk = mysql_fetch_array($check);
        $preview=$previewalpha;
        $preview2=$previewalpha2;



        $visEdit_root3 = __FILE__ ;
        $visEdit_root3 = str_replace('\\', '/', $visEdit_root3);
        $visEdit_root3 = str_replace('list_send2.php', 'e_data/', $visEdit_root3);

        //$visEdit_root = 'e_data/';
        include $visEdit_root3.'visEdit_control.class.php';
        $visEdit_dropdown_data['style']['default'] = 'No styles';

        // Generate pre existing content

                        if($preview == "" AND $preview2 == ""){
                        $setter = mysql_query ("SELECT * FROM Templates
                                                                         WHERE id LIKE '$srcloc'
                                                                        LIMIT 1
                                                                   ");

                        $set = mysql_fetch_array($setter);
                        $ccon = $set[content];
                        }
                        else {
                        if ($preview != ""){
                        $setter = mysql_query ("SELECT * FROM MessagesT
                                                                        WHERE id LIKE '$preview'
                                                                        LIMIT 1
                                                                   ");
                        $set = mysql_fetch_array($setter);
                        $ccon = urldecode($set[content]);
                        }
                        if ($preview2 != ""){
                        $setter = mysql_query ("SELECT * FROM Messages
                                                                        WHERE id LIKE '$preview2'
                                                                        LIMIT 1
                                                                   ");
                        $set = mysql_fetch_array($setter);
                        $ccon = urldecode($set[htmlmesg]);
                        $urlfinder = mysql_query ("SELECT * FROM Backend
                                                                         WHERE valid LIKE '1'
                                                                         limit 1
                                                                   ");
                        $findurl = mysql_fetch_array($urlfinder);
                        $murl = $findurl["murl"];
                        $val_replace = "--------------------------------------<br>$lang_266 <a href=\"$murl/box.php?funcml=unsub2&nlbox[1]=currentnl&email=subscriberemail\">$lang_267</a>.";
                        $ccon = str_replace ("$val_replace", "", $ccon);
                        $val_replace = "<br><img src=\"$murl/lt/t_go.php?i=currentmesg&e=subscriberemailec&l=open\" width=\"1\" height=\"1\" border=\"0\">";
                        $ccon = str_replace ("$val_replace", "", $ccon);
                        $lin_replace = "<A href=\"$murl/lt/t_go.php?i=currentmesg&e=subscriberemailec&l=";
                        $ccon = str_replace ("$lin_replace", "<A href=\"", $ccon);
                        $ccon = str_replace("|Q|", "?", $ccon);
                        $ccon = str_replace("|A|", "&", $ccon);
                        $ccon = str_replace("|E|", "=", $ccon);
                        }
                        }

        $sw = new visEdit_Wysiwyg('Content' /*name*/,stripslashes($ccon) /*value*/,
                       'en' /*language*/, 'full' /*toolbar mode*/, 'default' /*theme*/);

        $sw->show();

}
?>
  <p> <font size="2" face="Arial, Helvetica, sans-serif"><b>
    <?PHP
  if ($format != html){
  ?>
    <?PHP print $lang_37; ?></b></font><br>
    <font size="1" face="Arial, Helvetica, sans-serif"><a href="#"  onclick="MM_openBrWindow('/support/12all_kb_min/index.php?page=index_v2&id=69&c=2','Help','scrollbars=yes,width=350,height=375')"><?PHP print $lang_251; ?></a></font> <br>
    <textarea name="Text" cols="65" rows="8"><?PHP
        if ($prtext == ""){
        if ($ptext == ""){
        if ($texttemp != "" AND $texttemp != Blank){
$settert = mysql_query ("SELECT * FROM Templates
                         WHERE id LIKE '$texttemp'
                                                LIMIT 1
                       ");

$sett = mysql_fetch_array($settert);
$texttempd = $sett[content];
        print stripslashes($texttempd);
        }
        }
        else {
        $setter = mysql_query ("SELECT * FROM Messages
                                                        WHERE id LIKE '$ptext'
                                                        LIMIT 1
                                                   ");
        $set = mysql_fetch_array($setter);
        $ccon = $set[textmesg];
        $urlfinder = mysql_query ("SELECT * FROM Backend
                                                         WHERE valid LIKE '1'
                                                         limit 1
                                                   ");
        $findurl = mysql_fetch_array($urlfinder);
        $murl = $findurl["murl"];
        $val_replace = "--------------------------------------\n$lang_266 $lang_267:\n";
        $ccon = str_replace ("$val_replace", "", $ccon);
        $val_replace = "$murl/box.php?funcml=unsub2&nlbox[1]=currentnl&email=subscriberemail";
        $ccon = str_replace ("$val_replace", "", $ccon);
        print stripslashes($ccon);
        }
        }
        else {
        $ccon = urldecode($prtext);
        $urlfinder = mysql_query ("SELECT * FROM Backend
                                                         WHERE valid LIKE '1'
                                                         limit 1
                                                   ");
        $findurl = mysql_fetch_array($urlfinder);
        $murl = $findurl["murl"];
        $val_replace = "--------------------------------------\n$lang_266 $lang_267:\n";
        $ccon = str_replace ("$val_replace", "", $ccon);
        $val_replace = "$murl/box.php?funcml=unsub2&nlbox[1]=currentnl&email=subscriberemail";
        $ccon = str_replace ("$val_replace", "", $ccon);
        print stripslashes($ccon);        }
        ?></textarea>
  </p>
  <?PHP } ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td width="275"><font size="2" face="Arial, Helvetica, sans-serif">
        <?PHP
                                                        $check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
                                                 limit 1
                       ");
                        $chk = mysql_fetch_array($check);
                        if ($chk["a_lt"] == "0") {
                        ?>
        </font>
        <?PHP @include("lt/links_inc.php");
                        ?>
        <font size="2" face="Arial, Helvetica, sans-serif">
        <?PHP } ?>
        </font> <table width="100%" border="0" cellspacing="0" cellpadding="8">
          <tr>
            <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_254; ?>
              </b><font size="2"><a href="#h1"  onClick="MM_openBrWindow('/support/12all_kb_min/index.php?page=index_v2&id=72&c=9','Help','scrollbars=yes,width=350,height=375')">[?]</a></font></font></td>
            <td height="30"> <font size="2" face="Arial, Helvetica, sans-serif">
              <input name="includeunsub" type="checkbox" id="includeunsub" value="yes" checked>
              <?PHP print $lang_197; ?> </font></td>
          </tr>
          <?PHP
                                                        $check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
                                                 limit 1
                       ");
                        $chk = mysql_fetch_array($check);
                        if ($chk["a_lt"] == "0") {
                        ?>
          <tr>
            <td width="150" height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_255; ?></b></font></td>
            <td height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif">
              <input name="includetracking" type="checkbox" id="includetracking" value="yes" checked>
              <?PHP print $lang_197; ?> <br>
              <font size="1"><?PHP print $lang_256; ?></font></font></td>
          </tr>
          <?PHP } ?>
        </table></td>
      <td width="20">&nbsp;</td>
      <td>
<table width="100%" border="0" cellspacing="0" cellpadding="8">
          <tr>
            <td width="150" height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><b><a name="h1"></a><?PHP print $lang_187; ?>
              </b></font><font size="2" face="Arial, Helvetica, sans-serif"><a href="#h1"  onClick="MM_openBrWindow('/support/12all_kb_min/index.php?page=index_v2&id=74&c=9','Help','scrollbars=yes,width=350,height=375')">[?]</a></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
            <td height="30" bgcolor="#F3F3F3"> <font size="2" face="Arial, Helvetica, sans-serif">
              <select name="header" size=1>
                <option selected><font color="#000000"><?PHP print $lang_253; ?></font></option>
                <?PHP

$setter = mysql_query ("SELECT * FROM Templates
                         WHERE nl LIKE '$nl'
                                                 AND type LIKE 'header'
                                                 ORDER BY name
                       ");

if ($c1 = mysql_num_rows($setter)) {

while($set = mysql_fetch_array($setter)) {
?>
                <option value="<?PHP print $set["id"]; ?>"><font color="#000000">
                <?PHP print $set["name"]; ?> </font></option>
                <?PHP
}

} else {print "";} ?>
              </select>
              </font></td>
          </tr>
          <tr>
            <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_188; ?>
              </b></font><font size="2" face="Arial, Helvetica, sans-serif"><a href="#h1"  onClick="MM_openBrWindow('/support/12all_kb_min/index.php?page=index_v2&id=75&c=9','Help','scrollbars=yes,width=350,height=375')">[?]</a></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif"><b>
              </b></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
            <td height="30"> <font size="2" face="Arial, Helvetica, sans-serif">
              <select name="footer" size=1 >
                <option selected><font color="#000000" size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_253; ?></font></option>
                <?PHP

$setter = mysql_query ("SELECT * FROM Templates
                         WHERE nl LIKE '$nl'
                                                 AND type LIKE 'footer'
                                                 ORDER BY name
                       ");

if ($c1 = mysql_num_rows($setter)) {

while($set = mysql_fetch_array($setter)) {
?>
                <option value="<?PHP print $set["id"]; ?>"><font color="#000000" size="2" face="Arial, Helvetica, sans-serif">
                <?PHP print $set["name"]; ?> </font></option>
                <?PHP
}

} else {print "";} ?>
              </select>
              </font></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <p>
      </p>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="290" height="30"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;<?PHP
  $cucc = 1;
  $s_total = 0;
foreach ($nlbox as $something)
{
if ($something != "")
{
?>
    <input type="hidden" name="nlbox[<?PHP print $cucc; ?>]" value="<?PHP print $something; ?>">
    <?PHP
                        $filterdata = "";
                if ($filter != ""){
                        $prefilter = "AND nl LIKE '$something'
                                                AND email != ''
                                                AND active LIKE '0'";
                        $ffind = mysql_query ("SELECT * FROM Templates
                                                                 WHERE id LIKE '$filter'
                                                                 LIMIT 1
                                                                ");
                        $fresult = mysql_fetch_array($ffind);
                        $filterdata = stripslashes($fresult["content"]);
                        $filterdata = "AND $filterdata";
                        $filterdata = str_replace (" DIVIN", "$prefilter", $filterdata);
                }
                $findcount = mysql_query ("SELECT * FROM ListMembers
                WHERE nl LIKE '$something'
                AND email != ''
                AND active LIKE '0'
                $filterdata
                ");
                $findcount123 = mysql_num_rows($findcount);
                $s_total = $s_total+$findcount123;
$cucc = $cucc + 1;
}
}

        $cucc = $cucc - 1;

?>
    <input type="submit" value="<?PHP print $lang_257; ?>" name="submit" onClick="MM_validateForm('subject','','R','frome','','RisEmail');return document.MM_returnValue">
    <input type="hidden" name="page" value="list_send2">
    <input type="hidden" name="type" value="<?PHP if ($browser == MSIE){ print HTML; } else { print text; }?>">
    <input type="hidden" name="nl" value="<?PHP print $nl; ?>">
    <input type="hidden" name="val" value="preview">
    <input type="hidden" name="timeout" value="60">
    <input type="hidden" name="format" value="<?PHP print $format; ?>">
    <input type="hidden" name="nlamt" value="<?PHP print $cucc; ?>">
    <input name="savid" type="hidden" id="savid" value="<?PHP print $savid; ?>">
    <input name="btag" type="hidden" id="btag" value="<?PHP print $btag; ?>">
    <input name="filter" type="hidden" id="filter" value="<?PHP print $filter; ?>">
    <input name="select" type="hidden" id="select" value="<?PHP print $select; ?>">
</font></td>
      <td width="697" height="30"><p align="right"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_540; ?>
          <?PHP
        print $s_total;
?>
          <?PHP print $lang_541; ?>.<br>
          <?PHP print $lang_261; ?>
          <?PHP
        print $cucc; ?>
          <?PHP print $lang_262; ?></font></p></td>
    </tr>
  </table>
  <p><img src="media/footer.gif" width="540" height="1"> </p>
  <p><font color="#990000" size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_258; ?></strong></font></p>
  <p> <font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_259; ?><br>
    <textarea name="bodyhead" cols="65" rows="5" id="bodyhead"><?PHP
        if ($btag != ""){
        $settert3 = mysql_query ("SELECT * FROM Templates
                         WHERE id LIKE '$btag'
                                                LIMIT 1
                       ");

        $sett3 = mysql_fetch_array($settert3);
        $texttempd = $sett3[content];
        print stripslashes($texttempd);
        }
        else {
                $settert3 = mysql_query ("SELECT * FROM Templates
                         WHERE type LIKE 'DEFAULTBT'
                                                LIMIT 1
                       ");

        $sett3 = mysql_fetch_array($settert3);
        $texttempd = $sett3[content];
        $texttempd = stripslashes($texttempd);
        if ($texttempd != ""){
        print $texttempd;
        }
        else {
        ?>&lt;body bgcolor=&quot;#FFFFFF&quot; text=&quot;#000000&quot;&gt;<?PHP } } ?></textarea>
    </font></p>
  <p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_260; ?><br>
    <textarea name="bodyfoot" cols="65" rows="3" id="bodyfoot">&lt;/body&gt;</textarea>
    </font></p>
  <p>
    <input type="submit" value="<?PHP print $lang_257; ?>" name="submit2" onClick="MM_validateForm('subject','','R','from','','R');return document.MM_returnValue">
  </p>
</form>
<p><font size="4" face="Arial, Helvetica, sans-serif"><b>
  <?PHP
}
  if ($val == "preview"){

  $setter = mysql_query ("SELECT * FROM Templates
                         WHERE id LIKE '$header'
                                                 LIMIT 1
                       ");

$set = mysql_fetch_array($setter);
$header = $set["content"];
  $setter = mysql_query ("SELECT * FROM Templates
                         WHERE id LIKE '$footer'
                                                 LIMIT 1
                       ");

$set = mysql_fetch_array($setter);
$footer = $set["content"];


                                  $header2 = nl2br($header);
                                $footer2 = nl2br($footer);
?>
  </b></font></p>
<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
  <tr>
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="4" bgcolor="#FFFFFF">
        <tr>
          <td> <p><font face="Arial, Helvetica, sans-serif" size="2"><strong><?PHP print $lang_263; ?></strong></font><br>
              <font color="#666666" size="1" face="Arial, Helvetica, sans-serif">
              <?PHP print $lang_264; ?> <?PHP print $lang_265; ?></font></p></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
  <tr>
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="4" bgcolor="#FFFFFF">
        <tr>
          <td width="60"> <p><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_35; ?>:</b></font></p></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">
            <?PHP
  if ($fromn == ""){
  print $from;
  }
  else {
  print "$fromn ($from)";
  }
  ?>
            </font></td>
        </tr>
        <tr>
          <td width="60"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_31; ?>:</b></font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">
            <?PHP
                        $subject = stripslashes($subject);
                        print $subject;        ?>
            </font></td>
        </tr>
      </table></td>
  </tr>
</table>
<p><font size="2" face="Arial, Helvetica, sans-serif">
  <?PHP
  if ($format != text){
  ?>
  <font size="3"><b><?PHP print $lang_36; ?></b></font></font> </p>
<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
  <tr>
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF">
        <tr>
          <td> <p><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP
                        $visEdit_dir = __FILE__ ;
                        $visEdit_dir = str_replace('\\', '/', $visEdit_dir);
                        $visEdit_dir = str_replace('e_data/config/visEdit_control.config.php', '', $visEdit_dir);
                        $base_url = str_replace($visEdit_dir, '', dirname($HTTP_SERVER_VARS['PHP_SELF']));
                        $b_ddid = ''.$base_url.'/e_data/';
                        $visEdit_dir = $b_ddid;
                        // base url for images
                        $pimgnow = $base_url.'/';
                        $pimgnow = str_replace('list_send2.php', 'main.php', $pimgnow);
                        $file_loc = 'http://'.$HTTP_SERVER_VARS['SERVER_NAME'].$pimgnow.'main.php';
                        $Content = ereg_replace ("$file_loc#", "#", $Content);
                        $file_loc = 'http://'.$HTTP_SERVER_VARS['SERVER_NAME'].$pimgnow.'main.php';
                        $Content = ereg_replace ("$file_loc%", "%", $Content);
                        $Contentpre = $Content;
                        $Textpre = $Text;
                        $Subjectpre = $subject;
                                                        $Text2 = nl2br($Text);

if ($header2 != "" AND $header2 != "None"){
$Content = "$header2<p>$Content";
}
if ($footer2 != "" AND $footer2 != "None"){
$Content = "$Content<p>$footer2";
}
if($links == yes){
$linkresult = mysql_query ("SELECT * FROM Backend
                                                 WHERE valid LIKE '1'
                       ");
$lr = mysql_fetch_array($linkresult);
$murl = $lr["murl"];
$sendem=base64_encode($em);
$Content = ereg_replace ("[\]", "", $Content);

preg_match_all("/<a.+?href\s*=\s*([\"']?)(.+?)\\1*?>/is", $Content, $matches);

        foreach ($matches[2] as $something)
        {
                $stop = "";
                $pos = strpos($something, "http");
                if ($pos === false) {
                        $pos = strpos($something, "#");
                        if ($pos === false) {
                        }
                        else{
                                $stop = "yes";
                        }
                        $pos = strpos($something, "mailto:");
                        if ($pos === false) {
                        }
                        else{
                                $stop = "yes";
                        }
                        $pos = strpos($something, "%UNSUBSCRIBELINK%");
                        if ($pos === false) {
                        }
                        else{
                                $stop = "yes";
                        }
                        $pos = strpos($something, "%WEBCOPYLINK%");
                        if ($pos === false) {
                        }
                        else{
                                $stop = "yes";
                        }
                        $pos = strpos($something, "%UPDATEPROFILE%");
                        if ($pos === false) {
                        }
                        else{
                                $stop = "yes";
                        }


                }
                else{
                }
                if ($stop != "yes"){
                $something = str_replace("\" target=\"_", "", $something);
                $something = str_replace("?", "\?", $something);

                $old_link = "<A href=\"$something\">";

                $something = str_replace("?", "|Q|", $something);
                $something = str_replace("&", "|A|", $something);
                $something = str_replace("=", "|E|", $something);
                $something = str_replace("amp;", "", $something);
                $new_link = $something;
                $new_link = "$murl/lt/t_go.php?i=currentmesg&e=subscriberemailec&l=$new_link";
                $new_link = "<A href=\"$new_link\">";
                $Content = ereg_replace ("$old_link", "$new_link", $Content);
                //print "found 1<br>old - $old_link<br>new - $new_link";
                }
        }





$Text = ereg_replace ("href=\"http", "href=\"$newlink", $Text);
}
                  $urlfinder = mysql_query ("SELECT * FROM Backend
                         WHERE valid LIKE '1'

                                                 limit 1
                       ");
$findurl = mysql_fetch_array($urlfinder);
$murl = $findurl["murl"];
$Content = ereg_replace ("%WEBCOPYLINK%", "$murl/p_v.php?mi=currentmesg&nl=currentnl&ei=subscriberemail", $Content);
$Content = ereg_replace ("%UNSUBSCRIBELINK%", "$murl/box.php?funcml=unsub2&nlbox[1]=currentnl&email=subscriberemail", $Content);
$Content = ereg_replace ("%UPDATEPROFILE%", "$murl/p_m.php?mi=currentmesg&nl=currentnl&ei=subscriberemailec&eid=%subscriberid%", $Content);
if ($includeunsub == "yes"){
$botlink = "--------------------------------------<br>$lang_266 <a href=\"$murl/box.php?funcml=unsub2&nlbox[1]=currentnl&email=subscriberemail\">$lang_267</a>.";
$Content = "$Content<p>$botlink";
}
if ($includetracking == "yes"){
$imgtracker = "<img src=\"$murl/lt/t_go.php?i=currentmesg&e=subscriberemailec&l=open\" width=\"1\" height=\"1\" border=\"0\">";
$Content = "$Content<br>$imgtracker";
}

$Content = ereg_replace ("[\]", "", $Content);


if ($Content != ""){
$Content = "$bodyhead $Content $bodyfoot"; }


                        print $Content;        ?>
              </p></font></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<br>
<font size="2" face="Arial, Helvetica, sans-serif">
<?PHP } ?>
<?PHP
  if ($format != html){
  ?>
<font size="3"><b><?PHP print $lang_37; ?></b></font></font>
<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
  <tr>
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF">
        <tr>
          <td> <p><font size="2" face="Arial, Helvetica, sans-serif">
              <?PHP
                                $Text2 = nl2br($Text);
if ($header != "" AND $header != "None"){
$Text = "$header\n\n$Text";
$Text2 = "$header2<p>$Text2";
}
if ($footer != "" AND $footer != "None"){
$Text = "$Text\n\n$footer";
$Text2 = "$Text2<p>$footer2";
}
$urlfinder = mysql_query ("SELECT * FROM Backend
                         WHERE valid LIKE '1'

                                                 limit 1
                       ");
$findurl = mysql_fetch_array($urlfinder);
$murl = $findurl["murl"];

if ($includeunsub == "yes"){
$botlink = "--------------------------------------\n$lang_266 $lang_267:\n$murl/box.php?funcml=unsub2&nl=currentnl&email=subscriberemail";
$Text = "$Text\n\n$botlink";
$botlink = "--------------------------------------<br>$lang_266 $lang_267:<br>$murl/box.php?funcml=unsub2&nl=currentnl&email=subscriberemail";
$Text2 = "$Text2<p>$botlink";

}
$Text = ereg_replace ("%WEBCOPYLINK%", "$murl/p_v.php?mi=currentmesg&nl=currentnl&ei=subscriberemail", $Text);
$Text2 = ereg_replace ("%WEBCOPYLINK%", "$murl/p_v.php?mi=currentmesg&nl=currentnl&ei=subscriberemail", $Text2);
$Text = ereg_replace ("%UNSUBSCRIBELINK%", "$murl/box.php?funcml=unsub2&nl=currentnl&email=subscriberemail", $Text);
$Text2 = ereg_replace ("%UNSUBSCRIBELINK%", "$murl/box.php?funcml=unsub2&nl=currentnl&email=subscriberemail", $Text2);
$Text = ereg_replace ("%UPDATEPROFILE%", "$murl/p_m.php?mi=currentmesg&nl=currentnl&ei=subscriberemailec&eid=%subscriberid%", $Text);
$Text2 = ereg_replace ("%UPDATEPROFILE%", "$murl/p_m.php?mi=currentmesg&nl=currentnl&ei=subscriberemailec&eid=%subscriberid%", $Text2);

                                print stripslashes($Text2);        ?>
              </font></p></td>
        </tr>
      </table></td>
  </tr>
</table>
<p><font size="2" face="Arial, Helvetica, sans-serif">
  <?PHP }
 ?>
  </font></p>
<form Action="main.php" method="post" enctype="multipart/form-data" name="sender" id="sender">
                        <?PHP
                        $a_count = "0";
                        $check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
                                                 limit 1
                       ");
                        $chk = mysql_fetch_array($check);
                        if ($chk["a_ua"] == "0"){
                        $a_man = $chk["a_atch"];
                        while($a_count < $a_man){
                        $a_count++;
                        ?>
  <table width="100%" border="0" cellpadding="8" cellspacing="0">
    <tr>
      <td width="150" height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print "$lang_270 $a_count"; ?></strong></font></td>
      <td height="30" bgcolor="#F3F3F3"><input name="file[]" type="file" id="file[]">
      </td>
    </tr>
  </table>

  <?PHP
          }


  } ?>
  <?PHP
                                                        $check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
                                                 limit 1
                       ");
                        $chk = mysql_fetch_array($check);
                        if ($chk["a_sc"] == "0") {
                        ?>
  <br>
  <hr align="left" width="100%" size="1" noshade>
  <div align="left"><br>
    <table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#D5E2F0">
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <tr>
              <td> <table width="100%" border="0" cellspacing="0" cellpadding="4">
                  <tr>
                    <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_510; ?>
                        </strong> </font></div></td>
                    <td width="65"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_216; ?></font></div></td>
                    <td width="65"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_214; ?></font></div></td>
                    <td width="65"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_215; ?></font></div></td>
                    <td width="65"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_508; ?></font></div></td>
                    <td width="65"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_509; ?></font></div></td>
                  </tr>
                  <tr>
                    <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                        <input name="schedule" type="radio" id="schedule" value="yes">
                        <?PHP print $lang_197; ?>
                        <input name="schedule" type="radio" id="schedule" value="no" checked>
                        <?PHP print $lang_198; ?> </font></div></td>
                    <td width="65"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                        <select name="yearp" id="select2">
                          <option value="2004" selected>2004</option>
                          <option value="2005">2005</option>
                          <option value="2006">2006</option>
                          <option value="2007">2007</option>
                          <option value="2008">2008</option>
                          <option value="2009">2009</option>
                          <option value="2010">2010</option>
                        </select>
                        </font></div></td>
                    <td width="65"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                        <select name="monthp" id="select3">
                          <option value="01" selected>01</option>
                          <option value="02">02</option>
                          <option value="03">03</option>
                          <option value="04">04</option>
                          <option value="05">05</option>
                          <option value="06">06</option>
                          <option value="07">07</option>
                          <option value="08">08</option>
                          <option value="09">09</option>
                          <option value="10">10</option>
                          <option value="11">11</option>
                          <option value="12">12</option>
                        </select>
                        </font></div></td>
                    <td width="65"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                        <select name="dayp" id="select4">
                          <option value="01" selected>01</option>
                          <option value="02">02</option>
                          <option value="03">03</option>
                          <option value="04">04</option>
                          <option value="05">05</option>
                          <option value="06">06</option>
                          <option value="07">07</option>
                          <option value="08">08</option>
                          <option value="09">09</option>
                          <option value="10">10</option>
                          <option value="11">11</option>
                          <option value="12">12</option>
                          <option value="13">13</option>
                          <option value="14">14</option>
                          <option value="15">15</option>
                          <option value="16">16</option>
                          <option value="17">17</option>
                          <option value="18">18</option>
                          <option value="19">19</option>
                          <option value="20">20</option>
                          <option value="21">21</option>
                          <option value="22">22</option>
                          <option value="23">23</option>
                          <option value="24">24</option>
                          <option value="25">25</option>
                          <option value="26">26</option>
                          <option value="27">27</option>
                          <option value="28">28</option>
                          <option value="29">29</option>
                          <option value="30">30</option>
                          <option value="31">31</option>
                        </select>
                        </font></div></td>
                    <td width="65"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                        <select name="hourp" id="select10">
                          <option value="01" selected>01</option>
                          <option value="02">02</option>
                          <option value="03">03</option>
                          <option value="04">04</option>
                          <option value="05">05</option>
                          <option value="06">06</option>
                          <option value="07">07</option>
                          <option value="08">08</option>
                          <option value="09">09</option>
                          <option value="10">10</option>
                          <option value="11">11</option>
                          <option value="12">12</option>
                          <option value="13">13</option>
                          <option value="14">14</option>
                          <option value="15">15</option>
                          <option value="16">16</option>
                          <option value="17">17</option>
                          <option value="18">18</option>
                          <option value="19">19</option>
                          <option value="20">20</option>
                          <option value="21">21</option>
                          <option value="22">22</option>
                          <option value="23">23</option>
                          <option value="24">24</option>
                        </select>
                        </font></div></td>
                    <td width="65"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
                        <select name="minutep" id="select11">
                          <option value="00" selected>00</option>
                          <option value="10">10</option>
                          <option value="20">20</option>
                          <option value="30">30</option>
                          <option value="40">40</option>
                          <option value="50">50</option>
                        </select>
                        </font></div></td>
                  </tr>
                </table>
                <div align="center"> </div></td>
            </tr>
          </table></td>
      </tr>
    </table>
    <?PHP } ?>
    <br>
  </div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="200" height="45"><input name="submit" type="submit" style="background:red; color:white;" value=" <?PHP print $lang_268; ?> " ></td>
      <td height="45"> <div align="right"> <font color="#999999" size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_261; ?>
          <?PHP
        print $nlamt; ?>
          <?PHP print $lang_262; ?> <br>
          <?PHP
  $cucc = 1;
  $s_total = 0;
foreach ($nlbox as $something)
{
if ($something != "")
{
                        $filterdata = "";
                if ($filter != ""){
                        $prefilter = "AND nl LIKE '$something'
                                                AND email != ''
                                                AND active LIKE '0'";
                        $ffind = mysql_query ("SELECT * FROM Templates
                                                                 WHERE id LIKE '$filter'
                                                                 LIMIT 1
                                                                ");
                        $fresult = mysql_fetch_array($ffind);
                        $filterdata = stripslashes($fresult["content"]);
                        $filterdata = "AND $filterdata";
                        $filterdata = str_replace (" DIVIN", "$prefilter", $filterdata);
                }
                $findcount = mysql_query ("SELECT * FROM ListMembers
                WHERE nl LIKE '$something'
                AND email != ''
                AND active LIKE '0'
                $filterdata
                ");
                $findcount123 = mysql_num_rows($findcount);
                $s_total = $s_total+$findcount123;
$cucc = $cucc + 1;
}
}

        $cucc = $cucc - 1;

?>
          <?PHP print $lang_540; ?>
          <?PHP
        print $s_total;
?>
          <?PHP print $lang_541; ?>.</font> </div></td>
    </tr>
  </table>
  <p>
    <?PHP
  $cucc = 1;
foreach ($nlbox as $something)
{
if ($something != "")
{
?>
    <input type="hidden" name="nlbox[<?PHP print $cucc; ?>]" value="<?PHP print $something; ?>">
    <?PHP
$cucc = $cucc + 1;
}
}

        $cucc = $cucc - 1;

?>
    <input type="hidden" name="nlamt" value="<?PHP print $nlamt; ?>">
    <input type="hidden" name="page" value="list_send3">
    <input type="hidden" name="val" value="send">
    <input type="hidden" name="nl" value="<?PHP print $nl; ?>">
    <input type="hidden" name="type" value="<?PHP print $format; ?>">
    <input type="hidden" name="timeout" value="<?PHP print $timeout; ?>">
    <?PHP
        $Content=urlencode($Content);
    $Text=urlencode($Text);
        $Text = str_replace("\r", '', $Text);
        $subject=urlencode($subject);
?>
    <input type="hidden" name="subject" value="<?PHP print $subject; ?>">
    <input type="hidden" name="Content" value="<?PHP print $Content; ?>">
    <input type="hidden" name="from" value="<?PHP print $from; ?>">
    <input name="fromn" type="hidden" id="fromn" value="<?PHP print $fromn; ?>">
    <input type="hidden" name="Text" value="<?PHP print $Text; ?>">
    <input type="hidden" name="links" value="<?PHP print $links; ?>">
    <input type="hidden" name="max_file_size" value="100000">
    <input name="savid" type="hidden" id="savid" value="<?PHP print $savid; ?>">
    <input name="filter" type="hidden" id="filter" value="<?PHP print $filter; ?>">
  </p>
  </form>
<hr width="100%" size="1" noshade>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr valign="top">
    <td> <form name="" method="post" action="main.php">
        <div align="left">
          <input name="submit3" type="submit" id="submit34" value="<?PHP print $lang_269; ?>" >
          <?PHP
  $cucc = 1;
foreach ($nlbox as $something)
{
if ($something != "")
{
?>
          <input name="nlbox[<?PHP print $cucc; ?>]" type="hidden" id="nlbox[<?PHP print $cucc; ?>]23" value="<?PHP print $something; ?>">
          <?PHP
$cucc = $cucc + 1;
}
}

        $cucc = $cucc - 1;
        $Contentpre=urlencode($Contentpre);
        $extra = rand (1000, 90000);
        mysql_query ("INSERT INTO MessagesT (id, content) VALUES ('$extra' ,'$Contentpre')");
?>
          <input name="nlamt" type="hidden" id="nlamt222" value="<?PHP print $nlamt; ?>">
          <input name="page" type="hidden" id="page222" value="list_send2">
          <input name="val" type="hidden" id="val222">
          <input name="nl" type="hidden" id="nl222" value="<?PHP print $nl; ?>">
          <input name="type" type="hidden" id="type222" value="<?PHP print $format; ?>">
          <input name="timeout" type="hidden" id="timeout222" value="<?PHP print $timeout; ?>">
          <input name="psubject" type="hidden" id="psubject4" value="<?PHP print $subject; ?>">
          <input name="pfrom" type="hidden" id="pfrom4" value="<?PHP print $from; ?>">
          <input name="pfromn" type="hidden" id="pfrom4" value="<?PHP print $fromn; ?>">
          <input name="pcontent" type="hidden" id="pcontent4" value="<?PHP print $extra; ?>">
          <input name="prtext" type="hidden" id="prtext3" value="<?PHP print $Textpre; ?>">
          <input name="links" type="hidden" id="links222" value="<?PHP print $links; ?>">
          <input name="format" type="hidden" id="format23" value="<?PHP print $format; ?>">
          <input name="savid" type="hidden" id="savid222" value="<?PHP print $savid; ?>">
          <input name="btag" type="hidden" id="btag23" value="<?PHP print $btag; ?>">
          <input name="filter" type="hidden" id="filter222" value="<?PHP print $filter; ?>">
          <input name="select" type="hidden" id="select222" value="<?PHP print $select; ?>">
        </div>
      </form></td>
    <td width="165"><form name="" method="post" enctype="multipart/form-data" action="main.php">
        <p align="left">
          <input name="submit" type="submit" id="submit6" value="<?PHP print $lang_455; ?>" >
          <?PHP
  $cucc = 1;
foreach ($nlbox as $something)
{
if ($something != "")
{
        $nlpass = "$nlpass, $something , ";
$cucc = $cucc + 1;
}
}

        $cucc = $cucc - 1;

?>
          <input type="hidden" name="nlpass" value="<?PHP print $nlpass; ?>">
          <input name="nlamt" type="hidden" id="nlamt23" value="<?PHP print $nlamt; ?>">
          <input name="page" type="hidden" id="page23" value="list_send_s1">
          <input name="val" type="hidden" id="val23" value="send">
          <input name="nl" type="hidden" id="nl23" value="<?PHP print $nl; ?>">
          <input name="type" type="hidden" id="type23" value="<?PHP print $format; ?>">
          <input name="timeout" type="hidden" id="timeout23" value="<?PHP print $timeout; ?>">
          <input name="subject" type="hidden" id="subject22" value="<?PHP print $subject; ?>">
          <input name="Content" type="hidden" id="Content22" value="<?PHP print $Content; ?>">
          <input name="from" type="hidden" id="from22" value="<?PHP print $from; ?>">
          <input name="fromn" type="hidden" id="from22" value="<?PHP print $fromn; ?>">
          <input name="Text" type="hidden" id="Text22" value="<?PHP print $Text; ?>">
          <input name="links" type="hidden" id="links23" value="<?PHP print $links; ?>">
          <input name="max_file_size" type="hidden" id="max_file_size22" value="100000">
          <input name="savid" type="hidden" id="savid24" value="<?PHP print $savid; ?>">
          <input name="filter" type="hidden" id="filter23" value="<?PHP print $filter; ?>">
          <input name="select" type="hidden" id="select23" value="<?PHP print $select; ?>">
        </p>
      </form></td>
    <td width="175"><form action="send_appt.php" method="post" enctype="multipart/form-data" name="testsender" target="_blank" id="testsender">
        <p align="right">
          <input name="submit" type="submit" id="submit6" onClick="MM_validateForm('testemail','','RisEmail');return document.MM_returnValue" value="<?PHP print $lang_542; ?>" >
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr>
            <td width="50%"><font size="1">&nbsp;</font></td>
            <td width="50%" bgcolor="#CCCCCC"> <font size="1">&nbsp;</font></td>
          </tr>
          <tr bgcolor="#CCCCCC">
            <td colspan="2"><div align="center">
                <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" bgcolor="#F5F5F5">
                  <tr>
                    <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_543; ?>: </font>
                        <input name="testemail" type="text" id="testemail" value="<?PHP
                                                $check = mysql_query ("SELECT * FROM Lists
                                                                                                 WHERE id LIKE '$nl'
                                                                                                 limit 1
                                                                                        ");
                                                $chk = mysql_fetch_array($check);
                                                print $chk["email"]; ?>" size="20" onFocus="this.value=''">
                        <?PHP
  $cucc = 1;
foreach ($nlbox as $something)
{
if ($something != "")
{
        $nlpass = "$nlpass, $something , ";
$cucc = $cucc + 1;
}
}

        $cucc = $cucc - 1;

?>
                        <input type="hidden" name="nlpass" value="<?PHP print $nlpass; ?>">
                        <input name="nlamt" type="hidden" id="nlamt23" value="<?PHP print $nlamt; ?>">
                        <input name="page" type="hidden" id="page23" value="list_send_s1">
                        <input name="val" type="hidden" id="val23" value="send">
                        <input name="nl" type="hidden" id="nl23" value="<?PHP print $nl; ?>">
                        <input name="type" type="hidden" id="type23" value="<?PHP print $format; ?>">
                        <input name="timeout" type="hidden" id="timeout23" value="<?PHP print $timeout; ?>">
                        <input name="subject" type="hidden" id="subject22" value="<?PHP print $subject; ?>">
                        <input name="Content" type="hidden" id="Content22" value="<?PHP print $Content; ?>">
                        <input name="from" type="hidden" id="from22" value="<?PHP print $from; ?>">
                        <input name="fromn" type="hidden" id="from22" value="<?PHP print $fromn; ?>">
                        <input name="Text" type="hidden" id="Text22" value="<?PHP print $Text; ?>">
                        <input name="links" type="hidden" id="links23" value="<?PHP print $links; ?>">
                        <input name="max_file_size" type="hidden" id="max_file_size22" value="100000">
                        <input name="savid" type="hidden" id="savid24" value="<?PHP print $savid; ?>">
                        <input name="filter" type="hidden" id="filter23" value="<?PHP print $filter; ?>">
                        <input name="select" type="hidden" id="select23" value="<?PHP print $select; ?>">
                      </div></td>
                  </tr>
                </table>
              </div></td>
          </tr>
        </table>
      </form></td>
  </tr>
</table>
<hr width="100%" size="1" noshade>
<br>
<?PHP
}
?>
</body></html>