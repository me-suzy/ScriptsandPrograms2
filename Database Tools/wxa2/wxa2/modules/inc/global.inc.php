<?
//config
$g_table_prefix="webxadmin_";
$g_language="en";
$nb_rows_per_page=20;

//filenames
$app_url["front"]="http://webxadmin.free.fr";
$app_url["webxadmin"]="webxadmin.php";
$app_url["help"]="help.php";

//global variables
$arr_notice_messages=array();
$arr_messages=array();
$g_arr_fields_att=array();

$g_self = $_SERVER["PHP_SELF"];
$arr_g_self = split("/",$g_self);

//query variables
$arr_post=$_POST;
$g_t=$_GET["t"]; // table
$g_a=$_GET["a"]; // action
$g_i=$_GET["i"]; // row id
$g_p=$_GET["p"]; //  page
$g_o=$_GET["o"]; //list sort order
$g_od=$_GET["od"]; // asc or desc
$g_d=$_GET["d"]; // debug

if ($g_i=="") $g_i=$arr_post["id"];
if ($g_t=="") $g_t=$arr_post["t"];

function add_notice_message($str)
{
global $arr_notice_messages;
array_push($arr_notice_messages, "<li>$str</li>");
}

function msg($id)
{
global $arr_messages;
if (isset($arr_messages[$id]))
	return $arr_messages[$id];
else
	return "[msg:$id]";
}

//returns table name with prefix
function t_name($name)
{global $g_table_prefix;
return $g_table_prefix . $name;}

function parent_where($parent_field, $parent_value)
{
if ($parent_value=="")
   $str_where = $parent_field . "='' or " . $parent_field . " is null " ;
else
	$str_where = $parent_field . " = " . $parent_value;
return $str_where;
}
?>
