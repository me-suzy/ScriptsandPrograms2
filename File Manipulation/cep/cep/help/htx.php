<?php
/*------------------------------------------------------------------------------
CJG EXPLORER PRO v3.2 - WEB FILE MANAGEMENT - Copyright (C) 2003 CARLOS GUERLLOY
CJGSOFT Software
cjgexplorerpro@guerlloy.com
guerlloy@hotmail.com
carlos@weinstein.com.ar
Buenos Aires, Argentina
--------------------------------------------------------------------------------
This program is free software; you can  redistribute it and/or  modify it  under
the terms   of the   GNU General   Public License   as published   by the   Free
Software Foundation; either  version 2   of the  License, or  (at  your  option)
any  later version. This program  is  distributed in  the hope that  it  will be
useful,  but  WITHOUT  ANY  WARRANTY;  without  even  the   implied  warranty of
MERCHANTABILITY  or FITNESS  FOR A  PARTICULAR  PURPOSE.  See the  GNU   General
Public License for   more details. You  should have received  a copy of  the GNU
General Public License along  with this  program; if   not, write  to the   Free
Software  Foundation, Inc.,  59 Temple Place,  Suite 330, Boston,  MA 02111-1307
USA
------------------------------------------------------------------------------*/

include("all.php");


$htx=array(
"about"=>array("",
	"<BR><TABLE align=center cellspacing=0 cellpadding=0 border=0 style='background-image:url(himg/fondo.gif); background-repeat:no-repeat;' width=400 height=278>
	<TR><TD align=center><BR><IMG align=absmiddle src='himg/cjg.gif' width=118 height=18 border=0><BR>Version 3.2<BR><BR>
	Product site&nbsp;<B><A href='http://www.cjgexplorerpro.com.ar'>http://www.cjgexplorerpro.com.ar</A></B><BR>
	Company site&nbsp;<B><A href='http://www.cjgsoft.com.ar'>http://www.cjgsoft.com.ar</A></B><BR>
	Company e-mail&nbsp;<B><A href='mailto:cep@cjgsoft.com.ar'>cep@cjgsoft.com.ar</A></B><BR><BR>&copy;2002,2003 <A href='mailto:cep@guerlloy.com'>Carlos Guerlloy</A>
	</TD></TR></TABLE>",""),
"features"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0 width=100%><TR><TD class=jus>
	 <BR><B>".dohref("CjgExplorerPro",$lab['about'])."</B> is a Php/Javascript script oriented to manage remote files through the web, using the http protocol.<BR><BR>
	 The interface is tree-oriented. The user can browse the remote tree folder as he/she does locally with the regular file explorer.<BR><BR>
	 The files can be ".dohref("deleted",$lab['delete']).", ".dohref("copied",$lab['copy']).", ".dohref("moved",$lab['move']).", ".dohref("renamed",$lab['rename'])." and ".dohref("edited",$lab['edittextfile'])." remotely. Files permissions can be ".dohref("changed",$lab['chmod']).", folders can be ".dohref("created",$lab['mkdir'])." or ".dohref("deleted",$lab['delete'])." along with their contents.<BR><BR>
	 File archiving and compression are supported (".dohref("tar",$lab['tar']).", ".dohref("tgz",$lab['tgz'])." and ".dohref("zip",$lab['zip'])." formats), as well as remote archive extraction and/or uncompression.<BR><BR>
	 File transfers are supported both ways for single or multiple files (".dohref("download",$lab['download'])." and ".dohref("upload",$lab['upload']).").<BR><BR>
	 All functions are fully ".dohref("configurable",$lab['configure']).", the virtual root folder, the color scheme, and the language used (an utility for easy ".dohref("translate",$lab['translate'])." is included). 
	 </TD></TR></TABLE>",""),
"license"=>array(
	"<TABLE cellspacing=0 cellpadding=0 border=0 width=100%><TR><TD class=jus>
	<BR><B>CjgExplorerPro</B> is free, under the terms of the GPL License.</TD></TR></TABLE>",
	"<TABLE cellspacing=0 cellpadding=0 border=0 width=100%><TR><TD class=jus8><BR><BR>
	<B>DISCLAIMER.</B> This program is free software; you can redistribute it and/or modify it underthe terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version. This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA</TD></TR></TABLE>",""),
"screen"=>array(
	"<TABLE cellspacing=0 cellpadding=0 border=0 align=center><TR><TD>&nbsp;</TD></TR><TR>
	<TD><IMG name=sr1c1 onmouseover='chb();' onmouseout='chb()' src='himg/sr1c1.gif' width=83 height=20><BR><IMG name=sr3c1 onmouseover='chb();' onmouseout='chb()' src='himg/sr3c1.gif' width=83 height=193></TD>
	<TD><IMG name=sr1c2 onmouseover='chb();' onmouseout='chb()' src='himg/sr1c2.gif' width=141 height=10><BR><IMG name=sr2c2 onmouseover='chb();' onmouseout='chb()' src='himg/sr2c2.gif' width=141 height=16><BR><IMG name=sr4c2 onmouseover='chb();' onmouseout='chb()' src='himg/sr4c2.gif' width=141 height=187></TD>
	<TD><IMG name=sr1c3 onmouseover='chb();' onmouseout='chb()' src='himg/sr1c3.gif' width=176 height=10><BR><IMG name=sr5c3 onmouseover='chb();' onmouseout='chb()' src='himg/sr5c3.gif' width=176 height=82><BR><IMG name=sr6c3 onmouseover='chb();' onmouseout='chb()' src='himg/sr6c3.gif' width=176 height=33><BR><IMG name=sr9c3 onmouseover='chb();' onmouseout='chb()' src='himg/sr9c3.gif' width=176 height=78><BR><IMG name=sr7c3 onmouseover='chb();' onmouseout='chb()' src='himg/sr7c3.gif' width=176 height=10></TD>
	<TR><TD colspan=3><IMG name=sr8c1 onmouseover='chb();' onmouseout='chb()' src='himg/sr8c1.gif' width=400 height=10></TD></TR>
	<TR><TD>&nbsp;</TD></TR></TABLE>",
	"<TABLE cellspacing=0 cellpadding=0 border=0 width=100%>
	<TR id=ban><TD class=t3></TD><TD class=jus></TD></TR>
	<TR id=tsr1c1><TD class=t3>Folders toolbar</TD><TD class=jus>Toolbar over the folders tree, its buttons control the tree, for ".dohref("refresh",$lab['refresh'])." and ".dohref("collapse",$lab['collapse']).".</TD></TR>
	<TR id=tsr3c1><TD class=t3>Folders tree</TD><TD class=jus>Tree containing the remote site's folders schema. It is dynamically loaded on demand, as the folders are opened.</TD></TR>
	<TR id=tsr1c2><TD class=t3>Items toolbar</TD><TD class=jus>Toolbar over the items list, its buttons ".dohref("select items",$lab['select']).", ".dohref("configure view",$lab['changeview']).", and ".dohref("go back to the previous folder",$lab['openfolder']).".</TD></TR>
	<TR id=tsr2c2><TD class=t3>Folders combo</TD><TD class=jus>Shows the current folder. Contains the visited folders history list. Allows the user to goback to any of them, anytime.</TD></TR>
	<TR id=tsr4c2><TD class=t3>Items list</TD><TD class=jus>The main list, containing the folders and files contained in the current folder.</TD></TR>
	<TR id=tsr1c3><TD class=t3>Stats panel</TD><TD class=jus>shows the count of items in the current folder, the size of the files, and the selected items.</TD></TR>
	<TR id=tsr5c3><TD class=t3>Filesystem panel</TD><TD class=jus>This panel contains the buttons to execute ".dohref("filesystem functions",$lab['filesystem']).".</TD></TR>
	<TR id=tsr6c3><TD class=t3>Transfer panel</TD><TD class=jus>This panel contains the buttons to execute transfer functions.</TD></TR>
	<TR id=tsr9c3><TD class=t3>Preview panel</TD><TD class=jus>This panel shows a preview of the selected file or folder.</TD></TR>
	<TR id=tsr7c3><TD class=t3>History panel</TD><TD class=jus>This panel contains the history of functions executed during this session.</TD></TR>
	<TR id=tsr8c1><TD class=t3>About panel</TD><TD class=jus>Information ".dohref("about",$lab['about'])." this software.</TD></TR>
	</TABLE>",
	"function chb() { var n=(event.type=='mouseover'); 
		var i=event.srcElement; var o=document.all['t'+i.name]; 
		i.style.cursor=n?'hand':'default'; 
		ban.children.tags('TD')[0].innerText=n?o.children.tags('TD')[0].innerText:'';
		ban.children.tags('TD')[1].innerText=n?o.children.tags('TD')[1].innerText:'';
		ban.style.backgroundColor=n?'#FFFF99':'#FFFFFF'; 
		}"),
"admin"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>These topics are intended to be seen by site webmasters or programmers, not final users.<BR><BR></TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref("Prerequisites",$lab['prereq'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref("Install",$lab['install'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref("Configure",$lab['configure'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref("Translate utility",$lab['translate'])."</TD></TR>
	</TABLE>",""),
"install"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Create or empty the folder where the software will reside.</TD></TR>	
	<TR><TD class=paso>2</TD><TD class=jus>Unzip the distribution file onto it.</TD></TR>	
	<TR><TD class=paso>3</TD><TD class=jus>".dohref("Configure",$lab['configure'])." the product to the desired profile.</TD></TR>	
	<TR><TD class=paso>4</TD><TD class=jus>Type the url to the folder at the web explorer's address bar and start to use it.</TD></TR>	
	</TABLE>",""),
"configure"=>array(
	"<TABLE class=tblw cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>The config.php file contains a collection of variables whose values affect the behavior of the script. The values can be boolean (0 for no, 1 for yes), string or integer. To alter these values, the config.php must be edited with a regular text editor, taking care of the php syntax. The config.php file is full commented to help the configuration. Next is the variables list.</TD></TR>
	<TR><TD>&nbsp;</TD></TR></TABLE>",
	"<TABLE class=tblw cellspacing=1 cellpadding=2 border=0 align=center width=90% bgcolor='#CCCCCC'>
	<TR><TD class=jus colspan=3><B>General configuration</B></TD></TR>
	<TR><TD class=t3>\$usedocroot</TD><TD class=lef8>Boolean</TD><TD class=jus8>If is not set, the application's root is the absolute root of the remote filesystem. If it is set, the root folder for the application is the webserver's document root.</TD></TR>
	<TR><TD class=t3>\$root</TD><TD class=lef8>String</TD><TD class=jus8>The root folder which will act as virtual root for the cjgExplorerPro's user. No file or folder over this one will may be altered or accessed.</TD></TR>
	<TR><TD class=t3>\$langdir</TD><TD class=lef8>String</TD><TD class=jus8>Language used for messages and literals. The language should match the desired language's folder under the langs folder (See ".dohref("translate utility",$lab['translate']).").</TD></TR>
	<TR><TD class=t3>\$show_banner</TD><TD class=lef8>Boolean</TD><TD class=jus8>Shows or hides the ".dohref("About panel",$lab['screen'])."</TD></TR>
	<TR><TD class=jus colspan=3><B>".dohref("Folder Tree",$lab['screen'])."</B></TD></TR>
	<TR><TD class=t3>\$treewidthauto</TD><TD class=lef8>Boolean</TD><TD class=jus8>If this setting is on, the ".dohref("Folder Tree",$lab['screen'])."'s width will be automatically adjusted as the tree expands and collapses. If not, the width is fixed. It can be resized by the user, tough (see <FONT color='#FF0000'><B>\$frame_resize</B></FONT>)</TD></TR>
	<TR><TD class=t3>\$treewidthmax</TD><TD class=lef8>Integer</TD><TD class=jus8>Maximum width while resizing the ".dohref("Folder Tree",$lab['screen']).", if <FONT color='#FF0000'><B>\$treewidthauto</B></FONT> is set.</TD></TR>
	<TR><TD class=t3>\$treewidthmin</TD><TD class=lef8>Integer</TD><TD class=jus8>Minimum width while resizing the ".dohref("Folder Tree",$lab['screen']).", if <FONT color='#FF0000'><B>\$treewidthauto</B></FONT> is set.</TD></TR>
	<TR><TD class=jus colspan=3><B>".dohref("Items list",$lab['screen'])."</B></TD></TR>
	<TR><TD class=t3>\$alternateback</TD><TD class=lef8>Boolean</TD><TD class=jus8>Controls the ".dohref("item's list",$lab['screen'])." background. If set, the lines are dark and light grayed alternatively. If not, the background will be white. This is a default setting; it can be changed by the user (".dohref("Configure view",$lab['changeview']).").</TD></TR>
	<TR><TD class=t3>\$col_name</TD><TD class=lef8>Boolean</TD><TD class=jus8>Internal use.</TD></TR>
	<TR><TD class=t3>\$col_size</TD><TD class=lef8>Boolean</TD><TD class=jus8>Shows or hide the sizes column in ".dohref("item's list",$lab['screen'])." This column will be automatically hidden when the folder contain only folders. This is a default setting; it can be changed by the user (".dohref("Configure view",$lab['changeview']).").</TD></TR>
	<TR><TD class=t3>\$col_date</TD><TD class=lef8>Boolean</TD><TD class=jus8>Shows or hide the last modification date column in ".dohref("item's list",$lab['screen'])." This is a default setting; it can be changed by the user (".dohref("Configure view",$lab['changeview']).").</TD></TR>
	<TR><TD class=t3>\$col_type</TD><TD class=lef8>Boolean</TD><TD class=jus8>Shows or hide the file types column in ".dohref("item's list",$lab['screen'])." This is a default setting; it can be changed by the user (".dohref("Configure view",$lab['changeview']).").</TD></TR>
	<TR><TD class=t3>\$col_perm</TD><TD class=lef8>Boolean</TD><TD class=jus8>Shows or hide the permissions (posix systems) column in ".dohref("item's list",$lab['screen'])." This is a default setting; it can be changed by the user (".dohref("Configure view",$lab['changeview']).").</TD></TR>
	<TR><TD class=t3>\$col_owner</TD><TD class=lef8>Boolean</TD><TD class=jus8>Shows or hide the item's owner (posix systems) column in ".dohref("item's list",$lab['screen'])." This is a default setting; it can be changed by the user (".dohref("Configure view",$lab['changeview']).").</TD></TR>
	<TR><TD class=t3>\$col_group</TD><TD class=lef8>Boolean</TD><TD class=jus8>Shows or hide the item's group (posix systems) column in ".dohref("item's list",$lab['screen'])." This is a default setting; it can be changed by the user (".dohref("Configure view",$lab['changeview']).").</TD></TR>
	<TR><TD class=t3>\$datefull</TD><TD class=lef8>Boolean</TD><TD class=jus8>Sets the format date to full (including hour) or short (just date). This is a default setting; it can be changed by the user (".dohref("Configure view",$lab['changeview']).").</TD></TR>
	<TR><TD class=t3>\$permsfull</TD><TD class=lef8>Boolean</TD><TD class=jus8>In posix systems, sets the permission style to full (octal mask 000 to 777) or shows them relative to the current user. The latter is the default and is more useful. This is a default setting; it can be changed by the user (".dohref("Configure view",$lab['changeview']).").</TD></TR>
	<TR><TD class=t3>\$preview_files</TD><TD class=lef8>Boolean</TD><TD class=jus8>Shows a selected file preview in the ".dohref("preview panel",$lab['screen']).", if <FONT color='#FF0000'><B>\$allow_view</B></FONT> is set.</TD></TR>
	<TR><TD class=t3>\$frame_resize</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user (or not) to resize frames.</TD></TR>
	<TR><TD class=t3>\$allow_view</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to view file contents.</TD></TR>
	<TR><TD class=t3>\$allow_exec</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to execute (open in browser) files.</TD></TR>
	<TR><TD class=t3>\$allow_edit</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to edit files.</TD></TR>
	<TR><TD class=t3>\$maxnamelength</TD><TD class=lef8>Integer</TD><TD class=jus8>Maximum length for filenames to show. A longer filename will be shown truncated and a \"...\" will be appended.</TD></TR>
	<TR><TD class=jus colspan=3><B>".dohref("Contents window",$lab['contwin'])."</B></TD></TR>
	<TR><TD class=t3>\$shed_height</TD><TD class=lef8>Integer</TD><TD class=jus8>Default height for the ".dohref("Contents window",$lab['contwin']).". It can be resized by the user.</TD></TR>
	<TR><TD class=t3>\$shed_width</TD><TD class=lef8>Integer</TD><TD class=jus8>Default width for the ".dohref("Contents window",$lab['contwin']).". It can be resized by the user.</TD></TR>
	<TR><TD class=jus colspan=3><B>".dohref("Filesystem functions",$lab['filesystem'])."</B></TD></TR>
	<TR><TD class=t3>\$allow_delete</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to delete files.</TD></TR>
	<TR><TD class=t3>\$allow_copy</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to copy files.</TD></TR>
	<TR><TD class=t3>\$allow_move</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to move or rename files.</TD></TR>
	<TR><TD class=t3>\$allow_create</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to create new files.</TD></TR>
	<TR><TD class=t3>\$allow_mkdir</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to create folders.</TD></TR>
	<TR><TD class=t3>\$allow_chmod</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to change file or folder permissions (Posix systems only).</TD></TR>
	<TR><TD class=t3>\$allow_download</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to download files.</TD></TR>
	<TR><TD class=t3>\$allow_upload</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to upload files.</TD></TR>
	<TR><TD class=t3>\$allow_tar</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to archive or extract files in tar format.</TD></TR>
	<TR><TD class=t3>\$allow_tgz</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to archive or extract files in compressed tar format.</TD></TR>
	<TR><TD class=t3>\$allow_zip</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to compress or uncompress files in zip format.</TD></TR>
	<TR><TD class=t3>\$allow_zid</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to perform one-step compress and download files.</TD></TR>
	<TR><TD class=t3>\$allow_find</TD><TD class=lef8>Boolean</TD><TD class=jus8>Allows the user to find files through the tree.</TD></TR>
	<TR><TD class=t3>\$max_upload</TD><TD class=lef8>Integer</TD><TD class=jus8>Maximum number of files to upload in a single operation.</TD></TR>
	<TR><TD class=t3>\$max_upload_size</TD><TD class=lef8>Integer</TD><TD class=jus8>Maximum file size to upload.</TD></TR>
	<TR><TD class=t3>\$max_deep_levels</TD><TD class=lef8>Integer</TD><TD class=jus8>Maximum number of tree deepness to be allowed in a recursive folder copy. This is for prevention of loops due to links or filesytem inconsistency.</TD></TR>
	<TR><TD class=jus colspan=3><B>Panels</B></TD></TR>
	<TR><TD class=t3>\$open_stats</TD><TD class=lef8>Boolean</TD><TD class=jus8>The ".dohref("stats panel",$lab['screen'])." is initally open. This value is just a default, the user preferences will saved.</TD></TR>
	<TR><TD class=t3>\$open_filefuncs</TD><TD class=lef8>Boolean</TD><TD class=jus8>The ".dohref("filesystem panel",$lab['screen'])." is initally open. This value is just a default, the user preferences will saved.</TD></TR>
	<TR><TD class=t3>\$open_transfer</TD><TD class=lef8>Boolean</TD><TD class=jus8>The ".dohref("transfer panel",$lab['screen'])." is initally open. This value is just a default, the user preferences will saved.</TD></TR>
	<TR><TD class=t3>\$open_preview</TD><TD class=lef8>Boolean</TD><TD class=jus8>The ".dohref("preview panel",$lab['screen'])." is initally open. This value is just a default, the user preferences will saved.</TD></TR>
	<TR><TD class=t3>\$open_board</TD><TD class=lef8>Boolean</TD><TD class=jus8>The ".dohref("history panel",$lab['screen'])." is initally open. This value is just a default, the user preferences will saved.</TD></TR>
	<TR><TD class=jus colspan=3><B>Appearance</B></TD></TR>
	<TR><TD class=t3>\$bodyback</TD><TD class=lef8>String</TD><TD class=jus8>Default background color</TD></TR>
	<TR><TD class=t3>\$allback</TD><TD class=lef8>String</TD><TD class=jus8> Elements background color</TD></TR>
	<TR><TD class=t3>\$allfore</TD><TD class=lef8>String</TD><TD class=jus8>Elements foreground color</TD></TR>
	<TR><TD class=t3>\$allhigh</TD><TD class=lef8>String</TD><TD class=jus8>Highlighted edges color</TD></TR>
	<TR><TD class=t3>\$alldark</TD><TD class=lef8>String</TD><TD class=jus8>Dark edges color</TD></TR>
	<TR><TD class=t3>\$errorback</TD><TD class=lef8>String</TD><TD class=jus8>Error messages background color</TD></TR>
	<TR><TD class=t3>\$errorfore</TD><TD class=lef8>String</TD><TD class=jus8>Error messages foreground color</TD></TR>
	<TR><TD class=t3>\$rowevenback</TD><TD class=lef8>String</TD><TD class=jus8>Even rows background color</TD></TR>
	<TR><TD class=t3>\$rowevenfore</TD><TD class=lef8>String</TD><TD class=jus8>Even rows foreground color</TD></TR>
	<TR><TD class=t3>\$rowoddback</TD><TD class=lef8>String</TD><TD class=jus8>Odd rows background color</TD></TR>
	<TR><TD class=t3>\$rowoddfore</TD><TD class=lef8>String</TD><TD class=jus8>Odd rows foreground color</TD></TR>
	<TR><TD class=t3>\$bodyfont</TD><TD class=lef8>String</TD><TD class=jus8>Default font family</TD></TR>
	<TR><TD class=t3>\$bodyfontsize</TD><TD class=lef8>String</TD><TD class=jus8>Default font size</TD></TR>
	<TR><TD class=t3>\$prefontsize</TD><TD class=lef8>String</TD><TD class=jus8>Monospaced font size</TD></TR>
	</TABLE><BR>",""),
"translate"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>The langs folder contains a subfolder for every language supported. Inside those folders, there is a collection of text files containing all the messages and literals used, in the format <B>english message=translated message</B>, one message per line.</TD></TR>
	<TR><TD class=jus>There is an bundled utility related to this matter included in the distribution package. Running at the web explorer the file \"trans.php\" will help to alter messages or add new languages. The utility is very simple and self-explanatory.</TD></TR>
	<TR><TD align=center><IMG src='himg/trans.gif' width=400 height=222></TD></TR>
	</TABLE>",""),
"howto"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>This section contains a step-by-step description of the tasks that cjgExplorerPro can do. The tasks are divided into these categories:<BR><BR></TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['interface'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['navigation'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['select'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['filesystem'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['search'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['contents'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['transfer'])."</TD></TR>
	</TABLE>",""),
"prereq"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus><B>Server Side</B><BR><BR></TD></TR>
	<TR><TD class=jus>This software requires, in first place, a web server php-capable.<BR>The Php version installed must be 4.03 or superior.<BR>In order to use compression (".dohref("tgz",$lab['tgz'])." and ".dohref("zip",$lab['zip'])." archives), the zlib library must be installed and enabled.</TD></TR>
	<TR><TD class=jus><BR><B>Client Side</B><BR><BR></TD></TR>
	<TR><TD class=jus>This software is developed and tested only for IE 5.0 or superior.<BR>The screen was designed to fit properly in 800x600 pixels or better resolution.</TD></TR>
	</TABLE>",""),
"interface"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>This section covers the following tasks:<BR><BR></TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['hideshowpanel'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['resize'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['hideshowhist'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['clear'])."</TD></TR>
	</TABLE>",""),
"navigation"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>This section covers the following tasks:<BR><BR></TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['refresh'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['collapse'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['openfolder'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['changeview'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['sort'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['help'])."</TD></TR>
	</TABLE>",""),
"select"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>This section covers the following tasks:<BR><BR></TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['selectitems'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['selectall'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['unselect'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['selectallfiles'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['selectallfolders'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['reverse'])."</TD></TR>
	</TABLE>",""),
"filesystem"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>This section covers the following tasks:<BR><BR></TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['delete'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['move'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['rename'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['copy'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['mkdir'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['chmod'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['tar'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['tgz'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['zip'])."</TD></TR>
	</TABLE>",""),
"search"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>This section covers the following tasks:<BR><BR></TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['find'])."</TD></TR>
	</TABLE>",""),
"contents"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>The file contents are accessed by double-clicking the file name (or by pressing Enter while the file is ".dohref("selected",$lab['selectitems']).") in the ".dohref("items list",$lab['screen']).". A ".dohref("contents window",$lab['contwin'])." will be opened, showing the file contents (text or hexadecimal) or opened by the browser. The user can switch between these modes, and the default one depends on the file type.<BR>This section covers the following tasks:<BR><BR></TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['contwin'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['viewfile'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['openfile'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['editnewfile'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['edittextfile'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['editbinaryfile'])."</TD></TR>
	</TABLE>",""),
"transfer"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>This section covers the following tasks:<BR><BR></TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['download'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['zipdownload'])."</TD></TR>
	<TR><TD class=jus><IMG align=absmiddle src='img/page.gif' width=19 height=16>".dohref($lab['upload'])."</TD></TR>
	</TABLE>",""),
"hideshowpanel"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>Every panel on right side of the ".dohref("screen",$lab['screen'])." has a minus sign <IMG align=bottom src='himg/minus.gif' width=9 height=9> on the upper left corner. Clicking on it, the panel will be minimized, and the sign will turn into a plus <IMG align=bottom src='himg/plus.gif' width=9 height=9>, which will show the panel again if clicked.</TD></TR>
	</TABLE>",""),
"resize"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>At ".dohref("normal resolution",$lab['prereq'])." cjgExplorerPro will resize the frames according to needs and normally it will be no necessary for the user to resize the frames. Although, it can be done, if <FONT color='#FF0000'>\$frame_resize</FONT> is set in ".dohref("configuration",$lab['configure'])." </TD></TR>
	</TABLE>",""),
"hideshowhist"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>The ".dohref("history panel",$lab['screen'])." shows a brief summary of every function executed in the session. For design purposes, some details are hidden, and they can be seen clicking on the plus sign <IMG align=bottom src='himg/plus.gif' width=9 height=9> after the execution time. Then, the details can be hidden again using the minus sign <IMG align=bottom src='himg/minus.gif' width=9 height=9>.</TD></TR>
	</TABLE>",""),
"clear"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus>The ".dohref("history panel",$lab['screen'])." shows a brief summary of every function executed in the session. If the user wants to erase the history, this can be done using the button at the upper right corner of such panel.</TD></TR>
	</TABLE>",""),
"refresh"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Click the refresh button <SPAN class=button><IMG align=absmiddle src='himg/refresh.gif' width=15 height=18></SPAN>, in the ".dohref("folders toolbar",$lab['screen'])."</TD></TR>	
	</TABLE>",""),
"collapse"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Click the collapse button <SPAN class=button><IMG align=absmiddle src='himg/collapse.gif' width=11 height=18></SPAN>, in the ".dohref("folders toolbar",$lab['screen'])."</TD></TR>	
	</TABLE>",""),
"openfolder"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>The contents of the opened folder will be shown in the ".dohref("items list",$lab['screen']).". There are six ways to do it:<BR><BR></TD></TR>
	<TR><TD class=paso>A</TD><TD class=jus>Click the folder name in the ".dohref("folders tree",$lab['screen']).".</TD></TR>	
	<TR><TD class=paso>B</TD><TD class=jus>Navigate the ".dohref("folders tree",$lab['screen'])." with the arrow keys and hit Enter to open the chosen one.</TD></TR>	
	<TR><TD class=paso>C</TD><TD class=jus>Click the folder goback button <SPAN class=button><IMG align=absmiddle src='himg/dirant.gif' width=18 height=18></SPAN> in the ".dohref("items toolbar",$lab['screen'])." and the previous folder will be opened again.</TD></TR>	
	<TR><TD class=paso>D</TD><TD class=jus>Select the folder from the folders list in the ".dohref("folders combo",$lab['screen']).", where are stored all the visited folders during the current session, and the chosen folder will be opened again.</TD></TR>	
	<TR><TD class=paso>E</TD><TD class=jus>In the ".dohref("items list",$lab['screen']).", double-click a folder and it will be opened.</TD></TR>	
	<TR><TD class=paso>F</TD><TD class=jus>In the ".dohref("items list",$lab['screen']).", ".dohref("select",$lab['selectitems'])." a folder using the keyboard (arrows, first letter, etc), hit Enter, and the folder will be opened.</TD></TR>	
	</TABLE>",""),
"changeview"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>The view settings start with the ".dohref("configuration",$lab['configure'])."'s default and can be changed by the user:<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Click the settings button <SPAN class=button><IMG align=absmiddle src='himg/tool.gif' width=15 height=18></SPAN> in the ".dohref("items toolbar",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>The settings windows will be shown:<BR>&nbsp;&nbsp;&nbsp;<IMG align=bottom src='himg/changeview.gif' width=221 height=188></TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>Check or uncheck the options (they are explained in detail at the 'Items list' section in the ".dohref("configuration",$lab['configure'])." help).</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>Click Ok or Cancel. The ".dohref("items list",$lab['screen'])." view will change accordingly.</TD></TR>
	</TABLE>",""),
"sort"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>The ".dohref("items list",$lab['screen'])."'s current order are shown with two small arrows <IMG align=bottom src='himg/down.gif' width=9 height=13><IMG align=bottom src='himg/up.gif' width=9 height=13> indicating the column used to sort the list and the ascending or descending direction:<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Click on the desired column to sort the list.</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Click a new column to change the order, or click the same column just to change direction.</TD></TR>
	</TABLE>",""),
"help"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Click the help button <SPAN class=button><IMG align=absmiddle src='himg/help.gif' width=18 height=18></SPAN>, in the ".dohref("folders toolbar",$lab['screen'])."</TD></TR>	
	</TABLE>",""),
"selectitems"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>Items in ".dohref("items list",$lab['screen'])." must be selected to operate with them. Selected items are highlighted, and the count is stated in the ".dohref("stats panel",$lab['screen'])." There are six ways to select items:<BR><BR></TD></TR>
	<TR><TD class=paso>A</TD><TD class=jus>Click on an item. This will be select the current item, and unselect all the other ones.</TD></TR>	
	<TR><TD class=paso>B</TD><TD class=jus>Click on an item while hold the Ctrl key. This will select the current item (if unselected) or unselect it (if selected), keeping those already selected.</TD></TR>	
	<TR><TD class=paso>C</TD><TD class=jus>Click on an item while hold the Shift key. This will select all items between the current and the last selected one.</TD></TR>	
	<TR><TD class=paso>D</TD><TD class=jus>Use navigation keys (Arrows, Home, End, PgDn, PgUp). This will select the desired item and unselect all the other ones.</TD></TR>	
	<TR><TD class=paso>E</TD><TD class=jus>Use navigation keys while hold the Ctrl key. This will move the cursor (a gray rectangle surrounding the name) but will not select any item.</TD></TR>	
	<TR><TD class=paso>F</TD><TD class=jus>Use navigation keys while hold the Shift key. This will select all items between the desired and the last selected one.</TD></TR>	
	</TABLE>",""),
"selectall"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Click the select all button <SPAN class=button><IMG align=absmiddle src='himg/selall.gif' width=15 height=18></SPAN>, in the ".dohref("items toolbar",$lab['screen'])."</TD></TR>	
	</TABLE>",""),
"unselect"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Click the unselect all button <SPAN class=button><IMG align=absmiddle src='himg/unselall.gif' width=15 height=18></SPAN>, in the ".dohref("items toolbar",$lab['screen'])."</TD></TR>	
	</TABLE>",""),
"selectallfiles"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Click the select all files button <SPAN class=button><IMG align=absmiddle src='himg/selfiles.gif' width=15 height=18></SPAN>, in the ".dohref("items toolbar",$lab['screen'])."</TD></TR>	
	</TABLE>",""),
"selectallfolders"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Click the select all folders button <SPAN class=button><IMG align=absmiddle src='himg/selfolder.gif' width=15 height=18></SPAN>, in the ".dohref("items toolbar",$lab['screen'])."</TD></TR>	
	</TABLE>",""),
"reverse"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Click the invert selection button <SPAN class=button><IMG align=absmiddle src='himg/invsel.gif' width=15 height=18></SPAN>, in the ".dohref("items toolbar",$lab['screen'])."</TD></TR>	
	</TABLE>",""),
"delete"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will delete the desired items. Files or folders can be deleted. The operation can not be undone.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>".dohref("Select",$lab['selectitems'])." the items to delete in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Press the <SPAN class=button>Delete</SPAN> button in the ".dohref("filesystem panel",$lab['screen']).", or hit the 'Delete' key.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>When asked, confirm or cancel the deletion.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"move"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will move the desired items to a target. Files or folders can be moved. If the target is an existent folder, then the items will be moved into it. If not, then a rename is performed. In this case, only one item should be selected. The operation can not be undone.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>".dohref("Select",$lab['selectitems'])." the items to move/rename in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Type the target in the textbox next to the <SPAN class=button>Move</SPAN> button in the ".dohref("filesystem panel",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>Press the <SPAN class=button>Move</SPAN> button in the ".dohref("filesystem panel",$lab['screen'])." or hit the 'Enter' key.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>When asked, confirm or cancel the move/rename.</TD></TR>
	<TR><TD class=paso>5</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"rename"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function is part of the ".dohref("move",$lab['move'])." function. Actually, renaming is a special case of moving.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>".dohref("Select",$lab['selectitems'])." the item to rename in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Type the target name in the textbox next to the <SPAN class=button>Move</SPAN> button in the ".dohref("filesystem panel",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>Press the <SPAN class=button>Move</SPAN> button in the ".dohref("filesystem panel",$lab['screen'])." or hit the 'Enter' key.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>When asked, confirm or cancel the rename.</TD></TR>
	<TR><TD class=paso>5</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"copy"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will copy the desired items to a target. Files or folders can be copied. If the target is an existent folder, then the items will be copied into it. If not, then a copy with a new name is performed. In this case, only one item should be selected. The operation can not be undone.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>".dohref("Select",$lab['selectitems'])." the items to copy in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Type the target in the textbox next to the <SPAN class=button>Copy</SPAN> button in the ".dohref("filesystem panel",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>Press the <SPAN class=button>Copy</SPAN> button in the ".dohref("filesystem panel",$lab['screen'])." or hit the 'Enter' key.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>When asked, confirm or cancel the copy.</TD></TR>
	<TR><TD class=paso>5</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"mkdir"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will create a folder relative to the current folder.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Type the new folder name in the textbox next to the <SPAN class=button>Create folder</SPAN> button in the ".dohref("filesystem panel",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Press the <SPAN class=button>Create folder</SPAN> button in the ".dohref("filesystem panel",$lab['screen'])." or hit the 'Enter' key.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>When asked, confirm or cancel folder creation.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"chmod"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will change the permissions to the selected items. This function is applicable only to Unix systems.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>".dohref("Select",$lab['selectitems'])." the items whose permissions will be changed in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Type the new permissions octal mask in the textbox next to the <SPAN class=button>Change perms</SPAN> button in the ".dohref("filesystem panel",$lab['screen']).", or press the mask button <SPAN class=button><IMG align=absmiddle src='himg/chmod.gif' width=15 height=11></SPAN> to open the Permission Mask Window which will compose the octal mask:<BR>
	<IMG align=bottom src='himg/chmodmask.gif' width=206 height=125><BR>Each individual checkbox can be selected, as well as an entire row or column to set three permissions at once. Press the <SPAN class=button>Ok</SPAN> button to accept the mask and it will be copied to the textbox, or press the <SPAN class=button>Cancel</SPAN> button to discard the mask.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>Press the <SPAN class=button>Change perms</SPAN> button in the ".dohref("filesystem panel",$lab['screen'])." or hit the 'Enter' key.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>When asked, confirm or cancel permissions change.</TD></TR>
	<TR><TD class=paso>5</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"tar"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will pack the desired items into a new archive in tar format. Files and folders can be archived. In folders case, they will be scanned recursively to get all their contents.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>".dohref("Select",$lab['selectitems'])." the items to archive in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Type the target file in the textbox next to the <SPAN class=button>TAR archive</SPAN> button in the ".dohref("filesystem panel",$lab['screen']).". The 'tar' extension will be appended if wasn't typed.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>Press the <SPAN class=button>TAR archive</SPAN> button in the ".dohref("archive panel",$lab['screen'])." or hit the 'Enter' key.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>When asked, confirm or cancel the archiving.</TD></TR>
	<TR><TD class=paso>5</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"tgz"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will pack the desired items into a new archive in tgz format. Files and folders can be archived. In folders case, they will be scanned recursively to get all their contents.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>".dohref("Select",$lab['selectitems'])." the items to archive in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Type the target file in the textbox next to the <SPAN class=button>TGZ archive</SPAN> button in the ".dohref("filesystem panel",$lab['screen']).". The 'tgz' extension will be appended if wasn't typed.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>Press the <SPAN class=button>TGZ archive</SPAN> button in the ".dohref("archive panel",$lab['screen'])." or hit the 'Enter' key.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>When asked, confirm or cancel the archiving.</TD></TR>
	<TR><TD class=paso>5</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"zip"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will pack the desired items into a new archive in zip format. Files and folders can be archived. In folders case, they will be scanned recursively to get all their contents.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>".dohref("Select",$lab['selectitems'])." the items to archive in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Type the target file in the textbox next to the <SPAN class=button>ZIP archive</SPAN> button in the ".dohref("filesystem panel",$lab['screen']).". The 'zip' extension will be appended if wasn't typed.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>Press the <SPAN class=button>ZIP archive</SPAN> button in the ".dohref("archive panel",$lab['screen'])." or hit the 'Enter' key.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>When asked, confirm or cancel the archiving.</TD></TR>
	<TR><TD class=paso>5</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"untar"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will extract archived files and/or folders from the selected TAR archive. The operation can not be undone.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>".dohref("Select",$lab['selectitems'])." the archive to expand in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Press the <SPAN class=button>UnTAR</SPAN> button in the ".dohref("archive panel",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>When asked, confirm or cancel the archive expand.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"untgz"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will extract archived files and/or folders from the selected TGZ archive. The operation can not be undone.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>".dohref("Select",$lab['selectitems'])." the archive to expand in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Press the <SPAN class=button>UnTGZ</SPAN> button in the ".dohref("archive panel",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>When asked, confirm or cancel the archive expand.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"unzip"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will extract archived files and/or folders from the selected ZIP archive. The operation can not be undone.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>".dohref("Select",$lab['selectitems'])." the archive to expand in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Press the <SPAN class=button>UnZIP</SPAN> button in the ".dohref("archive panel",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>When asked, confirm or cancel the archive expand.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"find"=>array("","<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This tool will search for item names inside the tree. The search string can be the whole name, part of it, or a pattern (a regular expression).<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Type the string to search in the textbox next to the <SPAN class=button>Find files with</SPAN> button in the ".dohref("filesystem panel",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Press the <SPAN class=button>Find files with</SPAN> button in the ".dohref("filesystem panel",$lab['screen'])." or hit the 'Enter' key.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>A small window will show the search results, and the folder location for each found item.</TD></TR>
	<TR><TD class=paso>5</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"contwin"=>array("<TABLE cellspacing=0 cellpadding=0 border=0 width=100%><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This window allows the user to view, open, edit and download a particular file.<BR><BR></TD></TR>
	<TR><TD align=center>Normal mode<BR><IMG src='himg/contwin.gif' width=345 height=214 border=0></TD></TR>
	<TR><TD align=center>Edit mode<BR><IMG src='himg/editwin.gif' width=345 height=214 border=0></TD></TR>
	<TR><TD>&nbsp;</TD></TR>
	</TABLE>","<TABLE class=tblw cellspacing=1 cellpadding=2 border=0 align=center width=90% bgcolor='#CCCCCC'>
	<TR><TD nowrap><SPAN class=button><B>/cccc.html</B></SPAN></TD><TD class=jus>The name of the file being viewed or edited.</TD></TR>
	<TR><TD nowrap><SPAN class=button>189 bytes, page 1/1</SPAN></TD><TD class=jus>The size, the current and the total pages (screens) for the file being viewed</TD></TR>
	<TR><TD nowrap><SPAN class=button>Edit</SPAN></TD><TD class=jus>This button enters into the edit mode. The editing can be performed in hexadecimal or text mode, depending on the current view mode.</TD></TR>
	<TR><TD nowrap><SPAN class=button>Text</SPAN></TD><TD class=jus>This button changes the view mode to text.</TD></TR>
	<TR><TD nowrap><SPAN class=button>Hex</SPAN></TD><TD class=jus>This button changes the view mode to hexadecimal.</TD></TR>
	<TR><TD nowrap><SPAN class=button>Open</SPAN></TD><TD class=jus>This button opens (execute) the file. For instance, if the file is a graphic gif file, the user will see the actual picture instead of the file bytes.</TD></TR>
	<TR><TD nowrap><SPAN class=button>&lt;&lt;</SPAN></TD><TD class=jus rowspan=4>This buttons allow to navigate through the file when it takes more than one window page (screen).</TD></TR>
	<TR><TD nowrap><SPAN class=button>&lt;</SPAN></TD></TR>
	<TR><TD nowrap><SPAN class=button>&gt;</SPAN></TD></TR>
	<TR><TD nowrap><SPAN class=button>&gt;&gt;</SPAN></TD></TR>
	<TR><TD nowrap><SPAN class=button>Save</SPAN></TD><TD class=jus>This button saves, after a confirmation, the file to disk in the remote system.</TD></TR>
	<TR><TD nowrap><SPAN class=button>Exit</SPAN></TD><TD class=jus>This button leaves the edit mode and return back to normal. If the file was modified and not saved, then a discard changes confirmation is asked.</TD></TR>
	</TABLE>",""),
"viewfile"=>array("","<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>Viewing a file means get and see its contents. The file can be viewed either in text or hex mode.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Double-click the file name, or press Enter while is ".dohref("selected",$lab['selectitems'])." in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>The ".dohref("contents window",$lab['contwin'])." is opened.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>Choose between hexadecimal or text modes by clicking the <SPAN class=button>Hex</SPAN> or <SPAN class=button>Text</SPAN> buttons.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>If the file does not fit in one screen, use the <SPAN class=button>&lt;&lt;</SPAN> <SPAN class=button>&lt;</SPAN> <SPAN class=button>&gt;</SPAN> <SPAN class=button>&gt;&gt;</SPAN> buttons to navigate.</TD></TR>
	</TABLE>",""),
"openfile"=>array("","<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>Opening a file means ask to the webserver for it and execute it in the ".dohref("contents window",$lab['contwin']).".<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Double-click the file name, or press Enter while is ".dohref("selected",$lab['selectitems'])." in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>The ".dohref("contents window",$lab['contwin'])." is opened.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>CLick the <SPAN class=button>Open</SPAN> button.</TD></TR>
	</TABLE>",""),
"editnewfile"=>array("",
	"<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will create and edit a text file relative to the current folder.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Type the new file name in the textbox next to the <SPAN class=button>Edit new file</SPAN> button in the ".dohref("filesystem panel",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Press the <SPAN class=button>Edit new file</SPAN> button in the ".dohref("filesystem panel",$lab['screen'])." or hit the 'Enter' key.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>When asked, confirm or cancel the file creation.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>The ".dohref("contents window",$lab['contwin'])." is opened.</TD></TR>
	<TR><TD class=paso>5</TD><TD class=jus>Edit the file with the regular text editing functions.</TD></TR>
	<TR><TD class=paso>6</TD><TD class=jus>When done, save the file with the <SPAN class=button>Save</SPAN> button. A confirmation will be asked.</TD></TR>
	<TR><TD class=paso>7</TD><TD class=jus>Quit the edit mode with the <SPAN class=button>Exit</SPAN> button. If the file was not saved, a confirmation to discard the changes will be asked.</TD></TR>
	<TR><TD class=paso>8</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"edittextfile"=>array("","<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>Remote files can be edited and saved, if the user privileges allows to modify the file. This will depend on the remote operating system and the permissions of the file relative to the current user (see <FONT color='#FF0000'>\$permsfull</FONT> parameter in ".dohref($lab['configure']).").<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Double-click the file name, or press Enter while is ".dohref("selected",$lab['selectitems'])." in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>The ".dohref("contents window",$lab['contwin'])." is opened.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>Choose text mode by clicking the <SPAN class=button>Text</SPAN> button.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>Click the <SPAN class=button>Edit</SPAN> button to enter edit mode.</TD></TR>
	<TR><TD class=paso>5</TD><TD class=jus>Edit the file with the regular text editing functions.</TD></TR>
	<TR><TD class=paso>6</TD><TD class=jus>When done, save the file with the <SPAN class=button>Save</SPAN> button. A confirmation will be asked.</TD></TR>
	<TR><TD class=paso>7</TD><TD class=jus>Quit the edit mode with the <SPAN class=button>Exit</SPAN> button. If the file was changed and not saved, a confirmation to discard the changes will be asked.</TD></TR>
	</TABLE>",""),
"editbinaryfile"=>array("","<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>Remote files can be edited and saved, if the user privileges allows to modify the file. This will depend on the remote operating system and the permissions of the file relative to the current user (see <FONT color='#FF0000'>\$permsfull</FONT> parameter in ".dohref($lab['configure']).").<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Double-click the file name, or press Enter while is ".dohref("selected",$lab['selectitems'])." in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>The ".dohref("contents window",$lab['contwin'])." is opened.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>Choose hexadecimal mode by clicking the <SPAN class=button>Hex</SPAN> button.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>Click the <SPAN class=button>Edit</SPAN> button to enter edit mode.</TD></TR>
	<TR><TD class=paso>5</TD><TD class=jus>Edit the file. While in this mode, the user can use the navigation keys (arrows, pgdn, pgup, home, end) to move by the file, and the Tab key to switch between the hex and ascii panels.</TD></TR>
	<TR><TD class=paso>6</TD><TD class=jus>When done, save the file with the <SPAN class=button>Save</SPAN> button. A confirmation will be asked.</TD></TR>
	<TR><TD class=paso>7</TD><TD class=jus>Quit the edit mode with the <SPAN class=button>Exit</SPAN> button. If the file was changed and not saved, a confirmation to discard the changes will be asked.</TD></TR>
	</TABLE>",""),
"download"=>array("","<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will download the desired items. Only files can be downloaded. Although multiple files can be downloaded at once, for each file selected the browser will ask with a 'Save as' dialog, which may be uncomfortable with a large number of files. For such cases, the ".dohref("Zip and Download",$lab['zipdownload'])." function is recommended.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>".dohref("Select",$lab['selectitems'])." the items to download in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Press the <SPAN class=button>Download</SPAN> button in the ".dohref("transfer panel",$lab['screen']).", or hit the 'Enter' key.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>When asked, confirm or cancel the download.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"zipdownload"=>array("","<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function will first pack the desired items into a single zip file, and then download the zip file. Files and folders (including their contents) can be downloaded. The packed file has the default name 'download.zip', which can be changed by the user at the 'Save as' dialog.<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>".dohref("Select",$lab['selectitems'])." the items to download in the ".dohref("items list",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Press the <SPAN class=button>Zip &amp; Download</SPAN> button in the ".dohref("transfer panel",$lab['screen']).", or hit the 'Enter' key.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>When asked, confirm or cancel the download.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"upload"=>array("","<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This function allows to upload files from the local client to the remote server's current folder. Multiple files can be uploaded in a single operation. The success of the action will depend on the write permissions of the receiving folder relative to the current user (see <FONT color='#FF0000'>\$permsfull</FONT> parameter in ".dohref($lab['configure']).").<BR><BR></TD></TR>
	<TR><TD class=paso>1</TD><TD class=jus>Type the path of the file to upload the textbox next to the <SPAN class=button>Upload</SPAN> button in the ".dohref("transfer panel",$lab['screen']).", or use the <SPAN class=button>Browse</SPAN> button at right to pick the file.</TD></TR>
	<TR><TD class=paso>2</TD><TD class=jus>Hit 'Enter'. The typed file will scroll down, and a new empty textbox will be available to input another file.</TD></TR>
	<TR><TD class=paso>3</TD><TD class=jus>Repeat the steps 1 and 2 as long as needed to enter all the files to upload. The clear button <SPAN class=button><IMG align=absmiddle src='himg/fclear.gif' width=15 height=11></SPAN> can be used to reset all the filled fields.</TD></TR>
	<TR><TD class=paso>4</TD><TD class=jus>When done, press the <SPAN class=button>Upload</SPAN> button in the ".dohref("transfer panel",$lab['screen']).".</TD></TR>
	<TR><TD class=paso>5</TD><TD class=jus>When asked, confirm or cancel the upload.</TD></TR>
	<TR><TD class=paso>6</TD><TD class=jus>The upload will start and may take some time, depending on the size of the files. A small 'upload in progress' window will be showed while the upload is running:<BR><IMG src='himg/uploading.gif' width=204 height=101 border=0></TD></TR>
	<TR><TD class=paso>7</TD><TD class=jus>The 'upload in progress' window has a <SPAN class=button>Cancel Upload</SPAN> button, which can be pressed to abort the download anytime.</TD></TR>
	<TR><TD class=paso>8</TD><TD class=jus>When finished, the 'upload in progress' window is automatically closed.</TD></TR>
	<TR><TD class=paso>9</TD><TD class=jus>This action will be added to the ".dohref("Session history",$lab['screen']).", and will be green if successful or red if not. An error detail can be viewed in such case.</TD></TR>
	</TABLE>",""),
"history"=>array("","<TABLE cellspacing=0 cellpadding=0 border=0><TR><TD>&nbsp;</TD></TR>
	<TR><TD class=jus colspan=2>This is the list of the history pages visited, starting from the most recent one:<BR><BR></TD></TR>
	<SCRIPT>wh();</SCRIPT>
	</TABLE>","
	function wh() { var i;
	for(i=0;i<parent.hhelp.length;i++) {
	document.write('<TR><TD class=pash>'+Number(i+1)+'</TD><TD class=jus><A href=\'javascript:parent.hleft.mhelp(\"'+parent.hhelp[i]+'\",false);\' onmouseover=\'window.status=\"\"; return true;\' onmouseout=\'window.status=\"\"; return true;\'>'+parent.hlab[parent.hhelp[i]]+'</A></TD></TR>');
	document.close();
	} }"),
"fin"=>"fin");
?>


