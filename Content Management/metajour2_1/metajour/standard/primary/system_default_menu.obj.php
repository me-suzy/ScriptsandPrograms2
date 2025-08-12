<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 346091;
function objfile_346091 () {
$obj = owNew('template');
$objdata['name'] = "system_default_menu";
$objdata['content'] = "<script type=\"text/javascript\" src=\"{\$system.system_url}js/jsdomenu_compressed.js\"></script>

<script type=\"text/javascript\">
{literal}
function createjsDOMenu() {
{/literal}
{document.ext.menu.main.execute}
{assign var=\"m\" value=\$document.ext.menu.main.result}

{* Loop all parentids, and create their corresponding Javascript *}
{section name=par loop=\$m[0].parentlist start=1}
  menu{\$m[0].parentlist[par]} = new jsDOMenu(150, \"absolute\");
{section name=i loop=\$m}
{if \$m[i].parent == \$m[0].parentlist[par]}
    menu{\$m[0].parentlist[par]}.addMenuItem(new menuItem(\"{\$m[i].description}\", \"item{\$m[i].objectid}\", \"{if \$m[i].urltype > 0}{\$m[i].url}{/if}\"));
{/if}
{/section}
{/section}

{* Loop all items *}
  menu = new jsDOMenu(150, \"absolute\", \"\", true);
{section name=i loop=\$m}
{* if the item is a top-level item, place the item below the menu. object *}
{if \$m[i].level == 1}
    menu.addMenuItem(new menuItem(\"{\$m[i].description}\", \"item{\$m[i].objectid}\", \"{if \$m[i].urltype > 0}{\$m[i].url}{/if}\"));
{/if}
{* if the item has a child, add the code to attach submenus to items *}
{if \$m[i].haschild == 1}
menu{if \$m[i].level > 1}\$m[i].parentid}{/if}.items.item{\$m[i].objectid}.setSubMenu(menu{\$m[i].objectid});
{/if}
{/section}
    menu.moveTo(10, 85);
    menu.show();
}
</script>

{* A small part of the styles are put here in the header, because we have som references to
   files placed in the metajour installation directory *}
<style type=\"text/css\">
<!--
{literal}
.jsdomenudiv {
	background-image: url({/literal}{\$system.system_url}{literal}js/office_xp_menu_left.png);
}
.jsdomenuarrow {
	background-image: url({/literal}{\$system.system_url}{literal}js/office_xp_arrow.png);
}
.jsdomenuarrowover {
	background-image: url({/literal}{\$system.system_url}{literal}js/office_xp_arrow_o.png);
}
.jsdomenubardiv {
	background-image: url({/literal}{\$system.system_url}{literal}js/office_xp_divider.png);
}
{/literal}
-->
</style>";
$objdata['tpltype'] = "1";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = "/*
Menu related selectors
*/
.jsdomenudiv {
	background-color: #FFFFFF;
	background-image: url(office_xp_menu_left.png);
	background-repeat: repeat-y;
	border: 1px solid #8A867A;
	cursor: default;
	padding-bottom: 1px;
	padding-top: 1px;
	position: absolute; /* Do not alter this line! */
	visibility: hidden;
	z-index: 10;
}

.jsdomenuitem {
	background: transparent;
	border: none;
	color: #000000;
	font-family: Tahoma, Helvetica, sans, Arial, sans-serif;
	font-size: 12px;
	padding-bottom: 3px;
	padding-left: 30px;
	padding-right: 15px;
	padding-top: 3px;
	position: relative; /* Do not alter this line! */
}

.jsdomenuitemover {
	background-color: #c0c8d0;
	border: 1px solid #316AC5;
	color: #000000;
	font-family: Tahoma, Helvetica, sans, Arial, sans-serif;
	font-size: 12px;
	margin-left: 1px;
	margin-right: 1px;
	padding-bottom: 2px;
	padding-left: 28px;
	padding-right: 15px;
	padding-top: 2px;
	position: relative; /* Do not alter this line! */
}

.jsdomenuarrow {
	background-image: url(office_xp_arrow.png);
	background-repeat: no-repeat; /* Do not alter this line! */
	height: 7px;
	position: absolute; /* Do not alter this line! */
	right: 8px;
	width: 4px;
}

.jsdomenuarrowover {
	background-image: url(office_xp_arrow_o.png);
	background-repeat: no-repeat; /* Do not alter this line! */
	height: 7px;
	position: absolute; /* Do not alter this line! */
	right: 8px;
	width: 4px;
}

.jsdomenusep {
	padding-left: 28px;
}

.jsdomenusep hr {
}

/*
Menu bar related selectors
*/
.jsdomenubardiv {
	background-color: #ECE9D8;
	background-image: url(office_xp_divider.png);
	background-position: left;
	background-repeat: no-repeat;
	border: 1px outset;
	cursor: default;
	padding-bottom: 3px;
	padding-left: 1px;
	padding-right: 1px;
	padding-top: 3px;
	position: absolute; /* Do not alter this line! */
	visibility: visible;
}

.jsdomenubardragdiv {
	cursor: move;
	display: inline;
	font-family: Tahoma, Helvetica, sans, Arial, sans-serif;
	font-size: 12px;
	padding-bottom: 2px;
	padding-left: 5px;
	padding-right: 5px;
	padding-top: 2px;
	position: relative; /* Do not alter this line! */
	visibility: hidden;
	width: 9px;
}

.jsdomenubaritem {
	background-color: #EFEDDE;
	border: none;
	color: #000000;
	display: inline;
	font-family: Tahoma, Helvetica, sans, Arial, sans-serif;
	font-size: 12px;
	padding-bottom: 2px;
	padding-left: 24px;
	padding-right: 10px;
	padding-top: 2px;
	position: relative; /* Do not alter this line! */
}

.jsdomenubaritemover {
	background-color: #C1D2EE;
	border: 1px solid #316AC5;
	color: #000000;
	display: inline;
	font-family: Tahoma, Helvetica, sans, Arial, sans-serif;
	font-size: 12px;
	padding-bottom: 2px;
	padding-left: 23px;
	padding-right: 9px;
	padding-top: 2px;
	position: relative; /* Do not alter this line! */
}

.jsdomenubaritemclick {
	background-color: #EFEDDE;
	border: 1px solid #8A867A;
	color: #000000;
	display: inline;
	font-family: Tahoma, Helvetica, sans, Arial, sans-serif;
	font-size: 12px;
	padding-bottom: 2px;
	padding-left: 23px;
	padding-right: 9px;
	padding-top: 2px;
	position: relative; /* Do not alter this line! */
}";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
