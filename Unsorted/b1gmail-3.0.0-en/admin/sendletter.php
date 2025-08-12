<? include("../config.inc.php"); ?>
<html>
<head>
 <title>Adminbereich</title>
 <SCRIPT language=javascript>
  <!--

   function LmOver(elem, clr)
   {elem.style.backgroundColor = clr;}

   function LmOut(elem, clr)
   {elem.style.backgroundColor = clr;}

   function LmDown(elem, clr)
   {elem.style.backgroundColor = clr;}

  //-->
 </SCRIPT>
 <style> 
  <!--  
   a:link     { text-decoration: none      }
   a:hover    { text-decoration: underline }
   a:active   { text-decoration: none      }
   a:visited  { text-decoration: none      }

   a.dl:link     { color: #ffffff; text-decoration: none      }
   a.dl:hover    { color: #ffffff; text-decoration: underline }
   a.dl:active   { color: #ffffff; text-decoration: none      }
   a.dl:visited  { color: #ffffff; text-decoration: none      }
  -->
 </style>
</head>

<body bgcolor="#ffffff">
<center>
 <table width="60%" bgcolor="#003366" cellspacing="1">
  <tr>
    <td background="table.gif" width="100%"><font face="arial" size="2" color="#ffffff"><b>&nbsp;Newsletter</b></font></td>
  </tr>
  <tr>
<?

$goodcount=0;
$badcount=0;
$allcount=0;

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);

$sql = "SELECT * FROM b1gmail_users ORDER BY id ASC";
$ergebnis = mysql_query($sql, $verbindung);
 while($row = mysql_fetch_object($ergebnis))
  {
$empf = $row->User;
$subj = stripslashes($betreff);
$stxt = stripslashes($text);
$head = "From:newsletter@mail";
$resu = mail($empf,$subj,$stxt,$head);
if ($resu) {
    $goodcount++;
} else {
    $badcount++;
}
$allcount++;
  }

mysql_free_result($ergebnis);

mysql_close($verbindung);

?>
    <td bgcolor="#336699" width="100%" align="center"><font face="arial" size="2" color="#ffffff"><br>The newsletter has been sended.<br><br>Sended: <? echo($goodcount); ?><br>Not sended: <? echo($badcount); ?><br>All: <? echo($allcount); ?>
<br>

<br>
</font></td>
  </tr>
 </table>
</center>
</body>
</html>
