<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: include/event_handler.inc
## Desc: An abstract class for the event handlers in the system
## $Revision: 2.5 $
## $Author: brobertson $
## $Date: 2003/12/01 12:12:30 $
###############################################################################

/** EventHandler is an abstract class for the event handlers in the MySource system.
  */
global $INCLUDE_PATH;
include_once ("$INCLUDE_PATH/webobject.inc");

class EventHandler {

	// Private class variables

	var $messages = array();	# status messages emitted by execute()
	var $parameters = array();	# map of name-description pairs 

	/** Constructor.
	  * 
	  * @public
	*/
	function EventHandler() {
		# do nothing for now, subclasses are free to override, of course...
	}
	

	/** Subclasses of EventHandler implement <code>do_command()</code> to implement
	  * whatever code is to be executed on events that this event handler is registered
	  * for (it shouldn't return anything - but may add lines to the message list via
	  * <code>append_message()</code>.
	  * 
	  * @public
	  * @param array $param Parameters for the command.
	  * @see EventHandler::append_message
	  * @returns void
	*/
	function execute($param) {
		return;
	}

	/** Retrieves the messages emitted by <code>execute()</code>, and then clears the message buffer.
	  * 
	  * @public
	  * @see EventHandler::execute
	  * @returns array
	*/
	function get_messages() {
		$messages = $this->messages;
		$this->messages = array();
		return $messages;
	}

	/** Adds a message to the message list.
	  *
	  * @protected
	  * @returns void
	*/
	function append_message($msg) {
		$this->messages[] = $msg;
	}

	/** Returns an array (name-description pairs) of parameters that this event handler recognises. 
	  *
	  * @public
	  * @returns array
	*/
	function get_parameter_list() {
		return $this->parameters;
	}

	/**
	  *
	  * @private
	*/
	function print_context_variable_mappings (&$event, $prefix = '') {

		?>
			<table align="center" bgcolor="#e0e0e0">
			
			<tr bgcolor="#d0d0d0">
			<th>
			Handler Parameter</th>
			<th>
			Context Variable</th>
			</tr>
			<?
			foreach ($this->get_parameter_list() as $name => $description) {

				$context_variable_name = $event->get_mapped_context_var ($name);
				?>
				<tr>
				<td>
				<?= $description?>
				</td>
				<td>
				<?=combo_box ("{$prefix}[map][".$name."]", $event->get_context_variables(), $context_variable_name)?>
				</td>
				</tr>
				<?
			}
			?>
			</table>
		<?
		
	}

	/** Prints the backend interface to configuring this event handler. The default behaviour 
	  * is to print configuration sections for each parameter to connect to context variables
	  * in the Event object.<br>
	  * Instructions can be added here, and configuring of any parameters subclasses might want.<br>
	  * Todo: make this into a parameter set thing.
	  * 
	  * @param object $event The Event object that this EventHandler handles.
	  * @param string $prefix Prefix to use to name HTML elements.
	*/
	function print_backend(&$event, $prefix='') {
		?><h4>Context Variable Mappings</h4><?
		$this->print_context_variable_mappings($event, $prefix);
	}

	function process_backend(&$event, &$handler_options) {
		foreach ($handler_options['map'] as $parameter_var => $context_var) {
			$event->map_parameter_var($parameter_var, $context_var);
		}
	}
}
?>