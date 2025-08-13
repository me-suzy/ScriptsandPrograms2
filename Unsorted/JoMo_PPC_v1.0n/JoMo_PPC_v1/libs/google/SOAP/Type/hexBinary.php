<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Shane Caraveo                       Port to PEAR and more   |
// | Authors: Dietrich Ayala                      Original Author         |
// +----------------------------------------------------------------------+
//
// $Id: hexBinary.php,v 1.3 2002/02/25 18:59:49 shane Exp $
//
class SOAP_Type_hexBinary
{
    function to_bin($value)
    {
        $len = strlen($value);
        return pack('H' . $len, $value);
    }
    function to_hex($value)
    {
        return bin2hex($value);
    }
    function is_hexbin($value)
    {
        # first see if there are any invalid chars
        $l = strlen($value);

        if ($l < 1 || strspn($value, '0123456789ABCDEFabcdef') != $l) return FALSE;

        $bin = SOAP_Type_hexBinary::to_bin($value);
        $hex = SOAP_Type_hexBinary::to_hex($bin);
        return strcasecmp($value, $hex) == 0;
    }
}

?>