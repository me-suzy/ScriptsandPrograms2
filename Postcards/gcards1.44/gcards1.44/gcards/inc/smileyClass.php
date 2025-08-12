<?
/*
 * gCards - a web-based eCard application
 * Copyright (C) 2003 Greg Neustaetter
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
class smileyClass
{
	var $smileys = 	array(
			":)"=>"smile.gif",
			":wide"=>"wide.gif",
			":oogle"=>"oogle.gif",
			":("=>"sad.gif",
			":cool"=>"cool.gif",
			":wink"=>"wink.gif",
			":kiss"=>"kiss.gif",
			":beard"=>"beard.gif",
			":devil"=>"devil.gif",
			":bighair"=>"bighair.gif",
			":woo"=>"woo.gif",
		);
	var $imgPath = '';
	function smileyClass($relPath='')
	{
		$this->imgPath = $relPath;
	}
	function replaceSmileys($text)
	{
		foreach($this->smileys as $symbol=>$image)
		{
			$array1[] = $symbol;
			$array2[] = "<img src=\"".$this->imgPath.$image."\" border=0>";
		}
		$text = str_replace($array1, $array2, $text);
		return $text;
	}		
	function showSmileys($field)
	{
		if (!stristr($_SERVER['HTTP_USER_AGENT'], 'mac') and
		    !stristr($_SERVER['HTTP_USER_AGENT'], 'opera') and
		    preg_match('#msie ([0-9].[0-9]{1,2})#i', $_SERVER['HTTP_USER_AGENT'], $browser)
		    and $browser[1] >= 5.5) 
		{
			foreach($this->smileys as $symbol=>$image)
			{
			?>
				<a href="javascript:editor_insertHTML('<? echo $field; ?>','<? echo ' '.$symbol; ?>');"><img src="<? echo $this->imgPath.$image; ?>" border="0" alt="<? echo $symbol; ?>" title="<? echo $symbol; ?>"></a>
			<?
			}
		}
		else
		{
			foreach($this->smileys as $symbol=>$image)
			{
			?>
				<a href="javascript:insert('<? echo $symbol; ?>');"><img src="<? echo $this->imgPath.$image; ?>" border="0" alt="<? echo $symbol; ?>" title="<? echo $symbol; ?>"></a>
			<?
			}
		}
	}
}
?>