	<script language="JavaScript">
	function GP_popupConfirmMsg(msg) {
	  document.MM_returnValue = confirm(msg);
	}
	</script>
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_23; ?></strong></font></p>
<form name="form1" method="post" action="">
  <?PHP
  if ($opt != view){
  if ($val == "del"){
  print "<p>";
  mysql_query ("DELETE FROM Messages
  WHERE nl LIKE '$nl'
  AND id LIKE '$id'
								");

  print "$lang_451.<p>";
  }
  ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr bgcolor="#D5E2F0"> 
      <td width="78"> <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_29; ?></font></b></div></td>
      <td width="58"> <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_30; ?></font></b></div></td>
      <td> <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_31; ?></font></b></div></td>
      <td width="98" bgcolor="#D5E2F0">&nbsp;</td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr> 
      <td bgcolor="#CCCCCC"> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center">
          <?PHP 
$result = mysql_query ("SELECT * FROM Messages
						WHERE nl LIKE '$nl'
						AND status != '4'
                       	ORDER BY mdate DESC, mtime DESC, subject
");
if ($c1 = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
?>
          <tr bgcolor="#FFFFFF"> 
            <td width="70" bordercolor="#CCCCCC" bgcolor="#FFFFFF"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                <?PHP print $row["mdate"]; ?> </font></div></td>
            <td bordercolor="#CCCCCC" width="50"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                <?PHP print $row["mtime"]; ?> </font></div></td>
            <td bordercolor="#CCCCCC"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                <?PHP 
			if ($row["subject"] == ""){
			print "No Subject";
			}
			else {
			  $subject = ereg_replace ("[\]", "", $row["subject"]);	

			print $subject; 
			}
			?>
                </font></div></td>
            <td width="90" bordercolor="#CCCCCC"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_archive&val=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>&opt=view"><img src="media/info.gif" width="11" height="7" border="0"></a> 
                &nbsp;<a href="main.php?page=list_archive&val=del&id=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>"  onClick="GP_popupConfirmMsg('THIS CAN NOT BE UNDONE!\r\rAre you sure that you would like to remove this message?\r\rThis will delete this message and stats from this list without recovery options.');return document.MM_returnValue"><img src="media/del.gif" width="11" height="7" border="0"><br>
                </a><font size="1"><a href="main.php?page=list_sendr1&nl=<?PHP print $nl; ?>&psubject=<?PHP print $row["subject"]; ?>&pfrom=<?PHP print $row["mfrom"]; ?>&format=<?PHP print $row["type"]; ?>&ptext=<?PHP print $row["id"]; ?>&previewalpha=<?PHP print $row["id"]; ?>"><?PHP print $lang_456; ?></a></font><a href="main.php?page=list_archive&val=del&id=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>"> 
                </a></font></div></td>
          </tr>
          <?PHP
} 

} else {print "$lang_32.";} ?>
        </table></td>
    </tr>
  </table>
  <br>
  <table width="360" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
    <tr> 
      <td> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_143; ?></font></div>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">
          <tr> 
            <td width="50%"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="media/del.gif" width="11" height="7" border="0"> 
                = <?PHP print $lang_144; ?></font></div></td>
            <td width="50%"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="media/info.gif" width="11" height="7" border="0"> 
                = <?PHP print $lang_145; ?></font></div></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <?PHP }
  if ($opt == view){
  ?>
  <font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
  <?PHP
		  $result = mysql_query ("SELECT * FROM Messages
                         WHERE id LIKE '$val'
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></font></b></font></b></font></b></font></b></font></font></b></font></b></font></b></font></b></font> 
  <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D5E2F0">
    <tr> 
      <td> <div align="center"></div>
        <table width="100%" border="0" cellspacing="1" cellpadding="6">
          <tr valign="top"> 
            <td width="50%" bgcolor="#FFFFFF"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_146; ?> 
                <?PHP
  if($row["sent"] == "0"){
  print "UNKNOWN";
  }
  else{
  print $row["sent"];	
  }
  ?>
                <?PHP print $lang_147; ?>.</font></div></td>
          </tr>
          <tr valign="top">
            <td bgcolor="#FFFFFF"><font size="2" face="Arial, Helvetica, sans-serif"> 
              <a href="main.php?nl=<?PHP print $nl; ?>&page=list_archive_b&lid=<?PHP print $val; ?>">
              <?PHP 
						$resulttrack2 = mysql_query ("SELECT email FROM 12all_Bounce
                         WHERE mid LIKE '$val'
						");
			$tracknum=mysql_num_rows($resulttrack2);
			print $tracknum;
			?>
              <?PHP print $lang_579; ?>. ( 
              <?PHP 
				$ntotal = $row["amt"];
				$nvs = round(($tracknum / $ntotal),4);
				$nvs = round(($nvs * 100),4);
				print $nvs; 
				?>
              % )</a><b></b></font></td>
          </tr>
		    <?PHP
							$check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
			$chk = mysql_fetch_array($check);
			if ($chk["a_lt"] == "0") {
			?>
          <tr valign="top"> 
            <td bgcolor="#FFFFFF"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif">
                <?PHP 
			$vals = "lt/t_go.php?i=currentmesg&e=subscriberemailec&l=open";
			$resulttrack2 = mysql_query ("SELECT id FROM Messages
                         WHERE htmlmesg LIKE '%$vals%'
						 AND id LIKE '$val'
						");
			$tracknum=mysql_num_rows($resulttrack2);
			if ($tracknum != "0"){
			print $lang_148; ?>
                <?PHP
				$resulttrack = mysql_query ("SELECT * FROM Links
                         WHERE nl LIKE '$val'
						 AND link LIKE 'open'
						");
				$rowtrack = mysql_fetch_array($resulttrack);
				$trackid = $rowtrack["id"];
				$resulttrack2 = mysql_query ("SELECT id FROM 12all_LinksD
                         WHERE lid LIKE '$trackid'
						");
				$tracknum=mysql_num_rows($resulttrack2);
				if ($tracknum == ""){
				$tracknum = 0; 
				}
				print $tracknum;
				?>
                <?PHP print $lang_578; ?> <?PHP print $lang_149; ?>. ( 
                <?PHP 
				$ntotal = $row["amt"];
				$nvs = round(($tracknum / $ntotal),4);
				$nvs = round(($nvs * 100),4);
				print $nvs; 
				?>
                % )<b><br>
                </b></font><font size="4" face="Arial, Helvetica, sans-serif"><b><font size="1"> 
                <?PHP if($nvs >= 5){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 10){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 15){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 20){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 25){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 30){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 35){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 40){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 45){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 50){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 55){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 60){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 65){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 70){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 75){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 80){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 85){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 90){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 95){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                <?PHP if($nvs >= 100){ ?>
                <img src="media/box1.gif" width="8" height="6"> 
                <?PHP } else { ?>
                <img src="media/box2.gif" width="8" height="6"> 
                <?PHP } ?>
                </font></b></font><font size="2" face="Arial, Helvetica, sans-serif"><b> 
                <br>
                </b><a href="main.php?nl=<?PHP print $nl; ?>&page=lt/view2&id=<?PHP print $val; ?>&link=open&lid=<?PHP print $trackid; ?>"><font size="1"><?PHP print $lang_150; ?></font></a></font></div></td>
            <?PHP } ?>
          </tr>
          <tr valign="top"> 
            <td bgcolor="#FFFFFF"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"><a href="main.php?nl=<?PHP print $nl; ?>&page=lt/view&id=<?PHP print $row["id"]; ?>"><b><font size="2"> 
                <?PHP 			
			if ($row["tlinks"] == "yes"){
			print "$lang_151";
			}
?>
                </font></b></a></font></div></td>
          </tr>
		  <?PHP } ?>
        </table></td>
    </tr>
  </table>
  <p><font size="2" face="Arial, Helvetica, sans-serif"><b> <?PHP print $lang_29; ?></b>: 
    <?PHP	print $row["mdate"];	?>
    <br>
    <b><?PHP print $lang_30; ?></b>: 
    <?PHP	print $row["mtime"];	?>
    <br>
    <b><?PHP print $lang_35; ?></b> : 
    <?PHP	print $row["mfrom"];	?>
    </font></p>
  <p><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_31; ?></b>: 
    <?PHP
  $subject = ereg_replace ("[\]", "", $row["subject"]);	
  print $subject;	
  ?>
    </font></p>
  <p><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_34; ?></b>:</font></p>
  <p><font size="2" face="Arial, Helvetica, sans-serif"> 
    <?PHP 
  if ($row["type"] != text){
  ?>
    <font size="4"><b><font size="3"><?PHP print $lang_36; ?></font></b></font></font> 
  </p>
  <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#333333">
    <tr> 
      <td> <table width="100%" border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF">
          <tr> 
            <td> <p><font size="2" face="Arial, Helvetica, sans-serif"> 
                <?PHP
			    $htmlmesg = ereg_replace ("[\]", "", $row["htmlmesg"]);	

			  print $htmlmesg;	
			  ?>
                </font></p>
              <p><font size="2" face="Arial, Helvetica, sans-serif"> </font></p></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <br>
  <br>
  <font size="2" face="Arial, Helvetica, sans-serif"> 
  <?PHP } ?>
  <?PHP 
  if ($row["type"] != html){
  ?>
  <font size="4"><b><font size="3"><?PHP print $lang_37; ?></font></b></font></font> 
  <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#333333">
    <tr> 
      <td> <table width="100%" border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF">
          <tr> 
            <td> <p><font size="2" face="Arial, Helvetica, sans-serif"> 
                <?PHP	
			    $textmesg = ereg_replace ("[\]", "", $row["textmesg"]);
				$textmesg = nl2br($textmesg);
				print $textmesg;	?>
                </font><font size="2" face="Arial, Helvetica, sans-serif"> </font></p></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <p><font size="2" face="Arial, Helvetica, sans-serif"> 
    <?PHP } ?>
    </font></p>
  <p><font size="1" face="Arial, Helvetica, sans-serif"></font><b><font size="2" face="Arial, Helvetica, sans-serif"> 
    </font></b><font size="2" face="Arial, Helvetica, sans-serif"><b></b></font><font size="2" face="Arial, Helvetica, sans-serif"> 
    <?PHP } ?>
    </font></p>
</form>
