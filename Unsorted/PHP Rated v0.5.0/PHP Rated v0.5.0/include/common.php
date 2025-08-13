<?

/*
 * $Id: common.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$sql = "
	select
		*
	from
		$tb_settings
";
$query = sql_query($sql);
while($array = sql_fetch_array($query)){
	$$array["name"] = $array["setting"];
}

$small_font = ($base_font_size - 2) . "px";
$medium_font =	$base_font_size . "px";
$large_font = ($base_font_size + 1) . "px";
$pp = 1;

if(!isset($sr)) $sr = 0;
if(!isset($cp)) $cp = 1;

/*
 * $Id: common.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>