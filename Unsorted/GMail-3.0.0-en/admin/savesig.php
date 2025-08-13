<?
 include("../config.inc.php"); 


$phpin = "<" . "?";

$phpout = "?" . ">";

$configphp = "$phpin"."

 "."$"."pop_host = \"${pop_host}\";
 "."$"."pop_user = \"${pop_user}\";
 "."$"."pop_pass = \"${pop_pass}\";
 "."$"."domain = \"${domain}\";

 "."$"."sql_server = \"${sql_server}\";
 "."$"."sql_user = \"${sql_user}\";
 "."$"."sql_passwort = \"${sql_passwort}\";
 "."$"."sql_db = \"${sql_db}\";

 "."$"."sigwe = \"$sig\";
 "."$"."b1gversion = \"$b1gversion\";
 
 "."$"."speicher = \"$speicher\";
 "."$"."template = \"$template\";
 "."$"."copyright = \"Script &copy; 2002 by B1G.de\";
 
 "."$"."ads = \"1\";
 "."$"."key = \"$key\";

"."$phpout";

$datei = fopen("../config.inc.php", "w");
    fwrite($datei, "$configphp");
fclose($datei);

?>

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
    <td background="table.gif" width="100%"><font face="arial" size="2" color="#ffffff"><b>&nbsp;User-Managment</b></font></td>
  </tr>
  <tr>
    <td bgcolor="#336699" width="100%" align="center"><font face="arial" size="2" color="#ffffff"><br>The signatur have been changed.
<br>
<br>
</font></td>
  </tr>
 </table>
</center>
</body>
</html>
