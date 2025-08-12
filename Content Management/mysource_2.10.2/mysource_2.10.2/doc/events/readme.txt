Disclaimer: The backend interface stuff isn't down pat yet, but the basic mechanism is (Dom 16-May).

Event Handler Objects
=====================

Brief Description
-----------------

EventHandler objects encapsulate code to be executed when an event occurs. They provide interfaces for any custom code to be executed at any point of execution, or events, in a MySource module. These events are defined in a MySource Module, and EventHandler objects are registered with each event type (set up in the backend) and when an event occurs, the EventHandler's execute() method is invoked. 

Motivation
----------

Say you wanted to hook in custom written code, or even code that does a single task, like send email, into various points during the execution of a MySource module's code. In the code, when a certain event occurs, there is a call to the appropriate Event object's trigger() call. An EventHandler object can be registered for each Event. The trigger call delegates to the execute() call of an EventHandler object, and the EventHandler object implements the execute() method to do whatever it is that you want when the Event is triggered.

Example
-------

For example, when an order goes through the E-Commerce Store, you might want it to move the product keys involved to another category and send off some emails based on fields in records in Notitia. Or you might want to pull some other variables from the context of the Event. 

You create an EventHandler object, or rather a subclass of EventHandler (as the EventHandler class itself is an abstract class) that handles this custom code through execute(). The EventHandler object should define what parameters it takes to do its job, so, for example, the SendEmail object would have parameters like 'to_email', 'from_email', 'subject', 'body'. In the execute() method, these parameters are passed in the for of a params array e.g. $params['to_email'] and then used for execution.

In the E-Commerce Store, you define an Event object that defines the event for when an order is processed, with a name of, say, 'on_order_process'. This should be done at creation/load time - you can store these events wherever you like - I've taken to putting them in an array and putting it into $this->parameters['events']. 

Then set up some context variables in the Event to be exposed to EventHandlers when the Event is triggered, and this is done by defining their names. The idea is that a context array for the Event is constructed: variable values in the context of the event are added to the array using the context variable names as indices. This array is then passed to the Event's trigger() method, which will then map the context variable values to parameters for the event handler's execution.

Back to the example: once an order has gone through, the appropriate Event object is retrieved, the context array is created with values for which product key, and what category to move it to. The Event is trigger()'ed, given the context array. These values are then mapped to parameters for the event handler. The mapping from context variables to parameter variables is configured in the backend.

In the backend of the module, the backend for the events you use is printed, and you can set up which event handler type will handle each event, and also do configuration of each handler type (for example, for a SendEmail event handler type there might be options like the mail bodycopy, whether to send in HTML, whatever). It is here that context variables available in the context of the Event object are mapped to parameters that the event handler for the Event requires for its operation. 


Example Code
------------

Confused? Probably. Here is some example code.

Say you wanted some code executed that e-mailed specific people certain emails on the success of an order placement in the ECommerce Store. 

In the ECommerce_Store class:
<snip>
	class Site_Extension_ECommerce_Store extends Site_Extension {
		...
		# your standard parameters array. There is an 'events' key pointing to 
		# an array of event objects.
		var $parameters = array(); 
		...
</snip>

<snip>
	function load ($pageid) {
		...
		# a description and a note is stored with each event for display in the backend
		$event =& $this->add_event (
			'on_order_process', 
			"On Completion of Order Processing", 
			"This event is triggered when an order form for the ECommerce Store is processed."
		); 
		# add some context variables that are filled just before event is triggered
		$event->add_context_variable ('name', 'Name');
		$event->add_context_variable ('to', 'To');
		$event->add_context_variable ('subj', 'Subject');
		$event->add_context_variable ('body', 'Body');
	}
</snip>

There's also function process_order:
<snip>
	function process_order(...) {
		...

		# finish doing processing of order
		
		# make a context array for the event
		$context = array (
			'name' => 'John Doe', 
			'to' => 'john_doe@example.com',
			'subj' => 'Your Order has been processed',
			'body' => 'Your credit card details are ...'
		);

		# look up Event object in $this->parameters 
		$event =& $this->get_event ('on_order_process');

		# trigger the event and pass along the context
		$event->trigger($context);

		...
</snip>

In the event code:
<snip>

	function trigger ($context) {
		$params = array();

		# retrieve the names of parameters in the event handler
		# they should each have corresponding context variable names that are
		# set up in the backend, and this is stored in $this->parameter_context_map

		foreach (array_keys($this->handler->get_parameter_list()) as $parameter) {
			$context_variable_name = $this->parameter_context_map[$parameter];
			$params[$parameter] = $context[$context_variable_name];
		}

		$this->handler->execute($params);
	}

</snip>

Eventually what gets called is the send_email event handler's execute() method:
<snip>
	function execute($params) {
		$to_email = $params['to_email']; 
		...
	}
</snip>

Future Work
-----------
I'd like to be able to move event definitions from the load() function to the parameter set file so that events can be described thus:

	Event: on_order_process
	Description: On Completion of Order Processing
	Context_Variable: name	Name
	Context_Variable: credit_card_name Name on Credit Card
	Context_Variable: credit_card_number Credit Card Number
	Context_Variable: credit_card_expiry Credit Card Expiry
	Context_Variable: product_key Product Key
	(?) HandlerTypes_allowed: send_email update_record

Also there needs to be a composite event handler that can store multiple event handlers (including itself), change their sequence of execution. For now I'll work on it so that you can have more than one event handler attached to an event - but we might also have some sort of condition-based event handler in the future. Other ideas I've had is a debugging handler that pops up a window and prints out values at certain execution points in code.


Conclusion
----------

I'm hoping that this mechanism will be used in future MySource modules. Not only does it save us from a lot of code replication, e.g. email generation, Notitia record updates, but it also enables us to have a more event-driven programming style which might suit some applications better, and also make them more customizable. Imagine just being able to plug in custom code at arbitrary entry points that we define. 

-- Dom <dwong@squiz.net>
