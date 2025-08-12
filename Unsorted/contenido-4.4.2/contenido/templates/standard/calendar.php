<?php

include_once ('../../includes/config.php');

include_once ($cfg["path"]["contenido"].$cfg["path"]["includes"] . 'functions.i18n.php');

?>
<!--
Title: Tigra Calendar
URL: http://www.softcomplex.com/products/tigra_calendar/
Version: 3.2
Date: 10/14/2002 (mm/dd/yyyy)
Feedback: feedback@softcomplex.com (specify product title in the subject)
Note: Permission given to use this script in ANY kind of applications if
   header lines are left unchanged.
Note: Script consists of two files: calendar?.js and calendar.html
About us: Our company provides offshore IT consulting services.
    Contact us at sales@softcomplex.com if you have any programming task you
    want to be handled by professionals. Our typical hourly rate is $20.
-->
<html>
<head>
<title>Select Date, Please.</title>
<style>
	td {font-family: Tahoma, Verdana, sans-serif; font-size: 12px;}
</style>
<script language="JavaScript">

// months as they appear in the calendar's title
var ARR_MONTHS = ["<?php echo i18n("January"); ?>",
                  "<?php echo i18n("February"); ?>",
				  "<?php echo i18n("March"); ?>",
				  "<?php echo i18n("April"); ?>",
			      "<?php echo i18n("May"); ?>",
                  "<?php echo i18n("June"); ?>",
		          "<?php echo i18n("July"); ?>",
 				  "<?php echo i18n("August"); ?>",
				  "<?php echo i18n("September"); ?>",
				  "<?php echo i18n("October"); ?>",
				  "<?php echo i18n("November"); ?>",
				  "<?php echo i18n("December"); ?>"];
// week day titles as they appear on the calendar
var ARR_WEEKDAYS = ["<?php echo i18n("Su"); ?>",
					"<?php echo i18n("Mo"); ?>",
					"<?php echo i18n("Tu"); ?>",
					"<?php echo i18n("We"); ?>",
					"<?php echo i18n("Th"); ?>",
					"<?php echo i18n("Fr"); ?>",
					"<?php echo i18n("Sa"); ?>"];
// day week starts from (normally 0-Su or 1-Mo)
var NUM_WEEKSTART = 1;
// path to the directory where calendar images are stored. trailing slash req.
var STR_ICONPATH = '../../images/';

var re_url = new RegExp('datetime=(\\-?\\d+)');
var dt_current = (re_url.exec(String(window.location))
	? new Date(new Number(RegExp.$1)) : new Date());
var re_id = new RegExp('id=(\\d+)');
var num_id = (re_id.exec(String(window.location))
	? new Number(RegExp.$1) : 0);
var obj_caller = (window.opener ? window.opener.calendars[num_id] : null);

if (obj_caller && obj_caller.year_scroll) {
	// get same date in the previous year
	var dt_prev_year = new Date(dt_current);
	dt_prev_year.setFullYear(dt_prev_year.getFullYear() - 1);
	if (dt_prev_year.getDate() != dt_current.getDate())
		dt_prev_year.setDate(0);
	
	// get same date in the next year
	var dt_next_year = new Date(dt_current);
	dt_next_year.setFullYear(dt_next_year.getFullYear() + 1);
	if (dt_next_year.getDate() != dt_current.getDate())
		dt_next_year.setDate(0);
}

// get same date in the previous month
var dt_prev_month = new Date(dt_current);
dt_prev_month.setMonth(dt_prev_month.getMonth() - 1);
if (dt_prev_month.getDate() != dt_current.getDate())
	dt_prev_month.setDate(0);

// get same date in the next month
var dt_next_month = new Date(dt_current);
dt_next_month.setMonth(dt_next_month.getMonth() + 1);
if (dt_next_month.getDate() != dt_current.getDate())
	dt_next_month.setDate(0);

// get first day to display in the grid for current month
var dt_firstday = new Date(dt_current);
dt_firstday.setDate(1);
dt_firstday.setDate(1 - (7 + dt_firstday.getDay() - NUM_WEEKSTART) % 7);

// function passing selected date to calling window
function set_datetime(n_datetime, b_close) {
	if (!obj_caller) return;

	var dt_datetime = obj_caller.prs_time(
		(document.cal ? document.cal.time.value : ''),
		new Date(n_datetime)
	);

	if (!dt_datetime) return;
		obj_caller.target.value = (document.cal
			? obj_caller.gen_tsmp(dt_datetime)
			: obj_caller.gen_date(dt_datetime)
		);
	

	obj_caller.popup(dt_datetime.valueOf(),obj_caller.prefix,this);
}

function closeWindow (n_datetime)
{
	if (!obj_caller) return;

	var dt_datetime = obj_caller.prs_time(
		(document.cal ? document.cal.time.value : ''),
		new Date(n_datetime)
	);

	if (!dt_datetime) return;
		obj_caller.target.value = (document.cal
			? obj_caller.gen_tsmp(dt_datetime)
			: obj_caller.gen_date(dt_datetime)
		);
	
	window.close();
	
}

</script>
</head>
<body bgcolor="#FFFFFF" marginheight="5" marginwidth="5" topmargin="5" leftmargin="5" rightmargin="5">
<table class="clsOTable" cellspacing="0" border="0" width="100%">
<tr><td bgcolor="#A9AEC2">
<table cellspacing="1" cellpadding="3" border="0" width="100%">
<tr><td colspan="7"><table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<script language="JavaScript">
document.write(
'<td>'+(obj_caller&&obj_caller.year_scroll?'<a title="<?php echo i18n("Previous year"); ?>" alt="<?php echo i18n("Previous year"); ?>" href="javascript:set_datetime('+dt_prev_year.valueOf()+')"><img src="'+STR_ICONPATH+'year_prev.gif" border="0"></a>&nbsp;':'')+'<a title="<?php echo i18n("Previous month"); ?>" alt="<?php echo i18n("Previous month"); ?>" href="javascript:set_datetime('+dt_prev_month.valueOf()+')"><img src="'+STR_ICONPATH+'month_prev.gif" border="0"></a></td>'+
'<td align="center" width="100%"><font color="#ffffff">'+ARR_MONTHS[dt_current.getMonth()]+' '+dt_current.getFullYear() + '</font></td>'+
'<td><a alt="<?php echo i18n("Next month"); ?>" title="<?php echo i18n("Next month"); ?>" href="javascript:set_datetime('+dt_next_month.valueOf()+')"><img src="'+STR_ICONPATH+'month_next.gif"  border="0"></a>'+(obj_caller && obj_caller.year_scroll?'&nbsp;<a title="<?php echo i18n("Next year"); ?>" alt="<?php echo i18n("Next year"); ?>" href="javascript:set_datetime('+dt_next_year.valueOf()+')"><img src="'+STR_ICONPATH+'year_next.gif" border="0"></a>':'')+'</td>'
);
</script>
</tr>
</table></td></tr>
<tr>
<script language="JavaScript">

// print weekdays titles
for (var n=0; n<7; n++)
	document.write('<td bgcolor="#C6C6D5" align="center"><font color="#ffffff">'+ARR_WEEKDAYS[(NUM_WEEKSTART+n)%7]+'</font></td>');
document.write('</tr>');

// print calendar table
var dt_current_day = new Date(dt_firstday);
while (dt_current_day.getMonth() == dt_current.getMonth() ||
	dt_current_day.getMonth() == dt_firstday.getMonth()) {
	// print row heder
	document.write('<tr>');
	for (var n_current_wday=0; n_current_wday<7; n_current_wday++) {
		if (dt_current_day.getDate() == dt_current.getDate() &&
			dt_current_day.getMonth() == dt_current.getMonth())
			// print current date
			document.write('<td bgcolor="#A9AEC2" align="center" width="14%">');
		else if (dt_current_day.getDay() == 0 || dt_current_day.getDay() == 6)
			// weekend days
			document.write('<td bgcolor="#F4F4F7" align="center" width="14%">');
		else
			// print working days of current month
			document.write('<td bgcolor="#ffffff" align="center" width="14%">');

		document.write('<a href="javascript:set_datetime('+dt_current_day.valueOf() +', true);">');

		if (dt_current_day.getMonth() == this.dt_current.getMonth())
			// print days of current month
			document.write('<font color="#000000">');
		else 
			// print days of other months
			document.write('<font color="#606060">');
			
		document.write(dt_current_day.getDate()+'</font></a></td>');
		dt_current_day.setDate(dt_current_day.getDate()+1);
	}
	// print row footer
	document.write('</tr>');
}
if (obj_caller && obj_caller.time_comp)
	document.write('<form onsubmit="javascript:set_datetime('+dt_current.valueOf()+', true)" name="cal"><tr><td colspan="6" bgcolor="#C6C6D5"><font color="White" face="tahoma, verdana" size="2"><?php echo i18n("Time"); ?>: <input type="text" name="time" value="'+obj_caller.gen_time(this.dt_current)+'" size="8" maxlength="8"></font></td><td><a href="javascript:closeWindow('+dt_current.valueOf()+')"><img border="0" src="../../images/but_ok.gif"></a></td></tr></form>');
</script></table></tr></td></table></body>
</html>