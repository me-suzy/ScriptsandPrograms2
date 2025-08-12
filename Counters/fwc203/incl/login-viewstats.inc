<?php
echo '<table align="right"><tr><td class="noboldtitle"><b>logged on as <i>'.$username.'</i></b></td></tr></table><h1>Statistics for <i>'.$siteid.'</i></h1><form method="post" action="login.php?sav=stats&sessionid='.$sessionid.'">
<table border="0" height="39" cellspacing="5">
  <tr>
    <td height="13">Counter location:</td>
    <td height="13"><b>'.$url.'</b></td>
  </tr>
</table>
<table border="1" align="right" cellspacing="0" cellpadding="1" bordercolor="#D3E93D">
  <tr>
    <td bgcolor="#D3E93D" class="noboldtitle"><b>Change site ID</b></td>
  </tr>
  <tr>
    <td bgcolor="#F5FAD4" align="right">
            <select name="siteid">';
      	for ($i = 1; $i <= $num_lstsiteids; ++$i) {
      		if (($lstsiteid[$i]) && ($lstsiteid[$i]!=$siteid) || ($num_lstsiteids==1)) {
     		echo "\n         <option>".$lstsiteid[$i]."</option>"; }
	}
echo '
        </select>&nbsp;'; if ($num_lstsiteids > 1) { echo '<input type="submit" class="button" value="View">'; } echo '
    </td>
  </tr>
</table>
<table border="0" height="39" cellspacing="5">
  <tr>
    <td height="13">Total Hits since <i>'.$created.'</i>:</td>
    <td height="13"><b>'.$count.'</b></td>
  </tr>
  <tr>
    <td height="13">Unique Hits Today:</td>
    <td height="13"><b>'.$uh.'</b></td>
  </tr>
  <tr>
    <td height="13">Total Hits Today:</td>
    <td height="13"><b>'.$th.'</b></td>
  </tr>
</table></form>
';

// open log file
$log_file = "countdb/".$siteid."-l.db";
$lf = fopen($log_file, "r"); 
$lfdata = fread($lf, filesize($log_file));
fclose ($lf);

// determine if there are enough stats to form a graph
$lfdata = explode("\n", $lfdata);
$num_lfdata = count($lfdata)-1;
if ($showgraph==1) {
	if ($num_lfdata >= 1) {
		$nostats = 0;
		// show graph
		echo '<div align="center"><img src="graph.php?sessionid='.$sessionid.'&siteid='.$siteid.'"></div><br>';
	} else {
		$nostats = 1;
		echo '<b>Oops... Not enough statistics to graph the last 7 days.</b><br>In order for your graph to display properly, check back tomorrow, after someone has accessed your counter.<br>';
	}
}
echo '<br><font size="3"><b>Daily Report&nbsp;<a href="login.php?act=resetstats&siteid='.$siteid.'&sessionid='.$sessionid.'">[Reset]</a></b></font><br>
<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#ffffff" bgcolor="#F5FAD4">
  <tr>
    <td width="33%" bgcolor="#D3E93D">&nbsp;</td>
    <td width="33%" bgcolor="#D3E93D"><b>Unique Hits</b></td>
    <td width="33%" bgcolor="#D3E93D"><b>Total Hits</b></td>
  </tr>
';
if ($nostats==1) { echo $yesterday; }

// print stats
for ($i = $a; $i >= $u; $i--) {
	$temp = explode("!", $lines[$i]);
	if ($temp[0]) {
		echo '  <tr><td width="33%" bgcolor="#D3E93D" align="center">'.$temp[1].'</td><td width="33%">'.$temp[0].'</td><td width="33%">'.$temp[2].'</td></tr>';
		echo "\n";
	}
}

// if there are no stats, just say no stats
if ($nostats==1) { echo '  <tr><td width="99%" colspan="3">No stats.</td></tr>'; }
echo '</table>
<p>[<a href="login.php?act=home&sessionid='.$sessionid.'">Back to Account Control Panel</a>] 
[<a href="index.php?p=help" target="_blank">Help!</a>] 
[<a href="getcount.php?id='.$siteid.'">View Counter in Action</a>] 
[<a href="login.php?act=logout&sessionid='.$sessionid.'">Logout</a>]</p>';
?>