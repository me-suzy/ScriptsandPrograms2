<?
include_once("../../init.php");
include_once('./upgrade_functions.php');
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/html_general.inc"); 
#---------------------------------------------------------------------#
#
# This class allows you to upgrade on a per page basis a given template
# override the funtions upgrade_header() upgrade_footer() and upgrade_page_template()
# to use this class and do your processing of each page in

# num pages to process at one time
define(NUM_PAGES, 5);

class upgrade_template {

	var $num_pages;
	var $pageids;
	var $report;
	var $start;

	function upgrade_template($template) {
		$session = &get_mysource_session();
		$this->template = $template;
		$this->report = &$session->get_var('upgrade_report');
		$this->pageids = &$session->get_var('upgrade_pageids');
		$this->num_pages = &$session->get_var('upgrade_num_pages');

		if ($_REQUEST['started'] != 1) {
			if (!is_string($this->report)) {
				$this->report = '';
			}

			if ($this->validate() === false) {
				$this->failed();
			}

			if ($_REQUEST['num_pages']) {
				$this->num_pages = $_REQUEST['num_pages'];
			} else {
				$this->num_pages = NUM_PAGES;
			}

			# load the template pageids
			$web_system = &get_web_system();
			$this->report .= "<p>Finding $this->template pages ...</p>";
			$this->pageids = $web_system->get_template_pages($this->template);
			$session->set_var('upgrade_pageids', $this->pageids);
			if (count($this->pageids) == 0) {
				$this->report .= "No $this->template pages where found";
				$this->finished();
			}
			$this->start = 1;
		}
	}

	function validate() {
		$session = &get_mysource_session();
		global $SYSTEM_ROOT;

		# validate ok to runt
		if (!$session->logged_in()) {
			$session->login_screen("Upgrade $this->template", "You must be logged in.");
			return false;
		} else if (!user_root()) {
			$session->login_screen("Upgrade $this->template", "You must be <b>root</b> to upgrade the system.",$session->user->login);
			return false;
		}

		if (!is_file($SYSTEM_ROOT."/xtras/page/templates/$this->template/$this->template.inc")) {
			$this->report .= "$this->template does not exist in this system. Aborting.<br />";
			report_ignore($_SERVER['SCRIPT_FILENAME']);
			return false;
		}

		if (is_file($_SERVER['SCRIPT_FILENAME'] . '.success')) {
			$this->report .= 'This upgrade has already run. Aborting.<br />';
			return false;
		}

		if (is_file($_SERVER['SCRIPT_FILENAME'] . '.failure')) {
			unlink($_SERVER['SCRIPT_FILENAME'] . '.failure');
		}
	}

	function finished() {
		# We are done so lets clean up
		$session = &get_mysource_session();
		$session->set_var('upgrade_pageids', $null1=null);
		$session->set_var('upgrade_report', $null2='');
		echo $this->report;
		echo '...UPGRADE COMPLETE.';
		report_success($_SERVER['SCRIPT_FILENAME']);
		exit();
	}

	function failed() {
		# We are done so lets clean up
		$session = &get_mysource_session();
		$session->set_var('upgrade_pageids', $null1=null);
		$session->set_var('upgrade_report', $null2='');
		echo $this->report;
		echo '...UPGRADE FAILED.';
		report_success($_SERVER['SCRIPT_FILENAME']);
		exit();
	}

	function upgrade_header() {
		$this->report .= 'upgrade header<br />';
	}

	function upgrade_footer() {
		$this->report .= 'upgrade footer<br />';
	}

	function run() {
		if ($this->start) {
			$this->upgrade_header();
		}
		if (!isset($this->pageids)) {
			return;
		} elseif($_GET['action'] == 'finished') {
			$this->upgrade_footer();
			$this->finished();
		} else {
			$this->do_upgrade_limited_pages();
		}
	}

	function do_upgrade_limited_pages() {
		$num_to_process     = $_GET['num_to_process'];
		$num                = $_GET['num'];
		$num_processed      = $_GET['num_processed'];
		$started            = $_GET['started'];
		$start_time         = $_GET['start_time'];
		$action             = $_GET['action'];

		if (!$started) {
			# First popup ever
			$now = time();
			$num_to_process = count($this->pageids);
			$process_url = $_SERVER['PHP_SELF']."?num_processed=0&started=1&start_time=$now&num_to_process=$num_to_process";
			echo status_popup(1,$process_url,true,false,'#330099','Performing $this->template Upgrading - Please wait', "Starting to process $num_to_process $this->template pages");
			return;
		}
		# upgrade num pages 
		if (!empty($this->pageids)) {
			$count = $this->upgrade_num_pages();
		}

		$num_processed += $count;
		$percent = ceil(($num_processed / $num_to_process) * 100);
		$time_diff = time() - $start_time;
		$time_per_lookup = $time_diff / $num_processed;
		$time_left = $time_per_lookup * ($num_to_process - $num_processed);
		if ($time_left <= 0) $time_left = 1;
		$status = '';

		if (empty($this->pageids)) {
			$process_url = '';
			$status = "<script language=\"Javascript\">window.opener.location='".$_SERVER['PHP_SELF']."?action=finished&started=1';self.close();</script>";
			$percent = 100;
		} else {
			$process_url = $_SERVER['PHP_SELF']."?num_to_process=$num_to_process&num_processed=$num_processed&started=1&start_time=$start_time";
			$status = "Completed $num_processed $this->template Pages - ".($num_to_process - $num_processed).' remaining - est time: '.easy_time_total($time_left);
		}

		echo status_popup($percent,$process_url,false,$finish,'#330099','Performing $this->template Upgrade - Please wait',$status);
	}

	# upgrades $num_to_process pages and also removes $num_to_process elements from the pages array
	function upgrade_num_pages() {
		$web_system = &get_web_system();
		$keys = array_keys($this->pageids);
		$i = 0;
		foreach ($keys as $pageid) {
			if ($i == $this->num_pages) {
				break;
			}
			$i++;

			$page = &$web_system->get_page($pageid);
			$page_template = &$page->get_template();

			$this->upgrade_page_template($page, $page_template);

			$page_template->save_parameters();

			$page->clear_cache();
			unset($page_template);
			$web_system->forget_page($pageid);

			# remove page from list so it is not processed again
			unset($this->pageids[$pageid]);
		}
		return $i;
	}

	function upgrade_page_template(&$page, &$page_template) {
	}
} # end class

?>