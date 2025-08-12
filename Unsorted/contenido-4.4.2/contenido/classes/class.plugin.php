<?php

/******************************************
* File      :   classes_plugins.php
* Project   :   Contenido extensions
* Descr     :   Classes for Plug-In
*               handling in Contenido
*
* Author    :   Jan Lengowski
* Created   :   19.11.2002
* Modified  :   25.11.2002
*
* © four for business AG
******************************************/

/**
* Class Plugin
*
* Class for handling Plug-Ins in Contendio.
* Path to the Plugins is defined in /inc/config.php.
*
* @author Jan Lengowski <Jan.Lengowski@4fb.de>
* @copyright four for business AG 2002
* @version 1.0
*/
class Plugin {

    /**
    * Variable holding the Plug-In Directory
    * @var string
    */
    var $plugdir;
    
    /**
    * Array holding the general informations
    * about the found Plug-Ins
    * @var array
    */
    var $plugins;

    /**
    * Class constructor
    *
    * Defines the plug-in directory ($this->plugdir).
    *
    * @param string $plugdir Plugin directory (optional)
    */
    function Plugin($plugdir = '') {
        global $con_cfg;

        if ($plugdir != '') {
            $this->plugdir = $plugdir;
        } else {
            $this->plugdir = $con_cfg['PathPlugins'];
        }

    }
    
    /**
    * Search Function
    *
    * Searches the Plug-In Directory for Plug-Ins
    * and saves all found Plug-Ins into an multi-
    * dimensional array ($this->plugins). All general
    * information about the plugin is stored in the
    * array entry.
    *
    * @return void
    */
    function search() {
        // Contenido config array
        global $con_cfg;
        // open the directory
        if ($dir = @opendir($this->plugdir)) {
            // loop found directories
            while (($file = readdir($dir)) !== false) {
                // cut . and ..
                if ($file != '.' && $file != '..') {
                    // save directories into an array
                    $this->plugins[] = array('dir'=>$file.'/');
                }
            }
            closedir($dir);
        }
        
        // loop through and extract data from plugin.cfg
        foreach ($this->plugins as $id => $dir) {
            // read plugin.cfg
            $file = $this->plugdir.$this->plugins[$id]['dir'].'plugin.cfg';
            $carr = file($file);

            // loop the array and extract data;
            foreach ($carr as $line) {
                // cheeck if a : exists, line is a parameter line
                if (strstr($line,':')) {
                    // explode line
                    $vals = explode(':',$line);
                    // strip whitespace
                    $vals[0] = trim($vals[0]);
                    $vals[1] = trim($vals[1]);
                    // set data
                    $this->plugins[$id][$vals[0]] = $vals[1];
                }

            }
            $this->plugins[$id]['includedir'] = $con_cfg['PathPlugins'].$this->plugins[$id]['dir'].$this->plugins[$id]['includedir'];
            
        }
        
    } // end function

    /**
    * Link function for Plug-ins
    *
    * Generates an url with the variables
    * 'area' and 'contenido' attached
    *
    * @param string $url url for linking
    */
    function url($link) {

        $link_str = $link;

        if (strstr($link_str,'?')){
            $link_str .= '&area=' . $HTTP_GET_VARS['area'] . '&contenido=' . $HTTP_GET_VARS['contenido'];
        } else {
            $link_str .= '?area=' . $HTTP_GET_VARS['area'] . '&contenido=' . $HTTP_GET_VARS['contenido'];
        }
        
        return $link_str;
        
    }

} // end class Plugin


?>
