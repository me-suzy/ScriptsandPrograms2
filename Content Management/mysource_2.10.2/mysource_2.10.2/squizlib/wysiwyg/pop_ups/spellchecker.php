<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- SPELL CHECKER ------ PHP4 --------------##
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
## $Source: /home/cvsroot/squizlib/wysiwyg/pop_ups/spellchecker.php,v $
## $Revision: 1.6 $
## $Author: csmith $
## $Date: 2004/01/14 02:58:28 $
#######################################################################
include_once("../../../web/init.php");
#######################################################################
?>
<HTML>
<HEAD>
<style>
body, p, td {
	font-family:tahoma;
	font-size:9pt;
}

table {
	border-top: 1px solid #000000;
	border-right: 1px none #000000;
	border-bottom: 1px none #000000;
	border-left: 1px solid #000000;
}

td {
	border-top: 1px none #000000;
	border-right: 1px solid #000000;
	border-bottom: 1px solid #000000;
	border-left: 1px none #000000;
}

.error {
	background:yellow;
}

.sp_heading {
	font-size:16pt;
	font-weight:bolder;
}

</style>

</HEAD>
<BODY>
<DIV align="center">


<?
# we need to get the html from the dialogArguments into php
# so we need to put the value into a hidden field and then
# resubmit the form to get the variable into php (is there a better way?)

# we also get the WEB_PATH passed from the wysisyg editor
# so that we can include init to exploit the session 
# (CURRENTLY NOT WORKING just hard coded it in for now)


if(!isset($_POST['wysiwyg_body'])){
?>
	<FORM name=getBody action=<?= $_SERVER['PHP_SELF'] ?> method=POST>
	<input type=hidden value="" name="wysiwyg_body">
	<input type=hidden value="" name="stylesheet">
	</FORM>
	<script language="javascript" type="text/javascript">
	getBody.wysiwyg_body.value = window.dialogArguments["html"];
	getBody.stylesheet.value   = window.dialogArguments["stylesheet"];
	getBody.submit();
	</script>
<?
}# end if isset


###--MAIN--########################################################

# PSPELL_FAST creates a sufficient number of suggestions 
# and is a hell of a lot faster than the other options

$GLOBALS['pspell_link'] = pspell_new_personal ("$CONF_PATH/mysource.pws", "en", "", "", "", (PSPELL_FAST|PSPELL_RUN_TOGETHER));

$GLOBALS['pspell_options'] = array (
	"caps"			=> "Ignore UPPERCASE",
	"numberwords"	=> "Ignore Words With Numbers",
);

#make call to check_words()
check_words();

#########################################################

   ################################################
  # Checks the words and creates                 #
 # a list of  words and a status for each word  #
################################################

function check_words() {
	$pspell_link = get_pspell_handle();
	
	# some has just tried to close the window
	# so we better do that, and save their options too
	# so they don't have a hissy fit
	
	if(isset($_POST['exit'])){
		save_pspell_options();
		if($_POST['exit'] == 1){
			?>
			<script language="javascript" type="text/javascript">
				window.close();
			</script>
			<?
		}
	}

	$text = urldecode($_POST['wysiwyg_body']);
	$_POST['wysiwyg_body'] = urldecode($_POST['wysiwyg_body']);
	
	if($text != "") {

		# check to see if they have submitted the form
		# if so we want to make any changes, correct them in the wysiwyg_body
		# and return that back to the wysiwyg
			if($_POST['other']){
				foreach($_POST['other'] as $old => $new){
					list($old, $num) = split("_", $old);
					$old = urldecode($old);
					# they just want to add it, but not change it in the text (so continue)
					
					if($_POST['action'][$old . "_" . $num] == 'add'){
						add_to_personal_dictionary($old);
						continue;
					
					# they want to replace it in the text
					# and add it to the personal dictionary
					
					} else {
						if($_POST['action'][$old . "_" . $num] == 'replaceadd'){
							add_to_personal_dictionary($old);
						}
					}
					
					# make sure that they don't want to ignore the word
					if(check_option($old) && $_POST['action'][$old . "_" . $num] != 'ignore'){
						make_change($old, $new);
					}
				}
				$returnValue = str_replace(array("\r", "\n"), array("\\r", "\\n"), addslashes($_POST['wysiwyg_body']));
				
				//$returnValue = urlencode($_POST['wysiwyg_body']);
				# before we exit, save the options and ignores back to the session

				if(!get_new_option('numberwords') && !get_new_option('caps')){
					save_pspell_options();
					echo "<script language=\"Javascript\">\n";
					echo "var retVal = new Object();\n";
					echo "retVal['html'] = \"" .$returnValue . "\";\n";
					echo "window.returnValue = retVal['html']\n";
					echo "window.close();\n";
					echo "</script>\n";
				}
				save_pspell_options();
			}# end if
		
	
		# we need to strip any tags from the text, along with any quotations
		# that are not in the right place
		# before we do that, we should replace any <br>, </p> with spaces
		# so there is a word boundary
		# we also need to decode the text from html special chars eg. from &gt to >
		
		# only use this if you are certain that your
		# php ver is >= 4.3.0 
		# $text		= html_entity_decode($text);
		
		$trans_tbl	= get_html_translation_table (HTML_ENTITIES);
		$trans_tbl	= array_flip ($trans_tbl);
		$text		= strtr($text, $trans_tbl);

		# we want a list of original words that we can print with bold
		# representing the spelling mistakes (with all the quotation stuff 
		# that they may have added)
		
		$original_words  = $text;
	
		# strip out image tags
		$trash = array (
			"'<img[^>]*?.*?>'si",
		);
	
		$original_words = preg_replace($trash, "", $original_words);

		# because string tags replaces the following with a non-breaking space
		# we want to convert these to a plain old space before calling strip_tags()

		$text  = str_replace(array("<br>", "</p>", "<BR>", "</P>", "&nbsp;"), " ", $text);
		$text  = strip_tags($text);

		$text  = preg_replace(array("/\W+/", "/\%/"), " ", $text);
		$text  = preg_replace("/\b\d+\b/", " " , $text);
		$text  = preg_replace("/\&....\;/", " " , $text);

		#split words at all non alphanumric characters as pspell only handles words that consist of totally alphanumeric characters
		$words = preg_split("/([^A-Za-z]+)|\s+/", $text, -1, PREG_SPLIT_NO_EMPTY);

		$i				 = 0;
		$mistake_count	 = 0;
		$mistakes		 = array();
		$formatted_words = array();

		###############################
		# start checking for mistakes

		foreach($words as $word){
			$check_spelling = true;
			# check if UPPERCASE words should be ignored
			if($check_spelling && get_option('caps') && (strcmp(strtoupper($word), $word) == 0)){
					$check_spelling = false;
			}
			# check if words with numbers should be ignored
			if($check_spelling && get_option('numberwords')){
				if(has_numbers($word)){
					$check_spelling = false;
				}
			}
			$formatted_words[$i]['word'] = $words[$i];
			if($check_spelling && !pspell_check($pspell_link, $word)){
				$mistakes[$i]['name'] = $word;
				$mistakes[$i]['suggestions'] = get_suggestions($word);
				
				$formatted_words[$i]['status'] = 1;
				
				$mistake_count++;
			}
			else {
				$formatted_words[$i]['status'] = 0;
			}
			$i++;
		}
		# there are no mistkes
		if($mistake_count == 0){
			echo "<BR><BR><B>There are no errors</B><br><BR>";
			
			# we still want to give them a chance to change the options
			# so we are going to need a form for them to submit them
			
			echo "<form name=\"spellchecker\" action=\"" . $_SERVER['PHP_SELF'] .  "\" method=\"POST\">";
			
			print_pspell_options();
			
			# hidden value so we no just to close the window when we have the changes
			
			echo "<input type=\"hidden\" name=\"exit\" value=\"0\">\n";
			echo "<BR><br><a href=\"Javascript: this.spellchecker.exit.value='1'; spellchecker.submit(); window.close()\">Close Window</a>\n";
			echo "&nbsp;&nbsp;&nbsp;<a href=\"Javascript: spellchecker.submit();\">Refresh Options</a>\n";
			echo "</form>";
			exit;
		}

		# find the spelling errors and print them out
		
		echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" bgcolor=\"#C6C3C6\" width=\"100%\">";
		echo "<tr><td colspan=\"4\" class='sp_heading'>Spellchecker Results</td>\n";
		echo "</tr><tr>\n<td><b>Original Word</b></td>\n<td><b>Suggestion</b></td>\n<td><b>Replacement</b>";
		echo "</td>\n<td><b>Action</b></td></tr>\n";

		?>
		<form method="POST" name="spellcheck" action="<?= $_SERVER['PHP_SELF'] ?>">
		<?
		# print out a form where the can rectify the spelling mistakes
		$i = 0;

		# here is where it all hits the fan
	
		 ##########################################
		# create some sort of table
		# for making changes
		
		foreach($mistakes as $mistake){
			echo "<tr><td>" . $mistake['name'] . "</td>";
			if(!count($mistake['suggestions'])){
				echo "<td>No Suggestions</td>";
			} else {
				# we need to add a number after the mistake name because
				# if there are two mistakes that have the same name, 
				# they will override eachother when they are sent to the POST variable
				echo "<td><select name=\"corrections[" . $mistake['name'] . "_" .  $i .  "]\" onChange=\"Javascript: document.getElementById('" . $i . "').value = this.options[this.selectedIndex].text\">\n";

				foreach($mistake['suggestions'] as $suggestion){
					echo "<option value=\"" . $suggestion . "\">" . $suggestion . "</option>\n";
				}
				echo "</select></td>\n";
			}
			$value = (reset($mistake['suggestions'])) ? reset($mistake['suggestions']) : $mistake['name'];

			# add a button so they can add the word to the dictionary
			echo "<td><input type=\"text\" value=\"" . $value . "\" ID=\"" . $i . "\" name=\"other[" . urlencode($mistake['name']) . "_" . $i . "]\" size=\"10\"></td>\n";
			
			#add a checkbox so they can add it to the dictionary
			
			echo "<td><select name=\"action[" . $mistake['name'] . "_" .  $i . "]\">";
			echo "<option value=\"replace\">Replace in text</option>";
			echo "<option value=\"ignore\" selected>Ignore word and do not replace</option>";
			echo "<option value=\"replaceadd\">Replace text and add to dictionary</option>";
			echo "<option value=\"add\">Don't replace and add to dictionary</option>";
			echo "</select></td>\n";
		
			$i++;
		}
		?> 
		<input type="hidden" name="wysiwyg_body" value="<?= urlencode($_POST['wysiwyg_body']) ?>">
		<?
			# we want to print out the options here
			print_pspell_options();
		?>
		<td align="right"><input type="submit" name="commit" value="Apply Spelling Changes"></td></tr>
		</form></table>
		<BR><BR>
		<? if(isset($_POST['stylesheet'])){ ?>
		<style type="text/css">
		<?
		$fp = fopen($_POST['stylesheet'], "r");
		$buff = fread($fp, 10000);
		echo $buff;
		fclose($fp);
		?>
		</style>
		<?
		}
		echo "<div align=\"left\" style=\"padding-left: 3em;padding-right : 3em\">";
		
		# now, we want to print out the formatted word with the spelling mistakes marked 
		# as yellow
		foreach($formatted_words as $word){
			if($word['status'] == 1){
				$new_word = "<span class=\"error\">" . $word['word'] . "</span>";
				$original_words = str_replace($word['word'], $new_word, $original_words);
			}
		}
		echo $original_words;
		echo "</div>";
	} else {
		# there was nothing in the wysiwyg body

		echo "<BR><BR><div align=\"center\">There is no text to spell check</div>\n";
	}
}
   ##################################
  # function to make a correction  #
 # to a word                      #
##################################

function make_change($old_word='', $new_word='') {
	if (!$old_word || !$new_word) return;
	
	# it is possible to have a miss-spelt word in two or more places
	# where the incorrect word is the same in those places, 
	# but the intended words are different
	# because of this we need to check where the miss-spelt word
	# existed, and change them individually
	
	$old_word_len	= strlen($old_word);
	$body_len		= strlen($_POST['wysiwyg_body']);
	$pos			= strpos($_POST['wysiwyg_body'], $old_word);
	
	$str  = substr($_POST['wysiwyg_body'], 0, $pos + $old_word_len);
	$str2 = substr($_POST['wysiwyg_body'], $pos + $old_word_len, $body_len + 1);

	$_POST['wysiwyg_body']  = str_replace($old_word, $new_word, $str);
	$_POST['wysiwyg_body'] .= $str2; 

}

  #######################################
 # adds a new word to the dictionary   #
#######################################

function add_to_personal_dictionary($word='') {
	if (!$word) return;
	$pspell_link = get_pspell_handle();
	pspell_add_to_personal ($pspell_link, $word);
	pspell_save_wordlist ($pspell_link);
}

  ################################
 #  returns the pspell handle   #
################################

function get_pspell_handle(){
	return $GLOBALS['pspell_link'];
}

  ###################################
 # Returns a list of Suggestions   #
###################################

function get_suggestions($word='') {
	if (!$word) return;
	$pspell_link = get_pspell_handle();
	return pspell_suggest($pspell_link, $word);
}

  #################################################
 # function to check if an option is set or not  #
#################################################

function get_option($option=''){
	$SESSION = &get_mysource_session();
	$options = array();
	$options = $SESSION->get_var('pspell_options');

	if($options[$option]){
		return $options[$option];
	}
	# we need to check the $_POST var for options as well
	# because they might have just made a change to an
	# option

	if($_POST['pspell_options'][$option]) {
		return "new";
	}
	return 0;
}
   #######################################
  #  function to return if an           #
 # option has just been turned on/off  #
#######################################

function get_new_option($option=''){
	$SESSION = &get_mysource_session();
	$options = array();
	$options = $SESSION->get_var('pspell_options');

	if($_POST['pspell_options'][$option] && !$options[$option]) {
		return true;
	}
	elseif(!$_POST['pspell_options'][$option] && $options[$option]) {
		return true;
	}
	return false;
}
   
   #######################################
  # function to print the options for   #
 # the user                            #
#######################################

function print_pspell_options(){
	$SESSION = &get_mysource_session();
	$options = array();
	$options = $SESSION->get_var('pspell_options');		
	
	echo "<tr>\n";
	echo "<td colspan=\"3\">\n";
	if (!empty($GLOBALS['pspell_options'])) {
		foreach($GLOBALS['pspell_options'] as $option => $name) {
			$checked = "";
			if($options[$option] == "1"){
				$checked = " checked";
			}
			echo "(" . $name . "\n";
			echo "<input type=\"checkbox\" value=\"1\" name=\"pspell_options[" . $option . "]\"" . $checked . ">)\n";
			echo " ";
		}
	} else {
		echo '&nbsp;';
	}
	echo "</td>";
}
   #######################################
  # function to save the options        #
 # back to the session                 #
#######################################

function save_pspell_options(){
	$SESSION = &get_mysource_session();
	$SESSION->set_var('pspell_options', $_POST['pspell_options']);
}

    #############################################
   # function to check if an option was        #
  # initialized, and if it had any change     #
 # to the overall spelling                   #
#############################################

function check_option($word='') {
	if(get_option('numberwords')){
		if(has_numbers($word)){
			return false;
		}
	}
	if(get_option('caps')){
		if((strcmp(strtoupper($word), $word) == 0)){
			return false;
		}
	}
	return true;
}
   #######################################
  # function to check if a word has     #
 # numbers in it                       #
#######################################

function has_numbers($word='') {
	$regs = array(
		"/\d+[a-zA-Z]+/", 
		"/[a-zA-Z]+\d+/", 
		"/[a-zA-Z]+\d+[a-zA-Z]+/",
	);
	foreach($regs as $reg){
		if(preg_match($reg, $word)){
			return true;
		}
	}
	return false;
}

#################################################
?>
</div>
</BODY>
<HTML>
