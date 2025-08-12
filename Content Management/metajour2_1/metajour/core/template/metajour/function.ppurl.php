<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.ppurl.php
 * Type:     function
 * Name:     ppurl
 * Version:  1.1
 * Date:     March 13th, 2003
 * Purpose:  print an URL with added/deleted key/value pairs
 *           Example:
 *           {ppurl url="index.php?l=EN" key="print" value="1"}
 *           prints the string "index.php?l=EN&print=1"
 * Params:   url:    string, any URL which should be worked on
 *           key:    string, name of a GET parameter to change
 *           value:  string, value for GET parameter 'key'
 *           path:   string, specifies a new path for URL
 *           prefix: string, prefixed before 'path'
 * Hints:    - if url is omitted, the current URL (including GET) is taken
 *           - if value for a key is omitted, the key is deleted, e.g.:
 *             {ppurl url="index.php?print=1" key="print"}
 *             prints the string "index.php"
 *           - if path is omitted, basename('url') is used
 *           - prefix is handy if you use a template variable for absolute
 *             URL's instead of relative URL's
 *           - new parameters get urlencoded automatically
 * Bugs:     The whole thing only works if you use '&' as seperator for GET
 *           parameters, '&amp;' will _not_ work. (In fact this is not a bug,
 *           as the URL generated is syntactically correct, but not
 *           syntactically correct html)
 * Install:  Drop into the plugin directory.
 *           (requires shared.url_parameters.php)
 * Author:   Paul Kremer (paul !At! spurious !DOT! biz)
 * -------------------------------------------------------------
 */
require_once('shared.url_parameters.php');
function smarty_function_ppurl($params, &$smarty)
{
    extract($params);
    $nurl = new Smarty_Url_Parameters($url);

    if (empty($url)) {
        $nurl->fromCurrent();
    }

    if (!in_array('value', array_keys($params))) {
        $nurl->setParameter($key,false);
    } else {

       if (!in_array('key', array_keys($params))) {
           $smarty->trigger_error("assign: missing 'key' parameter");
           return;
       }

       $nurl->setParameter($key,$value);
    }

    if (!in_array('path', array_keys($params))) {
       $path = $nurl->getBasename();
    }

    if (in_array('prefix', array_keys($params))) {
       $path = $prefix . $path;
    }
 
    if ($path != '') {
       $nurl->setBasename($path);
    }

    return $nurl->getUrl();
}
?>
