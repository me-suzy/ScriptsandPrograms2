<?
/*
Copyright Information
Script File :  sources/admin.php
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
//check if this script has to be installed
if(file_exists("./install.php")==true){
  include("./install.php")  ;
 }else{
  //check if user is looged in
    global $loginid,$links,$linksbr,$title, $editlink ,  $dellink;
$title = "Fusion Contact ";
if(!$_COOKIE['fc_user']) {
 include("./sources/login.php");
} elseif(@$_COOKIE['fc_user']){
if($_COOKIE["fc_permission"]==2){
$level="Super Admin";
$linksbr=<<<html
<a href="?admin=email">&middot;Edit E-mails</a><br>
<a href="?admin=subject">&middot;Edit Subjects </a><br>
<a href="?admin=forms">&middot;Edit Forms</a><br>
<a href="?admin=template">&middot;Edit Templates</a><br>
<a href="?admin=help">&middot;Help/Update</a><br>
<a href="?admin=option">&middot;Options</a><br>
<a href="?admin=logout">&middot;Logout</a> 
html;
$links=<<<html
[ <a href="?admin=email">Edit E-mails</a> |
<a href="?admin=subject">Edit Subjects </a> |
<a href="?admin=forms">Edit Forms</a> |
<a href="?admin=template">Edit Templates</a> |
<a href="?admin=help">Help/Update</a> |
<a href="?admin=option">Options</a> | 
<a href="?admin=logout">Logout</a> ]
html;
   }
$loginid= "Logged in as: ".$_COOKIE['fc_user']."[".$level."]"; 

if(!$_GET["admin"]){
$cont= <<<HTML
welcome back <br>
please select an option<p>
<table width="68" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="132" nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="?admin=email">&middot;Edit 
      E-mails</a><br>
      <a href="?admin=subject">&middot;Edit Subjects </a><br>
      <a href="?admin=forms">&middot;Edit Forms</a><br>
      <a href="?admin=template">&middot;Edit Templates</a><br>
      <a href="?admin=help">&middot;Help/Update</a><br>
      <a href="?admin=option">&middot;Options</a><br>
      <a href="?admin=logout">&middot;Logout</a> </font></td>
  </tr>
</table>




HTML;
}
elseif ($_GET["admin"]=="email" ){
include("./sources/editemail.php");
}
elseif ($_GET["admin"]=="subject" ){
include("./sources/editsubject.php");
}
elseif ($_GET["admin"]=="forms" ){
include("./sources/editform.php");
}
elseif ($_GET["admin"]=="template" ){
include("./sources/edittemp.php");
}
elseif ($_GET["admin"]=="help" ){
include("./sources/help.php");
}
elseif ($_GET["admin"]=="option" ){
include("./sources/options.php");
}

	  } 
  
  
  // ok now that the user is logged in lets just add the logged out msg
//----Logged Out OK!



 if($_GET["admin"]=="out"){
$cont= <<<HTML
 <table width="400" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#B5B59C">
  <tr>
    <td bgcolor="#FFFFCC"><div align="center"><font color="#0000FF" size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <center>You Have Been Logged out <br> <a href="admin.php">Click here to login again </a></center>
	   </font></div></td>
  </tr>
</table>

HTML;
	}

  
  
  
  
	} //ends the else for if install.php is not found
	}// Ends Securtiy Check now lets display error msg
	else die(" You can not run this file from here");
	?>