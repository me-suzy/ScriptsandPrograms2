<?php
require("global.php");
$site['title'] = $evoLANG['title_main'];

$book = new Book;


switch($_GET['do'])
{
	case 'submit':
		if($_POST['addentry']) $content = $book->process_add();
	break;
	/* + ------------------------------------- + */
	case 'addentry':
		$content = $book->addentry();
	break;
	/* + ------------------------------------- + */
	case 'viewall':
		$content = $book->shout_viewall();
	break;
	/* + ------------------------------------- + */
	default;
		$content = $settings['mode'] == 'gb' ? $book->main() : $book->shout_main();
}

/* -------------------------------------------------------- */
$page->generate();
?>