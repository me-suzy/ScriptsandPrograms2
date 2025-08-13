<? header("Cache-Control: no-cache");
 session_start();
 include ("config.inc.php");

$nmail = 0;


$usermail = strtolower($user."@".$sdomain);
?>
<html>
<head>
<title>Adressbuch</title>
<style>
<!--
a:link     { text-decoration: none      }
a:hover    { text-decoration: underline }
a:active   { text-decoration: none      }
a:visited  { text-decoration: none      }

a.newmsg:link     { color: #ff0000; text-decoration: none      }
a.newmsg:hover    { color: #ff0000; text-decoration: underline }
a.newmsg:active   { color: #ff0000; text-decoration: none      }
a.newmsg:visited  { color: #ff0000; text-decoration: none      }

a.oldmsg:link     { color: #000000; text-decoration: none      }
a.oldmsg:hover    { color: #000000; text-decoration: underline }
a.oldmsg:active   { color: #000000; text-decoration: none      }
a.oldmsg:visited  { color: #000000; text-decoration: none      }

a.dl:link     { color: #ffffff; text-decoration: none      }
a.dl:hover    { color: #ffffff; text-decoration: underline }
a.dl:active   { color: #ffffff; text-decoration: none      }
a.dl:visited  { color: #ffffff; text-decoration: none      }
-->
</style>
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
</head>
<body background="bkg.gif">
<center>
<form method="post" action="javascript:Update('<? echo($feld); ?>')" enctype="application/x-www-form-urlencoded" name="addressbook">
<table width="80%" bgcolor="#003366" cellspacing="1">
 <tr>
  <td bgcolor="#336699" background="table.gif"><font face="arial" size="2" color="#ffffff"><center><b>&nbsp;</b></center></font></td>
  <td bgcolor="#336699" background="table.gif"><font face="arial" size="2" color="#ffffff"><center><b>Name</b></center></font></td>
  <td bgcolor="#336699" background="table.gif"><font face="arial" size="2" color="#ffffff"><center><b>E-Mail</b></center></font></td>
 </tr>
 <input type="hidden" name="remainingstr" value="">
<?
$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_adressen WHERE User='$usermail' ORDER BY Name ASC";
$ergebnis = mysql_query($sql, $verbindung);

$cont = 0;

 while($row = mysql_fetch_object($ergebnis))
  {

?>
 <tr>
  <td bgcolor="#ffffff" align="center"><input type="checkbox" name="to" value="&quot;<? echo($row->Name); ?>&quot; &lt;<? echo($row->Email); ?>&gt;"></td>
  <td bgcolor="#ffffff"><font face="arial" size="2" color="#000000"><center><? echo($row->Name); ?></center></font></td>
  <td bgcolor="#ffffff"><font face="arial" size="2" color="#000000"><center><? echo($row->Email); ?></center></font></td>
 </tr>
<?
  }

mysql_free_result($ergebnis);
mysql_close($verbindung);
?>
<script language="JavaScript">
      <!--
      function Update(whichfield)
      {
         var e2 = document.addressbook.remainingstr.value;
         for (var i = 0; i < document.addressbook.elements.length; i++)
         {
            var e = document.addressbook.elements[i];
            if (e.name == "to" && e.checked)
            {
               if (e2)
                  e2 += ",";
               e2 += e.value;
            }
         }
         if (whichfield == "an")
            window.opener.document.smail.an.value = e2;
         else if (whichfield == "cc")
            window.opener.document.smail.cc.value = e2;
         else
            window.opener.document.smail.bcc.value = e2;
         window.close();
      }
      //-->
</script>

</table><br>
<input type="submit" name="mailto.x" value="OK"> 
<br>
</form>
<br>

</center>
</body>
</html>
