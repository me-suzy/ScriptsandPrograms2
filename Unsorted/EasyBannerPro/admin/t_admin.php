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

if ($HTTP_GET_VARS[action]=='do_daily_job') $s[no_mini_job] = 1;
include("./common.php");
switch ($HTTP_GET_VARS[action]) {
case 'backup_home'			: backup_home();
case 'email_members'		: email_members('');
case 'uninstall'			: uninstall(0);
case 'moderators_home'		: moderators_home();
case 'reset_rebuild_home'	: reset_rebuild_home();
case 'reset_all_question'	: reset_all_question();
case 'do_daily_job' 		: daily_job($s[cas],0); reset_rebuild_home();
case 'do_day_to_days_all' 	: day_to_days_all($s[cas],0,0); reset_rebuild_home();
case 'do_days_to_months_all': days_to_months_all($s[cas],0,0); reset_rebuild_home();
case 'do_delete_old' 		: delete_old($s[cas],0); reset_rebuild_home();
case 'do_release_deferred_impressions': release_deferred_impressions(0); reset_rebuild_home();
case 'do_sliding_ratio'		: update_sliding_ratios(0); reset_rebuild_home();
case 'delete_days'			: delete_days($HTTP_GET_VARS);
case 'delete_months'		: delete_months($HTTP_GET_VARS);
}
switch ($HTTP_POST_VARS[action]) {
case 'moderator_added'		: moderator_added($HTTP_POST_VARS);
case 'moderator_delete'		: moderator_delete($HTTP_POST_VARS);
case 'moderator_edit'		: moderator_edit($HTTP_POST_VARS);
case 'moderator_edited'		: moderator_edited($HTTP_POST_VARS);
case 'moderators_home'		: moderators_home();
case 'backup'				: backup($HTTP_POST_VARS);
case 'restore'				: restore($HTTP_POST_VARS);
case 'email_members'		: email_members($HTTP_POST_VARS);
case 'reset_all'			: reset_all();
case 'uninstall'			: uninstall(1);
}

#################################################################################
#################################################################################
#################################################################################

function reset_rebuild_home() {
global $s;
check_session ('reset');
include('./_head.txt');
$cas = datum(1,0);
$day = datum (1,$s[daily]);
if ($s[info]) echo '<span class="text13blue"><b>'.$s[info].'</b></span><br><br>';
echo iot('Reset/Rebuild Tool');
?>
<table width="600" border="0" cellspacing="10" cellpadding="2" class="table1">
<form method="GET" action="t_admin.php">
<tr><td align="center" nowrap colspan="2"><span class="text13">Current time <?PHP echo $cas; ?></span></td></tr>
<tr>
<td align="left" valign="top"><input type="radio" name="action" value="do_daily_job"></td>
<td align="left"><span class="text13">Daily Job (last done <?PHP echo $day; ?>)</span><br>
<span class="text10">It deletes all IP records used in the anti-cheat protection, 
converts daily portion of the 'Deferred free impressions' to normal free impressions, 
then it moves data from table 'day' to the table which is used for the day-by-day statistic (these data will be marked as data from yesterday), 
updates moth-by-month statistic, 
deletes unpaid sponsor orders older that 7 days and sponsors with no one order who joined more than 7 days ago.<br>
This job should run automatically once a day after midnight - you can read more in the Manual. You can run it manually by this function, however some statistical data regarding clicks/impressions in the day-by-day and month-by-month statistics may be damaged (it does not affect to the overall statistic, clicks/impressions balance etc).</span></td>
</tr>
<tr>
<td align="left" valign="top"><input type="radio" name="action" value="do_day_to_days_all"></td>
<td align="left"><span class="text13">Copy today's data to the day-by-day statistic</span><br>
<span class="text10">Part of the Daily Job</span></td>
</tr>
<tr>
<td align="left" valign="top"><input type="radio" name="action" value="do_days_to_months_all"></td>
<td align="left"><span class="text13">Copy data of the actual month to the month-by-month statistic</span><br>
<span class="text10">Part of the Daily Job</span></td>
</tr>
<tr>
<td align="left" valign="top"><input type="radio" name="action" value="do_delete_old"></td>
<td align="left"><span class="text13">Delete unpaid sponsored orders older than 7 days and sponsors with no one order who joined more than 7 days ago</span><br>
<span class="text10">Part of the Daily Job</span></td>
</tr>
<tr>
<td align="left" valign="top"><input type="radio" name="action" value="do_release_deferred_impressions"></td>
<td align="left"><span class="text13">Release one-day amount of deferred free impressions - make them available to use</span><br>
<span class="text10">Part of the Daily Job</span></td>
</tr>
<tr>
<td align="left" valign="top"><input type="radio" name="action" value="do_sliding_ratio"></td>
<td align="left"><span class="text13">Apply sliding ratio to accounts by ratios of the last month</span><br>
<span class="text10">It's normally done the first day of each month</span></td>
</tr>
<tr>
<td align="left" valign="top"><input type="radio" name="action" value="delete_days"></td>
<td align="left"><span class="text13"> Delete all daily statistics for 
<select name="d_month" class="field1"><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select> 
<input name="d_year" size="4" maxlength="4" class="field1" value="2003"> and before</span>
<br><span class="text10">You should delete this statistic regulary in order to avoid too big (=slow) database.</span>
</td></tr>
<tr>
<td align="left" valign="top"><input type="radio" name="action" value="delete_months"></td>
<td align="left"><span class="text13"> Delete all monthly statistics for 
<select name="m_month" class="field1"><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select> 
<input name="m_year" size="4" maxlength="4" class="field1" value="2003"> and before</span>
</td></tr>
<tr>
<td align="left" nowrap><input type="radio" name="action" value="reset_all_question"></td>
<td align="left" nowrap><span class="text13"> Reset all statistics to zero</span></td></tr>
<tr><td align="center" nowrap colspan="2">
<input type="submit" name="A1" value="Submit" class="button1"><br>
</td></tr></form></table><br>
<?PHP
include('./_footer.txt');
exit;
}

#################################################################################

function reset_all_question() {
global $s;
check_session ('reset');
include('./_head.txt');
echo '<br><span class="text13blue"><b>This function resets all stats to zero. Are you sure?</b></span>
<form action="t_admin.php" method="post">
<input type=hidden name="action" value="reset_all">
<input type=submit name="x" value="Yes, reset it" class="button1"></form>';
include('./_footer.txt');
exit;
}

#################################################################################

function reset_all() {
global $s;
check_session ('reset');
for ($x=1;$x<=3;$x++)
{ dq("update $s[pr]stats$x set i_free = 0, c_free = 0, i_refer = 0, i_w = 0, c_w = 0, c_m = 0, i_m = 0, earned = 0, i_nu = 0, c_nu = 0, forclicks = 0,i_move_in=0,i_move_out=0",0);
  dq("update $s[pr]b$x set i1=0,c1=0,res1=0,i2=0,c2=0,res2=0,i3=0,c3=0,res3=0",0);
  dq("delete from $s[pr]days$x",0);
  dq("delete from $s[pr]months$x",0);
}
dq("delete from $s[pr]wait_imp",0);
dq("update $s[pr]day set cl_m=0,cl_w=0,m0=0,m1=0,m2=0,m3=0,m4=0,m5=0,m6=0,m7=0,m8=0,m9=0,m10=0,m11=0,m12=0,m13=0,m14=0,m15=0,m16=0,m17=0,m18=0,m19=0,m20=0,m21=0,m22=0,m23=0,w0=0,w1=0,w2=0,w3=0,w4=0,w5=0,w6=0,w7=0,w8=0,w9=0,w10=0,w11=0,w12=0,w13=0,w14=0,w15=0,w16=0,w17=0,w18=0,w19=0,w20=0,w21=0,w22=0,w23=0",0);
include('./_head.txt');
echo '<br><br><span class="text13blue"><b>All stats have been reseted to zero.</b></span><br>';
include('./_footer.txt');
exit;
}

#################################################################################

function delete_days($data) {
global $s;
check_session ('reset');
if ( (!$data[d_month]) OR (!$data[d_year]) )
{ $s[info] = 'You must select both month and year'; reset_rebuild_home(); }
for ($x=1;$x<=3;$x++)
{ dq("delete from $s[pr]days$x where y<'$data[d_year]'",0);
  dq("delete from $s[pr]days$x where y='$data[d_year]' AND m<='$data[d_month]'",0);
}
$s[info] = 'Selected records have been deleted';
reset_rebuild_home();
}

#################################################################################

function delete_months($data) {
global $s;
check_session ('reset');
if ( (!$data[m_month]) OR (!$data[m_year]) )
{ $s[info] = 'You must select both month and year'; reset_rebuild_home(); }
for ($x=1;$x<=3;$x++)
{ dq("delete from $s[pr]months$x where y<'$data[m_year]'",0);
  dq("delete from $s[pr]months$x where y='$data[m_year]' AND m<='$data[m_month]'",0);
}
$s[info] = 'Selected records have been deleted';
reset_rebuild_home();
}

#################################################################################
#################################################################################
#################################################################################

function backup_home() {
global $s;
check_session ('backup');
include('./_head.txt');
echo $s[info];
echo iot('Database Backup Tool');
?>
<table border="0" width="500" cellspacing="10" cellpadding="2" class="table1">
<form method="POST" action="t_admin.php">
<tr><td align="center">
<table border="0" cellspacing="0" cellpadding="2" width="400">
<tr><td align="left" nowrap><span class="text13">
<input type="radio" value="backup" name="action"> Backup database to a file</td></tr>
<tr><td align="left" nowrap><span class="text13">
<input type="radio" value="restore" name="action"> Restore database from a file</td></tr>
<tr><td align="left" nowrap><span class="text13">
Name of backup file in your data directory: <input class="field1" name="backupfile" size="15" maxlength="15" value="backup.db"></td></tr>
<tr><td align="left" nowrap><span class="text13">
<input type="checkbox" name="dump"> Use mysqldump (recommended)</span></td></tr>
<tr><td align="center">
<input type="submit" name="A1" value="&nbsp;Submit&nbsp;" class="button1"><br><br>
<span class="text10">
Notes:<br>
The options above are not compatible. It means that if you create a backup without mysqldump, you can't restore it by mysqldump etc.<br>
Anyway we recommend to download and install <a class="link10" target=_new" href="http://www.phpmyadmin.net">PHPMyAdmin</a>. This is a free tool which allows you manage mysql database, it also allows you to create backups.
</span></td></tr></table></td></tr></form></table><br>
<?PHP
include('./_footer.txt');
exit;
}

#########################################################################

function backup($data) {
global $s;
check_session ('backup');
if ($data[dump])
system("$s[sqldump] -t -h$s[dbhost] -u$s[dbusername] -p$s[dbpassword] $s[dbname] >$s[phppath]/data/$data[backupfile]");
else
{ set_time_limit(1000);
  $q = mysql_list_tables($s[dbname]);
  while ($table = mysql_fetch_row($q))
  { if (eregi("^$s[pr].*",$table[0])) $tables[] = $table[0]; }
  foreach ($tables as $k => $v) @unlink("$s[phppath]/data/$v");
  $time1 = time();
  //echo ' Working, please wait ... '.str_repeat (' ',5000); flush();
  foreach ($tables as $k => $v)
  $q = dq("SELECT * INTO OUTFILE \"$s[phppath]/data/$v\" FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '`' LINES TERMINATED BY '~~~\n' FROM $v",1);
  $write = fopen("$s[phppath]/data/$data[backupfile]",'w');
  foreach ($tables as $k => $v)
  { $begin = "insert into $v values("; $end = ");--END--\n";
    $fd = fopen("$s[phppath]/data/$v","r");
    while (!feof($fd))
    { unset($line);
      for ($x=1;$x<=1000;$x++)
      { $y = trim(fgets($fd,10000)); if ($line) $line .= "\n$y"; else $line = $y;
        if ( (ereg('~~~',$line)) OR (!trim($line)) ) break;
      }
      $line = ereg_replace('[\]N','NULL',$line); 
      $line = ereg_replace('~~~','',$line);
      if (trim($line)) fwrite ($write,$begin.$line.$end);
      //if (time()>($time1+2)) { $time1=time(); echo ' Working ... '.str_repeat (' ',4000); flush(); }
    }
    fclose($fd);
  }
  fclose($write);
  foreach ($tables as $k => $v) unlink("$s[phppath]/data/$v");
}
@chmod("$s[phppath]/data/$data[backupfile]",0666);
include('./_head.txt');
echo iot('Finished!');
echo '<span class="text13">Your mysql database has been backed up to file '.$data[backupfile].' in your "data" directory.<br>';
if ($data[dump]) echo 'Please note that the backup will not be successful if you have not the correct path to mysqldump in your Configuration.<br>';
include('./_footer.txt');
exit;
}

#########################################################################

function restore($data) {
global $s;
check_session ('backup');
include('./_head.txt');
if ($data[dump])
system("$s[sqldump] -t -h$s[dbhost] -u$s[dbusername] -p$s[dbpassword] $s[dbname] <$s[phppath]/data/$data[backupfile]");
else
{ set_time_limit(1000);
  $time1 = time();
  echo '<span id="processing"><span class="text13blue">Working, please wait ... </span><br><span class="text10">'.str_repeat (' ',5000); flush();
  $q = mysql_list_tables($s[dbname]);
  while ($table = mysql_fetch_row($q))
  { if (eregi("^$s[pr].*",$table[0])) $tables[] = $table[0]; }
  foreach ($tables as $k => $v) dq("delete from $v",0);
  $fd = fopen("$s[phppath]/data/$data[backupfile]","r");
  while (!feof($fd))
  { unset($line);
    for ($x=1;$x<=1000;$x++)
    { $y = trim(fgets($fd,10000)); if ($line) $line .= "\n$y"; else $line = $y;
      if ( (ereg('--END--',$line)) OR (!trim($line)) ) break;
    }
    $line = ereg_replace("[\]",'',$line);
    $line = ereg_replace("'","\\'",$line);
    $line = ereg_replace('`',"'",$line);
    $line = ereg_replace('--END--','',$line);
    if (trim($line)) dq($line,1);
    if (time()>($time1+2)) { $time1=time(); echo ' Working ... '.str_repeat (' ',4000); flush(); }
  }
}
echo '</span></span><br><br>';
flush();
echo "<script>processing.style.display='none'</script>";
echo '<span class="text13">Your mysql database has been restored from file '.$data[backupfile].' in your "data" directory.<br>';
if ($data[dump]) echo 'Please note that the recovery will not be successful if you have not the correct path to mysqldump in your Configuration.<br>';
include('./_footer.txt');
exit;
}


#################################################################################
#################################################################################
#################################################################################

function uninstall($sure) {
global $s;
check_session ('config');
if (!$sure) {
include('./_head.txt');
echo '<br><span class="text13blue"><b>This function deletes all tables with name starting by \''.$s[pr].'\'<br>(it\'s prefix of tables which are used by Easy Banner Pro).<br>All data will be lost. This can\'t be undone.<br><br>Are you sure?</b></span>
<form action=t_admin.php method=post>
<input type=hidden name="action" value="uninstall">
<input type=submit name="x" value="Yes, I\'m sure" class="button1"></form>
<span class="text13">Once it deletes all the tables, you will not be able to use most functions from the menu on the left. If you will want to use the script furthermore, you will need to install it again.</span>';
include('./_footer.txt'); exit;
}
$q = mysql_list_tables($s[dbname]);
while ($table = mysql_fetch_row($q))
{ if (eregi("^$s[pr].*",$table[0])) { dq("drop table $table[0]",1); $x++; }
}
include('./_head.txt');
echo '<br><span class="text13blue"><b>Total of '.$x.' tables have been deleted</b></span><br><br>
<span class="text13">If you want to use the script furthermore, you will need to install it again.</span>';
include('./_footer.txt'); exit;
}

#################################################################################
#################################################################################
#################################################################################

function email_members($a) {
global $s;
check_session ('email_u');
if ((!$a[emailtext]) OR (!$a[emailsubject]))     
{ include('./_head.txt');
  if (($a[emailtext]) OR ($a[emailsubject]))
  echo iot('Both fields are required');
  echo iot('Email Your Members');
  ?>
  <table border="0" cellspacing="10" cellpadding="2" class="table1">
  <form action=t_admin.php method=post><input type="hidden" name="action" value="email_members">
  <tr><td align="center">
  <table border="0" cellspacing="0" cellpadding="2">
  <tr><td align="left"><span class="text13">
  <input type="radio" name="who" value="all" checked> Email all members (normal members and sponsors)</td></tr>
  <tr><td align="left"><span class="text13">
  <input type="radio" name="who" value="members"> Email normal members only</td></tr>
  <tr><td align="left"><span class="text13">
  <input type="radio" name="who" value="sponsors"> Email sponsors only</td></tr>
  <tr><td align="left" colpsan=3>
  <span class="text13">Subject: </span>
  <input class="field1" type="text" size="70" name="emailsubject" value="<?PHP echo $a[emailsubject]; ?>"></td></tr>
  <tr><td align="left" colpsan=3>
  <span class="text13">Text:</span><br>
  <textarea class="field1" cols=82 rows=15 name="emailtext"><?PHP echo $a[emailtext]; ?></textarea></td></tr>
  <tr><td align="center" colpsan=3><span class="text13">
  Email format: <input type="radio" name="htmlmail" value="0" checked> Text &nbsp;&nbsp;<input type="radio" name="htmlmail" value="1"> HTML</span></td></tr>
  <tr><td align="center" colpsan=3>
  <input type="submit" name="xx" value="Send mass email" class="button1">
  </td></tr></table></td></tr></form></table>
  <?PHP
  include('./_footer.txt'); 
}
else
{ foreach ($a as $k=>$v) $a[$k] = stripslashes($v);
  if ($a[htmlmail]) $htmlmail = "\nMime-Version: 1.0\nContent-Type: text/html; charset=\"ISO-8859-1\"\nContent-Transfer-Encoding: 8bit";
  if ($a[who]=='members') $where = "where not (sponsor='1')";
  elseif ($a[who]=='sponsors') $where = "where sponsor='1'";
  $emaily = dq("select email from $s[pr]members $where",0);
  while ($address = mysql_fetch_row($emaily))
  { $uspech = mail($address[0],$a[emailsubject],$a[emailtext],"From: $s[adminemail]$htmlmail");
    $seznam .= "<br>$address[0]\n";	}
  if ($uspech) { include('./_head.txt'); echo "<br><span class=\"text13blue\"><b>Mass email has been successfully sent to:</b></span>
  <br><span class=\"text13\">$seznam</span><br>";
  include('./_footer.txt'); }
  else problem("Can not send emails. Please contact server administrator for help.");
}
exit;
}

#################################################################################
#################################################################################
#################################################################################

function moderators_home() {
global $s;
check_session ('admins');

$s[moderators] = select_moderators();
include ('./_head.txt');
echo $s[info];
echo iot('Add/Edit/Delete Moderators');
?>
<table border=0 width="620" cellspacing=10 cellpadding=0 class="table1">
<TR><TD align="center">
<table border=0 cellspacing=2 cellpadding=0>
  <form action="t_admin.php" method="post" name="form1">
  <input type="hidden" name="action" value="moderator_added">
  <tr><td align="center" colspan=3><span class="text13blue"><b>Create a new moderator</b></span></td></tr>
  <tr><td align="left" nowrap><span class="text13">Username&nbsp;&nbsp;</span></td>
  <td align="left" colspan=2><input class="field1" size=15 name="username" maxlength=15></td></tr>
  <tr><td align="left" nowrap><span class="text13">Password&nbsp;&nbsp;</span></td>
  <td align="left" colspan=2><input class="field1" size=15 name="password" maxlength=15></td></tr>
  <tr><td align="left" nowrap><span class="text13">Email&nbsp;&nbsp;</span></td>
  <td align="left" colspan=2><input class="field1" size=50 name="email" maxlength=100></td></tr>
  <tr><td align="left" nowrap><span class="text13">Name&nbsp;&nbsp;</span></td>
  <td align="left" colspan=2><input class="field1" size=50 name="name" maxlength=50></td></tr>
  <tr><td align="left" valign="top" rowspan=12><span class="text13">Privilegies<br>This moderator<br>may view/edit<br>these items</span></td>
  <tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="users"><span class="text13">Edit/delete users/accounts</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="accounts"><span class="text13">View/enable/disable users/accounts</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="sponsors"><span class="text13">Edit/delete sponsored users/accounts, add/edit/delete sponsor packs</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="s_accounts"><span class="text13">View/enable/disable sponsored users/accounts</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="backup"><span class="text13">Backup/restore database</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="blacklist"><span class="text13">Blacklist</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="email_u"><span class="text13">Can email users</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="reset"><span class="text13">Reset statistic, run Daily Job</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="tmpl_msg"><span class="text13">Templates, messages, default ads</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="admins"><span class="text13">Administrators</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="config"><span class="text13">Configuration, unistallation</span></td>
  </tr>
  <tr><td align="center" colspan=3><input type="submit" name="submit" value="&nbsp;Submit&nbsp;" class="button1"></td></tr>
</form></table></td></tr></table>
<br><br><table border=0 width="620" cellspacing=10 cellpadding=0 class="table1">
<TR><TD align="center">
<table border=0 width=100% cellspacing=2 cellpadding=0>
  <form action="t_admin.php" method="post" name="form1">
  <input type="hidden" name="action" value="moderator_edit">
  <tr><td align="center"><span class="text13blue"><b>Edit/delete an existing moderator</b></span></td></tr>
  <tr><td align=center><select class="field1" name="moderator"><?PHP echo $s[moderators] ?></select></td></tr>
  <tr><td align="center"><span class="text13">Action: 
  <input type="radio" name="action" value="moderator_edit" checked>Edit&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="radio" name="action" value="moderator_delete">Delete</span></td></tr>
<tr><td align=center><input type="submit" name="submit" value="&nbsp;Submit&nbsp;" class="button1"></td></tr>
</form></table></td></tr></table>
<?PHP
include ('./_footer.txt');
exit;
}

#################################################################################

function moderator_added($form) {
global $s;
check_session ('admins');
if ( (!$form[username]) OR (!$form[password]) OR (!$form[email]) ) $problem[] = 'Some of required fields left blank.';
foreach ($form as $k => $v) { if ($v=='on') $form[$k] = 1; }
if ( ((strlen($form[username])) < 6) OR ((strlen($form[username])) > 15) ) $problem[] = 'Username must be 6-15 characters long.';
if ( ((strlen($form[password])) < 6) OR ((strlen($form[password])) > 15) ) $problem[] = 'Password must be 6-15 characters long.';
$form[name] = replace_once_text($form[name]);
if ($problem)
{ $s[info] = eot('One or more errors found. Please try again.',implode('<br>',$problem));
  moderators_home();
}
$form[password] = md5($form[password]);
dq("insert into $s[pr]moderators values (NULL,'$form[username]','$form[password]','$form[email]','$form[name]','0','$form[users]','$form[accounts]','$form[sponsors]','$form[s_accounts]','$form[backup]','$form[blacklist]','$form[email_u]','$form[reset]','$form[tmpl_msg]','$form[admins]','$form[config]','$form[x1]','$form[x2]','$form[x3]')",1);
$s[info] = iot('New moderator '.$form[username].' has been created.');
moderators_home();
exit;
}

#################################################################################

function moderator_edit($form) {
global $s;
check_session ('admins');
include ('./_head.txt');
$q = dq("select * from $s[pr]moderators where number='$form[moderator]'",1);
$user = mysql_fetch_array($q); //$user = strip_replace_array($user);
//$user = stripslashes($user);
echo $s[info];
echo iot('Edit moderator '.$user[username]);

echo "<table border=0 width=\"620\" cellspacing=10 cellpadding=0 class=\"table1\">
<TR><TD align=\"center\">
<table border=0 cellspacing=2 cellpadding=0>
  <form action=\"t_admin.php\" method=\"post\" name=\"form1\">
  <input type=\"hidden\" name=\"action\" value=\"moderator_edited\">
  <input type=\"hidden\" name=\"number\" value=\"$user[number]\">
  <input type=\"hidden\" name=\"username\" value=\"$user[username]\">
  <tr><td align=\"left\" nowrap><span class=\"text13\">Username&nbsp;&nbsp;</span></td>
  <td align=\"left\" colspan=2><span class=\"text13\">$user[username]</span></td></tr>
  <tr><td align=\"left\" nowrap><span class=\"text13\">Password&nbsp;&nbsp;</span></td>
  <td align=\"left\" colspan=2><input class=\"field1\" size=15 name=\"password\" maxlength=15> <span class=\"text10\">Leave it blank if you don't want to change it</span></td></tr>
  <tr><td align=\"left\" nowrap><span class=\"text13\">Email&nbsp;&nbsp;</span></td>
  <td align=\"left\" colspan=2><input class=\"field1\" size=50 name=\"email\" maxlength=100 value=\"$user[email]\"></td></tr>
  <tr><td align=\"left\" nowrap><span class=\"text13\">Name&nbsp;&nbsp;</span></td>
  <td align=\"left\" colspan=2><input class=\"field1\" size=50 name=\"name\" maxlength=50 value=\"$user[name]\"></td></tr>
  <tr><td align=\"left\" valign=\"top\" rowspan=12><span class=\"text13\">Privilegies<br>This moderator<br>may view/edit<br>these items</span></td>";
  echo '<tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="users"';
  if ($user[users]) echo ' checked'; echo '><span class="text13">Edit/delete users/accounts</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="accounts"';
  if ($user[accounts]) echo ' checked'; echo '><span class="text13">View/enable/disable users/accounts</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="sponsors"';
  if ($user[sponsors]) echo ' checked'; echo '><span class="text13">Edit/delete sponsored users/accounts, add/edit/delete sponsor packs</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="s_accounts"';
  if ($user[s_accounts]) echo ' checked'; echo '><span class="text13">View/enable/disable sponsored users/accounts</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="backup"';
  if ($user[backup]) echo ' checked'; echo '><span class="text13">Backup/restore database</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="blacklist"';
  if ($user[blacklist]) echo ' checked'; echo '><span class="text13">Blacklist</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="email_u"';
  if ($user[email_u]) echo ' checked'; echo '><span class="text13">Can email users</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="reset"';
  if ($user[reset]) echo ' checked'; echo '><span class="text13">Reset statistic</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="tmpl_msg"';
  if ($user[tmpl_msg]) echo ' checked'; echo '><span class="text13">Templates and messages</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="admins"';
  if ($user[admins]) echo ' checked'; echo '><span class="text13">Administrators</span></td>
  </tr><tr>
  <td align="left" nowrap><input type="checkbox" value="1" name="config"';
  if ($user[config]) echo ' checked'; echo '><span class="text13">Configuration</span></td>
  </tr>
  <tr><td align="center" colspan=3><input type="submit" name="submit" value="&nbsp;Submit&nbsp;" class="button1"></td></tr>
</form></table></td></tr></table><br>
<a href="t_admin.php?action=moderators_home">Back to previous page</a><br><br>';
include ('./_footer.txt');
exit;
}

#################################################################################

function moderator_edited($form) {
global $s;
check_session ('admins');
if (!$form[number]) problem("An error has occurred. Please select the moderator again.");
if (!$form[email]) $problem[] = 'Email left blank';
if ($form[password])
{ if ( ((strlen($form[password])) < 6) OR ((strlen($form[password])) > 15) ) $problem[] = 'Password must be 6-15 characters long.';
  $form[password] = md5($form[password]); 
  $password = " ,password='$form[password]'";
}
$form[name] = replace_once_text($form[name]);
if ($problem)
{ $s[info] = eot('One or more errors found. Please try again.',implode('<br>',$problem));
  $form[moderator] = $form[number]; moderator_edit($form);
}
dq("update $s[pr]moderators set email = '$form[email]' $password, name = '$form[name]', users = '$form[users]', accounts = '$form[accounts]', sponsors = '$form[sponsors]', s_accounts = '$form[s_accounts]', backup = '$form[backup]', blacklist = '$form[blacklist]', email_u = '$form[email_u]', reset = '$form[reset]', tmpl_msg = '$form[tmpl_msg]', admins = '$form[admins]', config = '$form[config]' where number = '$form[number]'",1);
$s[info] = iot('Moderator '.$form[username].' has been edited');
$form[moderator] = $form[number];
moderator_edit($form);
}

#################################################################################


function moderator_delete($form) {
global $s,$HTTP_SESSION_VARS;
check_session ('admins');

$q = dq("select username from $s[pr]moderators where number = $form[moderator]",1);
$user = mysql_fetch_row($q);
if (!$user[0]) problem("Moderator $user[0] does not exist.");
if ($HTTP_SESSION_VARS[admuser]==$user[0]) problem('You can not delete your account');

include ('./_head.txt');
echo $form[info];
if (!$form[ok])
{ echo "<br><table border=0 width=500 cellspacing=10 cellpadding=2 class=\"table1\">
  <form action=\"t_admin.php\" method=\"post\" name=\"form1\">
  <input type=\"hidden\" name=\"action\" value=\"moderator_delete\">
  <input type=\"hidden\" name=\"ok\" value=\"1\">
  <input type=\"hidden\" name=\"moderator\" value=\"$form[moderator]\">
  <tr><td align=\"center\" nowrap><span class=\"text13blue\"><font color=\"red\"><b>You are about to delete moderator $user[0]. Are you sure?</b></span></td></tr>
  <tr><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Yes, delete\" class=\"button1\"></td></tr>
  </form></table>";
  include ('./_footer.txt');
  exit;
}
dq("delete from $s[pr]moderators where number = $form[moderator]",1);
$s[info] = iot('Moderator '.$user[0].' has been deleted');
moderators_home();
exit;
}

#################################################################################

function select_moderators() {
global $s;
check_session ('admins');
$q = dq("select * from $s[pr]moderators order by username",1);
while ($a=mysql_fetch_array($q)) $x .= "<option value=\"$a[number]\">$a[username]</option>";
return $x;
}

#################################################################################
#################################################################################
#################################################################################

?>