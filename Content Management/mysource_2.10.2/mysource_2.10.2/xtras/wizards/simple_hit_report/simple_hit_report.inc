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
## $Source: /home/cvsroot/xtras/wizards/simple_hit_report/simple_hit_report.inc,v $
## $Revision: 1.6 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:13 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Simple Hit Report Wizard
* A wizard to produce a graph of hits, visits, 
* and unique users for a given period of time
*
* @access public
* @package Wizards
*/
class Simple_Hit_Report extends Wizard {

	var $compatible_with = array('web','site','page');
	var $parameters = array('num_results' => '20', 
							'order_results' => 'hits',
							'order_cond' => 'DESC'
							);


	 ##############################
	# Constructor
	function Simple_Hit_Report (&$asset) {	
		Wizard::Wizard($asset);
	}

	function print_wizard() {
		$config = &get_system_config();
		if ($config->statistics_reporter != 'simple') {
			$backend = &$this->get_backend();

			$heading = 'MySource ' . get_class($this) . ' Wizard';
			$heading .= '<br><span style="font-size:12px">';
			$heading .= ucfirst(get_class($this->caller));
			if (isset($this->caller->id)) {
				$heading .= ' '.$this->caller->id;
			} else {
				$heading .= ' System';
			}
			if (isset($this->caller->name)) $heading .= ' ('.$this->caller->name.')';
			$heading .= '</span>';
			$backend->set_heading($heading);
			$pset = &$this->get_pset();
			$backend->add_button($this->button_names['cancel'],'window.close();');
			$backend->print_header();
			?>
				Sorry, you are not running the system with simple statistics. You can not run this wizard.
			<?
			$backend->print_commit_button();
			$backend->print_footer();
			return;
		}
		Wizard::print_wizard();
	}

	
	 ########################################################################################
	# Prints the interface - assumes a backend has already been setup and the header printed
	function process_wizard_web(&$backend) {
		$web_system = &$this->get_web_system();

		$pageid = (int)$_GET['reset_total'];
		if ($pageid > 0) {
			$sql = "UPDATE page SET hits_running_total=0 WHERE pageid='$pageid'";
			$hits = $web_system->db->update($sql);
		}

		switch ($this->asset_type) {
			case 'web' :
				# we need to do this for all sites
				$sites = &$web_system->get_editable_sites();
				$page_index = array();
				foreach ($sites as $siteid => $name) {
					$site = $web_system->get_site($siteid);
					$page_index = array_merge($page_index,array_keys($site->get_page_index()));
				}
				break;
			case 'page' :
				$page_index = &$this->caller->get_all_subpageids();
				array_push($page_index,(int)$this->caller->id);
				break;
			case 'site' :
				$page_index = $this->caller->get_page_index();
				$page_index = array_keys($page_index);
				break;
		}

		$pageids = implode(', ',$page_index);
		$orderby = $this->parameters['order_results'];
		$limit = $this->parameters['num_results'];
		$ordercond = $this->parameters['order_cond'];
		$sql = "SELECT pageid, name, hits, hits_running_total FROM page WHERE pageid IN ($pageids) ORDER BY $orderby $ordercond LIMIT $limit";
		$hits = $web_system->db->associative_array($sql);
		$backend->open_section('Hit Statistics');
		$backend->open_field('&nbsp;');
		
		?>
		<table cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">
			<tr>
				<td bgcolor="#B1BDCD" align="right" class="backend_fineprint"><b>Lineage</b></td>
				<td bgcolor="#B1BDCD" align="left" class="backend_fineprint"><b>Page Name</b></td>
				<td bgcolor="#B1BDCD" align="center" class="backend_fineprint"><b>Hits</b></td>
			</tr>
		<?
		foreach ($hits as $pageid => $data) {
			$web = &$this->get_web_system();
			$page = &$web->get_page($pageid);
			$site = &$page->get_site();
			$lineage_array = $page->get_lineage();
			$lineage = $site->name.' > ';
			foreach ($lineage_array as $id => $name) if ($pageid != $id) $lineage .= $name.' > ';
			?>
			<tr>
				<td align="right" class="backend_fineprint"><?=$lineage?></td>
				<td align="left" class="backend_fineprint"><i><?=$data['name']?></i></td>
				<td align="center" nowrap class="backend_fineprint">&nbsp;<?=$data['hits']?> (<a href="<?=$this->get_backend_href()?>&active_step=100&reset_total=<?=$pageid?>">+ <?=$data['hits_running_total']?></a>)&nbsp;</td>
			</tr>
			<?
		}
		?>
		</table>
		<br><br>
		To reset the running total of a page, click on the running total value (in brackets) next to the hit total of the page.
		<?
	}

	/** 
	* Gets HREF of the backend (for the continual refresh of the status popup) depending on where you are
	* 
	* Returns the correct Backend HREF on a page, site or web system level.
	*
	* @returns string
	* @access public
	*/
	function get_backend_href() {
		switch($this->asset_type) {
			case 'page':
				$web_system = &$this->get_web_system();
				$asset = &$web_system->get_page($this->asset_id);
				return $asset->get_backend_href().'&PARAMETER_SCREEN=wizards&wizard_type=simple_hit_report';
				break;
			case 'site':
				$web_system = &$this->get_web_system();
				$asset = &$web_system->get_site($this->asset_id);
				return $asset->get_backend_href().'&PARAMETER_SCREEN=wizards&wizard_type=simple_hit_report';
				break;
			case 'web':
				$asset = &$this->get_web_system();
				return $asset->get_backend_href().'&web_section=wizards&wizard_type=simple_hit_report';
				break;
		}
		
	}

}