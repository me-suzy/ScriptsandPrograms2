<?php
/**
 *  ValidatorUser subclass of Validator
 *  Validates a username
 */
class ValidateUser extends Validator{
    /**
    * Private
    * $user the username to validate
    */
    var $user;

    //! A constructor.
    /**
    * Constucts a new ValidateUser object
    * @param $user the string to validate
    */
    function ValidateUser($user){
        $this->user=$user;
        Validator::Validator();
    }

    //! A manipulator
    /**
    * Validates a username
    * @return void
    */
    function validate(){
        if(!preg_match('/^[a-zA-Z0-9_]+$/',$this->user )){
            $this->setError('Username contains invalid characters');
        }
        if(strlen($this->user) < 6 ){
            $this->setError('Username is too short');
        }
        if(strlen($this->user) > 20 ){
            $this->setError('Username is too long');
        }
    }
}

?>
