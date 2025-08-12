<?

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

require_once("ScriptFunctions.php");

// ==============================================================================================================================

// Function: PreviewArticleShort -- Preview the SHORT article
function PreviewArticleShort($ArticleID, $Sticky, $Headline, $PostDateTime, $PostAuthor, $ShortPost, $LongPost, $ImageID, $TemplateID, $TimesRead, $SpellCheck, $AllowComments, $Categories)
{
	global $FullNewsDisplayMode, $SpellCheckLanguage, $SpellCheckMinWordLength, $NewsDir;

	// Sanitise the input
    $Headline = stripslashes($Headline);
    $ShortPost = stripslashes($ShortPost);
    $LongPost = stripslashes($LongPost);

	// Load the user-defined codes (only do once)
	$UserCodes = BuildUserDefinedCodesList();

	// Set constants
	$LongPostExists = false; // Always false, as we never want to show a Read More hyperlink

	// Are we to spell-check?
	if ($SpellCheck)
	{
		require "SpellChecker.php";
		$SpellChecker = new spell_checker ($SpellCheckLanguage, $SpellCheckMinWordLength);

		$Headline = AnnotateSpellingErrors ($SpellChecker, $Headline);
		$ShortPost = AnnotateSpellingErrors ($SpellChecker, $ShortPost);
		$LongPost = AnnotateSpellingErrors ($SpellChecker, $LongPost);
	}

	// Load the template details
	$TemplateHeadlineContents = ReadTemplate($TemplateID, "H");
	$TemplateBodyContents = ReadTemplate($TemplateID, 'S');

	// Output the header
	$Contents = ParseTemplateCodes(-1, $TemplateHeadlineContents, $PostDateTime, $PostAuthor, $Headline, $TimesRead, $AllowComments, true, $LongPostExists, $Categories);
	echo $Contents;

	// Begin to build the short-post (the main body)
	$Contents = ParseTemplateCodes(-1, $TemplateBodyContents, $PostDateTime, $PostAuthor, $Headline, $TimesRead, $AllowComments, false, $LongPostExists, $Categories);
	$Contents = BuildImage($Contents, $ImageID);

	// Parse the user-defined codes for the short-post, and output the article
	$ParsedBody = ParseUserDefinedCodes($UserCodes, $ShortPost);
	$ParsedBody = ParseBBCodes($ParsedBody);
	$Contents = str_replace("{news}", $ParsedBody, $Contents);
	echo $Contents;

	// Are we to show the long post?
	if ( ($LongPostExists) && ($FullNewsDisplayMode == 1) )
	{
		$ParsedBody = ParseUserDefinedCodes($UserCodes, $LongPost);
		$ParsedBody = ParseBBCodes($ParsedBody);
		echo $ParsedBody;
	}
}

// ==============================================================================================================================

// Function: PreviewArticleLong -- Preview the LONG article
function PreviewArticleLong($ArticleID, $Sticky, $Headline, $PostDateTime, $PostAuthor, $ShortPost, $LongPost, $ImageID, $TemplateID, $TimesRead, $SpellCheck, $AllowComments, $Categories)
{
	global $FullNewsDisplayMode, $SpellCheckLanguage, $SpellCheckMinWordLength;

	// Sanitise the input
    $Headline = stripslashes($Headline);
    $ShortPost = stripslashes($ShortPost);
    $LongPost = stripslashes($LongPost);

	// Load the user-defined codes (only do once)
	$UserCodes = BuildUserDefinedCodesList();

	// Set constants
	$LongPostExists = ($LongPost != '');

	// Are we to spell-check?
	if ($SpellCheck)
	{
		require "SpellChecker.php";
		$SpellChecker = new spell_checker ($SpellCheckLanguage, $SpellCheckMinWordLength);

		$Headline = AnnotateSpellingErrors ($SpellChecker, $Headline);
		$ShortPost = AnnotateSpellingErrors ($SpellChecker, $ShortPost);
		$LongPost = AnnotateSpellingErrors ($SpellChecker, $LongPost);
	}

	// Load the template details
	$TemplateHeadlineContents = ReadTemplate($TemplateID, "H");
	$TemplateBodyContents = ReadTemplate($TemplateID, 'L');

	// Output the header
	$Contents = ParseTemplateCodes(-1, $TemplateHeadlineContents, $PostDateTime, $PostAuthor, $Headline, $TimesRead, $AllowComments, true, $LongPostExists, $Categories);
	echo $Contents;

	// Begin to build the short-post (the main body)
	$Contents = ParseTemplateCodes(-1, $TemplateBodyContents, $PostDateTime, $PostAuthor, $Headline, $TimesRead, $AllowComments, false, $LongPostExists, $Categories);
	$Contents = BuildImage($Contents, $ImageID);

	// Parse the user-defined codes for the short-post, and output the article
	$ParsedBody = ParseUserDefinedCodes($UserCodes, $LongPost);
	$ParsedBody = ParseBBCodes($ParsedBody);
	$Contents = str_replace("{news}", $ParsedBody, $Contents);
	echo $Contents;
}

// ==============================================================================================================================

// Function AnnotateSpellingErrors: Check a text string for spelling errors
function AnnotateSpellingErrors ($SpellChecker, $Contents) 
{
	$Words = split("[^[:alpha:]']+", $Contents); 
//	$Words = split(" ", $Contents);
	foreach ($Words as $Word)
	{
		if (! $SpellChecker->check($Word))
			$Contents = str_replace($Word, '<B><FONT color="red">' . $Word . "</FONT></B>", $Contents);
	}

	return $Contents;
}
?>