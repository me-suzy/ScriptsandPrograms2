<?php
include 'conf.inc.php';
include 'j_oocms.inc.php';
session_start();

if ($_GET['i'])
{
	$photoIndex = $_GET['i'];
	$_SESSION['photoIndex'] = $photoIndex;
}
else
{
	$photoIndex = $_SESSION['photoIndex'];
}

$path = '../gallery';
$filename = 'photo.'.$photoIndex;
$formPHP = '
<form enctype="multipart/form-data" method=post>
	<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
	Upload a 400x500 photo for the gallery.
	<input type=file name=photo /><br />
	Caption <input name=caption type=text value="$caption" /><br />
	<input type=submit value=save name=state />
</form>
';
$HTML = '
	<img src="$photo"><br />
	$caption
';
$THUMB = '<img src="$thumb" border=0>';

$state = $_REQUEST['state'];
$a['caption'] = $_REQUEST['caption'];

$cms = new Photo( $path, $filename, $formPHP, $urlRoot, 30, 30 );

switch( $state ) 
{
	case 'edit':
		$cms->load();
		$content = $cms->editForm();
		break;
	case 'save':
		$version = $cms->save( $a );
		$cms->generateFile( $HTML, 'photo.html' );
		$cms->generateFile( $THUMB, 'thumb.html' );
		$cms->export( $version );
		refresh();
		break;
	default:
		$content = propsAsTable($cms->load());
		$content .= editButton();
		$state = 'view';
		break;
}

?>
<?include('nav.inc.php');?>
<h1><?=$state?> <?=$filename?></h1>
<?=$content?>
<?include('nav.inc.php');?>
