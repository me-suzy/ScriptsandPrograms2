<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 345269;
function objfile_345269 () {
$obj = owNew('template');
$objdata['name'] = "standard_gallery_medium";
$objdata['content'] = "<center>
{if \$result.previd}<a href=\"{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_ext_objectid={\$result.previd}&_cmd=medium\">[<- {#previous#}]</a>&nbsp;&nbsp;{/if}
{if \$result.nextid}<a href=\"{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_ext_objectid={\$result.nextid}&_cmd=medium\">[{#next#} ->]</a>&nbsp;&nbsp;{/if}
{if \$config.usefull}
	<a href=\"{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_ext_objectid={\$request._ext_objectid}&_cmd=full\" {if \$config.fullnewwindow}target=\"_blank\"{/if}>[{#fullsize#}]</a>&nbsp;&nbsp;
	{elseif \$config.linktofull}
	<a href=\"getfile.php?objectid={\$request._ext_objectid}\" {if \$config.fullnewwindow}target=\"_blank\"{/if}>[{#fullsize#}]</a>&nbsp;&nbsp;
{/if}
<a href=\"{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_ext_index={\$result.index}&_cmd=index\">[{#back#}]</a>&nbsp;&nbsp;
<br><br>
<img src=\"{\$system.system_url}getfilethumb.php?objectid={\$request._ext_objectid}&auto={\$config.mediumsize}\">
</center>
{if \$config.commentpos == 1}
	<br>
	{if \$config.addcomment}
		{if \$post._ext_content != \"\"}
			{createobj type=\"comment\" parentid=\$post._ext_objectid content=\$post._ext_content name=\$post._ext_uname}
		{/if}
		<form method=\"post\" action=\"{\$me}\">
		<input type=\"hidden\" name=\"_ext\" value=\"{\$_ext}\">
		<input type=\"hidden\" name=\"_extcf\" value=\"{\$_extcf}\">
		<input type=\"hidden\" name=\"_ext_objectid\" value=\"{\$request._ext_objectid}\">
		<input type=\"hidden\" name=\"_cmd\" value=\"medium\">
		{if \$user.level != ACCESS_ANONYMOUS}
			{readobj objectid=\$user.objectid assign=\"u\"}
			<input type=\"hidden\" name=\"_ext_uname\" value=\"{if \$u.realname != \"\"}{\$u.realname}[else}{\$u.name}{/if}\">
		{/if}
		
		<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
		<tr>
		<th class=\"extheader\">&nbsp;</th>
		<th class=\"extheader\">&nbsp;{#header#}&nbsp;</th>
		</tr>
		{if \$user.level == ACCESS_ANONYMOUS}
			<tr>
				<td class=\"extcolorcell\" width=\"100\" valign=\"top\"><b>{#owner#}</b></td>
				<td class=\"extcolorcell\"><input type=\"text\" name=\"_ext_uname\" style=\"width: 95%\"></td>
			</tr>
		{/if}
		<tr>
			<td class=\"extcolorcell\" width=\"100\" valign=\"middle\"><b>{#content#}</b></td>
			<td class=\"extcolorcell\"><textarea name=\"_ext_content\" style=\"width: 95%; height: 100px;\"></textarea></td>
		</tr>
		<tr>
			<td class=\"extcolorcell\"></td>
			<td class=\"extcolorcell\"><input type=\"submit\" value=\"{#buttontext#}\" class=\"extbutton\"></td>
		</tr>
		</table>
		</form>
	{/if}
	
	
	{if \$config.listcomment}
		{listobj type=\"note\" parentid=\$request._ext_objectid assign=\"res\"}
		{if \$res|@sizeof != 0}
			<table class=\"exttablebg\" width=\"100%\" cellspacing=\"1\">
			<tr>
			<th class=\"extheader\">&nbsp;{#owner#}&nbsp;</th>
			<th class=\"extheader\">&nbsp;{#content#}&nbsp;</th>
			</tr>
			{section loop=\$res name=\"i\" step=-1}
				<tr>
					<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" width=\"100\" align=\"center\"></td>
					<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\"><div style=\"float: left\"><b>{\$res[i].name}</b></div><div style=\"float: right\">{#date#}: {\$res[i].object.created|date_format:\"%Y-%m-%d %H:%M:%S\"}</div></td>
				</tr>
				<tr>
					<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\" align=\"center\"></td>
					<td class=\"{if \$smarty.section.i.index is odd}extcolorcell{else}extlightcell{/if}\"><span class=\"extmsgbody\">{\$res[i].content}<br><br><br><br></span></td>
				</tr>
				{if not \$smarty.section.i.last}
					<tr><td class=\"extdarkcell\" style=\"height: 5px\" colspan=\"2\"></td></tr>
				{/if}
			{/section}
			<tr>
			<td class=\"extdarkcell\" colspan=\"10\"></td>
			</tr>
			</table>
		{/if}
	
	{/if}
{/if}";
$objdata['tpltype'] = "2";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = ".extcolorcell {
	PADDING-RIGHT: 4px; PADDING-LEFT: 4px; PADDING-BOTTOM: 4px; PADDING-TOP: 4px; BACKGROUND-COLOR: #dce1e5
}
.extlightcell {
        PADDING-RIGHT: 4px; PADDING-LEFT: 4px; PADDING-BOTTOM: 4px; PADDING-TOP: 4px; BACKGROUND-COLOR: #ececec
}
.extdarkcell {
	PADDING-RIGHT: 4px; PADDING-LEFT: 4px; PADDING-BOTTOM: 4px; PADDING-TOP: 4px; BACKGROUND-COLOR: #c0c8d0
}
.extheader {
	PADDING-RIGHT: 4px; PADDING-LEFT: 4px; FONT-WEIGHT: bold; FONT-SIZE: 85%; BACKGROUND-IMAGE: url(img/extbg.gif); COLOR: #ffffff; WHITE-SPACE: nowrap; HEIGHT: 24px; BACKGROUND-COLOR: #006699
}
.exttablebg {
	BACKGROUND-COLOR: #a9b8c2
}
.extmsgbody {
	LINE-HEIGHT: 140%
}
.extbutton {
background-color: #c0c8d0; font-style: bold; font-size: 80%; font-weight: bold;
}";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "previous = \"Previous\"
next = \"Next\"
fullsize = \"Full size\"
back = \"Back to index\"
header = \"Write comment\"
owner = \"Author\"
date = \"Date\"
subject = \"Subject\"
content = \"Message\"
buttontext = \"Send\" 

[DA]
previous = \"Foregående\"
next = \"Næste\"
fullsize = \"Fuld størrelse\"
back = \"Til oversigt\"
header = \"Skriv kommentar\"
owner = \"Forfatter\"
date = \"Skrevet\"
subject = \"Emne\"
content = \"Besked\"
buttontext = \"Send\" 
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
