<?php
include 'conf.inc.php';
include 'j_oocms.inc.php';

$path = '../gallery';

$state = $_REQUEST['state'];
$highest = $_REQUEST['highest'];

if ($highest && !preg_match('/^[0-9]+$/', $highest))
{
	print "That's not a valid number.";
	exit;
}

switch( $state )
{
	case 'addPhoto':
		mkdir("$path/photo.$highest");
		refresh();
		break;
	default:
		$content = listOfPhotos($path);
		break;
}

//-------------------------------------------
function listOfPhotos($path)
{
	global $highest;
	ob_start();
	print "<ul>";
	$dh = opendir($path);
	while( $d = readdir($dh) )
	{
		if (preg_match('/^photo/',$d))
		{
			$path_parts = pathinfo($d);
			$i = $path_parts['extension'];
			print "<li><a href=photos.php?i=$i>$d</a>";
			@include("../gallery/photo.$i/thumb.html");
			print "</li>";
			if ($i > $highest) $highest=$i;
		}
	}
	print "</ul>";
	return ob_get_clean();
}
?>
<html>
	<style>
		ul li {
			width: 20%;
			border: 1px solid black;
			padding: 3px;
			margin: 3px;
			list-style-type: none;
		}
	</style>
</html>
<?include('nav.inc.php');?>
<?=$content?>
<p>
<a href=?state=addPhoto&highest=<?=$highest+1?>>Add Photo</a>
<?include('nav.inc.php');?>
