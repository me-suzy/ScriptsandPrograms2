<?php
$top=<<<TOP
<html><head><title>%%TITLE%%</title><meta http-equiv="Content-Type" content="text/html; charset=%%CHARSET%%"><LINK href="%%RF%%style/%%STYLE%%/style.css" type=text/css rel=stylesheet><SCRIPT LANGUAGE="JavaScript">
<!--
function JumpFun(form) {form.submit()}
function GoRef(ref) {if(window.navigator.appName!="Konqueror") window.location.hash=ref}
function FormSubmit(form,timint,extaction,parameter) {form.tint.value=timint;form.tint_h.value=timint;form.extact_h.value=extaction;form.param_h.value=parameter;form.submit()}
function FormVal(form,val) {form.param2_h.value=val;form.submit()}
function FormAct(form,act) {form.act_h.value=act;form.submit()}
function FormIdAct(form,id,act) {form.id_h.value=id;form.act_h.value=act;form.submit()}
function FormIdExt(form,id,extact) {form.id_h.value=id;form.extact_h.value=extact;form.submit()}
function FormIdExtParam(form,id,extact,param) {form.id_h.value=id;form.extact_h.value=extact;form.param_h.value=param;form.submit()}
function FormTimIdExt(form,timint,id,extact) {form.tint.value=timint;form.id_h.value=id;form.extact_h.value=extact;form.submit()}
function FormExt(form,extact) {form.extact_h.value=extact;form.submit()}
function FormFilter(form,name,value) {form.filter_prm.value=name + "=" + value;form.submit()}
function FormPict(form,form2,pictid,stat,type,module) {form2.graph.value=pictid + "=" + form.act.value + "=" + stat + "=" + type + "=" + module;form2.submit();}
function ListPos(form,val1,val2) {form.listp.value=val1 + "=" + val2;form.submit();}
//-->
</SCRIPT></head><body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" %%SCROLL%%>
<div align=center><a name="top"></a>
<form name="graph" method="post" action="%%RF%%graph.php" target="_blank">
<input type=hidden name=uname value="%%UNAME%%"><input type=hidden name=passw value="%%PASSW%%"><input type=hidden name=language value="%%LANG%%">
<input type=hidden name=style value="%%STYLE%%"><input type=hidden name=graph></form>
<form name=%%SCRIPT%% method="post" action="%%SCRIPT%%.php">
<input type=hidden name=uname value="%%UNAME%%"><input type=hidden name=passw value="%%PASSW%%"><input type=hidden name=language value="%%LANG%%"><input type=hidden name=style value="%%STYLE%%"><input type=hidden name=id_h value=%%ID%%>
<input type=hidden name=listp><input type=hidden name=act_h><input type=hidden name=oldact value="%%OLDACT%%"><input type=hidden name=tint_h><input type=hidden name=extact_h><input type=hidden name=param_h><input type=hidden name=param2_h value=%%P2%%>
<table height=57 width=750 border=0 cellspacing=0 cellpadding=0>
<tr height=57><td valign=bottom rowspan=2><SPAN class=f9>&nbsp;&nbsp;</SPAN><a href="%%SITE%%" target=_blank><img width=205 height=55 src="./style/%%STYLE%%/image/logo.gif" border=0 alt="ActualAnalyzer %%SERIES%% %%VER%%" title="ActualAnalyzer %%SERIES%% %%VER%%"></a></td>
<td width=400 height=52 align=right valign=bottom><div class=menu>%%VERSION%% %%VER%%&nbsp;&nbsp;%%UPDATE%%
&nbsp;&nbsp;<a href="%%SITE%%faq.html" target=_blank>%%FAQ%%</a>&nbsp;&nbsp;&nbsp;</div></td></tr>
<tr><td colspan=2 height=5><img src="%%RF%%style/%%STYLE%%/image/0.gif" width="1" height="1"></td></tr>
<tr><td colspan=3 bgcolor="#cccccc"><img src="%%RF%%style/%%STYLE%%/image/0.gif" width="1" height="1"></td></tr></table><br>

TOP;
?>