<?include("modules/inc/global.inc.php")?>
<?include("modules/inc/db_mysql.inc.php")?>
<?
$xmlPath = dirname(__FILE__) . "/";
include("modules/action/xml_read_schema.inc.php");
include("modules/action/xml_read_language.inc.php");
include("modules/display/html_functions.inc.php");

$db=new DB_Sql;
// check db connection
$arr_existing_db_tables=array();
$db->query("show tables");
while ($db->next_record())
	{
	$arr_existing_db_tables[$db->f(0)]=array();
	}
?>
<html>
<head>
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<script src="jss/general.js"></script>
<script src="jss/url_context.js"></script>
<script src="jss/image_select.js"></script>
<LINK HREF="jss/style.css" REL="stylesheet" TYPE="text/css">
<title>webXadmin v2 <?=$g_t!=""?"(working on table $g_t)":""?></title>
</head>
<body><div id="dhtmltooltip"></div>
	<script src=jss/tooltip.js></script>
	<script>document.onmousemove=positiontip</script>
	<div id=container>
<?

$xpath_d_tables =  '/d_table';
 $ctx = xpath_new_context($d_schema);
 $str_tables=array();
 $arr_tables=array();
 if ($xpathObj = @$ctx->xpath_eval($xpath_d_tables))
	 {
	if ($g_t=="") $g_t=$xpathObj->nodeset[0]->get_attribute("name");
	  while(list($index, $d_table) = each($xpathObj->nodeset))
	  	{
			include("modules/action/nodeset_read_table.inc.php");
			if (!isset($arr_existing_db_tables[t_name($name)]))
				add_notice_message(sprintf(msg("sql_no_table"), t_name($name), $db->Database));
			$class="menu_table_entry";
			if ($g_t==$name) $class .= "_selected";
			$link = "<a class=$class href=javascript:goto_simple_href('t=$name')>";
			$str_tables[] ="<li>". $link . $caption . "</a></li>";
		}
	 
	 }
?>

<div id=menu_tables><?=join ("",$str_tables)?></div>



<?
$record_form_name = "frm_edit_" . $g_t;
$xpath_d_fields =  "/d_table[@name='$g_t']/d_field";

 if ($xpathObj = @$ctx->xpath_eval($xpath_d_fields))
 	{
	//sql
	reset($xpathObj->nodeset);
	while(list($index, $d_field) = each($xpathObj->nodeset))
		{
		include("modules/action/nodeset_read_field.inc.php");
		include("modules/action/arr_sql.inc.php");
		$g_arr_fields_att[$arr_field_attr["name"]]=$arr_field_attr;
		}
	
	//execute proper sql before form
	include("modules/action/sql_exec.inc.php");
	?>
	<div id=buttons>
	<?include("modules/display/html_buttons.inc.php")?>
	<?include("modules/display/html_paging.inc.php")?>
	</div>
	
	<?//form
	reset($xpathObj->nodeset);
	while(list($index, $d_field) = each($xpathObj->nodeset))
		{include("modules/action/nodeset_read_field.inc.php");
		include("modules/action/arr_frm.inc.php");}
	}
?> 

<?
if ($g_a=='frm' || 
	$g_a=="enr")
		 include("modules/display/html_frm.inc.php");

if ($g_a=='' ||
	$g_a=='enr_close' || ($g_i!="" && $arr_tables[$g_t]["parent_id_ref"]!=""))
		 include("modules/display/html_lst.inc.php");

if ($g_a=='sql')
		 include("modules/display/html_sql.inc.php");
?><?include("modules/display/html_messages.inc.php")?>
<div id=bottom_ad><a href=http://webxadmin.free.fr>this is webXadmin (v2 . 2005/07/07)</a>. 
<a href=http://webxadmin.free.fr/article/changelog-for-webxadmin-191.php>Changelog</a> - 
<a href=http://webxadmin.free.fr/article/webxadmin-suggestions-and-comments-236.php>Suggestions & comments</a> - 

<a href=http://webxadmin.free.fr/article/would-you-like-to-contribute-238.php>Contributions welcome</a> - 
<a href=http://webxadmin.free.fr/article/webxadmin-commercial-program-237.php>Custom development</a></div>
</div>
</body>
</html>
