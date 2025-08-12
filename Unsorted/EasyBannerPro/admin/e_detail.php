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


$s[SCRusers] = 'e_users.php'; $s[SCRdetail] = 'e_detail.php'; $s[sponsor] = 0;
include("./common.php");
include("$s[phppath]/admin/f_detail.php");

switch ($HTTP_POST_VARS[action]) {
case 'user_accept'			: user_accept($HTTP_POST_VARS);
case 'user_edit'			: user_edit($HTTP_POST_VARS[number]);
case 'user_edited'			: user_edited($HTTP_POST_VARS);
case 'user_delete'			: user_delete($HTTP_POST_VARS);
case 'ad_edited'			: ad_edited($HTTP_POST_VARS);
case 'account_enable'		: account_enable($HTTP_POST_VARS);
case 'account_disable'		: account_disable($HTTP_POST_VARS);
case 'save_numbers'			: save_numbers($HTTP_POST_VARS);
case 'save_wait_imp'		: save_wait_imp($HTTP_POST_VARS);
}
switch ($HTTP_GET_VARS[action]) {
case 'account_edit'			: account_edit($HTTP_GET_VARS);
case 'aff_credits_confer'	: aff_credits_confer($HTTP_GET_VARS);
case 'aff_credits_reject'	: aff_credits_reject($HTTP_GET_VARS);
}
if ((!$HTTP_GET_VARS[number]) AND ($HTTP_GET_VARS[username]))
{ $q = dq("select number from $s[pr]members where userid = '$HTTP_GET_VARS[username]'",1);
  $x = mysql_fetch_row($q); $HTTP_GET_VARS[number] = $x[0]; }
user_details($HTTP_GET_VARS[number]);

##########################################################################
##########################################################################
##########################################################################

function user_details($number) {
global $s;
check_session ('accounts');
$q = dq("select * from $s[pr]members where number = '$number'",1);
$user = mysql_fetch_assoc($q);
$free_cred_info = free_credits_info($user);
$accept = accept_user_info($number);

include('./_head.txt');
echo $s[info];
echo iot('User '.$user[userid].$accept.$free_cred_info);

user_details_table($number);
user_delete_table($number);

for ($x=1;$x<=3;$x++)
{ $s[use1] = 1; if (!$s["use$x"]) continue;
  $q = dq("select * from $s[pr]stats$x where number = '$number'",1);
  $stats = mysql_fetch_assoc($q);
  $q = dq("select * from $s[pr]link$x where number = '$number'",1);
  $link = mysql_fetch_assoc($q);

  $en_dis = enable_disable_info_button($number,$x,$stats);

  if (!$stats[weight]) $advantaged = 'No'; else $advantaged = $stats[weight];
  if ($stats[last]) $lastimpress = datum(1,$stats[last]); else $lastimpress = 'Never yet'; 
  $width = $s["w$x"]; $height = $s["h$x"];

  $y = list_category_categories($x,$stats); $category = $y[0]; $categories = $y[1];

  ?>
  <table border="0" width="500" cellspacing="2" cellpadding="4" class="table1"><tr><td align="center">
  <table border="0" width="480" cellspacing="0" cellpadding="2"><tr>
  <td colspan=2 align="center">
  <span class="text13blue"><b>Size <?PHP echo "$x (width $width px, height $height px)</b></span><br><span class=\"text13\">$en_dis[approved] $en_dis[enabled]"; ?></span></TD>
  </tr>
  <tr><td colspan=2 align="center">
  <table border=0 cellpadding=10 cellspacing=0 width="300"><tr>
  <?PHP echo $en_dis[button] ?>
  <form METHOD="get" action="<?PHP echo $s[SCRdetail] ?>">
  <input type="hidden" name="number" value="<?PHP echo $number; ?>">
  <input type="hidden" name="size" value="<?PHP echo $x; ?>">
  <input type="hidden" name="action" value="account_edit">
  <td align="center"><input type="submit" name="co" value="Edit this account" class="button1"></td></form>
  </tr></table>
  </td></tr>
  <tr>
  <td align="left" nowrap><span class="text13">Advantaged</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $advantaged; ?></a></span></td>
  </tr>
  <tr><td align="center" colspan="2" nowrap><span class="text10">If an account is advantaged, links of this account have priority.<br>Higher number = higher priority</span></td></tr>
  <tr>
  <td align="left" nowrap><span class="text13">Category</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $category; ?></span></span></td>
  </tr>
  <tr>
  <td align="left" nowrap valign="top"><span class="text13">Categories displayed on pages of this account</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $categories; ?></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Exchange ratio</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[exratio]*100 . "%"; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Impressions for click</span><br>
  <span class="text10">Number of impressions (if any) which gets this account<br>as a bonus for each click sent</span></td>
  <td align="left" valign="top" nowrap><span class="text13"><?PHP echo $stats[forclick]; ?></a></span></td>
  </tr>
  <tr><td align="center" nowrap colspan="2"><span class="text13blue"><b>Statistic</b></span></td></tr>
  <tr>
  <td align="left" nowrap><span class="text13">Not used impressions</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[i_nu]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Impressions sent by this account</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[i_m]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Credits earned</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[earned]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Clicks sent by this account</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[c_m]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Credits earned for clicks</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[forclicks]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Impressions moved from other sizes</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[i_move_in]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Impressions moved to other sizes</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[i_move_out]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Free credits total</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[i_free]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Free credits for referrals</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[i_refer]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Last impression sent</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $lastimpress; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Impressions received by this account</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[i_w]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Clicks received by this account</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[c_w]; ?></a></span></td>
  </tr>
  <tr><td colspan=2 align="center">
  <?PHP
  echo statistic_table_month($number,$x);
  get_three_ads($number,$x,$stats,$link,0);
  echo '</td></tr></table></td></tr></table><br>';
}
include('./_footer.txt');
exit;
}

#################################################################################
#################################################################################
#################################################################################

function account_edit($a) {
global $s;
check_session ('users');

$q = dq("select * from $s[pr]link$a[size] where number = '$a[number]'",1);
$link = mysql_fetch_assoc($q);
foreach ($link as $k => $v) $link[$k] = stripslashes($v);
for ($x=1;$x<=3;$x++) $link["raw$x"] = htmlspecialchars(unreplace_once_html($link["raw$x"]));

$q = dq("select * from $s[pr]stats$a[size] where number = '$a[number]'",1);
$stats = mysql_fetch_assoc($q);

$exratio = $stats[exratio]*100;
if ($stats[approved]) $i = '<font color="green">Enabled by admin</font>&nbsp;&nbsp;&nbsp;'; else $i = '<font color="red">Disabled by admin</font>&nbsp;&nbsp;&nbsp;';
if ($stats[enable]) $i .= '<font color="green">Enabled by user</font>'; else $i .= '<font color="red">Disabled by user</font>';

$stats[advantage] = '<SELECT class="field1" name="weight"><option value="0">No</option>';
for ($x=1;$x<=5;$x++)
{ if ($stats[weight]==$x) $selected=' selected'; else $selected='';
  $stats[advantage] .= "<option value=\"$x\"$selected>$x</option>\n";
}
$stats[advantage] .= '</select>';

$q = dq("select * from $s[pr]wait_imp where user = '$a[number]' and size = '$a[size]'",1);
$wait = mysql_fetch_assoc($q);
if (!$wait[rest]) $wait[rest] = 0; if (!$wait[daily]) $wait[daily] = 0;

include('./_head.txt');
echo $s[info];
echo iot('User '.$link[userid].' - Account '.$a[size].' </b>(width '.$s["w$a[size]"].'px, height '.$s["h$a[size]"].'px)</span><br><span class="text13">'.$i.'</span>');
?>
<table border="0" cellspacing="10" cellpadding="0" class="table1" width="580"><tr><td align="center">
<table border="0" cellspacing="2" cellpadding="4">
<form METHOD="post" action="e_detail.php">
<input type="hidden" name="number" value="<?PHP echo $a[number]; ?>">
<input type="hidden" name="size" value="<?PHP echo $a[size]; ?>">
<input type="hidden" name="action" value="save_numbers">
<tr>
<td align="left" nowrap><span class="text13">Exchange ratio</span></td>
<td align="left" nowrap><INPUT class="field1" size="3" maxlength="4" name="exratio" value="<?PHP echo $exratio; ?>"> %</td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Ban sliding ratio</span></td>
<td align="left" nowrap><INPUT type="checkbox" name="no_slide" value="1"<?PHP if ($stats[no_slide]) echo ' checked' ?>></td>
</tr>
<tr>
<td align="left"><span class="text13">Impressions for click<br></span><span class="text10">Set how many impressions (if any)<br>gets this account as a bonus for each click<br>on pages of this account</span></td>
<td align="left" nowrap><INPUT class="field1" size="3" maxlength="4" name="forclick" value="<?PHP echo $stats[forclick]; ?>"></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Not used impressions so far</span></td>
<td align="left"><span class="text13"><?PHP echo $stats[i_nu]; ?></span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Free impressions in all</span></td>
<td align="left"><span class="text13"><?PHP echo $stats[i_free]; ?></span></td>
</tr>
<tr>
<td align="left"><span class="text13">Add/take free impressions<br></span><span class="text10">Sample:<br>Write 100 to add 100 free impressions to this account<br>or -100 to take 100 impressions from this account</span></td>
<td align="left" nowrap><INPUT class="field1" size="7" maxlength="7" name="add_free" value="0"></td>
</tr>
<tr><td align="center" nowrap colspan="2">
<input type="submit" name="co" value="&nbsp;Save&nbsp;" class="button1"></td>
</td></tr></form></table>
<br>
<table border="0" cellspacing="2" cellpadding="4">
<form METHOD="post" action="e_detail.php">
<input type="hidden" name="number" value="<?PHP echo $a[number]; ?>">
<input type="hidden" name="size" value="<?PHP echo $a[size]; ?>">
<input type="hidden" name="action" value="save_wait_imp">
<tr>
<td align="center" colspan="2"><span class="text13blue">Deferred free impressions</span><br>
<span class="text10">This function allows you to add free impressions to any account but these impressions will not be available immediately but in steps. You set total number of free impressions and how many of them should be used each day. Example: You add 10,000 impressions and set that each day should be used 500 of them. Then it will take total of 20 days until all the impressions will be used.<br>
Note: The 'Daily Job' must be properly configured for this function.</span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Waiting deferred impressions</span></td>
<td align="left"><span class="text13"><?PHP echo $wait[rest]; ?></span></td>
</tr>
<tr>
<td align="left"><span class="text13">How many of them should be used daily</span></td>
<td align="left"><span class="text13"><?PHP echo $wait[daily]; ?></span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Add free impressions</span></td>
<td align="left"><INPUT class="field1" size="7" maxlength="7" name="add" value="<?PHP echo $wait[rest]; ?>"></td>
</tr>
<tr>
<td align="left"><span class="text13">Daily use</span></td>
<td align="left" nowrap><INPUT class="field1" size="7" maxlength="7" name="daily" value="<?PHP echo $wait[daily]; ?>"><span class="text13"> impressions</span></td>
</tr>
<tr>
<td align="center" colspan="2"><span class="text13">Once you hit the 'Save' button, all the waiting impressions will be deleted, only the just added impressions will be available.</span></td>
</tr>
<tr><td align="center" nowrap colspan="2">
<input type="submit" name="co" value="Save" class="button1"></td>
</td></tr></form></table>
</td></tr></table><br>
<?PHP
ad_edit_table($link,$stats,$a[size]);
echo "<a href=\"$s[SCRdetail]?number=$a[number]\">Back to previous screen</a><br><br>";
include('./_footer.txt');
exit;
}

#############################################################################
#############################################################################
#############################################################################

function save_numbers ($a) {
global $s;
check_session ('users');
$exratio = $a[exratio] / 100;
dq("update $s[pr]stats$a[size] set
i_free = i_free + '$a[add_free]', i_nu = i_nu + '$a[add_free]', exratio = '$exratio', forclick = '$a[forclick]', no_slide = '$a[no_slide]' 
where number = '$a[number]'",1);
$s[info] = iot('Data has been saved');
account_edit($a);
exit;
}

###########################################################################

function save_wait_imp ($a) {
global $s;
check_session ('users');
dq("delete from $s[pr]wait_imp where user = '$a[number]' and size = '$a[size]'",1);
dq("insert into $s[pr]wait_imp values ('$a[number]','$a[size]','$a[add]','$a[daily]')",1);
$s[info] = iot('Data has been saved');
account_edit($a);
exit;
}

#################################################################################
#################################################################################
#################################################################################

?>