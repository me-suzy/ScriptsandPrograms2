/*
	
	Use this script however you want.  Improve upon it, slice it, dice it,
	whatever..., but try to leave the comments and credits intact.

PURPOSE:
	The purpose of this script is to add a date selector widget to an html page. 
	
	It works using the makeCalendar script, developed by A. Gunther. Actually it is a 
	rewriting of that script, so thanks A.

	You can configure many aspects of the widget's look and feel, by changing the
	values in the global variables.

USAGE:
	The script dateSelector.js must be included in the page.
	It allows you to call the following functions from the HTML code:
	
	- makeCurrentCalendar(formName, fieldName, winWidth, winHeight, winTop, winLeft)
		which opens a new window at pos winTop, winLeft, winWidth pixels wide and winHeight pixels high,
		shows the calendar of the current month, returning the current day in a different bgColor
		and allows you to start browsing the calendar.
		
	- initDateField(formName, fieldName, date)
		which can be called, even more than once, on the onLoad event of the <body> tag, 
		to initialize the values in the given fields with the given date.
		The parameter date is an object and now, minutesBefore() and the like can be used.

	- minutesBefore(mins, date)
		returns a Date object corresponding to min minutes before date

	- secondsBefore(secs, date)
		returns a Date object corresponding to secs seconds before date

	- minutesAfter(mins, date)
		returns a Date object corresponding to min minutes after date

	- secondsAfter(secs, date)
		returns a Date object corresponding to secs seconds after date

	Once the window is opened, it retains the focus, using an interesting technique, developed by Jim.
	
	A click on a day, will close the dateSelector window, returning the date in the form field, and 
	the default time, if that option was activated in the configuration section.
			
EXAMPLE:
	<html>
	<head>
	<title>Try the date selector</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script language='javascript' src='dateSelector.js'></script>
	</head>
	
	<body bgcolor="#FFFFFF" text="#000000" 
		onLoad="initDateField('myForm', 'txtStartDate', minutesBefore(60, now), true); initDateField('myForm', 'txtEndDate', now, false)">
	<p>&nbsp;</p>
	<form name="myForm" method="post" action="">
	  Start date 
	  <input type="text" name="txtStartDate">
	  (<a href="#" 
	  		onclick="makeCurrentCalendar('myForm', 'txtStartDate', 250, 216, 220, 200)"
	  		><font size="2">use selector</font></a> | 
	  	<a href="#" 
	  		onclick="initDateField('myForm', 'txtStartDate', now, true)"
	  		><font size="2">now</font></a>) 
	<br>
	  End date 
	  <input type="text" name="txtEndDate">
	  (<a href="#" 
	  		onclick="makeCurrentCalendar('myForm', 'txtEndDate', 250, 216, 260, 200)"
	  		><font size="2">use selector</font></a> | 
	  	<a href="#" 
	  		onclick="initDateField('myForm', 'txtEndDate', now, true)"
	  		><font size="2">now</font></a>) 
	
	<p>
		<input type="submit" name="btnSubmit">
		<input type="reset" name="btnReset">

	</p>
	</form>
	<p>&nbsp; </p>
	</body>
	</html>
	
	************************************************************************************
	
Lo script e' stato modificato in modo da tenere su due text area separate la data e l'ora.
Su makeCurrentCalendar se il penultimo parametro e' true anziche' l'ora verra' stampato MESE_ANNO
Su makeCurrentCalendar se l'ultimo parametro e' true nel campo ora verra' stampato 00:00, altrimenti 23:59

	<form name="myForm" method="post" action="">
	<table border="1" width=100% cellspacing="5">
	<tr>
	<td width="15%" align="right">
		Data Inizio
	</td>
	<td width=35%>
		<input type="text" name="txtStartDate">
	</td>
	<td rowspan="2">
		(<a href="#" onclick="makeCurrentCalendar('myForm', 'txtStartDate', 'hrStartDate', 290, 230, 220, 200)">
		<font size="2">use selector</font></a> | <a href="#" onclick="initDateField('myForm', 'txtStartDate', 'hrStartDate', now, true)">
		<font size="2">now</font></a>) 
	</td>
	</tr>
	<tr>
	<td width="15%" align="right">
		Ora Inizio
	</td>
	<td width=35%>
		<input type="text" name="hrStartDate">
	</td>
	</tr>
	<TR>
		<TD COLSPAN=3 align=center><input type="submit" value="submit"><input type="reset" value="reset"></td>
	</tr>				
	</table>
	
	</form>

*/

// global variables (configuration section)
var isResizable = "yes"
var bgDayColor = "white"  	  // normal day background
var bgTodayColor = "orange"	  // today background
var bgHeaderColor = "blue"	  // header background
var nonMonthDayColor = "gray"  // color of the days not in the current month
var headColor = "#ffffff"			// color of titles in the table
var fontSize = "9px"
var linkBgColor = "yellow"		  // color of the link background when the mouse passes over
var textColor = "navy"		  // color of the text
var dateSep = '-'
var timeSep = ':'
var defaultStartHours = '00'
var defaultStartMinutes = '00'
var defaultEndHours = '23'
var defaultEndMinutes = '59'
var timeFlag = true			      // show time info in the field
var timeSelection = true		  // select time after closing windows (only IE4+)


// global variables (no configuration)
var now = new Date()
var skipcycle = false
var winWidth
var winHeight
var winTop
var winLeft


function fcsOnMe(){
	if (!skipcycle){
		window.focus(); 
	}

	mytimer = setTimeout('fcsOnMe()', 500);
}


function makeCalendar(month, year, formName, fieldNameDate, fieldNameHours,htime, start)
{	
	var mnames = new Array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec')

	var monthName, sCal
	var wrkDate, firstDay, curDate, dayPtr

	today = now.getDate();
	thisMonth = now.getMonth();
	thisYear = now.getYear();
	
	// Get the name of the user-specified month (i.e. May for 4) 
	monthName = mnames[parseInt(month)]
	
	// Create a new Date object representing the first day of monthName in
	// year, since most likely a calendar would start on the first.  This
	// will be the "working date".
	wrkDate = new Date(monthName + ' 1, ' + year)

	// Set the "day pointer" to zero.  This variable will be used to keep
	// track of how many cells have been added to the calendar table.
	// dayPtr will be incremented whether a blank cell or a cell
	// containing a date is added.  If dayPtr is evenly divisible by 7
	// a new row (or week) is started.
	dayPtr = 0

	// Set the day of the month of the working date to the first.
	curDate = 1

	// Start creating the calendar table html.
	sCal = ''	
	sCal += '<table border="0" cellspacing="2" cellpadding="4">\n'
	sCal += '<tr>\n<td colspan="7" bgcolor="' + bgHeaderColor + '">\n'
	sCal += '<table border=0 cellspacing=0 cellpadding=0 width="100%"><tr>\n'
	sCal += '<th align="left">\n\t<font class="b">&lt;</font><a href="#" style="color: ' + headColor + '" onClick="makePrevMonth(' + month + ', ' + year +  ',\'' + formName + '\',\'' + fieldNameDate + '\',\'' + fieldNameHours + '\',\'' + htime + '\',\'' + start + '\'); return false;">prev</a>\n</th>\n'
	sCal += '<th><font class="b">' + monthName + ' ' + year + '</font></th>\n'
	sCal += '<th align="right">\n\t<a href="#" style="color: ' + headColor + '" onClick="makeNextMonth(' + month + ', ' + year + ',\'' + formName + '\',\'' + fieldNameDate + '\',\'' + fieldNameHours + '\',\'' + htime + '\',\'' + start + '\'); return false;">next</a><font class="b">&gt;</font>\n</th>\n'
	sCal += '</tr>\n'
	sCal += '</table></td></tr>\n'
	sCal += '<tr>\n<td bgcolor="' + bgDayColor + '">Sun</td>\n<td bgcolor="' + bgDayColor + '">Mon</td>\n<td bgcolor="' + bgDayColor + '">Tue</td>\n'
	sCal += '<td bgcolor="' + bgDayColor + '">Wed</td>\n<td bgcolor="' + bgDayColor + '">Thu</td>\n<td bgcolor="' + bgDayColor + '">Fri</td>\n<td bgcolor="' + bgDayColor + '">Sat</td>\n</tr>\n'
	sCal += '<tr valign="top" align="right">\n'

	// Get the day of the week (0=Sunday to 6=Saturday) 
	// that the first day of monthName falls on.
	firstDay = wrkDate.getDay()

	// If that day is 0 (Sunday) then make it 7 so that the
	// calendar will have a blank first week (for cosmetic reasons).
	if (!firstDay) firstDay = 7

	// Pad the calendar with blank cells until the first day is encountered.
	// Remember this will be 7 not 0 cells if the first day falls on a Sunday.
	for (var i = 0; i < firstDay; i++)
	{
		nDay = minutesBefore((firstDay-i)*24*60, wrkDate)
		//sCal += '<td bgcolor="' + bgDayColor + '"><a href="#" style="color: ' + nonMonthDayColor + '" onclick="setDateField(' +  nDay.getDate() + ', ' + nDay.getMonth() + ', ' + nDay.getYear() + ',\'' + formName + '\',\'' + fieldNameDate + '\',\'' + fieldNameHours + '\',\'' + htime + '\'); return false;">' + nDay.getDate() + '</a></td>\n'		
		sCal += '<td bgcolor="' + bgDayColor + '"><font style="color: ' + nonMonthDayColor + '">' + nDay.getDate() + '</td>\n'		
		dayPtr++
	}
	
	// The while loop condition will be true as long as the month represented
	// by the working date is the same as the original user-specifed month.
	while (wrkDate.getMonth() == month)
	{
		if (!(dayPtr++ % 7)) sCal += '\n<tr valign="top" align="right">\n'
		
		// Add a cell to the calendar table contaning the date.
		// It will be a number between 1 and 28, 29, 30, or 31.
		if (curDate==today && month==thisMonth && year==thisYear){
			sCal += '<td bgcolor="' + bgTodayColor + '"><a href="#" onclick="setDateField(' +  curDate + ', ' + month + ', ' + year + ',\'' + formName + '\',\'' + fieldNameDate + '\',\'' + fieldNameHours + '\',\'' + htime + '\',\'' + start + '\'); return false;">' + curDate + '</a></td>\n'
		} else {
			sCal += '<td bgcolor="' + bgDayColor + '"><a href="#" onclick="setDateField(' +  curDate + ', ' + month + ', ' + year + ',\'' + formName + '\',\'' + fieldNameDate + '\',\'' + fieldNameHours + '\',\'' + htime + '\',\'' + start + '\'); return false;">' + curDate + '</a></td>\n'
		}

		// Set the day of the month of the working date using the incremented
		// value of curDate.  This is where the the script automatically
		// handles leap years.  If the value of curDate is 28, it is then
		// incremented to 29, then the working date is set, and the while
		// condition is tested.  In the year 2000, wrkDate.getMonth() would
		// still return 1 (February), yet in the year 2001, the same statement
		// would return 2 (March).
		wrkDate.setDate(++curDate)
	}

	// Pad the calendar with blank cells until all 42 days (6 weeks) are filled.
	while (dayPtr < 42)
	{
		if (!(dayPtr++ % 7)) sCal += '\n<tr valign="top" align="right">'
		nDay = minutesAfter((dayPtr-firstDay-wrkDate.getDate())*24*60, wrkDate)
		//sCal += '<td bgcolor="' + bgDayColor + '"><a href="#" style="color: ' + nonMonthDayColor + '" onclick="setDateField(' +  nDay.getDate() + ', ' + nDay.getMonth() + ', ' + nDay.getYear() + ',\'' + formName + '\',\'' + fieldNameDate + '\',\'' + fieldNameHours + '\'); return false;">' + nDay.getDate() + '</a></td>\n'		
		sCal += '<td bgcolor="' + bgDayColor + '"><font style="color: ' + nonMonthDayColor + '">' + nDay.getDate() + '</td>\n'
	}

	// Finish off the calendar table html.
	sCal  += '\n</tr></table>\n'

	return sCal
}

// Some helper functions to create a popup calendar:

function popupCalendar(sCal)
{
	var sHTM = ''

	// Start creating the html for the complete page.
	sHTM += '<html>\n<head>\n<title>Date Selector</title>\n'
	sHTM += '<script language="javascript" src="dateSelector.js"></script>\n'
	sHTM += '<style type="text/css">\n'
	sHTM += ' <!--\n'
	sHTM += ' body {  font-family: "Courier New", Courier, mono; font-size: ' + fontSize + '; color: ' + textColor + '}\n'
	sHTM += ' a:hover {  text-decoration: underlin; background-color: ' + linkBgColor + '; color: ' + textColor + '}\n'
	sHTM += ' a {  text-decoration: none; font-weight: bold; color: ' +  textColor + '}\n'
	sHTM += ' a:visited {  font-weight: bold; color: ' +  textColor + '}\n'
	sHTM += '.b {color: ' + headColor + '}\n'
	sHTM += ' -->\n'
	sHTM += ' </style>\n'
	sHTM += '</head>\n'

	// Use a monospaced font (such a Courier) to make all the calendar table
	// cells the same width.  This is where css comes in handy.  Without css
	// you would have to add <font face="blah, blah"></font> to every cell.
	sHTM += '<body bgcolor="#ffffff" leftMargin=2 topMargin=2 onload = "mytimer = setTimeout(\'fcsOnMe()\', 500);">\n'
	sHTM += '<div align="center">\n'

	sHTM += '<table bgcolor="#273C81" border="0" cellpadding="0" cellspacing="0">\n'
	sHTM += '<tr><td width="100%">\n'

	// Add in the calendar html table
	sHTM += sCal
	sHTM += '\n</div>\n'
	sHTM += '</font>\n</body>\n</html>'

	sHTM += '</td></tr></table>\n\n'

	// Create a window and write the calendar html to it.
	var w = window.open('', 'dateSelector', 'width='+winWidth+',height='+winHeight+',resizable='+isResizable+',scrollbars=no,screenX='+winLeft+',screenY='+winTop+',top='+winTop+',left='+winLeft+'')
	w.document.write(sHTM)
	w.document.close()
}

function makeCurrentCalendar(_formName, _fieldNameDate, _fieldNameHours, _winWidth, _winHeight, _winTop, _winLeft, _htime, _start)
{	
	
	formName = _formName
	fieldNameDate = _fieldNameDate 
	fieldNameHours = _fieldNameHours	
	htime = _htime
	start = _start
	
	winWidth = _winWidth 
	winHeight =  _winHeight
	winTop = _winTop
	winLeft = _winLeft

	
	popupCalendar(makeCalendar(now.getMonth(), now.getFullYear(), formName, fieldNameDate, fieldNameHours, htime, start))

}

function setDateField(day, month, year, formName, fieldNameDate, fieldNameHours, htime, start)
{	
	
	
	
	realMonth = month+1;

	
	
	if( start == 'false' )
		timeInfo = defaultEndHours + timeSep + defaultEndMinutes
	else 
		timeInfo = defaultStartHours + timeSep + defaultStartMinutes
	
	if( day < 10 )
		day = '0' + day;
	if( realMonth < 10 )
		realMonth = '0' + realMonth;
		
	// set the new value of the field
	window.opener.document.forms[formName].elements[fieldNameDate].value=  year + dateSep + realMonth + dateSep + day;
	
		
	if( htime == 'true' ) {
		window.opener.document.forms[formName].elements[fieldNameHours].value=timeInfo;		
	}
	else {		
		
		var name = parseDate(month, year)
		window.opener.document.forms[formName].elements[fieldNameHours].value=name;
	}
	
	
	
	
	// give the focus back to the field
	//window.opener.document.forms[formName].elements[fieldNameHours].focus();

	// select the hours
	// it only works if timeFlag is on
	// (watch out! only for IE, control has to be added)
	/*if (timeFlag && timeSelection){
		tr = window.opener.document.forms[formName].elements[fieldNameHours].createTextRange();
		tr.moveStart('word', 5);
		tr.select();
	}*/
	
	close();
}


function parseDate(month, year) {
	
	
	var mesi = new Array('Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre');
	
	var monthName = mesi[parseInt(month)];
	
	var ret = monthName + '_' + year;
	return ret;
}

function initDateField(formName, fieldNameDate, fieldNameHours, date, focusFlag)
{
	day = date.getDate()
	month = date.getMonth() + 1
	year = date.getYear()
	hours = date.getHours()
	minutes = date.getMinutes()

	// the timeFlag is checked for time informations inserition
	if (timeFlag){
		if (hours < 10){
			hours = '0' + hours
		}
		if (minutes <10){
			minutes = '0' + minutes
		}
		timeInfo = '' + hours + timeSep + minutes
	} else {
		timeInfo = ''
	}
	
	if( day < 10 )
		day = '0' + day;
	if( month < 10 )
		month = '0' + month;
	
	if( fieldNameHours.length != 0 )
		document.forms[formName].elements[fieldNameHours].value=timeInfo;	
	document.forms[formName].elements[fieldNameDate].value= year + dateSep + month + dateSep + day;
	/*if (focusFlag){			
		document.forms[formName].elements[fieldNameHours].focus();
		// give the focus back to the field
		
	
		// select the hours
		// it only works if timeFlag is on
		// (watch out! only for IE, control has to be added)
		if (timeFlag && timeSelection){
			tr = document.forms[formName].elements[fieldNameHours].createTextRange();
			tr.moveStart('word', 5);
			tr.select();
		}

	}*/
}

function makePrevMonth(month, year, formName, fieldNameDate, fieldNameHours, htime, start)
{
	if (month == 0){
		year--
		month = 11
	} else {
		month --
	}

	popupCalendar(makeCalendar(month, year, formName, fieldNameDate, fieldNameHours, htime, start))
}

function makeNextMonth(month, year, formName, fieldNameDate, fieldNameHours, htime, start)
{
	if (month == 11){
		year++
		month = 0
	} else {
		month ++
	}

	popupCalendar(makeCalendar(month, year, formName, fieldNameDate, fieldNameHours,htime,start))

}

function minutesBefore(mins, date)
{
	millisecs = Date.parse(date.toString());
	return new Date(millisecs-(mins*60000));		
}

function secondsBefore(secs, date)
{
	millisecs = Date.parse(date.toString());
	return new Date(millisecs-(secs*1000));		
}

function minutesAfter(mins, date)
{
	millisecs = Date.parse(date.toString());
	return new Date(millisecs+(mins*60000));		
}

function secondsAfter(secs, date)
{
	millisecs = Date.parse(date.toString());
	return new Date(millisecs+(secs*1000));		
}
