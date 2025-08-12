<?
global $INCLUDE_PATH;
include_once ("$INCLUDE_PATH/event_handler.inc");

/** An event handler class to send emails. <br><br>
  * Parameters used are to_email, subject and body.
  *
 */

class Event_Handler_Send_Email extends EventHandler {
	/** Constructor.
	  *
	  * @public
	*/
	function Event_Handler_Send_Email () {
		parent::EventHandler();
		$this->parameters = array (
			"to_email" => "To Email Address", 
			"from_email" => "From Email Address",
			"subject" => "Subject", 
			"body" => "Body"
		);
	}

	/** Sends email. 
	  * 
	  * @public
	  * @param array $param Parameters for the handler.
	  * @see EventHandler::append_message
	  * @returns void
	*/
	function execute ($param) {
		if (!valid_email($param['to_email'])) {
			$this->append_message ("To email <".$param['to_email']."> not valid.");
			return;
		}
		if (!valid_email ($param['from_email'])) {
			$this->append_message ("From email <".$param['from_email']."> not valid.");
			return;
		}
		mail ($param['to_email'], $param['subject'], $param['body'], "From: ".$param['from_email']);
		$this->append_message ("Email sent to {$param['to_email']}");
	}

	function print_backend(&$event, $prefix='') {
		?>
		<p>
		This is the backend for send email. Email is sent to the email address connected to the parameter <b>To</b>, using the subject connected to the parameter <b>Subject</b>.. you get the idea.
		</p>
		<?
		$this->print_context_variable_mappings ($event, $prefix);
	}

}

?>