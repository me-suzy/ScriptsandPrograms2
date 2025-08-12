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

loadstrings("ftypes.php");
define("DESC",0);
define("ICON",1);
define("DEFMODE",2);
define("OPENABLE",3);

define("OPENMODE",0);
define("TEXTMODE",1);
define("HEXMODE",2);
define("DIRMODE",3);
define("NOTOPEN",0);
define("YESOPEN",1);

$wsize=array(TEXTMODE=>16384,HEXMODE=>4096);

$ass=array(
    "dot"=>array(T("Folder"),"dotdot.gif",OPENMODE,NOTOPEN),
    "dir"=>array(T("Folder"),"dir.gif",OPENMODE,NOTOPEN),
    "file"=>array(T("File"),"file.gif",HEXMODE,NOTOPEN),
    ".txt"=>array(T("Text"),"txt.gif",OPENMODE,YESOPEN),
    ".doc"=>array(T("Word document"),"doc.gif",OPENMODE,YESOPEN),
    ".htm"=>array(T("Hipertext"),"html.gif",TEXTMODE,YESOPEN),
    ".html"=>array(T("Hipertext"),"html.gif",TEXTMODE,YESOPEN),
    ".gif"=>array(T("Gif graphic"),"gif.gif",OPENMODE,YESOPEN),
    ".jpg"=>array(T("Jpg graphic"),"jpg.gif",OPENMODE,YESOPEN),
    ".jpeg"=>array(T("Jpeg graphic"),"jpg.gif",OPENMODE,YESOPEN),
    ".png"=>array(T("Png graphic"),"png.gif",OPENMODE,YESOPEN),
    ".bmp"=>array(T("Bmp graphic"),"bmp.gif",OPENMODE,YESOPEN),
    ".mdb"=>array(T("MsAccess database"),"mdb.gif",HEXMODE,NOTOPEN),
    ".xls"=>array(T("MsExcel worksheet"),"xls.gif",OPENMODE,NOTOPEN),
    ".pps"=>array(T("MsPowerPoint presentation"),"pws.gif",OPENMODE,YESOPEN),
    ".ppt"=>array(T("MsPowerPoint presentation"),"pws.gif",OPENMODE,YESOPEN),
    ".asp"=>array(T("Active Server Page"),"asp.gif",TEXTMODE,NOTOPEN),
    ".php"=>array(T("Php script"),"php.gif",TEXTMODE,NOTOPEN),
    ".php2"=>array(T("Php script"),"php.gif",TEXTMODE,NOTOPEN),
    ".php3"=>array(T("Php script"),"php.gif",TEXTMODE,NOTOPEN),
    ".php4"=>array(T("Php script"),"php.gif",TEXTMODE,NOTOPEN),
    ".mp3"=>array(T("Mp3 sound file"),"mp3.gif",HEXMODE,NOTOPEN),
    ".pdf"=>array(T("Pdf file"),"pdf.gif",OPENMODE,YESOPEN),
    ".zip"=>array(T("Compressed archive"),"zip.gif",HEXMODE,NOTOPEN),
    ".z"=>array(T("Compressed archive"),"zip.gif",HEXMODE,NOTOPEN),
    ".Z"=>array(T("Compressed archive"),"zip.gif",HEXMODE,NOTOPEN),
    ".tar"=>array(T("Tar archive"),"zip.gif",HEXMODE,NOTOPEN),
    ".tgz"=>array(T("Compressed Tar archive"),"zip.gif",HEXMODE,NOTOPEN),
    ".rar"=>array(T("Compressed archive"),"zip.gif",HEXMODE,NOTOPEN),
    ".exe"=>array(T("Windows executable"),"exe.gif",HEXMODE,NOTOPEN),
    ".com"=>array(T("Windows executable"),"exe.gif",HEXMODE,NOTOPEN),
    ".js"=>array(T("Javascript text"),"txt.gif",TEXTMODE,NOTOPEN),
    ".css"=>array(T("Stylesheet text"),"txt.gif",TEXTMODE,NOTOPEN)
       );
?>
