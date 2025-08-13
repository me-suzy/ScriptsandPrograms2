<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>$site[name] - $site[title]</title>
<style type='text/css'>
$cssfile
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
$layout[head]
</head>
<body>

<h1 class="box">$subject</h1>
$evoLANG[by] : <a href="$_SERVER[PHP_SELF]?author/id:$author[id]"><b>$author[username]</b></a><br />
$evoLANG[rating] : $article[rating] <br />

<!-- $evoLANG[views] : $article[views]<br /> -->

<br />
<br />

<div id="article">
$article[article]
</div>

<br style="clear:both" />
<br style="clear:both" />

$article[customfields]
$author_box
<br />
</body>
</html>



