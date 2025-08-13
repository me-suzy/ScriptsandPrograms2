<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: compose.spell.php,v $ - $Revision: 1.8 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
define('SKIP_POP', true);
if ($_REQUEST['cmd'] == 'procframeset') {
	define('LEAVE_SESSION_OPEN', true);
}
if ($_REQUEST['cmd'] == 'procframeset' or $_REQUEST['cmd'] == 'process') {
	define('SKIP_SKIN', true);
} else {
	define('LOAD_MINI_TEMPLATES', true);
}
$templatesused = 'compose_spell_loading,compose_spell_noerrors,compose_spell_suggestions';
require_once('./global.php');

// ############################################################################
// Show processing frameset
if ($cmd == 'procframeset') {
	if (!empty($_REQUEST['data']['message'])) {
		$_SESSION['spell_wordbox'] = $_REQUEST['data']['message'];
		$spell_html = '0';
	} else {
		$_SESSION['spell_wordbox'] = $_REQUEST['message'];
		$spell_html = '1';
	}
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "XHTML1-f.dtd">
	<html>
	<head>
	<title><?php echo getop('appname'); ?>: Spell Checker</title>
	<frameset rows="0,*" framespacing="0" frameborder="no" border="1">
	<frame name="process" scrolling="no" _noresize="noresize" frameborder="no" border="0" src="compose.spell.php?cmd=process&spell_html=<?php echo $spell_html; ?>&sendafter=<?php echo $sendafter; ?>" />
	<frame name="content" scrolling="no" _noresize="noresize" frameborder="no" border="0" marginwidth="0" marginheight="0" src="compose.spell.php?cmd=loading" />
	</frameset>
	</head>
	</html>
	<?php
}

// ############################################################################
// Show "Loading..." screen
if ($cmd == 'loading') {
	eval(makeeval('echo', 'compose_spell_loading'));
}

// ############################################################################
// Process text and come up with misspelled words and suggestions
if ($cmd == 'process') {
	require_once('./includes/data_spell.php');
	require_once('./includes/functions_spell.php');
	$jsOutput = spell_process($_SESSION['spell_wordbox'], $spell_html, $sendafter);
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<title><?php echo getop('appname'); ?> Spell Checker</title>
	<script>
	<!--

	<?php echo $jsOutput; ?>

	// Return context of misspelling
	function getContext(intMissNum) {
		return msMissWordAr[intMissNum][2];
	}

	// Return the original index of the misspelled word from the main word array
	function getOi(intMissNum) {
		return msMissWordAr[intMissNum][1];
	}

	// Return misspelled word
	function getWord(intMissNum) {
		return msMissWordAr[intMissNum][0];
	}

	// Return number of suggestions for word
	function getSuggNum(intMissNum) {
		return msMissWordAr[intMissNum][3].length();
	}

	// Return array of word suggestions
	function getSuggList(intMissNum) {
		return msMissWordAr[intMissNum][3];
	}

	// Return true if we are at the end of the misspelled word array
	function isEnd(intMissNum) {
		return (intMissNum >= (msMissWordAr.length - 1));
	}

	// Return total misspelled
	function getMisspelledCount() {
		return msWc;
	}

	// Sets the corrected word in the original array
	function setNewWord(intMissNum, strNewWord) {
		var oi = getOi(intMissNum);
		var strOWord = getWord(intMissNum);
		msOrigWordAr[oi] = msOrigWordAr[oi].replace(strOWord, strNewWord);
	}

	// Sets the corrected word in the original array
	function setNewWord(intMissNum, strNewWord) {
		var oi = getOi(intMissNum);
		lastOrigWord = getWord(intMissNum);
		msOrigWordAr[oi] = msOrigWordAr[oi].replace(lastOrigWord, strNewWord);
	}

	// Sets the original word in the original array
	function setOrigWord(intMissNum, lastOrigWord) {
		var oi = getOi(intMissNum);
		var strNewWord = msOrigWordAr[oi];
		msOrigWordAr[oi] = msOrigWordAr[oi].replace(strNewWord, lastOrigWord);
	}

	// Returns the whole orginal word array (with assumed spell check corrections in place) for reassembly
	function getOrigAr() {
		return msOrigWordAr;
	}

	// Returns the last original word that was replaced
	function getLastOrigWord() {
		return lastOrigWord;
	}

	// -->
	</script>
	</head>
	<body>
	</body>
	</html>
	<?php
}

// ############################################################################
// Yay! No mistakes found!
if ($cmd == 'noerrors') {
	if ($sendafter) {
		?><script language="JavaScript" type="text/javascript">
		<!--
		window.top.opener.sendMail(window.top.opener.document.composeform, true);
		window.top.window.close();
		//-->
		</script><?php
		exit;
	} else {
		eval(makeeval('echo', 'compose_spell_noerrors'));
	}
}

// ############################################################################
// Show suggestions
if ($cmd == 'suggestions') {
	eval(makeeval('echo', 'compose_spell_suggestions'));
}

// ############################################################################
// Update user's dictionary and common mistakes
if ($_POST['cmd'] == 'updatedata') {
	// Add new words into dictionary
	$newWords = array_unique(explode("\n", $newWords));
	$newValues = array();
	foreach ($newWords as $word) {
		$word = trim($word);
		if (empty($word)) {
			continue;
		}
		$newValues[] = "($hiveuser[userid], '".addslashes($word)."', '".addslashes(metaphone($word))."')";
	}
	if (!empty($newValues)) {
		$DB_site->query('
			REPLACE INTO hive_word
			(userid, word, metaphone)
			VALUES '.implode(', ', $newValues).'
		');
	}

	// Close window
	?><script language="JavaScript" type="text/javascript">
	<!--
	window.top.window.close();
	//-->
	</script><?php
	exit;
}

?>