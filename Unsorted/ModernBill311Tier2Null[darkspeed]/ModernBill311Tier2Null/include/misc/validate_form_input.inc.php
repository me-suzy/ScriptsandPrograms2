<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
**
** File ....... validate_form_input.inc.php
** ModDate .... 7/20/01
** Usage ...... This file will clean all input variables from
**              "$HTTP_POST_VARS"
**              "$HTTP_GET_VARS"
**              "$HTTP_COOKIE_VARS"
** Example .... include("include/validate_form_input.inc.php");
**
** DERIVED FROM ZEND.COM
*/

function custom_strip_tags($value) {
         GLOBAL $bad_words, $bad_flag, $oops;
         $value = strip_tags($value);
         if (is_array($bad_words)&&in_array($value,$bad_words)) {
             $oops_message = "[".ERROR."] ".CANNOTSAY.": <b>$value</b>!<br>";
             $oops.= (ereg($oops_message,$oops)) ? "" : $oops_message ;
             $value = "";
         }
         return $value;
}

if(is_array($HTTP_POST_VARS)) {
   reset($HTTP_POST_VARS);
   while (list($key, $val) = each($HTTP_POST_VARS)) {
          if (is_array($val)) {
             while (list($akey,$aval) = each($val)) {
                  $HTTP_POST_VARS[$key][$akey] = custom_strip_tags($aval);
                  ${$key}[$akey] = custom_strip_tags($aval);
             }
          } else {
             $HTTP_POST_VARS[$key] = custom_strip_tags($val);
             ${$key} = custom_strip_tags($val);
          }
   }
}

if(is_array($HTTP_GET_VARS)) {
   reset($HTTP_GET_VARS);
   while (list($key, $val) = each($HTTP_GET_VARS)) {
          if (is_array($val)) {
             while (list($akey,$aval) = each($val)) {
                   $HTTP_GET_VARS[$key][$akey] = custom_strip_tags($aval);
                   ${$key}[$akey] = custom_strip_tags($aval);
             }
          } else {
             $HTTP_GET_VARS[$key] = custom_strip_tags($val);
             ${$key} = custom_strip_tags($val);
          }
   }
}

if(is_array($HTTP_COOKIE_VARS)) {
   reset($HTTP_COOKIE_VARS);
   while (list($key, $val) = each($HTTP_COOKIE_VARS)) {
          if (is_array($val)) {
             while (list($akey,$aval) = each($val)) {
                   $HTTP_COOKIE_VARS[$key][$akey] = custom_strip_tags($aval);
                   ${$key}[$akey] = custom_strip_tags($aval);
             }
          } else {
             $HTTP_COOKIE_VARS[$key] = custom_strip_tags($val);
             ${$key} = custom_strip_tags($val);
          }
   }
}

?>