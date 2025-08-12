<?php
/**
 * This response contains the extra menu page.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Calendar.inc' );
require_once( BASE_PATH.'core/Statics.inc' );
require_once( BASE_PATH.'core/Report.inc' );

class Extra extends Response {

	function Extra( &$buffer, &$args ) {
		$this->Response( $buffer, $args, TRUE );
	}

	function service( &$buffer, &$args ) {
		global $gConfig;

		$args['Heading'] = "Extra";
		$this->changeState('out/Html');

		if (isset($args['extraStatus'])) {
			echo "<p><strong>{$args['extraStatus']}</strong></p>\n";
		}

		$exportMenu   = Statics::getMenu();
		$calendarMenu = Calendar::getMenu();
		$reportMenu   = Report::getMenu();

		echo <<<eoh
<h3>Export a Static Snapshot</h3>
$exportMenu

<h3>Report Operations</h3>
$reportMenu

<h3>Calendar Operations</h3>
$calendarMenu

<h3>Statistics</h3>
<ul>
	<li>[<a href='{$_SERVER['PHP_SELF']}?iRequest=extra/ViewStatistics'>View statistics</a>]</li>
</ul>

<h3>Housekeeping Operations</h3>
<ul>
	<li>[<a href='{$_SERVER['PHP_SELF']}?iRequest=extra/ClearCache'>Clear cache</a>]<br>
	    Removes the cached content completely.</li>
	<li>[<a href='{$_SERVER['PHP_SELF']}?iRequest=extra/DeleteLock'>Remove edit locks</a>]<br>
	    Removes all currenntly active edit locks.</li>
	<li>[<a href='{$_SERVER['PHP_SELF']}?iRequest=extra/DeleteHistory'>Remove old versions from history</a>]<br>
		Removes all revisions physically from the history which are older than {$gConfig->mProp['MinFileAge']} days.</li>
	<li>[<a href='{$_SERVER['PHP_SELF']}?iRequest=extra/DeleteTrash'>Remove files from trash</a>]<br>
Removes all files physically from the trash which are older than {$gConfig->mProp['MinFileAge']} days.</li>
</ul>

<h3>Edit Variables and InterWiki Links</h3>
<ul>
	<li>[<a href='{$_SERVER['PHP_SELF']}?iRequest=extra/EditConfig&amp;iSection=variables'>Edit variables</a>]</li>
	<li>[<a href='{$_SERVER['PHP_SELF']}?iRequest=extra/EditConfig&amp;iSection=interwikilinks'>Edit InterWiki links</a>]</li>
</ul>

<h3>About</h3>
<ul>
	<li>[<a href='{$_SERVER['PHP_SELF']}?iRequest=etc/Status'>PWP status information</a>]</li>
	<li>[<a href='{$_SERVER['PHP_SELF']}?iRequest=etc/License'>GNU GPL License for PWP</a>]</li>
</ul>
eoh;
	}

}

?>