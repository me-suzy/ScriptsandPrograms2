<?
require("lang_select.php");
?>
<html>
<head>
<title>Message Archive</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?PHP print $lang_char; ?>">
</head>

<body>
<p align="center"><font color="#003366" size="6" face="Arial, Helvetica, sans-serif"><strong><? print $lang_23; ?></strong></font></p>
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td> 
      <? if ($nl == ""){ 
?>
      <p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><? print $lang_24; ?></strong></font></p>
      <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#BFD2E8">
        <tr> 
          <td> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center" bgcolor="#FFFFFF">
              <tr bgcolor="#D5E2F0"> 
                <td bordercolor="#CCCCCC" bgcolor="#D5E2F0"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><b><? print $lang_25; ?> </b></font></div></td>
                <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b><? print $lang_26; ?></b></font></div></td>
              </tr>
              <?php 
$result = mysql_query ("SELECT * FROM Lists
                         WHERE name != ''
                       	ORDER BY name
						");
if ($c = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {


?>
              <tr <? if ($cpick == 0){ ?>bgcolor="#F3F3F3"<? } else{ ?>bgcolor="#E9E9E9"<? } ?>> 
                <td bordercolor="#CCCCCC"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
                    <a href="archive.php?nl=<? print $row["id"]; ?>"> <font color="#000000" size="2"> 
                    <?php print $row["name"]; ?> </font></a></font></div></td>
                <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                    <?
			  			  $nlid = $row["id"];

					  $findcount = mysql_query ("SELECT * FROM Messages
                         WHERE nl LIKE '$nlid'
                       ");

$countdata = mysql_num_rows($findcount);
print $countdata;
?>
                    </font></div></td>
              </tr>
              <?php
				   if ($cpick == 0){
  $cpick = 1; 
  }
  else {
  $cpick = 0;
  }
}

} else {
?>
              <tr bgcolor="#FFFFFF"> 
                <td bordercolor="#CCCCCC"> <div align="left"><font color="#000000" size="2" face="Arial, Helvetica, sans-serif"> 
                    <? print $lang_27; ?></font></div></td>
                <td width="125" bordercolor="#CCCCCC">&nbsp; </td>
              </tr>
              <?php
				   if ($cpick == 0){
  $cpick = 1; 
  }
  else {
  $cpick = 0;
  }
}
?>
            </table></td>
        </tr>
      </table>
      <?
}
else {
?>
      <?
  if ($opt != view){
  if ($val == "empty"){
  print "<p>";
  print "<p>";
  }
  ?>
      <font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><? print $lang_28; ?></strong></font><br> <br> <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr bgcolor="#D5E2F0"> 
          <td width="58"> <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><? print $lang_29; ?></font></b></div></td>
          <td width="58"> <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><? print $lang_30; ?></font></b></div></td>
          <td bgcolor="#D5E2F0"> <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><? print $lang_31; ?></font></b></div></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr> 
          <td bgcolor="#CCCCCC"> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center">
              <?php 
$result = mysql_query ("SELECT * FROM Messages
						WHERE nl LIKE '$nl'
                       	ORDER BY mdate DESC, mtime DESC, subject
");
if ($c = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
?>
              <tr bgcolor="#FFFFFF"> 
                <td width="50" bordercolor="#CCCCCC" bgcolor="#FFFFFF"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"> 
                    <?php print $row["mdate"]; ?> </font></div></td>
                <td bordercolor="#CCCCCC" width="50"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"> 
                    <?php print $row["mtime"]; ?> </font></div></td>
                <td bordercolor="#CCCCCC"><font size="1" face="Arial, Helvetica, sans-serif"> 
                  <a href="archive.php?val=<?php print $row["id"]; ?>&nl=<? print $nl; ?>&opt=view"> 
                  <?php 
			if ($row["subject"] == ""){
			print "No Subject";
			}
			else {
			  $subject = ereg_replace ("[\]", "", $row["subject"]);	

			print $subject; 
			}
			?>
                  </a></font></td>
              </tr>
              <?php
}

} else {print "$lang_32
          ";} ?>
            </table></td>
        </tr>
      </table>
      <br>
      <a href="javascript:window.history.go(-1);"><font size="2" face="Arial, Helvetica, sans-serif"><? print $lang_33; ?></font></a> 
      <? }
  if ($opt == view){
  ?>
      <font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
      <?php
		  $result = mysql_query ("SELECT * FROM Messages
                         WHERE id LIKE '$val'
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
      </font></font></b></font></b></font></b></font></b></font></font></b></font></b></font></b></font></b></font> 
      <font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><? print $lang_34; ?><br>
      </strong></font><b><font size="2" face="Arial, Helvetica, sans-serif"> </font></b><font size="2" face="Arial, Helvetica, sans-serif"><b><br>
      </b></font> <table width="400" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="70" height="25"><font size="2" face="Arial, Helvetica, sans-serif"><b><? print $lang_29; ?></b>:</font></td>
          <td height="25"><font size="2" face="Arial, Helvetica, sans-serif"> 
            <?php	print $row["mdate"];	?>
            </font></td>
        </tr>
        <tr> 
          <td width="70" height="25"><font size="2" face="Arial, Helvetica, sans-serif"><b><? print $lang_30; ?></b>:</font></td>
          <td height="25"><font size="2" face="Arial, Helvetica, sans-serif"> 
            <?php	print $row["mtime"];	?>
            </font></td>
        </tr>
        <tr> 
          <td width="70" height="25"><font size="2" face="Arial, Helvetica, sans-serif"><b><? print $lang_35; ?></b>:</font></td>
          <td height="25"><font size="2" face="Arial, Helvetica, sans-serif"> 
            <?php	print $row["mfrom"];	?>
            </font></td>
        </tr>
        <tr> 
          <td width="70" height="25"><font size="2" face="Arial, Helvetica, sans-serif"><b><br>
            <? print $lang_31; ?></b>:</font></td>
          <td height="25"><font size="2" face="Arial, Helvetica, sans-serif"> 
            <br>
            <?php
  $subject = ereg_replace ("[\]", "", $row["subject"]);	
  print $subject;	
  ?>
            </font></td>
        </tr>
      </table>
      <p><br>
        <font size="2" face="Arial, Helvetica, sans-serif"> 
        <? 
  if ($row["type"] != text){
  ?>
        <font size="4"><b><font size="3"><? print $lang_36; ?></font></b></font></font> 
      </p>
      <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
        <tr> 
          <td> <table width="100%" border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF">
              <tr> 
                <td> <p><font size="2" face="Arial, Helvetica, sans-serif"> 
                    <?php
			    $htmlmesg = ereg_replace ("[\]", "", $row["htmlmesg"]);	

			  print $htmlmesg;	
			  ?>
                    </font><font size="2" face="Arial, Helvetica, sans-serif"> 
                    </font></p></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <br> <br> <font size="2" face="Arial, Helvetica, sans-serif"> 
      <? } ?>
      <? 
  if ($row["type"] != html){
  ?>
      <font size="4"><b><font size="3"><? print $lang_37; ?></font></b></font></font> 
      <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
        <tr> 
          <td> <table width="100%" border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF">
              <tr> 
                <td> <p><font size="2" face="Arial, Helvetica, sans-serif"> 
                    <?php	
			    $textmesg = ereg_replace ("[\]", "", $row["textmesg"]);
				$textmesg = nl2br($textmesg);
				print $textmesg;	?>
                    </font><font size="2" face="Arial, Helvetica, sans-serif"> 
                    </font></p></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <p><a href="javascript:window.history.go(-1);"><font size="2" face="Arial, Helvetica, sans-serif"><? print $lang_33; ?></font></a></p>
      <p><font size="2" face="Arial, Helvetica, sans-serif"> 
        <? } ?>
        </font><b><font size="2" face="Arial, Helvetica, sans-serif"> </font></b><font size="2" face="Arial, Helvetica, sans-serif"><b></b></font><font size="2" face="Arial, Helvetica, sans-serif"> 
        <? } 
  } ?>
        </font></p>
      <p><font size="2" face="Arial, Helvetica, sans-serif"><a href="box.php?nl=<? print $nl; ?>"><? print $lang_38; ?></a></font></p>
      </td>
  </tr>
</table>
<strong><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"></font></strong> 
<p>&nbsp;</p>
</body>
</html>