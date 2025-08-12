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

/** Event is an a class encapsulating events handled by EventHandler.
  */


class Event extends CacheableObject {
	var $name = '';			# the name of the event
	var $title = '';		# the pretty name of the event
	var $description = '';	# the description of the event
	var $context_variables = array();	
							# the context descriptor for the event - these are the 
							# map of name-description pairs which are provided for 
							# substitution into the parameters of any event handlers 
							# registered for this event
	var $parameter_context_map = array();
							# maps the context variables to which parameter variables
							# (this is set up in the backend)
	
	var $handler = null;	# the handler for this event
	var $handler_type;		# the type of the handler

	/** Constructor.
	  *
	  * @public
	*/
	function Event ($name, $title, $description) {
		$this->title = $title;
		$this->name = $name;
		$this->description = $description;
	}

	/** Adds a new context variable descriptor. These are used to pick out 
	  * variables in the context of the event to pass on to the event handler.
	  *
	  * @param string $name The name of the context variable.
	  * @param string $description A description of the context variable.
	  * @public
	*/
	function add_context_variable($name, $description) {
		$this->context_variables[$name] = $description;
	}

	/** Returns a copy of the context variable descriptor.
	  *
	  * @public
	  * @returns array
	*/
	function get_context_variables() {
		return $this->context_variables;
	}

	/** Returns the name of the event.
	  *
	  * @public
	  * @returns string
	*/
	function get_name() {
		return $this->name;
	}

	/** Returns the title of the event (for printing in the backend).
	  *
	  * @public
	  * @returns string
	*/
	function get_title() {
		return $this->title;
	}

	/** Returns a description of the event (for printing in the backend).
	  *
	  * @public
	  * @returns string
	*/
	function get_description() {		
		return $this->description;
	}

	/** Returns a reference to the handler for this event (null if none is registered).
	  *
	  * @public
	  * @returns object
	*/
	function &get_handler () {
		$this->load_handler();
		return $this->handler;
	}

	/** Sets the event handler for this event.
	  *
	  * @public
	  * @param object $event_handler The EventHandler object responsible for handling this event.
	  * @returns void
	*/

	function set_handler (&$event_handler, $handler_type) {
		$this->handler =& $event_handler;
		$this->parameter_context_map = array();
		
		#setting handler type as the case sensative handle type in case dir has a capital letter in it
		$this->handler_type = $handler_type;
	}

	/** Retrieves the class for the handler. 
	  *
	  * @private
	  * @returns object
	*/
	function load_handler() {
		global $XTRAS_PATH, $XTRAS;

		$handler_type = $this->handler_type;
		if (in_array($handler_type, array_keys($XTRAS->list_type('event_handlers')))) {
			if (!is_null($this->handler))  {
				include_once ("$XTRAS_PATH/event_handlers/$handler_type/$handler_type.inc");
				# this object can now be instantiated properly
				$this->handler = serialize($this->handler);
				$this->handler = unserialize($this->handler);
			}
		} else {
			# we don't have this handler type installed
			$this->handler_type = '';
			$this->handler = null;
		}
	}
	
	/** Add a mapping for a parameter variable in the event handler to a context 
	  * variable made available by this event.
	  * 
	  * @public
	  * @param string $context_variable The name of the variable to map to the event context.
	  * @param string $parameter_variable The name of the parameter variable to map from in the event handler.
	  * @returns void
	  */
	function map_parameter_var($parameter_variable, $context_variable) {
		if (is_null($this->handler))
			return;

		if (!array_key_exists($parameter_variable, $this->handler->get_parameter_list()))
			return;
		$this->parameter_context_map[$parameter_variable] = $context_variable;
	}

	/** Retrieves the mapping for a particular parameter variable.
	  * 
	  * @public
	  * @param string $parameter_variable The name of the parameter variable mapped from in the event handler.
	  * @returns string
	  */
	function get_mapped_context_var($parameter_variable) {
		if (is_null($this->handler))
			return null;

		if (!array_key_exists($parameter_variable, $this->handler->get_parameter_list()))
			return '';

		return $this->parameter_context_map[$parameter_variable];
	}

	/** Removes the handler for this event, setting it to null. Also clears the parameter-context variable map.
	  *
	  * @public
	  * @returns void
	*/
	function clear_handler() {
		$this->handler = null;
		$this->handler_type = '';
		$this->parameter_context_map = array();
	}

	/** Triggers this event. This will execute the handler, mapping the context to parameters for the event handler.
	  *
	  * @public
	  * @param array $context The event context.
	  * @returns array
	*/
	function trigger($context) {
		if (is_null($this->handler))
			return;
		$this->load_handler();

		$params = array();
		foreach (array_keys($this->handler->get_parameter_list()) as $parameter) {
			$context_variable_name = $this->parameter_context_map[$parameter];
			$params[$parameter] = $context[$context_variable_name];
		}
		$this->handler->execute($params);

		return $this->handler->get_messages();
	}
	
	function print_backend() {
		global $XTRAS;

		$this->load_handler();
		?><b><?=$this->title?></b>

		<p>
		<small>
		<?=$this->description?>
		</small>
		</p>

		<p>
		<b>Event handler</b>
		<select name="events[<?=$this->name?>][handler_type]">
		<?

		$selected = (is_null($this->handler))?'selected':'';
		?>
		<option <?=$selected?> value="">-- No handler --</option>
		<?
			$selected = '';
			foreach ($XTRAS->list_type('event_handlers') as $codename => $name) {
				if (!is_null($this->handler)) {
					$selected = ($codename == $this->handler_type)?'selected':'';
					include_once("$XTRAS_PATH/event_handlers/{$codename}/{$codename}.inc");
				} 
				?><option <?=$selected?> value="<?=$codename?>"><?=$name?></option>
				<?
			}
		?>
		</select>
		</p>
		
		<?
		if (!is_null($this->handler)) {
			$this->handler->print_backend($this, "events[".$this->get_name()."][handler_options]");
		} else {
			?>
			<p>No handler chosen.</p>
			<?
		}
	}

	function process_backend() {
		global $XTRAS_PATH;

		$options =& $_POST['events'][$this->get_name()];

		if (!isset($options))
			return;
		$handler_type = $options['handler_type'];

		if ($handler_type == "") {
			if ($this->handler_type != '')
				$this->clear_handler();
		} else {

			include_once("$XTRAS_PATH/event_handlers/{$handler_type}/{$handler_type}.inc");
			$class_name = 'event_handler_'.$handler_type;
			
			# if there's no change, then set options, otherwise, wipe clean and get a new handler
			$old_type_name = $this->handler_type;

			$this->set_handler(new $class_name(), $handler_type);
			if ($this->handler_type == $old_type_name) {
				$this->handler->process_backend($this, $options['handler_options']);
			}
		}
	}
}

?>