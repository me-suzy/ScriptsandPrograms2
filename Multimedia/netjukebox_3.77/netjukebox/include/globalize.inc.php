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
//  | Get                                                                       |
//  +---------------------------------------------------------------------------+
function get($key = NULL)
{
$value = '';
if (isset($key))	$value = @$_GET[$key];
else				$value = $_GET;
if (get_magic_quotes_gpc()) return RemoveMagicQuotes($value);
else						return $value;
}



//  +---------------------------------------------------------------------------+
//  | Post                                                                      |
//  +---------------------------------------------------------------------------+
function post($key = NULL)
{
$value = '';
if (isset($key))	$value = @$_POST[$key];
else				$value = $_POST;
if (get_magic_quotes_gpc())	return RemoveMagicQuotes($value);
else						return $value;
}



//  +---------------------------------------------------------------------------+
//  | Cookie                                                                    |
//  +---------------------------------------------------------------------------+
function cookie($key = NULL)
{
$value = '';
if (isset($key))	$value = @$_COOKIE[$key];
else				$value = $_COOKIE;
if (get_magic_quotes_gpc()) return	RemoveMagicQuotes($value);
							return $value;
}



//  +---------------------------------------------------------------------------+
//  | Get + Post                                                                |
//  +---------------------------------------------------------------------------+
function GetPost($key)
{
$value = '';
if		(isset($_GET[$key]))	$value = @$_GET[$key];
elseif	(isset($_POST[$key]))	$value = @$_POST[$key];
if 		(get_magic_quotes_gpc())	return RemoveMagicQuotes($value);
else								return $value;
}



//  +---------------------------------------------------------------------------+
//  | Remove Magic Quotes (string and array)                                    |
//  +---------------------------------------------------------------------------+
function RemoveMagicQuotes($string)
{
if (is_array($string))	@array_walk($string, 'stripslashes');
else					$string = stripslashes($string);
return $string;
}
?>
