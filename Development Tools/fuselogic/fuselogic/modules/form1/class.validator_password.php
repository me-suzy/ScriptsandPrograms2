<?php
/**
 *  ValidatorPassword subclass of Validator
 *  Validates a password
 */
class ValidatePassword extends Validator {
    /**
    * Private
    * $pass the password to validate
    */
    var $pass;
    /**
    * Private
    * $conf to confirm the passwords match
    */
    var $conf;

    //! A constructor.
    /**
    * Constucts a new ValidatePassword object subclass or Validator
    * @param $pass the string to validate
    * @param $conf to compare with $pass for confirmation
    */
    function ValidatePassword($pass,$conf){
        $this->pass = $pass;
        $this->conf = $conf;
        Validator::Validator();
    }

    //! A manipulator
    /**
    * Validates a password
    * @return void
    */
    function validate(){
        if($this->pass!=$this->conf){
            $this->setError('Passwords do not match');
        }
        if(!preg_match('/^[a-zA-Z0-9_]+$/',$this->pass )){
            $this->setError('Password contains invalid characters');
        }
        if(strlen($this->pass) < 6 ){
            $this->setError('Password is too short');
        }
        if(strlen($this->pass) > 20 ){
            $this->setError('Password is too long');
        }
    }
}
?>