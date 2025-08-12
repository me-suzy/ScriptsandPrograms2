<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

// Class for functions
// used in news output

$newsParserVersion = '1.3.4';

// +------------------------------------------------------------------+
// | Start Database Class                                             |
// +------------------------------------------------------------------+

class News {
	// ********************************************************
	// Vars
	// ********************************************************
	var $action = '';
	var $smiliesallowance = '';
	var $htmlallowance = '';
	var $unpallowance = '';
	// ********************************************************
	// Do Smilies
	// ********************************************************
	function unp_doSmilies($newstext)
	{
		/*
		 * unp_doSmilies
		 * Description: If smilies are enabled, convert smilie code into smilies.
		 */
		global $unpurl;
		if ($this->smiliesallowance == '1')
		{
			$newstext = preg_replace('/(&[#a-zA-Z0-9]+;)/i','\\1 ',$newstext); // <--add space after htmlspecialchars
			$newstext = str_replace(':)','<img src="'.$unpurl.'images/smilies/happy.gif" border="0" alt="Happy" />', $newstext);
			$newstext = str_replace(':(','<img src="'.$unpurl.'images/smilies/sad.gif" border="0" alt="Sad" />', $newstext);
			$newstext = str_replace(';)','<img src="'.$unpurl.'images/smilies/wink.gif" border="0" alt="Wink" />', $newstext);
			$newstext = str_replace(':D','<img src="'.$unpurl.'images/smilies/biggrin.gif" border="0" alt="Big Grin" />', $newstext);
			$newstext = str_replace('^_^','<img src="'.$unpurl.'images/smilies/keke.gif" border="0" alt="Keke" />', $newstext);
			$newstext = str_replace(array(':P',':p'),'<img src="'.$unpurl.'images/smilies/tongue.gif" border="0" alt="Tongue" />', $newstext);
			$newstext = preg_replace('/:angry:/i','<img src="'.$unpurl.'images/smilies/angry.gif" border="0" alt="Angry" />', $newstext);
			$newstext = preg_replace('/:confused:/i','<img src="'.$unpurl.'images/smilies/confused.gif" border="0" alt="Confused" />', $newstext);
			$newstext = preg_replace('/:rolleyes:/i','<img src="'.$unpurl.'images/smilies/rolleyes.gif" border="0" alt="Roll Eyes" />', $newstext);
			$newstext = preg_replace('/(&[#a-zA-Z0-9]+;)( )/i','\\1',$newstext); // <--strip space after htmlspecialchars
			return $newstext;
		}
		else
		{
			return $newstext;
		}
	}
	// ********************************************************
	// Start UNP Code
	// ********************************************************
	function unp_doCode($newstext)
	{
		/*
		 * unp_doCode
		 * Description: If UNP codes are enabled, converts code into HTML equivalents.
		 */
		if ($this->unpallowance == '1')
		{
			global $linkcolor; // need the link color - grab it
			$findarray = array();
			$replacearray = array();
			// #################### UNP REPLACEMENTS ####################
			// [B]
			$findarray['bold'] = '/\[b\](.+?)\[\/b\]/is';
			$replacearray['bold'] = '<strong>\\1</strong>';
			// [I]
			$findarray['italics'] = '/\[i\](.+?)\[\/i\]/is';
			$replacearray['italics'] = '<em>\\1</em>';
			// [U]
			$findarray['underline'] = '/\[u\](.+?)\[\/u\]/is';
			$replacearray['underline'] = '<span style="text-decoration: underline">\\1</span>';
			// [HR]
			$findarray['ruler'] = '/\[hr\]/i';
			$replacearray['ruler'] = '<hr />';
			// [COLOR=XXX]
			$findarray['color'] = '/\[color=([\#a-zA-Z0-9]+)\](.+?)\[\/color\]/is';
			$replacearray['color'] = '<font color="\\1">\\2</font>';
			// [SIZE=XXX]
			$findarray['size'] = '/\[size=([\d])\](.+?)\[\/size\]/is';
			$replacearray['size'] = '<font size="\\1">\\2</font>';
			// [FONT=XXX]
			$findarray['font'] = '/\[font=(.+?)\](.+?)\[\/font\]/is';
			$replacearray['font'] = '<font face="\\1">\\2</font>';
			// [BLOCKQUOTE]
			$findarray['blockquote'] = '/\[blockquote\](.+?)\[\/blockquote\]/is';
			$replacearray['blockquote'] = '<blockquote>\\1</bockquote>';
			// [EMAIL]
			$findarray['email'] = '/\[email\]([\w\-\.]+@[\w\-]+\.[\w\-\.]+)\[\/email\]/is';
			$replacearray['email'] = '<a href="mailto:\\1"><font face="verdana,arial,helvetica" size="2" color="'.$linkcolor.'">\\1</font></a>';
			// [EMAIL=XXX]
			$findarray['email2'] = '/\[email=([\w\-\.]+@[\w\-]+\.[\w\-\.]+)\](.+?)\[\/email\]/is';
			$replacearray['email2'] = '<a href="mailto:\\1"><font face="verdana,arial,helvetica" size="2" color="'.$linkcolor.'">\\2</font></a>';
			// [IMG]
			$findarray['image'] = '/\[img\]([-_.\/a-zA-Z0-9!&%\#?+,\'=:~]+)\[\/img\]/i';
			$replacearray['image'] = '<img src="\\1" border="0" alt="Image" />';
			if (ISPRINTABLEPAGE != true)
			{
				// [URL]
				$findarray['url1'] = '/\[url\]((http|https|news|ftp):\/\/\w+[^\s\[\]]+)\[\/url\]/i';
				$replacearray['url1'] = '<a href="\\1" target="_blank"><font face="verdana,arial,helvetica" size="2" color="'.$linkcolor.'">\\1</font></a>';
				// [URL] - no protocol (assumes http://)
				$findarray['url1-noprefix'] = '/\[url\](\w+[^\s\[\]]+)\[\/url\]/i';
				$replacearray['url1-noprefix'] = '<a href="http://\\1" target="_blank"><font face="verdana,arial,helvetica" size="2" color="'.$linkcolor.'">\\1</font></a>';
				// [URL=XXX]
				$findarray['url2'] = '/\[url=((http|https|news|ftp|aim):\/\/\w+[^\s\[\]]+)\](.+?)\[\/url\]/is';
				$replacearray['url2'] = '<a href="\\1" target="_blank" title="\\1"><font face="verdana,arial,helvetica" size="2" color="'.$linkcolor.'">\\3</font></a>';
				// [URL=XXX] - no protocol (assumes http://)
				$findarray['url2-noprefix'] = '/\[url=(\w+[^\s\[\]]+)\](.+?)\[\/url\]/is';
				$replacearray['url2-noprefix'] = '<a href="http://\\1" target="_blank" title="\\1"><font face="verdana,arial,helvetica" size="2" color="'.$linkcolor.'">\\2</font></a>';
			}
			else
			{
				// PRINTABLE PAGES DO NOT HAVE LINK COLOR PREDEFINED - DEFINED IN CSS FOR PRINTABLE PAGE
				// [URL]
				$findarray['url1'] = '/\[url\]((http|https|news|ftp):\/\/\w+[^\s\[\]]+)\[\/url\]/i';
				$replacearray['url1'] = '<a href="\\1" target="_blank">\\1</a>';
				// [URL] - no protocol (assumes http://)
				$findarray['url1-noprefix'] = '/\[url\](\w+[^\s\[\]]+)\[\/url\]/i';
				$replacearray['url1-noprefix'] = '<a href="http://\\1" target="_blank">\\1</a>';
				// [URL=XXX]
				$findarray['url2'] = '/\[url=((http|https|news|ftp|aim):\/\/\w+[^\s\[\]]+)\](.+?)\[\/url\]/is';
				$replacearray['url2'] = '<a href="\\1" target="_blank" title="\\1">\\3</a>';
				// [URL=XXX] - no protocol (assumes http://)
				$findarray['url2-noprefix'] = '/\[url=(\w+[^\s\[\]]+)\](.+?)\[\/url\]/is';
				$replacearray['url2-noprefix'] = '<a href="http://\\1" target="_blank" title="\\1">\\2</a>';
			}
			// #################### UNP REPLACEMENTS ####################
			$newstext = preg_replace($findarray, $replacearray, $newstext);

			// (c) || (tm) || (r)
			$newstext = preg_replace('/\(c\)/i', '&copy;', $newstext);
			$newstext = preg_replace('/\(tm\)/i', '&#153;', $newstext);
			$newstext = preg_replace('/\(r\)/i', '&reg;', $newstext);

			// strip out the potential baddies
			$newstext = preg_replace('/javascript:/i', 'java script:', $newstext); // strip out javascript
			$newstext = preg_replace('/about:/i', 'about :', $newstext); // strip out about
			$newstext = preg_replace('/vbscript:/i', 'vbscript :', $newstext); // strip out vbscript
			return $newstext;
		}
		else
		{
			return $newstext;
		}
	}
	// ********************************************************
	// Start News Formatting
	// ********************************************************
	function unp_doNewsFormat($text)
	{
		/*
		 * unp_doNewsFormat
		 * Description: Uses previously defined formatting functions
		 * on news.
		 */
		if ($this->htmlallowance == '0')
		{
			$text = htmlspecialchars($text);
		}
		$text = $this->unp_doSmilies($text);
		$text = $this->unp_doCode($text);
		$text = stripslashes($text);
		$text = nl2br($text);
		return $text;
	}
	// ********************************************************
	// Start Subject Formatting
	// ********************************************************
	function unp_doSubjectFormat($subject)
	{
		/*
		 * unp_doSubjectFormat
		 * Description: Strips slashes from news and removes any rogue
		 * HTML code.
		 */
		$subject = stripslashes($subject);
		$subject = htmlspecialchars($subject);
		return $subject;
	}
	// ********************************************************
	// Start Get Style
	// ********************************************************
	function unp_getStyle()
	{
		/*
		 * unp_getStyle
		 * Description: Gets news style from the database and creates
		 * appropriate variables.
		 */
		global $DB;
		$getstyle = $DB->query("SELECT * FROM `unp_style`");
		while ($style = $DB->fetch_array($getstyle))
		{
		 	global ${$style['varname']};
		 	${$style['varname']} = $style['value'];
		}
	}
}
?>