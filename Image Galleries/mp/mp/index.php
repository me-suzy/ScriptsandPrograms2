<?php
$photoIndex = $_GET['i'];
if (!$photoIndex) $photoIndex=1;

function photos()
{
	ob_start();
	print "<ul>";
	$dh = opendir('gallery');
	while( $d = readdir($dh) )
	{
		if (preg_match('/^photo/',$d))
		{
			$path_parts = pathinfo($d);
			$i = $path_parts['extension'];
			print "<li><a href=?i=$i>";
			@include("gallery/photo.$i/thumb.html");
			print "</a></li>";
		}
	}
	print "</ul>";
	return ob_get_clean();
}


?>
<html>
<head>
	<style>
		ul {
			margin-left: 0px;
			padding-left: 0px;
			width: 50px;
		}
		ul li {
			width: 30px;
			height: 30px;
			padding: 1px;
			margin: 3px;
			list-style-type: none;
		}
		ul li a img {
			border: 0px;
		}
		body {
			position: relative;
			background-color: #512;
		}
		#box {
			display: block;
			position: absolute;
			z-index: 2;
			top: 16px;
			left: 50px;
		}
	</style>
</head>
<body>

<div style="float: left;">
<?=photos()?>
</div>

<div id=box><?include("gallery/photo.$photoIndex/photo.html");?></div>

</body>
</html>
