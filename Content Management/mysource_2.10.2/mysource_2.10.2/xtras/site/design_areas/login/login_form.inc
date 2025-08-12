<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
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
## File: xtras/site/design_areas/login/login_form.inc
## Desc: this printe the buttons and all code needed for the login form
## $Source: /home/cvsroot/xtras/site/design_areas/login/login_form.inc,v $
## $Revision: 2.4 $
## $Author: bvial $
## $Date: 2003/11/25 04:17:13 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Login_Form extends Site_Design_Area {

	var $contents;

	function Site_Design_Area_Login_Form(&$_owner) {

		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		$this->contents = Array();

		# declare some variables 

		# editiable
		$this->_set_var("log_in_text", "Login", "text", "text to display when the user is not logged in");
		$this->_set_var("log_out_text", "Logout", "text", "text to display when the user has logged out");
		$this->_set_var("class", "", "text", "stylesheet class for the text");

		# uneditable
		$this->_set_var("log_in_image", "", "text");
		$this->_set_var("log_out_image", "", "text");
		$this->_set_var("logged_in_image", "", "text");
		$this->_set_var("logged_out_image", "", "text");


	}#end Site_Design_Area_Login_Form()

	function create(&$tag) {

		# init all vars
		$this->_set_variables($tag, true);
		 
		#######################################################################################
		# OK, so know that we have created it lets check there are a few necessary values

		# if they want images, then we need login and logout images
		if (!$this->get_val("log_in_image") xor !$this->get_val("log_out_image")) {
			$this->_set_error("Login Form needs Log-In and Log-Out Images", __FILE__, __LINE__);
			unset($this);
			return;
		}

		# if they want rollovers, then we need login and logout images
		if (!$this->get_val("logged_in_image") xor !$this->get_val("logged_out_image")) {
			$this->_set_error("Login Form needs Logged-In and Logged-Out Images", __FILE__, __LINE__);
			unset($this);
			return;
		}


	}#end create()
 
	function paint() {
		global $SESSION;

		$page = &$this->get_page();

		$images = ($this->get_val("log_in_image") && $this->get_val("log_out_image"));
		$rollovers = ($images && ($this->get_val("logged_in_image") && $this->get_val("logged_out_image")));

		if ($SESSION->logged_in()) {
			$text = $this->get_val("log_out_text");
			$href = "./?mysource_session_action=logout&p=".((int)$page->id);
			$status_msg = "Click here to log out.";
			$off_button = "logged_in_image";
			$on_button  = "log_out_image";
		} else {
			$text = $this->get_val("log_in_text");
			$href = "javascript:login_popup();";
			if($this->_owner->get_val('require_ssl')) {
				$refresh_url = $page->get_url(true,1);
				$url = $refresh_url.'mysource_action=login_prompt';
			} else $url = $page->get_url(true).'mysource_action=login_prompt';
			$status_msg = "If you have an account, click here to log in.";
			$off_button = "logged_out_image";
			$on_button  = "log_in_image";
		}#end if
		$swap_images = ($rollovers) ? "log_button_swap();" : "";
		$class = ($this->get_val("class")) ? "class='".$this->get_val("class")."'" : "";
		$text  = ($images) ? "<img name=log_button src='".$this->get_val("IMAGE_PREFIX").$this->get_val($off_button)."' border=0>" : $text;

	?>
		<script language=javascript>
	<?

		if ($rollovers) {
	?>
			var log_button_off = new Image();
			log_button_off.src = '<?=$this->get_val("IMAGE_PREFIX").$this->get_val($off_button) ?>';
			var log_button_on  = new Image();
			log_button_on.src  = '<?=$this->get_val("IMAGE_PREFIX").$this->get_val($on_button) ?>';
			function log_button_swap() {
				if (document.log_button.src != log_button_on.src) {
					document.log_button.src = log_button_on.src;
				} else {
					document.log_button.src = log_button_off.src;
				}
			}
	<?
		}#end if rollovers 
	?>
			function login_popup() {
				<? if($refresh_url) echo 'window.location="'.$refresh_url.'";'; ?>
				// Make it popup in middle of screen
				centerw = screen.width/2 - 100;
				centerh = screen.height/2 - 50;
				login_popup_window  = window.open('<?=$url;?>', 1, 'toolbar=no,width=200,height=100,left='+centerw+',top='+centerh+',nominimize,nomaximize,norestore,scrollbars=no');
			}
			function run_mysource_login(l,p,k) {
				document.loginform.mysource_login.value = l;
				document.loginform.mysource_password.value = p;
				document.loginform.mysource_login_key.value = k;
				document.loginform.submit();
			}
		</script>
		<form method=post action="<? echo $_SERVER['PHP_SELF']?>" name=loginform>
			<input type=hidden name=pageid value=<?=$page->id?>>
			<input type=hidden name=mysource_session_action value=login>
			<input type=hidden name=mysource_login     value="">
			<input type=hidden name=mysource_password  value="">
			<input type=hidden name=mysource_login_key value="">
		</form>
		<a href="<?=$href?>" onmouseover="<?=$swap_images?> window.status='<?=$status_msg?>';return true;" onmouseout="<?=$swap_images?> window.status='';return true;" <?=$class?>><?=$text?></a>
		<?

	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise this object 
	function print_user_backend($prefix_name="") {
		$web_system = &get_web_system();

		$uneditable = array("log_in_image", 
							"log_out_image", 
							"logged_in_image", 
							"logged_out_image");

		$backend = &$web_system->get_backend();
		return $this->print_vars_backend($backend, $prefix_name, $uneditable);

	}

	 
}#end class Site_Design_Area_Login_Form

?>