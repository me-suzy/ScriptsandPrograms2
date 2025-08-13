<?php
require("./admin_common.php");
$PHP_SELF = $_SERVER['PHP_SELF'];
$book = new Book;

$site['title'] = "Admin Panel";

switch($_GET['do'])
{
	case "manage":
		$content = $book->admin_manage();
	break;
	/* ---------------------------------------------- */
	case "edit":
		$content = $book->admin_editentry($_GET['id']);
	break;
	/* ---------------------------------------------- */
	case "delete":
		$content = $book->admin_deleteentry($_GET['id']);
	break;
	/* ---------------------------------------------- */
	case "submit":
		if ($_POST['editentry']) $content = $book->process_edit();
	break;
	/* ---------------------------------------------- */
}
eval("echo(\"".$tpl->gettemplate("main",1)."\");");
?>