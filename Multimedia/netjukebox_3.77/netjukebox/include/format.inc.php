<?php
//  +---------------------------------------------------------------------------+
//  | netjukebox, Copyright © 2001-2005  Willem Bartels                         |
//  |                                                                           |
//  | info@netjukebox.nl                                                        |
//  | http://www.netjukebox.nl                                                  |
//  |                                                                           |
//  | This file is part of netjukebox.                                          |
//  | netjukebox is free software; you can redistribute it and/or modify        |
//  | it under the terms of the GNU General Public License as published by      |
//  | the Free Software Foundation; either version 2 of the License, or         |
//  | (at your option) any later version.                                       |
//  |                                                                           |
//  | netjukebox is distributed in the hope that it will be useful,             |
//  | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
//  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
//  | GNU General Public License for more details.                              |
//  |                                                                           |
//  | You should have received a copy of the GNU General Public License         |
//  | along with this program; if not, write to the Free Software               |
//  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
//  +---------------------------------------------------------------------------+



//  +---------------------------------------------------------------------------+
//  | Formatted Navigator                                                       |
//  +---------------------------------------------------------------------------+
function FormattedNavigator($url, $name, $close = true)
{
global $cfg;
if (count($name) == 1)	echo '<font class="xl">' . "\n";
else					echo '<strong>' . "\n";
for($i=0; $i < count($name); $i++)
	{
	if ($i > 0)				echo '<img src="' . $cfg['img'] . '/small_arrow.gif" alt="" width="21" height="21" border="0" class="align">';
	if (!empty($url[$i]))	echo '<a href="' . $url[$i] . '">' . htmlentities($name[$i]) . '</a>' . "\n";
	else					echo htmlentities($name[$i]) . "\n";
	}
if (count($name) == 1)	echo '</font>' . "\n";
else					echo '</strong>' . "\n";
if ($close) echo '<br><br>' . "\n";
}



//  +---------------------------------------------------------------------------+
//  | Formatted Time                                                            |
//  +---------------------------------------------------------------------------+
function FormattedTime($miliseconds)
{
$seconds 	= round($miliseconds / 1000);
$hour		= floor($seconds / 3600);
$minutes 	= floor($seconds / 60) - $hour * 60;
$seconds 	= $seconds - floor($seconds / 60) * 60;
if ($hour > 0) $hour = $hour . ':'; else $hour = '';
return $hour . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
}



//  +---------------------------------------------------------------------------+
//  | Formatted Size                                                            |
//  +---------------------------------------------------------------------------+
function FormattedSize($filesize)
{
$weight = array('bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
for ($i = 0; $filesize >= 1024; $i++)
	$filesize /= 1024;
return number_format($filesize, 2) . ' ' . $weight[$i];
}



//  +---------------------------------------------------------------------------+
//  | Formatted Frequency                                                       |
//  +---------------------------------------------------------------------------+
function FormattedFrequency($frequency)
{
$weight = array('Hz', 'kHz', 'MHz', 'GHz', 'THz', 'PHz', 'EHz', 'ZHz', 'YHz');
for ($i = 0; $frequency >= 1000; $i++)
	$frequency /= 1000;
return number_format($frequency, 1) . ' ' . $weight[$i];
}



//  +---------------------------------------------------------------------------+
//  | Formatted Date                                                            |
//  +---------------------------------------------------------------------------+
function FormattedDate($year = NULL, $month = NULL, $day = NULL)
{
$date = '';
if (isset($day))	$date .= str_pad($day, 2, 0, STR_PAD_LEFT) . '&nbsp;';
if (isset($month))	$date .= FormattedMonth($month) . '&nbsp;';
if (isset($year))	$date .= $year;
return $date;
}



//  +---------------------------------------------------------------------------+
//  | Formatted Month                                                           |
//  +---------------------------------------------------------------------------+
function FormattedMonth($number)
{
$month = array(1 =>	'January', 'February', 'March', 'April', 'May', 'June',
					'July', 'August', 'September', 'October', 'November', 'December');
return $month[$number];
}



//  +---------------------------------------------------------------------------+
//  | Encode Escape Characters                                                  |
//  +---------------------------------------------------------------------------+
function EncodeEscapeCharacters($file)
{
global $cfg;
$file = str_replace('?', '^', $file); // ? to ^
$file = str_replace(';', ':', $file);
//if ($cfg['convert_undersquare'])
//	$file = str_replace(' ', '_', $file);
return $file;
}



//  +---------------------------------------------------------------------------+
//  | Decode Escape Characters                                                  |
//  +---------------------------------------------------------------------------+
function DecodeEscapeCharacters($file)
{
global $cfg;
$file = str_replace('^', '?', $file); // ^ to ?
$file = str_replace(';', ':', $file);
if ($cfg['convert_undersquare'])
	$file = str_replace('_', ' ', $file);
return $file;
}



//  +---------------------------------------------------------------------------+
//  | Random Key                                                                |
//  +---------------------------------------------------------------------------+
function random($format)
{
if ($format == '40hex')
	{
	// 160 bit key used for identification
	return sha1(uniqid(rand(), true));
	}
elseif ($format == '32base64')
	{
	// 192 bit key used for hashing
	// (decode with base64 for a 24 bytes triple des key)
	$key = md5(uniqid(rand(), true)) . md5(uniqid(rand(), true));
	$key = base64_encode(pack('H*', $key));
	return substr($key, 0, 32);
	}
else
	exit ('random wrong format');
}



//  +---------------------------------------------------------------------------+
//  | HMAC SHA-1                                                                |
//  +---------------------------------------------------------------------------+
function hmacsha1($key, $data)
{
$blocksize = 64;
if (strlen($key) > $blocksize) 
	$key = pack('H*', sha1($key));
$key		= str_pad($key, $blocksize, chr(0x00));
$ipad		= str_repeat(chr(0x36), $blocksize);
$opad		= str_repeat(chr(0x5c), $blocksize);
$hmacsha1	= pack('H*', sha1(($key^$opad) . pack('H*', sha1(($key^$ipad) . $data))));
return bin2hex($hmacsha1);
}
?>