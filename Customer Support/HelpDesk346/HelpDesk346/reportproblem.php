<?php
	//JaSON fARRELL
	//July 3, 2005
	//Revision 2
	//Rev 2: reportproblem becomes standalone, no longer accessing helpdeskaccess.php - all pertient code has been transferred
	
	session_start();
	include_once "phpSniff.class.php";
	include_once "./config.php";
	include_once "./includes/constants.php";
	
	#var_dump(DB_HOST);
	#var_dump(DB_UNAME);
	#var_dump(DB_PASS);
	#var_dump(DB_DBNAME);
	
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS);
	mysql_select_db(DB_DBNAME);
	include_once "./includes/settings.php";
	include_once "./includes/classes.php";
	
	$t = new Ticket();

	if (isset($_POST['command'])) include_once "./includes/createproblem/process.php";
	if (isset($_SESSION['enduser'])) {
		$user = unserialize($_SESSION['enduser']);
		if ($user->get('securityLevel') == ENDUSER_SECURITY_LEVEL)
			$t->set('regUser', $user->get('id'));
		else 
			$t->set('regUser', 0);
	}
?>
<html>
<head>
	<title>Help Desk Main Page</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name="Designed by Chad Edwards" content="QuickIntranet.com HelpDesk">
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000"  link="#0000FF" alink="#FF0000" vlink="#0000FF">
<table width="75%" height="933" border="0" align="left" cellpadding="0" cellspacing="2">
  <tr> 
    <td height="87" valign="top"><img src="images/help-desk-main.jpg" width="540" height="150"></td>
  </tr>
  <tr> 
  <form action="" method="post" enctype="multipart/form-data">
    <td height="26" valign="top">
    	<p><strong>Welcome to the Information Technology Help Desk page.</strong></p>
    	<a href="index.php">Return to Main Help Desk Page</a><br/>
    	<div align="left">
    	<?php
    		#if (!isset($_SESSION['enduser']))
    			include_once "./includes/upload_form.php";
    	?>
    	</div>
    </td>
  </tr>
  <tr> 
    <td height="233" valign="top"> <div align="center"> 
        <p align="left">Please provide us with the details necessary so that we 
          can quickly diagnose your' technical problem.<br>
          (<strong><font color="#FF0000">Filling in all fields results in faster 
          and higher quality solutions</font></strong>)</p>
          <p align="left">First Name:
            <input name="FirstName" type="text" value="<?php echo isset($user) ? $user->get('FirstName') : ''; ?>" />
            <br>
            Last Name: 
            <input name="LastName" type="text" value="<?php echo isset($user) ? $user->get('LastName') : ''; ?>" />
            <br>
            E Mail Address: 
            <input name="eMail" type="text" value="<?php echo isset($user) ? $user->get('email_addr') : ''; ?>" size="55" />
            <br>
            Phone Number (<em>Optional</em>): 
            <input name="phone" type="text" size="35" value ="<?php echo isset($user) ? $user->get('phoneNumber') : ''; ?>" />
            &nbsp; Ext (<em>Optional</em>) 
            <input name="ext" type="text" size="7" value="" value ="<?php echo isset($user) ? $user->get('phoneExt') : ''; ?>" 
          </p>
          <p align="left">Please select the category<br>
            your problem falls under</p>
          <p align="left"> 
            <select name="PCatagory" size="1">
            <?php
            $sql = "SELECT id  FROM " . DB_PREFIX . "categories";
			$res = mysql_query($sql) or die(mysql_error());
			while ($r = mysql_fetch_assoc($res))
			{
				$c = new Category($r['id']);
				if (isset($_POST['PCatagory']) && $_POST['PCatagory'] == $c->get('id'))
				 	echo '<option value="' . $c->get('id') . '" selected="selected">' . $c->get('name', 'stripslashes') . '</option>' . chr(10);
				else 
				 	echo '<option value="' . $c->get('id') . '">' . $c->get('name', 'stripslashes') . '</option>' . chr(10);	
			}
			?>
            </select>
          </p>
          <?php
          	if ($OBJ->get('user_defined_priorities', 'intval')) {
          ?>
          <div align="left"><b>Priority of the Problem:</b>
          <select name="priority" size="1">
          <?php
          	$q = "select pid from " . DB_PREFIX . "priorities order by severity";
          	$s = mysql_query($q) or die(mysql_error());
          	while ($r = mysql_fetch_assoc($s))
          	{
          		$p = new Priority($r['pid']);
				if (isset($_POST['priority']) && $_POST['priority'] == $p->get('pid'))
				 	echo '<option value="' . $p->get('pid') . '" selected="selected">' . $p->get('name', 'stripslashes') . '</option>' . chr(10);
				else 
				 	echo '<option value="' . $p->get('pid') . '">' . $p->get('name', 'stripslashes') . '</option>' . chr(10);	
          	}
          ?>
          </select></div>
          <br/>
          <?php	
          	}
          ?>
          <div align="left">Please describe the problem you are experiencing:<br>
            <textarea name="describe" cols="60" rows="5" id="describe"></textarea>
            <br>
            <input type="submit" name="command" value="Submit" class="button" /><br/>
            <span style="color:red"><?php echo isset($_error_msg) ? $_error_msg : ''; ?></span>
          </div>
        </form>
        <p><br>
        <p align="center"><font size="2" face="Times New Roman, Times, serif">CopyRight 
          2005 Help Desk Reloaded<br>
          <a href="http://www.helpdeskreloaded.com">Today's Help Desk Software 
          for Tomorrows Problem.</a></font></p>
        <p align="center">&nbsp;</p>
        <p align="center">&nbsp;</p>
        <p align="center">&nbsp;</p>
        <p align="center">&nbsp;</p>
        <p align="center">&nbsp;</p>
        <p align="center">&nbsp;</p>
        <p align="center"><a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a></p>
      </div></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>

</body>
</html>
<?php
	mysql_close();
?>