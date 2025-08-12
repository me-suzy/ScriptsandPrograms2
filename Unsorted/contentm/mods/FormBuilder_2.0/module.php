<?php
	
	/*////////////////////////////////////////////////////////////
	
	iWare Professional 4.0.0
	Copyright (C) 2002,2003 David N. Simmons 
	http://www.dsiware.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	A COPY OF THE GPL LICENSE FOR THIS PROGRAM CAN BE FOUND WITHIN THE
	docs/ DIRECTORY OF THE INSTALLATION PACKAGE.

	/////////////////////////////////////////////////////////////*/

	$result=$IW->Query("select * from mod_formbuilder limit 1");	
	$mode=$IW->Result($result,0,"mail_mode");
	$smtp=$IW->Result($result,0,"mail_smtp");
	$to=$IW->Result($result,0,"mail_to");
	$subject=$IW->Result($result,0,"mail_subject");
	$from=$IW->Result($result,0,"mail_from");
	$reply=$IW->Result($result,0,"reply");
	$submit=$IW->Result($result,0,"submit");
	if(isset($send)&&$send==1)
		{
		if($mode==1){ini_set("SMTP",$smtp);}
		$body="";
		for($i=0;$i<10;$i++)
			{
			$string=$IW->Result($result,0,"f_".$i);
			$data = explode("|",$string);
			$name=$data[0];
			$body.=$name." : ".${str_replace(" ","_",$name)}."\n";
			}		
		@mail($to,$subject,$body,"From:".$from."\n\n");
		echo "<p><i><b>".$reply."</b></i></p>";
		}
	else
		{
		echo "<center><table border=0 width=500>";
		echo "<form method=post action=\"?D=$D&send=1\">\n";
		$V="send|";
		for($i=0;$i<10;$i++)
			{
			$string=$IW->Result($result,0,"f_".$i);
			$data = explode("|",$string);
			$name=$data[0];
			$type=$data[1];
			$value=$data[2];
			$V.=$name."|";
			switch($type)
				{
				case 1: // text box
					echo str_replace("_"," ",$name)."<br /><input type=text size=30 name=\"".str_replace(" ","_",$name)."\" value=\"".$value."\"><br />";
				break;
				case 2: // text area
					echo str_replace("_"," ",$name)."<br /><textarea name=\"".str_replace(" ","_",$name)."\" rows=5 cols=40>".$value."</textarea><br />";
				break;
				case 3: // check box
					echo "<input type=checkbox name=\"".str_replace(" ","_",$name)."\" value=\"".$value."\"> ".str_replace("_"," ",$name)."<br />";
				break;
				case 4: // yes no
					echo str_replace("_"," ",$name)."<br /><input type=radio name=\"".str_replace(" ","_",$name)."\" value=\"Y\" checked> Yes <input type=radio  name=\"".str_replace(" ","_",$name)."\" value=\"N\"> No<br />";
				break;
				}
			}
		echo "</table><br />\n";
		echo "<input type=submit value=\"".$submit."\"></center>\n";
		echo "<input type=hidden name=V value=\"".str_replace(" ","_",$V)."\">\n";
		echo "</form>";
		}
	$IW->FreeResult($result);
?>