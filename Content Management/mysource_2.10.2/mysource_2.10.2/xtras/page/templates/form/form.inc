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
## File: xtras/page/templates/form/form.inc
## Desc: A template which allows the user to generate a customized form
## $Source: /home/cvsroot/xtras/page/templates/form/form.inc,v $
## $Revision: 2.104.2.3 $
## $Author: dofford $
## $Date: 2004/05/28 01:44:35 $
#######################################################################
global $INCLUDE_PATH, $SQUIZLIB_PATH;
include_once("$INCLUDE_PATH/page_template.inc");
include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");
include_once("$SQUIZLIB_PATH/form/form.inc");
include_once("$SQUIZLIB_PATH/csv/csv.inc");
include_once("$SQUIZLIB_PATH/xml/xml.inc");
include_once("$INCLUDE_PATH/parameter_set.inc");
include_once("$INCLUDE_PATH/html_body_format.inc");
include_once($SQUIZLIB_PATH."/mime_mail/mime_mail.inc");
#---------------------------------------------------------------------#

# Shoved in here so this old template works in the new system
class Page_Template_Form extends Page_Template {

	 ##############################################################
	# A title and a bodycopy. Simple requirments for a simple page
	var $parameters=array();

	# Stores the filepath of the recipient csv export file
	var $recipient_csv_filepath;

	 ##############################
	# Constructor
	function Page_Template_Form($pageid) {
		return Page_Template::Page_Template($pageid);
	}


	 ######################################################################
	# Create a new record in the databse and load it into this object
	function create($pageid) {
		$message = Page_Template::create($pageid);
		if(!$this->id)	return false;
		$db = &$this->get_db();
		# Set the title to the title of the current page.
		$page = &$this->get_page($pageid);
		$this->parameters['copy']['title'] = $page->name;
		$form = new Form('form');
		$form->title($page->name);
		$form->offer_standard_var('file','type','page');
		$this->parameters['form'] = $form->pack();

		# default the recipients to the the person who created the page
		$session = &get_mysource_session();
		$this->parameters['recipient_emails'] = ($session->logged_in()) ? $session->user->email : '';
		
		$db->update("UPDATE $this->table_name SET parameters='".addslashes(serialize($this->parameters))."' WHERE pageid='$pageid'");
		$id = $this->load($pageid);
		return $message;
	}




	 ################################################
	# Loads the temaplte details, from cache or database
	function load($pageid) {
		if ($pageid <= 0 && !($pageid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load $this->name page template without a valid pageid.",__FILE__,__LINE__);
		}

		 #################
		# Check the cache 
		//if ($this->load_from_cache($pageid)) return $pageid;

		 ########################
		# Load from the database
		$db = &$this->get_db();
		list($this->id, $tmp) = $db->single_row("SELECT pageid, parameters FROM $this->table_name WHERE pageid='$pageid'");
		$this->parameters = unserialize($tmp);

		 #####################################
		# Create a new record if none exists
		if (!$this->id) {
			return $this->create($pageid);
		}

		$this->set_generated_information();

		 ###################
		# Save to the cache 
		$this->save_to_cache($pageid);
		return $pageid;
	}

	 #######################################################
	# Removes the extension utterly from existance !
	function delete()   {
		return Page_Template::delete();
	}

	# dupe and new_id need no change from the default

	 #################################################################################
	# Provides an associative array of data that other templates might like to import
	function export_data() {
		return array("title"=>$this->get_title(),"bodycopy"=>$this->parameters['copy']['bodycopy'],"thankyou"=>$this->parameters['copy']['thankyou_bodycopy'],"form"=>$this->parameters['form']);
	}

	 
	 #################################################################################
	# Provides an associative array of data that other templates might like to import
	function import_data(&$data) {
		foreach($data as $code => $datum) {
			$code = strtolower(eregi_replace("[^a-z]+","",$code));
			if ($code == title || $code == name) $this->parameters['copy']['title'] = $datum;
			if ($code == bodycopy) 	$this->parameters['copy']['bodycopy'] = $datum;
			if ($code == form) 	$this->parameters['form'] = $datum;
			if ($code == thankyou) 	$this->parameters['copy']['thankyou_bodycopy'] = $datum;
		}
		$db = &$this->get_db();

		$db->update("UPDATE $this->table_name SET parameters='".addslashes(serialize($this->parameters))."'
			WHERE pageid='$this->id'");
		$this->clear_cache($this->id);
	}

	 ##################################################
	# Grabs keywords out of the title and bodycopy etc
	function extract_keywords() {
		$db = &$this->get_db();
		foreach($this->parameters['copy'] as $code => $bit) {
			if(substr(stripslashes($bit),0,14) == 'O:8:"bodycopy"') {
				$bodycopy = new BodyCopy($bit, $code);
				$copy .= strip_tags_padded($bodycopy->get_html())." ";
			} else $copy .= "$bit ";
		}
		return $copy;
	}
	 #####################################
	# grabs the value for a valued option
	function &get_value($code) {
		return $this->parameters[$code];
	}

	 #####################################################
	# Prints the page copy, with some keyword replacement
	function get_copy($code, $replacements) {
		$copy = &$this->parameters['copy'][$code];
		#if its a bodycopy, get the html and do the replacement on that instead
		if(substr(stripslashes($copy),0,14) == 'O:8:"bodycopy"') {
			$bodycopy = new BodyCopy($copy, $code);
			return replace_keywords($bodycopy->get_html(), array_merge($this->get_universal_replacements(),$replacements));
		}
		$copy = replace_keywords($copy, array_merge($this->get_universal_replacements(),$replacements));
		return $copy;
	}

	 #################################################
	# Returns an instance of a form in the parameters
	function &get_form($code) {
		$g = $this->table_name."_instanced_forms";
		global $$g;
		$l = &${$g}[$code];
		if(get_class($l) != "form") {
			$l = new Form($this->get_value($code));
		}
		return $l;
	}

	 #########################################
	# Prints a bit o' non-bodycopy copy
	function print_copy($code,$replacements) {
		echo eregi_replace("\r\n","<br>", $this->get_copy($code,$replacements));
		return;
	}

	 #########################################
	# Prints a bit o' cpoy
	function print_bodycopy($code,$replacements) {
		$bodycopy = new BodyCopy($this->parameters['copy'][$code], $code);
		$bodycopy->add_replace_keywords(array_merge($this->get_universal_replacements(),$replacements));
		$bodycopy->paint();
		return;
	}
	


	 ######################################################
	# Returns an associaive array of universal replacements
	# These can be used to replace special keywords in copy
	function &get_universal_replacements($descs) {
		$k = &Page_Template::get_universal_replacements();
		$d = &Page_Template::get_universal_replacements(1);
		if($descs) return $d;
		return $k;
	}


	/**
	 ** Always returns true as for printer_friendly_pages this is a dynmaic page that needs special attention.
	 ** @public
	 ** @returns boolean
	 **/
	function is_dynamic() {
		return true;
	}

	 #################################################
	# Set which answers to which questions go where?
	function process_selective_emails() {
		$new_selective_email = $_POST['new_selective_email'];

		$selective_emails = $_POST['selective_emails'];

		$current_details = &$this->get_value('selective_emails');

		foreach($new_selective_email as $questionid => $val_emails) {
			if(valid_email($val_emails['email']) && !stristr($current_details[$questionid][$val_emails['val']],$val_emails['email'])) $current_details[$questionid][$val_emails['val']] .= $val_emails['email'].';';
		}

		if($current_details != $selective_emails) {
			foreach($current_details as $questionid => $val_emails) {
				foreach($selective_emails[$questionid] as $val => $emails) {
					$current_details[$questionid][$val] = "";
					$possible_emails = split("[\; ]+",$emails);
					if(!empty($possible_emails))	{
						foreach($possible_emails as $email) {
							if(!valid_email($email)) continue;
							$current_details[$questionid][$val] .= $email.";";
						}
					}
				}
			}
			$db = &$this->get_db();
			if ($db->update("UPDATE $this->table_name SET parameters='".addslashes(serialize($this->parameters))."' WHERE pageid='$this->id'"))  {
				$this->clear_cache();
				return $message."Selective emails updated.";
			} else {
				return;
			}
		}
		return;
	}

	 #################################################
	# Set keywords for formelements
	function process_formelements_keyword() {
		$formelements_keyword = $_POST['formelements_keyword'];
		$current_details = &$this->get_value('formelements_keyword');

		$changed = false; # have any of the keywords been changed ?
		# Check if any of the submitted values are different
		# from what we have stored currently in the database
		foreach ($formelements_keyword as $questionid => $keyword) {
			if ($keyword != $current_details[$questionid]) {
				$changed = true;
				$current_details = $formelements_keyword;
				break; #as soon as we find one different, just save them all
			}
		}
		
		# If any of the keywords have been changed, update the database
		if ($changed) {
			$db = &$this->get_db();
			$query = "UPDATE $this->table_name SET parameters='".addslashes(serialize($this->parameters))."' WHERE pageid='$this->id'";
			if ($db->update($query))  {
				$this->clear_cache();
				return "Form Element keywords updated.";
			} else {
				return;
			}
		}
		return;
	}

	 ###############################################################
	# Insert serialized answers of this form into the database
	function set_log_answers($answers) {
		$session = &get_mysource_session();
		$db = &$this->get_db();
		$db->insert("INSERT INTO $this->table_name"."_log (pageid,submission_time,answers,userid,sessionid) VALUES ('$this->id',now(),'".addslashes($answers)."','".$session->user->id."','".session_id()."')");
		return "";
	}



	 ##################################################################
	# Prints the backend for the user - usually completely overwritten
	function print_backend() {
		$db = &$this->get_db();
		$forms = $db->associative_array("SELECT * FROM $this->table_name");
		$form = current($forms);
		$backend = &$this->setup_backend();
		$pset = &$this->get_pset();
		$pset->set_tabs($backend,'template_screen', $this->get_backend_href());
		if($pset->process($backend)) {
			$this->save_parameters();
			# Since on the front we save the whole form in the session (for pagination)
			# we need to clear it here when it gets updated.
			$session = &get_mysource_session();
			$session->unset_var("form_$this->id");

		}

		if($_POST['form_export']) { # someone clicked the export button
			switch($_POST['export_type']) {
				case 'csv': $this->csv_export(); return; 
				case 'xml': $this->xml_export(); return;
			}
		}

		# prints the rest of the tabs at the top
		$pset->print_backend($backend);
		return;
	}

	 ######################################################
	# Lets print the selective email addresses
	function print_selective_emails() {
		$form = $this->get_form('form');
		$backend = &$this->get_backend();
		$questions = $form->get_questions_by_type('listbox');
		$questions = array_merge($questions,$form->get_questions_by_type('tickbox'));
		if(count($questions)) {
			foreach($questions as $questionid => $title) {				
				$question = &$form->get_question_ref($questionid);
				$backend->open_field($title, 'one_liner');
				echo "<table><tr><td>";
				echo combo_box("new_selective_email[$questionid][val]",$question->options(),'');
				echo "</td><td>";
				echo text_box("new_selective_email[$questionid][email]",'',20,128);
				echo "</td></tr>";
				foreach($this->parameters['selective_emails'][$questionid] as $val => $emails) {
					$options = $question->options();
					if(in_array($val,array_keys($options)) && $emails) echo '<tr><td><b>'.$options[$val].'</b></td><td>'.text_box("selective_emails[$questionid][$val]",$emails,50,128).'</td></tr>';
				}
				echo '</table>';
			}
		}
	}


	 ######################################################
	# Decide whether or not to print the selective emails
	function show_if_selective_emails() {
		$form = $this->get_form('form'); 
		$questions = $form->get_questions_by_type('listbox');
		$questions = array_merge($questions,$form->get_questions_by_type('tickbox'));
		if(count($questions)) {
			return true;
		} else {
			return false;
		}
	}
	 #######################################
	# decide whether or not to print the validate bodycopy
	function show_if_validate_details(){
		if($this->get_value('validate_details')) return true;
		return false;
	}
		

	 #####################################
	# Lets print the formelements keyword
	function print_formelements_keyword() {
		$form = $this->get_form('form');
		$backend = &$this->get_backend();
		$questions = $form->get_questions_in_order();

		if(count($questions)) {
			foreach($questions as $questionid => $title) {
				$backend->open_field($title, 'one_liner');
				echo '<table>';
				if ($this->parameters['formelements_keyword'][$questionid] != '') {
					echo '<tr><td><b>'.$val.'</b></td><td>'.text_box("formelements_keyword[$questionid]",$this->parameters['formelements_keyword'][$questionid],50,128).'</td></tr>';
				} else {
					echo '<tr><td><b>'.$val.'</b></td><td>'.text_box("formelements_keyword[$questionid]",$this->parameters['formelements_keyword'][$questionid],50,128).'</td></tr>';
				}

				echo "</table>";
			}
		}
	}

	 ######################################################
	# Decide whether or not to print the selective emails
	function show_if_formelements_keyword() {
		$form = $this->get_form('form'); 
		$questions = $form->get_questions_by_type('listbox');
		$questions = array_merge($questions,$form->get_questions_by_type('tickbox'));
		$questions = array_merge($questions,$form->get_questions_by_type('textbox'));
		$questions = array_merge($questions,$form->get_questions_by_type('hidden'));
		if(!empty($questions)) {
			return true;
		} else {
			return false;
		}
	}


	 ############################################################
	# Prints a list of the fields available to export for the csv
	function print_csv_export_fields() {
		$backend = &$this->get_backend();
		$form = &$this->get_form('form');
		echo "<table>";
		$checked = (!isset($this->parameters['export_fields']) || $this->parameters['export_fields']['logid'])?'checked':'';
		echo "<tr><td><input type=checkbox name=other_fields[logid] value=1 $checked></td><td>Log ID</td></tr>";

		$checked = (!isset($this->parameters['export_fields']) || $this->parameters['export_fields']['submission_time'])?'checked':'';
		echo "<tr><td><input type=checkbox name=other_fields[submission_time] value=1 $checked></td><td>Submission Time</td></tr>";
		$checked = (!isset($this->parameters['export_fields']) || $this->parameters['export_fields']['userid'])?'checked':'';
		echo "<tr><td><input type=checkbox name=other_fields[userid] value=1 $checked></td><td>User</td></tr>";

		foreach ($form->section_question_order as $sectionid => $question_order) {
			$section_var_prefix = "section_$sectionid"."_";
			foreach ($question_order as $order => $questionid) {
				$question_var_prefix = $section_var_prefix."question_$questionid";
				$question = $form->sections[$sectionid]['questions'][$questionid];
				# by default all fields are filled
				$checked = (!isset($this->parameters['export_fields']) || $this->parameters['export_fields'][$question_var_prefix])?'checked':'';
				echo "<tr><td><input type=checkbox name=form_fields[$question_var_prefix] $checked></td><td>";
				echo "$question->title</td></tr>";
			}
		}
		echo "</table>";
		echo '<input type=hidden name="form_export" value="">';
		$backend->print_icon_button("javascript: document.edit.action.value='Export Submission History';document.edit.form_export.value=1;document.edit.submit();","Export Submission History","find");
	}

	function process_csv_export_fields() {
		$old_array = $this->parameters['export_fields'];
		$this->parameters['export_fields'] = array();
		foreach (array_keys($_POST['other_fields']) as $question_var_prefix) {
			$this->parameters['export_fields'][$question_var_prefix] = true;
		}
		foreach (array_keys($_POST['form_fields']) as $question_var_prefix) {
			$this->parameters['export_fields'][$question_var_prefix] = true;
		}
		if (equal_arrays($old_array, $this->parameters['export_fields']))
			return "Export fields updated";
	}

	function print_attachment_file_keywords() {
		?>
		<table cellspacing=1 cellpadding=3>
			<tr bgcolor=#aaaaaa>
				<td colspan=2><b> Email File Attachment Keyword Replacements Available </td>
			</tr>
			<tr bgcolor=#dddddd>
				<td>%pageid%</td>
				<td>The Page id.</td>
			</tr>
			<tr bgcolor=#dddddd>
				<td>%datetimeformat%</td>
				<td>Time measured to the nearest nanosecond</td>
			</tr>
			<tr bgcolor=#dddddd>
				<td>%counter%</td>
				<td>An incremented counter</td>
			</tr>
			<tr bgcolor=#dddddd>
				<td>%random%</td>
				<td>A randon number generator. Range 0-9999</td>
			</tr>
		</table>
		<?
	}

	 ###########################
	# prints a list of the logs
	function print_log_history() {
		$db = &$this->get_db();
		if($_POST['delete_confirm'] && $_POST['delete_logids']) {
			$sql =	"DELETE FROM $this->table_name"."_log \n".
					"WHERE logid IN (".join(",", array_keys($_POST['delete_logids'])).")";
			$db->delete($sql);
		}

		$logids = $db->single_column("SELECT logid FROM $this->table_name"."_log WHERE pageid='$this->id'");
		$users_system = &get_users_system();
		$log_page = $_REQUEST['log_page'];
		if (!$log_page) $log_page = 1;
		
		$log_count  = count($logids);
		if ($log_count) {
			$per_page    = 50;
			$page_count  = ceil($log_count / $per_page);
			if ($log_page > $page_count) $log_page = $page_count;
			$offset      = ($log_page - 1) * $per_page;

			$logs = $db->associative_array("SELECT logid,submission_time,userid FROM $this->table_name"."_log WHERE pageid='$this->id' ORDER BY submission_time DESC LIMIT $offset, $per_page ");
		?>
			<input type="hidden" name="log_page" value="<?=$log_page?>">
			<input type="hidden" name="view_logid" value="">
			<input type="hidden" name="delete_confirm" value="">
			<table cellpadding=2 cellspacing=2 border=0>
				<tr>
					<td><p class=field>ID</td>
					<td><p class=field>Submission Time</td>
					<td><p class=field>User</td>
					<td>Delete</td>
					<td>&nbsp;</td>
				</tr>
			<?
			foreach($logs as $logid => $data) {
				$user = $users_system->get_user($data['userid']);
				?>
				<tr bgcolor=#d0d0d0>
					<td align="right"><p class="backend_data"><?=$logid?></td>
					<td><p class="backend_data"><?=readable_datetime(mysql_to_timestamp($data['submission_time']))?></td>
					<td><p class="backend_data"><? echo (($user->id)?"<a href=\"user.php?userid=".$user->id."\">".$user->name()."</a>":"Anonymous") ?></td>
					<td align="center"><input type="checkbox" name="delete_logids[<?=$logid?>]" onClick="
						if (!document.edit.delete_confirm.value) {
							if (confirm('All checked entries will be deleted on clicking Commit. Are you sure this is what you want?')) {
								document.edit.delete_confirm.value = 1;
								return true;
							}
							else 
								return false;
						} 
						else 
							return true;
						"></td>
					<td><input type="button" name="view" value="View Log" onClick="javascript:document.edit.delete_confirm.value='';document.edit.view_logid.value=<?=$logid?>;document.edit.form_export.value=0; document.edit.submit(); return false;">
					</td>
				</tr>
				<?
			}
			if ($page_count > 1) {
			?>
				<script language=javascript>
					function jump_log_page(p) {
						document.edit.log_page.value = p;
						document.edit.submit();
					}
				</script>
				<tr>
					<td colspan=1 align=left><b>
					<? if ($log_page > 1) { ?>
						<a href="javascript:jump_log_page(<?=($log_page-1)?>);" onmouseover="javascript: window.status='View the previous page of logs'; return true;" onmouseout="javascript: window.status=''; return true;">&lt;&lt; Prev</a>
					<? } else { ?>
						&lt;&lt; Prev
					<? }#end if ?>
					</td>
					<td colspan=2 align=center><font face=arial size=2><? echo("Page $log_page of $page_count") ?></td>
					<td colspan=1 align=right><font face=arial size=><b>
					<? if ($log_page != $page_count) { ?>
						<a href="javascript:jump_log_page(<?=($log_page+1)?>);" onmouseover="javascript: window.status='View the next page of logs'; return true;" onmouseout="javascript: window.status=''; return true;">Next &gt;&gt;</a>
					<? } else { ?>
						Next &gt;&gt;
					<? }#end if ?>
					</td>
				</tr>
				<tr>
					<td colspan=4>
						<font face=courier>
						<?
						$digits = strlen((int) $page_count);

						for($i = 1; $i <= $page_count; $i++) {
							$num = sprintf("%0$digits"."d",$i);
							if ($i == $log_page) { 
							?>
								<span style="color:red;"><?=$num?></span>
							<?
							} else {
							?>
								<a href="javascript:jump_log_page(<?=$i?>)" onmouseover="javascript: window.status='Jump to page <?=$i?> of the logs'; return true;" onmouseout="javascript: window.status=''; return true;"><?=$num?></a>
							<?
							}
						}

						?>						
					</td>
				</tr>
			<?
			}
			?>
			</table>
			<? 
		} else {
			echo "<p class=backend_data>None";
		}
		return;
	}




	 ##########################
	# prints an individual log. 
	function print_individual_log_details() {
		$db = &$this->get_db();
		$users_system = &get_users_system();
		$logid = $_POST['view_logid'];

		if($logid) {
			$backend = &$this->get_backend();
			$log = $db->single_row("SELECT * FROM $this->table_name"."_log WHERE logid='$logid'");
			$user = $users_system->get_user($log['userid']);

			$form = $this->get_form('form');
			$form->import_answers($log['answers']);

			$backend->open_field('Submission Time');
			echo "<p class=backend_data>".readable_datetime(mysql_to_timestamp($log["submission_time"]));
			$backend->open_field('Submitted By');
			echo (($user->id)?"<a href=\"user.php?userid=".$user->id."\">".$user->name()."</a>":"Anonymous");

			$backend->open_field('Answers','top');
			$form->print_answers();
		} else {
			echo 'No log chosen.';
		}
		return;
	} 

	 ###########################################################
	# Returns the title as it should be printed on the frontend
	function get_title() {
		return $this->get_copy('title');
	}

	 ###########################################################################
	# This is where the REAL frontend happens. We like to be in the right scope
	function print_body() {
		$session = &get_mysource_session();
		$system_config = &get_system_config();
		$form_page = &$this->get_page();
		$page = &$this->web_system->get_page();
		$site = &$this->get_site();
		$design = &$page->get_design();

		if($this->get_value('paginate')) {
			#a showif can be used on current_page to use customised bodycopies
			$current_page = &$_POST['current_page'];
			if(!$current_page) {
				$form = $this->get_form('form');
				$current_page = 1;
			} else {
				if(!$form = &$session->get_var("form_$this->id")) {
					$form = $this->get_form('form');
				} else {
					$form = new Form($form);
				}
			}
			if($current_page >= count($form->sections)) $end = true;
		} else {
			if(!$form = &$session->get_var("form_$this->id")){
				$form = $this->get_form('form');
			} else {
				$validate_form = new Form($form);
				$form = new Form($form);
			}
			$end = true;
		}

		#set the session for the form to use
		if ($this->get_value('session_auto_answer')) {
			$form->set_session_auto_answer(&$session);
		}

		$moving_back = $_REQUEST['moving_back'];
		if(!$moving_back) {
			if($_REQUEST['validate_details'] && !$this->get_value('paginate')) {
				$current_page = count($form->sections) + 1;
			}
			#hide questions that are not in the format if a format exists
			$this->hide_questions(&$form, $current_page, $this->get_copy('form_format'));

			if ($form->process($current_page)) {
				$form->check_results = array();
				if(!$form->check($current_page)) {
					$results = $form->check_results;
					echo("<p><span class=error>You have not filled out this form correctly:</span></p>");
					echo("<ul>");
					foreach($results as $result) {
						echo "<li><span class=error>$result</span></li>";
					}
					echo("</ul>");
				} elseif($end) {
					if ($this->get_value ('recipient_send_form_info')) {
						$extra_data  = "Site: $site->name (ID: $site->id)\n";
						$extra_data .= "Page: $page->name (ID: $page->id)\n";
						

						if ($session->logged_in()) {
							$extra_data .= "User: ".$session->user->name()." (ID: ".$session->user->id.")\n";
						}
					}

					if ($form->get_requested_information('name'))
						$name = $form->get_requested_information('name');
					else if (($form->get_requested_information('firstname')) || ($form->get_requested_information('surname')))	{
						$name = $form->get_requested_information('firstname')." ".$form->get_requested_information('surname');
					}
					else	{
						if ($session->logged_in()) {
							$name = $session->user->name();
						}
					}
					//upto here
					if(!($email = $form->get_requested_information('email')) && $session->user) $email = $session->user->email;

					if(valid_email($email)) {
						$name = ereg_replace("[\n\r\t]+",' ', $name);
						$from_email = (($name)?'"'.$name.'" <'.$email.'>':$email);
					} else {
						$from_email = $system_config->webmaster_email;
						$email = $system_config->webmaster_email;
					}

					# for use replacing in various copies
					$replacements = array(); 
					$replace_html = $this->parameters['formelements_keyword_nice_values'];
					$replace_answers = unserialize($form->export_answers(0, $replace_html, $replace_html));
					while(list($r_sectionid, $r_sectionvals) = each($replace_answers)) {
						while(list($r_questionid, $r_answer) = each($r_sectionvals)) {
							$r_index = "$r_sectionid.$r_questionid";
							# if it was a checkbox etc then the value will be an array. so go get
							# all the individual elements out of that. 
							if(is_array($r_answer['value'])) {
								$answerval = '';
								foreach($r_answer['value'] as $k => $v) {
									# email lists are also an array
									if (is_array($v) && isset($v['email'])) {
										$answerval .= '<li><b>'.$v['name'].'</b> &lt;'.$v['email'].'&gt;</li>';
									} else 
										$answerval .= "<li>$v</li>";
								}
								$answerval = trim($answerval, ",");
							} else {
								$answerval = $r_answer['value'];
							}
							$replacements[$this->parameters['formelements_keyword'][$r_index]] = $answerval;
						}
					}

					$replacements['name'] = $name;
					$replacements['email'] = $email;
					if (trim($firstname = $form->get_requested_information('firstname')) != '') {
						$replacements['firstname'] = $firstname;
					}
					if (trim($surname = $form->get_requested_information('surname')) != '') {
						$replacements['surname'] = $surname;
					}

					$prev_abs_urls = absolute_urls();
					absolute_urls(true);
					
					#Recipient email text
					if (!$this->get_value('recipient_suppress_form_answers')) {
						if ($this->get_value('recipient_email_body_pos')) {
							$recipient_email_body = $this->get_copy('recipient_email_body',$replacements);
							$recipient_email_text = $form->form_summary($this->get_value('recipient_html_email'), $extra_data."\n", !$this->get_value('recipient_send_form_info'));
							$recipient_email_text = $recipient_email_body.$recipient_email_text;
						} else {
							# remove \n \r as form_summary fn replaces newlines with <br> nl2br()
							$recipient_email_body = str_replace(array("\n", "\r"), " ",$this->get_copy('recipient_email_body',$replacements));
							$recipient_email_text = $form->form_summary($this->get_value('recipient_html_email'), $extra_data."\n".$recipient_email_body, !$this->get_value('recipient_send_form_info'));
						}
					} else {
						if ($this->get_value('recipient_send_form_info')) {
							$recipient_email_text = $extra_data;
						}
						$recipient_email_text .= $this->get_copy('recipient_email_body',$replacements);
					}

					$recipient_subject = $this->get_value('recipient_subject');
					if (!isset($recipient_subject) || $recipient_subject == '') {
						$recipient_subject = "Form Submission - ".$this->get_title()."";
					}

					# Reciept email text
					if (!$this->get_value('receipt_suppress_form_answers')) {
						if ($this->get_value('receipt_email_body_pos')) {
							$reciept_email_body = $this->get_copy('receipt_email_body',$replacements);
							$receipt_email_text = $form->form_summary($this->get_value('receipt_html_email'));
							$receipt_email_text = $reciept_email_body.$receipt_email_text;
						} else {
							# remove \n \r as form_summary fn replaces newlines with <br> nl2br()
							$reciept_email_body = str_replace(array("\n", "\r"), " ",$this->get_copy('receipt_email_body',$replacements));
							$receipt_email_text = $form->form_summary($this->get_value('receipt_html_email'),$reciept_email_body);
						}
					} else {
						$receipt_email_text = $this->get_copy('receipt_email_body',$replacements);
					}

					absolute_urls($prev_abs_urls);

					$validate_bodycopy = $this->get_copy('validate_bodycopy');
					if($this->get_value('validate_details') && !$_REQUEST['validate_details']) {
						$replace_answers = unserialize($form->export_answers());
						$replacements = array();

						foreach($replace_answers as $section){
							foreach($section as $ans){
								$replacements[] = $ans['title'];
								$replacements[] = $ans['value'];
							}

						}

						?><form name="verify" enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>" method="POST"><?

						if($validate_bodycopy != "") {
							$form->render(0,$xtra_html,false,$validate_bodycopy);
							if ($this->get_value('default_validation')) {
								$form->print_answers();
							}
						} else {
							if ($this->get_value('default_validation')) {
								$form->print_answers();
							}
						}
						echo "<br><input type=hidden name=moving_back value=\"\"><input type=hidden name=current_page value=\"\"><input type=button value=\"Back\" onclick=\"form.form_".$form->name()."_action.value='Submit Form';document.verify.current_page.value=2;document.verify.moving_back.value=1;document.verify.submit();\">&nbsp;";

						echo "<input type=\"hidden\" name=\"validate_details\" value=\"1\"><input type=button value=\"Submit\" onclick=\"form.form_".$form->name()."_action.value='Submit Form';document.verify.current_page.value=4;verify.submit();\"></form>";

						# do not pack the form with the reference to the session
						$this->session_pack_form(&$session, &$form);
						return;
					} else {
						##############################
						# Recipient Email sending
						# firstly determine if an email file attachment is wanted
						$recipient_attatchment = ($this->get_value('recipient_file_attatchment') || is_null($this->get_value('recipient_file_attatchment')) ); # on by default
						
						#export csv file if needed
						if ($recipient_attatchment) {
							$this->recipient_csv_export($form, 'recipient');
						}
						
						if($this->get_value('recipient_html_email')) {
							$prev_abs_urls = absolute_urls();
							absolute_urls(true);
							#add stylesheet
							$recipient_email_text = "<link rel='stylesheet' href='".$design->stylesheet('abs')."' type='text/css'>".$recipient_email_text;
							absolute_urls($prev_abs_urls);
						} else {
							# plain text
							$recipient_email_text = str_replace(array("<br>",'<BR>','<br />','<BR />'),"\n\r", $recipient_email_text);
							$recipient_email_text = str_replace("\n","\n\r", $recipient_email_text);
							$recipient_email_text = html_entity_decode($recipient_email_text);
							$recipient_email_text = strip_tags($recipient_email_text);
						}

						foreach(explode("\n",trim($this->parameters['recipient_emails'])) as $recipient_email) {
							
							if($this->get_value('recipient_html_email')) {
								#add encoding
								$fromstring = "From: $from_email\nContent-Type: text/html; charset=iso-8859-1";
							} else {
								$fromstring = "From: $from_email";
							}

							# send it off...
							if (empty($recipient_email)) continue; # Make sure we dont send to blank emails

							# send email File attatchment
							if ($recipient_attatchment) {
								//send MIME mail with attachment
								$this->mail_with_csv_attachment($recipient_email, $from_email, $recipient_subject, $recipient_email_text);
							} else {
								//mail without attachment
								mail($recipient_email,$recipient_subject,$recipient_email_text,$fromstring,"-f$email");
							}
						}
						
						##############################
						#Reciept Email sending
						$send_receipt_email = ($this->get_value('receipt_email') || is_null($this->get_value('receipt_email'))); # on by default (ie if not set)

						if($send_receipt_email && valid_email($email)) {
							$receipt_from_email = $this->get_value('receipt_from_email');
							if(!$receipt_from_email || !valid_email ($receipt_from_email)) { 
								# if none is set or erroneous email - then default to webmaster
								$receipt_from_email = $system_config->webmaster_email;
							}

							$email_options = "From: $receipt_from_email";
							$mime_mail = new Mime_Mail();

							if (!$this->get_value('receipt_html_email')) {
								$receipt_email_text = str_replace(array("<br>",'<BR>','<br />','<BR />'),"\n\r", $receipt_email_text);
								$receipt_email_text = str_replace("\n","\n\r", $receipt_email_text);
								$receipt_email_text = html_entity_decode($receipt_email_text);
								$receipt_email_text = strip_tags($receipt_email_text);
								# set contentType
								$reciept_contentType = $mime_mail->TEXT;
							}
							else {
								# add stylesheet
								$prev_abs_urls = absolute_urls();
								absolute_urls(true);
								$receipt_email_text = "<link rel='stylesheet' href='".$design->stylesheet('abs')."' type='text/css'>".$receipt_email_text;
								absolute_urls($prev_abs_urls);

								# set ContentType
								$reciept_contentType = $mime_mail->HTML;
								# add encoding string to end of 'From:' line
								$email_options .= "\nContent-Type: text/html; charset=iso-8859-1";
							}

							//process reciept file attachments
							$reciept_file_id = &$this->parameters['receipt_email_attatchment'];

							//if not 0 is a file id
							if ($reciept_file_id != '0' && !is_null($reciept_file_id)) {
								$file = &$form_page->get_file($reciept_file_id);
								$reciept_filepath = $form_page->data_path.'/'.$file->filename;
								
								if(file_exists($reciept_filepath)) {
									//Send MIME mail
									$mime_mail = new Mime_Mail($receipt_from_email,$email,$this->get_value('receipt_subject'),'',wordwrap($tmp_body,72,"\n"));
									$mime_mail->attach($receipt_email_text,'',$reciept_contentType,$mime_mail->BIT7);
									
									$mime_mail->fattach($reciept_filepath, 'Form Submission Reciept Attatchment',$mime_mail->OCTET, $mime_mail->BASE64, "attachment; filename=\"".$file->filename."\"");
									$mime_mail->send_mail();
								}
							} else {
								# Mail with not attachment
								mail($email,$this->get_value('receipt_subject'),$receipt_email_text,$email_options,"-f$receipt_from_email");
							}
						}

						#Selective Emails
						foreach($this->get_value('selective_emails') as $questionid => $val_emails) {
							if(!$form->question_exists($questionid)) continue;
							$question = &$form->get_question_ref($questionid);
							$value = $question->value();
							if(is_array($value)) {
								foreach($value as $value) {
									$selective_email_recipients = array_merge(split("[\; ]+",$this->parameters['selective_emails'][$questionid][$value]),$selective_email_recipients);
								}
							} else {
								$selective_email_recipients = split("[\; ]+",$this->parameters['selective_emails'][$questionid][$value]);
							}
							foreach($selective_email_recipients as $email) {
								if($this->get_value('recipient_html_email')) # need to add encoding
									$fromstring = "From: $from_email\nContent-Type: text/html; charset=iso-8859-1";
								else # plain text
									$fromstring = "From: $from_email";

								if(valid_email($email)) {
									# send email File attatchment
									if ($recipient_attatchment) {
										//send MIME mail with attachment
										$this->mail_with_csv_attachment($email, $from_email, $recipient_subject, $recipient_email_text);
									} else {
										mail($email,$recipient_subject,$recipient_email_text,$fromstring,"-f$email");
									}
								}
							}
						}
						
						#DELETE the csv exported file now it is no longer needed
						if ($recipient_attatchment) {
							unlink($this->recipient_csv_filepath);
						}

						#Send to Friend emails
						$friend_emails = $form->get_requested_information("friend_emails");
						if (!is_array($friend_emails)) {
							if ($friend_emails) {
								$friend_emails = split("[\r\n]+", $form->get_requested_information("friend_emails"));
							} else {
								$friend_emails = array();
							}
						}

						 #######################
						#Send to Friend Emails
						$prev_abs_urls = absolute_urls();
						absolute_urls(true);

						$friends_body = $this->get_copy('send_to_friends_body',$replacements);

						if (isset($this->parameters['send_to_friends_html_email']) && $this->parameters['send_to_friends_html_email']) {
							$friends_body = "<link rel='stylesheet' href='".$design->stylesheet('abs')."' type='text/css'>".$friends_body;
						} else {
							$friends_body = str_replace(array("<br>",'<BR>','<br />','<BR />'),"\n\r", $friends_body);
							$friends_body = str_replace("\n","\n\r", $friends_body);
							$friends_body = html_entity_decode($friends_body);
							$friends_body = strip_tags($friends_body);
						}
						absolute_urls($prev_abs_urls);

						if(count($friend_emails) && !empty($friends_body)){
							$friends_subject = $this->get_copy('send_to_friends_subject',$replacements);
							if(empty($friends_subject)) {
								$friends_subject = 'Your friend has sent you an email from '.$site->name;
							}
							$friends_from_email = $this->get_copy('send_to_friends_from_email');
							if(!$friends_from_email) {
								# use the site name and webmaster email
								$friends_from_email =  ereg_replace("[\n\r\t]+"," ",$site->name)." <".$system_config->webmaster_email.">";
							}
							$friends_from_arg = "From: ".$friends_from_email;

							foreach ($friend_emails as $entry) {
								unset($toFriend);
								
								//check what type is is
								if (is_array($entry)) {
									# then this is an email list
									if(valid_email($entry['email'] )) {
										$toFriend = "\"{$entry['name']}\"<{$entry['email']} >";
									}
								} else {
									# then this is just a newline separated list of emails (e.g. from textbox)
									if (valid_email($entry))
										$toFriend = $entry;
								}

								//do some mailing
								if (isset($toFriend)) {
									
									//process reciept file attachments
									$friend_file_id = &$this->parameters['send_to_friends_email_attatchment'];

									//if not zero is a file id
									if ($friend_file_id != '0' && !is_null($friend_file_id)) {
										$friend_file = &$form_page->get_file($friend_file_id);
										$friend_filepath = $form_page->data_path.'/'.$friend_file->filename;

										if(file_exists($friend_filepath)) {
											
											//Send MIME mail with attachment
											$mime_mail = new Mime_Mail($friends_from_email,$toFriend,$friends_subject,'',wordwrap($tmp_body,72,"\n"));
									
											if (!$this->get_value('send_to_friends_html_email')) {
												$friend_contentType = $mime_mail->TEXT;
											}
											else {
												$friend_contentType = $mime_mail->HTML;
											}
											$mime_mail->attach($friends_body,'',$friend_contentType,$mime_mail->BIT7);

											$mime_mail->fattach($friend_filepath, 'Form Submission Friend Attatchment',$mime_mail->OCTET, $mime_mail->BASE64, "attachment; filename=\"".$friend_file->filename."\"");
											$mime_mail->send_mail();
										}
									} else {
										if($this->get_value('send_to_friends_html_email')) {
											# add encoding string to end of 'From:' line
											$friends_from_arg .= "\nContent-Type: text/html; charset=iso-8859-1";
										}
										mail($toFriend, $friends_subject, $friends_body, $friends_from_arg);
									}
								}
							}
						}

						if($this->get_value('log_form_submission')) {
							$this->set_log_answers($form->export_answers());
						}
						if ($this->get_value('hide_results')) {
							# If they are hiding the results, do a keyword substitution
							# on the html about to be output
							include_once("$INCLUDE_PATH/text.inc");
							$this->print_bodycopy("thankyou_bodycopy",$replacements);
						} else {
							# If they are not hiding the results, display the page
							# as per normal
							$this->print_bodycopy('thankyou_bodycopy',$replacements);
							if($_POST['validate_details'] && !$this->get_value('paginate')) {
								$validate_form->print_answers();
							} else {
								$form->print_answers();
							}
						}
						$session->unset_var('form_'.$this->id);
						return;
					} # end else validate_details
				} else {
					$current_page++;
				}
			}
		}
		
		$form_format = $this->get_copy('form_format');
		$form_format = str_replace('section_?', 'section_'.($current_page), $form_format);

		if(!($current_page > 1)) {
			$this->print_bodycopy('bodycopy');
		}

		?><form name="form" enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>" method=post><?
		if($session->logged_in() && $current_page <= 1 && !$moving_back) {
			$users_system = &get_users_system();
			$form->answer_auto_answer("name",$session->user->name());
			$form->answer_auto_answer("surname", $session->user->surname);
			$form->answer_auto_answer("firstname", $session->user->firstname);
			$form->answer_auto_answer("email",$session->user->email);
			# Grab the first placement and use that
			$placement = reset($session->user->placements);
			$location = &$users_system->get_location($placement['locationid']);
			$form->answer_auto_answer("phone",$placement['direct_phone']);
			$form->answer_auto_answer("mobile",$session->user->mobile_no);
			$form->answer_auto_answer("fax",$placement['direct_fax']);
			$form->answer_auto_answer("s_address",$location->s_address);
			$form->answer_auto_answer("s_suburb",$location->s_suburb);
			$form->answer_auto_answer("s_state",$location->s_state);
			$form->answer_auto_answer("s_postcode",$location->s_postcode);
			$form->answer_auto_answer("s_countryid",$location->s_countryid);
		}
		$xtra_html = "<input type=\"hidden\" name=\"current_page\" value=\"$current_page\">";
		$form->render($current_page,$xtra_html,false,$form_format);
		?></form><?
		$this->session_pack_form(&$session, &$form);
	}

	 ###############################################################
	# Export data for the given fields between start and end dates
	function csv_export() {
		$other_fields = $_POST['other_fields'];
		$form_fields = $_POST['form_fields'];

		#construct a nice string yyyy-mm-dd
		$startdate = $_POST['export_from_datevalue']['y'].'-'.str_pad($_POST['export_from_datevalue']['m'],2,'0',STR_PAD_LEFT).'-'. str_pad($_POST['export_from_datevalue']['d'],2,'0',STR_PAD_LEFT);
		#construct a nice string yyyy-mm-dd
		$enddate = $_POST['export_to_datevalue']['y'].'-'. str_pad($_POST['export_to_datevalue']['m'],2,'0',STR_PAD_LEFT).'-'. str_pad($_POST['export_to_datevalue']['d'],2,'0',STR_PAD_LEFT);

		$db = &$this->get_db();
		$form = $this->get_form('form');
		$csv = new Csv();
		if($startdate > $enddate) return;
		$logs = $db->associative_array("SELECT * FROM $this->table_name"."_log l WHERE l.pageid='$this->id'".(($startdate)?" AND l.submission_time >= '$startdate 00:00:00' AND l.submission_time <= '$enddate 23:59:59'":""));

		if($this->get_value('show_field_headers')) {
			foreach($other_fields as $key => $on) {
				if($on) $field_headers[] = $key;
			}
			reset($form_fields);
			while(list($question_var_prefix,$on) = each($form_fields)) {
				if($on) {
					$sectionid = substr($question_var_prefix,8,strpos($question_var_prefix,"question_")-9);
					$questionid = substr($question_var_prefix,strpos($question_var_prefix,"question_")+9);
					$field_headers[] = $form->sections[$sectionid]["questions"][$questionid]->title;
				}
			}
			$csv->set_field_headers(&$field_headers);
		}
		while(list($logid,$log) = each($logs)) {
			$csv_fields = "";
			$answers = unserialize($log["answers"]);
			foreach($other_fields as $key => $on) {
				if($on) $csv_fields[] = $log[$key];
			}
			reset($form_fields);
			while(list($question_var_prefix,$on) = each($form_fields)) {
				if($on) {
					$sectionid = substr($question_var_prefix,8,strpos($question_var_prefix,"question_")-9);
					$questionid = substr($question_var_prefix,strpos($question_var_prefix,"question_")+9);
					$csv_fields[] = $answers[$sectionid][$questionid]["value"];
				}
			}
			$csv_data[] = $csv_fields;
		}
		$csv->set_filename("form_log_".date("Y-m-d").".csv");
		$csv->set_values(&$csv_data);
		$csv->export($csv_data);
		return;
	}

	 ###############################################################
	# Export data for the given fields between start and end dates
	function xml_export() {
		$other_fields = $_POST['other_fields'];
		$form_fields = $_POST['form_fields'];
		$db = &$this->get_db();
		$form = $this->get_form('form');

		#construct a nice string yyyy-mm-dd
		$startdate = $_POST['export_from_datevalue']['y'].'-'.str_pad($_POST['export_from_datevalue']['m'],2,'0',STR_PAD_LEFT).'-'. str_pad($_POST['export_from_datevalue']['d'],2,'0',STR_PAD_LEFT);
		#construct a nice string yyyy-mm-dd
		$enddate = $_POST['export_to_datevalue']['y'].'-'. str_pad($_POST['export_to_datevalue']['m'],2,'0',STR_PAD_LEFT).'-'. str_pad($_POST['export_to_datevalue']['d'],2,'0',STR_PAD_LEFT);

		$xml = new Xml();
		if($startdate > $enddate) return;
		$logs = $db->associative_array("SELECT * FROM $this->table_name"."_log l WHERE  l.pageid='$this->id'".(($startdate)?" AND l.submission_time >= '$startdate 00:00:00' AND l.submission_time <= '$enddate 23:59:59'":""));
		$xml->tag_open("formdata");
		while(list($logid,$log) = each($logs)) {
			$xml->tag_open("submission");
			$answers = unserialize($log["answers"]);
			foreach($other_fields as $key => $on) {
				if($on) {
					$xml->tag_open(ereg_replace("_","",$key));
					$xml->add_data($log[$key]);
					$xml->tag_close();
				}
			}
			reset($form_fields);
			while(list($question_var_prefix,$on) = each($form_fields)) {
				if($on) {
					$sectionid = substr($question_var_prefix,8,strpos($question_var_prefix,"question_")-9);
					$questionid = substr($question_var_prefix,strpos($question_var_prefix,"question_")+9);
					$xml->tag_open(ereg_replace("_ ","",$form->sections[$sectionid]["questions"][$questionid]->title));
					$xml->add_data($answers[$sectionid][$questionid]["value"]);
					$xml->tag_close();
				}
			}
			$xml->tag_close();
		}
		$xml->tag_close();
		$xml->set_filename("form_log_".date("Y-m-d").".xml");
		$xml->export();
		return;
	}

	/** 
	* Export data for Email File attatchment (CSV)
	*
	* @param object $form The form object to export the answers from.
	* @param string $filepath The path (including filename) where the file will be saved
	* @returns null
	* @access public
	*/
	function email_recipient_csv_export($form, $filepath, $prefix) {
		$other_fields = $this->parameters[$prefix.'_export_fields']['other_fields'];
		$form_fields = $this->parameters[$prefix.'_export_fields']['form_fields'];
		$session = &get_mysource_session();

		$csv = new Csv();

		if($this->get_value('recipient_show_field_headers')) {
			foreach($other_fields as $key => $on) {
				if($on) $field_headers[] = $key;
			}
			reset($form_fields);
			while(list($question_var_prefix,$on) = each($form_fields)) {
				if($on) {
					$sectionid = substr($question_var_prefix,8,strpos($question_var_prefix,"question_")-9);
					$questionid = substr($question_var_prefix,strpos($question_var_prefix,"question_")+9);
					$field_headers[] = $form->sections[$sectionid]["questions"][$questionid]->title;
				}
			}
			$csv->set_field_headers(&$field_headers);
		}

		$csv_fields = "";

		$form_answers = $form->export_answers(0, $this->parameters['recipient_export_nice_value']);
		$answers = unserialize($form_answers);
		//setup csv_fields for other_fields
		if ($other_fields['submission_time']) {
			$csv_fields[] = date('Y-m-d H:i:s');//session time set to same format as SQL datetime format
		}
		if ($other_fields['userid']) {
			$csv_fields[] = $session->user->id; //user_id
		}

		reset($form_fields);
		while(list($question_var_prefix,$on) = each($form_fields)) {
			if($on) {
				$sectionid = substr($question_var_prefix,8,strpos($question_var_prefix,"question_")-9);
				$questionid = substr($question_var_prefix,strpos($question_var_prefix,"question_")+9);
				$csv_fields[] = $answers[$sectionid][$questionid]["value"];
			}
		}
		$csv_data[] = $csv_fields;
		//set values and export
		$csv->set_filepath($filepath);
		$csv->set_values(&$csv_data); 
		$csv->export($csv_data); 

		return;
	}

	/** 
	* Export data for Email File attatchment (XML)
	*
	* @param object $form The form object to export the answers from.
	* @param string $filepath The path (including filename) where the file will be saved
	* @returns null
	* @access public
	*/
	function email_recipient_xml_export($form, $filepath, $prefix) {
		$other_fields = $this->parameters[$prefix.'_export_fields']['other_fields'];
		$form_fields = $this->parameters[$prefix.'_export_fields']['form_fields'];
		$session = &get_mysource_session();

		$xml = new Xml();

		//write out XML
		$xml->tag_open("formdata");
		$form_answers = $form->export_answers();
		$answers = unserialize($form_answers);
		$xml->tag_open("submission");
		
		//setup csv_fields for other_fields
		if ($other_fields['submission_time']) {
			$xml->tag_open('submissiontime'); //submission_time is the normal tag
			$xml->add_data(date('Y-m-d H:i:s'));//session time set to same format as SQL datetime format
			$xml->tag_close();
		}
		if ($other_fields['userid']) {
			$xml->tag_open('userid'); //userid
			$xml->add_data($session->user->id); 
			$xml->tag_close();
		}

		//get form answers
		$form_answers = $form->export_answers(0, $this->parameters['recipient_export_nice_value']);
		$answers = unserialize($form_answers);

		reset($form_fields);
		while(list($question_var_prefix,$on) = each($form_fields)) {
			if($on) {
				$sectionid = substr($question_var_prefix,8,strpos($question_var_prefix,"question_")-9);
				$questionid = substr($question_var_prefix,strpos($question_var_prefix,"question_")+9);
				$xml->tag_open(ereg_replace("_ ","",$form->sections[$sectionid]["questions"][$questionid]->title));
				$xml->add_data($answers[$sectionid][$questionid]["value"]);
				$xml->tag_close();
			}
		}
		$xml->tag_close(); //submission
		
		$xml->tag_close(); //formdata
		
		//setup filepath anc export
		$xml->set_filepath($filepath);
		$xml->export();
		return;
	}

	/**
	* Returns a list of file attatchments to this page for selecting the reciept email file attachments
	*
	* @access public
	* @return array
	*/
	function get_file_attachment_list() {
		
		$send_image_list = array();
		$page = &$this->get_page();

		if(!empty($page->file_index)) {
			$send_image_list = array('');
			foreach($page->file_index as $id) {

				$file = &$page->get_file($id);
				if(file_exists($page->data_path.'/'.$file->filename)) {
					$send_image_list[$file->id] = $file->filename;
				} 
				else {
					//an orphaned file (doesnt exist in filesystem)
					$send_image_list[$file->id] = $file->filename." [File Missing]";
				}
			}
		}
		else {
			$send_image_list = array('[please upload a page attachment]');
		}
		
		return $send_image_list;
	}


	/**
	* Procceses setup before CSV attachment occurs
	*
	* @param string $prefix the prefix_used
	* @returns string containing the filepath
	* @access public
	*/
	function getFilename($prefix) {
		//replace keywords in filename
		$att_replacements = $this->get_universal_replacements();
		$att_replacements['pageid'] = $this->id;
		list($usec, $sec) = explode(" ",microtime()); 
		$att_replacements['datetimeformat'] = $sec + $usec * 1000000;
		$att_replacements['random'] = rand(0,9999);

		# Setup of counter parameter (email_file_attachment_counter)
		# counter parameter used to as counter to count number of submissions
		$att_count = &$this->get_value($prefix.'_attachment_counter');
		
		//set to 1 if null
		if (is_null($att_count) || !isset($att_count)) {
			$att_count = 0;
		}

		//setup filepath
		$filename = &$this->get_value('recipient_attatch_file_name');
		
		# set filename to this incase they forgot to set a filename we do not get an endless loop
		if (!$filename || $filename=='')
			$filename = 'email_csv_attachment_NEEDS_A_FILENAME';
		# validate file does not exist
		# incase the same counter value has been read from the parameters
		# for loop used to stop infinate loop
		do {
			$att_count++; //updates parameter as reference to its value
			$att_replacements['counter'] = $att_count;
			$att_filepath = $this->data_path.'/'.$filename;
			$att_filepath = replace_keywords($att_filepath, $att_replacements);

			//save new value to the parameters straight away
			$this->save_parameters();

		} while (file_exists($att_filepath));

		return $att_filepath;
	}
	
	function recipient_csv_export($form, $prefix) {
		$export_type = $this->get_value('recipient_export_type');
		$this->recipient_csv_filepath = $this->getFilename($prefix);

		switch($export_type) {
			case 'csv': 
				$this->email_recipient_csv_export($form, $this->recipient_csv_filepath, $prefix); 
				break; 
			case 'xml': 
				$this->email_recipient_xml_export($form, $this->recipient_csv_filepath, $prefix); 
				break;
		}
	}

	 #####################################
	# Mails with csv attachment
	function mail_with_csv_attachment($to, $from, $subject, $body) {
		
		//send MIME mail
		$mime_mail = new Mime_Mail($from,$to,$subject,'',wordwrap($tmp_body,72,"\n"));

		//determine the contentType
		if (!$this->get_value('recipient_html_email')) {
			$recipient_contentType = $mime_mail->TEXT;
		}
		else {
			$recipient_contentType = $mime_mail->HTML;
		}
		
		$mime_mail->attach($body,'',$recipient_contentType,$mime_mail->BIT7);

		$basename = basename($this->recipient_csv_filepath);
		$mime_mail->fattach($this->recipient_csv_filepath, 'Form Submission Attatchment',$mime_mail->OCTET, $mime_mail->BASE64, "attachment; filename=\"$basename\"");
		$mime_mail->send_mail();
	}

	/**
	* Prints CSV export fields list
	*
	* @param string $prefix the prefix_used
	* @returns None 
	* @access public
	*/
	function print_recipient_csv_export_fields($prefix) {
		$form = $this->get_form('form');

		echo "<table>";
		echo '<tr><td width="50">Order</td><td colspan="2">Export Values</td></tr>';
		$checked = (!isset($this->parameters[$prefix.'_export_fields']) || $this->parameters[$prefix.'_export_fields']['other_fields']['submission_time'])?'checked':'';
		echo "<tr><td></td><td><input type=\"checkbox\" name=\"{$prefix}_other_fields[submission_time] value=1\" $checked></td><td>Submission Time</td></tr>";
		$checked = (!isset($this->parameters[$prefix.'_export_fields']) || $this->parameters[$prefix.'_export_fields']['other_fields']['userid'])?'checked':'';
		echo "<tr><td></td><td><input type=\"checkbox\" name=\"{$prefix}_other_fields[userid] value=1\" $checked></td><td>User</td></tr>";
		$this->printCsvOrderForm($form, $prefix);
		echo "</table>";
	}

	/**
	* Prints the given forms CSV export fields list
	*
	* @param object $form - the form to use
	* @returns None 
	* @access public
	*/
	function printCsvOrderForm(&$form, $prefix) {
		$js = "this.value = parseFloat(this.value.replace(/[^0-9\.\-]+/g,'')); if (isNaN(this.value)) this.value = 1;";
		$js .= 'if(this.value < 1) this.value = 1;';
		$max=0;
		foreach (array_keys($form->section_question_order) as $sectionid) {
			$max += count($form->section_question_order[$sectionid]);
		}
		$js .= "if(this.value > $max) this.value = $max;";
		$i=1;
		$field_order = $this->parameters[$prefix.'_export_fields']['form_fields_order'];

		foreach ($form->section_question_order as $sectionid => $question_order) {
			$section_var_prefix = "section_$sectionid"."_";
			foreach ($question_order as $order => $questionid) {
				$question_var_prefix = $section_var_prefix."question_$questionid";
				$question = $form->sections[$sectionid]['questions'][$questionid];
				# by default all fields are filled
				$checked = (!isset($this->parameters[$prefix.'_export_fields']) || $this->parameters[$prefix.'_export_fields']['form_fields'][$question_var_prefix])?'checked':'';

				$order = $field_order[$question_var_prefix];
				if (!$order && !isset($this->parameters[$prefix.'_export_fields'])) {
					$order = $i;
					$field_order[$question_var_prefix] = $i;
				} elseif (!$order) {
					$order = '';
					$field_order[$question_var_prefix] = $max+1;
				}
				$field_array[$question_var_prefix] =  "<tr><td><input size=\"2\" type=\"text\" name=\"{$prefix}_form_fields_order[$question_var_prefix]\" value=\"$order\" onchange=\"$js\"></td><td><input type=\"checkbox\" name=\"{$prefix}_form_fields[$question_var_prefix]\" $checked></td><td>$question->title</td></tr>";
				$i++;
			}
		}

		# print the ordered array
		foreach (array_keys($field_order) as $question_var_prefix) {
			echo $field_array[$question_var_prefix];
		}
	}

	/**
	* Processes CSV export fields list
	*
	* @param string $prefix the prefix_used
	* @returns None 
	* @access public
	*/
	function process_recipient_csv_export_fields($prefix) {
		$old_array = $this->parameters[$prefix.'_export_fields'];
		$this->parameters[$prefix.'_export_fields'] = array();
		foreach (array_keys($_POST[$prefix.'_other_fields']) as $question_var_prefix) {
			$this->parameters[$prefix.'_export_fields']['other_fields'][$question_var_prefix] = true;
		}
		foreach (array_keys($_POST[$prefix.'_form_fields']) as $question_var_prefix) {
			$this->parameters[$prefix.'_export_fields']['form_fields'][$question_var_prefix] = true;
			
			#handle ordering
			$order = $_POST[$prefix.'_form_fields_order'][$question_var_prefix];
			$this->parameters[$prefix.'_export_fields']['form_fields_order'][$question_var_prefix] = (int) $order;
			if ($order < 1) continue;
			if ($this->parameters[$prefix.'_export_fields']['form_fields'][$question_var_prefix]) {
				$this->parameters[$prefix.'_export_fields']['form_fields'][$question_var_prefix] = (int) $order;
			}
		}
		
		#sort the arrays in order
		asort($this->parameters[$prefix.'_export_fields']['form_fields']);
		asort($this->parameters[$prefix.'_export_fields']['form_fields_order']);

		#fix up any order numbers that are the same
		$question_var_prefix_array = array_keys($this->parameters[$prefix.'_export_fields']['form_fields']);
		for ($question_var_prefix=current($question_var_prefix_array); $question_var_prefix=current($question_var_prefix_array);) {
			$order = $this->parameters[$prefix.'_export_fields']['form_fields'][$question_var_prefix];
			if (false === ($next_question_var_prefix = next($question_var_prefix_array))) break;
			$next_order = $this->parameters[$prefix.'_export_fields']['form_fields'][$next_question_var_prefix];
			if ($order >= $next_order && is_int($order)) {
					$this->parameters[$prefix.'_export_fields']['form_fields'][$next_question_var_prefix] = (int) $order+1;
					$this->parameters[$prefix.'_export_fields']['form_fields_order'][$next_question_var_prefix] = (int) $order+1;
			}
		}

		if (equal_arrays($old_array, $this->parameters[$prefix.'_export_fields']))
			return "$prefix Email export fields updated";
	}

	function session_pack_form(&$session, &$form) {
		# no need to pack the forms session ref
		unset($form->session);
		$session->set_var("form_$this->id",$form->pack());
		#set the session for the form to use
		if ($this->get_value('session_auto_answer')) {
			$form->set_session_auto_answer(&$session);
		}
	}

	# hides questions that are do not have an answer keyword in the format
	function hide_questions(&$form, $current_page, $format) {
		if (trim($format) == '' || is_null($current_page)) return;
		$keywords = retrieve_keywords_replacements($format);
		$sectionid = $form->get_ordered_sectionid((int)$current_page);
		$questions = $form->section_question_order[$sectionid];

		foreach ($keywords as $keyword) {
			if (preg_match('/section_('.$current_page.'|\?)+_question_([0-9]+)_answer/', $keyword, $matches)) {
				$key = array_search($questions[$matches[2]-1], $questions);
				unset($questions[$key]);
			}
		}
		foreach ($questions as $key=>$qid) {
			$questions[$key] = $sectionid.'.'.$qid;
		}
		if (count($questions)) {
			$form->hide_questions($questions);
		}
	}
}//end class
?>