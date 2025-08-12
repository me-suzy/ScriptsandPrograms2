<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Page Template Xtra ------ PHP4 ---------##
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
## File: form/form.inc
## Desc: A PHP class that stores the sructure of a customized form, also includes element classes
## $Source: /home/cvsroot/squizlib/form/form.inc,v $
## $Revision: 2.64.2.1 $
## $Author: tbarrett $
## $Date: 2004/05/12 07:53:52 $
#######################################################################
global $INCLUDE_PATH;
$FORM_DIR = dirname(__FILE__);
require_once("$INCLUDE_PATH/backend.inc");
require_once("$FORM_DIR/formelement.inc");
require_once("$FORM_DIR/formelement_standard.inc");
require_once("$FORM_DIR/formelement_special.inc");
require_once("$FORM_DIR/formrule.inc");
#---------------------------------------------------------------------#

global $FORM_NUMBERING_SCHEMES;
$FORM_NUMBERING_SCHEMES = array(
	'd' => '1,2,3...',
	'a' => 'a,b,c...',
	'A' => 'A,B,C...',
	'r' => 'i,ii,iii...',
	'R' => 'I,II,III...',
);


/**
* The FORM
*
* <br/><br/>Check the comments on all the class vars for specifics, but basically the form consists of sections
* that contain questions. You can develop the form in the backend and change the ordering of the questions
* as well as things like the types of the questions (listbox/textbox etc) and the order that the questions 
* should be displayed on the frontend.
*
* <br/><br/>When the form is printed out on the frontend, it uses the section_question_order var to determine
* order that the questions should be printed in. 
*
* <br/><br/>A lot of functions use the argument $order, which is basically the position of the section 
* that it is printed on the frontend. e.g. You might build a form with 3 sections in the backend, but 
* decide that you want to print the third section first. In that case, when you were using any of the 
* functions that required an $order argument, you would input '1' (the first section displayed), rather 
* than the sectionid. A useful functions are get_section_question_order if you have the sectionid and need
* the order, or get_ordered_sectionid if you have the order but need the sectionid to use in the 
* $form->sections area.
*
* @package Form
*/
class Form extends Object {
		 
	 ###################################
	# Variables

	/**
	* Name of the form, may be changed by the controlling software to distinguish it from other forms.
	*
	* @var string
	*/
	var $form_name  = 'form'; 

	/**
	* Title of the form
	*
	* Title of the form
	* @var string
	*/
	var $form_title = 'Form';    

	/**
	* A flag that decides whether or not to show the title on the frontend.
	*
	* @var boolean
	*/
	var $show_title = 1;


	/**
	* Specifies the alignment of the form when it is printed out on the page
	*
	* @var string
	* @see alignment()
	*/
	var $form_alignment = ''; 

	/**
	* An array of sections. The form starts with one section (undeletable).
	*
	* <br/><br/>Each section contains a title, note and an array of questions.  The sections
	* are indexed by sectionid, and the index never changes (even when you reorder the 
	* sections in the form) so you can always refer to the same section in the form
	* by its sectionid regardless of if other sections are moved or deleted. The order that
	* the questions are printed in is controlled by the $section_question_order.
	* <br/><br/>
	* The Questions array in the sections also maintain their unique id. The each question has a 
	* number of attributes possibly including title, note, value, multiple, options, width, height, columns
	* <br/>
	*
	* @var array
	* @see $section_question_order, FormElement::FormElement(), FormElementOptions::FormElementOptions()
	*	FormElementHidden::FormElementHidden(), FormElementTextbox::FormElementTextbox(),
	*	FormElementListbox::FormElementListbox(), FormElementTickbox::FormElementTickbox()
	*/
	var $sections = array(); 

	/**
	* The width of the column holding the questions - can be a percentage or pixel  value
	*
	* @var string
	*/
	var $question_column_width;  

	/**
	* The width of the column holding the answer- can be a percentage or pixel  value
	* 
	* @var string
	*/
	var $answer_column_width;    

	/**
	* Array of questionid => extra info, a variable to hold temporary information 
	* 
	* @var array
	*/
	var $question_list_cache = array();
	
	/**
	* An array of rules used for questions to define which ones must be answered or be a particular format, etc
	* 
	* <br/><br/>
	* no significance for the keys in the array. Array value is itself another array with he type of error 
	* message to print, questionid it refers to, and other useful info.
	* @var array
	* @see FormRule::FormRule()
	*/
	var $rules = array();

	/**
	* An array of rule descriptions, only populated when a check is done, and only includes the broken rules
	* 
	* @var array
	* @see check(), FormRule::FormRule()
	*/
	var $check_results = array();  # Texural report on the results of a check against the rules


	/**
	* Type of scheme used for numbering sections on the frontend. 
	* 
	* 'd' => '1,2,3...',
	* 'a' => 'a,b,c...',
	* 'A' => 'A,B,C...',
	* 'r' => 'i,ii,iii...',
	* 'R' => 'I,II,III...',
	*
	* @var string
	* @see section_index(), question_index()
	*/
	var $section_numbering_scheme  = 'R';

	/**
	* Flag to indicate whether or not to show numbering for sections
	* 
	* @var boolean
	* @see meta_form(), render()
	*/
	var $show_section_numbers  = 1;

	/**
	* Type of scheme used for numbering questions on the frontend. 
	* 
	* 'd' => '1,2,3...',
	* 'a' => 'a,b,c...',
	* 'A' => 'A,B,C...',
	* 'r' => 'i,ii,iii...',
	* 'R' => 'I,II,III...',
	*
	* @var string
	* @see section_index(), question_index()
	*/
	var $question_numbering_scheme  = 'a';

	/**
	* Flag to indicate whether or not to show numbering for questions
	* 
	* @var boolean
	* @see meta_form(), render()
	*/
	var $show_question_numbers = 1;

	/**
	* ?
	* 
	* @var boolean
	*/
	var $next_sectionid;

	/**
	* Specifies the text on the reset button. If the string is blank, the reset button is not printed. 
	* 
	* @var string
	* @see meta_form(), render()
	*/
	var $reset_button_text  = ''; # Button is not shown if blank

	/**
	* Specifies the text on the submit button. 
	* 
	* @var string
	* @see meta_form(), render()
	*/
	var $submit_button_text = 'Submit';

	/**
	* Specifies the text on the back button
	*
	* @var string
	* @see meta_form(), render()
	*/
	var $back_button_test = 'Back';

	/**
	* Specifies the text on the next button (will be the same as the submit button if blank)
	*
	* @var string
	* @see meta_form(), render()
	*/
	var $next_button_text = '';

	/**
	* An array of codename=>value pairs that can be used to automatically fill in questions on the form
	* 
	* @var array
	* @see offer_auto_answer(), meta_form()
	*/
	var $auto_answer_offers = array(); 

	/**
	* An array of sectionid.questionid=>auto_answer_type pairs - which questions should be autofilled with what
	* 
	* @var array
	* @see answer_auto_answer(), get_auto_answer_allocations(), update()
	*/
	var $auto_answer_assignments = array(); # Assigning the offered answers to questions

	/**
	* This is where the answers are stored before rendering
	* 
	* @var array
	*/
	var $auto_answer_answers = array(); # This is where the answers are stored before rendering

	/**
	* An array of codename=>value pairs which are requests for information from the outside code
	*
	* More documentation in request_information() as well as links to other relevant functions.
	* 
	* @var array
	* @see request_information()
	*/
	var $information_requests = array(); # Requests for information from outside code

	/**
	* An array of sectionid.questionid=>request_type pairs - which questions are assigned what requests
	* 
	* More documentation is available in the relevant functions listed.
	*
	* @var array
	* @see update(), get_requested_information(), get_requested_information_allocations(), 
	*	answer_requested_information()
	*/
	var $information_request_assignments = array(); # Assigning these requests to questions by admin

	/**
	* The order in which the sections and questions in the form should be printed on the frontend.
	* 
	* An array of sectionids containing an array of questionids
	* @var array
	* @see meta_form(), question_select_array(), update(), render(), process(), print_answers(),
	*	text_summary(), export_answers(), import_answers(), get_ordered_sectionid(),
	*	get_section_question_order()
	*/
	var $section_question_order = array(); # What order are the sections and questions

	/**
	* An array of different variables passed to form elements based on type
	* 
	* @var array
	* @see offer_standard_var(), new_question()
	*/
	var $standard_vars = array(); # 

	/**
	* An array of questionids that will not be rendered, processed or included in text/html summaries. 
	* 
	* @var array
	* @see render(), process(), text_summary(), export_answers(), hide_questions()
	*/
	var $hidden_questionids = array(); # A list of questionids that will not render or process - temp.

	/**
	* A boolean to say wether this form should use session auto answering
	* 
	* @var object
	* @see	render(), process(), answer_session_auto_answers(), use_meta_session_auto_answer(), 
	*		use_session_auto_answer(), set_session_auto_answer()
	*/
	var $session = false;

	/**
	* The value to store the session auto answers for the form in
	* 
	* @var string
	* @see	render(), process(), answer_session_auto_answers(), use_meta_session_auto_answer(), 
	*		use_session_auto_answer(), set_session_auto_answer()
	*/
	var $session_auto_prefix = '/xtras/squizlib/form/session_vals/';

	/**
	* Constructor
	* 
	* @param string $serialized A serialized form object
	* @returns object Form
	* @access public
	*/
	function Form ($serialized='') {
		$this->sections["1"]=  array("title" => "", "questions" => array());
		$this->section_question_order["1"] = "";
		if ($serialized) {
			if (!$this->unpack($serialized)) {
				$this->name($$serialized);
			} else {
				# An integrity check to make sure that all sections/questions are in the order array
				list($missing_sectionids) = array_compare(array_keys($this->sections),array_keys($this->section_question_order));
				for(reset($missing_sectionids);$sectionid=current($missing_sectionids);next($missing_sectionids)) {
					$this->section_question_order[$sectionid] = array_keys($this->sections[$sectionid][questions]);
				}
				for(reset($this->sections); $sectionid = key($this->sections); next($this->sections)) {
					list($missing_questionids) = array_compare(array_keys($this->sections[$sectionid][questions]),$this->section_question_order[$sectionid]);
					for(reset($missing_questionids);$questionid=current($missing_questionids);next($missing_questionids)) {
						$this->section_question_order[$sectionid][] = $questionid;
					}
				}
			}
		}
	}

	/**
	* Sets the alignment of the form. If $new_name is not supplied it will return the current name
	* 
	* @param string $new_name A new name for the form 
	* @returns string 
	* @access public
	*/
	function name($new_name='') {
		if ($new_name) 	$this->form_name = substr(ereg_replace("[^a-zA-Z0-9\_]+","",ereg_replace("[ \t\n\r]+","_",$new_name)),0,20);
		return $this->form_name;
	}

	/**
	* Sets the title of the form. If $new_title is not supplied it will return the current title
	* 
	* @param string $new_title A new title for the form 
	* @returns string 
	* @access public
	*/
	function title($new_title='') {
		if ($new_title) $this->form_title = ereg_replace("[ \t\n\r]+"," ",$new_title);
		return $this->form_title;
	}

	/** 
	* Add to standard vars
	* 
	* Add to standard vars
	* @param string $type 
	* @param string $name
	* @param string $value
	* @returns void
	* @access public
	* @see $standard_vars
	*/
	function offer_standard_var($type='',$name='',$value='') {
		if($type && $name) $this->standard_vars[$type][$name] = $value;
	}

	/** 
	* Sets the alignment of the form. If $new_alignment is not supplied it will return the current alignment
	* 
	* @param string $new_alignment A new alignment to set the form to ('left', 'right' or 'center')
	* @returns string
	* @access public
	*/
	function alignment($new_alignment='') {
		if (isset($new_alignment)) {
			$new_alignment = strtolower($new_alignment);
			$this->form_alignment = $new_alignment;
		}#end if
		return $this->form_alignment;
	}


	/** 
	* Converts the object into a nice addslashed string ready to save in the database
	* 
	* @returns string
	* @access public
	* @see unpack()
	*/
	function pack() {
		return addslashes(serialize($this));
	}
	
	/** 
	* Takes a string, and unpacks it into the object itself.
	* 
	* @param string $stringified A serialized string of the form object
	* @returns boolean
	* @access public
	* @see pack(), Form()
	*/
	function unpack($stringified='') {
		$new_object = unserialize($stringified);
		if (strtolower(get_class($new_object)) == "form") {
			$this = $new_object;
			return true;
		} else {
			$new_object = unserialize(stripslashes($stringified));
			if (($type = strtolower(get_class($new_object))) == "form") {
				$this = $new_object;
				return true;
			} else {
				if ($type) {
					$this->_set_error("Attempt to unpack form yielded an object which did not appear to be a form: $stringified");
					return false;
				}
			}
		}
	}


	/** 
	* Given an integer and a format type, returns the int in that format. 
	* 
	* @param string $i The number to display
	* @param string $f Format of the numbering system (e.g. plain numbers or roman numerals)
	* @returns string
	* @access private
	* @see section_index(), question_index()
	*/
	function index_format($i='',$f='') {
		$i = abs((int)$i);
		switch($f) {
		case "d": default:
			return $i;
			break;
		case "a":
			return strtolower(int_to_letter_code($i));
			break;
		case "A":
			return strtoupper(int_to_letter_code($i));
			break;
		case "r":
			return strtolower(int_to_roman($i));
			break;
		case "R":
			return strtoupper(int_to_roman($i));
			break;
		}
	}

	/** 
	* Returns a section index in the desired format
	* 
	* @param string $i The number to display
	* @returns string
	* @access public
	* @see index_format()
	*/
	function section_index($i='') {
		return $this->index_format($i,$this->section_numbering_scheme).".";
	}


	/** 
	* Returns a question index in the desired format
	* 
	* @param string $i The number to display. 
	* @param string $j If provided, it will be the minor number displayed (e.g. the a in III-a)
	* @returns string
	* @access public
	* @see index_format()
	*/
	function question_index($i='',$j='') {
		if ($j) {
			return "(".$this->index_format($i,$this->section_numbering_scheme)."-".$this->index_format($j,$this->question_numbering_scheme).")";
		} else {
			if(ereg("^[0-9]+\.[0-9]+$",$i)) {
				list($i,$j) = explode(".",$i);
				return $this->question_index($i,$j);
			}
			return "(".$this->index_format($i,$this->question_numbering_scheme).")";
		}
	}


	/** 
	* Prints the section of a HTML (meta)form which allows the maintenance of this object
	* 
	* <br/><br/>This function takes care of printing the questions as they are ordered and provides 
	* the interface where you can change the details of the question, such as the question title 
	* the type (textbox,listbox,tickbox etc). 
	* 
	* <br/><br/>It also offers the interface to create new sections and implement rules for the questions
	* in your form. 
	*
	* @param object Backend	$backend Reference to the backend object
	* @param string			$heading Heading of the backend section containing the form
	* @returns void
	* @access public
	* @see $section_question_order, $information_requests, $auto_answer_offers, $auto_answer_assignments, $rules
	*/
	function meta_form(&$backend,$heading='') {
		global $FORM_ELEMENT_TYPES;

		if(!$heading) $heading = $this->name();

		if(!$backend) $backend = new Backend();

		$var_prefix = 'form_'.$this->name().'_';
		?>
		<input type="hidden" name="<?=$var_prefix?>jump_to">
		<input type="hidden" name="<?=$var_prefix?>action">
		<input type="hidden" name="<?=$var_prefix?>active_sectionid">
		<?

		$backend->open_section("$heading - Details");
		?><a name="<?=$var_prefix?>"></a><?

		 #########################################################
		# If you wanted to delete the entire form for some reason
		#$backend->open_field();
		#$backend->print_icon_button("javascript: if (confirm('Are you sure you want to clear the entire form? All sections and questions will be deleted!') && confirm('Really sure? This is irreversible.')) {document.edit.$var_prefix"."action.value='Clear';document.edit.submit()}","Clear Entire Form","delete");

		$backend->open_field('Form Title', 'one_liner', '', false, 175);

		echo text_box($var_prefix.'title',$this->title(), 30, 128,'class=backend_data');

		?><input type=checkbox name="<? echo $var_prefix."show_title"?>" value="1" <? echo (($this->show_title)?" checked":"") ?>>
			(show?)<?

		$backend->open_field('Form Alignment', 'one_liner');
		echo combo_box($var_prefix."alignment",array('' => '', 'left' => 'Left', 'center' => 'Centre', 'right' => 'Right'),$this->form_alignment,'class=backend_data');

		$backend->open_field("Question Column Width", 'one_liner');
		echo text_box($var_prefix."question_column_width",$this->question_column_width, 10, 30,"class=backend_data");
		?><span class="backend_smallprint"> Not required</span><?

		$backend->open_field("Answer Column Width", 'one_liner');
		echo text_box($var_prefix."answer_column_width",$this->answer_column_width, 10, 30,"class=backend_data");
		?><span class="backend_smallprint"> Not required</span><?


		global $FORM_NUMBERING_SCHEMES;

		$backend->open_field("Section Index", 'one_liner');
		echo combo_box($var_prefix."section_numbering_scheme",$FORM_NUMBERING_SCHEMES,$this->section_numbering_scheme,"class=backend_data");
		?><input type=checkbox name="<? echo $var_prefix."show_section_numbers"?>" value="1" <? echo (($this->show_section_numbers)?" checked":"") ?>> (show?)<?

		$backend->open_field("Question Index", 'one_liner');
		echo combo_box($var_prefix."question_numbering_scheme",$FORM_NUMBERING_SCHEMES,$this->question_numbering_scheme,"class=backend_data");
		?><input type=checkbox name="<? echo $var_prefix."show_question_numbers"?>" value="1" <? echo (($this->show_question_numbers)?" checked":"") ?>> (show?)<?


		$backend->open_field("Submit Button Text", 'one_liner');
		echo text_box($var_prefix."submit_button_text",$this->submit_button_text, 10, 30,"class=backend_data");

		$backend->open_field("Reset Button Text", 'one_liner');

		echo text_box($var_prefix."reset_button_text",$this->reset_button_text, 10, 30,"class=backend_data"); 
		?><span class="backend_smallprint"> Leave blank for no reset button.</span><?

		$backend->open_field("Back Button Text", 'one_liner');
		echo text_box($var_prefix."back_button_text",$this->back_button_text, 10, 30,"class=backend_data");
		?><span class="backend_smallprint"> Only applicable if this form is paginated.</span><?

		$backend->open_field("Next Button Text", 'one_liner');
		echo text_box($var_prefix."next_button_text",$this->next_button_text, 10, 30,"class=backend_data");
		?><span class="backend_smallprint"> Submit button text will be used if this is left blank.</span><?

		# Alright, lets print out the sections in the predifined order
		reset($this->section_question_order);
		for($sectionid = 0; $sectionid = key($this->section_question_order); next($this->section_question_order)) {
			$section_order++;
			$section = &$this->sections[$sectionid];
			$section_var_prefix = $var_prefix."section_$sectionid"."_";
			?>
			<input type="hidden" name="<?=$section_var_prefix?>action">
			<input type="hidden" name="<?=$section_var_prefix?>active_questionid">
			<?

			$backend->open_section("$heading - Section ".$this->section_index($section_order));
			echo ("<a name=\"$section_var_prefix\"></a>");

			if (count($this->sections) > 1) { # Must leave at least one section 
				$backend->open_field("Remove?", 'one_liner');
				echo ("<span class=backend_data><input type=checkbox name=$section_var_prefix"."delete value=1> (entire section)</span>");		
			}

			$backend->open_field("Section Title", 'one_liner', '', false, 175);
			echo text_box($section_var_prefix."title",$section['title'], 30, 128,"class=backend_data");

			$backend->open_field("Section Note", 'one_liner');
			echo text_area($section_var_prefix."note",$section['note'], 50, 3, 1024,"class=backend_data");

			$backend->open_field('Section Order', 'one_liner');
			echo text_box($section_var_prefix."order",$section_order,2,3,'class=backend_data');
			?><input type="hidden" name="<?=$section_var_prefix?>old_order" value="<?=$section_order?>"><?

			# Now lets print out the questions in pre-defined order
			$question_order=0;
			reset($this->section_question_order[$sectionid]);
			for($questionid = 0; $questionid = current($this->section_question_order[$sectionid]); next($this->section_question_order[$sectionid])) {
				$question_order++;
				$question = &$this->sections[$sectionid]['questions'][$questionid];
				#if(!$question) {$section[questions] = array();continue;}
				$question_var_prefix = $section_var_prefix."question_$questionid".'_';

				$backend->open_field('Question '.$this->question_index($section_order,$question_order),'one_liner');
				?><a name="<?=$question_var_prefix?>"></a><?
				if(count($this->auto_answer_offers)) {
					echo combo_box($question_var_prefix.'auto_answer',array(""=>"-- No Auto-answer --")+$this->auto_answer_offers,$this->auto_answer_assignments["$sectionid.$questionid"],'class=backend_data').'<br/>';
				}
				if(count($this->information_requests)) {
					echo combo_box($question_var_prefix."information_request",array(""=>"-- No Special Information --")+$this->information_requests,$this->information_request_assignments["$sectionid.$questionid"],'class=backend_data').'<br/>';
				}

				$question->set_session_auto_answer($this->use_meta_session_auto_answer());
				echo combo_box($question_var_prefix."changetype_$questionid",$FORM_ELEMENT_TYPES,$question->type(),"class=backend_data\"");
				$question->meta_element($question_var_prefix,$questionid,$question_order);
			}

			$backend->open_field('More Questions?', 'one_liner');
			if (!$onetoten) {
				for($i = 0; $i <= 10; $i++) $onetoten[] = $i;
				$onetoten[0] = 'No';
			}
			echo combo_box($section_var_prefix.'new_question_count',$onetoten,0,'class=backend_data');
			echo combo_box($section_var_prefix.'new_question_type',$FORM_ELEMENT_TYPES,"textbox",'class=backend_data');
			?>(you can change the types later if you like)<?
		}

		$backend->open_section("$heading - New Section?");

		$backend->open_field('Title', 'one_liner', '', false, 175);
		echo text_box($var_prefix.'new_section_title','', 30, 128,'class=backend_data');
	
		# Grab an array of the questions for putting in combo boxes
		$question_select = $this->question_select_array();

		# RULES !
		$backend->open_section("$heading - Rules");
		?><a name="<?=$var_prefix?>rules"></a><?
		

		# List the rules
		$rule_index = 0;
		if (!empty($this->rules)) {
			foreach($this->rules as $ruleid => $rule) {
				$rule_index++;
				$backend->open_field("Rule $rule_index",'one_liner', '', false, 175);
				$rule->print_backend($this,"$var_prefix"."rule_$ruleid");
			}
		}

		$backend->open_field('New Rule?','one_liner', '', false, 175);
		?><input type="checkbox" name="<?=$var_prefix?>new_rule" value="1"><?
		echo combo_box("$var_prefix".'new_rule_questionid',$question_select,'','class=backend_data',40);
		
		$backend->open_field();
		?><span class="backend_smallprint"><b>Note:</b> On questions that may have multiple answers, <i>greater than</i> and <i>less than</i> refer to the <b>number</b> of answers, not the answers themselves.<?

		# Jump to a particular section if its been asked for
		if($jump_to = $_REQUEST[$var_prefix.'jump_to']) echo js("location = '#$jump_to'");

		$backend->close_section();
	}


	/** 
	* Returns an array with the question id as the key and a more description value. 
	* 
	* If the sectionid is not supplied, then an array of all questions in the form is returned. 
	* @param string $sectionid Section ID to retrieve
	* @returns array
	* @access public
	*/
	function question_select_array($sectionid='') {
		if(!$sectionid) {
			if(!empty($this->question_list_cache)) {
				return $this->question_list_cache;
			}
			$count = count($this->sections);
			$result = array();
			$sectionids = array_keys($this->section_question_order);
			for($sectionid = 0; $sectionid = current($sectionids); next($sectionids)) {
				$result = array_merge($result,$this->question_select_array($sectionid,$sectionid));
			}
			$this->question_list_cache = $result;
			return $this->question_list_cache;
		} else {
			#if(count($this->question_list_cache[$sectionid])) {
			#	return $this->question_list_cache[$sectionid];
			#}
			reset($this->section_question_order[$sectionid]);
			for($questionid = 0; $questionid = current($this->section_question_order[$sectionid]); next($this->section_question_order[$sectionid])) {
				$question = &$this->sections[$sectionid]['questions'][$questionid];
				list($section_order,$question_order) = $this->get_section_question_order($sectionid,$questionid);
				$result["$sectionid.$questionid"] = "Q. ".$this->question_index($section_order,$question_order).": ".ellipsisize($question->title(),30);
			}
			return $result;
		}
	}


	/** 
	* Processes input from the the form generated by meta_form
	* 
	* This is very big and needs more documentation but I'm getting to it...
	* @returns string
	* @access public
	* @see $auto_answer_assignments, $information_request_assignments, $section_question_order
	*/
	function update() {
		global $FORM_ELEMENT_TYPES;

		$var_prefix = "form_".$this->name()."_";

		$action				 = $_REQUEST[$var_prefix.'action'];
		$active_sectionid	 = gpc_stripslashes($_REQUEST[$var_prefix.'active_sectionid']);
		$new_section_title	 = gpc_stripslashes($_REQUEST[$var_prefix.'new_section_title']);
		$new_rule			 = gpc_stripslashes($_REQUEST[$var_prefix.'new_rule']);
		$new_rule_questionid = gpc_stripslashes($_REQUEST[$var_prefix.'new_rule_questionid']);

		# Update form attributes
		$this->title(gpc_stripslashes($_REQUEST[$var_prefix.'title']));
		$this->alignment(gpc_stripslashes($_REQUEST[$var_prefix.'alignment']));

		$this->show_title				 = gpc_stripslashes($_REQUEST[$var_prefix.'show_title']);
		$this->question_column_width	 = gpc_stripslashes($_REQUEST[$var_prefix.'question_column_width']);
		$this->answer_column_width		 = gpc_stripslashes($_REQUEST[$var_prefix.'answer_column_width']);
		$this->section_numbering_scheme  = gpc_stripslashes($_REQUEST[$var_prefix.'section_numbering_scheme']);
		$this->show_section_numbers		 = gpc_stripslashes($_REQUEST[$var_prefix.'show_section_numbers']);
		$this->question_numbering_scheme = gpc_stripslashes($_REQUEST[$var_prefix.'question_numbering_scheme']);
		$this->show_question_numbers	 = gpc_stripslashes($_REQUEST[$var_prefix.'show_question_numbers']);
		$this->submit_button_text		 = gpc_stripslashes($_REQUEST[$var_prefix.'submit_button_text']);
		$this->reset_button_text		 = gpc_stripslashes($_REQUEST[$var_prefix.'reset_button_text']);
		$this->back_button_text			 = gpc_stripslashes($_REQUEST[$var_prefix.'back_button_text']);
		$this->next_button_text			 = gpc_stripslashes($_REQUEST[$var_prefix.'next_button_text']);

		# Perform any special actions
		switch ($action) {
			# Clears everything off the form - brand new (mostly for development)
			case 'Clear':
				$new_form = new Form($this->name());
				$new_form->title($this->title());
				$this = $new_form;
				$message .= $this->title()." form has been cleared.\n";
				break;
			default:
				break;
		}

		# Have all the sections do their work
		# We do not need to worry about the order here..just do 'em from id of 1 upwards
		$section_orders = array();
		$remove_questionids = array();
		$remove_sectionids = array();
		for(reset($this->sections); $sectionid = key($this->sections); next($this->sections)) {
			$section_order++;
			$section = &$this->sections[$sectionid];

			$section_var_prefix = $var_prefix."section_$sectionid"."_";

			$action				= $_REQUEST[$section_var_prefix.'action'];
			$active_questionid	= gpc_stripslashes($_REQUEST[$section_var_prefix.'active_questionid']);
			$new_question_count = gpc_stripslashes($_REQUEST[$section_var_prefix.'new_question_count']);
			$new_question_type	= gpc_stripslashes($_REQUEST[$section_var_prefix.'new_question_type']);
			$new_question_title = gpc_stripslashes($_REQUEST[$section_var_prefix.'new_question_title']);
			$delete				= gpc_stripslashes($_REQUEST[$section_var_prefix.'delete']);
			$order				= gpc_stripslashes($_REQUEST[$section_var_prefix.'order']);
			$old_order			= gpc_stripslashes($_REQUEST[$section_var_prefix.'old_order']);

			$section['title'] = gpc_stripslashes($_REQUEST[$section_var_prefix.'title']);
			$section['note']  = gpc_stripslashes($_REQUEST[$section_var_prefix.'note']);

			# Perform any special actions on the section
			switch ($action) {
				default: break;
			}

			# Delete the ENTIRE SECTION !! (shuffles them down)
			if($delete) $remove_sectionids[] = $sectionid;

			# Reorder the section 
			$section_orders[$order][$old_order] = $sectionid;

			# Have all the questions do their work
			$question_order=0;
			for(reset($section['questions']); $questionid = key($section['questions']); next($section['questions'])) {
				$question_order++;
				$question = &$this->sections[$sectionid]['questions'][$questionid];
				$question_var_prefix = $section_var_prefix."question_$questionid"."_";

				$action				 = $_REQUEST[$question_var_prefix.'action'];
				$delete				 = gpc_stripslashes($_REQUEST[$question_var_prefix.'delete']);
				$auto_answer		 = gpc_stripslashes($_REQUEST[$question_var_prefix.'auto_answer']);
				$information_request = gpc_stripslashes($_REQUEST[$question_var_prefix.'information_request']);
				$order				 = gpc_stripslashes($_REQUEST[$question_var_prefix.'order']);
				$old_order			 = gpc_stripslashes($_REQUEST[$question_var_prefix.'old_order']);
				$changetype			 = gpc_stripslashes($_REQUEST[$question_var_prefix."changetype_$questionid"]);

				$question->set_session_auto_answer($this->use_meta_session_auto_answer());
				if ($qmsg = $question->update($question_var_prefix)) {
					$message .= "Q. ".$this->question_index($sectionid,$questionid).": $qmsg\n";
				}

				# Assign auto-answering	
				if($auto_answer && !$delete) {
					$this->auto_answer_assignments["$sectionid.$questionid"] = $auto_answer;
				} else {
					unset($this->auto_answer_assignments["$sectionid.$questionid"]);
				}

				# Assign requested informaiton
				if($information_request && !$delete) {
					$this->information_request_assignments["$sectionid.$questionid"] = $information_request;
				} else {
					unset($this->information_request_assignments["$sectionid.$questionid"]);
				}

				# Perform any special actions on the question (not covered by itself)
				switch ($action) {
					default: break;
				}

				# Change the questions type
				if ($changetype && $changetype != $question->type()) {
					$message .= $question->change_type($changetype, $this->standard_vars[$changetype]);
				}

				# Delete the question!! (shuffles them down)
				if($delete) {
					$message .= "Q. ".$this->question_index($sectionid,$questionid)." '".$question->title()."' removed\n";
					$remove_questionids[] = $questionid;
				}

				# Reorder the question
				$question_orders[$order][$old_order] = $questionid;
			}

			if (!empty($remove_questionids)) {
				# K let's go through and remove these suckerz
				foreach($remove_questionids as $remove_questionid) {
					unset($section['questions'][$remove_questionid]);
					unset($this->information_request_assignments[$sectionid.'.'.$remove_questionid]);
				}
			}
			unset($remove_questionids);

			# Okay...lets reorder the questions
			# Do this after deleting question and before adding new ones
			ksort($question_orders);
			unset($this->section_question_order[$sectionid]);
			if (!empty($question_orders)) {
				foreach($question_orders as $order => $data) {
					if (!empty($data)) {
						krsort($data);
						foreach($data as $questionid) {
							if($section['questions'][$questionid]) {
								$this->section_question_order[$sectionid][] = $questionid;
							}
						}
					}
				}
			}
			unset($question_orders);

			# Someone wants to create a new question! (or ten)
			if($new_question_count) {
				$qids = array_keys($section['questions']);
				$max = 0;
				if (!empty($qids)) {
					foreach($qids as $qid) {
						$new_questionid = max($new_questionid,$qid);
					}
				}
				for($i = 0; $i < $new_question_count; $i++) {
					if ($q = $this->new_question($new_question_type)) {
						$new_questionid++;
						$section['questions']["$new_questionid"] = $q;
						$q_index = $this->question_index($section_order,count($section['questions']) - count($remove_questionids));
						$section['questions']["$new_questionid"]->title("Question $q_index");
						$this->section_question_order[$sectionid][] = $new_questionid;
						$message .= "New ".$FORM_ELEMENT_TYPES[$new_question_type]." question $q_index has been added.\n";
					} else {
						$message .= "Unable to add new question.\n";
					}
				}
			}
		}

		# K let's go through and remove these suckerz
		if (!empty($remove_sectionids)) {
			foreach($remove_sectionids as $remove_sectionid) {
				unset($this->sections[$remove_sectionid]);
				if (!empty($this->information_request_assignments)) {
					foreach($this->information_request_assignments as $qid => $c) {
						list($s) = explode('.',$qid);
						if ($s == $remove_sectionid) unset($this->information_request_assignments[$qid]);
					}
				}
			}
		}
		unset($remove_sectionids);

		# Okay...lets reorder the sections
		# Do this after deleting sections and before adding new ones
		if (!empty($section_orders)) {
			ksort($section_orders);
			foreach($section_orders as $order => $data) {
				if (!empty($data)) {
					krsort($data);
					foreach($data as $sectionid) {
						if($this->sections[$sectionid]) {
							$section_question_order[$sectionid] = $this->section_question_order[$sectionid];
						}
					}
				}
			}
		}

		unset($section_orders);
		$this->section_question_order = &$section_question_order;

		# Creates a brand spanking new section to fill up with Q's
		if($new_section_title) { # Okay so they want a net section
			$sids = array_keys($this->sections);
			$max = 0;
			if (!empty($sids)) {
				foreach($sids as $sid) {
					$max = max($max,$sid);
				}
			}
			$new_sectionid = $max + 1;
			$this->sections["$new_sectionid"] = array("title" => $new_section_title, "note" => "", "questions" => array());
			$this->section_question_order[$new_sectionid] = "";
			$message .= "New section ".$this->section_index(count($this->sections))." '$new_section_title' has been added.\n";
		}

		# Lets the rules handle themselves.
		$rule_index = 0;
		$remove_ruleids = array();
		if (!empty($this->rules)) {
			for(reset($this->rules); $ruleid = key($this->rules); next($this->rules)) {
				$rule_index++;
				$rule = &$this->rules[$ruleid];
				$rule_var_prefix = $var_prefix."rule_$ruleid";

				$delete		= gpc_stripslashes($_REQUEST[$rule_var_prefix.'delete']);
				$questionid = gpc_stripslashes($_REQUEST[$rule_var_prefix.'questionid']);

				# Let's make sure we're still pointing to a valid question
				if(!$this->question_exists($questionid)) {
					$update_msg = "Appears to have had a missing question.\n";
					$delete = true;
				} else {
					# Update the rule
					$update_msg = $rule->update($this,$rule_var_prefix);
				}
				$message .= (($update_msg)?"Rule $rule_index: $update_msg\n":"");

				# Delete the rule
				if($delete) $remove_ruleids[] = $ruleid;
			}
		}

		# Creates a brand spanking new rule
		if($new_rule) {
			if ($this->question_exists($new_rule_questionid)) {
				$rids = array_keys($this->rules);
				$max = 0;
				if (!empty($rids)) {
					foreach($rids as $rid) {
						$max = max($max,$rid);
					}
				}
				$new_ruleid = $max + 1;
				$this->rules["$new_ruleid"] = new FormRule($new_rule_questionid);
				$message .= "New rule created: $new_ruleid (Q: ".$this->question_index($new_rule_questionid).")";
			}
		}

		# K let's go through and remove these suckerz
		if (!empty($remove_ruleids)) {
			foreach($remove_ruleids as $remove_ruleid) {
				unset($this->rules[$remove_ruleid]);
			}
		}
		unset($remove_ruleids);

		return $message;
	}

	/** 
	* Prints the form - must be printed within externally controlled <form> tags
	* 
	* Prints the form - must be printed within externally controlled <form> tags
	* <br/><br/>If given a order then simply render that section that coincides with that order
	* if supress_submit is true will not print the submit button so you can add it yourself
	* through xtra_html
	*
	* @param int		$order			The section that coincides with that order
	* @param string		$xtra_html		A string of HTML that is printed at the bottom of the rendered form
	* @param boolean	$supress_submit If true the submit button will not be printed
	* @param string		$format			A text string of html that can be used to format the
	*									form using keyword replacements. If the string is
	*									empty, the standard format will be used.
	* @param array		$skip_questions Contains an array of question ids that do not wish to be 
	*									rendered.
	* @returns void 
	* @access public
	* @see $section_question_order, section_index()
	*/
	function render($order,$xtra_html='',$supress_submit=false,$format='') {
		$var_prefix = 'form_'.$this->name().'_';

		$auto_format = true;
		if (trim($format) != '') {
			# we are printing the form using keyword replacements
			$auto_format = false;
		}

		if ($this->show_title) {
			if ($auto_format) {
				# if we need the title then print the a table to contain it and the proper table
				# below so that if the class 'heading' has a background colour it's appearance
				# if consistent over browsers
				?> 
				<table cellpadding=2 cellspacing=0 border=0 <?= ($this->alignment() !="") ? "align=\"" . $this->alignment() . "\"" : "" ?>>
					<tr>
						<td class=heading><?=$this->title()?></td>
					</tr>
					<tr>
						<td>
				<?
			} else {
				$keyword_replacements['form_title'] = $this->title();
			}
		}

		if ($auto_format) {
			?><table cellpadding=4 cellspacing=0 border=0 <?= ($this->alignment() != "") ? "align=\"" . $this->alignment() . "\"" : "" ?> width="100%"><?
		}

		# Do we show all sections or just the one
		if($order) $sectionids = array($this->get_ordered_sectionid((int)$order));
		else $sectionids = array_keys($this->section_question_order);
		for($sectionid = 0; $sectionid = current($sectionids); next($sectionids)) {
			if($order) $section_order = $order;
			else $section_order++;
			$section = &$this->sections[$sectionid];
			$section_var_prefix = $var_prefix."section_$sectionid".'_';
			if ($section_order > 1 && $auto_format) {
				?>
				<tr><td colspan=3><hr noshade size=0></td></tr>
				<?
			}

			if ($auto_format) {
			?>
			<tr>
				<td colspan=1 valign=top>
					<? 
			}
					if($this->show_section_numbers) {
						if ($auto_format) {
							echo "<p class=subheading>".$this->section_index($section_order);
						} else {
							$keyword_replacements['section_'.$section_order.'_index'] = $this->section_index($section_order);
						}
					}
					if ($auto_format) {
					?>
				</td>
				<td colspan=2 valign=top>
					<?
					}
					if($section['title']) {
						if ($auto_format) {
							echo "<p><span class=subheading>".$section['title']."</span><br/>";
						} else {
							$keyword_replacements['section_'.$section_order.'_title'] = $section['title'];
						}
					}
					if($section['note']) {
						if ($auto_format) {
							echo $section['note'];
						} else {
							$keyword_replacements['section_'.$section_order.'_note'] = $section['note'];
						}
					}
					if ($auto_format) {
					?>
				</td>
			</tr>
			<?
			}
			$question_order=0;
			
			reset($this->section_question_order[$sectionid]);
			for($questionid = 0; $questionid = current($this->section_question_order[$sectionid]); next($this->section_question_order[$sectionid])) {
			#for($questionid = 0; $questionid = current($question_set); next($question_set)) {
				# skip the hidden ones
				$question_order++;
				if(in_array("$sectionid.$questionid",$this->hidden_questionids,1)) continue;
				$question = &$this->sections[$sectionid]['questions'][$questionid];
				if ($this->use_session_auto_answer()) {
					$this->answer_session_auto_answers(&$question);
				}
				$question_var_prefix = $section_var_prefix."question_$questionid"."_";

				if ($auto_format) {
				?>
				<tr>
					<td valign=top nowrap>
						<p class=bold>
						<?
				}
						if ($this->show_question_numbers){
							if($this->show_section_numbers){
								if ($auto_format) {
									echo $this->question_index($section_order,$question_order);
								} else {
									$keyword_replacements['section_'.$section_order.'_question_'.$question_order.'_index'] = $this->question_index($section_order,$question_order);
								}
							} else {
								if ($auto_format) {
									echo $this->question_index($question_order);
								} else {
									$keyword_replacements['section_'.$section_order.'_question_'.$question_order.'_index'] = $this->question_index($question_order);
								}
							}
						}
				if ($auto_format) {
					?>
					</td>
					<td valign=top<?=($this->question_column_width) ? " width='$this->question_column_width'" : ''?>>
						<p><span class=bold><?=$question->title?></span><br/>
						<span class=backend_smallprint><?=$question->note?></span>
					</td>
					<td valign=top<?=($this->answer_column_width) ? " width='$this->answer_column_width'" : ''?>>
						<? $question->render($question_var_prefix) ?>
					</td>
				</tr>
				<?
				} else {
					$keyword_replacements['section_'.$section_order.'_question_'.$question_order.'_title'] = $question->title;
					$keyword_replacements['section_'.$section_order.'_question_'.$question_order.'_note'] = $question->note;
					ob_start();
					$question->render($question_var_prefix);
					$buffer = ob_get_contents();
					ob_end_clean();
					$keyword_replacements['section_'.$section_order.'_question_'.$question_order.'_answer'] = $buffer;
					$q = $this->get_question_answer($sectionid, $questionid);
					if(is_array($q)) $q = implode(', ', $q);
					$keyword_replacements['section_'.$section_order.'_question_'.$question_order.'_answer_text'] = $q;
				}

			}
		}

		if ($auto_format) {
		?>
			<tr>
				<td colspan=2>&nbsp;</td>
				<td>
					<? if ($xtra_html) echo $xtra_html; ?>
					<? if ($this->back_button_text && $order > 1) { ?>
						<input type=hidden name=moving_back><input type=button value="<?=$this->back_button_text?>" onclick="form.form_<?=$this->name()?>_action.value='Submit Form';form.current_page.value='<?=($order-1)?>';form.moving_back.value=1;form.submit();">
					<? }
					if ($this->reset_button_text) { ?>
						<input type=reset value="<?=$this->reset_button_text?>">
					<? } 
		
					if(!$supress_submit && $this->submit_button_text) {?>
						<input type=button value="<?=((trim($this->next_button_text) && $order && $order != count($this->sections))?$this->next_button_text:$this->submit_button_text)?>" onclick="form.<?=$var_prefix?>action.value='Submit Form';form.submit();">
						<input type=hidden name=<?=$var_prefix?>action>
					<? } else { ?>
						<input type=hidden name=<?=$var_prefix?>action value="Submit Form">
					<? } ?>
				</td>
			</tr>
		</table>
		<?
		} else {
			if ($this->reset_button_text) $keyword_replacements['reset_button'] = "<input type=reset value=\"$this->reset_button_text\">";
			if ($this->back_button_text && $order > 1) $keyword_replacements['back_button'] = "<input type=hidden name=moving_back><input type=button value=\"".$this->back_button_text."\" onclick=\"form.form_".$this->name()."_action.value='Submit Form';form.current_page.value=".($order-1).";form.moving_back.value=1;form.submit();\">";
			$keyword_replacements['submit_button'] = "$xtra_html <input type=button value=\"".((trim($this->next_button_text) && $order && $order != count($this->sections))?$this->next_button_text:$this->submit_button_text)."\" onclick=\"form.".$var_prefix."action.value='Submit Form';form.submit();\">";
		}

		if ($this->show_title && $auto_format) { 
			# close off the table we opened at the top
			?> 
					</td>
				</tr>
			</table>
			<?
		}

		if (!$auto_format) {
			if (!empty($keyword_replacements)) {
				foreach ($keyword_replacements as $k => $r) {
					$format = str_replace("%$k%",$r,$format);
				}
			}
			# remove unused keywords
			$format = preg_replace('/%[^\s]*%/','&nbsp;',$format);
			echo $format;

			?><input type=hidden name=<?=$var_prefix?>action><?
		}
 
	}


	/** 
	* Does a question with this ID exist or not?
	* 
	* @param string $questionid A questionid in sectionid.questionid format
	* @returns boolean
	* @access public
	*/
	function question_exists($questionid='') {
		list($sid,$qid) = explode(".",$questionid);
		return (
			isset($this->sections[$sid]['questions'][$qid])
			&& ereg("^formelement",get_class($this->sections[$sid]['questions'][$qid]))
		);
	}


	/** 
	* Processes the submitted form - reads in what the user has filled in
	*
	* If order is set then just process the section which corresponds to that order
	* 
	* @param	int		$order		If set then only the section corresponding to the order is processed
	* @param	boolean	$external_file_upload If true, the caller will handle uploading the files
	* @returns	boolean
	* @access public
	* @see FormElement::process()
	*/
	function process($order=0,$external_file_upload=false) {
		 ###########################################
		# Grab all those important global variables
		$var_prefix = 'form_'.$this->name().'_';
		$global_vars = array('action');
		foreach($global_vars as $global_var) {
			$$global_var = $_REQUEST[$var_prefix.$global_var];
			if (is_string($$global_var)) $$global_var = gpc_stripslashes($$global_var);
		}
		
		 #######################################
		# Do something !
		switch ($action) {
		case 'Submit Form':
			# Do we show all sections or just the one
			if($order) $sectionids = array($this->get_ordered_sectionid((int)$order));
			else $sectionids = array_keys($this->section_question_order);
			for($sectionid = 0; $sectionid = current($sectionids); next($sectionids)) {
				if($order) $section_order = $order;
				else $section_order++;
				$section = &$this->sections[$sectionid];
				$section_var_prefix = $var_prefix."section_$sectionid".'_';
				$qs = &$section[questions];
				for(reset($qs); $questionid = key($qs); next($qs)) {
					# skip the hidden ones
					if(in_array("$sectionid.$questionid",$this->hidden_questionids,1)) continue;
					$question = &$section['questions'][$questionid];
					# if the external caller is going to handle the file uploads, skip them
					if($external_file_upload && (get_class($question) == 'formelementfile')) continue;
					$question_var_prefix = $section_var_prefix."question_$questionid".'_';
					$question->process($question_var_prefix);
					if ($this->use_session_auto_answer()) {
						$auto_answer_assignment = $question->session_auto_answer_val;
						if (!empty($auto_answer_assignment)) {
							unset($sess_value);
							$sess_value = $question->value();
							$this->session->set_var($this->session_auto_prefix.$auto_answer_assignment, $sess_value);
						}
					}
				}
			}
			return 1;
		}
		return 0;
	}


	/** 
	* Runs a check on the values currently stored in the form questions, using the rules
	*
	* If order is set then it only checks for questions which do not rely on answers of other sections
	*	* 
	* @param int $order If set then only the section corresponding to the order is checked
	* @returns boolean
	* @access public
	* @see FormRule::check()
	*/
	function check($order=0) {
		# Cycle through the rules
		$result = true;
		if($order) {
			$sectionid = $this->get_ordered_sectionid($order);
			for(reset($this->rules);$rule = current($this->rules); next($this->rules)) {
				if(strtok($rule->questionid,".") == $sectionid) $rules_to_check[] = $rule;
			}
		} else {
			$rules_to_check = &$this->rules;
		}
		for(reset($rules_to_check); $rule = current($rules_to_check); next($rules_to_check)) {
			if(in_array($rule->questionid,$this->hidden_questionids,1)) continue;
			if (!$rule->check($this,$sectionid)) {
				$this->check_results[] = $rule->description($this);
				$result = false;
			}
		}
		return $result;
	}

	/** 
	* Prints the contents of the form in a nice web-friend form, but non-editable
	*
	* If a order is passed in then just print the answers for the corresponding section
	*
	* @param int		$order		If set then only the section corresponding to the order is checked
	* @param boolean	$html_text	Whether or not to print the answers table in HTML
	* @returns boolean
	* @access public
	* @see html_summary(), text_summary()
	*/
	function print_answers($order=0, $html_text=true) {
		include_once(dirname(__FILE__).'/../table_maker/table_maker.inc');
		$table = new table_maker(Array());

		# Do we show all sections or just the one
		if($order) $sectionids = array($this->get_ordered_sectionid((int)$order));
		else $sectionids = array_keys($this->section_question_order);
		for($sectionid = 0; $sectionid = current($sectionids); next($sectionids)) {
			if($order) $section_order = $order;
			else $section_order++;
			$section = &$this->sections[$sectionid];
			$body .= $mail_divider;
			$heading  = (($this->show_section_numbers)?"Section ".$this->section_index($section_order)." ":"");
			$heading .= $section['title'];

			$table->open_row(true);
			$table->open_cell(Array('colspan' => '2', 'class' => 'subheading'));
			echo $heading;

			$question_order=0;
			
			reset($this->section_question_order[$sectionid]);
			for($questionid = 0; $questionid = current($this->section_question_order[$sectionid]); next($this->section_question_order[$sectionid])) {
			#for($questionid = 0; $questionid = current($question_set); next($question_set)) {
				# skip the hidden ones
				$question_order++;
				if(in_array("$sectionid.$questionid",$this->hidden_questionids,1)) continue;
				$question = &$this->sections[$sectionid]['questions'][$questionid];
				$value = $question->nice_value(true);
				if(is_array($value) && !empty($value)) {
					$new_value = "";
					foreach($value as $key => $val) {
						$new_value .= "<li>".htmlspecialchars(nl2br($val))."</li>";
					}
					$value = $new_value;
				} 
				$table->open_row();
				$table->open_cell(Array('valign' => 'top', 'width'=> '30%'));
				echo('<b>' . (
						($this->show_question_numbers)?
						(
							($this->show_section_numbers)?
							$this->question_index($section_order,$question_order):
							$this->question_index($question_order)
						).': ':
						''
					) . $question->title() . '</b>');
				$table->open_cell(Array('valign' => 'top', 'width'=>'70%'));
				echo htmlspecialchars(nl2br($value));
			}
			if ($html_text) {
				$table->open_row(true);
				$table->open_cell(Array('colspan' => 2));
				echo '<hr size=0 noshade>';
			}
		}

		$table->paint($html_text);
	}

	/** 
	* Returns a HTML summary of the answers (does not print it)
	*
	* @param int		$order			If set then only the section corresponding to the order is checked
	* @param boolean	$html_text		Whether or not to print the answers table in HTML
	* @param boolean	$hide_footer	Whether or not to hide the footer data
	* @returns string
	* @access public
	* @see print_answers(), text_summary()
	*/
	function html_summary($order=0, $html_text=true, $hide_footer = false, $extra_data = '') {
		ob_start();
		if (!$hide_footer)	{
			echo $this->title()." - Form Submission<br>";
			echo '<hr size=1 noshade>';
			echo "Date: ".date("r")."<br>";
			echo 'URL:  http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'<br>';
			echo '<hr size=1 noshade>';
		}

		echo $extra_data.'<hr size=1 noshade>';
		
		$this->print_answers($order, $html_text);
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/** 
	* Sends an email with the form contents to the specified address.
	*
	* @param string $extra_data Extra data to append to the end of the results
	* @param string $to The email address to send the mail to
	* @returns boolean
	* @access public
	*/
	function send_mail($extra_data='',$to='') {
		 #########################################
		# Prepare an email of the submitted form
		$body = $this->text_summary($extra_data);

		$system_config = &get_system_config();

		$subject = $this->title();
		$from    = $system_config->webmaster_email;

		if ($to) {
			mail($to,$subject,$body,"From: $from");
			return 1;
		}
		return 0;
	}


	/** 
	* Returns a summary of the form - decides what kind of summary (text/html) to return.
	*
	* By default the function returns a text summary of the entire form, with a header including URL and 
	* date submitted. The main purpose of this function is for use in the custom form page template. 
	* You ask for a form summary and pass the single parameter set value for HTML email or not into this 
	* function, and the form handles which one to return internally.
	* 
	* @param string		$extra_data		Extra data to append to the end of the summary
	* @param boolean	$hide_footer	Flag to supress the printing of extra data like date and URL submitted from
	* @param int		$order			If set then only the section corresponding to the order is printed
	* @param boolean	$html			Whether or not to return the summary in text or HTML.
	* @returns string 
	* @access public
	* @see html_summary(), text_summary(), print_answers()
	*/

	function form_summary($html = false, $extra_data = '', $hide_footer = false, $order = '') {
		if($html) {
			$summary = $this->html_summary($order, $html, $hide_footer, nl2br($extra_data));
		} else {
			$summary = $this->text_summary($extra_data, $hide_footer, $order);
		}
		return $summary;
	}


	/** 
	* Returns a summary of the form formatted for fixed-width text emails etc
	*
	* If an order is specified, only the section that corresponds to that order
	* is summarised. Otherwise a summary of the whole exam is printed. 
	* 
	* @param string		$extra_data		Extra data to append to the end of the summary
	* @param boolean	$hide_footer	Flag to supress the printing of extra data like date and URL submitted from
	* @param int		$order			If set then only the section corresponding to the order is printed
	* @returns string 
	* @access public
	* @see html_summary(), print_answers()
	*/
	function text_summary($extra_data='', $hide_footer=false,$order='') {

		$mail_divider = str_pad("",72,"-")."\n";

		if (! $hide_footer)	{
			$body  = $this->title()." - Form Submission\n";
			$body .= $mail_divider;
			$body .= "Date: ".date("r")."\n";
			$body .= 'URL:  http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\n";
			$body .= $mail_divider;
		}
		
		# Tack on the extra data
		$body .= wordwrap($extra_data,72)."\n";

		if($order) $sectionids = array($this->get_ordered_sectionid((int)$order));
		else $sectionids = array_keys($this->section_question_order);

		# Do each section and question in turn
		#reset($this->section_question_order);
		#for($sectionid = 0; $sectionid = key($this->section_question_order); next($this->section_question_order)) {
		for($sectionid = 0; $sectionid = current($sectionids); next($sectionids)) {
			$section_order++;
			$section = &$this->sections[$sectionid];
			$body .= $mail_divider;
			if($section['title']) {
				$heading  = (($this->show_section_numbers)?"$section_order. ":"");
				$heading .= $section['title'];
				$heading  = $heading . "\n" . str_pad("",strlen($heading),"-") . "\n";
				$body .= "$heading\n";
			}
			if($section[note]) {
				$body .= wordwrap($section[note],72)."\n";
			}
			$body .= "\n";
			$question_order=0;
			reset($this->section_question_order[$sectionid]);
			for($questionid = 0; $questionid = current($this->section_question_order[$sectionid]); next($this->section_question_order[$sectionid])) {
				# skip the hidden ones
				$question_order++;
				if(in_array("$sectionid.$questionid",$this->hidden_questionids,1)) continue;
				$question = &$this->sections[$sectionid]['questions'][$questionid];
				$value = $question->nice_value();

				if(is_array($value) && !empty($value)) {
					$new_value = "";
					foreach($value as $key => $val) {
						$new_value .= "$val\n";
					}
					$value = $new_value;
				}

				$title = (($this->show_question_numbers)?(($this->show_section_numbers)?$this->question_index($section_order,$question_order):$this->question_index($question_order)):"")." ".$question->title();

				$title = wordwrap($title,26);
				$value = wordwrap($value,45);
				$t_array = split("\n|\r|\n\r|\r\n",$title);
				$v_array = split("\n|\r|\n\r|\r\n",$value);
				$lines = max(count($t_array),count($v_array));
				for($i = 0; $i < $lines; $i++) {
					$body .= str_pad($t_array[$i],26," ")." ";
					$body .= str_pad($v_array[$i],45," ")."\n";
				}
				$body .= "\n";
			}
		}

		return $body;
	}


	/** 
	* Returns a HTML string summary of the answers nicely formatted (not printed)
	*
	* @returns string 
	* @access public
	* @see html_summary(), print_answers()
	*/
	function get_answers_html() {
		ob_start();
		$this->print_answers();
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}#end get_html()


	/** 
	* Creates a new question object of the type specified
	*
	* @param string $type	The type of question to create (textbox, listbox, tickbox etc)
	* @param string $title	The title of the question
	* @param string $note	The note for the question
	* @param string $value	The default value of the question (user's answer will replace the value)
	* @returns object FormElement  
	* @access public
	* @see FormElement::FormElement()
	*/
	function new_question($type='', $title='', $note='', $value='',$options='') {
		if(class_exists($class = "FormElement$type")) {
			$q = new $class($title,$note,$value,$options,$this->standard_vars[$type]);
			return $q;
		} else {
			$this->_set_error("Unable to create question of type '$type'.. class doesn't exists",__FILE__,__LINE__);
			return false;
		}
	}


	/** 
	* Returns a string containing information on what questions were answered
	*
	* If order is specified, then only the questions corresponding to that order are 
	* exported. 
	*
	* @param int $order If set then only the section corresponding to the order is exported.
	* @returns string
	* @access public
	* @see import_answers()
	*/
	function export_answers($order=0, $nice_value=false, $html=false) {
		if($order) $sectionids = array($this->get_ordered_sectionid((int)$order));
		else $sectionids = array_keys($this->section_question_order);
		for($sectionid = 0; $sectionid = current($sectionids); next($sectionids)) {
			if($order) $section_order = $order;
			else $section_order++;
			$section = &$this->sections[$sectionid];
			if (!empty($section['questions'])) {
				foreach($section['questions'] as $questionid => $question) {
					if(!$question) continue;
					# skip the hidden ones
					if(in_array("$sectionid.$questionid",$this->hidden_questionids,1)) continue;
					$answers[$sectionid][$questionid]['type'] = $question->type();
					$answers[$sectionid][$questionid]['title'] = $question->title();
					if ($nice_value)
						$answers[$sectionid][$questionid]['value'] = $question->nice_value($html);
					else
						$answers[$sectionid][$questionid]['value'] = $question->value();
				}
			}
		}
		return serialize($answers);
	}

	/** 
	* Returns export_answers with some additional information
	*
	* At the moment its just section title but you can add other attributes fairly easily.
	*
	* If order is specified, then only the questions corresponding to that order are 
	* exported. 
	*
	* @param int $order If set then only the section corresponding to the order is exported.
	* @returns string
	* @access public
	* @see export_answers(), import_anaswers()
	*/
	function export_answers_extended($order=0) {
		$answers = unserialize($this->export_answers($order));
		if (!empty($answers)) {
			foreach($answers as $sectionid => $sectiondata) {
				$answers[$sectionid]['title'] = $this->sections[$sectionid]['title'];
			}
		}
		return serialize($answers);
	}


	/** 
	* Given an exported set of answers, fills in the form with them - NOT WORKING FOR CHECKBOX FIELDS
	*
	* If order is specified, then only the questions corresponding to that order are 
	* imported
	*
	* @param string $answers	Serialized string containing all the answers selected 
	* @param int	$order		If set then only the section corresponding to the order is exported.
	* @returns string
	* @access public
	* @see export_answers()
	*/
	function import_answers($answers='',$order=0) {
		$answers = unserialize($answers);
		$errors = array();
		if($order) $sectionids = array($this->get_ordered_sectionid((int)$order));
		else $sectionids = array_keys($this->section_question_order);
		for($sectionid = 0; $sectionid = current($sectionids); next($sectionids)) {
			if($order) $section_order = $order;
			else $section_order++;
			if(!isset($answers[$sectionid])) {
				$errors[] = "<u></u>Section ".$this->section_index($section_order)." answers missing.";
				continue;
			}
			$answers_sec = &$answers[$sectionid];
			$answers_sec = &$answers[$sectionid];
			$question_order=0;
			$questions = &$this->sections[$sectionid]['questions'];
			for(reset($questions); $questionid = key($questions); next($questions)) {
				$question_order++;
				if(!isset($answers_sec[$questionid])) {
					$errors[] = "Question ".$this->question_index($section_order,$question_order)."'s answer is missing.";
					continue;
				}
				$question = &$questions[$questionid];
				$answer = &$answers_sec[$questionid];
				if($answer["type"] != $question->type()) {
					$errors[] = "Question ".$this->question_index($section_order,$question_order)."'s answer is from a '$answer[type]' field, not a '".$question->type()."' field.";
				}
				if($answer["title"] != $question->title()) {
					$errors[] = "Question ".$this->question_index($section_order,$question_order)."'s ('".$question->title()."') answer is from a field named '$answer[title]'";
				}
				$question->set_value($answer["value"]);
			}
		}
		return $errors;
	}


	/** 
	* Given a quesiton id, returns a pointer to that question, for external interrorgation
	*
	* <br/><br/>Very useful to find things out like which options were available and what values the user selected
	* (Much easier than going $form->sections[questions][questionid][blah])
	*
	* <br/><br/><b>IMPORTANT:</b>If the questionid is not supplied, it is assumed that the sectionid is written as
	* the format questionid.sectionid
	*
	* @param string	$sectionid	Can be a sectionid only or a question id written as sectionid.questionid
	* @param int	$questionid	Optional, but if supplied $sectionid must be an int. See notes for more details.
	* @returns object Question
	* @access public
	* @see get_question()
	*/
	function &get_question_ref($sectionid='',$questionid=0) {
		if(!$questionid) {
			list($sectionid,$questionid) = explode('.',$sectionid);
		}
		$section = &$this->sections[$sectionid];
		if(!$section) {
			$this->_set_error("Attempt to get question from nonexistant section: $sectionid",__FILE__,__LINE__);
			return;
		}
		$question = &$section['questions'][$questionid];
		if(!$questionid) {
			$this->_set_error("Attempt to get nonexistant question: $sectionid.$questionid",__FILE__,__LINE__);
			return;
		}
		return $question;
	}


	/** 
	* Like the function get_question_ref but returns only a copy
	*
	* @param string	$sectionid	Can be a sectionid only or a question id written as sectionid.questionid
	* @param int	$questionid	Optional, but if supplied $sectionid must be an int. See notes for more details.
	* @returns object FormElement
	* @access public
	* @see &get_question_ref()
	*/
	function get_question($sectionid='',$questionid=0) {
		$question = $this->get_question_ref($sectionid,$questionid);
		return $question;
	}


	/** 
	* Answer a specified question with the value supplied
	*
	* <br/><br/>I think that if the sectionid is given as a question reference (i.e. sectionid.questionid) then 
	* the value is sometimes passed as the second paramter instead of the third. 
	* <br/><br/>The function adjusts accordingly by assigning the second passed value (which should be a sectionid)
	* as the value instead and proceeds as normal (that is to say, it proceeds to set the value for the question)
	*
	* @param string	$sectionid	Can be a sectionid only or a question id written as sectionid.questionid
	* @param int	$questionid	Optional, but if supplied $sectionid must be an int. See notes for more details.
	* @returns object FormElement
	* @access public
	* @see &get_question_ref(), FormElement::set_value()
	*/
	function answer_question($sectionid='',$questionid=0,$value='') {
		if(ereg("\.",$sectionid)) {
			$value = $questionid;
			$questionid = "";
		}
		$q = &$this->get_question_ref($sectionid,$questionid);
		$q->set_value($value);
		return $q->set_value($value);
	}
	
	/** 
	* Given a question's id, it returns that questions title
	*
	* @param int $sectionid		The id of the section that the question is in
	* @param int $questionid	The id of the question in the section
	* @returns string
	* @access public
	* @see &get_question_ref(), FormElement::title()
	*/
	function get_question_title($sectionid='',$questionid=0) {
		$question = &$this->get_question_ref($sectionid,$questionid);
		return $question->title();
	}


	/** 
	* Given a question's id, it returns that questions value
	*
	* <br/><br/>The return value will either be a string or an array, depending on whether the question is 
	* a multiple-option type question (e.g. checkbox) or something like a textbox which has only one answer.
	*
	* @param int $sectionid		The id of the section that the question is in
	* @param int $questionid	 The id of the question in the section
	* @returns mixed
	* @access public
	* @see &get_question_ref(), FormElement::value()
	*/
	function get_question_answer($sectionid='',$questionid=0) {
		$question = &$this->get_question_ref($sectionid,$questionid);
		return $question->value();
	}

	/** 
	* Given a question's id, it returns that questions type (eg checkbox, textbox)
	*
	* @param int $sectionid		The id of the section that the question is in
	* @param int $questionid	The id of the question in the section
	* @returns string
	* @access public
	* @see &get_question_ref(), FormElement::type()
	*/
	function get_question_type($sectionid='',$questionid=0) {
		$question = &$this->get_question_ref($sectionid,$questionid);
		return $question->type();
	}


	/** 
	* Automatically fills in the value for a particular question
	*
	* <br/><br/>Lets external code ask the form manager if they want any of the field's filled in
	* automatically. For example, the system could fill in the "Name" field with the 
	* current user's name automagically. But it doesn't know which question is the name
	* question. This function relieves the external code of worry about this and let's
	* the user define which questions they want automagically filled with the informaiton
	* being offered by the external code.
	*
	* @param string $code The codename of the replace (e.g. 'name' or 'email')
	* @param string $name The user-friendly name that is displayed on the meta-form (e.g. "User's name")
	* @returns string
	* @access public
	* @see $auto_answer_offers, answer_auto_answer()
	*/
	function offer_auto_answer($code='',$name='') {
		if(!$name) $name = $code;
		$this->auto_answer_offers[$code] = $name;
	}


	/** 
	* This changes the value of the question which has been assigned to the specified autoanswer code
	*
	* <br/>...or in other words, when the form gets rendered it goes through the auto answers that the 
	* user has said it wants to be done in this form and tries to match it up with the auto answer code that 
	* has been given as an argument. If the question exists, the value is changed to the $answer passed into the 
	* function. 
	* @param string $code The codename of the replace (e.g. 'name' or 'email')
	* @param string $name The answer that will become the value for the qestion
	* @returns int
	* @access public
	* @see $auto_answer_offers, offer_auto_answer()
	*/
	function answer_auto_answer($code='',$answer='') {
		if (!empty($this->auto_answer_assignments)) {
			foreach($this->auto_answer_assignments as $questionid => $c) {
				if ($c == $code) {
					if($this->question_exists($questionid)) {
						$q = &$this->get_question_ref($questionid);
						$q->set_value($answer);
						$count_set++;
					} else {
						unset($this->auto_answer_assignments[$questionid]);
					}
				}
			}
		}
		return $count_set;
	}

	/** 
	* Set the questions to hide. Hidden questions aren't rendered or processed in particular circumstances
	*
	* @param mixed $questionids Can be a single questionid (format sectionid.questionid) or an array of them
	* @returns void
	* @access public
	* @see $hidden_questionids
	*/
	function hide_questions($questionids=array()) {
		if(!is_array($questionids)) $questionids = array($questionids);
		$this->hidden_questionids = $questionids;
	}


	/** 
	* Allows external code to ask whether its auto-answered offers have been allocated by the admin user.
	*
	* Returns false if it hasn't and an array of questionids if it has.
	*
	* @param string $code The code to reference 
	* @returns mixed
	* @access public
	* @see $auto_answer_assignments
	*/
	function get_auto_answer_allocations($code='') {
		$result = array();
		if (!empty($this->auto_answer_assignments)) {
			foreach($this->auto_answer_assignments as $questionid => $c) {
				if ($c == $code) {
					if($this->question_exists($questionid)) {
						$result[] = $questionid;
						$q = &$this->get_question_ref($questionid);
					} else {
						unset($this->auto_answer_assignments[$questionid]);
					}
				}
			}
		}
		return $result;
	}


	/** 
	* Adds a request_information request to the array of information requests.
	*
	* <br/><br/>Information Requests let the system code using the form to request certain answers from the user.
	* That is, say you were making a sign-up form template. You want the admin to be 
	* able to make their own form, but you want to grab certain information form it if
	* you can, and put it in the database.
	* <br/><br/>You could request, for example, the signing-up user's name. The admin would allocate
	* this to her name question, and after the form has been submitted, you can grab the
	* name out of the form without needing to know which question it is in.
	*
	* @param string $code The reference code to use, must be unique in the information requests array
	* @param string $name Nice user-friendly name that is displayed in the metaform
	* @returns void
	* @access public
	* @see $information_requests, get_requested_information(), get_requested_information_allocations(),
	* answer_requested_information()
	*/
	function request_information($code='',$name='') {
		if(!$name) $name = $code;
		$this->information_requests[$code] = $name;
	}

	/** 
	* Given a code, find the question that the code was assigned to and return the question value to the caller.
	*
	* If one result is found, the string value is returned, otherwise an array of string values is returned.
	*
	* @param string $code The reference code to search for
	* @returns mixed
	* @access public
	* @see $information_requests, request_information(), get_requested_information_allocations(),
	* answer_requested_information()
	*/
	function get_requested_information($code='') {
		$result = array();
		if (!empty($this->information_request_assignments)) {
			foreach($this->information_request_assignments as $questionid => $c) {
				if ($c == $code) {
					if($this->question_exists($questionid)) {
						$q = &$this->get_question_ref($questionid);
						$result[] = $q->value();
					} else {
						unset($this->information_request_assignments[$questionid]);
					}
				}
			}
		}
		if (count($result) <= 1) return $result[0];
		return $result;
	}



	/** 
	* Allows external code to find out whether its requested information has been allocated by the admin user.
	* 
	* <br/><br/>A page template (for example) can ask whether its requested information has been
	* allocated to a question in the form by the admin user. If it hasn't, the function returns false. 
	* If it has, then the an array of matching questionids is returned. 
	*
	* @param string $code The reference code to search for
	* @returns mixed
	* @access public
	* @see $information_requests, request_information(), get_requested_information(),
	* answer_requested_information()
	*/
	function get_requested_information_allocations($code='') {
		$result = array();
		if (!empty($this->information_request_assignments)) {
			foreach($this->information_request_assignments as $questionid => $c) {
				if ($c == $code) {
					if($this->question_exists($questionid)) {
						$result[] = $questionid;
						$q = &$this->get_question_ref($questionid);
					} else {
						unset($this->information_request_assignments[$questionid]);
					}
				}
			}
		}
		return $result;
	}


	/** 
	* This changes the value of a the questions which have been assigned to this requested info
	* 
	* Just like auto_answering, except not!
	*
	* @param string $code The reference code to search for
	* @returns mixed
	* @access public
	* @see $information_requests, request_information(), get_requested_information(),
	* get_requested_information_allocations()
	*/
	function answer_requested_information($code='',$answer='') {
		if (!empty($this->information_request_assignments)) {
			foreach($this->information_request_assignments as $questionid => $c) {
				if ($c == $code) {
					if($this->question_exists($questionid)) {
						$q = &$this->get_question_ref($questionid);
						$q->set_value($answer);
						$count_set++;
					} else {
						unset($this->information_request_assignments[$questionid]);
					}
				}
			}
		}
		return $count_set;
	}

	/** 
	* Given an order, find the sectionid that occupies that spot.
	* 
	* @param int $order The order to find
	* @returns int
	* @access public
	* @see $section_question_order
	*/
	function get_ordered_sectionid($order=0) {
		if (!empty($this->section_question_order)) {
			reset($this->section_question_order);

			for($i=1;$i < $order;$i++) {
				next($this->section_question_order);
			}
			return key($this->section_question_order);
		} else {
			return 0;
		}
	}

	/** 
	* Given a sectionid and (optional) questionid, find the order of each in the section_question_order
	* 
	* <br/><br/>If no questionid is given, the order of the sectionid only is returned. Otherwise an array of sectionid, 
	* questionid is returned.
	* 
	* @param int $sectionid		The sectionid to find the order of
	* @param int $questionid	The questionid to find the order of
	* @returns mixed
	* @access public
	* @see $section_question_order
	*/
	function get_section_question_order($sectionid='',$questionid=0) {
		if (empty($this->section_question_order) && !$questionid) return false;
		for(reset($this->section_question_order);$sid=key($this->section_question_order);next($this->section_question_order)) {
			$i++;
			if($sectionid == $sid) break;
		}
		if(!$questionid) return $i;
		
		if (!empty($this->section_question_order[$sectionid])) {
				for(
					reset($this->section_question_order[$sectionid]);
					$qid=current($this->section_question_order[$sectionid]);
					next($this->section_question_order[$sectionid])
					) {
				$j++;
				if($questionid == $qid) break;
			}
		}
		return array($i,$j);
	}


	/** 
	* Allows external code to get a bunch of questions of a certain type
	* 
	* Returns an array of questionids (in sectionid.questionid format) as keys 
	* and a printable string of the question index as the value.
	*
	* @param string $type The type of question we're looking for
	* @returns array
	* @access public
	*/
	function get_questions_by_type($type='') {
		if(!$type) return;
		$result = array();
		if (!empty($this->sections)) {
			for(reset($this->sections); $sectionid = key($this->sections); next($this->sections)) {
				$section = &$this->sections[$sectionid];
				for(reset($section['questions']); $questionid = key($section['questions']); next($section['questions'])) {
					$question = &$section['questions'][$questionid];
					if($question->type() == $type) {
						list($section_order,$question_order) = $this->get_section_question_order($sectionid,$questionid);
						$result["$sectionid.$questionid"] = "Q. ".$this->question_index($section_order,$question_order).": ".$question->title;
					}
				}
			}
		}
		return $result;
	}

	/** 
	* Allows external code to get a bunch of questions
	* 
	* Returns an array of questionids (in sectionid.questionid format) as keys 
	* and a printable string of the question index as the value.
	*
	* @param string $order The section order we are looking at
	* @returns array
	* @access public
	*/
	function get_questions_in_order($order='') {
		$result = array();
		if (!empty($this->sections)) {
			# Do we show all sections or just the one
			if($order) $sectionids = array($this->get_ordered_sectionid((int)$order));
			else $sectionids = array_keys($this->section_question_order);

			for($sectionid = 0; $sectionid = current($sectionids); next($sectionids)) {
				if($order) $section_order = $order;
				else $section_order++;
				$section = &$this->sections[$sectionid];

				reset($this->section_question_order[$sectionid]);
				for($questionid = 0; $questionid = current($this->section_question_order[$sectionid]); next($this->section_question_order[$sectionid])) {
				$question_order++;
				if(in_array("$sectionid.$questionid",$this->hidden_questionids,1)) continue;
					$question = &$section['questions'][$questionid];
					list($section_order,$question_order) = $this->get_section_question_order($sectionid,$questionid);
						$result["$sectionid.$questionid"] = "Q. ".$this->question_index($section_order,$question_order).": ".$question->title;
				}
			}
		}
		return $result;
	}

	/** 
	* Prints the form - must be printed within externally controlled <form> tags
	* 
	* @param object Backend $backend The backend
	* @returns void
	* @access public
	*/
	function render_backend(&$backend) {

		if(!$backend) $backend = new Backend();

		$var_prefix = 'form_'.$this->name().'_';

		echo "<input type=hidden name=$var_prefix"."action>";

		reset($this->section_question_order);
		for($sectionid = 0; $sectionid = key($this->section_question_order); next($this->section_question_order)) {
			$section_order++;
			$section = &$this->sections[$sectionid];
			$section_var_prefix = $var_prefix."section_$sectionid".'_';

			$backend->open_section($section[title]);
			if($section[note]) {
				$backend->open_field('');
				echo "<span class=backend_fineprint>".$section[note]."</span>";
			}
			
			$question_order=0;
			reset($this->section_question_order[$sectionid]);
			for($questionid = 0; $questionid = current($this->section_question_order[$sectionid]); next($this->section_question_order[$sectionid])) {
				$question_order++;
				$question = &$this->sections[$sectionid]['questions'][$questionid];
				$question_var_prefix = $section_var_prefix."question_$questionid".'_';

				if ($this->show_question_numbers){
					if($this->show_section_numbers){
						$backend->open_field($this->question_index($section_order,$question_order)." ".$question->title."<br/><span class=backend_fineprint>".$question->note."</span>",'one_liner');
					} else {
						$backend->open_field($this->question_index($question_order)." ".$question->title."<br/><span class=backend_fineprint>".$question->note."</span>","one_liner");
					}
				} else {
					$backend->open_field($question->title."<br/><span class=backend_fineprint>".$question->note."</span>",'one_liner');
				}
				$question->render($question_var_prefix);
			}
		}
		$backend->print_commit_button('Commit',"document.edit.".$var_prefix."action.value='Submit Form';document.edit.action.value='Commit';document.edit.submit();");
	}

	/** 
	* returns an array of rule_index => ruleid. Index is never zero.
	* 
	* @returns array
	* @access public
	*/
	function get_ruleids() {
		$ret_val = Array();
		$rule_index = 0;
		for(reset($this->rules); NULL !== ($ruleid = key($this->rules)); next($this->rules)) {
			$rule_index++;
			$ret_val[$rule_index] = $ruleid;
		}#end for
		return $ret_val;
	}#end get_ruleids()


	/** 
	* returns a reference to the form rule, given an id
	* 
	* @returns object FormRule
	* @access public
	*/
	function &get_rule($ruleid) {
		return $this->rules[$ruleid];
	}#end get_rule()

	 ##################################
	# Determines if the form should use session auto answering
	function set_session_auto_answer(&$session) {
		# $session === true can be used to allow printing of the form in the backend $this->meta_form()
		$this->session = &$session;
	}

	 ##################################
	# Determines if the form should use session auto answering
	function use_session_auto_answer() {
		# set to true to allow printing in backend of form
		return (get_class($this->session) == 'mysourcesession');
	}

	 ##################################
	# Determines if the form should use session auto answering to render the meta_form() fn
	function use_meta_session_auto_answer() {
		return ($this->use_session_auto_answer() || $this->session === true);
	}

	 #####################################
	# Auto Answer session values
	function answer_session_auto_answers(&$question) {
		if ($this->use_session_auto_answer()) {
			$auto_answer_assignment = $question->session_auto_answer_val;
			if (!empty($auto_answer_assignment) && empty($question->value)) {
				$answer = $this->session->get_var($this->session_auto_prefix.$auto_answer_assignment);
				if (isset($answer)) {
					$question->set_value($answer);
				}
			}
		}
	}
}#end class

?>
