<?php

/*
+--------------------------------------------------------------------------
|   IBFORUMS v1 UPGRADE FROM 1.0.X to v1.1
|   ========================================
|   by Matthew Mecham
|   (c) 2001,2002 IBForums
|   http://www.ibforums.com
|   ========================================
|   Web: http://www.ibforums.com
|   Email: phpboards@ibforums.com
|   Licence Info: phpib-licence@ibforums.com
+---------------------------------------------------------------------------
|
|   > Upgrade Script #4
|   > Script written by Matt Mecham
|   > Date started: 23rd October 2002
|
+--------------------------------------------------------------------------
*/

//-----------------------------------------------
// USER CONFIGURABLE ELEMENTS
//-----------------------------------------------
 
// Root path

$root_path = "./";

//-----------------------------------------------
// NO USER EDITABLE SECTIONS BELOW
//-----------------------------------------------
 
error_reporting  (E_ERROR | E_WARNING | E_PARSE);
set_magic_quotes_runtime(0);

$template = new template;
$std      = new installer;

$VARS = $std->parse_incoming();

//--------------------------------
// Import $INFO, now!
//--------------------------------

require $root_path."conf_global.php";

//--------------------------------
// Load the DB driver and such
//--------------------------------

$INFO['sql_driver'] = !$INFO['sql_driver'] ? 'mySQL' : $INFO['sql_driver'];

$to_require = $root_path."sources/Drivers/".$INFO['sql_driver'].".php";
require ($to_require);

$DB = new db_driver;

$DB->obj['sql_database']     = $INFO['sql_database'];
$DB->obj['sql_user']         = $INFO['sql_user'];
$DB->obj['sql_pass']         = $INFO['sql_pass'];
$DB->obj['sql_host']         = $INFO['sql_host'];
$DB->obj['sql_tbl_prefix']   = $INFO['sql_tbl_prefix'];

// Get a DB connection

$DB->connect();

// Switch off auto_error

$DB->return_die = 1;

//---------------------------------------
// Sort out what to do..
//---------------------------------------

switch($VARS['a'])
{
	case 'tables':
		do_tables();
		break;
		
	case 'alter':
		do_alter();
		break;
		
	case 'index':
		do_index();
		break;
		
	case 'insert':
		do_insert();
		break;
		
	case 'templates':
		do_templates();
		break;
		
	case 'cleanup':
		do_cleanup();
		break;
		
	default:
		do_intro();
		break;
}

function do_cleanup()
{
	global $std, $template, $root, $DB;
	
	// Insert new CSS
	
	$DB->query("INSERT INTO ibf_css (css_name, css_text, css_comments) VALUES ('Invision Style Sheet (NEW)', 'form { display:inline }\r\nTABLE, TR, TD { font-family: Verdana, Tahoma, Arial; font-size: 8.5pt; color: #000000 }\r\na:link, a:visited, a:active { text-decoration: underline; color: #000000 }\r\na:hover { color: #465584 }\r\n.hlight { background-color: #DFE6EF }\r\n.dlight { background-color: #EEF2F7 }\r\n.mainbg { background-color: #FFFFFF }\r\n.mainfoot { background-color: #BCD0ED }\r\n.forum1 { background-color: #DFE6EF }\r\n.forum2 { background-color: #E4EAF2 }\r\n.post1 { background-color: #F5F9FD }\r\n.post2 { background-color: #EEF2F7 }\r\n.posthead { background-color: #E4EAF2 }\r\n\r\n.postbak { background-color: #D2D2D0 }\r\n.title { background-color: #C4DCF7 }\r\n.row1 { background-color: #EEF2F7 }\r\n.row2 { background-color: #F5F9FD }\r\n.postsep { background-color: #C7D2E0; height: 1px }\r\n\r\n.signature { font-size: 7.5pt; color: #333399 }\r\n.postdetails { font-size: 7.5pt }\r\n.postcolor, #postcolor { font-size: 9pt; line-height: 160% }\r\n.membertitle { font-size: 10px; line-height: 150%; color: #000000 }\r\n.normalname { font-size: 12px; font-weight: bold; color: #000033; padding-bottom: 2px }\r\n.normalname a:link, .normalname a:visited, .normalname a:active { text-decoration: underline; color: #000033; padding-bottom: 2px }\r\n.unreg { font-size: 11px; font-weight: bold; color: #990000 }\r\n.highlight { color: #FF0000 }\r\n.highlight a:link, .highlight a:visited, .highlight a:active { text-decoration: underline; color: #FF0000 }\r\n.highlight a:hover { text-decoration: underline }\r\n.desc { font-size: 8.0pt; color: #434951 }\r\n.copyright { font-family: Verdana, Tahoma, Arial; font-size: 7.5pt; line-height: 12px }\r\n.category { font-weight: bold; line-height: 150%; color: #4C77B6; background-color: #C2CFDF }\r\n.category   a:link, #category   a:visited, #category   a:active { text-decoration: none; color: #4C77B6 }\r\n.postfoot         {\r\n\r\n    font-weight:bold;\r\n\r\n    color:#3A4F6C;\r\n\r\n    height: 24px;\r\n\r\n    background-color: #D1DCEB;\r\n\r\n}\r\n.titlefoot { font-weight: bold; color: #3A4F6C; height: 24px; background-color: #BCD0ED }\r\n.titlemedium         {\r\n\r\n    font-weight:bold;\r\n    color:#3A4F6C;\r\n\r\n    height: 30px;\r\n\r\n    background-color: #9FBCE3;\r\n    \r\n    background-image: url(style_images/<#IMG_DIR#>/tile_sub.gif);\r\n\r\n}\r\n.titlemedium  a:link,  .titlefoot  a:link, .titlemedium  a:visited, .titlefoot  a:visited, .titlemedium  a:active, .titlefoot  a:active { text-decoration: underline; color: #3A4F6C }\r\n.titlemedium a:hover, .subtitle a:hover, .titlefoot a:hover { text-decoration: underline; color: #000000 }\r\n.maintitle         {\r\n\r\n    color:#FFFFFF;\r\n\r\n    font-size: 9.5pt;\r\n    \r\n    height: 26px;\r\n    \r\n    background-image: url(style_images/<#IMG_DIR#>/tile_back.gif);\r\n\r\n}\r\n.edit { font-size: 9px }\r\n.fancyborder { border: 1px dashed #999999 }\r\n.solidborder { border: 1px solid #999999 }\r\n.maintitle  a:link, .maintitle  a:visited, .maintitle  a:active { text-decoration: none; color: #FFFFFF }\r\n.maintitle a:hover { text-decoration: underline }\r\n.nav { font-weight: bold; color: #000000; font-size: 8.5pt }\r\n.pagetitle { color: #4C77B6; font-size: 18px; font-weight: bold; letter-spacing: -1px; line-height: 120% }\r\n.useroptions { background-color: #598CC3; height: 25px; font-weight: bold; color: #FFFFFF }\r\n.useroptions a:link, .useroptions a:visited,.useroptions a:active { text-decoration: none; color: #FFFFFF }\r\n.bottomborder { border-bottom: 1px dashed #D2D2D0 }\r\n.linkthru { color: #000000; font-size:8.5pt }\r\n.linkthru  a:link, .linkthru  a:active { text-decoration: underline; color: #000000 }\r\n.linkthru  a:visited { text-decoration: underline; color: #000000 }\r\n.linkthru  a:hover { text-decoration: underline; color: #465584 }\r\n#QUOTE { font-family: Verdana, Arial; font-size: 8pt; color: #333333; background-color: #FAFCFE; border: 1px solid Black; padding-top: 2px; padding-right: 2px; padding-bottom: 2px; padding-left: 2px }\r\n#CODE { font-family: Verdana, Arial; font-size: 8pt; color: #333333; background-color: #FAFCFE; border: 1px solid Black; padding-top: 2px; padding-right: 2px; padding-bottom: 2px; padding-left: 2px }\r\n.codebuttons { font-size: 8.5pt; font-family: verdana, helvetica, sans-serif; vertical-align: middle }\r\n.forminput { font-size: 9pt; font-family: verdana, helvetica, sans-serif; vertical-align: middle }\r\n.textinput { font-size: 9pt; font-family: verdana, helvetica, sans-serif; vertical-align: middle }\r\n.input { font-size: 9pt; font-family: verdana, helvetica, sans-serif; vertical-align: middle }\r\n', NULL)");
	
	$new_css_id = $DB->get_insert_id();
	
	// Get old skin details
	
	$DB->query("SELECT * FROM ibf_skins WHERE default_set=1");
	
	$old_skin = $DB->fetch_row();
	
	// First off, change all old skin sets to hidden
	
	$DB->query("UPDATE ibf_skins SET hidden=1, default_set=0");
	
	// Change upload perms..
	
	$DB->query("UPDATE ibf_forums SET upload_perms=''");
	
	// Get a new ID for the new skin set
	
	$barney = array( 'sname'      => "IBF Skin Set (NEW)",
					 'set_id'     => 1,
					 'tmpl_id'    => $old_skin['tmpl_id'],
					 'img_dir'    => 1,
					 'css_id'     => $new_css_id,
					 'hidden'     => 0,
					 'default_set'=> 1,
					 'macro_id'   => 1,
				   );
					   
		
	$DB->query("SELECT MAX(sid) as new_id FROM ibf_skins");
	
	$row = $DB->fetch_row();
	
	$barney['sid'] = $row['new_id'] + 1;

	$db_string = $DB->compile_db_insert_string( $barney );
	
	$DB->query("INSERT INTO ibf_skins (".$db_string['FIELD_NAMES'].") VALUES(".$db_string['FIELD_VALUES'].")");
	
	// Change member skins to default
	
	$DB->query("UPDATE ibf_members SET skin=NULL");
	
	$template->print_top('Upgrade Complete');
	
	$template->contents .= "<tr>
							  <td id='subtitle'>&#149;&nbsp;Upgrade from v1.0.X to v1.1</td>
							<tr>
							<td>
							  <table cellpadding='8' cellspacing='0' width='100%' align='center' border='0' id='tablewrap'>
							  <tr>
								<td>
							  <table width='100%' cellspacing='1' cellpadding='0' align='center' border='0' id='table1'>
							   <tr>
								<td>
								 <table width='100%' cellspacing='2' cellpadding='3' align='center' border='0'>
								 <tr>
								   <td>
								   		<b>The upgrade was successful!</b>
								   		<br><br>
								   		<b>Forum Upload Permission Changes</b>
								   		<br>
								   		Note, due to the way that the upload permissions are handled in v1.1, the upload permissions for all forums
								   		have been removed. You will need to log into your Admin CP and set up the permissions using the new system.
								   		<br><br>
								   		<b>Skin Set Changes</b>
								   		<br>
								   		The skin, templates and macros are handled completely differently in this new version. Unfortunately v1.0.X
								   		skins are not compatible with this new version. All old skin sets have been set to 'hidden' and all members
								   		have been moved onto the new default skin.
								   		<br><br>
								   		The new skin set will use the directory 'style_images/1/' as the image directory. Ensure that all the images
								   		in there have been updated from the download zip. If the directory does not exist, create it and upload
								   		the contents of the folder 'style_images/1/' from the zip file.
								   		<br><br>
								   		<b>PLEASE REMOVE THIS UPGRADE SCRIPT AS SOON AS POSSIBLE</b>
								   	</td>
								 </tr>
								</table>
							  </td>
							 </tr>
							</table>
						   </td>
						  </tr>
						 </table>";
						 
	
	
	$template->output();
}


//+---------------------------------------



function do_templates()
{
	global $std, $template, $root, $DB;
	
	//-----------------------------------
	// Lets open the style file
	//-----------------------------------
	
	$style_file = $root.'install_templates.txt';
	
	if ( ! file_exists($style_file) )
	{
		install_error("Could not locate '$style_file'. <br><br>Check to ensure that this file exists in the same location as this script.<br><br>You may need to enter a value for the root path in this installer script, to do this, simply open up this script in a text editor and enter a value in \$root - remember to add a trailing slash. NT users will need to use double backslashes");
	}
	
	if ( $fh = fopen( $style_file, 'r' ) )
	{
		$data = fread($fh, filesize($style_file) );
		fclose($fh);
	}
	else
	{
		install_error("Could open '$style_file'");
	}
	
	if (strlen($data) < 100)
	{
		install_error("Err 1:'$style_file' is incomplete, please re-upload a fresh copy over the existing copy on the server'");
	}
	
	// Chop up the data file.
	
	$template_rows = explode( "||~&~||", $data );
	
	$crows = count($template_rows);
	
	if ( $crows < 100 )
	{
		install_error("Err2: (Found $crows rows) '$style_file' is incomplete, please re-upload a fresh copy over the existing copy on the server'");
	}
	
	//-----------------------------------
	// Lets populate the database!
	//-----------------------------------
	
	foreach( $template_rows as $q )
	{
		$DB->error = "";
		
		$q = trim($q);
		
		//print $q;
	   
		if (strlen($q) < 5)
		{
			continue;
		}
		
		$DB->query("INSERT INTO ibf_skin_templates (set_id, group_name, section_content, func_name, func_data, updated, can_remove) VALUES $q");
			
		if ( $DB->error != "" )
		{
			install_error( "mySQL Error: ".$DB->error );
		}
	}
   
    //---------------------------------------
	
	$template->print_top('Step 5 Complete');
	
	$template->contents .= "<tr>
							  <td id='subtitle'>&#149;&nbsp;Upgrade from v1.0.X to v1.1</td>
							<tr>
							<td>
							  <table cellpadding='8' cellspacing='0' width='100%' align='center' border='0' id='tablewrap'>
							  <tr>
								<td>
							  <table width='100%' cellspacing='1' cellpadding='0' align='center' border='0' id='table1'>
							   <tr>
								<td>
								 <table width='100%' cellspacing='2' cellpadding='3' align='center' border='0'>
								 <tr>
								   <td>
								   		<b>The new templates been inserted</b>
								   		<br><br>
								   		You may now proceed to the next step.
								   	</td>
								 </tr>
								</table>
							  </td>
							 </tr>
							</table>
						   </td>
						  </tr>
						 </table>";
						 
	
	$template->contents .= "<tr><td align='center' style='font-size:18px'><br><b><a href='upgrade4.php?a=cleanup'>Proceed to Step 6: Finishing Up</a> &gt;&gt;</b></td></tr>";
	
	$template->output();
}


//+---------------------------------------



function do_insert()
{
	global $std, $template, $root, $DB;
	
	run_sql('insert');
	
	$template->print_top('Step 4 Complete');
	
	$template->contents .= "<tr>
							  <td id='subtitle'>&#149;&nbsp;Upgrade from v1.0.X to v1.1</td>
							<tr>
							<td>
							  <table cellpadding='8' cellspacing='0' width='100%' align='center' border='0' id='tablewrap'>
							  <tr>
								<td>
							  <table width='100%' cellspacing='1' cellpadding='0' align='center' border='0' id='table1'>
							   <tr>
								<td>
								 <table width='100%' cellspacing='2' cellpadding='3' align='center' border='0'>
								 <tr>
								   <td>
								   		<b>The new data has been inserted</b>
								   		<br><br>
								   		You may now proceed to the next step.
								   	</td>
								 </tr>
								</table>
							  </td>
							 </tr>
							</table>
						   </td>
						  </tr>
						 </table>";
						 
	
	$template->contents .= "<tr><td align='center' style='font-size:18px'><br><b><a href='upgrade4.php?a=templates'>Proceed to Step 5: Insert New Templates</a> &gt;&gt;</b></td></tr>";
	
	$template->output();
}


//+---------------------------------------


function do_index()
{
	global $std, $template, $root, $DB;
	
	run_sql('index');
	
	$template->print_top('Step 3 Complete');
	
	$template->contents .= "<tr>
							  <td id='subtitle'>&#149;&nbsp;Upgrade from v1.0.X to v1.1</td>
							<tr>
							<td>
							  <table cellpadding='8' cellspacing='0' width='100%' align='center' border='0' id='tablewrap'>
							  <tr>
								<td>
							  <table width='100%' cellspacing='1' cellpadding='0' align='center' border='0' id='table1'>
							   <tr>
								<td>
								 <table width='100%' cellspacing='2' cellpadding='3' align='center' border='0'>
								 <tr>
								   <td>
								   		<b>The indexes have been created</b>
								   		<br><br>
								   		You may now proceed to the next step.
								   	</td>
								 </tr>
								</table>
							  </td>
							 </tr>
							</table>
						   </td>
						  </tr>
						 </table>";
						 
	
	$template->contents .= "<tr><td align='center' style='font-size:18px'><br><b><a href='upgrade4.php?a=insert'>Proceed to Step 4: Insert New Data</a> &gt;&gt;</b></td></tr>";
	
	$template->output();
}


//+---------------------------------------

function do_alter()
{
	global $std, $template, $root, $DB;
	
	run_sql('alter');
	
	$template->print_top('Step 2 Complete');
	
	$template->contents .= "<tr>
							  <td id='subtitle'>&#149;&nbsp;Upgrade from v1.0.X to v1.1</td>
							<tr>
							<td>
							  <table cellpadding='8' cellspacing='0' width='100%' align='center' border='0' id='tablewrap'>
							  <tr>
								<td>
							  <table width='100%' cellspacing='1' cellpadding='0' align='center' border='0' id='table1'>
							   <tr>
								<td>
								 <table width='100%' cellspacing='2' cellpadding='3' align='center' border='0'>
								 <tr>
								   <td>
								   		<b>The tables have been altered</b>
								   		<br><br>
								   		You may now proceed to the next step.
								   	</td>
								 </tr>
								</table>
							  </td>
							 </tr>
							</table>
						   </td>
						  </tr>
						 </table>";
						 
	
	$template->contents .= "<tr><td align='center' style='font-size:18px'><br><b><a href='upgrade4.php?a=index'>Proceed to Step 3: Add Indexes</a> &gt;&gt;</b></td></tr>";
	
	$template->output();
}


//+---------------------------------------

function do_tables()
{
	global $std, $template, $root, $DB;
	
	run_sql('tables');
	
	$template->print_top('Step 1 Complete');
	
	$template->contents .= "<tr>
							  <td id='subtitle'>&#149;&nbsp;Upgrade from v1.0.X to v1.1</td>
							<tr>
							<td>
							  <table cellpadding='8' cellspacing='0' width='100%' align='center' border='0' id='tablewrap'>
							  <tr>
								<td>
							  <table width='100%' cellspacing='1' cellpadding='0' align='center' border='0' id='table1'>
							   <tr>
								<td>
								 <table width='100%' cellspacing='2' cellpadding='3' align='center' border='0'>
								 <tr>
								   <td>
								   		<b>The new tables have been created</b>
								   		<br><br>
								   		You may now proceed to the next step.
								   	</td>
								 </tr>
								</table>
							  </td>
							 </tr>
							</table>
						   </td>
						  </tr>
						 </table>";
						 
	
	$template->contents .= "<tr><td align='center' style='font-size:18px'><br><b><a href='upgrade4.php?a=alter'>Proceed to Step 2: Alter Existing Tables</a> &gt;&gt;</b></td></tr>";
	
	$template->output();
}


//+---------------------------------------

function do_intro()
{
	global $std, $template, $root;
	
	$template->print_top('Welcome');
	
	$template->contents .= "<tr>
							  <td id='subtitle'>&#149;&nbsp;Upgrade from v1.0.X to v1.1</td>
							<tr>
							<td>
							  <table cellpadding='8' cellspacing='0' width='100%' align='center' border='0' id='tablewrap'>
							  <tr>
								<td>
							  <table width='100%' cellspacing='1' cellpadding='0' align='center' border='0' id='table1'>
							   <tr>
								<td>
								 <table width='100%' cellspacing='2' cellpadding='3' align='center' border='0'>
								 <tr>
								   <td>
								   		<b>Welcome to the Invision Board Upgrade Utility</b>
								   		<br><br>
								   		Before you run this upgrade tool, please ensure that ALL of the source, language and skin files
								   		have been uploaded into the corresponding directories on your server.
								   		<br><br>
								   		This script should be in the root forum directory (the same directory that index.php is in).
								   		<br><br>
								   		This installer will alter the database for the new format and install the skin and macro files.
								   		<br><br>
								   		Please ensure that 'install_templates.txt' is uploaded and in the same directory as this script.
								   		<br><br>
								   		Also, please ensure that all the new skin_*.php files have been uploaded into 'Skin/s1' before starting
								   		this upgrader. If you wish to keep the old skin data in that directory, please copy the directory now before
								   		proceeding. Running this installer will set all existing skins to 'hidden' and install a new default skin pack
								   	</td>
								 </tr>
								</table>
							  </td>
							 </tr>
							</table>
						   </td>
						  </tr>
						 </table>";
						 
	$style_file = $root."install_templates.txt";
	
	$warnings = array();
	
	if ( ! file_exists($style_file) )
	{
		$warnings[] = "Cannot locate the file 'install_templates.txt'. This should be uploaded into the same directory as this script!";
	}
						 
	if ( count($warnings) > 0 )
	{
	
		$err_string = "<ul><li>".implode( "<li>", $warnings )."</ul>";
	
		$template->contents .= "<tr>
							  <td id='warning'>&#149;&nbsp;WARNING!</td>
							<tr>
							<td>
							  <table cellpadding='8' cellspacing='0' width='100%' align='center' border='0' id='tablewrap'>
							  <tr>
								<td>
							  <table width='100%' cellspacing='1' cellpadding='0' align='center' border='0' id='table1'>
							   <tr>
								<td>
								 <table width='100%' cellspacing='2' cellpadding='3' align='center' border='0'>
								 <tr>
								   <td>
								   		<b>The following errors must be rectified before continuing!</b>
								   		<br><br>
								   		$err_string
								   	</td>
								 </tr>
								</table>
							  </td>
							 </tr>
							</table>
						   </td>
						  </tr>
						 </table>";
	}
	else
	{
		$template->contents .= "<tr><td align='center' style='font-size:18px'><br><b><a href='upgrade4.php?a=tables'>Proceed to Step 1: Create New Tables</a> &gt;&gt;</b></td></tr>";
	}
	
	$template->output();
}



function install_error($msg="")
{
	global $std, $template, $root;
	
	$template->print_top('Warning!');
	

	
	$template->contents .= "<tr>
						  <td id='warning'>&#149;&nbsp;WARNING!</td>
						<tr>
						<td>
						  <table cellpadding='8' cellspacing='0' width='100%' align='center' border='0' id='tablewrap'>
						  <tr>
							<td>
						  <table width='100%' cellspacing='1' cellpadding='0' align='center' border='0' id='table1'>
						   <tr>
							<td>
							 <table width='100%' cellspacing='2' cellpadding='3' align='center' border='0'>
							 <tr>
							   <td>
									<b>The following errors must be rectified before continuing!</b><br>Please go back and try again!
									<br><br>
									$msg
								</td>
							 </tr>
							</table>
						  </td>
						 </tr>
						</table>
					   </td>
					  </tr>
					 </table>";
	
	
	
	$template->output();
}




//+-------------------------------------------------
// GLOBAL ROUTINES
//+-------------------------------------------------

function fatal_error($message="", $help="") {
	echo("$message<br><br>$help");
	exit;
}


//+--------------------------------------------------------------------------
// CLASSES
//+--------------------------------------------------------------------------



class template
{
	var $contents = "";
	
	function output()
	{
		echo $this->contents;
		echo "   
				 </table>
				 <br><br><center><span id='copy'>&copy 2002 Invision Board (www.invisionboard.com)</span></center>
				 
				 </body>
				 </html>";
		exit();
	}
	
	//--------------------------------------

	function print_top($title="")
	{
	
		$this->contents = "<html>
		          <head><title>Invision Upgrader :: $title </title>
		          <style type='text/css'>
		          	TABLE, TR, TD     { font-family:Verdana, Arial;font-size: 11px; color:#333333 }
					BODY      { font: 11px Verdana; color:#333333 }
					a:link, a:visited, a:active  { color:#000055 }
					a:hover                      { color:#333377;text-decoration:underline }
					
					#title  { font-size:10px; font-weight:bold; line-height:150%; color:#FFFFFF; height: 24px; background-image: url(html/sys-img/top_cell.gif); }
					#title  a:link, #title  a:visited, #title  a:active { text-decoration: underline; color : #FFFFFF; font-size:11px }
					
					#detail { font-family: Arial; font-size:11px; color: #333333 }
					
 					#large { font-family: verdana, arial; font-size:20px; color:#4C77B6; font-weight:bold; letter-spacing:-1px }
 					
					#subtitle { font-family: Verdana; font-size:22px; color:#4C77B6; font-weight:bold }
					
					#warning { font-family: Verdana; font-size:22px; color:#FF0000; font-weight:bold }
					
					#table1 {  background-color:#F1F1F1; width:100%; align:center; border:1px solid black }
					
					#tdrow1 { background-color:#F3F3EE }
					
					#tdrow2 { background-color:#EBEBE4 }
					
					#catrow  { font-size:10px; font-weight:bold; line-height:150%; color:#4C77B6; background-color:#C2CFDF; }
					
					#tablewrap {  border:1px dashed #777777; background-color:#EFEFEF }
					
					#copy { color:#555555; font-size:9px }
					
					#tdtop  { font-weight:bold; height:20px; line-height:150%; color:#FFFFFF; background-image: url(html/sys-img/top_cell.gif); }
					
					#green    { background-color: #caf2d9 }
					#red      { background-color: #f5cdcd }
					
					#button   { background-color: #4C77B6; color: #FFFFFF; font-family:Verdana, Arial; font-size:11px }
					
					#textinput { background-color: #EEEEEE; color:Ê#000000; font-family:Verdana, Arial; font-size:10px; width:100% }
					
					#dropdown { background-color: #EEEEEE; color:Ê#000000; font-family:Verdana, Arial; font-size:10px }
					
					#multitext { background-color: #EEEEEE; color:Ê#000000; font-family:Courier, Verdana, Arial; font-size:10px }
					
				  </style>
				  </head>
				 <body marginheight='0' marginwidth='0' leftmargin='0' topmargin='0' bgcolor='#FFFFFF'>
				 
				 <table width='100%' height='70' cellpadding='0' cellspacing='0' border='0'>
					<tr bgcolor='#4C77B6'>
						<td width='370' align='left' bgcolor='#4C77B6'><img src='html/sys-img/title.gif' width='370' height='70'></td>
					</tr>
				</table>
				<br>
				<table width='90%' cellpadding='0' cellspacing='0' border='0' align='center'>
				 ";
				  	   
	}


}


class installer
{

	function parse_incoming()
    {
    	global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_CLIENT_IP, $REQUEST_METHOD, $REMOTE_ADDR, $HTTP_PROXY_USER, $HTTP_X_FORWARDED_FOR;
    	$return = array();
    	
		if( is_array($HTTP_GET_VARS) )
		{
			while( list($k, $v) = each($HTTP_GET_VARS) )
			{
				//$k = $this->clean_key($k);
				if( is_array($HTTP_GET_VARS[$k]) )
				{
					while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) )
					{
						$return[$k][ $this->clean_key($k2) ] = $this->clean_value($v2);
					}
				}
				else
				{
					$return[$k] = $this->clean_value($v);
				}
			}
		}
		
		// Overwrite GET data with post data
		
		if( is_array($HTTP_POST_VARS) )
		{
			while( list($k, $v) = each($HTTP_POST_VARS) )
			{
				//$k = $this->clean_key($k);
				if ( is_array($HTTP_POST_VARS[$k]) )
				{
					while( list($k2, $v2) = each($HTTP_POST_VARS[$k]) )
					{
						$return[$k][ $this->clean_key($k2) ] = $this->clean_value($v2);
					}
				}
				else
				{
					$return[$k] = $this->clean_value($v);
				}
			}
		}
		
		return $return;
	}
    
    function clean_key($key) {
    
    	if ($key == "")
    	{
    		return "";
    	}
    	
    	$key = preg_replace( "/\.\./"           , ""  , $key );
    	$key = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $key );
    	$key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key );
    	return $key;
    }
    
    function clean_value($val) {
    
    	if ($val == "")
    	{
    		return "";
    	}
    	
    	$val = preg_replace( "/&/"         , "&amp;"         , $val );
    	$val = preg_replace( "/<!--/"      , "&#60;&#33;--"  , $val );
    	$val = preg_replace( "/-->/"       , "--&#62;"       , $val );
    	$val = preg_replace( "/<script/i"  , "&#60;script"   , $val );
    	$val = preg_replace( "/>/"         , "&gt;"          , $val );
    	$val = preg_replace( "/</"         , "&lt;"          , $val );
    	$val = preg_replace( "/\"/"        , "&quot;"        , $val );
    	$val = preg_replace( "/\|/"        , "&#124;"        , $val );
    	$val = preg_replace( "/\n/"        , "<br>"          , $val ); // Convert literal newlines
    	$val = preg_replace( "/\\\$/"      , "&#036;"        , $val );
    	$val = preg_replace( "/\r/"        , ""              , $val ); // Remove literal carriage returns
    	$val = preg_replace( "/!/"         , "&#33;"         , $val );
    	$val = preg_replace( "/'/"         , "&#39;"         , $val ); // IMPORTANT: It helps to increase sql query safety.
    	$val = stripslashes($val);                                     // Swop PHP added backslashes
    	$val = preg_replace( "/\\\/"       , "&#092;"        , $val ); // Swop user inputted backslashes
    	return $val;
    }
   
}

// Sql stuff


function run_sql($type)
{
	global $std, $template, $root, $DB;
	
	$DB->error = "";
	
	if ($type == 'tables')
	{
		$SQL = sql_tables();
	}
	else if ($type == 'alter')
	{
		$SQL = sql_alter();
	}
	else if ($type == 'index')
	{
		$SQL = sql_index();
	}
	else if ($type == 'insert')
	{
		$SQL = sql_insert();
	}
	
	//--------------------------------
		
	foreach( $SQL as $q )
	{
		$DB->query($q);
		
		if ( $DB->error != "" )
		{
			install_error($DB->error);
		}
	}
	
	return TRUE;
}





function sql_tables()
{
	$SQL = array();
	
$SQL[] = "CREATE TABLE ibf_pfields_content (
  member_id bigint(20) NOT NULL default '0',
  updated int(10) default '0',
  PRIMARY KEY  (member_id)
);";

$SQL[] = "CREATE TABLE ibf_pfields_data (
  fid smallint(5) NOT NULL auto_increment,
  ftitle varchar(200) NOT NULL default '',
  fdesc varchar(250) default '',
  fcontent text,
  ftype varchar(250) default 'text',
  freq tinyint(1) default '0',
  fhide tinyint(1) default '0',
  fmaxinput smallint(6) default '250',
  fedit tinyint(1) default '1',
  forder smallint(6) default '1',
  fshowreg tinyint(1) default '0',
  PRIMARY KEY  (fid)
);";
	
$SQL[] = "CREATE TABLE ibf_admin_logs (
  id bigint(20) NOT NULL auto_increment,
  act varchar(255) default NULL,
  code varchar(255) default NULL,
  member_id int(10) default NULL,
  ctime int(10) default NULL,
  note text,
  ip_address varchar(255) default NULL,
  PRIMARY KEY  (id)
);";

$SQL[] = "CREATE TABLE ibf_reg_antispam (
  regid varchar(32) NOT NULL default '',
  regcode varchar(8) NOT NULL default '',
  ip_address varchar(32) default NULL,
  ctime int(10) default NULL,
  PRIMARY KEY  (regid)
);";

$SQL[] = "CREATE TABLE ibf_macro (
  macro_id smallint(3) NOT NULL auto_increment,
  macro_value varchar(200) default NULL,
  macro_replace text,
  can_remove tinyint(1) default '0',
  macro_set smallint(3) default NULL,
  PRIMARY KEY  (macro_id),
  KEY macro_set (macro_set)
);";

$SQL[] = "CREATE TABLE ibf_macro_name (
  set_id smallint(3) NOT NULL default '0',
  set_name varchar(200) default NULL,
  PRIMARY KEY  (set_id)
);";

$SQL[] = "CREATE TABLE ibf_forum_tracker (
  frid bigint(20) NOT NULL auto_increment,
  member_id varchar(32) NOT NULL default '',
  forum_id int(10) NOT NULL default '0',
  start_date int(10) default NULL,
  last_sent int(10) NOT NULL default '0',
  PRIMARY KEY  (frid)
);";


$SQL[] = "CREATE TABLE ibf_calendar_events (
  eventid bigint(20) NOT NULL auto_increment,
  userid bigint(20) NOT NULL default '0',
  year int(4) NOT NULL default '2002',
  month int(2) NOT NULL default '1',
  mday int(2) NOT NULL default '1',
  title varchar(254) NOT NULL default 'no title',
  event_text text NOT NULL,
  read_perms varchar(254) NOT NULL default '*',
  unix_stamp int(10) NOT NULL default '0',
  priv_event tinyint(1) NOT NULL default '0',
  show_emoticons tinyint(1) NOT NULL default '1',
  rating smallint(2) NOT NULL default '1',
  PRIMARY KEY  (eventid),
  KEY unix_stamp (unix_stamp)
);";

$SQL[] = "CREATE TABLE ibf_skin_templates (
  suid int(10) NOT NULL auto_increment,
  set_id int(10) NOT NULL default '0',
  group_name varchar(255) NOT NULL default '',
  section_content mediumtext,
  func_name varchar(255) default NULL,
  func_data text,
  updated int(10) default NULL,
  can_remove tinyint(4) default '0',
  PRIMARY KEY  (suid)
);";

	return $SQL;
}

function sql_alter()
{
	$SQL = array();
	
	$SQL[] = "ALTER TABLE ibf_groups ADD g_max_messages INT (5) DEFAULT '50' , ADD g_max_mass_pm INT (5) DEFAULT '0';";
	$SQL[] = "ALTER TABLE ibf_groups ADD g_edit_cutoff INT (10) DEFAULT '0', ADD g_post_closed tinyint(1) default '0';";
	$SQL[] = "ALTER TABLE ibf_groups ADD g_promotion VARCHAR (10) DEFAULT '-1&-1' , ADD g_hide_from_list TINYINT (1) DEFAULT '0';";
	$SQL[] = "ALTER TABLE ibf_groups ADD g_search_flood MEDIUMINT (6) DEFAULT '20';";
	
	$SQL[] = "ALTER TABLE ibf_messages ADD cc_users TEXT;";
	$SQL[] = "ALTER TABLE ibf_messages ADD tracking TINYINT (1) DEFAULT '0';";
	$SQL[] = "ALTER TABLE ibf_messages ADD read_date INT (10);";
	
	$SQL[] = "ALTER TABLE ibf_search_results ADD member_id MEDIUMINT (10) DEFAULT '0' , ADD ip_address VARCHAR (64);";
	$SQL[] = "ALTER TABLE ibf_search_results ADD post_id TEXT;";
	$SQL[] = "ALTER TABLE ibf_search_results ADD post_max INT(10) DEFAULT '0' NOT NULL;";
	$SQL[] = "ALTER TABLE ibf_search_results CHANGE topics topic_id TEXT NOT NULL;";
	$SQL[] = "ALTER TABLE ibf_search_results CHANGE max_hits topic_max INT(3) DEFAULT '0' NOT NULL;";
	
	$SQL[] = "ALTER TABLE ibf_members DROP photo;";
	$SQL[] = "ALTER TABLE ibf_members ADD view_prefs VARCHAR (64) DEFAULT '-1&-1';";
	$SQL[] = "ALTER TABLE ibf_members ADD coppa_user TINYINT (1) DEFAULT '0';";
	$SQL[] = "ALTER TABLE ibf_members ADD mod_posts TINYINT (1) DEFAULT '0';";
	$SQL[] = "ALTER TABLE ibf_members ADD auto_track TINYINT (1) DEFAULT '0';";
	
	$SQL[] = "ALTER TABLE ibf_css ADD css_comments TEXT;";
	
	$SQL[] = "ALTER TABLE ibf_moderators ADD is_group TINYINT (1) DEFAULT '0' , ADD group_id SMALLINT (3) , ADD group_name VARCHAR (200) , ADD split_merge TINYINT (1) DEFAULT '0';";
	
	$SQL[] = "ALTER TABLE ibf_posts ADD append_edit TINYINT (1) DEFAULT '0' FIRST, ADD edit_time INT (10) AFTER append_edit;";
	$SQL[] = "ALTER TABLE ibf_posts ADD edit_name VARCHAR (255);";
	
	$SQL[] = "ALTER TABLE ibf_skins CHANGE img_id macro_id INT (10) DEFAULT '1' NOT NULL;";
	$SQL[] = "ALTER TABLE ibf_skins ADD img_dir VARCHAR (200) DEFAULT '1' AFTER css_id;";
	
	$SQL[] = "ALTER TABLE ibf_sessions DROP start_session;";
	$SQL[] = "ALTER TABLE ibf_sessions DROP member_pass;";
	$SQL[] = "ALTER TABLE ibf_sessions ADD in_forum INT (10) , ADD in_topic INT (10);";
	
	$SQL[] = "ALTER TABLE ibf_macro CHANGE macro_set macro_set SMALLINT (3) , ADD INDEX (macro_set);";
	
	$SQL[] = "ALTER TABLE ibf_polls ADD poll_question VARCHAR(255);";
	
	$SQL[] = "ALTER TABLE ibf_forums CHANGE last_id last_id INT(10) DEFAULT NULL;";
	$SQL[] = "ALTER TABLE ibf_forums CHANGE last_poster_id last_poster_id INT(10) DEFAULT NULL;";
	$SQL[] = "ALTER TABLE ibf_forums CHANGE use_attach upload_perms VARCHAR (255), CHANGE start_perms start_perms varchar(255), CHANGE reply_perms reply_perms varchar(255), CHANGE read_perms read_perms VARCHAR(255);";
	$SQL[] = "ALTER TABLE ibf_forums ADD sub_can_post TINYINT (1) DEFAULT '1';";

	return $SQL;
}

function sql_index()
{
	$SQL = array();
	
	$SQL[] = "ALTER TABLE ibf_sessions ADD INDEX(in_topic);";
	$SQL[] = "ALTER TABLE ibf_sessions ADD INDEX(in_forum);";
	$SQL[] = "ALTER TABLE ibf_skins ADD INDEX(tmpl_id);";
	$SQL[] = "ALTER TABLE ibf_skins ADD INDEX(css_id);";
	$SQL[] = "ALTER TABLE ibf_moderators ADD INDEX(forum_id);";
	$SQL[] = "ALTER TABLE ibf_members ADD INDEX(bday_month);";
	$SQL[] = "ALTER TABLE ibf_members ADD INDEX(bday_day);";
	$SQL[] = "ALTER TABLE ibf_calendar_events ADD INDEX(unix_stamp);";
	$SQL[] = "ALTER TABLE ibf_topics drop index forum_id;";
	$SQL[] = "ALTER TABLE ibf_topics add index forum_id (forum_id,approved,pinned,last_post);";
	$SQL[] = "ALTER TABLE ibf_messages ADD INDEX(vid);";
	$SQL[] = "ALTER TABLE ibf_posts DROP index forum_id;";
	$SQL[] = "ALTER TABLE ibf_posts ADD INDEX forum_id(forum_id, post_date);";
	$SQL[] = "ALTER TABLE ibf_moderators ADD INDEX(is_group);";
	$SQL[] = "ALTER TABLE ibf_moderators ADD INDEX(group_id);";


	return $SQL;
}

function sql_insert()
{
	$SQL = array();
	
	$SQL[] = "INSERT INTO ibf_groups (g_view_board, g_mem_info, g_other_topics, g_use_search, g_email_friend, g_invite_friend, g_edit_profile, g_post_new_topics, g_reply_own_topics, g_reply_other_topics, g_edit_posts, g_delete_own_posts, g_open_close_posts, g_delete_own_topics, g_post_polls, g_vote_polls, g_use_pm, g_is_supmod, g_access_cp, g_title, g_can_remove, g_append_edit, g_access_offline, g_avoid_q, g_avoid_flood, g_icon, g_attach_max, g_avatar_upload, g_calendar_post, prefix, suffix, g_max_messages, g_max_mass_pm, g_search_flood, g_edit_cutoff, g_promotion, g_hide_from_list) VALUES (0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Banned', 0, 0, 0, 0, 0, NULL, NULL, 0, 0, NULL, NULL, 50, 0, 20, 0, '-1&-1', 1);";
	$SQL[] = "insert into ibf_macro_name SET set_id=1, set_name='IBF Default Macro Set';";
	
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (1, 'A_LOCKED_B', '<img src=\'style_images/<#IMG_DIR#>/t_closed.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (2, 'A_MOVED_B', '<img src=\'style_images/<#IMG_DIR#>/t_moved.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (3, 'A_POLL', '<img src=\'style_images/<#IMG_DIR#>/t_poll.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (4, 'A_POLLONLY_B', '<img src=\'style_images/<#IMG_DIR#>/t_closed.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (5, 'A_POST', '<img src=\'style_images/<#IMG_DIR#>/t_new.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (6, 'A_REPLY', '<img src=\'style_images/<#IMG_DIR#>/t_reply.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (7, 'A_STAR', '<img src=\'style_images/<#IMG_DIR#>/pip.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (8, 'B_HOT', '<img src=\'style_images/<#IMG_DIR#>/f_hot.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (9, 'B_HOT_NN', '<img src=\'style_images/<#IMG_DIR#>/f_hot_no.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (10, 'B_LOCKED', '<img src=\'style_images/<#IMG_DIR#>/f_closed.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (11, 'B_MOVED', '<img src=\'style_images/<#IMG_DIR#>/f_moved.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (12, 'B_NEW', '<img src=\'style_images/<#IMG_DIR#>/f_norm.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (13, 'B_NORM', '<img src=\'style_images/<#IMG_DIR#>/f_norm_no.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (14, 'B_PIN', '<img src=\'style_images/<#IMG_DIR#>/f_pinned.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (15, 'B_POLL', '<img src=\'style_images/<#IMG_DIR#>/f_poll.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (16, 'B_POLL_NN', '<img src=\'style_images/<#IMG_DIR#>/f_poll_no.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (17, 'C_LOCKED', '<img src=\'style_images/<#IMG_DIR#>/bf_readonly.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (18, 'C_OFF', '<img src=\'style_images/<#IMG_DIR#>/bf_nonew.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (19, 'C_OFF_CAT', '<img src=\'style_images/<#IMG_DIR#>/bc_nonew.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (20, 'C_OFF_RES', '<img src=\'style_images/<#IMG_DIR#>/br_nonew.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (21, 'C_ON', '<img src=\'style_images/<#IMG_DIR#>/bf_new.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (22, 'C_ON_CAT', '<img src=\'style_images/<#IMG_DIR#>/bc_new.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (23, 'C_ON_RES', '<img src=\'style_images/<#IMG_DIR#>/br_new.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (24, 'F_ACTIVE', '<img src=\'style_images/<#IMG_DIR#>/user.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (25, 'F_NAV_SEP', ' ->', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (26, 'F_NAV', '<img src=\'style_images/<#IMG_DIR#>/nav.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (27, 'F_STATS', '<img src=\'style_images/<#IMG_DIR#>/stats.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (28, 'GO_LAST_ON', '', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (29, 'GO_LAST_OFF', '', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (30, 'M_ADDMEM', '<img src=\'style_images/<#IMG_DIR#>/msg_l_addmem.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (31, 'M_DELETE', '<img src=\'style_images/<#IMG_DIR#>/msg_l_delete.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (32, 'M_READ', '<img src=\'style_images/<#IMG_DIR#>/f_norm_no.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (33, 'M_REPLY', '<img src=\'style_images/<#IMG_DIR#>/msg_l_reply.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (34, 'M_UNREAD', '<img src=\'style_images/<#IMG_DIR#>/f_norm.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (35, 'P_AOL', '<img src=\'style_images/<#IMG_DIR#>/p_aim.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (36, 'P_DELETE', '<img src=\'style_images/<#IMG_DIR#>/p_delete.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (37, 'P_EDIT', '<img src=\'style_images/<#IMG_DIR#>/p_edit.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (38, 'P_EMAIL', '<img src=\'style_images/<#IMG_DIR#>/p_email.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (39, 'P_ICQ', '<img src=\'style_images/<#IMG_DIR#>/p_icq.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (40, 'P_MSG', '<img src=\'style_images/<#IMG_DIR#>/p_pm.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (41, 'P_PROFILE', '[ Profile ]', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (42, 'P_QUOTE', '<img src=\'style_images/<#IMG_DIR#>/p_quote.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (43, 'P_WEBSITE', '<img src=\'style_images/<#IMG_DIR#>/p_www.gif\' border=\'0\'  alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (44, 'CAT_IMG', '<img src=\'style_images/<#IMG_DIR#>/nav_m.gif\' border=\'0\'  alt=\'\' width=\'8\' height=\'8\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (45, 'B_HOT_DOT', '<img src=\'style_images/<#IMG_DIR#>/f_hot_dot.gif\' border=\'0\' alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (46, 'B_NEW_DOT', '<img src=\'style_images/<#IMG_DIR#>/f_norm_dot.gif\' border=\'0\' alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (47, 'B_HOT_NN_DOT', '<img src=\'style_images/<#IMG_DIR#>/f_hot_no_dot.gif\' border=\'0\' alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (48, 'B_NORM_DOT', '<img src=\'style_images/<#IMG_DIR#>/f_norm_no_dot.gif\' border=\'0\' alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (49, 'B_POLL_DOT', '<img src=\'style_images/<#IMG_DIR#>/f_poll_dot.gif\' border=\'0\' alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (50, 'B_POLL_NN_DOT', '<img src=\'style_images/<#IMG_DIR#>/f_poll_no_dot.gif\' border=\'0\' alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (51, 'NEW_POST', '<img src=\'style_images/<#IMG_DIR#>/newpost.gif\' border=\'0\'  alt=\'Goto last unread\' title=\'Goto last unread\' hspace=2>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (52, 'tbl_width', '95%', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (53, 'tbl_border', '#345487', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (55, 'P_YIM', '<img src=\'style_images/<#IMG_DIR#>/p_yim.gif\' border=\'0\' alt=\'\'>', 0, 1)";
	$SQL[] = "INSERT INTO ibf_macro (macro_id, macro_value, macro_replace, can_remove, macro_set) VALUES (56, 'P_MSN', '<img src=\'style_images/<#IMG_DIR#>/p_msn.gif\' border=\'0\' alt=\'\'>', 0, 1)";

	return $SQL;
}



?>
