<?php

/**
* Easy Class ("backbone" of easy framework)
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/

/**
* Easy Class ("backbone" of easy framework)
*
* @version      $Id: easy.class.php,v 1.6 2005/08/06 06:57:08 carsten Exp $
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/

class easy {

    /**
     * The class used for assertion handling. Can be overwritten
     *
     * @access private
     */
    var $assertion;
    
    /**
     * Constructor
     *
     * @access public
     */
	function easy () {
		
		// Core functionality: Assertions
        $this->assertion = new standard_assertion($this);
	}

    // =================================================================
    // Assertions
    // =================================================================

    /**
     * Sets proceeding in case of assertion
     *
     * @param boolean $continue_if_thrown   if true, programm gets continued in case of assertion.
     *
     * @access public
     */	
    function set_proceeding ($continue_if_thrown) {
    	$this->assertion->set_proceeding($continue_if_thrown);
    }
    
    /**
     * Sets function to use as assertion_callback
     *
     * @param string $assertion_function   default to easy_assert_callback
     *
     * @access public
     */	
    function set_assertion_function ($assertion_function) {
		$this->asserion_standard_function = $assertion_function;
		assert_options (ASSERT_CALLBACK, $assertion_function);
	}
        
    // =================================================================
    // PreShutdown, has be be called manually just before script termination
    // =================================================================

	function script_termination () {
		//if (USE_TIDY) $this->run_tidy ();	
	}

	// =================================================================
    // Shutdown, called when script stops (see register_shutdown_function)
    // =================================================================

	function shutdown () {
        $this->assertion->shutdown();
	}
}
?>
