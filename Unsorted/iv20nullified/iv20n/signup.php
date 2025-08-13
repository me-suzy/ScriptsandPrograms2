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

if (!isset($go)) require('config.php');

langsignup();
langprofile();
languser();
langprocess();
langmod();

if (!isset($go)) {

?>


<html>
<head>

<title><?=$sitename?> - <? print SUBMITPIC; ?></title>
</head>
<STYLE>
A:visited 	{TEXT-DECORATION: underline}
A:hover 	{BACKGROUND-COLOR: #C7D8EA; COLOR: #110474}
A:link		{TEXT-DECORATION: underline}
A:active 	{TEXT-DECORATION: underline, overline}
BODY 		{CURSOR: default; FONT-FAMILY: MS Sans Serif, Tahoma, Verdana, Arial; FONT-SIZE: 9px}
UL		{CURSOR: default; FONT-FAMILY: MS Sans Serif, Tahoma, Verdana, Arial, sans-serif; FONT-SIZE: 10px}
LI 		{CURSOR: default; FONT-FAMILY: MS Sans Serif, Tahoma, Verdana, Arial, sans-serif; FONT-SIZE: 10px}
P		{CURSOR: default; FONT-FAMILY: MS Sans Serif, Tahoma, Verdana, Arial, sans-serif; FONT-SIZE: 10px}
TD 		{FONT-FAMILY: MS Sans Serif, Tahoma, Verdana, Arial, sans-serif; FONT-SIZE: 10px}
TR 		{FONT-FAMILY: MS Sans Serif, Tahoma, Verdana, Arial, sans-serif; FONT-SIZE: 10px}
</STYLE>
<body bgcolor="#ffffff" text="#000000" link="#006699" alink="#000000" vlink="#000000" marginheight="0" marginwidth="0" topmargin=0 leftmargin=0 rightmargin=0>
<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr bgcolor="#375288"> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
<center>
  <table border="0" width="760" cellpadding="5" cellspacing="0">
    <tr bgcolor="#ffffff"> 
      <td valign="bottom" align="left" width="203"> 
        <div align="left"><a href="<?=$siteurl?>"><img src="picturevoting.gif" height="60" width="187" border="0" alt="<?=$sitename?>"></a>
        </div>
      </td>
      <td align="right" nowrap valign="bottom" width="537">&nbsp; </td>
    </tr>
  </table>
</center>
<table border=0 cellpadding=0 cellspacing=0 width="760" align="center">
  <center>
    <tr bgcolor="#375288"> 
      <td> 
      <?}?>
        <table border=0 cellspacing=1 cellpadding=4 width="100%" align="center">
          <tr> 
            <td valign="top" width="550" bgcolor="#f7f7f7"> 
              <div align="center" class="topper"> 
                <p><a href="<?=$votephp?>"><? print ORGOVOTE;?></a><br>
                </p>
                <FORM METHOD="POST" ENCTYPE="multipart/form-data" ACTION="<?=$processphp?>">
                <INPUT TYPE="HIDDEN" NAME="MAX_FILE_SIZE" VALUE="<? print ($uploadsize * 1024);?>">
                   <div align="center">
                    <table border="0" width="80%">
                      <tr> 
                        <td colspan="2"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><big><b><? PRINT CREATEACCT;?></b></big></font></td>
                      </tr>
                      <tr> 
                        <td colspan="2" bgcolor="#375395"><font color="#FFFFFF" face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><? print ACCTINFO;?></font></td>
                      </tr>
                      <tr> 
                        <td width="33%"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><b><? print CHOOSEUSERNAME;?></b></font></td>
                        <td width="67%"> <font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"> 
                          <input type="text" name="username" size="20" maxlength="20">
                          <? print MAXCHAR; ?></font></td>
                      </tr>
                      <tr> 
                        <td width="33%"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><b><? print CHOOSEPASSWORD;?></b></font></td>
                        <td width="67%"> <font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"> 
                          <input type="text" name="password" size="20" maxlength="20">
                          <? print MAXCHAR; ?></font></td>
                      </tr>
                      <tr> 
                        <td width="33%"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><b><? print EMAIL;?></b><br>
                          <? print MUSTBEVALID; ?></font></td>
                        <td width="67%"> <font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"> 
                          <input type="text" name="email" size="43">
                          <br>
                          </font></td>
                      </tr>
                      <tr> 
                        <td colspan="2"> 
                          <hr>
                        </td>
                      </tr>
                      <tr> 
                        <td colspan="2" bgcolor="#375395"><font color="#FFFFFF" face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><? print IMGINFO;?></font></td>
                      </tr>
                      <?  if ($nopic == "yes" ){    ?> 
                      <tr> 
                        <td colspan="2"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><b><? print AREYOU;?></b>
                          <input type="radio" name="submitpic" value="yes" checked>
                          Yes 
                          <input type="radio" name="submitpic" value="no">
                          No </font></td>
                      </tr>
                      <?    } ?> 
                        <? if ($allowurl != "0") {?>
                              <tr> 
                        <td width="33%"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><b><? print IMGURL; ?></b></font></td>
                        <td width="67%"> <font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"> 
                          <input type="text" name="url" size="43" value="http://">
                          </font></td>
                      </tr>
                      <tr> 
                        <td colspan="2"> 
                          <p><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1">(ie: 
                            http://www.yourwebhost.com/yourdir/yourpic.jpg ) </font></p>
                        </td>
                      </tr>
                              <?    } ?>
                      <? if ($allowupload != "0") {?> 
                      <tr> 
                        <td width="33%"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><b><? print ORUPLOAD; ?></b></font></td>
                        <td width="67%"> <font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"> 
                          <input type="FILE" name="userpic" size="30">
                          </font></td>
                      </tr>
                      <tr> 
                        <td colspan="2"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><? print DESCRIBE; ?> </font></td>
                      </tr>
      <? }?>                 
      <tr> 
                        <td colspan="2"> <font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"> 
                          <input type="text" name="describe" size="59" value="<? print SAMPLEDES; ?>">
                          </font></td>
                      </tr>
                
                      <tr> 
                        <td colspan="2"> 
                          <hr>
                        </td>
                      </tr>
                      <tr> 
                        <td colspan="2" bgcolor="#375395"><font color="#FFFFFF" face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><? print PROFILEINFO;?></font></td>
                      </tr>
                      <tr> 
                        <td width="33%"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><b><? print AGE; ?>:</b></font></td>
                        <td width="67%"> 
                          <input type="text" name="age" size="5">
                          </td>
                      </tr>
                      <tr> 
                        <td width="33%"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><b><? print CATEGORY;?>:</b></font></td>
                        <td width="67%"> <font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"> 
                          <select name="category" size="1">
                            <? foreach ($categories as $a) print "<option value=\"$a\">$a</option>\n";      ?>
                          </select>
                          </font></td>
                      </tr>
 <?
  foreach ( $extras as $marker ) {  // display extra fields
 ?>
<tr>
	<td width="33%"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><? echo $extra[$marker][name]; ?>:</font></td>
    <td width="67%">
	<? if (!is_array($extra[$marker][type])) { ?>
    <input type="text" name="<?=$marker?>" size="43" value="<? echo $extra[$marker][value]; ?>" maxlength="100">
	<? } else { 
	print "<select name=\"$marker\">";
	while (list($key, $value) = each ($extra[$marker][type])) {
    print "<option value=\"$value\">$value</option>";
	}
	print "</select>";
    } 
    print "</td></tr>"; 
  }
?>


                      <tr> 
                        <td colspan="2"> 
                          <input type=checkbox name="notifypub" value="1" checked><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><b><? print NOTIFYPUB;?></b></font>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                          <input type=checkbox name="notifypriv" value="1" checked><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><b><? print NOTIFYPRIV;?></b></font>
                          </td>
                        </tr>
                      <tr> 
                        <td colspan="2"> 
                          <hr>
                        </td>
                      </tr>
                      <tr> 
                        <td colspan="2" bgcolor="#375395"><font color="#FFFFFF" face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><? print YOURRATE;?></font></td>
                      </tr>
                      <tr> 
                        <td colspan="2"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><? print WHATRATE; ?></font></td>
                      </tr>
                      <tr> 
                        <td colspan="2"> 
                          <div align="left"> 
                            <table border="0" width="96%" bgcolor="#000000">
                              <tr> 
                                <td width="100%"> 
                                  <table border="0" width="100%" cellspacing="0" cellpadding="0" height="20">
                                    <tr bgcolor="#CCCCFF"> 
                                      <td width="100%" height="20"> 
                                        <div align="center"> 
                                          <p>1 
                                            <input type="radio" value="1" name="self">
                                            2 
                                            <input type="radio" value="2" name="self">
                                            3 
                                            <input type="radio" value="3" name="self">
                                            4 
                                            <input type="radio" value="4" name="self">
                                            5 
                                            <input type="radio" value="5" name="self">
                                            6 
                                            <input type="radio" value="6" name="self">
                                            7 
                                            <input type="radio" value="7" name="self">
                                            8 
                                            <input type="radio" value="8" name="self">
                                            9 
                                            <input type="radio" value="9" name="self">
                                            10 
                                            <input type="radio" value="10" name="self">
                                        </div>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </div>
                        </td>
                      </tr>
                      <tr align="center"> 
                        <td colspan="2"> 
                          <hr>
                        </td>
                      </tr>
                      <tr align="center"> 
                        <td colspan="2"> <font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"> 
                          <input type="submit" value="<? print CREATEACCT; ?>">
                          </font></td>
                      </tr>
                      <tr align="center"> 
                        <td width="33%"></td>
                        <td width="67%"></td>
                      </tr>
                      <tr align="center"> 
                        <td colspan="2"> 
                          <hr>
                        </td>
                      </tr>
                    </table>
                  </div>
                </form>
              </div>
            </td>
            <td valign="top" width="210" bgcolor="#e0e0e0" nowrap> 
              <p><b><? print PICSREV;?></b></p>
              <div align="center" class="topper"> </div>
              <p align="left"><b><? print WHATNOT; ?></b></p>
              <p align="left"><? print WHATNOTA; ?></p>
              <p><b><? print WHATURL; ?></b></p>
             <? print WHATURLA; ?> <? print NOTCOMPAT; ?>
              <p><b><? print ANYQS; ?></b></p>
              <p><?print EMAILAT; ?><br>
                <a href="mailto:<?=$admin?>"><?=$admin?></a></p>
            </td>
          </tr>
        </table>
<? if (!$go) { ?>
      </td>
    </tr>
  </center></table>
<center>
  <table border="0" width="760" cellpadding="5" cellspacing="0">
    <tr bgcolor="#ffffff"> 
      <td valign="center" align="center">&copy; 2001 <?=$sitename?>
        <br>
        <br>
      </td>
    </tr>
  </table>
</center>
</body>
</html>
<?} //  Image Vote(c) 2001 ProPHP.Com   ?>
