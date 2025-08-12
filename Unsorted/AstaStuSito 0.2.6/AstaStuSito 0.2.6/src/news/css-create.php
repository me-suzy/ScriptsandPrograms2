<?php

/*
AstaStuSito ver 0.2.6
Copyright (C) 2001-2002 isazi <isazi@olografix.org>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

# Include

include("../back/auth-conf.php");
include("../back/message-func.php");

include("news-conf.php");

if ( ($User == $UserNewsOK) && ($Pass == $PassNewsOK) )	{
	
	$Content = "body {
	\tbackground-color : $BackColor;
	\tbackground-image : url($BackImage);
	}

	h1 {
	\tfont-family : $TitleFont;
	\tcolor : $TitleColor;
	}

	p {
	\tfont-family : $ParaFont;
	\tcolor : $ParaColor;
	}

	a {
	\tfont-family : $LinkFont;
	\tcolor : $LinkColor;
	\ttext-decoration : $LinkStyle;
	}\n";

	if ( file_exists($CSSName) ) {
		unlink($CSSName);
	}

	$File = fopen($CSSName, "w") or exit;
	flock($File, 2);
	fputs($File, $Content);
	flock($File, 3);
	fclose($File);
	
	$Title = "CSS Created";
	$Message = "Ok, your css is created :+)";
	print(MakeXhtmlMessage($Title, $Message));
	
	exit;
	
}

else {
        
	$Title = "Logon Failed";
        $Message = "Your logon has failed, please SHUT UP AND DIE :-)";

	print(MakeXhtmlMessage($Title, $Message));
        exit;
	
}

?>
