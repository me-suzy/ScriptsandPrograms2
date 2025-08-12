<?php
require('./prepend.inc.php');

$eins=rand(1,99999);
$zwei=rand(2,66666);
$drei=rand(9,88888);

$rechnung = $eins + $zwei - $drei;
?>

<?
include("./templates/main-header.txt");
?>


<br><center><b>Bannereinblendungen in der Viewbar</b></center><br><form method="post" action="danke.php?rechnung=<? echo "$rechnung"; ?>"><TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0" width="95%" align="center">
<TR>
  <TD width="30%" bgcolor="#E4E4E4"><b>Name:</b></TD>
  <TD bgcolor="#E4E4E4"><center><input type="text" name="name" value="your name or company name" size="40"></center></TD>
</TR>
<TR>
  <TD width="30%" bgcolor="#E4E4E4"><b>e-mail:</b></TD>
  <TD bgcolor="#E4E4E4"><center><input type="text" name="email" value="your e-mail" size="40"></center></TD>
</TR>
<TR>
  <TD width="30%" bgcolor="#E4E4E4"><b>Choose package:</b></TD>
  <TD bgcolor="#E4E4E4"><center><select name="views" class="forms">
                                <option value="10000" selected>10.000 BannerViews - <? echo "$baa"; ?> &euro;&nbsp;</A></option>
                                <option value="50000" selected>50.000 BannerViews - <? echo "$bab"; ?> &euro;&nbsp;</A></option>
                                <option value="100000" selected>100.000 BannerViews - <? echo "$bac"; ?> &euro;&nbsp;</A></option></center></TD>
</TR>
<TR>
  <TD width="30%" bgcolor="#E4E4E4"><b>Banner URL:</b></TD>
  <TD bgcolor="#E4E4E4"><center><input type="text" name="source" value="http://www." size="40"></center></TD>
</TR>
<TR>
  <TD width="30%" bgcolor="#E4E4E4"><b>Target URL:</b></TD>
  <TD bgcolor="#E4E4E4"><center><input type="text" name="target" value="http://www." size="40"></center></TD>
</TR>
<TR>
  <TD width="30%" bgcolor="#E4E4E4"><b>Alt Text:</b></TD>
  <TD bgcolor="#E4E4E4"><center><input type="text" name="alt" value="Alt Text" size="40"></center></TD>
</TR>
</table><br><center><font face="Arial,helvetica"><input type="submit" name="next"
     value="Submit Order">&nbsp;<input type="reset" name="next" value="Reset"></font></form>


 <center><b>Visits in traffic exchange</b></center><br><form method="post" action="dankea.php?rechnung=<? echo "$rechnung"; ?>"><TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0" width="95%" align="center">
<TR>
  <TD width="30%" bgcolor="#E4E4E4"><b>Name:</b></TD>
  <TD bgcolor="#E4E4E4"><center><input type="text" name="name" value="your name or company name" size="40"></center></TD>
</TR>
<TR>
  <TD width="30%" bgcolor="#E4E4E4"><b>e-mail:</b></TD>
  <TD bgcolor="#E4E4E4"><center><input type="text" name="email" value="your e-mail" size="40"></center></TD>
</TR>
<TR>
  <TD width="30%" bgcolor="#E4E4E4"><b>Password:</b></TD>
  <TD bgcolor="#E4E4E4"><center><input type="password" name="password" value="" size="40"></center></TD>
</TR>
<TR>
  <TD width="30%" bgcolor="#E4E4E4"><b>Anzahl:</b></TD>
  <TD bgcolor="#E4E4E4"><center><select name="points" class="forms">
                                <option value="500" selected>500 visits - <? echo "$besa"; ?> &euro;&nbsp;</A></option>
                                <option value="1000" selected>1.000 visits - <? echo "$besb"; ?> &euro;&nbsp;</A></option>
                                <option value="5000" selected>5.000 visits - <? echo "$besc"; ?> &euro;&nbsp;</A></option>
                                <option value="10000" selected>10.000 visits - <? echo "$besd"; ?> &euro;&nbsp;</A></option>
                                <option value="50000" selected>50.000 visits - <? echo "$bese"; ?> &euro;&nbsp;</A></option>
                                <option value="100000" selected>100.000 visits - <? echo "$besf"; ?> &euro;&nbsp;</A></option></center></TD>
</TR>
<TR>
  <TD width="30%" bgcolor="#E4E4E4"><b>Target URL:</b></TD>
  <TD bgcolor="#E4E4E4"><center><input type="text" name="url" value="http://www." size="40"></center></TD>
</TR>
</table><br><center><font face="Arial,helvetica"><input type="submit" name="next"
     value="Submit Order">&nbsp;<input type="reset" name="next" value="Reset"></font></form>

<?
include("./templates/main-footer.txt");
?>