<?php
/**
 * Replace file_get_contents()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @link        http://php.net/function.file_get_contents
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Revision: 1.16 $
 * @internal    resource_context is not supported
 * @since       PHP 5
 * @require     PHP 4.0.1 (trigger_error)
 */
if (!function_exists('file_get_contents'))
{
    function file_get_contents ($filename, $incpath = false, $resource_context = null)
    {
        if (false === $fh = fopen($filename, 'rb', $incpath)) {
            trigger_error('file_get_contents() failed to open stream: No such file or directory', E_USER_WARNING);
            return false;
        }

        clearstatcache();
        if ($fsize = filesize($filename)) {
            $data = fread($fh, $fsize);
        }
        
        else {
            while (!feof($fh)) {
                $data .= fread($fh, 8192);
            }
        }

        fclose($fh);

        return $data;    
    }
}

?>