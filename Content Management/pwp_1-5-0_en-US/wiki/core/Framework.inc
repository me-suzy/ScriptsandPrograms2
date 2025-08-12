<?php
/**
 * A root class for every response. A response or a chain of responses
 * matches a request. It is an abstract base class and uses a template pattern.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class Response {

	/** Indicates state change request fired somewhere in the logic of response. */
	var $_mNewState = NULL; //must be NULL or new state

	/**
	 * Constructs and runs a response. Checks the input and
	 * generates output or displays an error message.
	 * Access restriction works with the server provided $_SERVER['REMOTE_USER']
	 * and a config entry.
	 */
	function Response( &$buffer, &$args, $isRestricted = FALSE ) {
		global $gError, $gEndTime;

		//mark the script execution time limit if not set, save it on global space
		if (empty($gEndTime)) {
			$gEndTime = ini_get('max_execution_time');
			$gEndTime = time() + ((empty($gEndTime))?(28):($gEndTime - 2)); //had some problems with this under PHP 5
		}

		if ($isRestricted and !$this->isAdmin()) {
			$gError->add('This action requires special user rights which are not assigned to your account.');
		}
		if ( $gError->mNoErrorOccured ) {
			//check input params now
			$this->verify( $args );

			if ( ($gError->mNoErrorOccured) and ($this->_mNewState == NULL) ) {
				//no errors, no state change requests yet; continue work
				$this->service( $buffer, $args );
			}
		}
		//not only else; inner 'if' above can trigger errors
		if ( !$gError->mNoErrorOccured ) {
			$this->changeState('etc/Error');
		}
	}

	/**
	 * Requests a state change, i.e. another class gets loaded. May be called from inside
	 * service() and verify(). Will be ignored (overridden) if $gError->add() is called.
	 * If params are required for the new state, use the args-array in verify() or service().
	 */
	function changeState( $newRequest ) {
		$this->_mNewState = $newRequest;
	}

	/** Returns a new state or NULL. Called by run.php*/
	function getNewState() {
		return $this->_mNewState;
	}

	/**
	 * Abstract function. The implementation of the response will
	 * verify the supplied parameters and call $gError->add() if
	 * necessary. This is also the right place for input data conversions
	 * like string to date.
	 *
	 * @param $args: Array of arguments passed by previous responses from earlier states.
	 *				 New arguments (parameters) may be added. They will be available in service().
	 */
	function verify( &$args ) {}

	/**
	 * Abstract function. The implementation of the response will
	 * store the logic in this function.
	 *
	 * @param $args: Array of arguments passed by previous responses from earlier states.
	 *				 New arguments (parameters) may be added. They will be available in the
	 *				 next response if a state change takes place.
	 *
	 * @param $buffer: The complete output (echo, print) from earlier responses; an empty string is
	 *				   used for the first response.
	 */
	function service( &$buffer, &$args ) {}

	/**
	 * New in 1.5.0. Returns TRUE if:
	 * - $gConfig->mProp['Admins'] is not defined or empty (everyone is admin)
	 * - $_SERVER['REMOTE_USER'] is contained in $gConfig->mProp['Admins']
	 * $gConfig->mProp['Admins'] is a comma separated list of user names.
	 */
	function isAdmin() {
		global $gConfig;

		if (empty($gConfig->mProp['Admins'])) {
			return TRUE;
		}
		//Note: The commas avoid partial name matches like "dm" in "admin".
		return strpos(",,{$gConfig->mProp['Admins']},", ",{$_SERVER['REMOTE_USER']},");
	}

	/**
	 * Returns true as long as we are not close to the script timeout.
	 */
	function canContinue() {
		global $gEndTime;
		return time() < $gEndTime;
	}

}

/**
 * The error class holds all error messages fired
 * by the current response. It is just a global data
 * container read by /resp/etc/Error.inc
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class ErrorStorage {

	/** An arry that contaions all error messages. */
	var $mErrors = '';

	/** True if a call to add() was made. */
	var $mNoErrorOccured = TRUE;

	/**
	 * Stores a new error message in mErr[]. Gets called
	 * in the implementation of verifyInput() or assembleData().
	 *
	 * @param $msg A dict entry name.
	 */
	function add( $msg ) {
		$this->mErrors .= "<li>$msg</li>\n";
		$this->mNoErrorOccured = FALSE;
	}

	/** Resets this objects state. */
	function reset() {
		$this->mErrors = '';
		$this->mNoErrorOccured = TRUE;
	}
}

?>
