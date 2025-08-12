 <?
 /*
Copyright Information
Script File :  login.php
Creator:  Jose Blanco (snyper)
Version:  1.0
Date Created: Feb. 20 / 2005
Released :  Feb. 27 / 2005
website: http://x-php.com , Shadowphp.net
e-mail: joseblanco.jr@g-mail.com
Aim: xphp snyper , Junior Snyper
please keep this copyright in place. :)
*/
if($sec_inc_code=="081604"){ // making sure file is not being run by it's self
$linksbr="<a href=\"admin.php\">Login</a>";
$links="<a href=\"admin.php\">Login</a>";
$loginid="Your are not logged in";
$cont.= <<<HTML
 <form method = "post" action="admin.php">
  <table  width="30%" border="0" align="center" cellpadding="1" cellspacing="1" witdh="100%">
    <tr> 
      <td colspan="2"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Admin 
          Cp Login</strong></font></div></td>
    </tr>
    <tr> 
      <td width="9%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">username:</font></td>
      <td width="91%"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type = "text" id = "user3" name = "user"  class = "text" tabindex = "1">
        </font></td>
    </tr>
    <tr> 
      <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">password:</font></td>
      <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type = "password" name = "pass"  class = "text" tabindex = "2">
        </font></td>
    </tr>
    <tr> 
      <td colspan="2"><div align="center"> <font color="#006699" size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input type = "hidden"  name = "do" value="login">
          <input type = "submit"  name = "submit" value="Login">
          <em><br>
          </em></font></div></td>
    </tr>
  </table>
</form>

HTML;
	}// Ends Securtiy Check now lets display error msg
	else die(" You can not run this file from here");
	?>