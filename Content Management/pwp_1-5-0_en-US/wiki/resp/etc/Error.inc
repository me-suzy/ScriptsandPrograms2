<?php
/**
 * The Response shown if an error occured. Queries core/ErrorStorage;
 * instantiated as $gError. Will show 1..n messages and provides
 * a back link and a link to the start page.
 *
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class Error extends Response {

	/** Reset error flag for response. */
	function Error( &$buffer, &$args ) {
		global $gError;
		$gError->mNoErrorOccured = TRUE; //otherwise Response will not work
		$this->Response( $buffer, $args );
	}

	function service( &$buffer, &$args ) {
		global $gError, $gConfig;

		echo <<< eoh
<div id='textcolumn' style='padding: 50,0,50,0;'>
	<p class="error">The last action caused the following error(s):</p>
	<ul class="error">
		{$gError->mErrors}
	</ul>
	<p class="error">Please go back or jump to the start page with the links provided below.</p>
	<p><a href="javascript:history.back()">Back&lt;&lt;</a></p>
	<p><a href="{$_SERVER['PHP_SELF']}?iRequest=wiki/ViewPage&amp;iPage={$gConfig->mProp['StartPage']}">Home&gt;&gt;</a></p>
	<br>
</div>
eoh;
		$args['Heading'] = 'Error';
		$this->changeState('out/Html');
		$gError->reset(); //ok errors printed, reset the storage
	}
}

?>