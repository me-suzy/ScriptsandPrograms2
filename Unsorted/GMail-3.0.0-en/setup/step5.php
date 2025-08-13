<html>
<head>
<title>b1gMail Setup</title>
</head>
<body bgcolor="#ffffff">
<center>
 <table width="100%" height="100%" cellspacing="0" cellpadding="0">
<form action="step6.php" method="post">
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
             <b><u>b1gMail Other</u></b>
             <br><br>
             Fill in the following fields.
<br>
<br>
<input type="hidden" name="config_pop_host" value="<? echo($config_pop_host); ?>">
<input type="hidden" name="config_pop_user" value="<? echo($config_pop_user); ?>">
<input type="hidden" name="config_pop_pass" value="<? echo($config_pop_pass); ?>">

<input type="hidden" name="config_sql_server" value="<? echo($config_sql_server); ?>">
<input type="hidden" name="config_sql_user" value="<? echo($config_sql_user); ?>">
<input type="hidden" name="config_sql_passwort" value="<? echo($config_sql_passwort); ?>">
<input type="hidden" name="config_sql_db" value="<? echo($config_sql_db); ?>">
<table>
 <tr>
  <td>Your Domain(s) (seperate more then one with a ":"):</td>
  <td><input type="text" name="config_domain" size="28" value="domain.com"></td>
 </tr>
 <tr>
  <td>Quota per Mailbox (MB):</td>
  <td><input type="text" name="config_speicher" size="28" value="5"></td>
 </tr>
 <tr>
  <td>Password for the Admin:</td>
  <td><input type="text" name="apass" size="28" value=""></td>
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
</