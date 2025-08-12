<?php
/**
 *  Validator superclass for form validation
 */
class Validator {
    /**
    * Private
    * $errorMsg stores error messages if not valid
    */
    var $errorMsg;

    //! A constructor.
    /**
    * Constucts a new Validator object
    */
    function Validator(){
        $this->errorMsg=array();
        $this->validate();
    }

    //! A manipulator
    /**
    * @return void
    */
    function validate() {
        // Superclass method does nothing
    }

    //! A manipulator
    /**
    * Adds an error message to the array
    * @return void
    */
    function setError($msg){
        $this->errorMsg[]=$msg;
    }

    //! An accessor
    /**
    * Returns true is string valid, false if not
    * @return boolean
    */
    function isValid () {
        if(isset($this->errorMsg)){
            return false;
        }else{
            return true;
        }
    }

    //! An accessor
    /**
    * Pops the last error message off the array
    * @return string
    */
    function getError () {
        return array_pop($this->errorMsg);
    }
}

?>