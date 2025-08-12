<?
global $INCLUDE_PATH;
include_once ("$INCLUDE_PATH/event_handler.inc");

/** An event handler class to do nothing. Use this as a template for creating new event handlers.
  *
 */

class Event_Handler_Null extends EventHandler {
	/** Constructor.
	  *
	  * @public
	*/
	function Event_Handler_Null () {
		parent::EventHandler();
		$this->parameters = array ("bogus" => "A bogus variable - not used.");
	}

	/** Does nothing. 
	  * 
	  * @public
	  * @param array $param Parameters for the handler.
	  * @see EventHandler::append_message
	  * @returns void
	*/
	function execute ($param) {

		$bogus = $param['bogus'];
		# do something with $bogus
		$this->append_message ("Bogus was: $bogus");
		return;
	}
}
?>