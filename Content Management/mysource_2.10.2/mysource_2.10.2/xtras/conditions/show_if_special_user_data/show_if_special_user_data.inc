<?php 
    ##############################################
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
## $Source: /home/cvsroot/xtras/conditions/show_if_special_user_data/show_if_special_user_data.inc,v $
## $Revision: 1.5 $
## $Author: dofford $
## $Date: 2004/01/15 23:24:57 $
#######################################################################
global $INCLUDE_PATH, $SQUIZLIB_PATH, $EDIT_DIR;
require_once("$INCLUDE_PATH/condition.inc");
require_once("$SQUIZLIB_PATH/form/form.inc");
require_once("$SQUIZLIB_PATH/var_serialise/var_serialise.inc");
#---------------------------------------------------------------------#

class show_if_special_user_data extends Condition {
	var $width = 400;
	var $height = 350;


	  #############################################
	 # gets called to evaluate the condition     #
	#############################################
	
	function evaluate($val) {
		$session = &get_mysource_session();
		$user = $session->user;
		if (!$user->id) return false;
		
		$users_sys = &get_users_system();
		$db = &$users_sys->get_db();
		
		$selections = array();

		$selections = unserialize(urldecode($val));
		
		$orgs = array();
		$curr_form = "";
		
		$curr_orgid = "";
		$found = 0;
		$success = false;
		$question_count = 0;
		$current_question = "";

		foreach(array_keys($selections['searchon']) as $searchon){
			
			# get the organisation, section, question and value
			# from the seralised data

			list($oid, $sid, $qid, $val) = explode("_", $searchon); 
			if($qid != $current_question){
				if($success == false && $current_question != ""){
					return false;
				}
				$current_question = $qid;
				$found = 0;
			}
			#only need to get the form when we have a new orgainsation
			if($curr_orgid != $oid){
				$curr_form = $db->single_element("SELECT answers FROM affiliation WHERE userid='" . $user->id . "' AND organisationid='" . $oid . "'");
				$found = 0;
				if($success == false && $curr_orgid != ""){
					return false;
				}
				$curr_orgid = $oid;
			} 
			
			$form = $curr_form;
			if(!$form) return false;
			$form = unserialize($form);
			
			# insane search algorithm

			foreach($form as $sectionid => $questions){
				foreach($questions as $questionid => $question){
					if(in_array($question['type'], array('listbox', 'tickbox'))){
						if($sid == $sectionid && $qid == $questionid){
							$andor = $selections['andor'][$oid . "_" . $sid . "_" . $questionid];
							
							# more than one value was selected
							if(is_array($question['value'])){
								foreach($question['value'] as $value){
									if(strtolower($value) == strtolower($val)){	
										$found++;
										if(strtolower($andor) == 'or'){
											return true;
										} else {
											if($this->num_question_values($selections['searchon'], $oid, $sid, $qid) == $found){
												$success = true;
											} else {
												$success = false;
											}
										}
									}
								}
							# only one value was selected (radio button)
							} else {
								if($question['value'] == $val){
									if(strtolower($andor) == 'or'){
										return true;
									} else {
										$success = true;
										$found = $this->num_question_values($selections['searchon'], $oid, $sid, $qid);
									}
								} else {
									return false;
								}
							}# end else / if
						} # end if
					} # end if
				} # end foreach
			} # end foreach

			#check if we are at the last loop and we have not a match (in the and)
			if($found != $this->num_question_values($selections['searchon'], $oid, $sid, $qid)){
				$success = false;
			}
		} # end foreach
		if($success == true){
				return true;
		}
	}#end evaluate()

	   #########################################################
	  # function to return the number of                      #
	 # values that a question has (for multiple selections)  #
	#########################################################
	
	function num_question_values($vals, $orgid, $sectionid, $questionid){
		if(is_array($vals)){
			$count = 0;
			foreach(array_keys($vals) as $val){
				if(preg_match("/$orgid\_$sectionid\_$questionid\_.+/", $val)){
					$count++;
				}
			}
		}
		return $count;
	}


	  ####################################
	 # Print the backend                #
	#################################### 
	
	function print_backend() {
		if ($_GET['iframe'] != 1) {
			?>
			<IFRAME SRC="<?=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&iframe=1'?>" NAME="show_if_special_user_data" ID="show_if_special_user_data" SCROLLING="yes"  MARGINWIDTH=0 MARGINHEIGHT=0 FRAMEBORDER="No" ALLOWTRANSPARENCY="true" STYLE="background-color: #FFFFFF; width: 100%; height: 100%;"></IFRAME>
			<?
			return;
		}

		$this->print_javascript();

		if(!isset($_GET['page'])){
			$page = 1;
		} else {
			$page = $_GET['page'];
		}
		
		echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\" name=\"cond_form\" method=\"GET\">\n";
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n";
		echo "<tr>";
		echo "<td align=\"center\"><B>Special User Data Show If - Page " . $page . "</b></td>\n";
		echo "</tr>\n";
	 
		switch($page){
			case "1":
				$this->setup_previous_selections();
				$this->get_organisations();
				break;
			case "2":
				$this->get_sections();
				break;
			case "3":
				$this->encapsulate_values();
				break;
		}
		$this->create_hidden_values();

		($page < "2") ? $button = "Next -->" : $button = "Finish";
		
		echo "<tr><td align=\"center\"><input type=\"Submit\" value=\"" . $button ."\" name=\"Submit\"></td></tr>";
		echo "</table>";
		echo "</form>";
		
	}
	     #################################################
	    # create some hidden values so that             #
	   # we can post back to the form.                 #
	  # bodycopy.php will get these values and        #
	 # load this do again                            #
	#################################################
	
	function create_hidden_values(){
		$ignore = array (
			"page",
		);
		foreach($_GET as $name => $value){
			if(in_array($name, $ignore)) continue;
			print "<input type=\"hidden\" name=\"" . $name . "\" value=\"" . $value . "\">";
		}
	}
	
	   #########################################
	  # Function to get the sections for the  #
	 # backend                               #
	#########################################
	
	function get_sections(){
		if(!isset($_GET['org'])){
			echo "could not get the organisation";
		}
		$orgs = array();
		$orgs = $_GET['org'];

		echo "<tr><td><br><br>Please Select the question values you want to search on: <br></td></tr>";
	
		foreach($orgs as $orgid) { 
			echo "<tr><td align=\"center\"><table border=1 bordercolor=\"#ebebeb\" cellpadding=\"9\" cellspacing=\"3\">";

			# get the organisation so that we can get the name of it (without sticks)
			
			$users_system = &get_users_system();
			$org = &$users_system->get_organisation($orgid);
			
			echo "<tr><td colspan=\"2\" style=\"color: red\"><B>Organisation: " . $org->name . "</B></td></tr>";

			# get the form for this organisation
			
			$form = $this->get_special_user_data_form($orgid);

			foreach ($form->sections as $sectionid => $section) {
				if(trim($section['title']) == ""){
					$title = "(No Title Specified)";
				} else {
					$title = $section['title'];
				}
				print "<tr><td colspan=\"2\"><B>Section " . $title . "</B></td></tr>\n";
				if(count($section['questions']) < 1 ){
					print "<tr><td colspan=\"2\">There are no questions for this Section</td></tr>\n";
				} else {

					
					foreach ($section['questions'] as $questionid => $question) {
						$type = $form->get_question_type($sectionid, $questionid);
						if(!in_array($type, array("tickbox", "listbox"))) continue;

						echo "<tr><td valign=\"top\">" . $question->title . "";
						echo "<br><select name=\"andor[" . $orgid . "_" . $sectionid . "_" . $questionid . "]\">";
						
						$and = '';
						$or  = '';
						if($this->get_previous_selection('andor', $orgid, $sectionid, $questionid, 'or')){
							$or = " selected";
						} elseif($this->get_previous_selection('andor', $orgid, $sectionid, $questionid, 'and')){
							$and = " selected";
						}
						
						echo "<option value=\"or\"" . $or .  ">Or</option>\n<option value=\"and\"" . $and . ">And</option></td>\n";
						echo "</select>";
						
						echo "<td>";
						
						foreach($question->options as $name => $value){
							$checked = "";
							if($this->get_previous_selection('searchon', $orgid, $sectionid, $questionid, $value)){
								$checked = " checked";
							}
							echo "<input type=\"checkbox\" name=\"searchon[" . $orgid . "_" . $sectionid . "_" . $questionid . "_" . $value . "]\" value=\"1\" " . $checked . ">$name<br>\n";
						}
						
						echo "</td></tr>";

					}
				}
			}
			echo "</table>";
		}
		echo "</td></tr>";
		echo "<input type=\"hidden\" value=\"3\" name=\"page\">";
	}
	    ##############################################
	   # get the previous selections that           #
	  # the user made and return true if this      #
     # one was selected last time                 #
	##############################################

	function get_previous_selection($type, $orgid, $sectionid, $questionid, $value=''){
		$prev = $_GET['previous'];
		$prev = unserialize(urldecode($prev));
		foreach($prev[$type] as $answer => $val){
			$v = '';
			list($o, $s, $q, $v) = explode("_", $answer);
			if($type == 'searchon'){
				if($orgid == $o && $sectionid == $s && $questionid == $q && $value == $v){
					return true;
				} 
			} elseif($type == 'andor') {
				if($orgid == $o && $sectionid == $s && $questionid == $q && (strtolower($value) == strtolower($val))){
					return true;
				} 
			}
		}
		return false;
	}
	   ###################################################
	  # function that gets the previous selections      #
	 # and submits them so PHP can use them            #
	###################################################
	
	function setup_previous_selections(){
	?>
		<script language="Javascript">
		setup_previous_sections();
		</script>
	<?
	}
	  #############################################
	 # get the form for that orgid               #
	#############################################
	
	function get_special_user_data_form($orgid) {
		if (!$orgid) return new Form();
		
		$users_sys = &get_users_system();
		$db = &$users_sys->get_db();
		$special_data_form = $db->single_element("SELECT form FROM organisation WHERE organisationid='$orgid'");
		return new Form($special_data_form);
	}
	   #######################################
	  # function to get the organisations   #
	 # for the backend                     #
	#######################################

	function get_organisations(){
		
		echo "<tr><td align=\"center\"><B>Step 1 - Please Select the Organisations</B></td></tr>\n";
		echo "<tr><td align=\"center\">";
		echo "<select name=\"org[]\" multiple>\n";
		
		$users_system = &get_users_system();
		$org_tree = array();
		$org_tree = $users_system->org_array_with_sticks();
		
		foreach($org_tree as $id => $name){
			echo "<option value=" . $id . ">" . $name . "</option>";
		}
		
		echo "</select></td></tr>\n";
		echo "<input type=\"hidden\" value=\"2\" name=\"page\">";
	}

	  #########################################
	 # function to create some javascript    #
	#########################################
	
	function print_javascript() {
	?>
		<script type="text/javascript" language="javascript">
			// Command to load the value
			var iFrame_window = window.top;
			var getVal = iFrame_window.opener.document.main_form.showif_conds.value;
			//alert(getVal);

			// Function to save the value
			function save_conditions(returnVal) {
				var iFrame_window = window.top;
				var retVal = returnVal;
				iFrame_window.opener.document.main_form.showif_conds.value = retVal;
				iFrame_window.close();
			}
			function setup_previous_sections(){
				document.write("<input type=\"hidden\" name=\"previous\" value=\"" + getVal + "\">");
			}
			
		</script>
	<?
	}
	   ###############################################
	  # encapsulate the values so we can send them  #
	 # with javascript                             #
	###############################################

	function encapsulate_values(){
		$values = array();

		$values['searchon']		= $_GET['searchon'];
		$values['andor']		= $_GET['andor'];

		if(isset($_GET['searchon'])){
			if(count($_GET['searchon'])){
				$ser = urlencode(serialize($values));
			}
		}
		?>
		<script language="Javascript">
		save_conditions("<?= $ser ?>");
		</script>
		<?
	}


}# end class show_if_special_user_data

?>