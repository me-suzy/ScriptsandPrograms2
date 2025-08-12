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
## $Source: /home/cvsroot/xtras/wizards/bmail_summary/bmail_summary.inc,v $
## $Revision: 1.6.2.1 $
## $Author: mbrydon $
## $Date: 2004/05/26 01:15:15 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* User List Wizard
* A wizard to produce list of all expired, active, locked, 
* pending or all user accounts. The results can be
* for any or all organisations.
*
* @access public
* @package Wizards
*/
class Bmail_Summary extends Wizard {

	var $compatible_with = array('users_extension_bmail_bulkmail');
	var $parameters = array('export_type' => 'csv',
							'export_show_headers' => 0,
							'export_show_fields' => array('total_sent', 'total_reads','unique_reads','total_forwards',
														 'unique_forwards','deleted', 'bounced')
							  );


	 ##############################
	# Constructor
	function Bmail_Summary (&$asset) {	
		Wizard::Wizard($asset);
		$this->add_button('Export','Export',100,2);
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
		if (($action == 'Generate') || ($action == 'Commit')) {
			$this->generate_summary();
		} else if ($action == 'Generate_finished') {
			$session = &get_mysource_session();
			$wizard_cacher_href = $session->get_var('wizard_cacher_href');
			echo status_popup('100',$this->get_backend_href().'&active_step=100',false,true,'#330099','Processing Complete','');
			exit();
		}
	}


	function get_backend_href() {
		$bmail_system = $this->caller->get_bmail_system();
		$bulkmail = $bmail_system->get_bulkmail($this->caller->id);
		return $bulkmail->get_backend_href().'&wizard_type=bmail_summary';
	}


	function generate_summary($wizard_cacher_href='') {
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
				echo status_popup(1,$process_url,false,false,'#330099','Generating Report - Please wait', "Starting to process $num_to_read b-Mail reads");
				exit();
			}

			$stats = $session->get_var('stats');

			# process $num reads
			$results = $db->associative_array("SELECT mailid, userid, email_address, reads, forwards, first_read, last_read FROM xtra_users_extension_bmail_bulkmail_reads WHERE bulkmailid=".$this->caller->id." ORDER BY mailid LIMIT $num_read, $num");
			
			foreach ($results as $data) {
				if ($data['reads'] > 0) $stats['total_reads'] += $data['reads'];
				if ($data['reads'] > 0) $stats['unique_reads']++;

				if ($data['forwards'] > 0) $stats['total_forwards'] += $data['forwards'];
				if ($data['forwards'] > 0) $stats['unique_forwards']++;

				if (!$stats['first_read']) $stats['first_read'] = $data['first_read'];
				if ($data['first_read'] < $stats['first_read']) $stats['first_read'] = $data['first_read'];
				if (!$stats['last_read']) $stats['last_read'] = $data['last_read'];
				if ($data['last_read'] > $stats['last_read']) $stats['last_read'] = $data['last_read'];
			}
			
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
				$session->unset_var('stats');
				$this->set_summary($stats);
			} else {
				$session->set_var('stats',$stats);
				sleep(2);
				$process_url = $this->get_backend_href()."&num_to_read=$num_to_read&num=$num&num_read=$num_read&action=Generate&started=1&start_time=$start_time";
				$status = "Processed $num_read reads - ".($num_to_read - $num_read).' remaining - est time: '.easy_time_total($time_left);
			}
			echo status_popup($percent,$process_url,false,false,'#330099','Generating Report - Please wait',$status);
			exit();
		}

		$stats = array();
		$stats['total_sent'] = 0;
		$stats['total_reads'] = 0;
		$stats['unique_reads'] = 0;
		$stats['total_forwards'] = 0;
		$stats['unique_forwards'] = 0;
		$stats['deletes'] = 0;
		$stats['bounces'] = 0;
		$stats['first_read'] = 0;
		$stats['last_read'] = 0;

		# get bounced mails
		$bmail_system = &$this->caller->get_bmail_system();
		if($bmail_system->is_bounce_login()) {
			$stats['bounces'] = $bmail_system->get_bounce_email_count();
		}

		list($stats['total_sent'], $stats['sent_date'], $stats['closed_date'], $stats['created_date']) = $db->single_row("SELECT num_sent, UNIX_TIMESTAMP(sent_date), UNIX_TIMESTAMP(closed_date), UNIX_TIMESTAMP(create_date) FROM xtra_users_extension_bmail_bulkmail WHERE bulkmailid=".$this->caller->id);

		$session->set_var('stats',$stats);
		$session->set_var('wizard_cacher_href',$wizard_cacher_href);

		$num_to_read = $db->single_element("SELECT COUNT(*) FROM xtra_users_extension_bmail_bulkmail_reads WHERE bulkmailid=".$this->caller->id);
		$num = 5000; # number of read to process at a time
		$process_url = $this->get_backend_href()."&num_to_read=$num_to_read&num=$num&num_read=0&action=Generate&started=0";
		echo status_popup(1,$process_url,true,false,'#330099','Generating Report - Please wait', "Starting to process $num_to_read b-Mail reads");
	}



	function set_summary($summary=array()) {
		$users = &get_users_system();
		$db = &$users->get_db();
		$db->delete("DELETE FROM xtra_wizard_bmail_summary WHERE bulkmailid=".$this->caller->id);
		$db->insert("INSERT INTO xtra_wizard_bmail_summary (bulkmailid, summary) VALUES (".$this->caller->id.",'".addslashes(serialize($summary))."')");
	}

	function get_summary() {
		$users = &get_users_system();
		$db = &$users->get_db();
		$stats = $db->single_element("SELECT summary FROM xtra_wizard_bmail_summary WHERE bulkmailid=".$this->caller->id);
		$stats = unserialize(stripslashes($stats));
		$stats['deletes'] = $stats['total_sent'] - $stats['unique_reads'] - $stats['bounces'];
		return $stats;
	}



	function process_export() {
		$export_data = $this->get_summary();

		$export_type		 = $this->parameters['export_type'];
		$export_show_headers = $this->parameters['export_show_headers'];
		$export_show_fields  = $this->parameters['export_show_fields'];

		switch($export_type) {
			case 'xml' :
				global $SQUIZLIB_PATH;
				include_once("$SQUIZLIB_PATH/xml/xml.inc");
				
				$xml = new Xml();
				
				$xml->tag_open('bmail_summary');

				if (in_array('total_sent',$export_show_fields)) {
					$xml->tag_open('total_sent');
					$xml->add_data($export_data['total_sent']);
					$xml->tag_close();
				}
				
				if (in_array('total_reads',$export_show_fields)) {
					$xml->tag_open('total_reads');
					$xml->add_data($export_data['total_reads']);
					$xml->tag_close();
				}

				if (in_array('unique_reads',$export_show_fields)) {
					$xml->tag_open('unique_reads');
					$xml->add_data($export_data['unique_reads']);
					$xml->tag_close();
				}

				if (in_array('total_forwards',$export_show_fields)) {
					$xml->tag_open('total_forwards');
					$xml->add_data($export_data['total_forwards']);
					$xml->tag_close();
				}

				if (in_array('unique_forwards',$export_show_fields)) {
					$xml->tag_open('unique_forwards');
					$xml->add_data($export_data['unique_forwards']);
					$xml->tag_close();
				}

				if (in_array('deleted',$export_show_fields)) {
					$xml->tag_open('deleted');
					$xml->add_data($export_data['deletes']);
					$xml->tag_close();
				}

				if (in_array('bounced',$export_show_fields)) {
					$xml->tag_open('bounced');
					$xml->add_data($export_data['bounces']);
					$xml->tag_close();
				}

				$xml->tag_close();
				$xml->set_filename('bmail_'.$this->id.'_summary_'.date('Y-m-d').'.xml');
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
				if (in_array('total_sent',$export_show_fields)) $csv_fields[] = $export_data['total_sent'];
				if (in_array('total_reads',$export_show_fields)) $csv_fields[] = $export_data['total_reads'];
				if (in_array('unique_reads',$export_show_fields)) $csv_fields[] = $export_data['unique_reads'];
				if (in_array('total_forwards',$export_show_fields)) $csv_fields[] = $export_data['total_forwards'];
				if (in_array('unique_forwards',$export_show_fields)) $csv_fields[] = $export_data['unique_forwards'];
				if (in_array('deleted',$export_show_fields)) $csv_fields[] = $export_data['deletes'];
				if (in_array('bounced',$export_show_fields)) $csv_fields[] = $export_data['bounces'];

				$csv_data[] = $csv_fields;
		
				$csv->set_filename('bmail_'.$this->id.'_summary_'.date('Y-m-d').'.csv');
				$csv->set_values(&$csv_data);
				$csv->export();
		}
	}


	 ########################################################################################
	# Prints the interface - assumes a backend has already been setup and the header printed
	function process_wizard_web(&$backend) {

		global $SQUIZLIB_PATH;
		include_once($SQUIZLIB_PATH.'/graph/graph.inc');

		$stats = $this->get_summary();

		$col_graph = new Graph('column','b-Mail Summary','',450);
		$col_graph->set_subject('Statistic');
		$col_graph->add_candidate('Total Sent');
		$col_graph->add_candidate('Total Reads');
		$col_graph->add_candidate('Deleted/Unread');
		$col_graph->add_candidate('Bounces');
		$col_graph->add_candidate(' ');
		$col_graph->add_candidate('Total Forwards');
		$col_graph->add_candidate('Unique Reads');
		$col_graph->add_candidate('Unique Forwards');
		$layer = 'b-Mail '.$this->caller->id;
		$col_graph->add_layer($layer,'','','2874B5');

		$col_graph->set_value($layer, 'Total Sent', $stats['total_sent']);
		$col_graph->set_value($layer, 'Total Reads', $stats['total_reads']);
		$col_graph->set_value($layer, 'Deleted/Unread', $stats['deletes']);
		$col_graph->set_value($layer, 'Unique Reads', $stats['unique_reads']);
		$col_graph->set_value($layer, 'Bounces', $stats['bounces']);
		$col_graph->set_value($layer, 'Total Forwards', $stats['total_forwards']);
		$col_graph->set_value($layer, 'Unique Forwards', $stats['unique_forwards']);
		
		$col_graph->render();
		$bmail_system = &$this->caller->get_bmail_system();
		$col_graph->save_image($bmail_system->data_path.'/bmail_col_summary_for_bmail_'.$this->caller->id);
		$col_img_src = $bmail_system->get_file_href().'bmail_col_summary_for_bmail_'.$this->caller->id.'.png';

		$pie_graph = new Graph('pie','b-Mail Reads v Deletes v Bounces','',450);
		$pie_graph->set_subject('Statistic');
		$pie_graph->add_candidate('Unique Reads','','','334A66');
		$pie_graph->add_candidate('Deleted/Unread','','','2874B5');
		$pie_graph->add_candidate('Bounces','','','3C94DE');
		$pie_graph->add_layer($layer);

		$pie_graph->set_value($layer, 'Deleted/Unread', $stats['deletes']);
		$pie_graph->set_value($layer, 'Unique Reads', $stats['unique_reads']);
		$pie_graph->set_value($layer, 'Bounces', $stats['bounces']);
		
		$pie_graph->render();
		$pie_graph->save_image($bmail_system->data_path.'/bmail_pie_summary_for_bmail_'.$this->caller->id);
		$pie_img_src = $bmail_system->get_file_href().'bmail_pie_summary_for_bmail_'.$this->caller->id.'.png';

		$backend->open_section('Summary');
		$backend->open_field('&nbsp;');
		?>
		<table border="1" bordercolor="#000000" cellspacing="0" cellpadding="3">
			<tr>
				<td colspan="3" width="100%" bgcolor="#DDDDDD">
					<table border="0" cellspacing="0" cellpadding="3" width="100%">
						<tr>
							<td align="right" nowrap>Total Number of <b>Recipients Attempted</b>:</td>
							<td align="center"><?=$stats['total_sent']?></td>
							<td align="center">&nbsp;</td>
						</tr>
						<tr>
							<td align="right" bgcolor="#F0F0F0" nowrap>Total Number of <b>Succesful Recipients</b>:</td>
							<td align="center" bgcolor="#F0F0F0"><?=($stats['total_sent'] - $stats['bounces'])?></td>
							<td align="center" bgcolor="#F0F0F0">[ <?=round((($stats['total_sent'] - $stats['bounces']) / $stats['total_sent'] * 100), 2)?>% ]</td>
						</tr>
						<tr>
							<td align="right" nowrap>Total Number of <b>Unsuccesful Recipients</b>:</td>
							<td align="center"><?=$stats['bounces']?></td>
							<td align="center">[ <?=round(($stats['bounces'] / $stats['total_sent'] * 100), 2)?>% ]</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>Number of <b>Total Reads</b>:</td>
				<td align="center"><?=$stats['total_reads']?></td>
				<td align="center" style="color:#666666"><?=round(($stats['total_reads'] / $stats['total_sent'] * 100), 2)?>%</td>
			</tr>
			<tr>
				<td align="left" nowrap>Number of <b>Unique Reads</b>:</td>
				<td align="center"><?=$stats['unique_reads']?></td>
				<td align="center"><b><?=round(($stats['unique_reads'] / $stats['total_sent'] * 100), 2)?>%</b></td>
			</tr>
			<tr>
				<td align="left" nowrap>Number of <b>Total Forwards</b> (estimate):</td>
				<td align="center"><?=$stats['total_forwards']?></td>
				<td align="center" style="color:#666666"><?=round(($stats['total_forwards'] / $stats['total_sent'] * 100), 2)?>%</td>
			</tr>
			<tr>
				<td align="left" nowrap>Number of <b>Unique Forwards</b> (estimate):</td>
				<td align="center"><?=$stats['unique_forwards']?></td>
				<td align="center" style="color:#666666"><?=round(($stats['unique_forwards'] / $stats['total_sent'] * 100), 2)?>%</td>
			</tr>
			<tr>
				<td align="left" nowrap>Number <b>Deleted / Unread</b> (estimate):</td>
				<td align="center"><?=$stats['deletes']?></td>
				<td align="center"><b><?=round(($stats['deletes'] / $stats['total_sent'] * 100), 2)?>%</b></td>
			</tr>
		</table>
		<br><br>
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
			<tr>
				<td align="left" nowrap bgcolor="#DDDDDD"><b>First Read</b>:</td>
				<td align="left" bgcolor="#DDDDDD"><?=date('D M j, Y, g:i a',mysql_to_timestamp($stats['first_read']))?></td>
			</tr>
			<tr>
				<td align="left" nowrap bgcolor="#DDDDDD"><b>Last Read</b>:</td>
				<td align="left" bgcolor="#DDDDDD"><?=date('D M j, Y, g:i a',mysql_to_timestamp($stats['last_read']))?></td>
			</tr>
		</table>
		<?
		$backend->open_section('Graph');
		$backend->open_field('&nbsp;');
		?>
		<table border="0">
			<tr>
				<td valign="top"><img src="<?=$col_img_src?>"><br><br><img src="<?=$pie_img_src?>"></td>
			</tr>
		</table>
		<?
	}

}
