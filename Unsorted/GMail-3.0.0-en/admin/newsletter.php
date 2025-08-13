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
    <td bgcolor="#336699" width="100%" align="center"><font face="arial" size="2" color="#ffffff"><br>Here you can write a newsletter to all the users of your Webmail Service.
<br><br>
<table><form action="sendletter.php" method="post"><tr><td valign="top"><font face="arial" size="2" color="#ffffff">Subject:</font></td><td><input type="text" size="32" name="betreff"></td></tr>
<tr><td valign="top"><font face="arial" size="2" color="#ffffff">Message:</font></td><td><textarea style="width:320;height:240" name="text"></textarea></td></tr>
<tr><td valign="top"><font face="arial" size="2" color="#ffffff">&nbsp;</font></td><td><input type="submit" value="Submit"></textarea></td></tr></form></table>
<br><br>
</font></td>
  </tr>
 </table>
</center>
</body>
</html>
