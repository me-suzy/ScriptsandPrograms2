<?php
/**
* Class/library management functions
*
* @package     phpSlash
* @copyright   2002 - Open Concept Consulting
* @version     0.7 $Id: lib.resources.php,v 1.13 2005/05/28 12:19:08 krabu Exp $
* @copyright   Copyright (C) 2003 OpenConcept Consulting
*
* This file is part of Back-End.
*
* Back-End is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* Back-End is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Back-End; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
* Returns new unique instance of an object, taking into account replacement class names
*
* Attempts to include the PHP file if class is not defined - only works where
* class-name and file-name match
*
* For backward compatibility PSL. Except where replacement classes are used, it would be
* more efficient to simply use new foo(..) in the code
*
* @param string $className
* @param mixed $args,... (optional list of arguments to be supplied to the object)
* @retun object
*/
function pslNew() {
   global $_PSL;

   $args = func_get_args();
   $argc = func_num_args();
   if (!$argc) {
       return NULL;
   }
   $className = array_shift($args);
   --$argc;

   if (isset($_PSL['resources'][$className])) {
#debug('Replacing - '.$className,$_PSL['resources'][$className]);
       $className = $_PSL['resources'][$className];
   }
   if (!class_exists($className)) {
       // Could check for existence of file here, and add BE_ if it doesnt exist
      require_once($_PSL['classdir'] . '/' .$className.'.class');
   }

   if ($argc) {
       // This is the worst case: Multiple args are needed for constructor
       // One option would be:
       //    $obj = new $className();
       //    call_user_func_array(array(&$obj,'_constrcutor'), $args);
       // But that needs all existing classes to move constructor functionality
       // to a _constructor() method,
       // So instead, we need to use evil eval():

        $code = '$obj = new ' . $className . '(';
#        if (function_exists('var_export')) {
#            foreach ($args as $arg) {
#                $code .= var_export($arg, true) . ', ';
#            }
#        } else {
            for ($i=0;$i<$argc;++$i) {
                $code .= "\$args[$i], ";
            }
#        }
        $code = substr($code, 0, -2) . ');';
#       debug('Executing', $code);
#print_r(debug_backtrace()); echo "<br>\n";
        eval($code);
   } else {
#       debug('Instantiating',$className);
       // Simple case: Just instantiate
       $obj = new $className();
   }

   return $obj;
}


/**
* Returns a reference to a pslSingleton object
*
* Currently, instances are only indexed by classtype, and constructor parameters are ignored
*
* (Using singleton pattern)
* @retun objectRef
*/
function & pslSingleton($className) {
   global $_PSL;

   static $instance = array();

   if (isset($_PSL['resources'][$className])) {
       $className = $_PSL['resources'][$className];
   }

   if (!isset($instance[$className])) {
      if (!class_exists($className)) {
         require_once($_PSL['classdir'] . '/' .$className.'.class');
      }
      $instance[$className] = new $className();
   }
   return $instance[$className];
}


/**
* Used by pslNew and pslSingleton - remembers info on replacement classes
*
* @param string $oldClass Name of class to be subsituted for
* @param string $newClass Class to load in whenever oldClass is requested
*/
function addClassReplacement($oldClass,$newClass) {
    global $_PSL;
    $_PSL['resources'][$oldClass] = $newClass;
}

/**
* Attempt to load in class
*
* @param string $className Conceptual name of class to load. Replacement class will be used if set
* @param boolean $forceName If true, classReplacement is ignored
* @return void
*/
function loadClass($className, $forceClass = false) {
   global $_PSL;
   if (!$forceClass && isset($_PSL['resources'][$className])) {
       $className = $_PSL['resources'][$className];
   }
#   debug("Loading $className",$_PSL['classdir'] . '/' .$className.'.class');
   require_once($_PSL['classdir'] . '/' .$className.'.class');
}

/**
* Used to check whether a call to pslNew is needed
*/
function pslClassExists($className) {
   global $_PSL;
   if (isset($_PSL['resources'][$className])) {
       $className = $_PSL['resources'][$className];
   }
   return class_exists($className);
}

/**
* Used if replacement class name is needed (eg in slassSess, slassAuth classes)
*
* @deprecated ??
*/
function pslGetClass($className) {
   global $_PSL;
   if (isset($_PSL['resources'][$className])) {
       $className = $_PSL['resources'][$className];
   }
   return $className;
}

/**
* For backward compatibility
* - we now rely on include/require statements in pslNew to bring in the code
* @deprecated
*/
function addClassRequirement() {echo __FUNCTION__,' called';}
function addLibraryRequirement() {echo __FUNCTION__,' called';}
function loadLibrary() {echo __FUNCTION__,' called';}

?>
