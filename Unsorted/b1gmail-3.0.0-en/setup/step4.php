<html>
<head>
<title>b1gMail Setup</title>
</head>
<body bgcolor="#ffffff">
<center>
 <table width="100%" height="100%" cellspacing="0" cellpadding="0">
<form action="step5.php" method="post">
  <tr>
   <td width="100%" height="100%" valign="middle" align="center">
    <table cellspacing="1" bgcolor="#000000" cellpadding="0" width="65%">
     <tr>
      <td bgcolor="#003366" background="table.gif" align="center">
       <font face="verdana" size="2" color="#ffffff">
        b1gMail Setup
       </font>
      </td>
     </tr>
     <tr>
      <td bgcolor="#D4D0C8" align="center">
       <table width="100%">
        <tr>
         <td width="150" valign="middle" align="center">
          <img src="mbox.gif" border="0">
         </td>
         <td valign="top" align="center">
           <font face="verdana" size="2" color="#000000">
             <b><u>b1gMail MySQL</u></b>
             <br><br>
             Enter your MySQL data.
<br>
<br>
<input type="hidden" name="config_pop_host" value="<? echo($config_pop_host); ?>">
<input type="hidden" name="config_pop_user" value="<? echo($config_pop_user); ?>">
<input type="hidden" name="config_pop_pass" value="<? echo($config_pop_pass); ?>">
<table>
 <tr>
  <td>MySQL Host:</td>
  <td><input type="text" name="config_sql_server" size="28"></td>
 </tr>
 <tr>
  <td>MySQL User:</td>
  <td><input type="text" name="config_sql_user" size="28"></td>
 </tr>
 <tr>
  <td>MySQL Password:</td>
  <td><input type="text" name="config_sql_passwort" size="28"></td>
 </tr>
 <tr>
  <td>MySQL Database:</td>
  <td><input type="text" name="config_sql_db" size="28"></td>
 </tr>
</table>
           </font>
         </td>
        </tr>
        <tr>
         <td align="center"><font face="verdana" size="1" color="#000000">&copy; 2002 by www.b1g.de</font></td>
         <td align="right"><font face="verdana" size="2" color="#000000"><input type="image" src="next.gif" action="submit"></font></td>
        <tr>
       </table>
      </td>
     </tr>
    </table>
   </td>
  </tr>
 </form>
 </table>
</center>
</body>
</html>