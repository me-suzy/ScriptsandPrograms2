<?php
/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 - 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: update.php
-----------------------------------------------------
 Purpose: Update class
=====================================================
*/




// ------------------------------
//  Set-up base preferences
// ------------------------------

error_reporting(E_ALL);
set_magic_quotes_runtime(0);

$path = pathinfo(__FILE__);

define('EXT',			'.'.$path['extension']);
define('PATH_DB',		'./db/'); 
define('PATH_CORE',		'./core/'); 
define('CONFIG_FILE',	'config'.EXT);

// ------------------------------
//  Fetch config file
// ------------------------------

require 'config'.EXT;

// ------------------------------
//  Connect to the database
// ------------------------------

require PATH_DB.'db.'.$conf['db_type'].EXT;
	
$db_config = array(
					'hostname'  	=> $conf['db_hostname'],
					'username'  	=> $conf['db_username'],
					'password'  	=> $conf['db_password'],
					'database'  	=> $conf['db_name'],
					'prefix'    	=> $conf['db_prefix'],
					'conntype'  	=> $conf['db_conntype'],
					'debug'			=> 1,
					'show_queries'	=> FALSE,
					'enable_cache'	=> FALSE
				  );

$DB = new DB($db_config);
$DB->db_connect();



// ------------------------------
//  Instantiate the Update class
// ------------------------------

$UD = new Update();
$UD->conf = $conf;
$UD->update_manager();



class Update {

	var $cur 			= '100';
	var $update_dir 	= './updates/';
	var $update_files	= array();
	var $conf			= array();
	var $next_link		= '';
	var $newest			= '';
	
	// -----------------------------
	//  Update Manager
	// -----------------------------   		

	function update_manager()
	{	
		// If the 'app_version' index is not present in the config file we are 
		// dealing with the public beta.  If so, we'll write it and redirect
	
		if ( ! isset($this->conf['app_version']))
		{
			$data['app_version'] = 0;
			$this->append_config_file($data);
			header("location: update".EXT); 
			exit;
		}	
		
		
		// Fetch the names of all the update scripts in the "update" directory.
		// We use this info to create the update links and to know
		// which update file is next in line to be called.
		
		$this->fetch_update_script_names();
		
		// Create the page header
		
		$this->page_header();		
		
		if ( ! isset($_GET['action']) || ! isset($this->update_files['0']))
		{	
			$this->default_content();
		}
		else
		{	
			// Create the link to the requested update file
		
			$file = $this->update_dir.'ud_'.$this->update_files['0'].EXT;
		
			if ( ! file_exists($file))
			{
				$this->error_message();
				$this->page_footer();
				exit;
			}
			
			// Require the update file and invoke the class
			
			require $file;
			
			$XD = new Updater;
			
			// If the do_update() function returns false we have a problem.
			
			if ( ! $XD->do_update())
			{
				$this->error_message();
				$this->page_footer();
				exit;
			}
			
			// Update the config file with the app_version we just installed
			
			$data['app_version'] = $this->update_files['0'];
			
			$this->update_config_file($data);
			
			$this->conf['app_version'] = $this->update_files['0'];
			
			// Slice the array so we can move onto the next update file
		
			$this->update_files = array_slice($this->update_files, 1);	
			
			// Show the appropriate success message
			
			if (count($this->update_files) > 0)
			{
				$this->good_update_message();
			}
			else
			{
				$this->update_finished_message();
			}
		}
		
		// Create the link to the update file
		
		$this->next_link();
		
		// Page footer
		
		$this->page_footer();
	}
	// END



	// -----------------------------
	//  Default Content
	// ----------------------------- 

	function default_content()
	{
		$from = 'Public Beta 1';

		$fver = ( ! isset($this->conf['app_version']) || $this->conf['app_version'] == 0) ? $from : $this->conf['app_version'];
					
		if (is_numeric($fver))
		{
			$from = '';
			
			if ($fver == '009')
			{
				$from .= 'Public Beta 2';
			}
			else
			{
				$from .= 'Version '.substr($fver, 0, 1).'.'.substr($fver, 1, 1);
	
				if (substr($fver, 2, 1) != 0)
				{
					$from .= '.'.substr($fver, 2, 1);
				}
			}
		}
	?>
		 
	<div id='content'>
	
	<h1>Welcome!</h1>
	
	<p>You are currently running ExpressionEngine: <?php echo $from; ?></p>
	
	<?php
	
	if ($this->newest == $this->conf['app_version'])
	{
	?>
	<p>This is the most current version</p>
	<?php
	}
	else
	{
	?>
	
	<p>Before performing any updates, please make sure you have uploaded all new scripts as indicated in the documentation.</p>
	
	<p>If you are ready to update your system, please click the link below</p>
	
	<?php
	}
	}
	// END



	// -----------------------------
	//  Good update message
	// ----------------------------- 

	function good_update_message()
	{
		$cver = ( ! isset($this->conf['app_version']) || $this->conf['app_version'] == 0) ? $this->cur : $this->conf['app_version'];
					
		if (is_numeric($cver))
		{
			$this->cur = '';
			
			if ($cver == '009')
			{
				$this->cur .= 'Public Beta 2';
			}
			else
			{
				$this->cur .= 'Version '.substr($cver, 0, 1).'.'.substr($cver, 1, 1);
	
				if (substr($cver, 2, 1) != 0)
				{
					$this->cur .= '.'.substr($cver, 2, 1);
				}
			}
		}
	?>
		 
	<div id='content'>	
	
	<h3>Good!</h3>
			
	<p>You you have updated to: <?php echo $this->cur; ?></p>
	
	<p>
	Please click the link below to continue to the next step.
	</p>
	
	<?php
	}
	// END



	// -----------------------------
	//  Update finished message
	// ----------------------------- 

	function update_finished_message()
	{
		$cur = '100';

		$cver = ( ! isset($this->conf['app_version']) || $this->conf['app_version'] == 0) ? $cur : $this->conf['app_version'];
					
		if (is_numeric($cver))
		{
			$cur = ' ';
			
			if ($cver == '009')
			{
				$cur .= 'Public Beta 2';
			}
			else
			{
				$cur .= 'Version '.substr($cver, 0, 1).'.'.substr($cver, 1, 1);
	
				if (substr($cver, 2, 1) != 0)
				{
					$cur .= '.'.substr($cver, 2, 1);
				}
			}
		}
	?>
		 
	<div id='content'>	
	
	<h1>Success!!</h1>
	
	<p><b>You have successfully updated to ExpressionEngine <?php echo $cur; ?>!</b></p>
	
	<h2>VERY IMPORTANT!!</h2>
			
	<div class="error">
	Using your FTP program, please delete THIS file (<b>update.php</b>) from your server, as well as the entire <b>updates</b> directory.
	<br /><br />
	Leaving these items on your server presents a security risk

	</dif>
	<?php
	}
	// END



	// -----------------------------
	//  Error Message
	// ----------------------------- 

	function error_message()
	{
	?>
		 
	<div id='content'>	
	
	<h2>ERROR</h2>
			
	<p>
	An error was encountered while performing this update.
	</p>
	<?php
	}
	// END
	
	
	
	// -----------------------------
	//  Create the next link
	// ----------------------------- 

	function next_link()
	{
		if (count($this->update_files) > 0)
		{
			$from = 'Public Beta 1';

			$fver = ( ! isset($this->conf['app_version']) || $this->conf['app_version'] == 0) ? $from : $this->conf['app_version'];
						
			if (is_numeric($fver))
			{
				$from = 'Version '.substr($fver, 0, 1).'.'.substr($fver, 1, 1);
	
				if (substr($fver, 2, 1) != 0)
				{
					$from .= '.'.substr($fver, 2, 1);
				}
			}
			
			$tver = $this->update_files['0'];
			
			$to = ' ';
			
			if ($tver == '009')
			{
				$to .= 'Public Beta 2';
			}
			else
			{
				$to .= 'Version '.substr($tver, 0, 1).'.'.substr($tver, 1, 1);
				
				if (substr($tver, 2, 1) != 0)
				{
					$to .= '.'.substr($tver, 2, 1);
				}
			}
			
			
			$path = 'update'.EXT.'?action=ud';

			echo "<p><br /><a href='".$path."'>Update from {$from}&nbsp; to&nbsp; {$to}</a></p>";
		}
	}
	// END



	// -----------------------------
	//  Fetch Available Updates
	// ----------------------------- 
	
	// This function reads though the "updates" directory and
	// makes a list of all available updates

	function fetch_update_script_names()
	{
		$cur =  ( ! isset($this->conf['app_version'])) ? 0 : $this->conf['app_version'];
	
		if ( ! $fp = @opendir($this->update_dir)) 
			return false;
			
		while (false !== ($file = readdir($fp))) 
		{
			if (substr($file, 0, 3) == 'ud_')
			{			
				$file = str_replace(EXT,  '', $file);
				$file = str_replace('ud_', '', $file);
				
				if ($file > $cur)
				{
					$this->update_files[] = $file;
					$this->newest = $file;
				}
				else
				{
					$this->newest = $cur;
				}
			}
		} 
		
		closedir($fp); 
	}
	// END

	
	
	// -----------------------------------------
	//	Update config file
	// -----------------------------------------
		
	// Note:  The input must be an array

	function update_config_file($newdata = '')
	{
		if ( ! is_array($newdata))
		{
			return false;
		}
				
		require CONFIG_FILE;
		
		// -----------------------------------------
		//	Write config backup file
		// -----------------------------------------
				
		$old  = "<?php\n\n";
		$old .= "if ( ! defined('EXT')){\nexit('Invalid file request');\n}\n\n";
	 
		foreach ($conf as $key => $val)
		{
			$old .= "\$conf['".$key."'] = \"".addslashes($val)."\";\n";
		} 
		
		$old .= '?'.'>';
		
		$bak_path = str_replace(EXT, '', CONFIG_FILE);
		$bak_path .= '_bak'.EXT;
		
		if ($fp = @fopen($bak_path, 'wb'))
		{
			flock($fp, LOCK_EX);
			
			fwrite($fp, $old);
			
			flock($fp, LOCK_UN);
			
			fclose($fp);
		}		
		
		// -----------------------------------------
		//	Add new data values to config file
		// -----------------------------------------		
			
		foreach ($newdata as $key => $val)
		{
			$val = str_replace("\n", " ", $val);
		
			if (isset($conf[$key]))
			{			
				$conf[$key] = trim($val);	
			}
		}
		
		reset($conf);
		
		// -----------------------------------------
		//	Write config file as a string
		// -----------------------------------------
		
		$new  = "<?php\n\n";
		$new .= "if ( ! defined('EXT')){\nexit('Invalid file request');\n}\n\n";
	 
		foreach ($conf as $key => $val)
		{
			$new .= "\$conf['".$key."'] = \"".addslashes($val)."\";\n";
		} 
		
		$new .= '?'.'>';
		
		// -----------------------------------------
		//	Write config file
		// -----------------------------------------

		if ($fp = @fopen(CONFIG_FILE, 'wb'))
		{
			flock($fp, LOCK_EX);
			
			fwrite($fp, $new);
			
			flock($fp, LOCK_UN);
			
			fclose($fp);
		}
	}	
	// END
		
		
		
	// -------------------------------------------
	//	Append config file 
	// -------------------------------------------
	
	// This function allows us to add new config file elements
	
	// Note:  The input must be an array

	function append_config_file($new_config)
	{
		require CONFIG_FILE;

		if ( ! is_array($new_config))
			return false;
		
		// -----------------------------------------
		//	Write config backup file
		// -----------------------------------------
		
		$old  = "<?php\n\n";
		$old .= "if ( ! defined('EXT')){\nexit('Invalid file request');\n}\n\n";
	 
		foreach ($conf as $key => $val)
		{
			$old .= "\$conf['".$key."'] = \"".$val."\";\n";
		} 
		
		$old .= '?'.'>';
		
		$bak_path = str_replace(EXT, '', CONFIG_FILE);
		$bak_path .= '_bak'.EXT;

		if ($fp = @fopen($bak_path, 'wb'))
		{
			flock($fp, LOCK_EX);
			
			fwrite($fp, $old);
			
			flock($fp, LOCK_UN);
			
			fclose($fp);
		}		
		
		// -----------------------------------------
		//	Merge new data to the congig file
		// -----------------------------------------
		
		$conf = array_merge($conf, $new_config);		
				
		$new  = "<?php\n\n";
		$new .= "if ( ! defined('EXT')){\nexit('Invalid file request');\n}\n\n";
	 
		foreach ($conf as $key => $val)
		{
			$new .= "\$conf['".$key."'] = \"".$val."\";\n";
		} 
		
		$new .= '?'.'>';

		if ($fp = @fopen(CONFIG_FILE, 'wb'))
		{
			flock($fp, LOCK_EX);
			
			fwrite($fp, $new);
			
			flock($fp, LOCK_UN);
			
			fclose($fp);
		}		
	}
	// END
		

	// -----------------------------
	//  Page Header
	// ----------------------------- 
	
	function page_header()
	{
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
	
	<head>
	<title>ExpressionEngine | Update Wizard</title>
	
	<meta http-equiv='content-type' content='text/html; charset=UTF-8' />
	<meta name='MSSmartTagsPreventParsing' content='TRUE' />
	<meta http-equiv='expires' content='-1' />
	<meta http-equiv= 'pragma' content='no-cache' />
	
	<style type='text/css'>
	
	
	body {
	  margin:             0;
	  padding:            0;
	  font-family:        Verdana, Geneva, Helvetica, Trebuchet MS, Sans-serif;
	  font-size:          12px;
	  color:              #333;
	  background-color:   #fff;
	  }
	
	a {
	  font-size:          12px;
	  text-decoration:    underline;
	  font-weight:        bold;
	  color:              #330099;
	  background-color:   transparent;
	  }
	  
	a:visited {
	  color:              #330099;
	  background-color:   transparent;
	  }
	
	a:active {
	  color:              #ccc;
	  background-color:   transparent;
	  }
	
	a:hover {
	  color:              #000;
	  text-decoration:    none;
	  background-color:   transparent;
	  }
	
	
	#simpleHeader {  
	  background-color:   #828BD1;
	  height:             50px;
	  border-bottom:      #000 1px solid;
	}
	.solidLine { 
	  border-top:          #999 1px solid;
	  }
	.logo {
	  font-family:         Arial, Trebuchet MS, Verdana, Sans-serif;
	  font-size:           20px;
	  color:               #fff;
	  height:              16px;
	  letter-spacing:      0px;
	  background:          transparent;
	  text-align:          bottom;
	  padding:             16px 0 0 20px; /* top right bottom left */ 
	  }
	  	 
	#content {
	  left:          0px;
	  right:         10px;
	  margin:        0 35px 0 25px;
	  }
	
	.copyright {
	  text-align:         center;
	  font-family:        Verdana, Geneva, Helvetica, Trebuchet MS, Sans-serif;
	  font-size:          9px;
	  color:              #999999;
	  line-height:        15px;
	  margin-top:         20px;
	  margin-bottom:      15px;
	  padding:            20px;
	  }
	  	
	.error {
	  font-family:        Verdana, Trebuchet MS, Arial, Sans-serif;
	  font-size:          13px;
	  margin-bottom:      8px;
	  font-weight:        normal;
	  color:              #990000;
	}
	
	h1 {
	  font-family:        Verdana, Trebuchet MS, Arial, Sans-serif;
	  font-size:          20px;
	  font-weight:        bold;
	  color:              #000;
	  margin-top:         15px;
	  margin-bottom:      16px;
	  background-color:   transparent;
	  border-bottom:      #7B81A9 2px solid;
	}
	
	h2 {
	  font-family:        Arial, Trebuchet MS, Verdana, Sans-serif;
	  font-size:          18px;
	  color:              #990000;
	  letter-spacing:     2px;
	  margin-top:         14px;
	  margin-bottom:      8px;
	  border-bottom:      #7B81A9 1px dashed;
	  background-color:   transparent;
	}
	h3 {
	  font-family:        Arial, Trebuchet MS, Verdana, Sans-serif;
	  font-size:          18px;
	  color:              #009933;
	  letter-spacing:     2px;
	  margin-top:         14px;
	  margin-bottom:      8px;
	  border-bottom:      #7B81A9 1px dashed;
	  background-color:   transparent;
	}
	
	p {
	  font-family:        Verdana, Geneva, Trebuchet MS, Arial, Sans-serif;
	  font-size:          12px;
	  font-weight:        normal;
	  color:              #333;
	  margin-top:         8px;
	  margin-bottom:      8px;
	  background-color:   transparent;
	}
		
	form {
	  margin:         0;
	}
	.hidden {
	  margin:         0;
	  padding:        0;
	  border:         0;
	}
	.input {
	  border-top:         1px solid #999999;
	  border-left:        1px solid #999999;
	  background-color:   #fff;
	  color:              #000;
	  font-family:        Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
	  font-size:          11px;
	  height:             1.6em;
	  padding:            .3em 0 0 2px;
	  margin-top:          0;
	  margin-bottom:       12px;
	} 
	.textarea {
	  border-top:         1px solid #999999;
	  border-left:        1px solid #999999;
	  background-color:   #fff;
	  color:              #000;
	  font-family:        Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
	  font-size:          11px;
	  margin-top:         6px;
	  margin-bottom:      3px;
	}
	.select {
	  background-color:   #fff;
	  font-family:        Arial, Verdana, Sans-serif;
	  font-size:          10px;
	  font-weight:        normal;
	  letter-spacing:     .1em;
	  color:              #000;
	  margin-top:         6px;
	  margin-bottom:      3px;
	} 
	.radio {
	  color:              #000;
	  margin-top:         7px;
	  margin-bottom:      4px;
	  padding:            0;
	  border:             0;
	  background-color:   transparent;
	}
	.checkbox {
	  background-color:   transparent;
	  margin:             3px;
	  padding:            0;
	  border:             0;
	}
	.submit {
	  background-color:   #fff;
	  font-family:        Arial, Verdana, Sans-serif;
	  font-size:          10px;
	  font-weight:        normal;
	  letter-spacing:     .1em;
	  padding:            1px 3px 1px 3px;
	  margin-top:         6px;
	  margin-bottom:      4px;
	  text-transform:     uppercase;
	  color:              #000;
	}  
	
	</style>
	
	</head>
	
	<body>
	
	<div id='simpleHeader'>
	<table border='0' cellspacing='0' cellpadding='0' width='96%'>
	<tr>
	<td class='logo'>ExpressionEngine Update Wizard</td>
	</tr>
	</table>
	</div>
	
	<div id='content'>
	<br />
	<?php
	}
	// END
	
	
	
	// -----------------------------
	//  Page Footer
	// ----------------------------- 

	function page_footer()
	{
	?>
	
	<div class='copyright'>ExpressionEngine by pMachine - &#169; copyright 2003 - 2004 - pMachine, Inc. - All Rights Reserved</div>
	
	</div>
	
	</body>
	</html>
	<?php
	}
	// END

}
// END CLASS

?>