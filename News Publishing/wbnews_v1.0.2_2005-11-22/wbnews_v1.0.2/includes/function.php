<?php


/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 31st July 2005                          #||
||#     Filename: function.php                           #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**

	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package main

*/

if (!defined('wbnews'))
	die('Hacking Attempt');

define("INC_FUNC", true);

/**
	Returns a limited version of a string, except keeping any BBcode URLs within the string

	@access public
	@param String        - The String to limit
    @param int limit     - Number of Characters to allow
	@return String
*/
function limitWords($string, $limit)
{
	$stringLength = strlen($string);
	$stringBuffer = '';
	$bufferLimit = 0;
	
	$doString = false;
	for ($i=0;$i<$stringLength;$i++)
	{
		if ($bufferLimit == $limit)
			break;
		
		$char = $string{$i};
		if ($char == '[')
		{
			if (strpos(substr($string, $i + 1, 3), 'url') >= 0)
			{
				$start = $i;
				$end = strpos(substr($string, $start), '[/url]'); //find first [/url] to get end position
				$newString = substr($string, $start, $end + 6); //get substr + [/url]
				$newStringLength = strlen($newString);
				$doString = true;
			}
		}

		if ($doString === false)
		{
			$stringBuffer .= $char;
			$bufferLimit++;
		}
		else
		{
			$stringBuffer .= $newString;
			$doString = false;
			$i = $i + $newStringLength; //skip over the next `x` characters
			
			if ($bufferLimit == $limit || ($limit - $bufferLimit) < 3) //dont continue if there are only 3chars left
				break;
		}
	}	
	
	return $stringBuffer;
}

/**
    @param string string - The String to be parsed
    @param int Width - Characters before break
    @param string break - The string to break text
    @return string
    
    @todo detect if there is HTML Tags
*/
function word_wrap($string, $width, $break) 
{
    $string = preg_replace('/([^\s]{'.$width.'})/i', "$1" . $break, $string);
    return $string;
}

/**
    @param String banList - Space seperated values of IP Addresses
    @return boolean
*/
function isBanned($string)
{
    $findme = $_SERVER['REMOTE_ADDR'];
    if (strpos($string, $findme) !== false)
        return true;
    return false;
}

function isSpam($string, $spamWords)
{
}

function toGMT()
{
    $GMT = date("O");
    return -(36 * $GMT). " ";
}

/**
    
*/
function badWordFilter($badWordList, $string)
{
    $replacement = $badWordList['badwords_replacement'];
    $replacementLength = strlen($replacement); 
    $list = $badWordList['badwords'];

    $numList = count($listExplode = explode(" ",$list));
    for ($i=0;$i<$numList;$i++)
    {

        $length=strlen($listExplode[$i]);
        $replace = '';
        for ($r=1;$r<=$length;$r++)
        {
            $b = ($r-1);
            if ($r > $replacement)
                $b = 0;
            
            if ($replacement !='')
                $replace .= $replacement{$b};
            else
                $replace='';
        }
        
        $string = preg_replace("/".$listExplode[$i]."/i", $replace, $string);
    }    
    return $string;
    
}

function getUsername($db, $userid)
{
    $getUser = $db->db_query("SELECT username FROM " . TBL_USERS . " WHERE userid = '" . (int)$userid . "'");
    if ($db->db_numrows($getUser))
    {
        $user = $db->db_fetcharray($getUser);
        return $user['username'];
    }
    else
        return false;
}

?>
