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
## File: form/formrule.inc
## Desc: Restrictive rules that can be used in securing customized forms
## $Source: /home/cvsroot/squizlib/form/formrule.inc,v $
## $Revision: 2.10 $
## $Author: bvial $
## $Date: 2003/07/28 03:59:50 $
#######################################################################
include_once("$FORM_DIR/formelement_standard.inc");
include_once("$FORM_DIR/formelement_special.inc");
#---------------------------------------------------------------------#

 ####################################
# Global array of form element types
global $FORM_RULE_TYPES;
$FORM_RULE_TYPES= array (
	'answered'     => 'be answered',
	'answered_correctly' => 'be answered correctly',
	'correct_if_answered' => 'be correct, if answered',
	'is_number' => 'be a number',
	'equals'       => 'equal',
	'contains'     => 'contain',
	'contained_in' => 'be found in',
	'greater_than' => 'be greater than',
	'less_than'    => 'be less than',
	'match_regexp' => 'match the extended regexp:'
);


/**
* MOTHER CLASS - Returns a happy true if the prmry q is answered
*
* @package Form
* @see Form::Form(), FormElement::FormElement()
*/
class FormRule extends Object {


	 ###################################
	# Variables
	var $questionid      = 0;
	var $negate          = 0; # Negate the answer? (true <--> false / e.g. answered <--> not answered)
	var $alt_description = '';

	 ##################################
	# Constructor
	function FormRule ($questionid=0,$negate=false,$alt_description='') {
		$this->questionid = $questionid;
		$this->negate = $negate;
		$this->alt_description = $alt_description;
	}


	 ############################################
	# Checks whether the rule has been followed
	function check(&$form) {
		$q = &$form->get_question($this->questionid);
		return ($q->answered() xor $this->negate);
	}


	 ##########################################
	# Returns a description of the rule
	function description(&$form) {
		if($this->alt_description) return $this->alt_description;
		return $this->in_words($form);
	}

	 
	 #############################################################
	# Returns a string describing the rule requirements in words
	function in_words(&$form) {
		global $FORM_RULE_TYPES;
		if($form->show_question_numbers) {
			$number  = 'Q. ';
			list($sid,$qid) = explode('.',$this->questionid);
			list($section_order,$question_order) = $form->get_section_question_order($sid,$qid);
			if ($form->show_section_numbers) {
				$number .= $form->question_index($section_order,$question_order);
			} else {
				$number .= $form->question_index($question_order);
			}
		}
		$q = &$form->get_question($this->questionid);
		$title = $q->title();
		$desc = $FORM_RULE_TYPES[$this->type()];
		$not = (($this->negate)?'not ':'');
		return "$number $title must $not $desc.";
	}



	 ##########################################
	# Changes the type of this object
	function change_type($type='') {
		global $FORM_RULE_TYPES;
		if (!$FORM_RULE_TYPES[$type]) $type = "answered";
		$old_type = $this->type();
		eval("\$this = new FormRule$type(\$this->questionid,\$this->negate,\$this->alt_description);");
		return "Type changed from '".$FORM_RULE_TYPES[$old_type]."' to '".$FORM_RULE_TYPES[$type]."'";
	}

	 ##################################
	# Returns the current element type
	function type() {
		$type = ereg_replace("^formrule","",get_class($this));
		if (!$type) $type = "answered";
		return $type;
	}

	 #################################################
	# Prints the backend for modifying the rule
	function print_backend(&$form,$prefix='') {
		global $FORM_RULE_TYPES;
		$questions = $form->question_select_array();
		?><table cellspacing=0 cellpadding=0 border=0>
			<tr><td align=right class=backend_data>Remove?</td><td><input type=checkbox name="<?=$prefix."delete"?>" value=1></td></tr>
			<tr><td align=right class=backend_data>Question:</td><td><?=combo_box("$prefix"."questionid",$questions,$this->questionid,"class=backend_data",40)?></td></tr>
			<tr>
				<td align=right class=backend_data>Rule:</td>
				<td class =data>
					<?
					echo combo_box($prefix."negate",array("0"=>"must","1"=>"must not"),$this->negate,"class=backend_data");
					echo combo_box($prefix."type",$FORM_RULE_TYPES,$this->type(),"class=backend_data");
					?>
				</td>
			</tr>
			<? $this->print_backend_special($form,$prefix,$questions) ?>
			<tr><td align=right class=backend_data>Default:</td><td class=backend_data>Rule <?=(($this->check($form))?"satisfied":"broken")?>.</td></tr>
			<tr><td align=right class=backend_data>Description:</td><td class=backend_data><?=$this->in_words($form)?></td></tr>
			<tr><td align=right class=backend_data>Alt. Desc.:</td><td class=backend_data><?=text_box($prefix."alt_description",$this->alt_description,40,128)?></td></tr>
		</table><?
	}

	# To be filled in by the kids
	function print_backend_special(&$form,$prefix) {
	}

	 ##############################################
	# Updates according to changes in the backend
	function update(&$form,$prefix='') {
		$questionid = $_REQUEST[$prefix.'questionid'];
		$type		= $_REQUEST[$prefix.'type'];

		if($this->questionid !== $questionid) {
			$this->questionid = $questionid;
			$message .= "Question changed to $questionid. ";
		}
		
		$this->negate = $_REQUEST[$prefix.'negate'];
		$this->alt_description = stripslashes($_REQUEST[$prefix.'alt_description']);

		$message .= $this->update_special($form,$prefix);

		if($type != $this->type()) $message .= $this->change_type($type);

		return $message;
	}

	# For the kids to fill in
	function update_special(&$form,$prefix='') {
	}

}

/**
* Simplest type, is the question answered? - idential to mother class
*
* @package Form
* @see Form::Form(), FormRule::FormRule()
*/
class FormRuleAnswered extends FormRule {}


/**
* For questions which can check if they are correctly answered
*
* @package Form
* @see Form::Form(), FormRule::FormRule()
*/
class FormRuleAnswered_Correctly extends FormRule {
	function check(&$form) {
		$q = &$form->get_question($this->questionid);
		return ($q->answered_correctly() xor $this->negate);
	}
}


/**
* For questions which can check if they are correctly answered
*
* @package Form
* @see Form::Form(), FormRule::FormRule()
*/
class FormRuleCorrect_If_Answered extends FormRule {
	function check(&$form) {
		$q = &$form->get_question($this->questionid);
		if($q->answered()) {
			return ($q->answered_correctly() xor $this->negate);
		}
		return true;
	}
}

/**
* For questions that must contain a number
*
* @package Form
* @see Form::Form(), FormRule::FormRule()
*/
class FormRuleIs_Number extends FormRule {
	function check(&$form) {
		$q = &$form->get_question($this->questionid);
		return ($q->answer_regexp_match("^[\-]?([0-9]+|[0-9]*[\.][0-9]+)$") xor $this->negate);
	}
}


/**
* New superclass of rules that can compare answers to other answers
*
* @package Form
* @see Form::Form(), FormRule::FormRule()
*/
class FormRuleComparison extends FormRule {

	# Var
	var $subject=''; # The value being compared to

	 ########################
	# Constructor
	function FormRuleComparison($questionid=0,$negate=false,$alt_description='',$subject='') {
		$this->FormRule($questionid);
		$this->negate = $negate;
		$this->alt_description = $alt_description;
		$this->set_subject($subject);
	}

	 ######################################
	# Changes the subject
	function set_subject($new_subject='') {
		$this->subject = $new_subject;
	}


	 ##############################
	# Additions textural description
	function in_words(&$form) {
		$text = substr(formrule::in_words($form),0,-1);
		if(ereg("\[Q\:[0-9]+.[0-9]+\]",$this->subject)) {
			$questionid = substr($this->subject,3,-1);
			if($form->question_exists($questionid)) {
				$other_q = &$form->get_question($questionid);
				$new_text = $number.$other_q->title();
			} else {
				$new_text = "[missing question]";
			}
		} else {
			if(is_string($this->subject)) {
				$new_text = $this->subject;
			} else {
				$new_text = implode(", ",$this->subject);
			}
		}
		return "$text $new_text.";
	}


	 ################################################################################################
	# Checks whether the rule has been followed
	# This function needs the sectionid as maybe the comparison question has not been answered yet
	function check(&$form,$sectionid='') {
		$q = &$form->get_question($this->questionid);
		# Are we being asked to compare with another question?
		# Syntax: "[Q:<sectionid>.<questionid>]"
		if(ereg("\[Q\:[0-9]+\.[0-9]+\]",$this->subject)) {
			$questionid = substr($this->subject,3,-1);
			$other_sectionid = strtok($questionid,".");
			if($sectionid && $sectionid < $other_sectionid) {
				return true;
			}
			if($form->question_exists($questionid)) {
				$other_q = &$form->get_question($questionid);
				$subject = $other_q->value();
			}
		} else {
			$subject = &$this->subject;
		}
		return $this->compare($q,$subject);
	}


	 #######################
	# Makes the comparison
	function compare(&$q,&$subject) {
		return ($q->answer_equals($subject) xor $this->negate);
	}

	 ##########################################
	# Changes the type of this object
	function change_type($type='') {
		global $FORM_RULE_TYPES;
		$old_type = $this->type();
		eval("\$this = new FormRule$type(\$this->questionid,\$this->negate,\$this->alt_description,\$this->subject);");
		return "Type changed from '".$FORM_RULE_TYPES[$old_type]."' to '".$FORM_RULE_TYPES[$type]."'";
	}

	 ####################################
	# New fields !
	function print_backend_special(&$form,$prefix='',&$questions) {
		$q = $form->get_question($this->questionid);
		if(ereg("\[Q\:[0-9]+.*[0-9]+\]",$this->subject)) {
			$q->set_value("");
			$compare_qid = substr($this->subject,3,-1);
		} else {
			$q->set_value($this->subject);
		}
		?>
		<tr>
			<td align=right class=backend_data valign=top>Value:</td>
			<td><?=$q->render($prefix."subject")?></td>
		</tr>
		<tr>
			<td align=right class=backend_data>or other Q:</td>
			<td><?=combo_box($prefix."subject_questionid",array(""=>"-- None --")+$questions,$compare_qid,"class=backend_data",40)?></td>
		</tr>
		<?
	}

	 ############################
	# Special new things !
	function update_special(&$form,$prefix='') {
		$subject_questionid = $_REQUEST[$prefix.'subject_questionid'];

		if($subject_questionid) {
			if($this->subject != ($new_subject = "[Q:$subject_questionid]")) {
				$this->subject = $new_subject;
				return "Now compared to question $subject_questionid";
			}
		} else {
			$q = $form->get_question($this->questionid);
			$q->process($prefix.'subject');
			$this->subject = $q->value();
			return '';
		}
	}

}


/**
* Simplest comparison type, is the question equal to this? - idential to mother class
*
* @package Form
* @see Form::Form(), FormRule::FormRule()
*/
class FormRuleEquals extends FormRuleComparison {}


/**
* And here are some more of the simple comparisons
*
* @package Form
* @see Form::Form(), FormRule::FormRule()
*/
class FormRuleContains extends FormRuleComparison {
	function compare(&$q,&$subject) {
		return ($q->answer_contains($subject) xor $this->negate);
	}
}


/**
* And here are some more of the simple comparisons
*
* @package Form
* @see Form::Form(), FormRule::FormRule()
*/
class FormRuleContained_In extends FormRuleComparison {
	function compare(&$q,&$subject) {
		return ($q->answer_contained_in($subject) xor $this->negate);
	}

	 ####################################
	# New fields !
	function print_backend_special(&$form,$prefix='',&$questions) {
		$q = $form->get_question($this->questionid);
		if(ereg("\[Q\:[0-9]+.[0-9]+\]",$this->subject)) {
			$q->set_value("");
			$compare_qid = substr($this->subject,3,-1);
		} else {
			$q->set_value($this->subject);
			$q->multiple = 1;
		}
		?>
		<tr>
			<td align=right class=backend_data valign=top>Value:</td>
			<td><?=$q->render($prefix."subject")?></td>
		</tr>
		<tr>
			<td align=right class=backend_data>or other Q:</td>
			<td><?=combo_box("$prefix"."subject_questionid",array_merge(array(""=>"-- None --"),$questions),$compare_qid,"class=backend_data",40)?></td>
		</tr>
		<?
	}

}


/**
* And here are some more of the simple comparisons
*
* @package Form
* @see Form::Form(), FormRule::FormRule()
*/
class FormRuleGreater_Than extends FormRuleComparison {
	function compare(&$q,&$subject) {
		return ($q->answer_greater_than($subject) xor $this->negate);
	}
}


/**
* And here are some more of the simple comparisons
*
* @package Form
* @see Form::Form(), FormRule::FormRule()
*/
class FormRuleLess_Than extends FormRuleComparison {
	function compare(&$q,&$subject) {
		return ($q->answer_less_than($subject) xor $this->negate);
	}
}


/**
* And here are some more of the simple comparisons
*
* @package Form
* @see Form::Form(), FormRule::FormRule()
*/
class FormRuleMatch_Regexp extends FormRuleComparison {

	 ################################################################################
	# We need to have our own print_backend_special() fn because otherwise the 
	# regular expressions that can be entered are restricted to the max size of the 
	# selected question - assuming that it is a text box, if not then can't do regexp at all
	function print_backend_special(&$form,$prefix='',&$questions) {
		$q = $form->get_question($this->questionid);
		if(ereg("\[Q\:[0-9]+.*[0-9]+\]",$this->subject)) {
			$q->set_value("");
			$compare_qid = substr($this->subject,3,-1);
		} else {
			$q->set_value($this->subject);
		}
		?>
		<tr>
			<td align=right class=backend_data valign=top>Value:</td>
			<td><?=text_box($prefix.'subjectvalue', $this->subject, 30);?></td>
		</tr>
		<tr>
			<td align=right class=backend_data>or other Q:</td>
			<td><?=combo_box($prefix."subject_questionid",array(""=>"-- None --")+$questions,$compare_qid,"class=backend_data",40)?></td>
		</tr>
		<?
	}

	function compare(&$q,&$subject) {
		return ($q->answer_regexp_match($subject) xor $this->negate);
	}
}

?>