<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include ("config.php");
include ("settings.inc.php");

$err = "";
$succ = "";

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) ) {
	
	include_once ("header.inc.php");
	echo "<script language=\"javascript\">window.location=\"login.php\"</script>";
	abcPageExit();
}

if( isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) ) {

	if( $_POST['submit'] == "Backup" ) {
					
		$succ = SaveDbDumpFile( $dbname );
		exit;
		
	}
	else if( $_POST['submit'] == "Restore" ) {
						
		if ( !$err = RestoreDbFromFile( $db , $_FILES['backup_file']['tmp_name'] ) )
			$succ = $lng[904] . "<br>";
		
	}

}

include_once ("header.inc.php");

echo"<h2>".$lng[327].": \"$dbname\"</h2><blockquote>";

if ( $_SESSION["demo"] )
	echo "<p><font color='red'>".$lng[286]."</font></p>";

//

if ( $err )
	echo "<p><font color='red'>".$err."</font></p>";
	
if ( $succ )
	echo "<p><font color='navy'>".$succ."</font></p>";

?>

<p>
<?= $lng[905] ?>
<br>
<?= $lng[906] ?>
<br>
<form action="" method="post">
<input type="submit" name="submit" value="<?= $lng[907] ?>">
</form>

<hr size='1' noshade>

<p>
<?= $lng[908] ?>
<br>
<b><?= $lng[909] ?></b>

<form action="" method="post" enctype="multipart/form-data">
<input type='file' name='backup_file'>
<input type="submit" name="submit" value="<?= $lng[910] ?>">

</form>

<?php

include_once('footer.inc.php');

?>
