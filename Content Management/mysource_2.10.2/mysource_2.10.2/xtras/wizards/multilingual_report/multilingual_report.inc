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
## $Source: /home/cvsroot/xtras/wizards/multilingual_report/multilingual_report.inc,v $
## $Revision: 1.2 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:12 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Multilingual Report Wizard
* A wizard to produce a table of the most visited pages or subpages
*
* @access public
* @package Wizards
*/
class Multilingual_Report extends Wizard {

	var $log_table = 'page';
	var $image_save_path = '';
	var $image_view_path = '';
	var $title = 'Multilingual Report';
	var $subject = 'Language';
	var $summary = array();
	var $compatible_with = array('site');
	var $parameters = array('graph_type'  => 'column',
							'layers' => array('percentage','total'),
							'tabulate' => false
							  );

	 ##############################
	# Constructor
	function Multilingual_Report (&$asset) {	
		Wizard::Wizard($asset);
		$this->add_button('Quick Report','Quick',100,array(1,));
	}

	 ########################################################
	# Changes the path where the graph images are saved
	function set_image_save_path($image_save_path) {
		$this->image_save_path = $image_save_path;
	}

	 ########################################################
	# Changes the path where the graph images are viewd
	function set_image_view_path($image_view_path) {
		$this->image_view_path = $image_view_path;
	}

	 ########################################################################################
	# Prints the interface - assumes a backend has already been setup and the header printed
	function process_wizard_web(&$backend) {
		$languages_config = &get_config('languages');

		if (!method_exists($backend,'open_field')) $backend = &$this->get_backend();
		$db = &$this->get_db();

		$this->set_image_save_path($this->caller->data_path);
		$this->set_image_view_path($this->caller->get_file_href());
	
		$graph_type = $this->parameters['graph_type'];
		$layers		= $this->parameters['layers'];
		$tabulate	= $this->parameters['tabulate'];
	
		$this->default_languages = $db->associative_array('SELECT pageid,default_languages FROM page WHERE siteid="'.$this->caller->id.'"');
		$site_default_languages = $db->single_element('SELECT default_languages FROM site WHERE siteid="'.$this->caller->id.'"');

		$total_count = 0;

		# Lets generate the number of pages published in each page
		foreach($this->default_languages as $pageid => $default_languages) {
			$total_count++;
			# Set undefined if no language is defined
			if(!$default_languages) {
				if(!$default_languages = $site_default_languages) {
					$this->summary['undefined']['total']++;
					continue;
				}
			}
			foreach(explode(',',$default_languages) as $language) {
				$this->summary[$languages_config->languages[$language]]['total']++;
			}
		}

		foreach($this->summary as $language => $data) {
			$this->summary[$language]['percentage'] = sprintf('%01.2f',($data['total'] / $total_count * 100));
		}

		$backend->open_section('Summary');
		$backend->open_field('&nbsp;');
		?>
		<table border="1" bordercolor="#000000" cellspacing="0" cellpadding="3">
			<tr>
				<td nowrap bgcolor="#DDDDDD"><b>Language</b></td>
				<td nowrap bgcolor="#DDDDDD"><b>Percentage (%)</b></td>
				<td nowrap bgcolor="#DDDDDD"><b>Total</b></td>
			</tr>
			<?
			foreach($this->summary as $language => $data) {
				?>
				<tr>
					<td nowrap><?=$language?></td>
					<td nowrap><?=$data['percentage'].'%'?></td>
					<td nowrap><?=$data['total']?></td>
				</tr>
				<?
			}
			?>
		</table>
		<?
		$backend->open_section('Graph');
		
		# Lets generate the graph
		global $SQUIZLIB_PATH;
		include_once($SQUIZLIB_PATH.'/graph/graph.inc');

		$graph = new Graph($graph_type,$this->title,'',500);
		$graph->set_subject($this->subject);
		foreach($layers as $layer) {
			$graph->add_layer($layer);
		}

		foreach($this->summary as $language => $data) {
			$graph->add_candidate($language);

			foreach($layers as $layer) {
				$graph->set_value($layer, $language, $this->summary[$language][$layer]);
			}
		}

		$graph->set_limits(0); # start the graph at 0.
		$graph->render();
		$graph->save_image($this->image_save_path.'/multilingual_report');
		$img_src = $this->image_view_path.'/multilingual_report.png';
		# make sure we refresh the image
		$img_src .= (strstr($img_src, '?') ? '&' : '?').'time='.time();
		echo('<img src="'.$img_src.'">');
		if($tabulate) {
			$backend->open_section('Tabulated Results');
			$backend->open_field('');
			echo $graph->print_table();
		}
	}
}	