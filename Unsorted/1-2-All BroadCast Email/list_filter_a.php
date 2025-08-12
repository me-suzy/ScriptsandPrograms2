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
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_460; ?> </strong><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"> 
  <?PHP
		  $result = mysql_query ("SELECT * FROM Backend
                         WHERE valid LIKE '1'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></b></font></b></font></b></font></b></font></font></p>
<?PHP
if ($action != save){
?>
<form name="form1" method="post" action="main.php">
  <table width="100%" border="0" cellspacing="0" cellpadding="4">
    <tr> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">Where:</font></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td width="33%" bgcolor="#F3F3F3"><select name="uno1" id="uno1">
          <?PHP
			$result23 = mysql_query ("SELECT * FROM Lists
									 WHERE id LIKE '$nl'
									 
									 limit 1
								   ");
			$row23 = mysql_fetch_array($result23);
			?>
          <option value=""></option>
          <option value="name">Name</option>
          <option value="email">E-mail</option>
          <option value="sdate">Signup Date</option>
          <option value="field1"> 
          <?PHP	if ($row23["field1"] != ""){ print $row23["field1"]; } else { print "Optional Field 1"; } ?>
          </option>
          <option value="field2"> 
          <?PHP	if ($row23["field2"] != ""){ print $row23["field2"]; } else { print "Optional Field 2"; } ?>
          </option>
          <option value="field3"> 
          <?PHP	if ($row23["field3"] != ""){ print $row23["field3"]; } else { print "Optional Field 3"; } ?>
          </option>
          <option value="field4"> 
          <?PHP	if ($row23["field4"] != ""){ print $row23["field4"]; } else { print "Optional Field 4"; } ?>
          </option>
          <option value="field5"> 
          <?PHP	if ($row23["field5"] != ""){ print $row23["field5"]; } else { print "Optional Field 5"; } ?>
          </option>
          <option value="field6"> 
          <?PHP	if ($row23["field6"] != ""){ print $row23["field6"]; } else { print "Optional Field 6"; } ?>
          </option>
          <option value="field7"> 
          <?PHP	if ($row23["field7"] != ""){ print $row23["field7"]; } else { print "Optional Field 7"; } ?>
          </option>
          <option value="field8"> 
          <?PHP	if ($row23["field8"] != ""){ print $row23["field8"]; } else { print "Optional Field 8"; } ?>
          </option>
          <option value="field9"> 
          <?PHP	if ($row23["field9"] != ""){ print $row23["field9"]; } else { print "Optional Field 9"; } ?>
          </option>
          <option value="field10"> 
          <?PHP	if ($row23["field10"] != ""){ print $row23["field10"]; } else { print "Optional Field 10"; } ?>
          </option>
        </select></td>
      <td width="33%" bgcolor="#F3F3F3"><select name="uno2" id="uno2">
          <option value=""></option>
          <option value="LIKE">Equals (Is)</option>
          <option value="!=">Does Not Equal (Is Not)</option>
          <option value="CONTAINS">Contains</option>
          <option value="&gt;=">Is Greater Than Or Equal To</option>
          <option value="&lt;=">Is Less Than Or Equal To</option>
          <option value="&gt;">Is Greater Than</option>
          <option value="&lt;">Is Less Than</option>
        </select></td>
      <td width="33%" bgcolor="#F3F3F3"><input name="uno3" type="text" id="uno3"></td>
    </tr>
    <tr> 
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <select name="div1" id="div1">
          <option value="And">And</option>
          <option value="Or">Or</option>
        </select>
        </font></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td bgcolor="#F3F3F3"><select name="dos1" id="dos1">
          <?PHP
			$result23 = mysql_query ("SELECT * FROM Lists
									 WHERE id LIKE '$nl'
									 
									 limit 1
								   ");
			$row23 = mysql_fetch_array($result23);
			?>
          <option value=""></option>
          <option value="name">Name</option>
          <option value="email">E-mail</option>
          <option value="sdate">Signup Date</option>
          <option value="field1"> 
          <?PHP	if ($row23["field1"] != ""){ print $row23["field1"]; } else { print "Optional Field 1"; } ?>
          </option>
          <option value="field2"> 
          <?PHP	if ($row23["field2"] != ""){ print $row23["field2"]; } else { print "Optional Field 2"; } ?>
          </option>
          <option value="field3"> 
          <?PHP	if ($row23["field3"] != ""){ print $row23["field3"]; } else { print "Optional Field 3"; } ?>
          </option>
          <option value="field4"> 
          <?PHP	if ($row23["field4"] != ""){ print $row23["field4"]; } else { print "Optional Field 4"; } ?>
          </option>
          <option value="field5"> 
          <?PHP	if ($row23["field5"] != ""){ print $row23["field5"]; } else { print "Optional Field 5"; } ?>
          </option>
          <option value="field6"> 
          <?PHP	if ($row23["field6"] != ""){ print $row23["field6"]; } else { print "Optional Field 6"; } ?>
          </option>
          <option value="field7"> 
          <?PHP	if ($row23["field7"] != ""){ print $row23["field7"]; } else { print "Optional Field 7"; } ?>
          </option>
          <option value="field8"> 
          <?PHP	if ($row23["field8"] != ""){ print $row23["field8"]; } else { print "Optional Field 8"; } ?>
          </option>
          <option value="field9"> 
          <?PHP	if ($row23["field9"] != ""){ print $row23["field9"]; } else { print "Optional Field 9"; } ?>
          </option>
          <option value="field10"> 
          <?PHP	if ($row23["field10"] != ""){ print $row23["field10"]; } else { print "Optional Field 10"; } ?>
          </option>
        </select></td>
      <td bgcolor="#F3F3F3"><select name="dos2" id="dos2">
          <option value=""></option>
          <option value="LIKE">Equals (Is)</option>
          <option value="!=">Does Not Equal (Is Not)</option>
          <option value="CONTAINS">Contains</option>
          <option value="&gt;=">Is Greater Than Or Equal To</option>
          <option value="&lt;=">Is Less Than Or Equal To</option>
          <option value="&gt;">Is Greater Than</option>
          <option value="&lt;">Is Less Than</option>
        </select></td>
      <td bgcolor="#F3F3F3"><input name="dos3" type="text" id="dos3"></td>
    </tr>
    <tr> 
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <select name="div2" id="div2">
          <option value="And">And</option>
          <option value="Or">Or</option>
        </select>
        </font></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td bgcolor="#F3F3F3"><select name="tres1" id="tres1">
          <?PHP
			$result23 = mysql_query ("SELECT * FROM Lists
									 WHERE id LIKE '$nl'
									 
									 limit 1
								   ");
			$row23 = mysql_fetch_array($result23);
			?>
          <option value=""></option>
          <option value="name">Name</option>
          <option value="email">E-mail</option>
          <option value="sdate">Signup Date</option>
          <option value="field1"> 
          <?PHP	if ($row23["field1"] != ""){ print $row23["field1"]; } else { print "Optional Field 1"; } ?>
          </option>
          <option value="field2"> 
          <?PHP	if ($row23["field2"] != ""){ print $row23["field2"]; } else { print "Optional Field 2"; } ?>
          </option>
          <option value="field3"> 
          <?PHP	if ($row23["field3"] != ""){ print $row23["field3"]; } else { print "Optional Field 3"; } ?>
          </option>
          <option value="field4"> 
          <?PHP	if ($row23["field4"] != ""){ print $row23["field4"]; } else { print "Optional Field 4"; } ?>
          </option>
          <option value="field5"> 
          <?PHP	if ($row23["field5"] != ""){ print $row23["field5"]; } else { print "Optional Field 5"; } ?>
          </option>
          <option value="field6"> 
          <?PHP	if ($row23["field6"] != ""){ print $row23["field6"]; } else { print "Optional Field 6"; } ?>
          </option>
          <option value="field7"> 
          <?PHP	if ($row23["field7"] != ""){ print $row23["field7"]; } else { print "Optional Field 7"; } ?>
          </option>
          <option value="field8"> 
          <?PHP	if ($row23["field8"] != ""){ print $row23["field8"]; } else { print "Optional Field 8"; } ?>
          </option>
          <option value="field9"> 
          <?PHP	if ($row23["field9"] != ""){ print $row23["field9"]; } else { print "Optional Field 9"; } ?>
          </option>
          <option value="field10"> 
          <?PHP	if ($row23["field10"] != ""){ print $row23["field10"]; } else { print "Optional Field 10"; } ?>
          </option>
        </select></td>
      <td bgcolor="#F3F3F3"><select name="tres2" id="tres2">
          <option value=""></option>
          <option value="LIKE">Equals (Is)</option>
          <option value="!=">Does Not Equal (Is Not)</option>
          <option value="CONTAINS">Contains</option>
          <option value="&gt;=">Is Greater Than Or Equal To</option>
          <option value="&lt;=">Is Less Than Or Equal To</option>
          <option value="&gt;">Is Greater Than</option>
          <option value="&lt;">Is Less Than</option>
        </select></td>
      <td bgcolor="#F3F3F3"><input name="tres3" type="text" id="tres3"></td>
    </tr>
    <tr> 
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <select name="div3" id="div3">
          <option value="And">And</option>
          <option value="Or">Or</option>
        </select>
        </font></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td bgcolor="#F3F3F3"><select name="quatro1" id="quatro1">
          <?PHP
			$result23 = mysql_query ("SELECT * FROM Lists
									 WHERE id LIKE '$nl'
									 
									 limit 1
								   ");
			$row23 = mysql_fetch_array($result23);
			?>
          <option value=""></option>
          <option value="name">Name</option>
          <option value="email">E-mail</option>
          <option value="sdate">Signup Date</option>
          <option value="field1"> 
          <?PHP	if ($row23["field1"] != ""){ print $row23["field1"]; } else { print "Optional Field 1"; } ?>
          </option>
          <option value="field2"> 
          <?PHP	if ($row23["field2"] != ""){ print $row23["field2"]; } else { print "Optional Field 2"; } ?>
          </option>
          <option value="field3"> 
          <?PHP	if ($row23["field3"] != ""){ print $row23["field3"]; } else { print "Optional Field 3"; } ?>
          </option>
          <option value="field4"> 
          <?PHP	if ($row23["field4"] != ""){ print $row23["field4"]; } else { print "Optional Field 4"; } ?>
          </option>
          <option value="field5"> 
          <?PHP	if ($row23["field5"] != ""){ print $row23["field5"]; } else { print "Optional Field 5"; } ?>
          </option>
          <option value="field6"> 
          <?PHP	if ($row23["field6"] != ""){ print $row23["field6"]; } else { print "Optional Field 6"; } ?>
          </option>
          <option value="field7"> 
          <?PHP	if ($row23["field7"] != ""){ print $row23["field7"]; } else { print "Optional Field 7"; } ?>
          </option>
          <option value="field8"> 
          <?PHP	if ($row23["field8"] != ""){ print $row23["field8"]; } else { print "Optional Field 8"; } ?>
          </option>
          <option value="field9"> 
          <?PHP	if ($row23["field9"] != ""){ print $row23["field9"]; } else { print "Optional Field 9"; } ?>
          </option>
          <option value="field10"> 
          <?PHP	if ($row23["field10"] != ""){ print $row23["field10"]; } else { print "Optional Field 10"; } ?>
          </option>
        </select></td>
      <td bgcolor="#F3F3F3"><select name="quatro2" id="quatro2">
          <option value=""></option>
          <option value="LIKE">Equals (Is)</option>
          <option value="!=">Does Not Equal (Is Not)</option>
          <option value="CONTAINS">Contains</option>
          <option value="&gt;=">Is Greater Than Or Equal To</option>
          <option value="&lt;=">Is Less Than Or Equal To</option>
          <option value="&gt;">Is Greater Than</option>
          <option value="&lt;">Is Less Than</option>
        </select></td>
      <td bgcolor="#F3F3F3"><input name="quatro3" type="text" id="quatro3"></td>
    </tr>
    <tr> 
      <td colspan="3"><br>
        <font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_4; ?></strong><br>
        <font size="1"><?PHP print $lang_185; ?></font> </font> </td>
    </tr>
    <tr> 
      <td colspan="3" bgcolor="#F3F3F3"><input name="fname" type="text" id="fname" size="35"></td>
    </tr>
    <tr> 
      <td height="30" colspan="3"><font size="2" face="Arial, Helvetica, sans-serif"><strong><br>
        <?PHP print $lang_294; ?></strong><br>
        <font size="1"><?PHP print $lang_295; ?><br>
        </font><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="univers" value="<?PHP print $row_admin["user"]; ?>" <?PHP $utemp = $row_admin["user"]; if ($row["uni"] == "$utemp"){ print "checked";} ?>>
        <?PHP print $lang_296; ?> <?PHP print $row_admin["user"]; ?> <?PHP print $lang_297; ?>.<br>
        <input type="radio" name="univers" value="all" <?PHP if ($row["uni"] == "all"){ print "checked";} ?>>
        <?PHP print $lang_298; ?><br>
        <input name="univers" type="radio" value="" <?PHP if ($row["uni"] == ""){ print "checked";} ?>>
        <?PHP print $lang_299; ?></font><font size="1"> </font></font></td>
    </tr>
  </table>
  <p><font size="2"><font face="Arial, Helvetica, sans-serif"> 
    <input name="Submit" type="submit" onClick="MM_validateForm('fname','','R');return document.MM_returnValue" value="<?PHP print $lang_21; ?>">
    </font></font><font size="2" face="Arial, Helvetica, sans-serif"> 
    <input name="page" type="hidden" id="page" value="list_filter_a">
    <input name="action" type="hidden" id="action" value="save">
    <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
    </font></p>
</form>
<p>
  <?PHP
}
else {
	if ($uno1 != "" AND $uno2 != "" AND $uno3 != ""){
		if ($uno2 == "CONTAINS"){
			$nquery = "$uno1 LIKE '%$uno3%'\n";
		}
		else {
			$nquery = "$uno1 $uno2 '$uno3'\n";
		}
	}
	else {
		print "You did not specify any filter rules or did not specify the first line.  Please try again.";
		die();
	}
	if ($dos1 != "" AND $dos2 != "" AND $dos3 != ""){
		if ($dos2 == "CONTAINS"){
			$nquery = "$nquery$div1 $dos1 LIKE '%$dos3%'\n";
		}
		else {
			$nquery = "$nquery$div1 $dos1 $dos2 '$dos3'\n";
		}	
		if ($div1 == "Or"){
			$nquery = "$nquery DIVIN ";
		}

	}
	if ($tres1 != "" AND $tres2 != "" AND $tres3 != ""){
		if ($tres2 == "CONTAINS"){
			$nquery = "$nquery$div2 $tres1 LIKE '%$tres3%'\n";
		}
		else {
			$nquery = "$nquery$div2 $tres1 $tres2 '$tres3'\n";
		}
		if ($div2 == "Or"){
			$nquery = "$nquery DIVIN ";
		}
	}
	if ($quatro1 != "" AND $quatro2 != "" AND $quatro3 != ""){
		if ($quatro2 == "CONTAINS"){
			$nquery = "$nquery$div3 $quatro1 LIKE '%$quatro3%'\n";
		}
		else {
			$nquery = "$nquery$div3 $quatro1 $quatro2 '$quatro3'\n";
		}
		if ($div3 == "Or"){
			$nquery = "$nquery DIVIN ";
		}
	}	
	
	$nqueryview = str_replace ("\n", "<br>", $nquery);
	$nquery = addslashes($nquery);
	$name = addslashes($fname);
	mysql_query ("INSERT INTO Templates (nl, name, content, type, uni) VALUES ('$nl' ,'$name' ,'$nquery' ,'FILTER' ,'$univers')");  
	print "$name, $lang_181.<p><br>";
	print "Details:<br>$nqueryview <META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=main.php?page=list_filter&nl=$nl\">
";

?>
  </font> 
  <?PHP
}
?>
</p>
