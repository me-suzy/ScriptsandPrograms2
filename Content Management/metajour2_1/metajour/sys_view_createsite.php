<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view.php');
require_once('basic_field.php');
require_once('install/createsite.inc.php');

class sys_view_createsite extends basic_view {

	function loadLanguage() {
		basic_view::loadLanguage();
	}
	
	function titleBar() {
		return '<div class="metatitle">'.$this->shadowtext('Create new company / site').'</div>';
	}

	function viewStart() {
		return '<div class="metawindow">';
	}
	
	function viewEnd() {
		return '</div>';
	}

	function beforeForm() {
		return '<div class="metabox">';
	}
	
	function afterForm() {
		return '</div>';
	}

	function view() {
		$result .= $this->viewStart();
		$result .= $this->titleBar();
		$result .= $this->beforeForm();
		
		if ($this->data['step'] == '2') {
			$failure = false;
			if ($this->data['password'] != $this->data['passwordcheck']) {
				$result .= "Passwords doesn't match<BR>";
				$failure = true;
			}
			if ($this->data['viewer_path'] == '') {
				$result .= "You have to enter the path to the documentroot<BR>";
				$failure = true;
			}
		
			if ($this->data['viewer_url'] == '') {
				$result .= "You have to enter the URL for the site<BR>";
				$failure = true;
			}
		
			if ($this->data['name'] == '') {
				$result .= "You have to enter a name for the site<BR>";
				$failure = true;
			}
		
			if ($this->data['username'] == '') {
				$result .= "You have to enter af name for the administrator account<BR>";
				$failure = true;
			}
	
			if (!$failure) {
				$site = createSite($this->data['username'], $this->data['password'], $this->data['viewer_path'], $this->data['viewer_url'], $this->data['name']);
				$result .= "<strong>Site has been created with number: ".$site."<BR>";
				$result .= "Place this number as the value of \$site in site.php<P>";
				$result .= "Copy the files index.php, showpage.php and site.php to ".$viewer_path."<P>";
				$result .= "Remember to edit the \$viewer_path, \$viewer_url and \$site variables in site.php</strong>";
			}
		}
		
		if ($this->data['step'] != '2' || $failure) {
			$result .= '<form name="metaform" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">';
			$result .= '<input type="hidden" name="_DONTCONVERT_" value="1">';
			$result .= '<input type="hidden" name="step" value="2">';
			$result .= $this->returnMePost();
			$result .= $this->makeField('Name of site','<input type="text" name="name" size=60 value="MyWebsite, Inc.">');
			$result .= $this->makeField('Absolute path to documentroot','<input type="text" name="viewer_path" size=60>');
			$result .= $this->makeField('URL to site','<input type="text" name="viewer_url" size=60>');
			$result .= $this->makeField('Administrator name','<input type="text" name="username" value="administrator">');
			$result .= $this->makeField('Administrator password','<input type="password" name="password">');
			$result .= $this->makeField('Repeat password','<input type="password" name="passwordcheck">');
			$result .= $this->makeField('Initial language','<select name="language"><option value="EN">English</option><option value="DA">Danish</option></select>');
	
			$result .= $this->returnviewpost($this->view);
			$result .= '<div style="padding-bottom: 14px;">';
			$result .= '<input id="submit1" name="submit1" type="submit" class="mformsubmit" value="Create">';
			$result .= '</div>';
			$result .= '</form>';
		}
		$result .= '<br><br><br>';
		$result .= $this->afterForm();
		$result .= $this->viewEnd();;
		return $result;
	}
}

?>