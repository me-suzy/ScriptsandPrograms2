<?
if ($page == "page1"){ // "page1" can be set at what ever u want :)
	$a = 1; 
	$file = 1; // $file is the file which can be included :)
}elseif ($page == "page2"){ 
	$b = 1;
	$file = 2;
}elseif ($page == "page3"){
	$c = 1;
	$file = 3;
}elseif ($page == "page4"){
	$d = 1;
	$file = 4;
}elseif ($page == "page5"){
	$e = 1;
	$file = 5;
}elseif ($page == "page6"){
	$f = 1;
	$file = 6;
}else {
	$a = 1;
	$file = 1;
}

function nav($text,$unf,$num){
	if ($unf == "1"){
		echo "<td bgcolor=#f8f8f8 width=100 height=16 rowspan=2 align=center><a href=\"unfnav.php?page=page".$num."\">".$text."</a></td>";
	}else {
		echo "<td bgcolor=#e9e9e9 width=100 height=15 align=center><a href=\"unfnav.php?page=page".$num."\">".$text."</a></td>";
	}
}

function nav2($bla){
	if ($bla == "1"){
		echo "";
	}else {
		echo "<td bgcolor=black width=100 height=1></td>";
	}
}

?>
<html>
<head>
<style>
a {
	text-decoration: none;
	font-size: 11px;
	font-family: verdana;
	color: blue;
	font-weight: bold;
}
</style>
</head>
<body>
<table cellspacing=0 cellpadding=0 border=0 align=center><tr><td bgcolor=black>
<table cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td bgcolor=black width=1 height=1></td>
		<td bgcolor=black width=100 height=1></td>
		<td bgcolor=black width=1 height=1></td>
		<td bgcolor=black width=100 height=1></td>
		<td bgcolor=black width=1 height=1></td>
		<td bgcolor=black width=100 height=1></td>
		<td bgcolor=black width=1 height=1></td>
		<td bgcolor=black width=100 height=1></td>
		<td bgcolor=black width=1 height=1></td>
		<td bgcolor=black width=100 height=1></td>
		<td bgcolor=black width=1 height=1></td>
		<td bgcolor=black width=100 height=1></td>
		<td bgcolor=black width=1 height=1></td>
	</tr>
	<tr>
		<td bgcolor=black width=1 height=15></td>
		<? nav(".: page 1 :.",$a,"1"); ?>
		<td bgcolor=black width=1 height=15></td>
		<? nav(".: page 2 :.",$b,"2"); ?>
		<td bgcolor=black width=1 height=15></td>
		<? nav(".: page 3 :.",$c,"3"); ?>
		<td bgcolor=black width=1 height=15></td>
		<? nav(".: page 4 :.",$d,"4"); ?>
		<td bgcolor=black width=1 height=15></td>
		<? nav(".: page 5 :.",$e,"5"); ?>
		<td bgcolor=black width=1 height=15></td>
		<? nav(".: page 6 :.",$f,"6"); ?>
		<td bgcolor=black width=1 height=15></td>
	</tr>
	<tr>
		<td bgcolor=black width=1 height=1></td>
		<? nav2($a); ?>
		<td bgcolor=black width=1 height=1></td>
		<? nav2($b); ?>
		<td bgcolor=black width=1 height=1></td>
		<? nav2($c); ?>
		<td bgcolor=black width=1 height=1></td>
		<? nav2($d); ?>
		<td bgcolor=black width=1 height=1></td>
		<? nav2($e); ?>
		<td bgcolor=black width=1 height=1></td>
		<? nav2($f); ?>
		<td bgcolor=black width=1 height=1></td>
	</tr>
	<tr>
		<td bgcolor=black width=1></td>
		<td bgcolor=#f8f8f8 colspan=11 align=center><br><font size=5 face=verdana>
		<b>Page 
			<? 
			echo $file; // it can be changed to include($file); :)
			?> 
		</b>
		</font><br><br>
		</td>
		<td bgcolor=black width=1></td>
	</tr>
	<tr>
		<td bgcolor=black height=1 colspan=13></td>
	</tr>
</table>
</td></tr></table>

</body>
</html>