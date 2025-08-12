<?php
/**
 * This response closes most response chains. It surrounds the
 * buffer with HTML start and end. It contains the menu.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class Html extends Response {

	function Html( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gConfig, $gError;

		if (empty($args['Heading'])) {
			$args['Heading'] = $gConfig->mProp['HtmlTitle'];
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig;


		$menu = $this->_getMenu( $gConfig->mProp['MenuType'] );
		$goto = $this->_getGotoForm();
		$create = $this->_getCreateForm();
		if ( !empty($_SERVER['REMOTE_USER']) ) {
			$admin = ($this->isAdmin())?('(admin)'):('');
			$user = "<div align='right'><small>Logged in as: {$_SERVER['REMOTE_USER']} $admin</small></div>";
		} else {
			$user = '';
		}
		if ($gConfig->mProp['MenuType'] == 1) {
			$page = $this->getPageTopBottom($buffer, $args['Heading'], $menu, $goto, $create, $user);
		} elseif ($gConfig->mProp['MenuType'] == 2) {
			$page = $this->getPageLeft($buffer, $args['Heading'], $menu, $goto, $create, $user);
		} else {
			$page = $this->getPageRight($buffer, $args['Heading'], $menu, $goto, $create, $user);
		}


		echo <<<eoh
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
<html>
<head>
	<meta name="Keywords" content="{$gConfig->mProp['CommonKeywords']}">
	<meta name="Description" content="{$gConfig->mProp['CommonDescription']}">
	<meta name="Language" content="{$gConfig->mProp['Language']}">
	<meta name="Generator" content="PWP Wiki Processor">
	<meta name="robots" content="index,follow">
	<title>{$gConfig->mProp['HtmlTitle']} &raquo; {$args['Heading']}</title>
	{$gConfig->mProp["StyleSheets"]}
</head>
<body>
	$page
	{$gConfig->mProp['FooterLine']}
</body>
</html>
eoh;
	}

	/**
	 * Returns the standard layout with menu on top and at the bottom.
	 */
	function getPageTopBottom($buffer, $heading, $menu, $goto, $create, $user) {
		global $gConfig;
		return <<<eoh
<div id='uppermenu'>
	{$gConfig->mProp['LogoImg']}
	<h1>$heading</h1>
	$menu
	<table class='layout' border='0' cellspacing='2' cellpadding='2'>
	<tr>
		<td class='layout'>$goto</td><td class='layout'>$create</td>
	</tr>
	</table>
</div>
<div id='contents'>
	$buffer
</div>
<div id='lowermenu'>
	$menu
	<table class='layout' border='0' cellspacing='2' cellpadding='1'>
	<tr>
		<td class='layout'>$goto</td><td class='layout'>$create</td>
	</tr>
	</table>
	$user
</div>
eoh;
	}

	/**
	 * Returns the left menu layout.
	 */
	function getPageLeft($buffer, $heading, $menu, $goto, $create, $user) {
		global $gConfig;
		return <<<eoh
<table class='layout' border='0' cellspacing='10' cellpadding='6'>
<tr>
	<td id='logo'>{$gConfig->mProp['LogoImg']}</td>
	<td id='uppermenu'><h1>$heading</h1></td>
</tr>
<tr>
	<td id='leftmenu'>
		$menu
		<hr size='1' noshade style='color: #000000;'>
		$goto
		<hr size='1' noshade style='color: #000000;'>
		$create
		<hr size='1' noshade style='color: #000000;'>
		$user
	</td>
	<td id='contents'>
		$buffer
	</td>
</tr>
</table>
eoh;
	}

	/**
	 * Returns the right menu layout.
	 */
	function getPageRight($buffer, $heading, $menu, $goto, $create, $user) {
		global $gConfig;
		return <<<eoh
<table class='layout' border='0' cellspacing='10' cellpadding='6'>
<tr>
	<td id='uppermenu'><h1>$heading</h1></td>
	<td id='leftmenu'>{$gConfig->mProp['LogoImg']}</td>
</tr>
<tr>
	<td id='contents'>
		$buffer
	</td>
	<td id='leftmenu'>
		$menu
		<hr size='1' noshade style='color: #000000;'>
		$goto
		<hr size='1' noshade style='color: #000000;'>
		$create
		<hr size='1' noshade style='color: #000000;'>
		$user
	</td>
</tr>
</table>
eoh;
	}

	/** Returns the GoTo form beneath the menu. */
	function _getGotoForm() {
		return <<<eoh
<form action='{$_SERVER['PHP_SELF']}' method='post' class='search'>
	<input type='hidden' name='iRequest' value='navigation/FileSearchResult'>
	<input type='text' name='iSearch' size='15'>
	<input type='submit' value=' Goto ' title='List pages with matching name'>
</form>
eoh;
	}

	/** Returns the GoTo form beneath the menu. */
	function _getCreateForm() {
		return <<<eoh
<form action='{$_SERVER['PHP_SELF']}' method='post' class='search'>
	<input type='hidden' name='iRequest' value='wiki/CreatePage'>
	<input type='text' name='iPage' size='15'>
	<input type='submit' value=' Create ' title='Create a new page'>
</form>
eoh;
	}

	/** Returns the main menu. */
	function _getMenu( $menuType ) {
		global $gConfig;

		if ($menuType == 1) {
			$separator1 = '|';
			$separator2 = '-';
			$separator3 = '|';
		} else {
			$separator1 = '<br>&nbsp;&nbsp;';
			$separator2 = '<br>&nbsp;';
			$separator3 = '';
		}

		$fileDependent = '';
		if (!empty( $_REQUEST['iPage'] )) {
			if ($gConfig->mProp['UseJSLinks']) {
				$fileDependent = <<<eoh
<hr size='1' noshade style='color: #000000;'>
$separator3
<a href='{$_SERVER['PHP_SELF']}?iRequest=wiki/ViewPage&amp;iPage={$_REQUEST['iPage']}'><strong>{$_REQUEST['iPage']}</strong></a>
$separator2
<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=wiki/EditPage&amp;iPage={$_REQUEST['iPage']}"; return false;'>Edit</a>
$separator2
<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=wiki/QuickEditPage&amp;iPage={$_REQUEST['iPage']}"; return false;'>QuickEdit</a>
$separator2
<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=history/ViewDiff&amp;iPage={$_REQUEST['iPage']}";'>Diff</a>
$separator2
<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=history/HistoryList&amp;iPage={$_REQUEST['iPage']}";'>History</a>
$separator2
<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=navigation/BackLinks&amp;iPage={$_REQUEST['iPage']}";'>BackLinks</a>
$separator2
<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=wiki/RenameForm&amp;iPage={$_REQUEST['iPage']}";'>Rename</a>
$separator2
<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=wiki/CopyForm&amp;iPage={$_REQUEST['iPage']}";'>Copy</a>
$separator2
<a href='#' onclick='if (confirm("Erase page?")) {document.location="{$_SERVER['PHP_SELF']}?iRequest=wiki/DeletePage&amp;iPage={$_REQUEST['iPage']}"; } return false;'>Erase</a>
$separator3
eoh;
			} else {
				$fileDependent = <<<eoh
<hr size='1' noshade style='color: #000000;'>
$separator3
<a title='Reload {$_REQUEST['iPage']}' href='{$_SERVER['PHP_SELF']}?iRequest=wiki/ViewPage&amp;iPage={$_REQUEST['iPage']}'><strong>{$_REQUEST['iPage']}</strong></a>
$separator2
<a title='Edit {$_REQUEST['iPage']}' href='{$_SERVER['PHP_SELF']}?iRequest=wiki/EditPage&amp;iPage={$_REQUEST['iPage']}'>Edit</a>
$separator2
<a title='View and edit {$_REQUEST['iPage']}' href='{$_SERVER['PHP_SELF']}?iRequest=wiki/QuickEditPage&amp;iPage={$_REQUEST['iPage']}'>QuickEdit</a>
$separator2
<a title='Diff vs. previous version' href='{$_SERVER['PHP_SELF']}?iRequest=history/ViewDiff&amp;iPage={$_REQUEST['iPage']}'>Diff</a>
$separator2
<a title='Older versions of {$_REQUEST['iPage']}' href='{$_SERVER['PHP_SELF']}?iRequest=history/HistoryList&amp;iPage={$_REQUEST['iPage']}'>History</a>
$separator2
<a title='Show pages linking to {$_REQUEST['iPage']}' href='{$_SERVER['PHP_SELF']}?iRequest=navigation/BackLinks&amp;iPage={$_REQUEST['iPage']}'>BackLinks</a>
$separator2
<a title='Rename the page {$_REQUEST['iPage']}' href='{$_SERVER['PHP_SELF']}?iRequest=wiki/RenameForm&amp;iPage={$_REQUEST['iPage']}'>Rename</a>
$separator2
<a title='Create a copy of {$_REQUEST['iPage']}' href='{$_SERVER['PHP_SELF']}?iRequest=wiki/CopyForm&amp;iPage={$_REQUEST['iPage']}'>Copy</a>
$separator2
<a title='Move {$_REQUEST['iPage']} into trash bin' href='{$_SERVER['PHP_SELF']}?iRequest=wiki/DeletePage&amp;iPage={$_REQUEST['iPage']}' onclick='return confirm("Move into trash bin?");'>Erase</a>
$separator3
eoh;
			}
		}

		if (!$gConfig->mProp['UseJSLinks']) { //protect the extra link by JS; no robot access allowed
			return <<<eoh
$separator1<a href='{$_SERVER['PHP_SELF']}?iRequest=wiki/ViewPage&amp;iPage={$gConfig->mProp['StartPage']}'>{$gConfig->mProp['StartPage']}</a>
$separator1<a href='{$_SERVER['PHP_SELF']}?iRequest=navigation/ViewIndex'>WikiPages</a>
$separator1<a href='{$_SERVER['PHP_SELF']}?iRequest=upload/UploadList'>UploadedFiles</a>
$separator1<a href='{$_SERVER['PHP_SELF']}?iRequest=navigation/ViewRecentChanges'>RecentChanges</a>
$separator1<a href='{$_SERVER['PHP_SELF']}?iRequest=trash/TrashList'>TrashBin</a>
$separator1<a href='{$_SERVER['PHP_SELF']}?iRequest=navigation/FullTextSearchForm'>FullTextSearch</a>
$separator1<a href='{$_SERVER['PHP_SELF']}?iRequest=extra/Extra'>Extra</a>
$separator1<a href='{$_SERVER['PHP_SELF']}?iRequest=etc/License'>About</a>
$separator3
$fileDependent
eoh;
		} else {
			return <<<eoh
$separator1<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=wiki/ViewPage&amp;iPage={$gConfig->mProp['StartPage']}";return false;'>{$gConfig->mProp['StartPage']}</a>
$separator1<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=navigation/ViewIndex";return false;'>WikiPages</a>
$separator1<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=upload/UploadList";return false;'>UploadedFiles</a>
$separator1<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=navigation/ViewRecentChanges";return false;'>RecentChanges</a>
$separator1<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=trash/TrashList";return false'>TrashBin</a>
$separator1<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=navigation/FullTextSearchForm";return false;'>FullTextSearch</a>
$separator1<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=extra/Extra";return false'>Extra</a>
$separator1<a href='#' onclick='document.location="{$_SERVER['PHP_SELF']}?iRequest=etc/License";return false'>About</a>
$separator3
$fileDependent
eoh;
		}
	}

}

?>
