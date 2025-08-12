<html>
<head>
<title>DreamHost Installer v2.3</title>
</head>
<body>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif"><b><font size="4" color="#990000">DreamHost 
  Installer Program, v.2.3
  </font></b></font>
<hr width="450" size="1" noshade>
<p align="left"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>Thank 
  you for purchasing DreamHost.</b></font></p>
<p align="left"><b><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Our 
  goal is to streamline the installation proceedure, and to make the setup of 
  DreamHost as fast and painless as humanly possible.<br>
  <br>
  Please follow the instructions below, and this should be the easiest install 
  you have ever completed.</font></b></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif"><b><font size="4" color="#990000"><br>
  <br>
  <font size="3">Database Configuration -&gt;<br>
  <br>
  </font></font></b><font size="4" color="#990000"><font size="2" color="#000000">DreamHost 
  relies very heavily on MySQL for data storage, and some of the configuration 
  information that will be defined in this program will be stored in MySQL. For 
  that very reason, the first part of this installation will be dedicated to the 
  proper configuration of MySQL.<br>
  The first thing we must do is insert the MySQL host, username, password, and 
  database name into the <i>setup.php</i> file. This file can be found in the 
  main DreamHost directory. Open<i> setup.php </i>it with a text editor, and you 
  will see the following information: (Just not color coded as it in this HTML 
  file)</font></font></font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif"><font size="4" color="#990000"><font size="2" color="#666666">$host 
  = &quot;<font color="#990000">localhost</font>&quot;; <br>
  $user = &quot;<font color="#990000">username</font>&quot;; <br>
  $pass = &quot;<font color="#990000">password</font>&quot;; <br>
  $database = &quot;<font color="#990000">dreamhost</font>&quot;;</font></font></font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">Now, 
  you must change the settings in &quot;<font color="#990000">red</font>&quot; 
  to the correct information for your database. Do not edit anything else or you 
  could cause strange error messages.<br>
  <br>
  Usually, the <font color="#666666">$host</font> setting will remain the same 
  (localhost), but if you are setting up the database on another server for security 
  reasons, or MySQL limitations, you will need to enter the remote server's IP 
  address. <br>
  <br>
  The <font color="#666666">$username</font> and <font color="#666666">$password 
  </font>settings are for... well, you should know this one without my help! <br>
  </font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">Now, 
  we must setup the </font><font face="Verdana, Arial, Helvetica, sans-serif"><font size="4" color="#990000"><font size="2" color="#666666">$database<font color="#000000"> 
  </font></font></font></font><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">variable, 
  which is simply the database name you want DreamHost to setup in this file, 
  and then use to store/retrieve data from once setup is complete. If you are 
  on a host that has given your username/password proper access to setup new MySQL 
  databases, the setup program will create the database for you. However, some 
  hosts use programs such as Cpanel which change the name of the database. In 
  this event, you will need to to create the database manually through CPanel, 
  or you can use a nice web admin such as phpMyAdmin. Alternatively, you can telnet 
  to your server and create the database name manually.</font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">That 
  completes the database configuration, and we will test it shortly. However, 
  since you already have the setup.php file open in an editor, you will probably 
  see the following line:<br>
  <br>
  <font color="#666666">$salt = &quot;<font color="#990000">\some\where\secure\salt.php</font>&quot;;</font></font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">What 
  you are looking at now is the path to the <i>salt.php </i>file, which contains 
  the encryption/decryption password that will be used by the RC4 function that 
  encrypts and decrypts credit card numbers. We recommend that you place this 
  file in a directory on your server that requires root access. This way, even 
  if some pathetic hacker gains access to this file, as well as your database, 
  he will still be unable to view the encrypted credit card information. You can 
  even change the name from <i>salt.php</i> to whatever you like. But you must 
  get the path set correctly, or DreamHost will halt and you will recieve an error 
  message. <br>
  <br>
  Well, that completes the modifications you must make to the <i>setup.php</i> 
  file. Close it, and open the <i>salt.php</i> file. You should see the following:<br>
  <br>
  <font color="#666666">$pwd = &quot;<font color="#990000">124578936</font>&quot;; 
  </font></font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">You 
  are now looking at the encryption/decryption code that will be used by our RC4 
  encryption/decryption function. Don't make it to simple or short. It is good 
  to use a combination of Uppercase, Lowercase, Numbers, and Special Characters. 
  (<font color="#990000">@*-?,</font>) Note: Some special characters, such as 
  $%+(){} may cause error messages in some versions of PHP, so if you get 'Illegal 
  syntax' error messages and DreamHost halts, try changing the code to something 
  simple to see if that fixes the problem. Also, once you pick a code, it is final! 
  If you change it after your start using DreamHost, you will not be able to retrieve/view 
  credit card info that was inserted into the database while you used the previous 
  code. So please, pick a code and don't change it.<br>
  </font><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">You 
  can now close the <i>salt.php</i> file, and copy it to wherever you specied 
  it would be in the <i>setup.php</i> file. Then delete it from the DreamHost 
  directory, unless of course this is your path of choice for the file to reside. 
  Of course, this destroys any security benifits that it was designed to provide.<br>
  <br>
  Now, one last file to modify, then you will be almost done! Open the index file 
  in the /dreamhost/admin/ directory. (NOT THE /dreamhost/ DIRECTORY!) You only 
  need to modify one line in this file. Look for this:<br>
  </font></p>
<p><font face="Arial, Helvetica, sans-serif" size="2" color="#666666">$path = 
  &quot;<font color="#990000">/path/to/dreamhost/</font>&quot;;</font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">Change 
  the path to the path to the main directory (where we are now, with the setup.php 
  &amp; functions.php) I will take the liberty to use a little bit of PHP code 
  below to retrieve the current directory. This should be the correct path to 
  use in the setting above, but double check to be sure:<br>
  <br>
  <? 
  $path = $HTTP_SERVER_VARS["SCRIPT_FILENAME"];
  $path = ereg_replace("install.php","",$path);
  $path = ereg_replace("install.php3","",$path);
  $path = eregi_replace("install.php","",$path);
  $path = eregi_replace("install.php3","",$path);
  $url = "http://" . $HTTP_SERVER_VARS["HTTP_HOST"] . "/dreamhost/";
  $email = $HTTP_SERVER_VARS["SERVER_ADMIN"];
 ?>
  </font><font face="Arial, Helvetica, sans-serif" size="2" color="#666666"><b><font color="#990000">Your 
  path appears to be: <font color="#000000"> 
  <? echo $path ?>
  </font></font></b></font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">Okay, 
  now that you have set that path in <i>/admin/setup.php</i>, you can close it 
  out and continue to the final setup steps.</font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><font face="Verdana, Arial, Helvetica, sans-serif"><b><font size="4" color="#990000"><font size="3">Database 
  Installation -&gt;</font></font></b></font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><b>Setup 
  Step 1.<br>
  </b></font><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><br>
  The DreamHost Installer will be be happy to create the database for you, but 
  if for some reason you have already created the database, or you use a system 
  such as CPanel that renames the database, then you will have to skip this section. 
  Just be sure that the database does indeed exsist and that the setup.php file 
  reflects its actual name and username/password settings before continuing to 
  Step 2.</font></p>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><a href="install_1.php" target="_blank"><b>CLICK 
  HERE TO CREATE THE DATABASE</b></a> </font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><br>
  </font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><b>Setup 
  Step 2.<br>
  </b></font><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><br>
  Once the database is created, we must insert the proper tables into it, so we 
  can store/retrieve data to the database. Click the link below to do so now...</font></p>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><a href="install_2.php" target="_blank"><b>CLICK 
  HERE TO CREATE THE TABLES</b></a></font></p>
<p>&nbsp;</p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><b>Setup 
  Step 3.<br>
  </b></font><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><br>
  If steps 1 and 2 were sucessful, take the time to pat yourself on the back! 
  You are almost done.<br>
  All that is left is inserting a vital setup variables into the database, and 
  the setup will be complete!<br>
  </font></p>
<form name="form1" action="install_3.php">
  <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr> 
      <td width="10%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><b>PATH:</b></font></td>
      <td width="90%"> 
        <input type="text" name="path" size="75" maxlength="100" value="<? echo $path ?>">
      </td>
    </tr>
    <tr> 
      <td width="10%">&nbsp;</td>
      <td width="90%"> 
        <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">The 
          above setting is once again the path to the main /dreamhost/ directory.<br>
          The default value should be correct, but please do double check...</font></p>
        <p>&nbsp;</p>
      </td>
    </tr>
    <tr> 
      <td width="10%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><b>URL:</b></font></td>
      <td width="90%"> 
        <input type="text" name="url" size="75" maxlength="100" value="<? echo $url ?>">
      </td>
    </tr>
    <tr> 
      <td width="10%">&nbsp;</td>
      <td width="90%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">The 
        above setting url to the main /dreamhost/ directory. <br>
        </font><br>
      </td>
    </tr>
    <tr> 
      <td width="10%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><b>LOGIN:</b></font></td>
      <td width="90%"> 
        <input type="text" name="login" size="25" maxlength="100" value="login">
      </td>
    </tr>
    <tr> 
      <td width="10%">&nbsp;</td>
      <td width="90%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">The 
        above setting defines the login for the DreamHost admin.</font><br>
        <br>
      </td>
    </tr>
    <tr> 
      <td width="10%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><b>PASSWORD:</b></font></td>
      <td width="90%"> 
        <input type="text" name="password" size="25" maxlength="100" value="admin">
      </td>
    </tr>
    <tr> 
      <td width="10%">&nbsp;</td>
      <td width="90%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">The 
        above setting defines the password for the DreamHost admin.</font><br>
        <br>
      </td>
    </tr>
    <tr> 
      <td width="10%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><b>SUPERUSER:</b></font></td>
      <td width="90%"> 
        <input type="text" name="superuser" size="25" maxlength="100" value="admin">
      </td>
    </tr>
    <tr> 
      <td width="10%">&nbsp;</td>
      <td width="90%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">The 
        above setting defines the superuser password, which is required for certian 
        sections of the DreamHost Admin area.</font><br>
        <br>
      </td>
    </tr>
    <tr> 
      <td width="10%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><b>ADMIN:</b></font></td>
      <td width="90%"> 
        <input type="text" name="email" size="25" maxlength="100" value="<? echo $email ?>">
      </td>
    </tr>
    <tr> 
      <td width="10%">&nbsp;</td>
      <td width="90%">&nbsp;</td>
    </tr>
    <tr> 
      <td width="10%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><b>COMPANY:</b></font></td>
      <td width="90%"> 
        <input type="text" name="company" size="25" maxlength="100" value="Your Company Name">
      </td>
    </tr>
  </table>
  <p> <br>
  </p>
  <p align="center"> 
    <input type="submit" name="Submit" value="&lt; - Click here to setup the configuration -&gt;">
  </p>
</form>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><br>
  </font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif"><b><font size="3" color="#990000">PHP 
  INFORMATION &gt;<br>
  </font></b><font size="3" color="#990000"> <font color="#666666" size="2">The 
  information below is included for your reference. </font></font></font><br>
  <font face="Arial, Helvetica, sans-serif" size="2" color="#666666"><b><font color="#990000"><font color="#000000"> 
  <? echo phpinfo(); ?>
  </font></font></b></font></p>
</body>
</html>