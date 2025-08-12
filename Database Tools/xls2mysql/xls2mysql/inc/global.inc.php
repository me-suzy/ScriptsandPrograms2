
<?
$xls_dir="xls/"; // directory of Excel Files, with ending /

$process_page = "xls2mysql_step1.php";

function str_database_name($str)
{
 $str=strtolower(strtr($str,"*()!$'?: ,&+-/.¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ",
					"---------------SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy"));
  return $str;
}

function str_database_value($str)
{
$str  =str_replace("'","\\'",$str);
$str = "'" . $str . "'";
return $str;
}
?>
