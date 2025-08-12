<?php

ob_start();

//-----------------------------------------------
// Installation of PhpMyComic
//-----------------------------------------------

function draw_steps()
   {
      if (!isset($_POST['setup']) or $_POST['setup'] == '')
	     {
		    $_POST['setup'] = 'check';
		 }

      $steps=array
	     (
		    check => '<li class="list">System Check</li>',
		    database => '<li class="list">Database Options</li>',
		    personal =>	'<li class="list">Personal Options</li>',
		    sysset =>	'<li class="list">System Options</li>',
            complete => '<li class="list">Setup Complete</li>',
	     );

      foreach ($steps as $key => $value)
	     {
		   if ($key == $_POST['setup'])
		      {
	             print '<b>'.$value.'</b>';
			  }
		   else
		      {
			     print $value;
			  }
		 }
   }

// Check if the image and config directory is writeble or not
function fn_check()
   {
      print '<font class="head">PhpMyComic 1.2.0 Installation</font><br />';
      print '<font class="norm">Configurations and Settings Wizard<br /><br />';
      print 'This is an open-source, template driven, web based apllication for collecting and organizing your comics. PhpMyComic is licened under the GPL (General Public License).<br /><br />';
      print '<b>System Check</b>';

	  $fp = @fopen('../config/config.test', 'w');

      if ($fp)
	     {
		    fclose($fp);
			print '<div class="grey">Config directory writeble: <font color="green">O.K.</font></div>';
			unlink ('../config/config.test');
		 }
	  else
	     {
	        print '<div class="grey">Config directory writeble: <font color="red">ERROR</font></div>';
	        $error = 1;
	     }

      $fp = @fopen('../image/image.test', 'w');

      if ($fp)
	     {
		    fclose($fp);
			print '<div class="grey">Image directory writeble: <font color="green">O.K.</font></div>';
			unlink ('../image/image.test');
		 }
	  else
	     {
	        print '<div class="grey">Image directory writeble: <font color="red">ERROR</font></div>';
			$error = 1;
	     }

      $fp = @fopen('../backup/backup.test', 'w');

      if ($fp)
         {
            fclose($fp);
            print '<div class="grey">Backup directory writeble: <font color="green">O.K.</font></div>';
            unlink ('../backup/backup.test');
         }
      else
         {
            print '<div class="grey">Backup directory writeble: <font color="red">ERROR</font></div>';
            $error = 1;
         }

      if ($error == 1)
	     {
	        print '<input type="hidden" name="setup" value="check">';
		 }
	  else
	     {
	        print '<input type="hidden" name="setup" value="database">';
	     }
   }

// Setting up the database values
function fn_database()
   {
      print '<font class="head">Database Options</font><br />';
      print '<font class="norm">Configurations and Settings Wizard<br /><br />';
      print '
	         <font class="norm">Please enter the mysql database information:<br />

             <div class="grey">
             <TABLE border="0" cellspacing="0" cellpadding="0" width="400">
	           <tr height="25"><td width="150"><font class="norm">&nbsp;MySQL Hostname:</font></td><td width="250"><input type="text" name="setup_host" class="admin" value="localhost"></td></tr>
	           <tr height="25"><td width="150"><font class="norm">&nbsp;MySQL Username:</font></td><td width="250"><input type="text" name="setup_user" class="admin" value="root"></td></tr>
	           <tr height="25"><td width="150"><font class="norm">&nbsp;MySQL Password:</font></td><td width="250"><input type="password" name="setup_pass" class="admin" value=""></td></tr>
	           <tr height="25"><td width="150"><font class="norm">&nbsp;MySQL Database:</font></td><td width="250"><input type="text" name="setup_data" class="admin" value=""></td></tr>
	         </table>
             </div>
			 
			 <br />Check this checkbox if you already have a database and do not want to install the new tables in your database.<br />
			
             <div class="grey">
	         <input type="checkbox" name="setup_base" value="1">No tables
             </div>

	         </font>';

      print '<input type="hidden" name="setup" value="personal">';
   }

// Writing the config file and making the DB tables
function fn_database_w()
   {
      $values = '<?php

                 // PhpMyComic System Configurations
	             $sql[\'host\']       = "'.$_POST['setup_host'].'";
	             $sql[\'user\']       = "'.$_POST['setup_user'].'";
	             $sql[\'pass\']       = "'.$_POST['setup_pass'].'";
	             $sql[\'data\']       = "'.$_POST['setup_data'].'";

                 ?>';

      // Open file for writing
      $fp = @fopen("../config/config.php","w") or die("Could not open file");

      // Write the config file
      $numBytes = @fwrite($fp, $values) or die("Could not write to file");

      // Close the config file
      @fclose($fp);

      if($_POST['setup_base'] != "1")
	     {
		    // Open a MySQL connection
			mysql_connect($_POST['setup_host'],$_POST['setup_user'],$_POST['setup_pass']) or die("Unable to connect to SQL server");

            $result = mysql_select_db($_POST['setup_data']) or die("Unable to find DB");

            if(!$result)
               {
                  mysql_query("CREATE DATABASE ".$_POST['setup_data']." ");
                  mysql_select_db($_POST['setup_data']);
               }


            // Create table pmc_comic for all the comics
			mysql_query("CREATE TABLE `pmc_comic` (
			`uid` int(5) NOT NULL auto_increment,
			`title` varchar(10) NOT NULL default '',
			`story` varchar(150) NOT NULL default '',			
			`price` decimal(6,2) NOT NULL default '0.00',
			`user1` varchar(100) NOT NULL default '',
			`user2` varchar(100) NOT NULL default '',
			`image` varchar(150) NOT NULL default '',
			`issue` int(3) NOT NULL default '0',
			`issueltr` varchar(5) NOT NULL default '',
			`volume` int(2) NOT NULL default '0',
			`type` varchar(10) NOT NULL default '',
			`genre` varchar(10) NOT NULL default '',
			`publisher` varchar(10) NOT NULL default '',
			`condition` varchar(10) NOT NULL default '',
			`format` varchar(10) NOT NULL default '',
			`plot` text NOT NULL,
			`value` decimal(6,2) NOT NULL default '0.00',
			`date` datetime NOT NULL default '0000-00-00 00:00:00',
			`variation` varchar(10) NOT NULL default '',
			`part1` varchar(10) NOT NULL default '',
			`part2` varchar(10) NOT NULL default '',
			`language` varchar(100) NOT NULL default '',
			`translator` varchar(100) NOT NULL default '',
            `currency` varchar(10) NOT NULL default '',
            `loan` varchar(5) NOT NULL default '',
            `ebay` varchar(5) NOT NULL default '',
            `ebaylink` varchar(150) NOT NULL default '',
            `pubdate` date NOT NULL default '0000-00-00',
            `qty` int(5) NOT NULL default '1',
            `fav` varchar(5) NOT NULL default '',
			PRIMARY KEY  (`uid`),
			KEY `uid` (`uid`)
			) TYPE=MyISAM;");

            // Create table pmc_artist
			mysql_query("CREATE TABLE `pmc_artist` (
			`uid` int(5) NOT NULL auto_increment,
			`name` varchar(100) NOT NULL default '',
			`type` varchar(20) NOT NULL default '',
			`link` varchar(100) NOT NULL default '',
			`year` int(4) NOT NULL default '',
			PRIMARY KEY  (`uid`),
			KEY `uid` (`uid`)
			) TYPE=MyISAM;");

            // Create table pmc_user
			mysql_query("CREATE TABLE `pmc_user` (
			`ID` smallint(4) NOT NULL auto_increment,
			`name` varchar(20) default NULL,
			`realname` varchar(150) default NULL,
			`password` varchar(50) default NULL,
			`email` varchar(50) default NULL,
			PRIMARY KEY  (`ID`),
			UNIQUE KEY `name` (`name`)
			) TYPE=MyISAM;");
			
			// Create table pmc_loan
			mysql_query("CREATE TABLE `pmc_loan` (
			`itemid` smallint(4) NOT NULL auto_increment,
			`comicid` smallint(4) NOT NULL default '0',
			`titleid` int(5) NOT NULL,
			`date` date NOT NULL default '0000-00-00',
			`due` date NOT NULL default '0000-00-00',
			`name` varchar(150) NOT NULL default '',
			`notes` varchar(150) NOT NULL default '',
			PRIMARY KEY (`itemid`),
			KEY `itemid` (`itemid`)
			) TYPE=MyISAM;");		
			
			// Create table pmc_link
			mysql_query("CREATE TABLE `pmc_link` (
			`uid` int(5) NOT NULL auto_increment,
			`artist_id` int(5) NOT NULL,
			`comic_id` int(5) NOT NULL,
			`title_id` int(5) NOT NULL,
			`type` varchar(50) NOT NULL default '',
			PRIMARY KEY (`uid`),
			KEY `uid` (`uid`)
			) TYPE=MyISAM;");				

            // Add some default values to the tables
            mysql_query("INSERT INTO `pmc_artist` VALUES (1, 'Ongoing Series', 'Type', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (2, 'Other', 'Type', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (3, 'Dynamic Force', 'Type', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (4, 'One Shot', 'Type', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (5, 'Mini Series', 'Type', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (6, 'Preview', 'Type', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (7, 'Special', 'Type', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (8, 'Poor', 'Condition', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (9, 'Fair', 'Condition', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (10, 'Good', 'Condition', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (11, 'Very Good', 'Condition', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (12, 'Fine', 'Condition', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (13, 'Very Fine', 'Condition', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (14, 'Near Mint', 'Condition', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (15, 'Mint', 'Condition', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (16, 'Softcover', 'Format', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (17, 'Hardcover', 'Format', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (18, 'Paperback', 'Format', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (19, 'Magazine', 'Format', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (20, 'Unknown Series', 'Series', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (21, 'Unknown Publisher', 'Publisher', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (22, 'Unknown Genre', 'Genre', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (23, 'None', 'Variation', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (24, 'Alternative Cover', 'Variation', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (25, 'Autographed', 'Variation', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (26, 'Gold Foil Cover', 'Variation', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (27, 'Limited Edition', 'Variation', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (28, 'Signed', 'Variation', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (29, 'Special Cover', 'Variation', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (30, 'Hologram Edition', 'Variation', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (31, 'Unknown Writer', 'Writer', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (32, 'Unknown Inker', 'Inker', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (33, 'Unknown Penciler', 'Penciler', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (34, 'Unknown Colorist', 'Colorist', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (35, 'Unknown Letterer', 'Letterer', '', '');");
			mysql_query("INSERT INTO `pmc_artist` VALUES (36, 'Unknown Coverartist', 'Coverartist', '', '');");
            mysql_query("INSERT INTO `pmc_artist` VALUES (37, 'USD', 'Currency', '', '');");
            mysql_query("INSERT INTO `pmc_artist` VALUES (38, 'NOK', 'Currency', '', '');");
            mysql_query("INSERT INTO `pmc_artist` VALUES (39, 'DKK', 'Currency', '', '');");
            mysql_query("INSERT INTO `pmc_artist` VALUES (40, 'EUR', 'Currency', '', '');");
            mysql_query("INSERT INTO `pmc_artist` VALUES (41, 'JPY', 'Currency', '', '');");
            mysql_query("INSERT INTO `pmc_artist` VALUES (42, 'CAD', 'Currency', '', '');");
            mysql_query("INSERT INTO `pmc_artist` VALUES (43, 'CHF', 'Currency', '', '');");
            mysql_query("INSERT INTO `pmc_artist` VALUES (44, 'SEK', 'Currency', '', '');");
            mysql_query("INSERT INTO `pmc_artist` VALUES (45, 'GBP', 'Currency', '', '');");
            mysql_query("INSERT INTO `pmc_artist` VALUES (46, 'AUD', 'Currency', '', '');");
            mysql_query("INSERT INTO `pmc_artist` VALUES (46, 'HUF', 'Currency', '', '');");
		 }
   }

// Setting up the personal settings
function fn_personal($error = '')
   {
      print '<font class="head">Personal Options</font><br />';
      print '<font class="norm">Configurations and Settings Wizard<br /><br />';
      print '
	         <font class="norm">Enter your personal information:<br />

             <div class="grey">
		     <TABLE border="0" cellspacing="0" cellpadding="0" width="400">
		       <tr height="25"><td width="150"><font class="norm">&nbsp;Real Name:</font></td><td width="250"><input type="text" name="setup_name" class="admin" value=""></td></tr>
		       <tr height="25"><td width="150"><font class="norm">&nbsp;E-Mail address:</font></td><td width="250"><input type="text" name="setup_mail" class="admin" value=""></td></tr>
		     </table>
             </div><br />

		     Please enter the administrator password for the system:<br />

             <div class="grey">
             <TABLE border="0" cellspacing="0" cellpadding="0" width="400">
		       <tr height="25"><td width="150"><font class="norm">&nbsp;Enter password:</font></td><td width="250"><input type="password" name="setup_pass1" class="admin" value=""></td></tr>
		       <tr height="25"><td width="150"><font class="norm">&nbsp;Confirm password:</font></td><td width="250"><input type="password" name="setup_pass2" class="admin" value=""></td></tr>
		     </table>
             </div>

             </font>

		     <font class="error">&nbsp;'.$error.'</font>';

      print '<input type="hidden" name="setup" value="sysset">';
   }

// Writing the personal settings
function fn_personal_w()
   {
      include("../config/config.php");

      // Open a MySQL connection
      mysql_connect($sql['host'],$sql['user'],$sql['pass'])
            or die("Unable to connect to SQL server");

      mysql_select_db($sql['data'])
            or die("Unable to find DB");

      // Check if the Admin action excists
	  $data = mysql_query('SELECT * FROM pmc_user WHERE name = \'Admin\'') or die("Select Failed!");

      if (mysql_num_rows($data) == 0)
	     {
	        mysql_query('INSERT INTO pmc_user (name, password, email, realname) VALUES (\'Admin\', \''.md5($_POST['setup_pass1']).'\', \''.$_POST['setup_mail'].'\', \''.$_POST['setup_name'].'\');');
	     }
	  else
	     {
	        mysql_query('UPDATE `pmc_user` SET `password` = \''.md5($_POST['setup_pass1']).'\', `email` = \''.$_POST['setup_mail'].'\', `realname` = \''.$_POST['setup_name'].'\' WHERE `name` = \'Admin\';');
	     }

      echo mysql_error();
   }

// Setting up the system settings
function fn_sysset()
   {
      print '<font class="head">System Options</font><br />';
      print '<font class="norm">Configurations and Settings Wizard<br /><br />';
      print '<font class="norm">Please enter the url of your copy of phpmycomic. You must remember the trailing slash (/) at the end of your url: Ex: <i>http://www.yoururl.com/pmc/</i><br />

             <div class="grey">
             <TABLE border="0" cellspacing="0" cellpadding="0" width="400">
               <tr height="25"><td width="150"><font class="norm">&nbsp;PMC URL:</font></td><td width="250"><input type="text" name="setup_siteurl" class="admin" value="http://www."></td></tr>
               <tr height="25"><td width="150"><font class="norm">&nbsp;Site Title:</font></td><td width="250"><input type="text" name="setup_sitetitle" class="admin" value="PhpMyComic 1.2.0"></td></tr>             
               <tr height="25"><td width="150"><font class="norm">&nbsp;Date Option:</font></td><td width="250"><input type="text" name="setup_time" class="admin" value="%d-%m-%Y %H:%i:%S"></td></tr>
             </table>
             </div><br />

             Select if you want to enable / disable these features:<br />

             <div class="grey">
             <TABLE border="0" cellspacing="0" cellpadding="0" width="400">
               <tr height="25"><td width="150"><font class="norm">&nbsp;Enable PDF:</font></td><td width="250">
               <font class="norm">
               <input type="radio" name="setup_pdf" value="Yes" CHECKED>Yes
               <input type="radio" name="setup_pdf" value="No">No
               </font>               
               </td></tr>
               <tr height="25"><td width="150"><font class="norm">&nbsp;Enable Print:</font></td><td width="250">
               <font class="norm">
               <input type="radio" name="setup_print" value="Yes" CHECKED>Yes
               <input type="radio" name="setup_print" value="No">No
               </font>
               </td></tr>
               <tr height="25"><td width="150"><font class="norm">&nbsp;Enable Loan system:</font></td><td width="250">
               <font class="norm">
               <input type="radio" name="setup_loan" value="Yes" CHECKED>Yes
               <input type="radio" name="setup_loan" value="No">No
               </font>
               </td></tr>
             </table>
             </div><br />

             Please enter the image upload configurations:<br />

             <div class="grey">
             <TABLE border="0" cellspacing="0" cellpadding="0" width="400">
               <tr height="25"><td width="150"><font class="norm">&nbsp;Max Width (px):</font></td><td width="250"><input type="text" name="setup_width" class="admin" value="600"></td></tr>
               <tr height="25"><td width="150"><font class="norm">&nbsp;Max Height (px):</font></td><td width="250"><input type="text" name="setup_height" class="admin" value="800"></td></tr>
               <tr height="25"><td width="150"><font class="norm">&nbsp;Max Size (bytes):</font></td><td width="250"><input type="text" name="setup_size" class="admin" value="400000"></td></tr>
             </table>
             </div>

             </font>';

      print '<input type="hidden" name="setup" value="complete">';
   }

// Writing the config file
function fn_sysset_w()
   {
      include("../config/config.php");

      // Open a MySQL connection
      mysql_connect($sql['host'],$sql['user'],$sql['pass']) or die("Unable to connect to SQL server");
      mysql_select_db($sql['data']) or die("Unable to find DB");

      // Set the date and get the query results
      $date = date("Y-m-d G-i-s");
	  $result = mysql_query('SELECT * FROM pmc_user WHERE name=\'Admin\'');
	  $row = mysql_fetch_assoc($result);

      // Set the config file values
      $values = '<?php

// PhpMyComic System Configurations

$sql[\'host\'] = "'.$sql['host'].'";
$sql[\'user\'] = "'.$sql['user'].'";
$sql[\'pass\'] = "'.$sql['pass'].'";
$sql[\'data\'] = "'.$sql['data'].'";

$themes = "default";
$language = "english";
$install = "'.$date.'";
$dateoption = "'.$_POST['setup_time'].'";
$siteurl = "'.$_POST['setup_siteurl'].'";
$sitetitle = "'.$_POST['setup_sitetitle'].'";

$pdfenable = "'.$_POST['setup_pdf'].'";
$printenable = "'.$_POST['setup_print'].'";
$loanenable = "'.$_POST['setup_loan'].'";
$imgwidth = "'.$_POST['setup_width'].'";
$imgheight = "'.$_POST['setup_height'].'";
$imgsize = "'.$_POST['setup_size'].'";
$rssenable = "Yes";
$favenable = "No";

$statsenable = "Yes";
$statstype = "Full";
$listtype = "Latest";
$rownumber = "10";
$paginate = "10";
		
$version = "1.2.0";
$vername = "Promotheus";

?>';

      // Open file for writing
	  $fp = @fopen("../config/config.php","w") or die("Could not open file");

      // Write the config file
	  $numBytes = @fwrite($fp, $values) or die("Could not write to file");

      // Close the config file
	  @fclose($fp);
   }

// The setup is done
function fn_complete()
   {
      print '<font class="head">ThanYou!</font><br />';
      print '<font class="norm">
      You have now successfully installed PhpMyComic 1.2.0 onto your system!<br><br>For further information about PMC you can visit us online:<br><a href="http://www.phpmycomic.net" class="textlink" target="_blank">http://www.phpmycomic.net</a><br><br><b>Important!</b><br>Now that you have installed phpmycomic, you must:<br>Delete the directory "install" to prevent altering of your system configurations!<br><br>Just hit Next to start using PhpMyComic. Enjoy!<br />
      </font>';

      print '<input type="hidden" name="setup" value="startuse">';
   }

// Goto the index page and start using PhpMyComic
function fn_startuse()
   {
      header('Location: ../index.php');
      exit;
   }

?>

<html>
<head>
<title>PhpMyComic Setup</title>
<link rel=stylesheet href="style.css" type=text/css>
</head>
<body class="install" topmargin="0" bottommargin="0" rightmargin="0" leftmargin="0" marginwidth="0" marginheight="0" scroll="yes">
<TABLE border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
  <tr>
    <td valign="middle" align="center">

    <TABLE border="0" cellspacing="0" cellpadding="0" width="800">
      <tr><td width="100%" height="19" background="img/bar_back.jpg" valign="middle" align="left"><TABLE border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td width="10%"><img src="img/bar_left.jpg"></td><td width="80%" class="head"></td><td width="10%" align="right"><img src="img/bar_right.jpg"></td></tr></table></td></tr><tr><td>

      <table border="0" cellspacing="0" cellpadding="0" width="100%" class="window">
      <tr><td>

      <TABLE border="0" cellspacing="0" cellpadding="0" width="100%" class="listborder" height="550">
        <tr><td>
          <TABLE border="0" cellspacing="1" cellpadding="0" width="100%" height="100%">
            <tr class="listitem"><td class="norm" valign="top">

            <TABLE border="0" cellspacing="0" cellpadding="0" width="100%" height="100%" class="black">
              <tr>
                <td background="img/back.jpg" valign="top" width="171">

                <img src="img/logo.jpg"><br />

                <ul class="list">
                <?php draw_steps(); ?>
                </ul>

                <br /><br /><br /><br /><br /><br /><br /><br />
                <ul class="list">
                <li class="list"><b><a href="http://www.phpmycomic.net" class="listlink" target="_blank">Website</a></b></li>
                <li class="list"><b><a href="mailto:bstar@dustrium.net" class="listlink">Contact</a></b></li>
                </ul>

                </td><td width="100%" bgcolor="#FFFFFF" valign="top">

                <div class="content">

                <form class="thin" method="post" action="install.php">
                  <?php
                  switch($_POST['setup'])
                     {
                        case 'database':
                           fn_database();
                        break;

                        case 'personal':
                           fn_database_w();
                           fn_personal();
                        break;

                        case 'sysset':
                           if(empty($_POST['setup_pass1']) or empty($_POST['setup_pass2']))
                              {
                                 $error ='There was an emty field';
                              }
                           if ($_POST['setup_pass1']!= $_POST['setup_pass2'] and empty($error))
                              {
                                 $error = 'The Password fields did not match';
                              }
                           if (strlen($_POST['setup_pass1']) < 5 and empty($error))
                                {
                                 $error='The password is too short (Minimum = 5)';
                                }
                           if (!ereg("^[[:alnum:]_-]+$", $_POST['setup_pass1']) and empty($error))
                              {
                                 $error='Password contains a character besides (a-z,0-9,_ ,-)';
                              }
                           if (isset($error))
                              {
                                 fn_personal($error);
                              }
                           else
                              {
                                 fn_personal_w();
                                 fn_sysset();
                              }
                        break;

                        case 'complete':
                           fn_sysset_w();
                           fn_complete();
                        break;

                        case 'startuse';
                           fn_startuse();
                        break;

                        case 'check':
                           default:
                           fn_check();
                     }
                  ?>

                  <br />

                  <INPUT type="submit" name="Submit" value=" Next " class="button">
                  </form>

                </div>

                </td>
              </tr>
            </table>

            </td></tr>
          </table>
        </td></tr>
      </table>

      </td></tr>
      </table>

      </td></tr><tr><td width="100%" height="7" background="img/bottom_back.jpg" valign="middle" align="left"><TABLE border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td width="10%"><img src="img/bottom_left.jpg"></td><td width="80%" align="center"></td><td width="10%" align="right"><img src="img/bottom_right.jpg"></td></tr></table></td></tr>
    </table>

	</td>
  </tr> 
</table> 
</body>
</html>