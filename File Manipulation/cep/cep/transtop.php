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

if(isset($HTTP_GET_VARS["mkdir"])) {
$mkdir=strtolower($HTTP_GET_VARS["mkdir"]);
mkdir("langs/$mkdir",0777) or die("Error: mkdir langs/$mkdir");
}
?>

<HTML>
<HEAD>
<META NAME="Author" CONTENT="Carlos Guerlloy">
<META NAME="Description" CONTENT="cjgExplorer Pro v3.2">
<META http-equiv="Expires" content="MON, 4 JUL 1967 12:00:00 GMT">
<STYLE>
BODY,SELECT,INPUT { background-color:#CCCCCC; font-family:MS Sans Serif; font-size:14px; color:#000000; }
FORM { margin:0; }
</STYLE>
</HEAD>
<BODY onload='ft.submit();'>
TRANSLATION UTILITY<BR>
<FORM name=ft action="transleft.php" method=post target=tleft>
Language&nbsp;<SELECT name=lang onchange='ft.submit();'>
<?php
$dir=opendir("langs") or die("Can't open langs folder");
$a=0;
while($file=readdir($dir)) { if($file=="."||$file=="..") continue; $a=1;
echo("<OPTION value=$file>$file\n");
}
closedir($dir);
if(!$a) die("No subfolders under langs folder");
?>
</SELECT>
</FORM>
<FORM name=fn method=get>
Create language&nbsp;<INPUT type=text size=20 maxlength=15 name=mkdir>
</FORM>
</BODY>
</HTML>
