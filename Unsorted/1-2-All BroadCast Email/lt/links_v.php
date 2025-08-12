<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><b> <?PHP print $lang_411; ?></b></font></p>
  <table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr bgcolor="#D5E2F0"> 
    <td width="58"> 
      <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_29; ?></font></b></div>
    </td>
    <td width="58"> 
      <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_30; ?></font></b></div>
    </td>
    <td> 
      <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_31; ?></font></b></div>
    </td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr> 
    <td bgcolor="#D5E2F0"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center">
        <?PHP 
$result = mysql_query ("SELECT * FROM Messages
						WHERE nl LIKE '$nl'
						AND tlinks LIKE 'yes'
                       	ORDER BY mdate DESC, mtime DESC, subject
");
if ($row = mysql_fetch_array($result)) {

do {
?>
        <tr bgcolor="#FFFFFF"> 
          <td width="50" bordercolor="#CCCCCC" bgcolor="#FFFFFF"> 
            <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <?PHP print $row["mdate"]; ?>
              </font></div>
          </td>
          <td bordercolor="#CCCCCC" width="50"> 
            <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <?PHP print $row["mtime"]; ?>
              </font></div>
          </td>
          <td bordercolor="#CCCCCC"><font size="1" face="Arial, Helvetica, sans-serif"> 
            <a href="main.php?nl=<?PHP print $nl; ?>&page=lt/view&id=<?PHP print $row["id"]; ?>">
            <?PHP 			
			if ($row["tlinks"] == "yes"){
			$sub = $row["subject"];
			print "Link Stats for message \"$sub\".";
			}
?>
            </a></font></td>
        </tr>
        <?PHP
} while($row = mysql_fetch_array($result));

} else {print "$lang_32.
          ";} ?>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
