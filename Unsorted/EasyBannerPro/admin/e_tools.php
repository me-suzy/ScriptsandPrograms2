<?PHP

#################################################
##                                             ##
##              Easy Banner Pro                ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                 Version 2.8                 ##
##             copyright (c) 2003              ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################


include("./common.php");
check_session ('config');

switch ($HTTP_GET_VARS[action]) {
case 'ratios_home'		: ratios_home();
}
switch ($HTTP_POST_VARS[action]) {
case 'ratios_save'	: ratios_save($HTTP_POST_VARS);
}

#########################################################################
#########################################################################
#########################################################################

function ratios_home() {
global $s;
$q = dq("select * from $s[pr]ratios order by min",1);
while ($x = mysql_fetch_assoc($q))
{ $f[$x[size]][] = $x[min]; $t[$x[size]][] = $x[max]; $r[$x[size]][] = $x[ratio]*100; }
include('./_head.txt');
echo $s[info];
echo iot('Sliding Ratios');
echo '<table border="0" width="600" cellspacing="4" cellpadding="2" class="table1"><tr>';
for ($x=1;$x<=3;$x++)
{ ?>
  <td align="center">
  <table border="0" width="100" cellspacing="0" cellpadding="2">
  <form action="e_tools.php" method="post">
  <input type="hidden" name="action" value="ratios_save">
  <input type="hidden" name="size" value="<?PHP echo $x ?>">
  <tr><td align="center" colspan="4"><span class="text13blue"><b>Ad size <?PHP echo $x ?></b></span></td></tr>
  <tr>
  <td align="center"><span class="text13">From</span></td>
  <td align="center"><span class="text13">To</span></td>
  <td align="center"><span class="text13">Ratio</span></td>
  </tr>
  <tr>
  <td align="center"><input class="field1" size="4" maxlength="4" name="f[0]" value="<?PHP echo $f[$x][0] ?>"></td>
  <td align="center"><input class="field1" size="4" maxlength="4" name="t[0]" value="<?PHP echo $t[$x][0] ?>"></td>
  <td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[0]" value="<?PHP echo $r[$x][0] ?>"></td>
  </tr>
  <tr>
  <td align="center"><input class="field1" size="4" maxlength="4" name="f[1]" value="<?PHP echo $f[$x][1] ?>"></td>
  <td align="center"><input class="field1" size="4" maxlength="4" name="t[1]" value="<?PHP echo $t[$x][1] ?>"></td>
  <td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[1]" value="<?PHP echo $r[$x][1] ?>"></td>
  </tr>
  <tr>
  <td align="center"><input class="field1" size="4" maxlength="4" name="f[2]" value="<?PHP echo $f[$x][2] ?>"></td>
  <td align="center"><input class="field1" size="4" maxlength="4" name="t[2]" value="<?PHP echo $t[$x][2] ?>"></td>
  <td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[2]" value="<?PHP echo $r[$x][2] ?>"></td>
  </tr>
  <tr>
  <td align="center"><input class="field1" size="4" maxlength="4" name="f[3]" value="<?PHP echo $f[$x][3] ?>"></td>
  <td align="center"><input class="field1" size="4" maxlength="4" name="t[3]" value="<?PHP echo $t[$x][3] ?>"></td>
  <td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[3]" value="<?PHP echo $r[$x][3] ?>"></td>
  </tr>
  <tr>
  <td align="center"><input class="field1" size="4" maxlength="4" name="f[4]" value="<?PHP echo $f[$x][4] ?>"></td>
  <td align="center"><input class="field1" size="4" maxlength="4" name="t[4]" value="<?PHP echo $t[$x][4] ?>"></td>
  <td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[4]" value="<?PHP echo $r[$x][4] ?>"></td>
  </tr>
  <tr>
  <td align="center"><input class="field1" size="4" maxlength="4" name="f[5]" value="<?PHP echo $f[$x][5] ?>"></td>
  <td align="center"><input class="field1" size="4" maxlength="4" name="t[5]" value="<?PHP echo $t[$x][5] ?>"></td>
  <td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[5]" value="<?PHP echo $r[$x][5] ?>"></td>
  </tr>
  <tr>
  <td align="center"><input class="field1" size="4" maxlength="4" name="f[6]" value="<?PHP echo $f[$x][6] ?>"></td>
  <td align="center"><input class="field1" size="4" maxlength="4" name="t[6]" value="<?PHP echo $t[$x][6] ?>"></td>
  <td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[6]" value="<?PHP echo $r[$x][6] ?>"></td>
  </tr>
  <tr>
  <td align="center"><input class="field1" size="4" maxlength="4" name="f[7]" value="<?PHP echo $f[$x][7] ?>"></td>
  <td align="center"><input class="field1" size="4" maxlength="4" name="t[7]" value="<?PHP echo $t[$x][7] ?>"></td>
  <td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[7]" value="<?PHP echo $r[$x][7] ?>"></td>
  </tr>
  <tr>
  <td align="center"><input class="field1" size="4" maxlength="4" name="f[8]" value="<?PHP echo $f[$x][8] ?>"></td>
  <td align="center"><input class="field1" size="4" maxlength="4" name="t[8]" value="<?PHP echo $t[$x][8] ?>"></td>
  <td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[8]" value="<?PHP echo $r[$x][8] ?>"></td>
  </tr>
  <tr>
  <td align="center"><input class="field1" size="4" maxlength="4" name="f[9]" value="<?PHP echo $f[$x][9] ?>"></td>
  <td align="center"><input class="field1" size="4" maxlength="4" name="t[9]" value="<?PHP echo $t[$x][9] ?>"></td>
  <td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[9]" value="<?PHP echo $r[$x][9] ?>"></td>
  </tr>
  <tr><td align="center" colspan="4"><input type="submit" name="A1" value="Save" class="button1"></td></tr>
  </form></table>
  </td>
  <?PHP
}
echo '</tr></table><br>';
echo iot('Info');
?>
<span class="text10">
<b>How it works</b><br>You can set 1-10 ratios for each ad size. It allows you to automatically give better ratio to users who have better ratio (clicks/impressions) on their pages. It may be useful to motivate users to place the exchange code on preferred places on their pages. At the begin of each month the system sets up new ratios for all users, these new ratios will be based on the numbers above.<br><br>
<b>Example</b><br>You set these ratios for size 1:<br>
From 0 to 0.5 ratio 52<br>
From 0.51 to 0.8 ratio 59<br>
It means that:<br>
Users who have ratio (clicks/impressions) on their pages less than 0.5% get 52 impressions of their ads for 100 impressions they send<br>
Users who have ratio (clicks/impressions) on their pages between 0.51% and 0.8% get 59 impressions of their ads for 100 impressions they send
<br><br>
<b>Notes</b><br>
Make sure to enable "Allow sliding ratios" in your Configuration if you want to use this feature.<br>
You must use Crontab or Task Manager to run the Daily Job. If you use the "Automatically Rebuild", don't use this feature.<br>
A sliding ratio will be applied only to accounts which sent 100 or more impressions in the last month.<br>
You can ban sliding ratios in setting of each account.<br>
<br>
<b>Sample - how this settings could look</b></span>
<table border="0" width="100" cellspacing="0" cellpadding="2">
<tr>
<td align="center"><span class="text13">From</span></td>
<td align="center"><span class="text13">To</span></td>
<td align="center"><span class="text13">Ratio</span></td>
</tr>
<tr>
<td align="center"><input class="field1" size="4" maxlength="4" name="f[0]" value="0.00"></td>
<td align="center"><input class="field1" size="4" maxlength="4" name="t[0]" value="0.20"></td>
<td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[0]" value="35"></td>
</tr>
<tr>
<td align="center"><input class="field1" size="4" maxlength="4" name="f[1]" value="0.21"></td>
<td align="center"><input class="field1" size="4" maxlength="4" name="t[1]" value="0.40"></td>
<td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[1]" value="42"></td>
</tr>
<tr>
<td align="center"><input class="field1" size="4" maxlength="4" name="f[2]" value="0.41"></td>
<td align="center"><input class="field1" size="4" maxlength="4" name="t[2]" value="0.60"></td>
<td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[2]" value="50"></td>
</tr>
<tr>
<td align="center"><input class="field1" size="4" maxlength="4" name="f[3]" value="0.61"></td>
<td align="center"><input class="field1" size="4" maxlength="4" name="t[3]" value="0.80"></td>
<td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[3]" value="55"></td>
</tr>
<tr>
<td align="center"><input class="field1" size="4" maxlength="4" name="f[4]" value="0.81"></td>
<td align="center"><input class="field1" size="4" maxlength="4" name="t[4]" value="1.00"></td>
<td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[4]" value="60"></td>
</tr>
<tr>
<td align="center"><input class="field1" size="4" maxlength="4" name="f[5]" value="1.01"></td>
<td align="center"><input class="field1" size="4" maxlength="4" name="t[5]" value="1.20"></td>
<td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[5]" value="65"></td>
</tr>
<tr>
<td align="center"><input class="field1" size="4" maxlength="4" name="f[6]" value="1.21"></td>
<td align="center"><input class="field1" size="4" maxlength="4" name="t[6]" value="1.40"></td>
<td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[6]" value="70"></td>
</tr>
<tr>
<td align="center"><input class="field1" size="4" maxlength="4" name="f[7]" value="1.41"></td>
<td align="center"><input class="field1" size="4" maxlength="4" name="t[7]" value="1.70"></td>
<td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[7]" value="75"></td>
</tr>
<tr>
<td align="center"><input class="field1" size="4" maxlength="4" name="f[8]" value="1.71"></td>
<td align="center"><input class="field1" size="4" maxlength="4" name="t[8]" value="2.00"></td>
<td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[8]" value="80"></td>
</tr>
<tr>
<td align="center"><input class="field1" size="4" maxlength="4" name="f[9]" value="2.01"></td>
<td align="center"><input class="field1" size="4" maxlength="4" name="t[9]" value="100"></td>
<td align="center" nowrap><input class="field1" size="4" maxlength="4" name="r[9]" value="90"></td>
</tr>
</table>
<?PHP
include('./_footer.txt');
exit;
}

#########################################################################

function ratios_save($data) {
global $s;
dq("delete from $s[pr]ratios where size = '$data[size]'",1);
for ($x=0;$x<=9;$x++)
{ $ratio = $data[r][$x]/100; $min = $data[f][$x]; $max = $data[t][$x];
  if (!$ratio) continue;
  dq("insert into $s[pr]ratios values('$data[size]','$min','$max','$ratio')",1);
}
$s[info] = iot('Sliding ratios have been updated');
ratios_home();
}

#########################################################################

?>