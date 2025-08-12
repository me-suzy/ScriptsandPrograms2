<?php
/***************************************************************************
*	Vanilla Guestbook
*	Version: 0.1
*	Filename: emoticon_functions.php
*	Description: Functions relating smilies
****************************************************************************
*	Build Date: October 11, 2005
*	Author: Tachyon
*	Website: http://tachyondecay.net/
****************************************************************************
*	Copyright © 2005 by Tachyon
*
*	This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.  A copy of the GPL version 2 is
*	included with this package in the file "COPYING.TXT"
*
*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this program; if not, write to the Free Software
*   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
****************************************************************************/

//The directory in which smilies can be found, relatively to the path of course:
$emotedirectory = $path."images/smilies/";

//A multidimensional array of all smilies
//Format:
//First element is the text to be replaced
//Second element is the filename of the image
//
$emotes[] = array("-->", "icon_arrow.gif");
$emotes[] = array(":D", "icon_biggrin.gif");
$emotes[] = array(":s", "icon_confused.gif");
$emotes[] = array(":S", "icon_confused.gif");
$emotes[] = array("B)", "icon_cool.gif");
$emotes[] = array(":'(", "icon_cry.gif");
$emotes[] = array(":eek:", "icon_eek.gif");
$emotes[] = array(":evil:", "icon_evil.gif");
$emotes[] = array(":!:", "icon_exclaim.gif");
$emotes[] = array(":(", "icon_frown.gif");
$emotes[] = array(":idea:", "icon_idea.gif");
$emotes[] = array(":lol:", "icon_lol.gif");
$emotes[] = array(">_<", "icon_mad.gif");
$emotes[] = array(":mrgreen:", "icon_mrgreen.gif");
$emotes[] = array(":|", "icon_neutral.gif");
$emotes[] = array(":?:", "icon_question.gif");
$emotes[] = array(":P", "icon_razz.gif");
$emotes[] = array(":blush:", "icon_redface.gif");
$emotes[] = array(":rolleyes:", "icon_rolleyes.gif");
$emotes[] = array(":)", "icon_smile.gif");
$emotes[] = array(":(", "icon_sad.gif");
$emotes[] = array(":o", "icon_surprised.gif");
$emotes[] = array(":O", "icon_surprised.gif");
$emotes[] = array(";)", "icon_wink.gif");

//
//The below functions should work fine regardless
//of the values of the array above.
//In other words, you don't need to edit these
//unless you want to change how certain things are
//displayed.
//

//An emoticon guide
function emote_guide()
{
	global $emotedirectory, $emotes;

	$size = sizeof($emotes) - 1;
	$i = 0;

	while($i <= $size)
	{
		$image = "<img src=\"".$emotedirectory.$emotes[$i][1]."\" alt=\"\" />";
		echo "<tr>\n\t<td>{$emotes[$i][0]}</td>\n\t<td>$image</td>\n</tr>\n";
		$i++;
	}
}

//Replace the text with an emoticon
function replace_emotes($comment)
{
	global $emotedirectory, $emotes;

	$size = sizeof($emotes) - 1;
	$i = 0;

	while($i <= $size)
	{
		$replace = "<img src=\"".$emotedirectory.$emotes[$i][1]."\" alt=\"\" />";
		$comment = str_replace($emotes[$i][0],$replace,$comment);
		$i++;
	}

	return $comment;
}
