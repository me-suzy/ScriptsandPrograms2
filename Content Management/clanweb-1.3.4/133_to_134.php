<html>
<head>
	<title>ClanAdmin Tools 1.3.3, 1.3.4prX -> ClanWeb 1.3.4 Update wizard</title>
</head>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<body>
<?php 
//	-----------------------------------------
// 	$File: 133_to_134.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-05-05
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------
//  CAT INSTALLER 0.4
// 	By ClanAdmin Tools 2005
//
//	SCRIPT VERSION
//	--------------
	$update_to_script = '1.3.4';
//  INSTALL VERSION - The version of the installer.
//	--------------
	$installer_ver = '0.4';
// 	CANCEL TEXT - Controls the cancel button.
//	--------------
	$cancel = "<input class=\"text\" onclick=\"if(confirm('Abort setup? Click yes to close window. Cancel to continue. ')) location='http://www.clanadmintools.com/'\" type=\"reset\" name=\"cancel\" value=\"Cancel\">";
//
//
// 	Check if cfg.php exists, if not DIE.
if(!file_exists('cfg.php'))
{
		$error = 'The config file couldn\'t be detected. Please make sure that the update file is placed in the root
		directory.';
}else{
	  require('cfg.php');
}

?>
<table style="width: 100%; height: 100%; text-align: center; border: 0px;">
	<tr>
	   	<td valign="middle" align="center">		   	   
      	   	<table style="width: 550px; height: 410px; background-color: #f1f1f1; border: 1px solid #C0C0C0;" class="text">
        	   	<tr> 
					<td style="width: 120px;" rowspan="2"><img src="gfx/install.png" /></td>
          			<td style="height: 370px; text-align: center; background-color: white; border-bottom: 1px solid #c0c0c0;" valign="top">
<?php
// When user hit update button, start altering tables.
// Also print which table that have been altered without error.		
if(isset($_POST['update']))
{
 		echo"<div style=\"text-align: left;\">";
		 $db->query("ALTER TABLE `".$db_prefix."news` CHANGE `newspost` `newspost` TEXT NOT NULL") or exit('An error occured while updating DB.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		 echo"ALTER TABLE `".$db_prefix."news` CHANGE `newspost` `newspost` TEXT NOT NULL <strong style=\"color: green;\">PASSED</strong>";
		 echo"<br/>";
		 $db->query("ALTER TABLE `".$db_prefix."news` CHANGE `name` `nickname` VARCHAR( 25 ) NOT NULL") or exit('An error occured while updating DB.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		 echo"ALTER TABLE `".$db_prefix."news` CHANGE `name` `nickname` VARCHAR( 25 ) NOT NULL <strong style=\"color: green;\">PASSED</strong>";
		 echo"<br/>";
		 $db->query("ALTER TABLE `".$db_prefix."game` CHANGE `report` `report` TEXT NOT NULL ") or exit('An error occured while updating DB.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		 echo"ALTER TABLE `".$db_prefix."game` CHANGE `report` `report` TEXT NOT NULL <strong style=\"color: green;\">PASSED</strong>";
		 echo"<br/>";
		 $db->query("ALTER TABLE `".$db_prefix."script` CHANGE `version` `version` VARCHAR( 10 ) NOT NULL") or exit('An error occured while updating DB.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		 echo"ALTER TABLE `".$db_prefix."script` CHANGE `version` `version` VARCHAR( 10 ) NOT NULL <strong style=\"color: green;\">PASSED</strong>";
		 echo"<br/>";
		 $db->query("ALTER TABLE `".$db_prefix."script` CHANGE `installdate` `installdate` VARCHAR( 15 ) NOT NULL") or exit('An error occured while updating DB.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		 echo"ALTER TABLE `".$db_prefix."script` CHANGE `installdate` `installdate` VARCHAR( 15 ) NOT NULL <strong style=\"color: green;\">PASSED</strong>";
		 echo"<br/>";
		 $db->query("ALTER TABLE `".$db_prefix."motd` CHANGE `motd` `motd` TEXT NOT NULL") or exit('An error occured while updating DB.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		 echo"ALTER TABLE `".$db_prefix."motd` CHANGE `motd` `motd` TEXT NOT NULL <strong style=\"color: green;\">PASSED</strong>";
		 echo"<br/>";
		 $db->query("ALTER TABLE `".$db_prefix."comments` CHANGE `comment` `comment` TEXT NOT NULL") or exit('An error occured while updating DB.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		 echo"ALTER TABLE `".$db_prefix."comments` CHANGE `comment` `comment` TEXT NOT NULL <strong style=\"color: green;\">PASSED</strong>";
		 echo"<br/>";
		 $db->query("ALTER TABLE `".$db_prefix."comments` CHANGE `ip` `ip` VARCHAR( 15 ) NOT NULL") or exit('An error occured while updating DB.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		 echo"ALTER TABLE `".$db_prefix."comments` CHANGE `ip` `ip` VARCHAR( 15 ) NOT NULL <strong style=\"color: green;\">PASSED</strong>";
		 echo"<br/>";
		 $db->query("ALTER TABLE `".$db_prefix."reported` CHANGE `comment` `comment` TEXT NOT NULL") or exit('An error occured while updating DB.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		 echo"ALTER TABLE `".$db_prefix."reported` CHANGE `comment` `comment` TEXT NOT NULL <strong style=\"color: green;\">PASSED</strong>";
		 echo"<br/>";
		 $db->query("ALTER TABLE `".$db_prefix."reported` CHANGE `ip` `ip` VARCHAR( 15 ) NOT NULL") or exit('An error occured while updating DB.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		 echo"ALTER TABLE `".$db_prefix."reported` CHANGE `ip` `ip` VARCHAR( 15 ) NOT NULL <strong style=\"color: green;\">PASSED</strong>";
		 echo"<br/>";
		 $db->query("ALTER TABLE `".$db_prefix."users` CHANGE `username` `username` VARCHAR( 25 ) NOT NULL") or exit('An error occured while updating DB.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		 echo"ALTER TABLE `".$db_prefix."users` CHANGE `username` `username` VARCHAR( 25 ) NOT NULL <strong style=\"color: green;\">PASSED</strong>";
		 echo"<br/>";
		 $db->query("ALTER TABLE `".$db_prefix."crew` RENAME `".$db_prefix."members`") or exit('An error occured while updating DB.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		 echo"ALTER TABLE `".$db_prefix."crew` RENAME `".$db_prefix."members` <strong style=\"color: green;\">PASSED</strong>";
		 echo"<br/>";
		 $db->query("ALTER TABLE `".$db_prefix."members` CHANGE `nick` `nickname` VARCHAR( 25 ) NOT NULL") or exit('An error occured while updating DB.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		 echo"ALTER TABLE `".$db_prefix."members` CHANGE `nick` `nickname` VARCHAR( 25 ) NOT NULL <strong style=\"color: green;\">PASSED</strong>";
		 echo"<br/>";
		 
		 $sql = 'CREATE TABLE '.$db_prefix.'spons (
                  id int(3) NOT NULL auto_increment,
                  spons_cat int(3) NOT NULL default 0,
                  spons_name varchar(255) NOT NULL,
                  spons_info text NOT NULL,
                  PRIMARY KEY (id)
                );';
    
    	$db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'spons <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		echo"CREATE TABLE `".$db_prefix."spons`	<strong style=\"color: green;\">PASSED</strong>";	

    	$sql = 'CREATE TABLE '.$db_prefix.'spons_cat (
                  spons_cat int(3) NOT NULL auto_increment,
                  spons_type varchar(255) NOT NULL,
                  PRIMARY KEY (spons_cat)
                );';
    
    	$db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'spons_cat <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		echo"CREATE TABLE `".$db_prefix."spons_cat`	<strong style=\"color: green;\">PASSED</strong>";
		
		$sql = 'CREATE TABLE '.$db_prefix.'online (
              user_id int(3) unsigned NOT NULL default 0,
              cookiesum varchar(255) NOT NULL default '',
              KEY user_id_online(user_id)
            ) TYPE=HEAP;';
            
        $db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'online <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		echo"CREATE TABLE `".$db_prefix."online`	<strong style=\"color: green;\">PASSED</strong>";
		
		
		 $db->query("UPDATE `".$db_prefix."script` SET `version` = '".$update_to_script."'")or die($db->error());
		 echo"</div>";
		 echo"<h3>Update finished without errors.</h3>";
		 echo"</td>
                            </tr>
                            <tr>
                              <td style=\"height: 30px; text-align: right;\"><span class=\"copytext\">CAT installer $installer_ver by ClanAdmin Tools 2005</span>
                              <form name=\"step_one\" method=\"post\" action=\"index.php\">
                    				<input class=\"text\" type=\"submit\" value=\"Finish >\" />
                    			</form>
                    		  </td>
                            </tr>
                          </table>                    		   
                    		   </td>
                    	   </tr>
                    </table>";
}
else
{
 if(!empty($error))
 {
  		echo"<h3>Welcome to the ClanWeb v1.3.4 update wizard</h3>";
  		echo"$error";
  		echo"</td>
                            </tr>
                            <tr>
                              <td style=\"height: 30px; text-align: right;\"><span class=\"copytext\">CAT installer $installer_ver by ClanAdmin Tools 2005</span>
                              
                    		  </td>
                            </tr>
                          </table>                    		   
                    		   </td>
                    	   </tr>
                    </table>";
                    exit;
 }
		 echo"<h3>Welcome to the ClanWeb v1.3.4 update wizard</h3>
                		  	  This wizard will help you update from CAT 1.3.3 & 1.3.4pr1 to CW 1.3.4
                		  	  <br/><br/>
                		  	  DBprefix: $db_prefix <br/>
                		  	  DBname: $dbname <br/>
							  DBuser: $dbuser <br/>
							  DBpass: $dbpw <br/>
							  DBhost: $dbhost <br/><br/>
							  <strong>CFG.php data</strong>:<br/>
                		  	  If this information is correct then click on 'Update' to run update sequence.
                			  </td>
                            </tr>
                            <tr>
                              <td style=\"height: 30px; text-align: right;\"><span class=\"copytext\">CAT installer $installer_ver by ClanAdmin Tools 2005</span>
                              <form name=\"update\" method=\"post\" action=\"\">
                    				<input class=\"text\" type=\"submit\" name=\"update\" value=\"Update\" /> $cancel
                    			</form>
                    		  </td>
                            </tr>
                          </table>                    		   
                    		   </td>
                    	   </tr>
                    </table>";
            			
}                
?>
</body>
</html>