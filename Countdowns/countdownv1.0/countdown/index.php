<?php
include('countdown.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>PHP Countdown v<?php echo $version; ?></title>
<style type="text/css">
<!--
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: medium;
	color: #F0F0F0;
}
body {
	background-color: #FFFF99;
}
.box {
	border: 2px solid #000000;
	background-color: #FF9966;
	background-position: center;
	margin-left: auto;
	margin-right: auto;
	color: #FFFFFF;
}
a:link {
	color: #333333;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #333333;
}
a:hover {
	text-decoration: underline;
	color: #333333;
}
a:active {
	text-decoration: none;
	color: #333333;
}
.style1 {font-size: x-large}
.style2 {font-size: large}
-->
</style>
</head>

<body>
<div class="box" style="width:600px;">
  <div align="center">
    <p class="style1">PHP Countdown v<?php echo $version; ?></p>
    <p><br />
  &copy;2005 Nathan Bolender<br />
      <a href="http://www.nathanbolender.com">www.nathanbolender.com</a></p>
  </div>
</div>

<p>
<div class="box" style="width:500px;"><span class="style2">countdown( <em>int month</em>, <em>int day</em> [, <em>int year</em> [, <em>int hour</em> [, <em>int minute</em> [, <em>int second</em>]]]] ) </span><br /> 
  <br />
  This function will generate the information for your countdown and return it in an array like this:<br />
  <br />
array(<br />
<em>'years' =&gt; int years,<br />
'months' =&gt; int months,<br />
'days' =&gt; int days,<br />
'hours' =&gt; int hours,<br />
'minutes' =&gt; int minutes,<br />
'seconds' =&gt; int seconds,<br />
'now' =&gt; str now,<br />
'then' =&gt; str then,</em><br />
'passed' =&gt; int passed</em><br />
)<br />
<br />
This information can be formatted as you like, or you can use printcountdown() to display it. </div>
</p>

<p>
<div class="box" style="width:500px;"><span class="style2">printcountdown( <em>array countdown</em>, [, <em>int mode </em> [, <em>str name</em>]] ) </span><br /> 
  <br />
  This function will return a string containing your countdown from countdown() in a human-readable format. <em>Countdown</em> needs to be an array returned from countdown() or an array in the same format in order for this function to work.<br />
  <br />
  There are 4 possible modes for this function:<br />
  <strong>0 (default):</strong> Time until My Birthday! - 02:33:00 11/21/05: 6 hours, 28 minutes, and 36 seconds.<br />
  <strong>1:</strong> My Birthday! - 02:33:00 11/21/05: 6 hours, 28 minutes, and 36 seconds.<br />
  <strong>2:</strong> 6 hours, 28 minutes, and 36 seconds.<br />
<strong>3:</strong> 6 hours, 28 minutes, and 36 seconds</div>
</p>

<p>
<div class="box" style="width:500px;">
  <div align="center"><strong><span class="style1">Examples</span></strong><br />
    <br />
  </div>
  <?php
  echo printcountdown(countdown(11,25,YEAR,6,33), 0, 'My Birthday!').'<br /><br />';
echo printcountdown(countdown(11,28), 1, 'Kyle\'s Birthday').'<br /><br />';
echo printcountdown(countdown(12,25), 0, 'Christmas').'<br /><br />';
echo printcountdown(countdown(5,12,2008), 2, 'E3 2008');
  ?>
</div>
</p>
</body>
</html>
