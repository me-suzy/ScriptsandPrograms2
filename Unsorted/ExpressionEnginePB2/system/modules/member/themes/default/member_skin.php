<?php

/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 - 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: member_skin.php
-----------------------------------------------------
 Purpose: Member Profile Skin Elements
=====================================================
*/

if ( ! defined('EXT')){
	exit('Invalid file request');
}

class Member_skin {

//-------------------------------------
//  CSS Stylesheet
//-------------------------------------

function stylesheet()
{
return <<< EOF
<style type='text/css'>

body {
 margin:0;
 padding:0;
 font-family:Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:11px;
 color:#000;
 background-color:#fff;
}

a {
 text-decoration:none; color:#330099; background-color:transparent;
}
a:visited {
 color:#330099; background-color:transparent;
}
a:hover {
 color:#000; text-decoration:underline; background-color:transparent;
}

#content {
 left:				0px;
 right:				10px;
 margin:			10px 25px 10px 25px;
 padding:			8px 0 0 0;
}

.header {
 margin:			0 0 14px 0;
 padding:			2px 0 2px 0;
 border:			1px solid #000770;
 background-color:	#797EB8;
 text-align:		center;
}

.breadcrumb {
 margin:			0 0 10px 0;
 background-color:	transparent;   
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			10px; 
}

.default, .defaultBold {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			11px;
 color:				#000;
 padding:			3px 0 3px 0;
 background-color:	transparent;  
}

.defaultBold {
 font-weight:		bold;
}

.defaultSmall {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			10px;
 color:				#000;
 background-color:	transparent;  
}

h1 {  
 font-family:		Georgia, Times New Roman, Times, Serif, Arial;
 font-size: 		20px;
 font-weight:		bold;
 letter-spacing:	.05em;
 color:				#fff;
 margin: 			3px 0 3px 0;
 padding:			0 0 0 10px;
}

h2 {
 font-family:		Tahoma, Verdana, Geneva, Trebuchet MS, Arial, Sans-serif;
 font-size:			14px;
 font-weight:		bold;
 color:				#000;
 padding: 			5px 6px 5px 4px;
 margin:			0;
 background-color: transparent;  
}

h3 {  
 font-family:	Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:		11px;
 font-weight:	bold;
 color:			#000;
 background:	transparent;
 margin:		6px 0 3px 0;
}

p {  
 font-family:	Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:		11px;
 font-weight:	normal;
 color:			#000;
 background:	transparent;
 margin: 		6px 0 6px 0;
}

.alert {
 color:				#990000;
 font-weight:		bold;
}

.highlight {
 color:				#990000;
 font-weight:		normal;
}

.success {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			11px;
 color:				#009933;
 font-weight:		bold;
 background-color:	transparent; 
}

.outerBorder {
 border:		1px solid #4B5388;
}

.borderTopBot {
 border-top:	1px solid #4B5388;
 border-bottom:	1px solid #4B5388;
}

.borderBot {
 border-bottom:		1px solid #4B5388;
}

.innerPad {
 padding:			10px 15px 15px 15px;
 background-color:	transparent;
}

.innerShade {
 background-color:	#DDE1E7;
 margin:			0;
 padding:			10px;
}

.smallPad {
 padding:			4px 6px 6px 6px;
 background-color:	transparent;
}

.paddedCenter {
 text-align:		center;
 margin: 			10px 0 10px 0;
}

.tablePad {
 padding:			3px 3px 5px 3px;
 background-color:	#fff;
}

.tablePadBig {
 padding:			14px 0 5px 12px;
 background-color:	#fff;
}

.tableHeading {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			13px;
 color:				#000;
 padding:			6px 10px 6px 6px;
 border-bottom:		1px solid #4B5388;
}

.tableCell {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			11px;
 color:				#000;
 padding:			6px 10px 6px 6px;
 background-color:	#fff;  
}

.tableCellOne {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			11px;
 color:				#000;
 padding:			6px 10px 6px 6px;
 border-top:		1px solid #fff;
 border-bottom:		1px solid #B0B1BB;
 border-right:		1px solid #fff;
 background-color:	#eee;  
}
.tableCellTwo {
 font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:         11px;
 color:             #000;
 padding:           6px 10px 6px 6px;
 border-top:        1px solid #fff;
 border-bottom:     1px solid #B0B1BB;
 border-right:      1px solid #fff;
 background-color:  #E4E4E4;  
}

.memberlistRowOne {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			11px;
 color:				#000;
 padding:           6px 6px 6px 8px;
 background-color:	#DADADD;  
}

.memberlistRowTwo {
 font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:         11px;
 color:             #000;
 padding:           6px 6px 6px 8px;
 background-color:  #eee;  
}

.memberlistHead {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size: 		11px;
 font-weight: 		bold;
 color:				#000;
 padding: 			8px 0 8px 8px;
 border-bottom:		1px solid #999;
 background-color:	transparent;  
}

.memberlistFooter {
 font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:         11px;
 color:             #000;
 padding:           6px 10px 6px 6px;
 border-top:        1px solid #ccc;
 border-bottom:     1px solid #999;
 border-right:      1px solid #fff;
 background-color:  #C6C9CF;  
}

.profileTitle {
 font-family:		Tahoma, Verdana, Geneva, Trebuchet MS, Arial, Sans-serif;
 font-size:			14px;
 font-weight:		bold;
 color:				#000;
 padding: 			5px 6px 5px 0;
 margin:			0;
 background-color: transparent;  
}

.profileTopBox {
 background-color:	#D1D4DA;
 margin:			0;
 padding:			10px;
}

.profileHead {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			10px;
 font-weight:		bold;
 text-transform:	uppercase;
 color:				#000;
 padding:			5px 4px 5px 10px;
 background-color:	#ADB1B8;  
 border-top:		1px solid #fff;
 border-bottom:		1px solid #eee;
 margin:			0 0 0 0;
}

.profileMenuInner {
 padding-left:		10px;
 padding-right:		8px;
 margin-bottom:		4px;
 margin-top:		4px;
}

.menuItem {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			11px;
 padding:			3px 0 5px 0;
 background-color:	transparent;  
}

.paginate {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			12px;
 font-weight: 		normal;
 letter-spacing:	.1em;
 padding:			10px 6px 10px 4px;
 margin:			0;
 background-color:	transparent;  
}

.pagecount {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			10px;
 color:				#666;
 font-weight:		normal;
 background-color: transparent;  
}

.copyright {
 text-align:        center;
 font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:         9px;
 color:             #999;
 margin-top:        15px;
 margin-bottom:     15px;
}

form {
 margin:            0;
}

.hidden {
 margin:            0;
 padding:           0;
 border:            0;
}

.input {
 border-top:        1px solid #999999;
 border-left:       1px solid #999999;
 background-color:  #fff;
 color:             #000;
 font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:         11px;
 height:            1.6em;
 padding:           .3em 0 0 2px;
 margin-top:        6px;
 margin-bottom:     5px;
} 

.textarea {
 border-top:        1px solid #999999;
 border-left:       1px solid #999999;
 background-color:  #fff;
 color:             #000;
 font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:         11px;
 margin-top:        3px;
 margin-bottom:     5px;
}

.select {
 background-color:  #fff;
 font-family:       Arial, Verdana, Sans-serif;
 font-size:         10px;
 font-weight:       normal;
 letter-spacing:    .1em;
 color:             #000;
 margin-top:        6px;
 margin-bottom:     3px;
} 

.radio {
 color:             #000;
 margin-top:        7px;
 margin-bottom:     4px;
 padding:           0;
 border:            0;
 background-color:  transparent;
}

.checkbox {
 background-color:  transparent;
 margin:            3px;
 padding:           0;
 border:            0;
}

.submit {
 background-color:  #fff;
 font-family:       Arial, Verdana, Sans-serif;
 font-size:         10px;
 font-weight:       normal;
 letter-spacing:    .1em;
 padding:           1px 3px 1px 3px;
 margin-top:        6px;
 margin-bottom:     4px;
 text-transform:    uppercase;
 color:             #000;
}
</style>
EOF;
}
// END




//-------------------------------------
//  Breadcrumb
//-------------------------------------

function breadcrumb()
{
return <<< EOF

<table class='breadcrumb' border='0' cellpadding='0' cellspacing='0' width='99%'>
<tr>
<td><span class="defaultBold">&nbsp; <a href="{homepage}">{site_name}</a> {breadcrumb}</span></td>

{if logged_in}
<td align="right">

{lang:logged_in_as}&nbsp; <span class="defaultBold"><a href="{profile_path=member/index}">{name}</a></span>

&nbsp;|&nbsp;

<span class="defaultBold"><a href="{path=member/profile}">{lang:my_account}</a></span>

&nbsp;|&nbsp;

<span class="defaultBold"><a href="{path=member/memberlist}">{lang:memberlist}</a></span>

&nbsp;|&nbsp;

<span class="defaultBold"><a href="{path="LOGOUT"}">{lang:logout}</a></span>

&nbsp;&nbsp;

</td>
{/if}

</tr>
</table>
EOF;
}
// END




//-------------------------------------
//  Copyright Notice
//-------------------------------------

function copyright()
{
return <<< EOF

<div class='copyright'>Powered by ExpressionEngine, Nullified by GTT</div>

EOF;
}
// END




//-------------------------------------
//  Member Profile Menu
//-------------------------------------

function menu()
{
return <<< EOF

<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='outerBorder' style='width:24%' valign='top'>

<div class='tablePad'>


<div class='tableHeading'><h2>{lang:menu}</h2></div>

<div class='borderBot'><div class='profileHead'>{lang:personal_settings}</div></div>

<div class='profileMenuInner'>

<div class='menuItem'><a href='{path:profile}'>{lang:edit_profile}</a></div>

<div class='menuItem'><a href='{path:email}'>{lang:email_settings}</a></div>

<div class='menuItem'><a href='{path:username}'>{lang:username_and_password}</a></div>

<div class='menuItem'><a href='{path:localization}'>{lang:localization}</a></div>

</div>


<div class='borderTopBot'><div class='profileHead'>{lang:extras}</div></div>

<div class='profileMenuInner'>

<div class='menuItem'><a href='{path:notepad}' >{lang:notepad}</a></div>

</div>

</div>


</td>
<td style='width:1%'>&nbsp;</td>

EOF;
}
// END




//-------------------------------------
//  Member Profile Home Page
//-------------------------------------

function home_page()
{
return <<< EOF

<td class='outerBorder' style='width:75%' valign='top'>


<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tablePad' >

<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tableHeading'colspan='2'><h2>{lang:your_stats}</h2></td>

</tr><tr>

<td class='tableCellTwo'><div class='defaultBold'>{lang:email}</div></td>
<td class='tableCellTwo'><a href='mailto:{email}'><b>{email}</b></a></td>

</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:join_date}</div></td>
<td class='tableCellOne'>{join_date}</td>

</tr><tr>

<td class='tableCellTwo'><div class='defaultBold'>{lang:last_visit}</div></td>
<td class='tableCellTwo'>{last_visit_date}</td>

</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:most_recent_entry}</div></td>
<td class='tableCellOne'>{recent_entry_date}</td>

</tr><tr>

<td class='tableCellTwo'><div class='defaultBold'>{lang:most_recent_comment}</div></td>
<td class='tableCellTwo'>{recent_comment_date}</td>

</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:total_entries}</div></td>
<td class='tableCellOne'>{total_entries}</td>

</tr><tr>

<td class='tableCellTwo'><div class='defaultBold'>{lang:total_comments}</div></td>
<td class='tableCellTwo'>{total_comments}</td>

</tr>
</table>

</td>
</tr>
</table>


</td>
</tr>
</table>

EOF;
}
// END




//-------------------------------------
//  Member Profile Form
//-------------------------------------

function edit_profile_form()
{
return <<< EOF

<td class='outerBorder' style='width:76%' valign='top'>


<form method="post" action="{path:update_profile}">


<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tablePad'>

<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tableHeading'colspan='2'><h2>{lang:edit_your_profile}</h2></td>

</tr><tr>

<td class='tableCellOne' width='25%'><div class='defaultBold'>{lang:url}</div></td>
<td class='tableCellTwo' width='75%'><input type='input' class='input' name='url' value='{url}' maxlength='75' style='width:100%'/></td>

</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:birthday}</div></td>
<td class='tableCellTwo'>{form:birthday_year} {form:birthday_month} {form:birthday_day}</td>

</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:location}</div></td>
<td class='tableCellTwo'><input type='input' class='input' name='location' value='{location}' maxlength='50' style='width:100%'/></td>


</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:occupation}</div></td>
<td class='tableCellTwo'><input type='input' class='input' name='occupation' value='{occupation}' maxlength='80' style='width:100%'/></td>


</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:interests}</div></td>
<td class='tableCellTwo'><input type='input' class='input' name='interests' value='{interests}' maxlength='120' style='width:100%'/></td>


</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:aol_im}</div></td>
<td class='tableCellTwo'><input type='input' class='input' name='aol_im' value='{aol_im}' maxlength='50' style='width:100%'/></td>

</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:icq}</div></td>
<td class='tableCellTwo'><input type='input' class='input' name='icq' value='{icq}' maxlength='50' style='width:100%'/></td>

</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:yahoo_im}</div></td>
<td class='tableCellTwo'><input type='input' class='input' name='yahoo_im' value='{yahoo_im}' maxlength='50' style='width:100%'/></td>

</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:msn_im}</div></td>
<td class='tableCellTwo'><input type='input' class='input' name='msn_im' value='{msn_im}' maxlength='50' style='width:100%'/></td>

</tr><tr>

<td class='tableCellOne' valign='top'><div class='defaultBold'>{lang:bio}</div></td>
<td class='tableCellTwo'><textarea name='bio' style='width:100%' class='textarea' rows='12' cols='90'>{bio}</textarea></td>



{custom_profile_fields}

</tr>
</table>

</td>
</tr>
</table>


<div class='paddedCenter'>

<input type='submit' class='submit' value='{lang:update}' />

<br /><br />

<span class="alert">*</span> {lang:required}

</div>

</form>


</td>
</tr>
</table>

EOF;
}
// END




//-------------------------------------
//  Custom Profile Field Form
//-------------------------------------

function custom_profile_fields()
{
return <<< EOF

</tr><tr>

<td class='tableCellOne' width='25%'><div class='defaultBold'>{lang:profile_field}</div></td>
<td class='tableCellTwo' width='75%'>{form:custom_profile_field}</td>

EOF;
}
// END




//-------------------------------------
//  Profile Update Message
//-------------------------------------

function success()
{
return <<< EOF

<td class='outerBorder' style='width:76%' valign='top'>

<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tablePad'>

<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tableHeading'><h2>{lang:heading}</h2></td>

</tr><tr>

<td class='tableCellOne'><div class='success'>{lang:message}</div></td>

</tr>
</table>

</td>
</tr>
</table>

</td>
</tr>
</table>

EOF;
}
// END




//-------------------------------------
//  Email Preferences Form
//-------------------------------------

function email_prefs_form()
{
return <<< EOF

<td class='outerBorder' style='width:76%' valign='top'>

<form method="post" action="{path:update_email_settings}">


<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tablePad' >

<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tableHeading'colspan='2'><h2>{lang:email_settings}</h2></td>

</tr><tr>

<td class='tableCellTwo' width='30%'><div class='defaultBold'>{lang:email}</div></td>
<td class='tableCellTwo' width='70%'><input type='input' class='input' name='email' value='{email}' maxlength='75' style='width:100%'/></td>

</tr><tr>

<td class='tableCellOne' colspan='2'><div class='defaultBold'><input type='checkbox' name='accept_admin_email' value='y' {state:accept_admin_email} />&nbsp;&nbsp;{lang:accept_admin_email}</div></td>

</tr><tr>

<td class='tableCellOne' colspan='2'><div class='defaultBold'><input type='checkbox' name='accept_user_email' value='y' {state:accept_user_email} />&nbsp;&nbsp;{lang:accept_user_email}</div></td>

</tr><tr>

<td class='tableCellOne' colspan='2'><div class='defaultBold'><input type='checkbox' name='notify_by_default' value='y' {state:notify_by_default} />&nbsp;&nbsp;{lang:notify_by_default}</div></td>

</tr><tr>

<td class='tablePadBig' colspan='2'>
<div class='defaultBold'><span class='alert'>*</span>&nbsp; {lang:existing_password}</div>
<div class='default'><span class='highlight'>{lang:existing_password_exp}</span></div>
<input type='password' class='input' name='password' value='' maxlength='32' style='width:300px'/></td>

</tr>
</table>

</td>
</tr>
</table>


<div class='paddedCenter'>

<input type='submit' class='submit' value='{lang:update}' />

<br /><br />

<span class="alert">*</span> {lang:required}

</div>

</form>


</td>
</tr>
</table>

EOF;
}
// END




//-------------------------------------
//  Username and Password Form
//-------------------------------------

function username_password_form()
{
return <<< EOF

<td class='outerBorder' style='width:76%' valign='top'>

<form method="post" action="{path:update_username_password}">


<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tablePad' >

<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tableHeading'colspan='2'><h2>{lang:username_and_password}</h2></td>

{row:username_form}


</tr><tr>

<td class='tableCellTwo'><div class='defaultBold'><span class='alert'>*</span>&nbsp; {lang:screen_name}</div></td>
<td class='tableCellTwo'><input type='input' class='input' name='screen_name' value='{screen_name}' maxlength='50' style='width:250px'/></td>

</tr><tr>

<td class='tableCellOne' colspan='2'>

<div class='defaultBold'>{lang:password_change}</div>
<div class='alert'>{lang:password_change_exp}</div>

<div class='defaultBold'><br />{lang:new_password}</div>
<input style='width:250px' type='password' name='password' value='' size='35' maxlength='32' class='input' />

<div class='defaultBold'>{lang:new_password_confirm}</div>
<input style='width:250px' type='password' name='password_confirm' value='' size='35' maxlength='32' class='input' />

</td>

</tr><tr>

<td class='tablePadBig' colspan='2'>
<div class='defaultBold'><span class='alert'>*</span>&nbsp; {lang:existing_password}</div>
<div class='default'><span class='highlight'>{lang:existing_password_exp}</span></div>
<input type='password' class='input' name='current_password' value='' maxlength='32' style='width:300px'/></td>

</tr>
</table>

</td>
</tr>
</table>


<div class='paddedCenter'>

<input type='submit' class='submit' value='{lang:update}' />

<br /><br />

<span class="alert">*</span> {lang:required}

</div>

</form>


</td>
</tr>
</table>

EOF;
}
// END




//-------------------------------------
//  Username Form Row
//-------------------------------------

function username_row()
{
return <<< EOF
</tr><tr>

<td class='tableCellTwo' width='30%'><div class='defaultBold'><span class="alert">*</span>&nbsp; {lang:username}</div></td>
<td class='tableCellTwo'><input type='input' class='input' name='username' value='{username}' maxlength='50' size='35'style='width:250px'/></td>
EOF;
}
// END




//-------------------------------------
//  Username Change Disallowed Message
//-------------------------------------

function username_change_disallowed()
{
return <<< EOF

</tr><tr>

<td class='tableCellTwo' colspan='2' width='100%'>{lang:username_disallowed}</td>

EOF;
}
// END




//-------------------------------------
//  Password Change Warning
//-------------------------------------

function password_change_warning()
{
return <<< EOF

<div class='alert'><br />{lang:password_change_warning}</div>

EOF;
}
// END




//-------------------------------------
//  Localization Preferences Form
//-------------------------------------

function localization_form()
{
return <<< EOF

<td class='outerBorder' style='width:76%' valign='top'>

<form method="post" action="{path:update_localization}">


<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tablePad' >

<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tableHeading'colspan='2'><h2>{lang:localization_settings}</h2></td>

</tr><tr>

<td class='tableCellOne' width='30%'><div class='defaultBold'>{lang:timezone}</div></td>
<td class='tableCellTwo' width='70%'>{form:localization}</td>

</tr><tr>

<td class='tableCellOne'>&nbsp;</td>
<td class='tableCellTwo' width='70%'>
<div class='defaultBold'><input type='checkbox' name='daylight_savings' value='y' {state:daylight_savings} /> {lang:daylight_savings_time}</div></td>

</tr><tr>

<td class='tableCellOne' width='30%'><div class='defaultBold'>{lang:time_format}</div></td>
<td class='tableCellTwo' width='70%'>{form:time_format}</td>

</tr><tr>

<td class='tableCellOne' width='30%'><div class='defaultBold'>{lang:language}</div></td>
<td class='tableCellTwo' width='70%'>{form:language}</td>

</tr>
</table>

</td>
</tr>
</table>

<div class='paddedCenter'>

<input type='submit' class='submit' value='{lang:update}' />

</div>

</form>


</td>
</tr>
</table>


EOF;
}
// END




//-------------------------------------
//  Notepad Form
//-------------------------------------

function notepad_form()
{
return <<< EOF

<td class='outerBorder' style='width:76%' valign='top'>

<form method="post" action="{path:update_notepad}">


<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tablePad' >

<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='tableHeading'colspan='2'><h2>{lang:notepad}</h2></td>

</tr><tr>

<td class='tableCellOne' colspan='2'>{lang:notepad_blurb}</td>

</tr><tr>

<td class='tableCellTwo' colspan='2'><textarea name='notepad' style='width:100%' class='textarea' rows='{notepad_size}' cols='90'>{notepad_data}</textarea></td>

</tr><tr>

<td class='tableCellTwo' width='30%'><div class='defaultBold'>{lang:notepad_size}</div></td>
<td class='tableCellTwo' width='70%'><input type='input' class='input' name='notepad_size' value='{notepad_size}' maxlength='2' style='width:60px'/></td>

</tr>
</table>

</td>
</tr>
</table>

<div class='paddedCenter'>

<input type='submit' class='submit' value='{lang:update}' />

</div>

</form>

</td>
</tr>
</table>

EOF;
}
// END




//-------------------------------------
//  Forgot Password Form
//-------------------------------------

function forgot_form()
{
return <<< EOF
<div class='outerBorder'>

<div class='innerPad'>

{form_declaration}

<h3>{lang:your_email}</h3>

<p><input type="text" name="email" value="" class="input" maxlength="120" size="40" /></p>

<p><input type="submit" value="{lang:submit}" class="submit" /></p>

</form>

<p><br /><a href="{path=member/login}">{lang:back_to_login}</a>

</div>
</div>
EOF;
}
// END




//-------------------------------------
//  Public Member Profile
//-------------------------------------

function public_profile()
{
return <<< EOF
<table class='outerBorder' border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='profileTopBox' valign='top'>

<div class='profileTitle'>{name}</div>
<p>{lang:member_group}&nbsp; <b>{member_group}</b></p>

</td>
<td class='profileTopBox' align="right" valign='top'>

<p><b>{lang:total_entries}:&nbsp; {total_entries} &nbsp;&nbsp;</p>
<p><b>{lang:total_comments}:&nbsp; {total_comments} &nbsp;&nbsp;</p>

</td>
</tr>
</table>


<div class='tablePad'><br /></div>


<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='outerBorder' width='49%' valign='top'>
<div class='tablePad'>

<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:url}</div></td>
<td class='tableCellOne'>{if url}<a href="{url}" target="_blank"><b>{url}</b></a>{/if}</td>

</tr><tr>

<td class='tableCellTwo' width='50%'><div class='defaultBold'>{lang:email}</div></td>
<td class='tableCellTwo' width='50%'>
{if accept_email}
<a href="#" {email_console}><img src="{image_path}icon_email.gif" alt="Email Console" title="Email Console"></a>
{/if}
</td>

</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:aol_im}</div></td>
<td class='tableCellOne'>
{if aol_im}
<a href="#" {aim_console}><img src="{image_path}icon_aim.gif" width="58" height="15" border="0" alt="AOL IM" title="AOL IM"></a>
{/if}
</td>

</tr><tr>

<td class='tableCellTwo'><div class='defaultBold'>{lang:icq}</div></td>
<td class='tableCellTwo'>
{if icq}
<a href="#" {icq_console}><img src="{image_path}icon_icq.gif" width="58" height="15" border="0" alt="ICQ" title="ICQ"></a>
{/if}
</td>

</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:yahoo}</div></td>
<td class='tableCellOne'>
{if icq}
<a href="{yahoo_console}" target="_blank"><img src="{image_path}icon_yim.gif" width="58" height="15" border="0" alt="Yahoo Messenger" title="Yahoo Messenger"></a>
{/if}
</td>

</tr><tr>

<td class='tableCellTwo'><div class='defaultBold'>{lang:msn}</div></td>
<td class='tableCellTwo'>{msn_im}</td>

</td>
</tr>
</table>

</div>

</td>
<td width='2%'>&nbsp;</td>

<td class='outerBorder' width='49%' valign='top'>

<div class='tablePad'>
<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>

</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:member_local_time}</div></td>
<td class='tableCellOne'>{local_time format="%F %d, %Y &nbsp;%h:%i %A"}</td>

</tr><tr>

<td class='tableCellTwo'><div class='defaultBold'>{lang:last_visit}</div></td>
<td class='tableCellTwo'>{last_visit format="%F %d, %Y &nbsp;%h:%i %A"}</td>

</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:join_date}</div></td>
<td class='tableCellOne'>{join_date format="%F %d, %Y &nbsp;%h:%i %A"}</td>

</tr><tr>

<td class='tableCellTwo'><div class='defaultBold'>{lang:most_recent_comment}</div></td>
<td class='tableCellTwo'>{last_comment_date format="%F %d, %Y &nbsp;%h:%i %A"}</td>

</tr><tr>

<td class='tableCellOne'><div class='defaultBold'>{lang:most_recent_entry}</div></td>
<td class='tableCellOne'>{last_entry_date format="%F %d, %Y &nbsp;%h:%i %A"}</td>

</tr><tr>

<td class='tableCellTwo'><div class='defaultBold'>{lang:birthday}</div></td>
<td class='tableCellTwo'>{birthday}</td>

</td>
</tr>
</table>

</div>

</td>
</tr>
</table>

<div class='tablePad'><br /></div>




<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>
<td class='outerBorder' width='49%' valign='top'>

<div class='tablePad'>

<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>

<td class='tableCellTwo' width='50%'><div class='defaultBold'>{lang:location}</div></td>
<td class='tableCellOne' width='50%'><div class='default'>{location}</div></td>

</tr><tr>

<td class='tableCellTwo' width='50%'><div class='defaultBold'>{lang:occupation}</div></td>
<td class='tableCellOne' width='75%'><div class='default'>{occupation}</div></td>

</tr><tr>

<td class='tableCellTwo' width='50%'><div class='defaultBold'>{lang:interests}</div></td>
<td class='tableCellOne' width='50%'><div class='default'>{interests}</div></td>

{custom_profile_fields}

</tr>
</table>

</div>

</td>

<td width='2%'>&nbsp;</td>

<td class='outerBorder' width='49%' valign='top'>


<div class='tablePad'>
<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>

<td class='smallPad'>
<h3>{lang:bio}</h3>

{bio}

</td>
</tr>
</table>

</div>

</td>
</tr>
</table>

EOF;
}
// END




//-------------------------------------
//  Custom Profile Field Rows
//-------------------------------------

function public_custom_profile_fields()
{
return <<< EOF

</tr><tr>

<td class='tableCellTwo' valign='top'><div class='defaultBold'>{field_name}</div></td>
<td class='tableCellOne'>{field_data}</td>

EOF;
}
// END




//-------------------------------------
//  Login Form
//-------------------------------------

function login_form()
{
return <<< EOF
<div class='outerBorder'>

<div class='innerPad'>

{form_declaration}



<h3>{lang:username}</h3>
<p><input type="text" name="username" value=""  maxlength="32" class="input" size="25" /></p>

<h3>{lang:password}</h3>
<p><input type="password" name="password" value="" maxlength="32" class="input" size="25" /></p>

{if auto_login}<p><input class='checkbox' type='checkbox' name='auto_login' value='1'  /> {lang:auto_login}</p>{/if}

<p><input class='checkbox' type='checkbox' name='anon' value='1'  checked='checked' /> {lang:show_name}</p>

<p><input type="submit" value="{lang:submit}"  class="submit" /></p>

<p><a href="{path=member/forgot}">{lang:forgot_password}</a></p>

</form>

</div>
</div>

EOF;
}
// END




//-------------------------------------
//  Registration Form
//-------------------------------------

function registration_form()
{
return <<< EOF
<div class='outerBorder'>

<div class='tablePad'>

<table border='0' cellspacing='0' cellpadding='0' style='width:100%'>
<tr>

<td class='tableCellOne' width='35%'><div class='defaultBold'><span class="highlight">*</span> {lang:username}</div><div class='default'>{lang:username_length}</div></td>
<td class='tableCellTwo' width='65%'><input type="text" name="username" value="" maxlength="32" class="input" size="25" style="width:300px" /></td>

</tr><tr>

<td class='tableCellOne' width='35%'><div class='defaultBold'><span class="highlight">*</span> {lang:password}</div><div class='default'>{lang:password_length}</div></td>
<td class='tableCellTwo' width='65%'><input type="password" name="password" value="" maxlength="32" class="input" size="25" style="width:300px" /></td>

</tr><tr>

<td class='tableCellOne' width='35%'><div class='defaultBold'><span class="highlight">*</span> {lang:password_confirm}</div></div></td>
<td class='tableCellTwo' width='65%'><input type="password" name="password_confirm" value="" maxlength="32" class="input" size="25" style="width:300px" /></td>


</tr><tr>

<td class='tableCellOne' width='35%'>
<div class='defaultBold'><span class="highlight">*</span> {lang:screen_name}</div>
<div class='default'>{lang:screen_name_explanation}</div>
</td>
<td class='tableCellTwo' width='65%'><input type="text" name="screen_name" value="" maxlength="100" class="input" size="25" style="width:300px" /></td>

</tr><tr>

<td class='tableCellOne' width='35%'><div class='defaultBold'><span class="highlight">*</span> {lang:email}</div></td>
<td class='tableCellTwo' width='65%'><input type="text" name="email" value="" maxlength="120" class="input" size="40" style="width:300px" /></td>

</tr><tr>

<td class='tableCellOne' width='35%'><div class='defaultBold'>{lang:url}</div></td>
<td class='tableCellTwo' width='65%'><input type="text" name="url" value="" maxlength="100" class="input" size="25" style="width:300px" /></td>

{custom_fields}
</tr><tr>
<td class='tableCellOne' width='35%'><div class='defaultBold'>{required}<span class="highlight">*</span>{/required} {field_name}</div></td>
<td class='tableCellTwo' width='65%'>{field}</td>
{/custom_fields}

</tr><tr>

<td colspan='2' class='tableCellOne'>
<div class='defaultBold'>{lang:terms_of_service}</div>

<textarea name='rules' style='width:100%' class='textarea' rows='8' cols='90' readonly>
All messages posted at this site express the views of the author, and do not necessarily reflect the views of the owners and administrators of this site.

By registering at this site you agree not to post any messages that are obscene, vulgar, slanderous, hateful, threatening, or that violate any laws.   We will permanently ban all users who do so.   

We reserve the right to remove, edit, or move any messages for any reason.
</textarea>

<input type='checkbox' name='accept_terms' value='y'  />&nbsp;&nbsp;<b>{lang:terms_accepted}</b>
</td>
</tr>
</table>

<div class='innerPad'>

<p><span class="highlight">*</span> {lang:required_fields}</p>

<p><input type="submit" value="{lang:submit}" class="submit" /></p>

</div>

</div>
</div>
EOF;
}
// END




//-------------------------------------
//  Member List Page
//-------------------------------------

function memberlist()
{
return <<< EOF
<div class='outerBorder'>

<div class='tablePad'>

<form method="post" action="{path=member/memberlist}">

<table border="0" cellpadding="6" cellspacing="1" width="100%">
<tr>
<td class='memberlistHead'>{lang:name}</td>
<td class='memberlistHead'>{lang:comments}</td>
<td class='memberlistHead'>{lang:email}</td>
<td class='memberlistHead'>{lang:url}</td>
<td class='memberlistHead'>{lang:aol}</td>
<td class='memberlistHead'>{lang:icq}</td>
<td class='memberlistHead'>{lang:yahoo}</td>
<td class='memberlistHead'>{lang:join_date}</td>
<td class='memberlistHead'>{lang:last_visit}</td>

{member_rows}

<tr>
<td class='memberlistFooter' colspan="9" align='center' valign='middle'>

<div class="defaultSmall">
<b>{lang:show}</b>

<select name='group_id' class='select'>
{group_id_options}
</select>


&nbsp; <b>{lang:sort}</b>

<select name='order_by' class='select'>
{order_by_options}
</select> 

&nbsp;  <b>{lang:order}</b>

<select name='sort_order' class='select'>
{sort_order_options}
</select> 

&nbsp; <b>{lang:rows}</b>

<select name='row_limit' class='select'>
{row_limit_options}
</select> 


&nbsp; <input type='submit' value='Submit' class='submit' />

</div>
</td>
</tr>
</table>

{if paginate}

<div class='paginate'>

<span class='pagecount'>{page_count}</span>&nbsp; {paginate}

</div>

{/if}

</form>

EOF;
}
// END




//-------------------------------------
//  Member List Rows
//-------------------------------------

function memberlist_rows()
{
return <<< EOF
<tr>

<td class='{member_css}' width="20%">
<span class="defaultBold"><a href="{profile_path=member/index}">{name}</a></span>
</td>

<td class='{member_css}'>{total_comments}</td>

<td class='{member_css}'>
{if accept_email}
<a href="#" {email_console}><img src="{image_path}icon_email.gif" width="56" height="15" alt="Email Console" title="Email Console"></a>
{/if}
</td>

<td class='{member_css}'>
{if url}
<a href="{url}" target="_blank"><img src="{image_path}icon_www.gif" width="56" height="15" border="0" alt="{url}" title="{url}"></a>
{/if}
</td>

<td class='{member_css}'>
{if aol_im}
<a href="#" {aim_console}><img src="{image_path}icon_aim.gif" width="56" height="15" border="0" alt="AOL IM" title="AOL IM"></a>
{/if}
</td>

<td class='{member_css}'>
{if icq}
<a href="#" {icq_console}><img src="{image_path}icon_icq.gif" width="56" height="15" border="0" alt="ICQ" title="ICQ"></a>
{/if}
</td>

<td class='{member_css}'>
{if icq}
<a href="{yahoo_console}" target="_blank"><img src="{image_path}icon_yim.gif" width="56" height="15" border="0" alt="Yahoo Messenger" title="Yahoo Messenger"></a>
{/if}
</td>


<td class='{member_css}'>{join_date  format="%m/%d/%Y"}</td>

<td class='{member_css}'>{last_visit  format="%m/%d/%Y"}</td>

</tr>
EOF;
}
// END




//-------------------------------------
//  Email Console
//-------------------------------------

function email_form()
{
return <<< EOF
<div class='outerBorder'>

<div class='innerPad'>

{form_declaration}

<h3>{lang:recipient}&nbsp; {name}</h3>

<h3>{lang:subject}</h3>
<p><input type="text" name="subject" value="" style='width:100%' maxlength="80" class="input" size="70" /></p>

<h3>{lang:message}</h3>
<p><textarea name='message' style='width:100%' class='textarea' rows='10' cols='90'></textarea></p>

<div class="innerShade">
<p>{lang:message_disclaimer}</p>
<p class='highlight'>{lang:message_logged}</p>
</div>
<p><input type='checkbox' name='self_copy' value='y' />&nbsp;&nbsp;{lang:send_self_copy}</p>

<p><input type="submit" value="{lang:submit}" class="submit" /></p>

<div class="paddedCenter"><a href="JavaScript:window.close();">{lang:close_window}</a></div>

</form>

</div>
</div>
EOF;
}
// END




//-------------------------------------
//  Email Messages
//-------------------------------------

function email_user_message()
{
return <<< EOF
<div class='outerBorder'>

<div class='innerPad'>

<br />

<div class="innerShade">
<p class='{css_class}'>{lang:message}</p>
</div>

<div class="paddedCenter"><a href="JavaScript:window.close();">{lang:close_window}</a></div>

</form>

</div>
</div>
EOF;
}
// END




//-------------------------------------
//  AOL Instant Messenger Console
//-------------------------------------

function aim_console()
{
return <<< EOF
<div>&nbsp;</div>
<table width="118" cellspacing="0" cellpadding="0" border="0" align="center">
<tr>
<td width="118" height="46"><img src="{image_path}aim_head.gif" width="118" height="46" border="0"></td>
</tr>
<tr><td width="118" height="40"><a href="aim:goim?screenname={aol_im}&message=Hi.+Are+you+there?"><img src="{image_path}aim_im.gif" width="118" height="40" border="0" alt="I am Online"></a></td>
</tr><tr>
<td width="118" height="40"><a href="aim:addbuddy?screenname={aol_im}"><img src="{image_path}aim_buddy.gif" width="118" height="40" border="0" alt="Add me to your Buddy List"></a></td>
</tr><tr>
<td width="118" height="33"><a href="http://aim.aol.com/aimnew/NS/congratsd2.adp"><img src="{image_path}aim_footer.gif" width="118" height="33" border="0"></a></td>
</tr>
</table>
<div>&nbsp;</div>
<div class="paddedCenter"><a href="JavaScript:window.close();">{lang:close_window}</a></div>
EOF;
}
// END




//-------------------------------------
//  ICQ Console
//-------------------------------------

function icq_console()
{
return <<< EOF
<div class='outerBorder'>

<div class='innerPad'>

{form_declaration}

<h3>{lang:recipient}&nbsp; {name}</h3>
<h3>{lang:icq_number}&nbsp; {icq}</h3>

<h3>{lang:subject}</h3>
<p><input type="text" name="subject" value="" style='width:100%' maxlength="80" class="input" size="70" /></p>

<h3>{lang:message}</h3>
<p><textarea name='body' style='width:100%' class='textarea' rows='10' cols='90'></textarea></p>

<p><input type="submit" value="{lang:submit}"  class="submit" /></p>

<div class="paddedCenter"><a href="JavaScript:window.close();">{lang:close_window}</a></div>

</form>

</div>
</div>
EOF;
}
// END




}
// END CLASS
?>