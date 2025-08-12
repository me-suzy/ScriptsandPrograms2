<?php
if(!file_exists("data.inc.php")) { 
	echo "<font face=\"Verdana\" size=\"2\">You have to run the setup ( <a href=\"setup.php\">Setup</a> ) file before using My DataBook.</font>";
	exit;
}

if(!isset($Sec)) {
	header("location: index.php?Sec=home");
}

include "data.inc.php";

$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
mysql_select_db($DB_name);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>My DataBook Version 1.0</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
function move(fbox, tbox) {
	var arrFbox = new Array();
	var arrTbox = new Array();
	var arrLookup = new Array();
	var i;
	for(i=0; i<tbox.options.length; i++) {
		arrLookup[tbox.options[i].text] = tbox.options[i].value;
		arrTbox[i] = tbox.options[i].text;
	}
	var fLength = 0;
	var tLength = arrTbox.length
	for(i=0; i<fbox.options.length; i++) {
		arrLookup[fbox.options[i].text] = fbox.options[i].value;
		if(fbox.options[i].selected && fbox.options[i].value != "") {
			arrTbox[tLength] = fbox.options[i].text;
			tLength++;
		} else {
			arrFbox[fLength] = fbox.options[i].text;
			fLength++;
		}
	}
	arrFbox.sort();
	arrTbox.sort();
	fbox.length = 0;
	tbox.length = 0;
	var c;
	for(c=0; c<arrFbox.length; c++) {
		var no = new Option();
		no.value = arrLookup[arrFbox[c]];
		no.text = arrFbox[c];
		fbox[c] = no;
	}
	for(c=0; c<arrTbox.length; c++) {
		var no = new Option();
		no.value = arrLookup[arrTbox[c]];
		no.text = arrTbox[c];
		tbox[c] = no;
	}
}
function selectAll(box) {
	for(var i=0; i<box.length; i++) {
		box.options[i].selected = true;
	}
}
</script>
<style type="text/css">
<!--
.Menu {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none;
	margin: 3px;
	padding: 3px;
}
td {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
	text-decoration: none;
}
.SubMenu {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #6666FF;
	text-decoration: none;
	font-weight: bold;
	border: 1px solid #CCCCCC;
}
.link {
	text-decoration: none;
}
.BottomBorder {
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #000000;
}
.BottomBorderB {
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #CC9900;
}
.linkA {
	text-decoration: none;
	color: #999999;
	font-size: 10px;
}
.linkB {
	text-decoration: none;
	color: #0000FF;
	font-size: 12px;
	font-weight: normal;
}
.Title {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none;
	margin: 1px;
	padding: 1px;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #000000;
}
input {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333333;
	text-decoration: none;
	margin: 0px;
	padding: 0px;
	border: 1px solid #999999;
}
.MiniLink {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #999999;
	text-decoration: none;
}
.select {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333333;
	text-decoration: none;
	margin: 0px;
	padding: 0px;
	border: 1px solid #999999;
}
.CheckBox {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333333;
	text-decoration: none;
	margin: 0px;
	padding: 0px;
}
.cal {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 9px;
	color: #000000;
	text-decoration: none;
	border: 1px solid #CCCCCC;
	text-align: right;
	vertical-align: top;
}
.calBig {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
	text-decoration: none;
	border: 1px solid #CCCCCC;
	text-align: right;
	vertical-align: top;
}
.UpLink {
	text-decoration: none;
	padding-bottom: 1px;
	bottom: 4px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
	font-weight: bold;
}
-->
</style>
</head>

<body link="#6666FF" vlink="#6666FF" alink="#6666FF" bgcolor="#FFFFFF">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script language="JavaScript" src="overlib.js"></script>

<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr valign="bottom"> 
          <td width="250"><img src="images/header.gif" width="250" height="50" alt="My DataBook"></td>
          <td><div align="right"></div></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
        <tr>
          <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#F0F0F0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
                    <tr>
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#666666">
                          <tr>
                            <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                                <tr>
                                  <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
                                      <tr> 
                                        <td width="90" bgcolor="#6699FF" style="cursor:hand" onClick="window.location='index.php?Sec=home'" onMouseOver="bgColor='#FFFFFF'; this.style.color='#000000'" onMouseOut="bgColor='#6699FF'; this.style.color='#FFFFFF'" class="Menu"> 
                                          <div align="center">Home</div></td>
                                        <td width="90" bgcolor="#6699FF" style="cursor:hand" onClick="window.location='contacts.php?Sec=contacts'" onMouseOver="bgColor='#FFFFFF'; this.style.color='#000000'" onMouseOut="bgColor='#6699FF'; this.style.color='#FFFFFF'" class="Menu"> 
                                          <div align="center">Contacts</div></td>
                                        <td width="90" bgcolor="#6699FF" style="cursor:hand" onClick="window.location='schedule.php?Sec=schedule'" onMouseOver="bgColor='#FFFFFF'; this.style.color='#000000'" onMouseOut="bgColor='#6699FF'; this.style.color='#FFFFFF'" class="Menu"> 
                                          <div align="center">Schedule</div></td>
                                        <td width="90" bgcolor="#6699FF" style="cursor:hand" onClick="window.location='notes.php?Sec=notes'" onMouseOver="bgColor='#FFFFFF'; this.style.color='#000000'" onMouseOut="bgColor='#6699FF'; this.style.color='#FFFFFF'" class="Menu"> 
                                          <div align="center">Notes</div></td>
                                        <td width="90" bgcolor="#6699FF" style="cursor:hand" onClick="window.location='tasks.php?Sec=tasks'" onMouseOver="bgColor='#FFFFFF'; this.style.color='#000000'" onMouseOut="bgColor='#6699FF'; this.style.color='#FFFFFF'" class="Menu"> 
                                          <div align="center">Tasks</div></td>
                                        <td width="90" bgcolor="#6699FF" style="cursor:hand" onClick="window.location='reminders.php?Sec=reminders'" onMouseOver="bgColor='#FFFFFF'; this.style.color='#000000'" onMouseOut="bgColor='#6699FF'; this.style.color='#FFFFFF'" class="Menu"> 
                                          <div align="center">Reminders</div></td>
                                        <td width="90" bgcolor="#6699FF" style="cursor:hand" onClick="window.location='diary.php?Sec=diary'" onMouseOver="bgColor='#FFFFFF'; this.style.color='#000000'" onMouseOut="bgColor='#6699FF'; this.style.color='#FFFFFF'" class="Menu"> 
                                          <div align="center">Diary</div></td>
                                      </tr>
                                    </table></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td>
						

<?php

if (isset($Sec)) {
	echo ("<table width=\"650\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\">\n<tr>\n<td align=\"center\" class=\"SubMenu\">");
	switch($Sec) {
		case notes:
			echo (".: <a href=\"notes.php?Sec=notes\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">Show notes</a> : <a href=\"add_note.php?Sec=notes\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">Add Note</a> :.");
			break;

		case contacts:
			echo (".: <a href=\"contacts.php?Sec=contacts\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">Show Contacts</a> : <a href=\"add_contact.php?Sec=contacts\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">Add Contact</a> : <a href=\"mod_groups.php?Sec=contacts\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">Manage Groups</a> :.");
			break;

		case reminders:
			break;

		case home:
			echo (".: <a href=\"http://board.theadminshop.com/viewforum.php?f=5\" target =\"_blank\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">Help & Support</a> : <a href=\"http://board.theadminshop.com/viewforum.php?f=7\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\"target =\"_blank\" >Report a bug</a> : <a href=\"http://board.theadminshop.com/viewforum.php?f=6\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\"target =\"_blank\" >Suggestion box</a> :.");
			break;

		case tasks:
			echo (".: <a href=\"tasks.php?Sec=tasks\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">Task Listing</a> : <a href=\"add_task.php?Sec=tasks\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">Add new task</a> :.");
			break;

		case schedule:
			echo (".: <a href=\"schedule.php?Sec=schedule\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">Calendar</a> : <a href=\"dialy.php?Sec=schedule\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">Day Planner</a> : <a href=\"scheduled_notes.php?Sec=schedule\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">View Scheduled notes</a> : <a href=\"add_scheduleed_note.php?Sec=schedule\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">Add Scheduled note</a> : <a href=\"appointments.php?Sec=schedule\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">Appointments</a> : <a href=\"add_appointment.php?Sec=schedule\" class=linkA onMOuseOver=\"this.style.color='#000000'\" onMouseOut=\"this.style.color=''\">Add appointment</a> :.");
			break;
	}
	echo ("</td>\n</tr>\n</table><br>");
}

if (!isset($Sec)) {
	echo ("<br><br>");
}

?>