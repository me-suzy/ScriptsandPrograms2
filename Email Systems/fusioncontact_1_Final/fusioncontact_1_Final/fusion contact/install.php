<?
/*
Copyright Information
Script File :  install.php
Creator:  Jose Blanco (snyper)
Version:  1.0
Date Created: Feb. 20 / 2005
Released :  Feb. 27 / 2005
website: http://x-php.com , Shadowphp.net
e-mail: joseblanco.jr@g-mail.com
Aim: xphp snyper , Junior Snyper
please keep this copyright in place. :)
*/
if($sec_inc_code=="081604"){
if($install=="1"){
	   $usern = htmlspecialchars(strtolower($_POST["usern"]));
	   $pass = htmlspecialchars($_POST["pass"]);
   	   $permission ="2";
	    $string ='<?php die("You may not access this file"); ?>'."\n";
	   $string .=  $usern. "|>". md5($pass). "|>". $permission."\n";
	   $fileu = file("./inc/users.db.php") or die("Problem getting the user details flat-file "); 
       $fpu = fopen("./inc/users.db.php", "w");
	   fwrite($fpu,$string);
	   fclose($fpu);
	 $cont.=  <<<html
 <table width="400" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#B5B59C">
  <tr>
    <td bgcolor="#FFFFCC"><div align="center"><font color="#0000FF" size="1" face="Verdana, Arial, Helvetica, sans-serif">
        Admin Account successfully Created.<br> <strong><a href="?admin=del">click here to login</a></strong></font></div></td>
  </tr>
</table>

html;

	}else{
			 $cont.=  <<<html
			 <center>welcome to Fusion contact, The only thing to do is create the admin account,<br>
			  lets do that and then you may login.</center>
		<form action="?install=1" method="post">
<table width="268" border="1" style="border-style: dashed; border-collapse:collapse" bordercolor="#000000">
    <tr> 
      <td colspan="2" align="center" ><font size="1" face="Verdana">&nbsp;</font><font size="1" face="Verdana"><B>Create 
        Admin Account</B></font></td>
    </tr>
    <tr> 
      <td width="21%" align="center"><font size="1" face="Verdana">username</font></td>
      <td width="79%" align="center"><font size="1" face="Verdana"> 
        <input name="usern" type="text"  >
        </font> </td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">password</font></td>
      <td align="center"><input name="pass" type="text"  > </td>
    </tr>
    <tr> 
      <td height="27" colspan="2" align="center"><input name="submit"  type="submit" value="Submit"> 
      </td>
    </tr>
  </table>
</form>
html;
}
		}// Ends Securtiy Check now lets display error msg
	else die(" You can not run this file from here");
	?>