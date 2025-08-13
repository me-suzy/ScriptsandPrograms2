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
    <td background="table.gif" width="100%"><font face="arial" size="2" color="#ffffff"><b>&nbsp;Ad-Managment: Banner</b></font></td>
  </tr>
  <tr>
    <td bgcolor="#336699" width="100%" align="center"><font face="arial" size="2" color="#ffffff"><br>Here you can manage your banners.
<br>
<table width="80%" bgcolor="#336699" cellspacing="1">
  <tr>
    <td bgcolor="#003366" background="table.gif" width="33%" align="center"><font face="arial" size="2" color="#ffffff"><b>ID</b></font></td>
    <td bgcolor="#003366" background="table.gif" width="33%" align="center"><font face="arial" size="2" color="#ffffff"><b>Banner</b></font></td>
    <td bgcolor="#003366" background="table.gif" width="33%" align="center"><font face="arial" size="2" color="#ffffff"><b>Action</b></font></td>
  </tr>
<?

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_banner ORDER BY id ASC";
$ergebnis = mysql_query($sql, $verbindung);

 while($row = mysql_fetch_object($ergebnis))
  {
?>
  <tr>
    <td bgcolor="#003366"  width="33%" align="center"><font face="arial" size="2" color="#ffffff"><? echo($row->id); ?></font></td>
    <td bgcolor="#003366"  width="33%" align="center"><font face="arial" size="2" color="#ffffff"><? echo($row->code); ?></font></td>
    <td bgcolor="#003366"  width="33%" align="center"><font face="arial" size="2" color="#ffffff"><a class="dl" href="rmbanner.php?id=<? echo($row->id); ?>">Delete</a></font></td>
  </tr>
<?
  }

mysql_free_result($ergebnis);
mysql_close($verbindung);

?>
 </table>
<br>
<a class="dl" href="nbanner.php">Add banner</a>
<br><br>
</font></td>
  </tr>
 </table>
<br>
<br>
 <table width="60%" bgcolor="#003366" cellspacing="1">
  <tr>
    <td background="table.gif" width="100%"><font face="arial" size="2" color="#ffffff"><b>&nbsp;Ad-Managment: Signatur</b></font></td>
  </tr>
  <tr>
    <td bgcolor="#336699" width="100%" align="center"><font face="arial" size="2" color="#ffffff"><br>
<form action="savesig.php" method="post">
Signatur: <input type="text" value="<? echo($sigwe); ?>" size="48" name="sig">&nbsp;<input type="submit" value="OK">
</form>
</font></td>
  </tr>
 </table>
</center>
</body>
</html>
