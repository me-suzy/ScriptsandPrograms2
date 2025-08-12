<?php

    /* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

    /*
        Class:          JoDB
        Package:        JoDB
        Description:    DBMS abstraction layer and SQL builder
        Platform:       PHP 5
        Author:         Jari Jokinen <jari.jokinen@iki.fi>
        Homepage URL:   http://jari.sigmatic.fi/jodb/
        License:        Free for non-commercial use.
                        For commercial use, contact author.
                        Redistributing the modified source code isn't allowed!

        Version:        0.0.2
        Released:       2005/07/04
        First release:  2005/05/19
    */

    class JoDB {

        public static function factory(&$settings) {

            // Method:  JoDB::factory()
            // Action:  Load DBMS driver and create new instance
            // Params:  Settings (array)
            // Return:  Instance (object ID)

            if (!is_array($settings)) {
                $settings = self::parseDSN($settings);
            }

            $classname = 'JoDB_' . ucfirst(strtolower($settings['dbms']));
            
            if (include_once $classname . '.php') {
                return new $classname($settings);
            }
            else {
                throw new JoDB_Exception(
                    'Could not load DBMS driver: ' . $settings['dbms'],
                    __CLASS__,
                    __METHOD__
                );
            }
            
        }

        public static function connect(&$settings) {

            // Method:  JoDB::connect()
            // Action:  Load driver, create new instance and connect to DBMS
            // Params:  Settings (array)
            // Return:  Instance (object ID)
        
            $obj = self::factory($settings);
            $obj->connect();
            return $obj;
            
        }

        private static function parseDSN($dsn) {

            // Method:  JoDB::parseDSN()
            // Action:  Parse data source name string
            // Params:  DSN (string)
            // Return:  Settings (array)

            preg_match(
                '/^(.*?)\:\/\/(.*?)\:(.*?)\@(.*?)\:([0-9]{1,6})\/(.*?)$/',
                $dsn,
                $matches
            );
            return array (
                'dbms'     => $matches[1],
                'username' => $matches[2],
                'password' => $matches[3],
                'hostname' => $matches[4],
                'hostport' => $matches[5],
                'database' => $matches[6]
            );
            
        }
    
    }

    /*
        Class:          JoDB_Exception
        Package:        JoDB
        Description:    Exception class for JoDB class and its child classes
    */

    class JoDB_Exception extends Exception {
    
        protected $message, $class, $method = '';

        public function __construct($message, $class, $method) {
            $this->message  = $message;
            $this->class    = $class;
            $this->method   = $method;
        }

        public function getError() {
            return array($this->message, $this->class, $this->method);
        }

        public function __toString() {
            return
                'An error occurred in method "' . $this->method .
                '" of class "' . $this->class . '": ' . $this->message;
        }
    
    }

?>
