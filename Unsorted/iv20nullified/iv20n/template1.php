<html>

<head>
<script LANGUAGE="JavaScript">
function fullScreen(theURL) {
window.open(theURL, '', 'fullscreen=no, scrollbars=no, width=350,height=420');
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
    <td colspan="3">&nbsp;
    </td>
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
        <div align="center">
<? include ('banner1.php'); ?>
        </div>
      </td>
    </tr>
  </table>
</center>

<table border=0 cellpadding=0 cellspacing=0 width="760" align="center">
    <tr bgcolor="#375288">
      <td>
        <table border=0 cellspacing=1 cellpadding=4 width="100%" align="center">
          <tr>
            <td valign="top" width="550" bgcolor="#f7f7f7">

            <p align="center"> <b>Rate this picture between 10 (best) and 1 (worst).</b>
              <br>
              <a href="<? print "$gophp?go=signupphp"?>">Submit Your Picture</a> | <a href="<? print "$gophp?go=faqphp"?>">FAQ</a>
              | <a href="<? print "$modphp"?>">Moderators</a></p>
<center>
<table border="0" width="100%">
<tr>
<td width="96" align="center" valign="top">
<form name="reportForm" method="POST" action="<?=$votephp?>">
<input type="hidden" name="rnum" value="<?=$rnum?>">
<input type="hidden" name="donerep" value="<?=$donerep?>">
<input type="hidden" name="member" value="<?=$newmember?>">
<input type="hidden" name="imgid" value="<?=$newid?>">
<input type="hidden" name="c" value="<?=$c?>">
<input type="hidden" name="vote" value="99">
</form>
<form method="POST" action="<?=$votephp?>">
<input type="hidden" name="image" value="<?=$newurl?>">
<input type="hidden" name="rnum" value="<?=$rnum?>">
<input type="hidden" name="donerep" value="<?=$donerep?>">
<input type="hidden" name="member" value="<?=$newmember?>">
<input type="hidden" name="imgid" value="<?=$newid?>">


                      <table border=0 cellpadding=2 cellspacing=0 bgcolor=#006699 background="bar.gif">
                        <tr>
                          <td nowrap valign=middle align=right><font color="#FFFFFF" size="1">
                            VOTE:<br>
                             &nbsp;&nbsp;&nbsp;10
                            <input type=radio name=vote value=10 onclick="this.form.submit()">
                            </font></td>
                        </tr>
                        <tr>
                          <td nowrap valign=middle align=right><font color="#FFFFFF" size="1">
                            9
                            <input type=radio name=vote value=9 onclick="this.form.submit()">
                            </font></td>
                        </tr>
                        <tr>
                          <td nowrap valign=middle align=right><font color="#FFFFFF" size="1">
                            8
                            <input type=radio name=vote value=8 onclick="this.form.submit()">
                            </font></td>
                        </tr>
                        <tr>
                          <td nowrap valign=middle align=right><font color="#FFFFFF" size="1">
                            7
                            <input type=radio name=vote value=7 onclick="this.form.submit()">
                            </font></td>
                        </tr>
                        <tr>
                          <td nowrap valign=middle align=right><font color="#FFFFFF" size="1">
                            6
                            <input type=radio name=vote value=6 onclick="this.form.submit()">
                            </font></td>
                        </tr>
                        <tr>
                          <td nowrap valign=middle align=right><font color="#FFFFFF" size="1">
                            5
                            <input type=radio name=vote value=5 onclick="this.form.submit()">
                            </font></td>
                        </tr>
                        <tr>
                          <td nowrap valign=middle align=right><font color="#FFFFFF" size="1">
                            4
                            <input type=radio name=vote value=4 onclick="this.form.submit()">
                            </font></td>
                        </tr>
                        <tr>
                          <td nowrap valign=middle align=right><font color="#FFFFFF" size="1">
                            3
                            <input type=radio name=vote value=3 onclick="this.form.submit()">
                            </font></td>
                        </tr>
                        <tr>
                          <td nowrap valign=middle align=right><font color="#FFFFFF" size="1">
                            2
                            <input type=radio name=vote value=2 onclick="this.form.submit()">
                            </font></td>
                        </tr>
                        <tr>
                          <td nowrap valign=middle align=right><font color="#FFFFFF" size="1">
                            1
                            <input type=radio name=vote value=1 onclick="this.form.submit()">
                            </font></td>
                        </tr>
                      </table>
                      <br>
                     <?=$pickcat?>
</form>
</td>
<td width="454" align="center" valign="top">
                    <p align="center">
					<a href="javascript:void(0);" onClick="scrollScreen('<?=$profilephp?>?u=<?=$newmember?>&id=<?=$newid?>');">My Profile</a>
					<a href="javascript:void(0);" onClick="fullScreen('<?=$mailphp?>?to=<?=$newmember?>');">Send Me A Message</a><br>
					<?=$newimage?><br><?=$otherpics;?><br><br>
                              <a href="javascript:void(0);" onClick="fullScreen('<?=$mailphp?>?imgid=<?=$newid?>&type=outer');">Send this picture to a friend</a><br></p>
                   <?=$samples?>
                    <p align="center">
					<? include ('banner1.php'); ?>
					</p>
                    <p align="left"><font size="1"><?=$sitename?> is designed
                      to be a fun way to show your picture and/or vote your opinion
                      of pictures submitted by users of this website. <a href="javascript:void(0);" onClick="fullScreen('<?=$reportphp?>?id=<?=$newid?>');">Click
                      here</a> to report to us if the picture above is broken,
                      copyrighted, or inappropriate (ads, models, nudity,
                      gross, joke, fake, etc.) </font></p>
</td></tr>
</table>
</center>
</td>
          <td valign="top" width="210" bgcolor="#e0e0e0" nowrap> <?=$loggedin?> <br>
<?=$lastpicture?>  <br>

            <p> <a href="<?=$gophp?>?go=topphp&c=<?=$c?>&amp;w=top">Top 10</a><br>
              <a href="<?=$gophp?>?go=topphp&c=<?=$c?>&amp;w=bottom">Bottom 10</a><br>
              <a href="<?=$gophp?>?go=newphp&c=<?=$c?>">Newest Pictures</a></p>
              <?=$loginbox?>

</td></tr>
</table>
</td></tr>
</table>

<center>
  <table border="0" width="760" cellpadding="5" cellspacing="0">
    <tr>
      <td valign="center" align="center"> Copyright &copy; 2001 <?=$sitename?><br>
        <br>
      </td>
    </tr>
  </table>
  </center>
</body>
</html>
