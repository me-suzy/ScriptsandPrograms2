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

require_once ("config.php");
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
    <form method="post" action="go.php">
        
  <table border="0" width="500" vspace="0" hspace="0" cellspacing="4" cellpadding="0" class="blueboldtext">
    <tr> 
      <td colspan="2"> 
        <div align="justify"> 
          <p align="center"><b><br>
            Use this form to search all member profiles.</b> </p>
        </div>
      </td>
    </tr>
    <tr> 
      <td nowrap> 
        <div align="right">&nbsp;&nbsp;Search User Names</div>
      </td>
      <td> 
        <input type="text" name="searchname" size="20" maxlength="100">
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right">&nbsp;&nbsp;Gender</div>
      </td>
      <td> 
        <select name="searchsex">
          <option value="">Any Gender</option>
          <option value="men">Male</option>
          <option value="women">Female</option>
        </select>
      </td>
    </tr>
    <tr> 
      <td nowrap> 
        <div align="right">&nbsp;&nbsp;Age</div>
      </td>
      <td>Between 
        <input type="text" name="searchage1" size="2" maxlength="2" value="18">
        and 
        <input type="text" name="searchage2" size="2" maxlength="2" value="99">
      </td>
    </tr>
    <?
  foreach ( $extras as $marker ) {  // display extra fields
?> 
    <tr> 
      <td> 
        <div align="right">&nbsp;&nbsp;<? echo $extra[$marker][name]; ?></div>
      </td>
      <td> <? if (!is_array($extra[$marker][type])) { ?> 
        <input type="text" name="search<?=$marker?>" size="43" value="<? echo $extra[$marker][value]; ?>" maxlength="100">
        <? } else { 
	print "<select name=\"search$marker\">";
	print "<option value=\"\">Choose...</option>";
	while (list($key, $value) = each ($extra[$marker][type])) {
    print "<option value=\"$value\">$value</option>";
	}
	print "</select>";
    } 
    print "</td></tr>"; 
  }
print "  </select>";
?> </td>
    </tr>
    <tr> 
      <td nowrap> 
        <div align="right">&nbsp;&nbsp;Search Entire Profile</div>
      </td>
      <td> 
        <input type="text" name="searchentire" size="20" maxlength="100">
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right">&nbsp;&nbsp;Number of Results</div>
      </td>
      <td> 
        <select name="resultswanted">
          <option value="25" selected>25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right">&nbsp;&nbsp;Sort By</div>
      </td>
      <td> 
        <select name="searchsort">
          <option value="name">Alphabetical</option>
          <option value="new" selected>Newest Members First</option>
        </select>
      </td>
    </tr>
    <tr> 
      <td> 
        <div align="right">&nbsp;&nbsp;Only show users with pictures</div>
      </td>
      <td>
        <input type="radio" name="picturesonly" value="yes" checked>
        <input type="radio" name="picturesonly" value="no">
      </td>
    </tr>
    <tr> 
      <td>&nbsp; </td>
      <td> 
        <input type="hidden" name="go" value="searchresults">
        <input type="hidden" name="searchresults" value="searchresults.php">
        <input type="submit" name="submit" value="Search!">
      </td>
    </tr>
  </table>
      </form>
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