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
## $Source: /home/cvsroot/xtras/wizards/bmail_user_summary/bmail_user_summary.inc,v $
## $Revision: 1.4.2.1 $
## $Author: mbrydon $
## $Date: 2004/05/26 01:17:25 $
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
class Bmail_User_Summary extends Wizard {

	var $compatible_with = array('users_extension_bmail_bulkmail');
	var $parameters = array('export_type' => 'csv',
							'export_show_headers' => 0,
							'export_show_fields' => array('email_address','userid','reads','reads_percent','forwards',
														 'forwards_percent','first_read','last_read',
														 'mail_client','ip')
							);


	 ##############################
	# Constructor
	function Bmail_User_Summary (&$asset) {	
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
		if (($action == 'Generate') || ($action == "Commit")) {
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
		return $bulkmail->get_backend_href().'&wizard_type=bmail_user_summary';
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
				echo status_popup(1,$process_url,false,false,'#330099','Generating Report - Please wait', "Starting to process $num_to_read b-Mail recipients");
				exit();
			}

			$stats = $session->get_var('stats');

			# process $num reads
			$data = $db->associative_array("SELECT mailid, email_address, userid, reads, forwards, first_read, last_read, mail_client, ip FROM xtra_users_extension_bmail_bulkmail_reads WHERE bulkmailid='".$this->caller->id."' ORDER BY reads DESC LIMIT $num_read, $num");
			$stats['data'] = array_merge($stats['data'], $data);

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
				$status = "Processed $num_read recipients - ".($num_to_read - $num_read).' remaining - est time: '.easy_time_total($time_left);
			}
			echo status_popup($percent,$process_url,false,false,'#330099','Generating Report - Please wait',$status);
			exit();
		}

		$stats = array();

		list($stats['total_sent'], $stats['sent_date'], $stats['closed_date'], $stats['created_date']) = $db->single_row("SELECT num_sent, UNIX_TIMESTAMP(sent_date), UNIX_TIMESTAMP(closed_date), UNIX_TIMESTAMP(create_date) FROM xtra_users_extension_bmail_bulkmail WHERE bulkmailid='".$this->caller->id."'");

		list($stats['total_reads'], $stats['total_forwards']) = $db->single_row("SELECT SUM(reads), SUM(forwards) FROM xtra_users_extension_bmail_bulkmail_reads WHERE bulkmailid='".$this->caller->id."'");

		$session->set_var('stats',$stats);
		$session->set_var('wizard_cacher_href',$wizard_cacher_href);

		$num_to_read = $db->single_element("SELECT COUNT(*) FROM xtra_users_extension_bmail_bulkmail_reads WHERE bulkmailid='".$this->caller->id."'");
		if ($num_to_read > 2000) $num_to_read = 2000;
		$num = 500; # number of read to process at a time
		$process_url = $this->get_backend_href()."&num_to_read=$num_to_read&num=$num&num_read=0&action=Generate&started=0";
		echo status_popup(1,$process_url,true,false,'#330099','Generating Report - Please wait', "Starting to process $num_to_read b-Mail recipients");
	}


	function set_summary($summary=array()) {
		$users = &get_users_system();
		$db = &$users->get_db();
		$db->delete("DELETE FROM xtra_wizard_bmail_user_summary WHERE bulkmailid='".$this->caller->id."'");
		$db->insert("INSERT INTO xtra_wizard_bmail_user_summary (bulkmailid, summary) VALUES ('".$this->caller->id."','".addslashes(serialize($summary))."')");
	}

	function get_summary() {
		$users = &get_users_system();
		$db = &$users->get_db();
		$stats = $db->single_element("SELECT summary FROM xtra_wizard_bmail_user_summary WHERE bulkmailid='".$this->caller->id."'");
		return unserialize(stripslashes($stats));
	}


	function process_export() {
		$stats = $this->get_summary();
		$export_data = $stats['data'];

		$export_type		 = $this->parameters['export_type'];
		$export_show_headers = $this->parameters['export_show_headers'];
		$export_show_fields  = $this->parameters['export_show_fields'];

		switch($export_type) {
			case 'xml' :
				global $SQUIZLIB_PATH;
				include_once("$SQUIZLIB_PATH/xml/xml.inc");
				
				$xml = new Xml();
				
				$xml->tag_open('user_summary');

				foreach ($export_data as $data) {
					$xml->tag_open('user');

					if (in_array('email_address',$export_show_fields)) {
						$xml->tag_open('email_address');
						$xml->add_data($data['email_address']);
						$xml->tag_close();
					}

					if (in_array('userid',$export_show_fields)) {
						$xml->tag_open('user_id');
						$xml->add_data($data['userid']);
						$xml->tag_close();
					}

					if (in_array('reads',$export_show_fields)) {
						$xml->tag_open('reads');
						$xml->add_data($data['reads']);
						$xml->tag_close();
					}

					if (in_array('reads_percent',$export_show_fields)) {
						$xml->tag_open('reads_percent');
						$xml->add_data(round(($data['reads'] / $stats['total_reads'] * 100), 2).'%');
						$xml->tag_close();
					}

					if (in_array('forwards',$export_show_fields)) {
						$xml->tag_open('forwards');
						$xml->add_data($data['forwards']);
						$xml->tag_close();
					}

					if (in_array('forwards_percent',$export_show_fields)) {
						$xml->tag_open('forwards_percent');
						$xml->add_data(round(($data['forwards'] / $stats['total_forwards'] * 100), 2).'%');
						$xml->tag_close();
					}

					if (in_array('first_read',$export_show_fields)) {
						$xml->tag_open('first_read');
						$xml->add_data(date('D M j, Y, g:i a',mysql_to_timestamp($data['first_read'])));
						$xml->tag_close();
					}

					if (in_array('last_read',$export_show_fields)) {
						$xml->tag_open('last_read');
						$xml->add_data(date('D M j, Y, g:i a',mysql_to_timestamp($data['last_read'])));
						$xml->tag_close();
					}

					if (in_array('mail_client',$export_show_fields)) {
						$xml->tag_open('mail_client');
						$xml->add_data($data['mail_client']);
						$xml->tag_close();
					}

					if (in_array('ip',$export_show_fields)) {
						$xml->tag_open('ip');
						$xml->add_data($data['ip']);
						$xml->tag_close();
					}

					$xml->tag_close();
				}

				$xml->tag_close();
				$xml->set_filename('bmail_'.$this->id.'_user_summary_'.date('Y-m-d').'.xml');
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
					$csv_fields = array();
					if (in_array('email_address',$export_show_fields)) $csv_fields[] = $data['email_address'];
					if (in_array('userid',$export_show_fields))$csv_fields[] = $data['userid'];
					if (in_array('reads',$export_show_fields))$csv_fields[] = $data['reads'];
					if (in_array('reads_percent',$export_show_fields))$csv_fields[] = round(($data['reads'] / $stats['total_reads'] * 100), 2).'%';
					if (in_array('forwards',$export_show_fields))$csv_fields[] = $data['forwards'];
					if (in_array('forwards_percent',$export_show_fields))$csv_fields[] = round(($data['forwards'] / $stats['total_forwards'] * 100), 2).'%';
					if (in_array('first_read',$export_show_fields))$csv_fields[] = date('D M j, Y, g:i a',mysql_to_timestamp($data['first_read']));
					if (in_array('last_read',$export_show_fields))$csv_fields[] = date('D M j, Y, g:i a',mysql_to_timestamp($data['last_read']));
					if (in_array('mail_client',$export_show_fields))$csv_fields[] = $data['mail_client'];
					if (in_array('ip',$export_show_fields))$csv_fields[] = $data['ip'];

					$csv_data[] = $csv_fields;
				}
		
				$csv->set_filename('bmail_'.$this->id.'_user_summary_'.date('Y-m-d').'.csv');
				$csv->set_values(&$csv_data);
				$csv->export();
		}
	}


	 ########################################################################################
	# Prints the interface - assumes a backend has already been setup and the header printed
	function process_wizard_web(&$backend) {
		$stats = $this->get_summary();

		$backend->open_section('User Report');
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
				<td align="left" nowrap valign="top" bgcolor="#DDDDDD"><b>Email Address</b></td>
				<td align="center" valign="top" nowrap bgcolor="#DDDDDD"><b>Reads [ % ]</b></td>
				<td align="center" valign="top" nowrap bgcolor="#DDDDDD"><b>Forwards [ % ]</b></td>
				<td align="left" valign="top" nowrap bgcolor="#DDDDDD"><b>First Read</b></td>
				<td align="left" valign="top" nowrap bgcolor="#DDDDDD"><b>Last Read</b></td>
				<!-- <td align="left" valign="top" nowrap bgcolor="#DDDDDD"><b>Mail Client</b></td>
				<td align="left" valign="top" nowrap bgcolor="#DDDDDD"><b>IP Address</b></td> -->
			</tr>	
			<?
			$i = 0;
			$num_to_show = 20;
			$page = (int)$_POST['page'];
			if (!$page) $page = 1;
			$offset = ($page-1) * $num_to_show;
			$page_count = ceil(count($stats['data']) / $num_to_show);
			
			foreach ($stats['data'] as $data) {
				$i++;
				if ($i <= $offset) continue;
				if ($i > $offset + $num_to_show) break;
				$read_percentage = round(($data['reads'] / $stats['total_reads'] * 100), 2);
				$forward_percentage = round(($data['forwards'] / $stats['total_forwards'] * 100), 2);
				if ($data['reads'] > 0 && $read_percentage <= 0) $read_percentage = '< 0.01';
				if ($data['forwards'] > 0 && $forward_percentage <= 0) $forward_percentage = '< 0.01';
				?>
				<tr>
					<td align="left" nowrap valign="top"><?=$data['email_address']?></td>
					<td align="center" valign="top" nowrap><?=$data['reads']?> [<?=$read_percentage?>%]</td>
					<td align="center" valign="top" nowrap><?=$data['forwards']?> [<?=$forward_percentage?>%]</td>
					<td align="left" valign="top" nowrap><?=date('D M j, Y, g:i a',mysql_to_timestamp($data['first_read']))?></td>
					<td align="left" valign="top" nowrap><?=date('D M j, Y, g:i a',mysql_to_timestamp($data['last_read']))?></td>
					<!-- <td align="left" nowrap valign="top"><?=$data['mail_client']?></td>
					<td align="left" nowrap valign="top"><?=$data['ip']?></td> -->
				</tr>
				<?
			}

			if ($page_count > 1) {
			?>
			<tr>
				<td align="left" colspan="5">
					<input type="hidden" name="page" value="0">
					<script language="javascript">
						function jump_to_page(p) {
							document.edit.page.value = p;
							document.edit.active_step.value = 2;
							document.edit.submit();
						}
					</script>
					<table border="0" width="100%">
						<tr>
							<td align="left" valign="top" nowrap><font face=arial size=2><b>
							<? if ($page > 1) { ?>
								<a href="javascript:jump_to_page(<?=($page-1)?>);" onmouseover="javascript: window.status='View the previous page of recipients'; return true;" onmouseout="javascript: window.status=''; return true;" style="color:#000000">&lt;&lt; Prev</a>
							<? } else { ?>
								&lt;&lt; Prev
							<?}?>
							</td>
							<td align="center" valign="top" width="100%">
							<font face="arial" size="2"><b><?="Page $page of $page_count"?></b>
							<p><font face=courier>
								<?
								$digits = strlen((int) $page_count);

								for($i = 1; $i <= $page_count; $i++) {
									$num = sprintf("%0$digits"."d",$i);
									if ($i == $page) {
									?>
										<span style="color:red;"><?=$num?></span>
									<?
									} else {
									?>
										<a href="javascript:jump_to_page(<?=$i?>)" onmouseover="javascript: window.status='Jump to page <?=$i?> of recipients'; return true;" onmouseout="javascript: window.status=''; return true;"><?=$num?></a>
									<?
									}
								}

								?>
							</td>
							<td align="right" valign="top" nowrap><font face=arial size=2><b>
							<? if ($page != $page_count) { ?>
								<a href="javascript:jump_to_page(<?=($page+1)?>);" onmouseover="javascript: window.status='View the next page of recipients'; return true;" onmouseout="javascript: window.status=''; return true;" style="color:#000000">Next &gt;&gt;</a>
							<? } else { ?>
								Next &gt;&gt;
							<? } ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?
			} # End if page_count
		?>
		</table>
		<?
	}

}
