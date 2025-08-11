<?php
//	-----------------------------------------
// 	$File: index.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-04-10
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------
		
	// Check if cfg.php exists, if not go to install.php
	if (!file_exists('cfg.php'))
	{
		header("Location: install.php");
	}
	require ('cfg.php'); 
	if (isset($_POST['login']))
	{
        if(!$_POST['user'])
        {
          Header ("Location: index.php?error=denied");
        }

		$sql = "SELECT id, username, password FROM " .$db_prefix. "users 
				WHERE username = '".$_POST['user']."' 
				LIMIT 1";
		$sql = $db->query($sql) or exit('An error occured while retreiving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		($read = $db->fetch_array($sql));
				
		if(md5($_POST['pass']) == $read['password'] && $_POST['user'] == $read['username'])
		{
		  $cookiesum = md5($read['password'] && $read['username']);
		  $user_id = $read['id'];
		  $sql = "INSERT INTO " .$db_prefix. "online 
				(user_id,cookiesum) VALUES ('$user_id','$cookiesum')";
		   $sql = $db->query($sql) or exit('An error occured while retreiving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
			setcookie('catcookie',''.$cookiesum.'',time()+10000);
			Header ("Location: default.php");
		}
		else
		{ 	
			Header ("Location: index.php?error=wrong");
		}
	}
	// top.inc
    require ('_inc/top_1.inc.php');
    // Content

?>
<table style="width: 100%; height: 100%; border: 0px;">
	   <tr>
	   	   <td valign="middle" align="center">
			<div style="width: 300px; height: 150px; background-color: #F5F1CF; border: 1px solid #BEB882; font-family: arial; font-size: 11px; -moz-border-radius: 4px;"><img src="images/users.gif" style="margin: 8px;" align="right"/>
				 <div align="left" style="margin: 15px;">
				 <form method="POST" action="">
				 	  <strong><?php echo $lang_username; ?>:</strong><br/> <input style="font-size: 10px; font-family: arial; margin: 3px;" type="text" name="user" /><br/>
				 	  <strong><?php echo $lang_password; ?>:</strong><br/> <input style="font-size: 10px; font-family: arial; margin: 3px;" type="password" name="pass" /><br/>
				 	  <input type="submit" class="button" name="login" value="<?php echo $lang_login; ?>">
				 </form>
				 <?php
				 	  if(!EMPTY($_GET['error']))
				 	  {
				 	   switch($_GET['error'])
				 	   {
				 	   	case 'denied':
				 	   	
				?>
				<div style="border: 1px dashed #BEB882; background-color: #FCFAE6;">
					 <img src="images/errormessage.gif" /> <strong>Access denied!</strong
				</div>
				<?php
					  	break;
					  	case 'wrong':
				?>
				<div style="border: 1px dashed #BEB882; background-color: #FCFAE6;">
					 <img src="images/errormessage.gif" /> <strong>Wrong password or username!</strong
				</div>
				<?php
				break;
				 	   }
				 	  }
				?>
				 </div>
			</div>
			</td>
	   </tr>
</table>
</body>
</html>
