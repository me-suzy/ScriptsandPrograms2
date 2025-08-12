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
## $Source: /home/cvsroot/xtras/wizards/bmail_links/bmail_links.inc,v $
## $Revision: 1.5 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:12 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Bmail Links Wizard
* A wizard to produce list of all links clicked through
* by readers of a b-Mail
*
* @access public
* @package Wizards
*/
class Bmail_Links extends Wizard {

	var $compatible_with = array('users_extension_bmail_bulkmail');
	var $parameters = array('export_type' => 'csv',
							'export_show_headers' => 0,
							'export_show_fields' => array('link','total_clicks','first_click',
														 'first_click_time_diff','last_click','last_click_time_diff')
							  );


	 ##############################
	# Constructor
	function Bmail_Links (&$asset) {	
		Wizard::Wizard($asset);
		$this->add_button('Export','Export',100,3);
	}


	function process_special_action($action) {
		$backend = &$this->get_backend();
		# Special Export Actions
		if ($action == 'Export') {
			# show the export options screen
			$backend->add_button('Export',"document.edit.action.value='Commit_Export';document.edit.submit()");
			$backend->add_button('Cancel',"document.edit.action.value='';document.edit.submit()");
			$pset = &$this->get_pset();
			$pset->print_screen($backend,'User Export');
			exit();
		} else if ($action == 'Commit_Export') {
			$pset = &$this->get_pset();
			$pset->process_screen($backend,'User Export');
			$this->process_export();
			exit();
		}

		# Special Report Generation Actions
		if ($action == 'Graph' || $_REQUEST['active_step'] == 1) {
			$this->generate_graphs();
		} else if ($action == 'Graph_finished') {
			echo status_popup('100',$this->get_backend_href().'&active_step=100',false,true,'#330099','Processing Complete','');
			exit();
		}
	}


	function get_backend_href() {
		$bmail_system = $this->caller->get_bmail_system();
		$bulkmail = $bmail_system->get_bulkmail($this->caller->id);
		return $bulkmail->get_backend_href().'&wizard_type=bmail_links';
	}


	function generate_graphs() {

		global $SQUIZLIB_PATH;
		include_once($SQUIZLIB_PATH.'/graph/graph.inc');
		
		$num_to_read = $_GET['num_to_read'];
		$num_read = $_GET['num_read'];
		$num = $_GET['num'];
		$started = $_GET['started'];
		$start_time = $_GET['start_time'];
		$action = $_GET['action'];
		$period = $_GET['period'];
		$last_period = $_GET['last_period'];
		$num_periods = $_GET['num_periods'];
		$session = &get_mysource_session();
		set_time_limit(0);

		$users = &get_users_system();
		$db = &$users->get_db();

		if (isset($num_to_read)) {

			if (!$started) {
				$now = time();
				$process_url = $this->get_backend_href()."&num_periods=$num_periods&last_period=$last_period&num=$num&period=$period&num_to_read=$num_to_read&num_read=0&action=Graph&started=1&start_time=$now";
				echo status_popup(1,$process_url,false,false,'#330099','Generating Graphs - Please wait', "Starting to process link click-throughs");
				exit();
			}

			$stats = $session->get_var('stats');
			$link = $session->get_var('link');
			
			$graph_values = unserialize($session->get_var('graph_values'));
			$stats['data']['hit_times'] = unserialize(stripslashes($stats['data']['hit_times']));

			$i = $stats['sent_date'];
			$k = ($num_read * $num);
			$max = $k + $num;
			

			if ($k < (count($stats['data']['hit_times']) -1)) {

				for ($k; $k < $max; $k++) {
					while($i <= $stats['closed_date']) {
						$i += ($period * 60 * 60);
						if (!isset($graph_values[$i])) $graph_values[$i] = 0;
						if ($k >= $max) break;
						while ($timestamp = mysql_to_timestamp($stats['data']['hit_times'][$k])) {
							if ($timestamp < 0 || $timestamp > $i) break;
							$graph_values[$i]++;
							$k++;
							if ($k >= $max) break 2;
						}
					}
					break;
				}
			
				$session->set_var('graph_values',serialize($graph_values));
			
			} else {
				$line_graph = unserialize($session->get_var('line_graph'));
				$col_graph = unserialize($session->get_var('col_graph'));
				
				$clicks = 0;
				foreach ($graph_values as $i => $c) {
					$clicks += $c;
					$name = date('d-m-y H:i:s',$i);
					$line_graph->add_candidate($name);
					$col_graph->add_candidate($name);
					$line_graph->set_value($link, $name, $clicks);
					$col_graph->set_value($link, $name, $c);
				}

				$session->set_var('line_graph',serialize($line_graph));
				$session->set_var('col_graph',serialize($col_graph));
			}
			
			
			sleep(2);


			$num_read++;
			$percent = ceil(($num_read / $num_to_read) * 100);
			$finish = (($num_read >= $num_to_read) ? 1 : 0);
			$time_diff = time() - $start_time;
			$time_per_lookup = $time_diff / $num_read;
			$time_left = $time_per_lookup * ($num_to_read - $num_read);
			if ($time_left <= 0) $time_left = 1;
			$status = '';

			if ($finish) {
				$session->set_var('period',$period);
				$process_url = $this->get_backend_href()."&action=Graph_finished&started=1";
				$status = 'Finalising Report...';
				$percent = 100;
			} else {
				$process_url = $this->get_backend_href()."&num_periods=$num_periods&num=$num&last_period=$last_period&period=$period&num_to_read=$num_to_read&num_read=$num_read&action=Graph&started=1&start_time=$start_time";
				$status = 'Processing link click-throughs - est time: '.easy_time_total($time_left);
			}
			echo status_popup($percent,$process_url,false,false,'#330099','Generating Graphs - Please wait',$status);
			exit();
		}

		$stats = array();
		$graph_values = array();

		list($stats['total_sent'], $stats['sent_date'], $stats['closed_date'], $stats['created_date']) = $db->single_row("SELECT num_sent, UNIX_TIMESTAMP(sent_date), UNIX_TIMESTAMP(closed_date), UNIX_TIMESTAMP(create_date) FROM xtra_users_extension_bmail_bulkmail WHERE bulkmailid='".$this->caller->id."'");

		if (!$_POST['chosen_link']) $_POST['chosen_link'] = $this->parameters['chosen_link'];

		$stats['data'] = $db->associative_row("SELECT link, hits, hit_times FROM xtra_users_extension_bmail_bulkmail_links WHERE link='".addslashes($_POST['chosen_link'])."' AND bulkmailid='".$this->caller->id."'");

		$period = $_POST['period'];
		if (!$period) {
			$num_periods = 10;
			if(!$period = (int)(($stats['closed_date'] - $stats['sent_date']) / $num_periods / 60 / 60)) $period = 0.1;
		} else {
			$num_periods = ceil(($stats['closed_date'] - $stats['sent_date']) / $period * 60 * 60);
		}

		$hit_times = unserialize(stripslashes($stats['data']['hit_times']));
		$stats['first_click']= $hit_times[0];
		$stats['last_click']= $hit_times[count($hit_times)-1];
		unset($hit_times);

		$line_graph = new Graph('line','Click Throughs Over Time','',700);
		$line_graph->set_subject('Time');
		$line_graph->add_layer($_POST['chosen_link'],'','','2874B5');

		$col_graph = new Graph('column',"Click Throughs Every $period Hour".(($period == 1) ? '' : 's'),'',700);
		$col_graph->set_subject('Time');
		$col_graph->add_layer($_POST['chosen_link'],'','','2874B5');


		$session->set_var('line_graph',serialize($line_graph));
		$session->set_var('col_graph',serialize($col_graph));
		$session->set_var('stats',$stats);
		$session->set_var('graph_values',serialize($graph_values));
		$session->set_var('link',$_POST['chosen_link']);
		

		$num = 100; # number of click-throughs to process at a time
		$num_to_read = (int)ceil($db->single_element("SELECT hits FROM xtra_users_extension_bmail_bulkmail_links WHERE link='".addslashes($_POST['chosen_link'])."' AND bulkmailid='".$this->caller->id."'") / $num) + 1;
		$process_url = $this->get_backend_href()."&num_periods=$num_periods&last_period=0&num=$num&period=$period&num_to_read=$num_to_read&num_read=0&action=Graph&started=0";
		echo status_popup(1,$process_url,true,false,'#330099','Generating Graphs - Please wait', "Starting to process link click-throughs");
	}


	function process_export() {
		$session = &get_mysource_session();
		$stats = $session->get_var('stats');
		$export_data = $stats['data'];

		$export_type		 = $this->parameters['export_type'];
		$export_show_headers = $this->parameters['export_show_headers'];
		$export_show_fields  = $this->parameters['export_show_fields'];

		switch($export_type) {
			case 'xml' :
				global $SQUIZLIB_PATH;
				include_once("$SQUIZLIB_PATH/xml/xml.inc");
				
				$xml = new Xml();
				
				$xml->tag_open('bmail_summary');

				if (in_array('link',$export_show_fields)) {
					$xml->tag_open('link');
					$xml->add_data($export_data['link']);
					$xml->tag_close();
				}
				
				if (in_array('total_clicks',$export_show_fields)) {
					$xml->tag_open('total_clicks');
					$xml->add_data($export_data['hits']);
					$xml->tag_close();
				}

				if (in_array('first_click',$export_show_fields)) {
					$xml->tag_open('first_click');
					$xml->add_data(date('D M j, Y, g:i a',mysql_to_timestamp($stats['first_click'])));
					$xml->tag_close();
				}

				if (in_array('first_click_time_diff',$export_show_fields)) {
					$xml->tag_open('first_click_time_diff');
					$xml->add_data('+ '.easy_time_total(mysql_to_timestamp($stats['first_click']) - $stats['sent_date'],true));
					$xml->tag_close();
				}

				if (in_array('last_click',$export_show_fields)) {
					$xml->tag_open('last_click');
					$xml->add_data(date('D M j, Y, g:i a',mysql_to_timestamp($stats['last_click'])));
					$xml->tag_close();
				}

				if (in_array('last_click_time_diff',$export_show_fields)) {
					$xml->tag_open('last_click_time_diff');
					$xml->add_data('+ '.easy_time_total(mysql_to_timestamp($stats['last_click']) - $stats['sent_date'],true));
					$xml->tag_close();
				}

				$xml->tag_close();
				$xml->set_filename('bmail_'.$this->id.'link_summary_'.$data['link'].'_'.date('Y-m-d').'.xml');
				$xml->export();
				break;

			default :
				global $SQUIZLIB_PATH;
				include_once("$SQUIZLIB_PATH/csv/csv.inc");

				$fields = array();
				foreach ($export_show_fields as $field) $fields[] = ucwords(str_replace('_',' ',$field));

				$csv = new Csv();

				if($export_show_headers) {
					reset($fields);
					$csv->set_field_headers(&$fields);
				}

				$csv_fields = array();
				if (in_array('link',$export_show_fields)) $csv_fields[] = $export_data['link'];
				if (in_array('total_clicks',$export_show_fields)) $csv_fields[] = $export_data['hits'];
				if (in_array('first_click',$export_show_fields)) $csv_fields[] = date('D M j, Y, g:i a',mysql_to_timestamp($stats['first_click']));
				if (in_array('first_click_time_diff',$export_show_fields)) $csv_fields[] = '+ '.easy_time_total(mysql_to_timestamp($stats['first_click']) - $stats['sent_date'],true);
				if (in_array('last_click',$export_show_fields)) $csv_fields[] = date('D M j, Y, g:i a',mysql_to_timestamp($stats['last_click']));
				if (in_array('last_click_time_diff',$export_show_fields)) $csv_fields[] = '+ '.easy_time_total(mysql_to_timestamp($stats['last_click']) - $stats['sent_date'],true);

				$csv_data[] = $csv_fields;
		
				$csv->set_filename('bmail_'.$this->id.'link_summary_'.$data['link'].'_'.date('Y-m-d').'.csv');
				$csv->set_values(&$csv_data);
				$csv->export();
		}
	}


	function get_link_options() {
		$users = &get_users_system();
		$db = &$users->get_db();
		$results = $db->single_column("SELECT link FROM xtra_users_extension_bmail_bulkmail_links WHERE bulkmailid='".$this->caller->id."' ORDER BY link");
		$retval = array();

		if (!count($results)) {
			$stats  = $db->single_element("SELECT summary FROM xtra_wizard_bmail_links WHERE bulkmailid='".$this->caller->id."'");
			$stats  = unserialize(stripslashes($stats));
			foreach ($stats['data'] as $link => $data) $retval[$link] = $link;
		} else {
			foreach ($results as $link) $retval[$link] = $link;
		}

		return $retval;
	}


	 ########################################################################################
	# Prints the interface - assumes a backend has already been setup and the header printed
	function process_wizard_web(&$backend) {
		global $SQUIZLIB_PATH;
		include_once($SQUIZLIB_PATH.'/graph/graph.inc');

		$session = &get_mysource_session();
		$stats = $session->get_var('stats');
		$period = $session->get_var('period');
		$line_graph = unserialize($session->get_var('line_graph'));
		$col_graph = unserialize($session->get_var('col_graph'));

		$link = $this->parameters['chosen_link'];
		$stats['data']['hit_times'] = unserialize(stripslashes($stats['data']['hit_times']));

		$line_graph->render();
		$bmail_system = &$this->caller->get_bmail_system();
		$line_graph->save_image($bmail_system->data_path.'/bmail_line_summary_for_'.$period.'hour_bmail_'.$this->caller->id.'_links');
		$line_img_src = $bmail_system->get_file_href().'bmail_line_summary_for_'.$period.'hour_bmail_'.$this->caller->id.'_links.png';

		$col_graph->render();
		$col_graph->save_image($bmail_system->data_path.'/bmail_col_summary_for_'.$period.'hour_bmail_'.$this->caller->id.'_links');
		$col_img_src = $bmail_system->get_file_href().'bmail_col_summary_for_'.$period.'hour_bmail_'.$this->caller->id.'_links.png';

		$backend->open_section('Link Report');
		$backend->open_field('&nbsp;');
		?>
		<table border="1" bordercolor="#000000" cellspacing="0" cellpadding="3">
			<tr>
				<td align="left" nowrap bgcolor="#DDDDDD"><b>Period Open</b>:</td>
				<td align="left" bgcolor="#DDDDDD" nowrap><?=easy_time_total($stats['closed_date'] - $stats['sent_date'], true)?></td>
			</tr>
			<tr>
				<td align="left" nowrap>Date b-Mail <b>Created</b>:</td>
				<td align="left"><?=date('D M j, Y, g:i a',$stats['created_date'])?></td>
			</tr>
			<tr>
				<td align="left" nowrap>Date b-Mail <b>Sent</b>:</td>
				<td align="left"><?=date('D M j, Y, g:i a',$stats['sent_date'])?></td>
			</tr>
			<tr>
				<td align="left" nowrap>Date b-Mail <b>Closed</b>:</td>
				<td align="left"><?=date('D M j, Y, g:i a',$stats['closed_date'])?></td>
			</tr>
		</table>
		<br><br>
		<table border="1" bordercolor="#000000" cellspacing="0" cellpadding="3">
			<tr>
				<td colspan="4" width="100%" bgcolor="#DDDDDD">
					<table border="0" cellspacing="0" cellpadding="3" width="100%">
						<tr>
							<td align="right" bgcolor="#F0F0F0" nowrap colspan="2"><b>Click Through</b> report for <b><?=$link?></b></td>
						</tr>				
						<tr>
							<td align="right" nowrap>Number of <b>Clicks Throughs</b>:</td>
							<td align="center"><?=$stats['data']['hits']?></td>
						</tr>	
					</table>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap valign="top">First Click:</td>
				<td align="left" valign="top" nowrap><?=date('D M j, Y, g:i a',mysql_to_timestamp($stats['first_click']))?></td>
				<td align="right" valign="top">+ <?=easy_time_total(mysql_to_timestamp($stats['first_click']) - $stats['sent_date'],true)?></td>
			</tr>
			<tr>
				<td align="left" nowrap valign="top">Last Click:</td>
				<td align="left" valign="top" nowrap><?=date('D M j, Y, g:i a',mysql_to_timestamp($stats['last_click']))?></td>
				<td align="right" valign="top">+ <?=easy_time_total(mysql_to_timestamp($stats['last_click']) - $stats['sent_date'],true)?></td>
			</tr>
		</table>
		<?
		$backend->open_section('Graph');
		$backend->open_field('&nbsp;');
		?>
		<table border="0">
			<tr>
				<td>
				Show click throughs ever <input type="text" name="period" value="<?=$period?>" size="3"> hour(s) <input type="button" onclick="Javascript:document.edit.active_step.value=1;document.edit.submit();return false;" value=" Update Graphs "><br><br>
				<img src="<?=$line_img_src?>&t=<?=time()?>" width="700"><br>
				<img src="<?=$col_img_src?>&t=<?=time()?>"  width="700"></td>
			</tr>
		</table>
		<?
	}

}