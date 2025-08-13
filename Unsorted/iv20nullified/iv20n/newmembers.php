<?
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Image Vote - Photo Rating System                  //
// Release Version      : 2.0.0                                             //
// Program Author       : Ronald James                                      //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////

if (!isset($go)) require ("config.php");

$originalc=$c;
// $u (user)  $c (category)  $f (function) $id (img id #)  $w top or bottom
if(!isset($c)) $c = "all";

$lastpicture="";

// connect to the database until the end
mysql_connect($host,$user,$pass);
@mysql_select_db($database) or die( "Unable to select database");


if ($c == "all") {
if ($validate="yes") $addval = "where validate = 'ok'";
$result=mysql_query("SELECT * FROM $usertable $addval order by joindate DESC LIMIT 30") or die(mysql_error());
}
else {
$result=mysql_query("SELECT * FROM $usertable WHERE category = '$c' and status = 'active' order by joindate DESC LIMIT 30") or die(mysql_error());
}




// done with database?  better close it
mysql_close ();

?>
<? if (!isset($go)) {?>
<html>

<head>
<script LANGUAGE="JavaScript">
function fullScreen(theURL) {
window.open(theURL, '', 'fullscreen=no, scrollbars=no, width=350,height=400');
}
</script>
<title><?=$sitename?></title>
<STYLE type=text/css>

A:visited 	{TEXT-DECORATION: underline}
A:hover 	{BACKGROUND-COLOR: #C7D8EA; COLOR: #110474}
A:link		{TEXT-DECORATION: underline}
A:active 	{TEXT-DECORATION: none}
BODY 		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
UL		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
LI 		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
P		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
TD 		{FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
TR 		{FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
TEXTAREA	{BACKGROUND-COLOR: #C7D8EA; COLOR: #110474; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 10px}
FORM 		{FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 10px}
</STYLE>
</head>

<body bgcolor="#ffffff" text="#000000" link="#006699" alink="#000000" vlink="#000000" marginheight="0" marginwidth="0" topmargin=0 leftmargin=0 rightmargin=0>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr bgcolor="#375288">
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
<center>
  <table border="0" width="760" cellpadding="5" cellspacing="0">
    <tr bgcolor="#ffffff">
      <td valign="bottom" align="left" width="203">
        <div align="left"><a href="<?=$siteurl?>"><img src="picturevoting.gif" height="60" width="161" border="0" alt="Vote your opinion about pictures at <?=$sitename?>"></a> 
        </div>
      </td>
      <td align="right" nowrap valign="bottom" width="537">
        <div align="center"> </div>
      </td>
    </tr>
  </table>
</center>
<table border=0 cellpadding=2 cellspacing=2 width="760" align="center" bgcolor="#375288">
  <tr bgcolor="#FFFFFF"> 
    <td colspan="2">
<? } ?>
      <div align="center"> 
        <p><b> </b><br>
          Newest Additions</p>
        <table width="600" border="1" cellspacing="0" cellpadding="0">
          <tr bgcolor="#000066"> 
            <td width="110">
              <div align="left"><font color="#FFFFFF">Name</font></div>
            </td>
            <td width="103">
              <div align="left"><font color="#FFFFFF">Category</font></div>
            </td>

            <td width="225">
              <div align="left"><font color="#FFFFFF">Description</font></div>
            </td>
          
          </tr>
<? $bgcolor = "#E6E6E6"; while ($resultz = mysql_fetch_array($result)) { ?>
          <tr bgcolor="<?=$bgcolor?>">
            <td width="110" valign="top"> <a href="<?=$votephp?>?who=<?=$resultz[name]?>"><?=$resultz[name]?></a>
              <div align="left"></div>
            </td>
            <td width="103" valign="top">
              <div align="left"><?=$resultz[category]?></div>
            </td>
                     <td width="225" valign="top">
              <div align="left"><?=$resultz[info2]?></div>
            </td>
            </tr>
 <?if ($bgcolor=="#E6E6E6") $bgcolor = "#F3F3F3"; else $bgcolor="#E6E6E6";}?>
          

        </table>
        <p>&nbsp; </p>
        <br>
        <a href="<?=$votephp?>"> </a> 
        <p> <br>
        </p>
        </div>
<? if (!$go) {?>
	</td>
     </tr>
</table>
<center>
  <table border="0" width="760" cellpadding="5" cellspacing="0">
    <tr>
      <td valign="center" align="center"> <br>
        &copy; 2001 <?=$sitename?> <br>
        <br>
      </td>
    </tr>
  </table>
  </center>
</body>
</html>
<? } //  Image Vote(c) 2001 ProPHP.Com   ?>
