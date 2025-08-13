<?php

?>
<html>
<head>
<title>IMG2ASCII installation</title>
<style>
.link:hover {
	TEXT-DECORATION: underline
	}
.link {
	font: italic 8pt;
	font-family: verdana;
	TEXT-DECORATION: none;
	color: #000066
}
.texte {
	font: 8pt;
	font-family: verdana;
}
.form {
	font-family: verdana;
	font-size: 10px;
	font-weight: normal;
	color: #16246C;
	text-decoration: none;
	background-color: #FFFFFF;
	border-bottom-color: #666666;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-left-color: #666666;
	border-right-color: #666666;
	border-right-width: 1px;
	border-top-color: #666666;
	border-top-width: 1px;
}

BODY {
	scrollbar-face-color: #CFE3E3;
	scrollbar-shadow-color: #ABD5D5;
	scrollbar-highlight-color: #ABD5D5;
	scrollbar-3dlight-color: #2D7DA7;
	scrollbar-darkshadow-color: #2D7DA7;
	scrollbar-track-color: #CFE3E3;
	scrollbar-arrow-color: #ABD5D5
}
		  
#infobull {
	position: absolute;
	z-index: 1000;
	top: 0px;
	left: 0px;
	width: 200px;
}
DIV.infobullDIV {
	width: 200px;
	padding: 2px;
	background: yellow;
	border: 1px solid black;
	color: black;
	font-family: Arial,Helvetica;
	font-style: Normal;
	font-weight: Normal;
	font-size: 12px;
	line-height: 14px;
}
</style>
</head>
<body bgcolor='#CFE3E3'>
<?php
if (isset($_GET['send']))
{
  $db_server = $_GET['dbhost'];                                 // MySQL Server
  $db_name = $_GET['dbbase'];                                        // Database name
  $db_user = $_GET['dbuser'];                                      // Username
  $db_passwort = $_GET['dbpass'];                               // Password
  $db_create = $_GET['dbcreate'];
  $fehler="";
  $db = @MYSQL_CONNECT($db_server,$db_user,$db_passwort);
  if (!$db)
    $fehler.="There was an error connecting to the database.<br />";
  else
    $db_select = MYSQL_SELECT_DB($db_name);
  if (!$db_select && !$db_create)
    $fehler.="The database you entered was not found.<br />";
  elseif (!$db_select)
  {
    $worked = mysql_query("CREATE DATABASE $db_name");
    // creates db
    if (!$worked)
      $fehler .= "The database could not be created, try to create the database with another tool.<br />";
    else 
      $db_select = MYSQL_SELECT_DB($db_name);
  }
  if (!$fehler)
  {
    $fp = fopen("ascii.sql","r");
    echo "<small>";
    $run = "";
    while ($line = fgets($fp,1000))
    {
      if (substr(trim($line),0,1) != "#" && $line!="")  // line contains a query
      {
        $run.=$line;
        if (substr(trim($run),-1) == ";")
        {
          $worked = mysql_query(($run));            // run query (addslashes()?)
          if (!$worked)
          {
            echo $run." could not be executed! <br />\n";
            $didntwork =1;
          }
          $run="";                                            // empty query for a new one
        }
      }
    }
    echo "</small>";
    fclose($fp);
    if ($didntwork) echo "<br /><br />The SQL-dump could not completely be inserted into your database. Try to install it again, or if this error ";
    else
    {
        $fp = fopen("ascii.php","r");
        $temp=0;
        while ($line = fgets($fp,1000))
        {
          if (substr($line,0,12)=="\$db_server =")
            $temp=1;
          if ($temp == 0)
            $vorher.=$line;
          if ($temp == 1)
            $inhalt.=$line; 
          if ($temp == 2)
            $nachher.=$line;
          if (substr($line,0,14)=="\$db_passwort =")
            $temp=2;
        }
        fclose($fp);  
        $fp2 = fopen ("ascii.php","w");
        $text = "\$db_server = \"".$db_server."\";\r\n\$db_name = \"".$db_name."\";\r\n\$db_user = \"".$db_user."\";\r\n\$db_passwort = \"".$db_passwort."\";\r\n ";
        $writer = fwrite($fp2,$vorher.$text.$nachher);
        fclose($fp2);
        if ($writer)
          echo "<span class=\"texte\"><b>IMG2ASCII has successfully been installed on your system!</b><br /><br />Click <a href=\"ascii.php\" class=\"link\">here</a> to run IMG2ASCII.</span>";
    }
  }
}
if (!isset($_GET['send']) || $fehler)
{
  ?>
  <center>
<br><br>
<table width=620 cellpadding=0 cellspacing=0 align=center>
<col width=1 bgcolor='#2D7DA7'>
<col width=600>
<col width=1 bgcolor='#2D7DA7'>
	<tr>
		<td></td>
		<td bgcolor='#2D7DA7' align='center' style='font: bold 14px; font-family: verdana;'>IMG2ASCII installation</td>
		<td></td>
	</tr>
	<tr>
		<td></td>
	<td bgcolor='#ABD5D5' align=left class=texte>
				<br>
<form enctype="multipart/form-data" action="install.php" method="GET">
<table border=0 align=center>
	<col>
	<col align=left>
	<tr>
		<td colspan=2 align=center style='font: bold 9pt; font-family: verdana;'>Connection settings to the MySQL database<br><?php 
if ($fehler) echo "<font color=\"#ff0000\">".$fehler."</font>";?><br></td>
	</tr>
	<tr>
		<td class=texte>Server Address</td>
		<td><INPUT TYPE='TEXT' NAME='dbhost' SIZE='30' VALUE='<?php if (isset($db_server)) echo $db_server; else echo "localhost";?>' class=form></td>
	</tr>
	<tr>
		<td class=texte>Database name</td>
		<td><INPUT TYPE='TEXT' NAME='dbbase' SIZE='30' VALUE='<?php if (isset($db_name)) echo $db_name;?>' class=form></td>
	</tr>
  <tr>
		<td class=texte>Create database<br />(if not already created)</td>
		<td><INPUT TYPE='checkbox' NAME='dbcreate' VALUE='1'<?php if (isset($db_create)) echo " checked";?>></td>
	</tr>
	<tr>
		<td class=texte>Username</td>
		<td><INPUT TYPE='TEXT' NAME='dbuser' SIZE='30' VALUE='<?php if (isset($db_user)) echo $db_user;?>' class=form></td>
	</tr>
	<tr>
		<td class=texte>Password</td>
		<td><INPUT TYPE='Password' NAME='dbpass' SIZE='30' VALUE='<?php if (isset($db_password)) echo $db_password;?>' class=form></td>
	</tr>
</table>

<br>
<center>
<br><br>
<input type='submit' value=' Install ' class=form name="send"></center>
</form>
<br><br>

	</td>
	<td></td>
</tr>
<tr>
	<td height=1 colspan=3></td>
</tr>
</table>
<br>
<a href='https://sourceforge.net/projects/img2ascii/' class=link>© 2003 Ueli Weiss</a>
</center>
</body>
</html>
  <?php
}
?>