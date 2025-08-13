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
function filledForm($form_vars, $action)
{
	$vararray = explode(',',$form_vars);
	foreach ($vararray as $var)
	{
		$var = trim($var);
		if (!$action[$var]) return false;
	}
	return true;
}

function validEmail($address)
{
	if (eregi("^[a-z0-9]([\._\-]?[a-z0-9])*@[a-z0-9]([\.\-]?[a-z0-9])*\.[a-z]{2,}$", $address))
		return true;
	else
		return false;
}

function checkAddSlashes($text) 
{
  if (get_magic_quotes_gpc()==1) 
  {
    return ($text);
  } 
  else 
  {
    return (addslashes($text)); 
  }
}


?>