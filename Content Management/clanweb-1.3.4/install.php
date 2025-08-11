<html>
<head>
	<title>ClanWeb 1.3.4 Setup wizard</title>	
<script type="text/javascript" src="js/openwindow.js"></script>
</head>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<body>
<?php 
//	-----------------------------------------
// 	$File: install.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-05-05
// 	$email: info@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------
//  CAT INSTALLER 0.4
// 	By ClanAdmin Tools 2005
//
//	SCRIPT VERSION - What version are we to install?
//	--------------
	$script = '1.3.4';
//  INSTALL VERSION - The version of the installer.
//	--------------
	$installer_ver = '0.4';
// 	CANCEL TEXT - Controls the cancel button.
//	--------------
	$cancel = "<input class=\"text\" onclick=\"if(confirm('Abort setup? Click yes to close window. Cancel to continue. ')) location='http://www.clanadmintools.com/'\" type=\"reset\" name=\"cancel\" value=\"Cancel\">";
//
//
// 	Check if cfg.php already exists
if (!file_exists('cfg.php'))
	exit('The file \'cfg.php\' already exists. You have probably already installed a version of ClanWeb.  
		  Click <a href="index.php">here</a> instead.');

?>
<table style="width: 100%; height: 100%; text-align: center; border: 0px;">
	<tr>
	   	<td valign="middle" align="center">		   	   
      	   	<table style="width: 550px; height: 410px; background-color: #f1f1f1; border: 1px solid #C0C0C0;" class="text">
        	   	<tr> 
					<td style="width: 120px;" rowspan="2"><img src="gfx/install.png" /></td>
          			<td style="height: 370px; text-align: center; background-color: white; border-bottom: 1px solid #c0c0c0;" valign="top">
<?php
// We're doing it all backwards in this file.
// Step one of the setup can be found at the end.
// The setup procedure has been commented from the beginning to the end.
// 
// Step 5 and final step in the setup process
// Let's round things up and install the database and create the 
// info to the config file. 
                 		if(isset($_POST['step_5']))
                		{
                			// Check if username and password is valid to the requirements
                    		if(strlen($_POST['username']) < 2)
                    		 exit('Your username is shorter then 2 characters. <a href=\'javascript:back(-1)\'>Back</a>');
                    		if(strlen($_POST['username']) > 20)
                    			exit('Your username is longer then 20 characters. <a href=\'javascript:back(-1)\'>Back</a>');
                    		if(strlen($_POST['password']) < 4)
                    			exit('Your password is shorter then 4 characters. <a href=\'javascript:back(-1)\'>Back</a>');
                    		if(strlen($_POST['password']) > 16)
                    			exit('Your password is longer then 16 characters. <a href=\'javascript:back(-1)\'>Back</a>');
                    		
                    		$username 	= $_POST['username'];
                    		$password 	= md5($_POST['password']); // convert the password into a md5 sum
                    		$db_prefix 	= $_POST['db_prefix'];
                    		$dbhost 	= $_POST['dbhost'];
                    		$dbname 	= $_POST['dbname'];
                    		$dbuser 	= $_POST['dbuser'];
                    		$dbpw 		= $_POST['dbpw'];
                    		$lang_dir 	= "lang/en"; // The language directory
                    		$lang_file 	= "lang_main.php"; // the language file
                    		
                    		// Require db file, todays date and start creating tables
                    		$date = date('y-m-d');
                    		
    						require('db/mysql.php');
                    		require('install/mysql_db.php');
                    		
                      		// Generate the config information and put it into $config  
        					$config = '<?php'
							."\n\n".
							'$db_prefix = \''.$db_prefix."';\n".
							'$dbname = \''.$dbname."';\n".
							'$dbuser = \''.$dbuser."';\n".
							'$dbpw = \''.$dbpw."';\n".
							'$dbhost = \''.$dbhost."';\n".
							'$lang = \''.$lang_dir."';\n".
							'$lang_file = \''.$lang_file."';\n\n".
							'$script = \''.$script."';\n\n".
							'require("$lang/$lang_file");'."\n".
							'require (\'db/mysql.php\');'."\n". '?'.">";
        
                			// Lets present the config data for the user in a textbox. 
                			// Also print a finish box that will take the user to index.php
							?>
                			<h3>Installation process</h3>
                		  	  Installing application...<br/>
                		  	  Please wait...<br/>
                		  	  <br/>
                		  	  Paste this text into your own cfg.php <br/>
                		  	  <textarea cols="80" rows="15" class="textfelt"><?php echo htmlspecialchars($config) ?></textarea>
                			  <br/>
                		  	  Click on 'Finish' to finish setup.
        			</td>
    			</tr>
                <tr>
                    <td style="height: 30px; text-align: right;">
                        <form name="step_one" method="post" action="index.php">
                			<input class="text" type="submit" name=\"new\" value="Finish" />
                		</form>
                	</td>
                </tr>
            </table>                		   
        </td>
    </tr>
</table>
<?php
	 	 	 			 exit;
            			}
            			// Step 4 in the setup process
            			// Let's fill in all required data to the database
						// so that we later on can create the config file.           			
            			if(isset($_POST['step_4']))
            			{
?>
   <h3>Setup data</h3>
	Please fill in all data required
	<form name="step_5" method="post" action="">
		<table style="width: 70%; font-size: 11px; font-family: verdana; border: 1px solid #789DC0;" align="center" cellspacing="1" cellpadding="4">
  			<tr> 
    			<td style="width: 180px; white-space: nowrap; background-color: #cccccc;"><strong>Database server hostname</strong>(<a href="#" onclick="MM_openBrWindow('install/install_help.php?id=1','help','status=no,scrollbars=no,width=400,height=60')">?</a>)</td>
    			<td style="background-color: #f1f1f1;">      
      				<input type="text" name="dbhost" size="30" maxlength="100" class="textfelt" value="localhost" />
    			</td>
  			</tr>
  			<tr> 
    			<td style="width: 180px; white-space: nowrap; background-color: #cccccc;"><strong>Database name</strong>(<a href="#" onclick="MM_openBrWindow('install/install_help.php?id=2','help','status=no,scrollbars=no,width=400,height=60')">?</a>)</td>
    			<td style="background-color: #f1f1f1;">        
    				<input type="text" name="dbname" size="30" maxlength="50" class="textfelt" />
    			</td>
  			</tr>
  			<tr> 
    			<td style="width: 180px; white-space: nowrap; background-color: #cccccc;"><strong>Database username</strong>(<a href="#" onclick="MM_openBrWindow('install/install_help.php?id=3','help','status=no,scrollbars=no,width=400,height=60')">?</a>)</td>
    			<td style="background-color: #f1f1f1;">       
      				<input type="text" name="dbuser" size="30" maxlength="50" class="textfelt" />
    			</td>
  			</tr>
  			<tr> 
    			<td style="width: 180px; white-space: nowrap; background-color: #cccccc;"><strong>Database password</strong>(<a href="#" onclick="MM_openBrWindow('install/install_help.php?id=4','help','status=no,scrollbars=no,width=400,height=60')">?</a>)</td>
    			<td style="background-color: #f1f1f1;">        
      				<input type="text" name="dbpw" size="30" maxlength="50" class="textfelt" />
    			</td>
  			</tr>
  			<tr> 
    			<td style="background-color: #cccccc; width: 180px; white-space: nowrap"><strong>Table prefix</strong>(<a href="#" onclick="MM_openBrWindow('install/install_help.php?id=5','help','status=no,scrollbars=no,width=400,height=60')">?</a>)</td>
    			<td style="background-color: #f1f1f1;"> 
      				<input type="text" name="db_prefix" size="20" maxlength="30" class="textfelt" value="cat_"/>
    			</td>
  			</tr>
  			<tr> 
    			<td style="background-color: #cccccc; width: 180px; white-space: nowrap"><strong>Admin username</strong>(<a href="#" onclick="MM_openBrWindow('install/install_help.php?id=6','help','status=no,scrollbars=no,width=400,height=60')">?</a>)</td>
    			<td style="background-color: #f1f1f1;">       
      				<input type="text" name="username" size="25" maxlength="25" class="textfelt" value="admin"/>
    			</td>
  			</tr>
  			<tr> 
    			<td style="background-color: #cccccc; width: 180px; white-space: nowrap"><strong>Admin password</strong>(<a href="#" onclick="MM_openBrWindow('install/install_help.php?id=7','help','status=no,scrollbars=no,width=400,height=60')">?</a>)</td>
    			<td style="background-color: #f1f1f1;"> 
      				<input type="text" name="password" size="16" maxlength="16" class="textfelt" value="admin"/>
      			</td>
  			</tr>
		</table>
		  	  Click on 'Install' to install ClanWeb 1.3.4.
		</td>
    </tr>
    <tr>
        <td style="height: 30px; text-align: right;">      
			<input class="text" type="submit" name="step_5" value="Install" /> <?php echo $cancel ?>
		  
		</td>
    </tr>
</table>
	</form>	   
		</td>
	</tr>
</table>
<?php
            		exit;
						}
            			// Step 3 in the setup process
            			// Time to check the PHP and MySQL versions.
						// If they don't meet the demands the setup will stop.           			
            			if(isset($_POST['step_3']))
            			{
            			 echo"<h3>Software</h3>
                		  	  Checking software requirements...
                		  	  <br/><br/>
                		  	  ";
                		  	  if (intval(str_replace('.', '', phpversion())) < 405)
							  {
							  	 echo"PHP version: <font style=\"color: red\">".phpversion()."</font><br/> 
								 ClanWeb requires at least PHP 4.0.5 to run properly. You must upgrade your PHP installation 
								 before you can continue.<br/>";
							  	 $phpcheck = '0';
							  }
							  else
							  {
							  	 echo"PHP version: <font style=\"color: green\">".phpversion()."</font><br/>";
							  	 $phpcheck = '1';
                		  	  }
                		  	  if (intval(str_replace('.', '', mysql_get_server_info())) < 32332)
							  {							
							   	echo"MySQL version: <font style=\"color: red\">".mysql_get_server_info()."</font><br/> ClanWeb requires atleast 3.23.32 to run.<br/>";	
							  	$mysqlcheck = '0';
							  }
							  else
							  {
								echo"MySQL Version: <font style=\"color: green\">".mysql_get_server_info()."</font><br/>";	
								$mysqlcheck = '1';
							  }
							 echo"<br/>";	
							if($phpcheck && $mysqlcheck == '1')
							{
								echo"<strong style=\"color: green;\">Test Passed</strong><br/>";
                		  		echo"<br/>
                		  	  			  Click on 'Next' to continue.";
                		  	}
                		  	else
                		  	{
							  	echo"<strong style=\"color: red;\">Test failed</strong><br/>";
								echo"The setup couldn't continue because of your server software didn't match the requirements
								to run ClanWeb. Please upgrade your software and try to reinstall the application.";
							}
                			  	echo"</td>
                            </tr>
                            <tr>
                              <td style=\"height: 30px; text-align: right;\">";
                              if($phpcheck && $mysqlcheck == '1')
							  {
                               echo"<form name=\"step_one\" method=\"post\" action=\"\">
                    				<input class=\"text\" type=\"submit\" name=\"step_4\" value=\"Next >\" /> $cancel
                    			</form>";
                    		  }
                    		  echo"
                    		  </td>
                            </tr>
                          </table>                    		   
                    		   </td>
                    	   </tr>
                    </table>";
					exit;                		  	  
            			}
            			// Step 2 in the setup process
            			// The license part           			
            			if(isset($_POST['step_2']))
            			{
            			echo"<h3>License agreement</h3>
                		  	  Please review the license terms before installing ClanWeb 1.3.4
                		  	  <br/><br/>
                		  	  <iframe src=\"license.htm\" style=\"width: 410px; height: 300px; border: 1px;\"></iframe>
                		  	  <br/>
                		  	  Click on 'Next' to continue.";
                		echo"</td>
                            </tr>
                            <tr>
                              <td style=\"height: 30px; text-align: right;\">
                              <form name=\"step_one\" method=\"post\" action=\"\">
                    				<input class=\"text\" type=\"submit\" name=\"step_3\" value=\"Next >\" /> $cancel
                    			</form>
                    		  </td>
                            </tr>
                          </table>                    		   
                    		   </td>
                    	   </tr>
                    </table>";
                    exit;
            			}
            			
            			// Step 1 in the setup process
            			// The welcome screen

            			echo"<h3>Welcome to the ClanWeb v1.3.4 setup wizard</h3>
                		  	  This wizard will guide you through the setup of ClanWeb v1.3.4.
                		  	  <br/><br/>
                		  	  Click on 'Next' to continue.";
                		echo"</td>
                            </tr>
                            <tr>
                              <td style=\"height: 30px; text-align: right;\"><span class=\"copytext\">CAT installer $installer_ver by ClanAdmin Tools 2005</span>
                              <form name=\"step_one\" method=\"post\" action=\"\">
                    				<input class=\"text\" type=\"submit\" name=\"step_2\" value=\"Next >\" /> $cancel
                    			</form>
                    		  </td>
                            </tr>
                          </table>                    		   
                    		   </td>
                    	   </tr>
                    </table>";               
?>
</body>
</html>