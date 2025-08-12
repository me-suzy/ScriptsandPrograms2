<?include("modules/inc/global.inc.php")?>
<?include("modules/inc/db_mysql.inc.php")?>
<head></head>
<body>
<div id="dhtmltooltip"></div>
	<script src=jss/tooltip.js></script>
	<script>document.onmousemove=positiontip</script>
<script src="jss/url_context.js"></script>
<LINK HREF="jss/style.css" REL="stylesheet" TYPE="text/css">
<div id=buttons><?include("modules/display/html_buttons.inc.php")?></div>
<?
$xmlPath = dirname(__FILE__) . "/";
include("modules/action/xml_read_schema.inc.php");
?><div id=sql>
This page can help you sort basic configuration issues


<ul>
<li><b>WEBXADMIN REQUIREMENTS</b></li>
<ul>

<li>MYSQL / PHP 4.3 with domxml.<br>
<a href=javascript:goto_href('a=php_info')>Click here to display  <b>PHP configuration</b></a></li></ul>

<li><b>DATABASE / XML configuration issues</b>
 <ul>
 <li>If you can't connect to your database : check out <b>modules/inc/db_mysql.inc.php</b>
<li>If you have an existing database and do not wish to write a whole xml file. <br>
<a href=javascript:goto_href('a=create_xml')>Click here to generate the  <b>xml d_schema object from tables in your database</b></a></li>
<li>If you have no database tables yet and  would like to generate them from the existing xml d_schema in xml/my_tables.xml<br>
<a href=javascript:goto_href('a=create_sql')>Click here to generate the <b>sql table creation scripts</b></a></li>
</ul>
</li></ul>
</div>
<?if ($g_a=="php_info")
{echo "<div id=sql>";
echo "<style>table {border-collapse: collapse;}
.center {text-align: center;}
.center table { margin-left: auto; margin-right: auto; text-align: left;}
.center th { text-align: center; !important }
td, th { border: 1px solid #525A73; font-size: 75%; vertical-align: baseline;}
h1 {font-size: 150%;}
h2 {font-size: 125%;}
.p {text-align: left;}
.e {background-color: #ccccff; font-weight: bold;}
.h {background-color: #9999cc; font-weight: bold;}
.v {background-color: #cccccc;}
i {color: #666666;}
hr {width: 600px; align: center; background-color: #cccccc; border: 0px; height: 1px;}</style>";
ob_start();
phpinfo();
$info = ob_get_contents();
ob_end_clean();
preg_match_all("=<body[^>]*>(.*)</body>=siU", $info, $tab);
$phpinfo = $tab[1][0];
echo $phpinfo;
	echo "</div>";
}
if ($g_a=="create_xml")
{echo "<div id=sql>";
echo "<div id=sql>WARNING : webxadmin currently works with an autoincrement unique identifier with the name <b>id</b>. <li>If you don't have this field in your tables webxadmin won't work unless you create it.
<li>If your tables have such an id field, it won't appear in the xml schema, that's normal (it is automatically added by webxadmin)</div>";
$db=new DB_Sql;


// here write the correspondance between sql types (key) and xml types (value)
$xml_type["varchar"]="";
$xml_type["text"]="texthtml";


$db->query("show tables");
$arr_tables=array();
$str=array();
while ($db->next_record())
{
 $arr_tables[] = $db->f(0);
}
//echo join ("<BR>", $arr_tables);
reset ($arr_tables);
$arr_cols=array();
while (list($k,$v)=each($arr_tables))
 {
 $db->query("show columns from " . $v);
 while ($db->next_record())
  {
  $arr_cols[$v][] = array("name"=>$db->f(0),"type"=>$db->f("1") );
  
  }
 }
 
reset ($arr_tables);
while (list($k,$v)=each($arr_tables))
 {
 $str[] = '<d_table  caption="' . $v . '" name="' . $v . '">';

  reset($arr_cols[$v]);
  while (list($l,$m)=each($arr_cols[$v]))
   {
   if ($m["name"]!="id")
 $str[] = str_repeat(" ",10) . '<d_field caption="" name="' . $m["name"]  . '" must_be="0" type="' . $xml_type[$m["name"]]. '"/>';

 }
 $str[] = '</d_table>';
 }
echo str_replace("  " ," &nbsp; ", nl2br(htmlentities(join("\n",$str))));
echo "</div>";
}//end action = create_xml


if ($g_a=="create_sql")
{
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
	    	$arr_tables[$name]=array( $caption,$parent_id_ref);
			$class="menu_table_entry";
			if ($g_t==$name) $class .= "_selected";
			$link = "<a class=$class href=javascript:goto_simple_href('t=$name')>";
			$str_tables[] ="<li>". $link . $caption . "</a></li>";
		}
	 
	 }
	 
reset($arr_tables);
while (list($g_t, $attributes) = each($arr_tables))
	{
	$str_fields_frm=array();
$arr_fields_lst=array();

$xpath_d_fields =  "/d_table[@name='$g_t']/d_field";

 if ($xpathObj = @$ctx->xpath_eval($xpath_d_fields))
 	{
	while(list($index, $d_field) = each($xpathObj->nodeset))
		{
			include("modules/action/nodeset_read_field.inc.php");
			include("modules/action/arr_sql.inc.php");
		}
	 include("modules/display/html_sql_create.inc.php");
	}
	
	}
	
} //end action create
?>