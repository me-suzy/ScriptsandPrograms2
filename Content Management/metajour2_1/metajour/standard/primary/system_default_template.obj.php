<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 345377;
function objfile_345377 () {
$obj = owNew('template');
$objdata['name'] = "system_default_template";
$objdata['content'] = "<html>
<head>
<title>{\$system.title}</title>
{\$document.header}
{if \$request._print == '1'}
{literal}
<style type=\"text/css\">
<!--
#left {display: none}
#right {display: none}
#header {display: none}
#footer {display: none}
-->
</style>
{/literal}
{/if}
{if \$request._print != '1'}{include file=\"system_default_menu\"}{/if}
</head>
<body {if \$request._print != '1'}onload=\"initjsDOMenu()\"{/if}>
<div id=\"wrapper\">
  <div id=\"header\">{\$system.title}</div>

	<div id=\"contentwrapper\">
		<div id=\"left\">&nbsp;
		</div>
		<div id=\"content\">
			{if \$_system_force_password == \"yes\"}
				{include file=\"standard_password_dialog\"}
			{else}
				<!--METAZO_CONTENT_BEGIN-->
				{assign var=\"section\" value=\"0\"}
				{if \$request._section != ''}
				{assign var=\"section\" value=\$request._section}
				{/if}
				{if \$document.section[\$section].name != ''}<h1>{\$document.section[\$section].name}</h1>{/if}
				{if \$document.section[\$section].subname != ''}<h2>{\$document.section[\$section].subname}</h2>{/if}
				{\$document.section[\$section].content}
				<!--METAZO_CONTENT_END-->
			{/if}
		</div>
		<div id=\"right\">
{if \$setting.tools}
		{* Icons for print friendly page and for document editor *}
		<a href=\"{\$me}&_print=1\" target=\"_blank\"><img src=\"{\$system.system_url}standard/primary/img/print.png\"></a>
		{if \$user.level >= \$smarty.const.ACCESS_EDITOR}
		{getsysaccess objectid=\$document.objectid assign=\"res\"}
		{if \$res == 1}
		<img src=\"{\$system.system_url}standard/primary/img/edit.png\" onclick=\"window.open('{\$system.system_url}gui.php?view=editor&objectid={\$document.object.objectid}','','top=0,left=0,width=970,height=670,toolbar=0,location=0,status=1,scrollbars=1,resizable=1'); return false;\">
		{/if}
		{/if}
{/if}
{if \$setting.docinfo}
		{* Block with document information *}
		<div class=\"extblock\">
		<div class=\"extblockheader\">{#docinfo#}</div>
		{#created#}: {\$document.object.created}<BR>
		{#changed#}: {\$document.object.changed}<BR>
		{readobj objectid=\$document.object.changedby assign=\"u\"}
		{#editor#}: {\$u.name}<BR>
		</div>
{/if}
{if \$setting.related}
		{document.ext.related.none.execute}
		{\$document.ext.related.none.output}
{/if}
{if \$setting.latest}
		{document.ext.listlatestdoc.none.execute}
		{\$document.ext.listlatestdoc.none.output}
{/if}
{if \$setting.popular}
		{document.ext.listpopulardoc.none.execute}
		{\$document.ext.listpopulardoc.none.output}
{/if}
{if \$setting.search}
		{document.ext.search.none.execute}
		{\$document.ext.search.none.output}
{/if}
{if \$setting.login}
		{document.ext.login.none.execute}
		{\$document.ext.login.none.output}
{/if}
{if \$setting.online}
		{document.ext.online.none.execute}
		{\$document.ext.online.none.output}
{/if}
		</div>
	</div>

  <div id=\"footer\">IPW METAjour 2.1 &copy; IPW Systems A/S</div>

</div>
</body>
</html>";
$objdata['tpltype'] = "0";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = "body {
  font-family: Tahoma, Verdana;
  font-size: 0.7em;
  margin: 0px;
  padding: 0px;
}

h1 {
  font-family: Tahoma, Verdana;
  font-size: 1.5em;
}

h2 {
  font-family: Tahoma, Verdana;
  font-size: 1.2em;
}

textarea {
  font-family: Tahoma, Verdana;
  font-size: 1em;
}

img {
margin: 3px;
}

input {
  font-size: 1.1em;
}

a img {
  border: 0px none;
}

a {
  text-decoration: none;
  font-weight: bold;
  color: #006699;
}

a:visited {
  text-decoration: none;
  font-weight: bold;
  color: #006699;
}

a:active {
  text-decoration: none;
  font-weight: bold;
  color: #006699;
}

a:hover {
  text-decoration: underline;
  font-weight: bold;
  color: #006699;
}

.extblock {
MARGIN-BOTTOM: 4px;
}
.extblockheader {
	PADDING-TOP: 4px; PADDING-BOTTOM: 4px; MARGIN-BOTTOM: 2px;
	FONT-WEIGHT: bold; FONT-SIZE: 125%; COLOR: #000000; WHITE-SPACE: nowrap;
	BORDER-BOTTOM: 1px dashed #000000;
}

#wrapper {
min-width: 980px;
}

#content {
width: 600px;
float: left;
padding-top: 10px;
line-height: 1.5em;
}
#left {
width: 200px;
float: left;
}
#right {
width: 180px;
float: right;
}

#header {
background-color: #eeeeee;
height: 55px;
text-align: center;
font-size: 24px;
font-weight: bold;
letter-spacing: 3px;
padding-top: 20px;
}

#footer {
background-color: #eeeeee;
height: 45px;
text-align: center;
padding-top: 30px;
clear: both;
}

@media print {
#left {display: none}
#right {display: none}
#header {display: none}
#footer {display: none}
}";
$objdata['param'] = "name=tools;inputtype=checkbox;label=Show tools
name=docinfo;inputtype=checkbox;label=Show document info
name=related;inputtype=checkbox;label=Show related pages
name=latest;inputtype=checkbox;label=Show latest pages
name=popular;inputtype=checkbox;label=Show popular pages
name=search;inputtype=checkbox;label=Show search form
name=login;inputtype=checkbox;label=Show login form
name=online;inputtype=checkbox;label=Show number online";
$objdata['setting'] = "a:8:{s:5:\"tools\";s:1:\"1\";s:7:\"docinfo\";s:1:\"1\";s:7:\"related\";s:1:\"1\";s:6:\"latest\";s:1:\"1\";s:7:\"popular\";s:1:\"1\";s:6:\"search\";s:1:\"1\";s:5:\"login\";s:1:\"1\";s:6:\"online\";s:1:\"1\";}";
$objdata['config'] = "created = \"Created\"
changed = \"Changed\"
editor = \"Editor\"
docinfo = \"Document info\"

[DA]
created = \"Oprettet\"
changed = \"Redigeret\"
editor = \"Redaktør\"
docinfo = \"Dokument information\"";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
$obj->setDefault();
return $obj;
}
?>
