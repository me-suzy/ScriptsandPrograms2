<?php
/**
 * This response creates a new and empty wiki page.
 * Pages need to become created, they aren't created
 * implicitely upon first 'save'.
 * Checks for valid name.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Cache.inc' );
require_once( BASE_PATH.'core/Trash.inc' );

class CreatePage extends Response {

	function CreatePage( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		if (empty($_REQUEST['iPage'])) {
			$gError->add("Missing parameter.");
		} elseif (!Data::isValidFileName($_REQUEST['iPage'])) {
			$gError->add("The submitted page name is empty or contains invalid characters. Do not use &lt; &gt; * | etc.");
		} else if (Data::fileExists($_REQUEST['iPage'])) {
			$gError->add("This page does already exists and cannot be created: {$_REQUEST['iPage']}");
		} else if ( strpos(",{$_REQUEST['iPage']}", DATA_EXTENSION) ) {
			$gError->add("This page name contains an invalid substring: {$_REQUEST['iPage']}");
		}
	}

	function service( &$buffer, &$args ) {
		global $gError;

		//cannot create a file that still exists in trash:
		//would make wipe out possible: (re-)create empty page and move it to trash;
		//overwrite trashed file
		if (!Trash::fileExists( $_REQUEST['iPage'].DATA_EXTENSION )) {

			if (Data::make($_REQUEST['iPage'])) {
				Cache::clearNumerical( INDEX_PAGE_NAME );
				$this->changeState('wiki/EditPage');
			} else {
				$gError->add("Could not create new Wiki page: {$_REQUEST['iPage']}");
			}

		} else {
			$args['Heading'] = "Create Conflict: {$_REQUEST['iPage']}";
			$this->changeState('out/Html');

			$ext = DATA_EXTENSION;
			echo <<<eoh
$buffer
<p>
The page <strong>{$_REQUEST['iPage']}</strong> was moved into the trash bin.
You cannot create a page with this name. But you may unerase this page and
edit its contents.
</p>

<ul>
<li>
<a href='{$_SERVER['PHP_SELF']}?iRequest=trash/ReActivate&amp;iTrashPage={$_REQUEST['iPage']}$ext'>[Unerase {$_REQUEST['iPage']}]</a>
</li>
<li>
<a href='{$_SERVER['PHP_SELF']}?iRequest=trash/TrashList'>[List files in the trash bin]</a>
</li>
</ul>

eoh;
		}
	}

}

?>