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
## $Source: /home/cvsroot/xtras/wizards/bmail_links_summary/bmail_links_summary.inc,v $
## $Revision: 1.5.2.1 $
## $Author: mbrydon $
## $Date: 2004/05/26 01:11:10 $
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
class Bmail_Links_Summary extends Wizard {

	var $compatible_with = array('users_extension_bmail_bulkmail');
	var $parameters = array('export_type' => 'csv',
							'export_show_headers' => 0,
							'export_show_fields' => array('link','clicks','clicks_percent',
														 'first_click','last_click')
							  );


	 ##############################
	# Constructor
	function Bmail_Links_Summary (&$asset) {	
		Wizard::Wizard($asset);
		$this->add_button('Export','Export',100,4);
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
		if ($action == 'Generate' || $_REQUEST['active_step'] == 1) {
			$this->create_summary();
		} else if ($action == 'Generate_finished') {
			echo status_popup('100',$this->get_backend_href().'&active_step=2',false,true,'#330099','Processing Complete','');
			exit();
		}

		# Special Graph Generation Actions
		if ($action == 'Graph' || $_REQUEST['active_step'] == 2) {
			$this->generate_graphs();
		} else if ($action == 'Graph_finished') {
			echo status_popup('100',$this->get_backend_href().'&active_step=100',false,true,'#330099','Processing Complete','');
			exit();
		}
	}


	function get_backend_href() {
		$bmail_system = $this->caller->get_bmail_system();
		$bulkmail = $bmail_system->get_bulkmail($this->caller->id);
		return $bulkmail->get_backend_href().'&wizard_type=bmail_links_summary';
	}



	function create_summary() {
		
		$num_to_read = $_GET['num_to_read'];
		$num = $_GET['num'];
		$num_read = $_GET['num_read'];
		$started = $_GET['started'];
		$start_time = $_GET['start_time'];
		$action = $_GET['action'];
		$session = &get_mysource_session();
		set_time_limit(0);

		$users = &get_users_system();
		$db = &$users->get_db();

		if (isset($num) && isset($num_to_read)) {

			if (!$started) {
				$now = time();
				$process_url = $this->get_backend_href()."&num_to_read=$num_to_read&num=$num&num_read=0&action=Generate&started=1&start_time=$now";
				echo status_popup(1,$process_url,false,false,'#330099','Generating Report - Please wait', "Starting to process $num_to_read b-Mail links");
				exit();
			}

			$stats = $session->get_var('stats');

			# process $num links
			$data = $db->associative_row("SELECT link, hits, hit_times FROM xtra_users_extension_bmail_bulkmail_links WHERE link<>'' AND bulkmailid='".$this->caller->id."' ORDER BY link LIMIT $num_read, 1");

			
			if ($data['hits'] > 0) $stats['total_clicks'] += $data['hits'];
			
			if ($data['hits'] > $stats['most_clicks']['max_clicks']) {
				$stats['most_clicks']['links'] = array();
				$stats['most_clicks']['max_clicks'] = $data['hits'];
			}
			if ($data['hits'] >= $stats['most_clicks']['max_clicks']) {
				$stats['most_clicks']['links'][] = $data['link'];
			}

			if ($data['hits'] < $stats['least_clicks']['min_clicks']) {
				$stats['least_clicks']['links'] = array();
				$stats['least_clicks']['min_clicks'] = $data['hits'];
			}
			if ($data['hits'] <= $stats['least_clicks']['min_clicks']) {
				$stats['least_clicks']['links'][] = $data['link'];
			}	
		
			$stats['links'][$data['link']] = $data;

			$hit_times = unserialize(stripslashes($data['hit_times']));
			$stats['links'][$data['link']]['first_click']= $hit_times[0];
			$stats['links'][$data['link']]['last_click']= $hit_times[count($hit_times)-1];
			unset($hit_times);

			$session->set_var('stats',$stats);
			sleep(2);
		


			$num_read += $num;
			$percent = ceil(($num_read / $num_to_read) * 100);
			$finish = (($num_read >= $num_to_read) ? 1 : 0);
			$time_diff = time() - $start_time;
			$time_per_lookup = $time_diff / $num_read;
			$time_left = $time_per_lookup * ($num_to_read - $num_read);
			if ($time_left <= 0) $time_left = 1;
			$status = '';

			if ($finish) {
				$process_url = $this->get_backend_href()."&action=Generate_finished&started=1";
				$status = 'Finalising Report...';
				$percent = 100;
			} else {
				$process_url = $this->get_backend_href()."&num_to_read=$num_to_read&num=$num&num_read=$num_read&action=Generate&started=1&start_time=$start_time";
				$status = "Processed $num_read links - ".($num_to_read - $num_read).' remaining - est time: '.easy_time_total($time_left);
			}
			echo status_popup($percent,$process_url,false,false,'#330099','Generating Report - Please wait',$status);
			exit();
		}

		$stats = array();
		$stats['most_clicks']['links'] = array();
		$stats['most_clicks']['max_clicks'] = 0;
		$stats['least_clicks']['links'] = array();
		$stats['least_clicks']['min_clicks'] = 1000000000000;
		$stats['links'] = array();

		list($stats['total_sent'], $stats['sent_date'], $stats['closed_date'], $stats['created_date']) = $db->single_row("SELECT num_sent, UNIX_TIMESTAMP(sent_date), UNIX_TIMESTAMP(closed_date), UNIX_TIMESTAMP(create_date) FROM xtra_users_extension_bmail_bulkmail WHERE bulkmailid='".$this->caller->id."'");

		$session->set_var('stats',$stats);

		$num_to_read = $db->single_element("SELECT COUNT(*) FROM xtra_users_extension_bmail_bulkmail_links WHERE link<>'' AND bulkmailid='".$this->caller->id."'");
		$num = 1; # number of links to process at a time
		$process_url = $this->get_backend_href()."&num_to_read=$num_to_read&num=$num&num_read=0&action=Generate&started=0";
		echo status_popup(1,$process_url,true,false,'#330099','Generating Report - Please wait', "Starting to process $num_to_read b-Mail links");
	}




	function generate_graphs() {

		global $SQUIZLIB_PATH;
		include_once($SQUIZLIB_PATH.'/graph/graph.inc');
		
		$num_to_read = $_GET['num_to_read'];
		$num_read = $_GET['num_read'];
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
				$process_url = $this->get_backend_href()."&num_periods=$num_periods&last_period=$last_period&period=$period&num_to_read=$num_to_read&num_read=0&action=Graph&started=1&start_time=$now";
				echo status_popup(1,$process_url,false,false,'#330099','Generating Graphs - Please wait', "Starting to process $num_to_read b-Mail links");
				exit();
			}

			$stats = $session->get_var('stats');
			
			$line_graph = unserialize($session->get_var('line_graph'));
			$col_graph = unserialize($session->get_var('col_graph'));

			$x = 0;
			foreach ($stats['links'] as $link => $data) {
				$x++;
				if ($x <= $num_read) continue;
				if ($x > $num_read + 1) break;
				if (trim($link) == '') continue;
				
				$data['hit_times'] = unserialize(stripslashes($data['hit_times']));
				sort($data['hit_times']);

				if (!$last_period) {
					$line_graph->add_layer($link);
					$col_graph->add_layer($link);
				}

				$hour = 0;
				$graph_values = array();
				$count = count($data['hit_times']);
				$i = $stats['sent_date'];

				for ($k = 0; $k < $count; $k++) {
					while($i <= $stats['closed_date']) {
						$i += ($period * 60 * 60);
						$hour++;
						$graph_values[$i] = 0;
						if ($k > $count) continue;
						while (mysql_to_timestamp($data['hit_times'][$k]) < $i) {
							$k++;
							if ($k > $count) break;
							$graph_values[$i]++;
						}
					}
				}

				$clicks = 0;
				foreach ($graph_values as $i => $c) {
					$clicks += $c;
					$name = date('d-m-y H:i:s',$i);
					if (trim($name) == '') continue;
					if (!$num_read) {
						$line_graph->add_candidate($name);
						$col_graph->add_candidate($name);
					}
					$line_graph->set_value($link, $name, $clicks);
					$col_graph->set_value($link, $name, $c);
				}
			}

			$session->set_var('stats',$stats);
			$session->set_var('line_graph',serialize($line_graph));
			$session->set_var('col_graph',serialize($col_graph));
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
				$process_url = $this->get_backend_href()."&num_periods=$num_periods&last_period=$last_period&period=$period&num_to_read=$num_to_read&num_read=$num_read&action=Graph&started=1&start_time=$start_time";
				$status = "Processed $num_read links - ".($num_to_read - $num_read).' remaining - est time: '.easy_time_total($time_left);
			}
			echo status_popup($percent,$process_url,false,false,'#330099','Generating Graphs - Please wait',$status);
			exit();
		}

		$stats = $session->get_var('stats');

		$period = $_POST['period'];
		if (!$period) {
			$num_periods = 10;
			$period = (int)(($stats['closed_date'] - $stats['sent_date']) / $num_periods / 60 / 60);
			if (!$period) $period = 0.5;
		} else {
			$num_periods = ceil(($stats['closed_date'] - $stats['sent_date']) / $period * 60 * 60);
		}

		$line_graph = new Graph('line','Click Throughs Over Time','',700);
		$line_graph->set_subject('Time');

		$col_graph = new Graph('column',"Click Throughs Every $period Hour".(($period == 1) ? '' : 's'),'',700);
		$col_graph->set_subject('Time');

		$session->set_var('line_graph',serialize($line_graph));
		$session->set_var('col_graph',serialize($col_graph));

		$num_to_read = $db->single_element("SELECT COUNT(*) FROM xtra_users_extension_bmail_bulkmail_links WHERE link<>'' AND bulkmailid='".$this->caller->id."'");
		$process_url = $this->get_backend_href()."&num_periods=$num_periods&last_period=0&period=$period&num_to_read=$num_to_read&num_read=0&action=Graph&started=0";
		echo status_popup(1,$process_url,true,false,'#330099','Generating Graphs - Please wait', "Starting to process $num_to_read b-Mail links");
	}


	function process_export() {
		$session = &get_mysource_session();
		$stats = $session->get_var('stats');
		$export_data = $stats['links'];

		$export_type		 = $this->parameters['export_type'];
		$export_show_headers = $this->parameters['export_show_headers'];
		$export_show_fields  = $this->parameters['export_show_fields'];

		switch($export_type) {
			case 'xml' :
				global $SQUIZLIB_PATH;
				include_once("$SQUIZLIB_PATH/xml/xml.inc");
				
				$xml = new Xml();
				
				$xml->tag_open('link_summary');

				foreach ($export_data as $data) {
					$xml->tag_open('link');
					$data['hit_times'] = unserialize(stripslashes($data['hit_times']));

					if (in_array('link',$export_show_fields)) {
						$xml->tag_open('link');
						$xml->add_data(addslashes($data['link']));
						$xml->tag_close();
					}

					if (in_array('clicks',$export_show_fields)) {
						$xml->tag_open('clicks');
						$xml->add_data($data['hits']);
						$xml->tag_close();
					}

					if (in_array('clicks_percent',$export_show_fields)) {
						$xml->tag_open('clicks_percent');
						$xml->add_data(round($data['hits'] / $stats['total_clicks'] * 100, 2).'%');
						$xml->tag_close();
					}

					if (in_array('first_click',$export_show_fields)) {
						$xml->tag_open('first_click');
						$xml->add_data(date('D M j, Y, g:i a',mysql_to_timestamp($data['first_click'])));
						$xml->tag_close();
					}

					if (in_array('last_click',$export_show_fields)) {
						$xml->tag_open('last_click');
						$xml->add_data(date('D M j, Y, g:i a',mysql_to_timestamp($data['last_click'])));
						$xml->tag_close();
					}

					$xml->tag_close();
				}

				$xml->tag_close();
				$xml->set_filename('bmail_'.$this->id.'_link_summary_'.date('Y-m-d').'.xml');
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

				foreach ($export_data as $data) {
					$data['hit_times'] = unserialize(stripslashes($data['hit_times']));

					$csv_fields = array();
					if (in_array('link',$export_show_fields)) $csv_fields[] = $data['link'];
					if (in_array('clicks',$export_show_fields)) $csv_fields[] = $data['hits'];
					if (in_array('clicks_percent',$export_show_fields)) $csv_fields[] = round($data['hits'] / $stats['total_clicks'] * 100, 2).'%';
					if (in_array('first_click',$export_show_fields)) $csv_fields[] = date('D M j, Y, g:i a',mysql_to_timestamp($data['first_click']));
					if (in_array('last_click',$export_show_fields)) $csv_fields[] = date('D M j, Y, g:i a',mysql_to_timestamp($data['last_click']));

					$csv_data[] = $csv_fields;
				}
		
				$csv->set_filename('bmail_'.$this->id.'_link_summary_'.date('Y-m-d').'.csv');
				$csv->set_values(&$csv_data);
				$csv->export();
		}
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

		$line_graph->render();
		$bmail_system = &$this->caller->get_bmail_system();
		$line_graph->save_image($bmail_system->data_path.'/bmail_line_summary_for_'.$period.'hour_bmail_'.$this->caller->id.'_link_summary');
		$line_img_src = $bmail_system->get_file_href().'bmail_line_summary_for_'.$period.'hour_bmail_'.$this->caller->id.'_link_summary.png';

		$col_graph->render();
		$col_graph->save_image($bmail_system->data_path.'/bmail_col_summary_for_'.$period.'hour_bmail_'.$this->caller->id.'_link_summary');
		$col_img_src = $bmail_system->get_file_href().'bmail_col_summary_for_'.$period.'hour_bmail_'.$this->caller->id.'_link_summary.png';

		$backend->open_section('Link Summary');
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
							<td align="right" nowrap>Total Number of <b>Clicks Throughs</b>:</td>
							<td align="center"><?=$stats['total_clicks']?></td>
						</tr>
						<tr>
							<td align="right" bgcolor="#F0F0F0" nowrap>Total Number of <b>Unique Links Clicked</b>:</td>
							<td align="center" bgcolor="#F0F0F0"><?=count($stats['links'])?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap valign="top">Most Popular Link(s):</td>
				<td align="left" valign="top" nowrap><?=implode('<br>',$stats['most_clicks']['links'])?></td>
				<td align="center" valign="top"><?=$stats['most_clicks']['max_clicks']?></td>
				<td align="center" valign="top"><b><?=round($stats['most_clicks']['max_clicks'] / $stats['total_clicks'] * 100, 2)?>%</b></td>
			</tr>
			<tr>
				<td align="left" nowrap valign="top">Least Popular Link(s):</td>
				<td align="left" valign="top" nowrap><?=implode('<br>',$stats['least_clicks']['links'])?></td>
				<td align="center" valign="top"><?=$stats['least_clicks']['min_clicks']?></td>
				<td align="center" valign="top"><b><?=round($stats['least_clicks']['min_clicks'] / $stats['total_clicks'] * 100, 2)?>%</b></td>
			</tr>
		</table>
		<br><br>
		<table border="1" bordercolor="#000000" cellspacing="0" cellpadding="3">
			<tr>
				<td bgcolor="#DDDDDD" align="left"><b>Link</b></td>
				<td bgcolor="#DDDDDD" align="center"><b>Clicks [ % ]</b></td>
				<td bgcolor="#DDDDDD" align="center"><b>First Click</b></td>
				<td bgcolor="#DDDDDD" align="center"><b>Last Click</b></td>
			</tr>
			<?
			foreach ($stats['links'] as $link => $data) {
				?>
				<tr>
					<td><?=$link?></td>
					<td align="center"><?=$data['hits']?> [ <?=round($data['hits'] / $stats['total_clicks'] * 100, 2)?>% ]</td>
					<td align="center"><?=date('D M j, Y, g:i a',mysql_to_timestamp($data['first_click']))?></td>
					<td align="center"><?=date('D M j, Y, g:i a',mysql_to_timestamp($data['last_click']))?></td>
				</tr>
				<?
			}
			?>
		</table>
		<?
		$backend->open_section('Graph');
		$backend->open_field('&nbsp;');
		?>
		<table border="0">
			<tr>
				<td>
				Show click throughs every <input type="text" name="period" value="<?=$period?>" size="3"> hour(s) <input type="button" onclick="Javascript:document.edit.active_step.value=2;document.edit.submit();return false;" value=" Update Graphs "><br><br>
				<img src="<?=$line_img_src?>&t=<?=time()?>" width="700"><br>
				<img src="<?=$col_img_src?>&t=<?=time()?>"  width="700"></td>
			</tr>
		</table>
		<?
	}

}
