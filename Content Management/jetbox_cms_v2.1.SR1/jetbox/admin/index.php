<?
$thisfile="/index.php";
require("../includes/includes.inc.php");
if($install_jetbox==true){
	header("Location: ../index.php"); /* Redirect browser */
	/* Make sure that code below does not get executed when we redirect. */
	echo "&nbsp;";
	exit;
}
ob_start();

authenticate();
$containera = ob_get_contents(); 
ob_end_clean();
function listrecords(){
	 header("Location: cms/"); /* Redirect browser */
	/* Make sure that code below does not get executed when we redirect. */
	echo "&nbsp;";
	exit;
}

echo $containera;
jetstream_footer();