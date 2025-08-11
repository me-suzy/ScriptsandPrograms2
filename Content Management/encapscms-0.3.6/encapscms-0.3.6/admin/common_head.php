<html>
<head>
<title>Administrator</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<META content=0 http-equiv=Expires>
  <meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <meta HTTP-EQUIV="Cache-Control" content="no-cache">
<link href="html/admin.css" type="text/css" rel="stylesheet">
</head>
<html>
<body >

<div align="center">

<TABLE border="0" width="100%" cellspacing="0">
<tr>

<td><b>EphpCMS admin</b></td>

<td align="right" valign="bottom">

<?
$root = '../';
//(isset($root))?'../admin/':'./';
//echo '$root:'.$root;
$html["links"] = array(
		array("title"=>"blogs","href"=>"blogs.php",'page'=>'blogs'),
		array("title"=>"gallery","href"=>"gallery.php",'page'=>'gallery'),
		//array("title"=>"forum","href"=>$root.'forum/admin/','page'=>'forum'),
	);
$active_link="";
//var_dump($_SERVER["REQUEST_URI"]);

foreach ($html["links"] as $item) {
	if($page != $item['page'] ){
		$style="	
			color: #004080;
			text-decoration: underline;
			border: none;
			font-weight: bold;
			background-color: white;";
	}
	else {
		$style="	
			padding: 2px;
			color: white;
			text-decoration: none;
			font-weight: bold;
			background-color: #004080;";
		$active_link=$item["title"];
	}
	?>&nbsp;|&nbsp;<a href="<?=$item["href"]?>" style="<?=$style?>"><?=$item["title"]?></a><?
}?>&nbsp;|
</td>

</tr>
<tr><td colspan="2" bgcolor="#004080" height="10"></td></tr>
</TABLE>
<br>