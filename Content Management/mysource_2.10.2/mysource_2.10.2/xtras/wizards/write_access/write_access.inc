<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: include/report.inc
## Desc: Parent class for system reporting
## $Source: /home/cvsroot/xtras/wizards/write_access/write_access.inc,v $
## $Revision: 1.3 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:13 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Write Access Wizard
* A wizard to produce a table of all site admins & editors, page admins & editors
*
* @access public
* @package Wizards
*/
class Write_Access extends Wizard {

	var $restrict_cond = '1=1';
	var $report_desc = 'List of all write access permissions';
	var $log_table = '';
	var $idfields = array("sitename"=>"siteid", "pagename"=>"pageid", "firstname"=>"userid", "surname"=>"0");
	var $compatible_with = array('web');
	var $parameters = array();


	 ##############################
	# Constructor
	function Write_Access (&$asset) {	
		Wizard::Wizard($asset);
	}


	 ########################################################################################
	# Prints the interface - assumes a backend has already been setup and the header printed
	function process_wizard_web(&$backend) {

		$web_system = &$this->get_web_system();
		$db = &$this->get_db();
		global $EDIT_DIR;
		
		$access_type = $this->parameters['access_type'];

		$backend->open_section("$this->report_desc ");
		echo "<br>";
		$query = "";
		$heading = "";
		$cols = "";

		foreach ($access_type as $type)	{
			switch($type)	{
				case "sa":	
							$query = "SELECT s.name as sitename, u.firstname, u.surname, s.siteid, u.userid FROM site as s, user as u, site_admin as sa WHERE sa.siteid = s.siteid AND sa.userid = u.userid ORDER BY s.siteid DESC";
							$heading = "Site Administrators";
							$cols = "3";
							$col_heading_1 = array("Site Name","sitename");
							$col_heading_2 = array("Admin's First Name","firstname");
							$col_heading_3 = array("Admin's Surname","surname");
					break;
				case "se":	
							$query = "SELECT s.name as sitename, u.firstname, u.surname, s.siteid, u.userid FROM site as s, user as u, site_editor as sa WHERE sa.siteid = s.siteid AND sa.userid = u.userid ORDER BY s.siteid DESC";
							$heading = "Site Editors";
							$cols = "3";
							$col_heading_1 = array("Site Name","sitename");
							$col_heading_2 = array("Editor's First Name","firstname");
							$col_heading_3 = array("Editor's Surname","surname");
					break;
				case "pa":	
							$query = "SELECT s.name as sitename, p.name as pagename, u.firstname, u.surname, s.siteid, p.pageid, u.userid FROM site as s, user as u, page_admin pa, page as p WHERE pa.pageid = p.pageid AND pa.userid = u.userid AND p.siteid = s.siteid ORDER BY s.siteid DESC";
							$heading = "Page Administrators";
							$cols = "4";
							$col_heading_1 = array("Site Name","sitename");
							$col_heading_2 = array("Page Name","pagename");
							$col_heading_3 = array("Admin's First Name","firstname");
							$col_heading_4 = array("Admin's Surname","surname");
					break;
				case "pe":	
							$query = "SELECT s.name as sitename, p.name as pagename, u.firstname, u.surname, s.siteid, p.pageid, u.userid FROM site as s, user as u, page_editor pa, page as p WHERE pa.pageid = p.pageid AND pa.userid = u.userid AND p.siteid = s.siteid ORDER BY s.siteid DESC";
							$heading = "Page Editors";
							$cols = "4";
							$col_heading_1 = array("Site Name","sitename");
							$col_heading_2 = array("Page Name","pagename");
							$col_heading_3 = array("Editor's First Name","firstname");
							$col_heading_4 = array("Editor's Surname","surname");
					break;
			}
			$entry = $db->query($query);
			$header = $heading." (".$db->numrows($entry).")";
			$backend->open_section($header);
			if ($db->numrows($entry))	{
				?>
				<table border="1" width="100%" cellpadding=4 align=center bgcolor="#F0F0F0">
					<tr>
					<?	
					for ($i=1;$i<=$cols;$i++)	{
						echo "<td align=center><b>".${'col_heading_'.$i}[0]."</b></td>";
					}
					?>
					</tr>
				<?
					while ($result = mysql_fetch_array($entry))	{
						?><tr>
						<?	
						for ($i=1;$i<=$cols;$i++)	{
							$tmp = ${'col_heading_'.$i}[1];
							$idfield = $this->idfields[$tmp];
							//get right link in the backend
							if ($tmp == "sitename")
								$link = $EDIT_DIR."/site.php?s=".$result[siteid];
							elseif ($tmp == "firstname" || $tmp == "surname")
								$link = $EDIT_DIR."/user.php?userid=".$result[userid];
							elseif ($tmp == "pagename")
								$link = $EDIT_DIR."/page.php?p=".$result[pageid];

							?>
							<td>&nbsp;<a href="#" onclick="Javascript:window.opener.location='<?=$link?>'; return false;"><?=$result[$tmp]?></a><? echo (($idfield) ? "<span class=smallprint> (ID: ".$result[$idfield].")</span>" : ""); ?>&nbsp;</td>
							<?
						}
					}
				?></table><?
			}
		}
	}
}