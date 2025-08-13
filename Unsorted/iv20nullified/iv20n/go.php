
<html>

<head>
<script LANGUAGE="JavaScript">
function fullScreen(theURL) {
window.open(theURL, '', 'fullscreen=no, scrollbars=no, width=350,height=400');
}
</script>
<script LANGUAGE="JavaScript">
function scrollScreen(theURL) {
window.open(theURL, '', 'fullscreen=no, scrollbars=yes, width=420,height=400');
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
<table border=0 cellpadding=2 cellspacing=2 width="760" align="center" bgcolor="#000000">
  <tr bgcolor="#FFFFFF"> 
    <td colspan="2">
      <div align="center"> 
        <p><? require ("config.php"); include ("./${$go}");?></p>
        </div>
     </tr>
</table>
<center>
  <table border="0" width="760" cellpadding="5" cellspacing="0">
    <tr>
      <td valign="center" align="center"> <br>
        Copyright &copy; 2001 <?=$sitename?> <br>
        <br>
      </td>
    </tr>
  </table>
  </center>
</body>
</html>
<? //  Image Vote(c) 2001 ProPHP.Com   ?>
