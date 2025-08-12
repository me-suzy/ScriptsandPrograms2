<?php

/**
 * Class Module
 * Class for module information and management
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @version 1.0
 * @copyright four for business 2003
 */
class Module {

    /**
     * Constructor Function
     * @param
     */
    function Module() {
        // empty
    } // end function

    /**
     * getAvailableModules()
     * Returns all modules available in the system
     * @return array   Array with id and name entries
     */
    function getAvailableModules() {
        global $cfg;

        $db = new DB_Contenido;

        $sql = "SELECT
                    idmod,
                    name
                FROM
                ". $cfg["tab"]["mod"];

        $db->query($sql);

        $modules = array();
        
        while ($db->next_record())
        {
            
            $newentry["name"] = $db->f("name");

            $modules[$db->f("idmod")] = $newentry;

        }

        return ($modules);
    } // end function

    /**
     * getModuleName()
     * Returns the name for a given moduleid
     * @return string   String with the name for the module
     */
    function getModuleName( $module ) {
        global $cfg;

        $db = new DB_Contenido;

        $sql = "SELECT
                    name
                FROM
                ". $cfg["tab"]["mod"] ."
                WHERE
                    idmod = '".$module."'";
        $db->query($sql);
        $db->next_record();

        return ($db->f("name"));

    } // end function

    /**
     * getModuleID()
     * Returns the idmodule for a given module name
     * @return int     Integer with the ID for the module
     */
    function getModuleID( $module ) {
        global $cfg;

        $db = new DB_Contenido;

        $sql = "SELECT
                    idmod
                FROM
                ". $cfg["tab"]["mod"] ."
                WHERE
                    name = '".$module."'";

        $db->query($sql);
        $db->next_record();

        return ($db->f("idmod"));

    } // end function


    /**
     * moduleInUse()
     * Checks if the module is in use
     * @return bool    Specifies if the module is in use
     */
    function moduleInUse( $module ) {
        global $cfg;


        if (!is_numeric($module))
        {
            $module = $this->getModuleID($name);
        }
        
        $db = new DB_Contenido;

        $sql = "SELECT
                    idmod
                FROM
                ". $cfg["tab"]["container"] ."
                WHERE
                    idmod = '".$module."'";

        $db->query($sql);

        if ($db->nf() == 0)
        {
            return false;
        } else {
            return true;
        }


    } // end function  
    

} // end class

?>
