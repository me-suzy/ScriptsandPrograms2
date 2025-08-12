<?php
/*************************
  Coppermine Photo Gallery
  ************************
  Copyright (c) 2003-2005 Coppermine Dev Team
  v1.1 originaly written by Gregory DEMAR

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  Coppermine version: 1.4.2
  $Source: /cvsroot/coppermine/devel/include/mb.inc.php,v $
  $Revision: 1.5 $
  $Author: gaugau $
  $Date: 2005/11/23 07:53:39 $
**********************************************/
/*
        MBFS - MultiByte Functions Simulator
        Functions that simulate the mb_*() extension functionality
        NOTE: only Unicode possible with these

        @author DJ Maze
        @copyright 2005 http://moocms.com
*/

global $mb_uppercase, $mb_lowercase;

# PHP 4 >= 4.0.6, PHP 5
if (!function_exists('mb_strlen')) {

        function mb_strlen($str) {
                global $mb_utf8_regex;
                return preg_match_all("#$mb_utf8_regex".'|[\x00-\x7F]#', $str, $dummy);
        }

        function mb_substr($str, $start, $end=null) {
                global $mb_utf8_regex;
                preg_match_all("#$mb_utf8_regex".'|[\x00-\x7F]#', $str, $str);
                $str = array_slice($str[0], $start, $end);
                return implode('', $str);
        }

}

# PHP 4 >= 4.3.0, PHP 5
function mb_strtolower($str) {
        global $mb_uppercase, $mb_lowercase;
        return str_replace($mb_uppercase, $mb_lowercase, $str);
}

function mb_strtoupper($str) {
        global $mb_uppercase, $mb_lowercase;
        return str_replace($mb_lowercase, $mb_uppercase, $str);
}

$mb_uppercase = array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        '&Icirc;',
        'Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Ã','Å¸','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml; ','&Auml;¢','&Auml;¤','&Auml;¦','&Auml;¨','&Auml;ª','&Auml;¬','&Auml;®','I','&Auml;&sup2;','&Auml;´','&Auml;¶',
        '&Auml;¹','&Auml;»','&Auml;½','&Auml;¿','Å','Å','Å','Å',
        'Å','Å','Å','Å','Å','Å','Å','Å','Å','Å','Å','Å ','Å¢','Å¤','Å¦','Å¨','Åª','Å¬','Å®','Å°','Å&sup2;','Å´','Å¶','Å¹','Å»','Å½','S',
        'Æ','Æ','Æ','Æ',
        'Æ','Ç¶','Æ','&Egrave;½',
        '&Egrave; ','Æ ','Æ¢','Æ¤','Æ§',
        'Æ¬','Æ¯','Æ&sup3;','Æ&micro;','Æ¸',
        'Æ¼',
        'Ç·','Ç','Ç','Ç','Ç','Ç','Ç','Ç','Ç','Ç','Ç','Ç','Æ','Ç','Ç ','Ç¢','Ç¤','Ç¦','Ç¨','Çª','Ç¬','Ç®',
        'Ç&sup2;','Ç´','Ç¸','Çº','Ç¼','Ç¾','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;',
        '&Egrave;¢','&Egrave;¤','&Egrave;¦','&Egrave;¨','&Egrave;ª','&Egrave;¬','&Egrave;®','&Egrave;°','&Egrave;&sup2;',
        '&Egrave;»',
        'Æ','Æ',
        'Æ','Æ',
        'Æ',
        'Æ',
        'Æ',
        'Æ',
        'Æ','Æ',
        'Æ',
        'Æ',
        'Æ',
        'Æ¦',
        'Æ©',
        'Æ®',
        'Æ±','Æ&sup2;',
        'Æ·',
        '&Eacute;',
        '&Icirc;','&Icirc;','&Icirc;','&Icirc;',
        '&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc; ','&Icirc;¡','&Icirc;£','&Icirc;£','&Icirc;¤','&Icirc;¥','&Icirc;¦','&Icirc;§','&Icirc;¨','&Icirc;©','&Icirc;ª','&Icirc;«','&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc;','&Icirc;¦','&Icirc; ',
        'Ï','Ï','Ï','Ï','Ï ','Ï¢','Ï¤','Ï¦','Ï¨','Ïª','Ï¬','Ï®','&Icirc;','&Icirc;¡','Ï¹',
        '&Icirc;','Ï·','Ïº',
        'Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð ','Ð¡','Ð¢','Ð£','Ð¤','Ð¥','Ð¦','Ð§','Ð¨','Ð©','Ðª','Ð«','Ð¬','Ð­','Ð®','Ð¯','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ð','Ñ ','Ñ¢','Ñ¤','Ñ¦','Ñ¨','Ñª','Ñ¬','Ñ®','Ñ°','Ñ&sup2;','Ñ´','Ñ¶','Ñ¸','Ñº','Ñ¼','Ñ¾','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve; ','&Ograve;¢','&Ograve;¤','&Ograve;¦','&Ograve;¨','&Ograve;ª','&Ograve;¬','&Ograve;®','&Ograve;°','&Ograve;&sup2;','&Ograve;´','&Ograve;¶','&Ograve;¸','&Ograve;º','&Ograve;¼','&Ograve;¾','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute; ','&Oacute;¢','&Oacute;¤','&Oacute;¦','&Oacute;¨','&Oacute;ª','&Oacute;¬','&Oacute;®','&Oacute;°','&Oacute;&sup2;','&Oacute;´','&Oacute;¶','&Oacute;¸','&Ocirc;','&Ocirc;','&Ocirc;','&Ocirc;','&Ocirc;','&Ocirc;','&Ocirc;','&Ocirc;','&Ocirc;±','&Ocirc;&sup2;','&Ocirc;&sup3;','&Ocirc;´','&Ocirc;&micro;','&Ocirc;¶','&Ocirc;·','&Ocirc;¸','&Ocirc;¹','&Ocirc;º','&Ocirc;»','&Ocirc;¼','&Ocirc;½','&Ocirc;¾','&Ocirc;¿','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ','Õ',
        '&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸ ','&aacute;¸¢','&aacute;¸¤','&aacute;¸¦','&aacute;¸¨','&aacute;¸ª','&aacute;¸¬','&aacute;¸®','&aacute;¸°','&aacute;¸&sup2;','&aacute;¸´','&aacute;¸¶','&aacute;¸¸','&aacute;¸º','&aacute;¸¼','&aacute;¸¾','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹ ','&aacute;¹¢','&aacute;¹¤','&aacute;¹¦','&aacute;¹¨','&aacute;¹ª','&aacute;¹¬','&aacute;¹®','&aacute;¹°','&aacute;¹&sup2;','&aacute;¹´','&aacute;¹¶','&aacute;¹¸','&aacute;¹º','&aacute;¹¼','&aacute;¹¾','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º',
        '&aacute;¹ ','&aacute;º ','&aacute;º¢','&aacute;º¤','&aacute;º¦','&aacute;º¨','&aacute;ºª','&aacute;º¬','&aacute;º®','&aacute;º°','&aacute;º&sup2;','&aacute;º´','&aacute;º¶','&aacute;º¸','&aacute;ºº','&aacute;º¼','&aacute;º¾','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;» ','&aacute;»¢','&aacute;»¤','&aacute;»¦','&aacute;»¨','&aacute;»ª','&aacute;»¬','&aacute;»®','&aacute;»°','&aacute;»&sup2;','&aacute;»´','&aacute;»¶','&aacute;»¸','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼¨','&aacute;¼©','&aacute;¼ª','&aacute;¼«','&aacute;¼¬','&aacute;¼­','&aacute;¼®','&aacute;¼¯','&aacute;¼¸','&aacute;¼¹','&aacute;¼º','&aacute;¼»','&aacute;¼¼','&aacute;¼½','&aacute;¼¾','&aacute;¼¿','&aacute;½','&aacute;½','&aacute;½','&aacute;½','&aacute;½','&aacute;½',
        '&aacute;½',
        '&aacute;½',
        '&aacute;½',
        '&aacute;½','&aacute;½¨','&aacute;½©','&aacute;½ª','&aacute;½«','&aacute;½¬','&aacute;½­','&aacute;½®','&aacute;½¯','&aacute;¾º','&aacute;¾»','&aacute;¿','&aacute;¿','&aacute;¿','&aacute;¿','&aacute;¿','&aacute;¿','&aacute;¿¸','&aacute;¿¹','&aacute;¿ª','&aacute;¿«','&aacute;¿º','&aacute;¿»','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾¨','&aacute;¾©','&aacute;¾ª','&aacute;¾«','&aacute;¾¬','&aacute;¾­','&aacute;¾®','&aacute;¾¯','&aacute;¾¸','&aacute;¾¹',
        '&aacute;¾¼',
        '&Icirc;',
        '&aacute;¿',
        '&aacute;¿','&aacute;¿',
        '&aacute;¿¨','&aacute;¿©',
        '&aacute;¿¬',
        '&aacute;¿¼',
        '&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;°','&acirc;° ','&acirc;°¡','&acirc;°¢','&acirc;°£','&acirc;°¤','&acirc;°¥','&acirc;°¦','&acirc;°§','&acirc;°¨','&acirc;°©','&acirc;°ª','&acirc;°«','&acirc;°¬','&acirc;°­','&acirc;°®','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2; ','&acirc;&sup2;¢','&acirc;&sup2;¤','&acirc;&sup2;¦','&acirc;&sup2;¨','&acirc;&sup2;ª','&acirc;&sup2;¬','&acirc;&sup2;®','&acirc;&sup2;°','&acirc;&sup2;&sup2;','&acirc;&sup2;´','&acirc;&sup2;¶','&acirc;&sup2;¸','&acirc;&sup2;º','&acirc;&sup2;¼','&acirc;&sup2;¾','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3; ','&acirc;&sup3;¢',
        '&aacute; ','&aacute;¡','&aacute;¢','&aacute;£','&aacute;¤','&aacute;¥','&aacute;¦','&aacute;§','&aacute;¨','&aacute;©','&aacute;ª','&aacute;«','&aacute;¬','&aacute;­','&aacute;®','&aacute;¯','&aacute;°','&aacute;±','&aacute;&sup2;','&aacute;&sup3;','&aacute;´','&aacute;&micro;','&aacute;¶','&aacute;·','&aacute;¸','&aacute;¹','&aacute;º','&aacute;»','&aacute;¼','&aacute;½','&aacute;¾','&aacute;¿','&aacute;','&aacute;','&aacute;','&aacute;','&aacute;','&aacute;',
        'ï¼¡','ï¼¢','ï¼£','ï¼¤','ï¼¥','ï¼¦','ï¼§','ï¼¨','ï¼©','ï¼ª','ï¼«','ï¼¬','ï¼­','ï¼®','ï¼¯','ï¼°','ï¼±','ï¼&sup2;','ï¼&sup3;','ï¼´','ï¼&micro;','ï¼¶','ï¼·','ï¼¸','ï¼¹','ï¼º',
);
$mb_lowercase = array(
        'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
        '&Acirc;&micro;',
        'Ã ','Ã¡','Ã¢','Ã£','Ã¤','Ã¥','Ã¦','Ã§','Ã¨','Ã©','Ãª','Ã«','Ã¬','Ã­','Ã®','Ã¯','Ã°','Ã±','Ã&sup2;','Ã&sup3;','Ã´','Ã&micro;','Ã¶','Ã¸','Ã¹','Ãº','Ã»','Ã¼','Ã½','Ã¾','Ã¿','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;','&Auml;¡','&Auml;£','&Auml;¥','&Auml;§','&Auml;©','&Auml;«','&Auml;­','&Auml;¯','&Auml;±','&Auml;&sup3;','&Auml;&micro;','&Auml;·',
        '&Auml;º','&Auml;¼','&Auml;¾','Å','Å','Å','Å','Å',
        'Å','Å','Å','Å','Å','Å','Å','Å','Å','Å','Å','Å¡','Å£','Å¥','Å§','Å©','Å«','Å­','Å¯','Å±','Å&sup3;','Å&micro;','Å·','Åº','Å¼','Å¾','Å¿',
        'Æ','Æ','Æ','Æ',
        'Æ','Æ','Æ','Æ',
        'Æ','Æ¡','Æ£','Æ¥','Æ¨',
        'Æ­','Æ°','Æ´','Æ¶','Æ¹',
        'Æ½',
        'Æ¿','Ç','Ç','Ç','Ç','Ç','Ç','Ç','Ç','Ç','Ç','Ç','Ç','Ç','Ç¡','Ç£','Ç¥','Ç§','Ç©','Ç«','Ç­','Ç¯',
        'Ç&sup3;','Ç&micro;','Ç¹','Ç»','Ç½','Ç¿','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;','&Egrave;',
        '&Egrave;£','&Egrave;¥','&Egrave;§','&Egrave;©','&Egrave;«','&Egrave;­','&Egrave;¯','&Egrave;±','&Egrave;&sup3;',
        '&Egrave;¼',
        '&Eacute;','&Eacute;',
        '&Eacute;','&Eacute;',
        '&Eacute;',
        '&Eacute;',
        '&Eacute; ',
        '&Eacute;£',
        '&Eacute;¨','&Eacute;©',
        '&Eacute;¯',
        '&Eacute;&sup2;',
        '&Eacute;&micro;',
        '&Ecirc;',
        '&Ecirc;',
        '&Ecirc;',
        '&Ecirc;','&Ecirc;',
        '&Ecirc;',
        '&Ecirc;',
        '&Icirc;¬','&Icirc;­','&Icirc;®','&Icirc;¯',
        '&Icirc;±','&Icirc;&sup2;','&Icirc;&sup3;','&Icirc;´','&Icirc;&micro;','&Icirc;¶','&Icirc;·','&Icirc;¸','&Icirc;¹','&Icirc;º','&Icirc;»','&Icirc;¼','&Icirc;½','&Icirc;¾','&Icirc;¿','Ï','Ï','Ï','Ï','Ï','Ï','Ï','Ï','Ï','Ï','Ï','Ï','Ï','Ï','Ï','Ï','Ï','Ï','Ï',
        'Ï','Ï','Ï','Ï','Ï¡','Ï£','Ï¥','Ï§','Ï©','Ï«','Ï­','Ï¯','Ï°','Ï±','Ï&sup2;',
        'Ï&micro;','Ï¸','Ï»',
        'Ð°','Ð±','Ð&sup2;','Ð&sup3;','Ð´','Ð&micro;','Ð¶','Ð·','Ð¸','Ð¹','Ðº','Ð»','Ð¼','Ð½','Ð¾','Ð¿','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ','Ñ¡','Ñ£','Ñ¥','Ñ§','Ñ©','Ñ«','Ñ­','Ñ¯','Ñ±','Ñ&sup3;','Ñ&micro;','Ñ·','Ñ¹','Ñ»','Ñ½','Ñ¿','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;','&Ograve;¡','&Ograve;£','&Ograve;¥','&Ograve;§','&Ograve;©','&Ograve;«','&Ograve;­','&Ograve;¯','&Ograve;±','&Ograve;&sup3;','&Ograve;&micro;','&Ograve;·','&Ograve;¹','&Ograve;»','&Ograve;½','&Ograve;¿','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;','&Oacute;¡','&Oacute;£','&Oacute;¥','&Oacute;§','&Oacute;©','&Oacute;«','&Oacute;­','&Oacute;¯','&Oacute;±','&Oacute;&sup3;','&Oacute;&micro;','&Oacute;·','&Oacute;¹','&Ocirc;','&Ocirc;','&Ocirc;','&Ocirc;','&Ocirc;','&Ocirc;','&Ocirc;','&Ocirc;','Õ¡','Õ¢','Õ£','Õ¤','Õ¥','Õ¦','Õ§','Õ¨','Õ©','Õª','Õ«','Õ¬','Õ­','Õ®','Õ¯','Õ°','Õ±','Õ&sup2;','Õ&sup3;','Õ´','Õ&micro;','Õ¶','Õ·','Õ¸','Õ¹','Õº','Õ»','Õ¼','Õ½','Õ¾','Õ¿','&Ouml;','&Ouml;','&Ouml;','&Ouml;','&Ouml;','&Ouml;','&Ouml;',
        '&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸','&aacute;¸¡','&aacute;¸£','&aacute;¸¥','&aacute;¸§','&aacute;¸©','&aacute;¸«','&aacute;¸­','&aacute;¸¯','&aacute;¸±','&aacute;¸&sup3;','&aacute;¸&micro;','&aacute;¸·','&aacute;¸¹','&aacute;¸»','&aacute;¸½','&aacute;¸¿','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹','&aacute;¹¡','&aacute;¹£','&aacute;¹¥','&aacute;¹§','&aacute;¹©','&aacute;¹«','&aacute;¹­','&aacute;¹¯','&aacute;¹±','&aacute;¹&sup3;','&aacute;¹&micro;','&aacute;¹·','&aacute;¹¹','&aacute;¹»','&aacute;¹½','&aacute;¹¿','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º','&aacute;º',
        '&aacute;º','&aacute;º¡','&aacute;º£','&aacute;º¥','&aacute;º§','&aacute;º©','&aacute;º«','&aacute;º­','&aacute;º¯','&aacute;º±','&aacute;º&sup3;','&aacute;º&micro;','&aacute;º·','&aacute;º¹','&aacute;º»','&aacute;º½','&aacute;º¿','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»','&aacute;»¡','&aacute;»£','&aacute;»¥','&aacute;»§','&aacute;»©','&aacute;»«','&aacute;»­','&aacute;»¯','&aacute;»±','&aacute;»&sup3;','&aacute;»&micro;','&aacute;»·','&aacute;»¹','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼','&aacute;¼ ','&aacute;¼¡','&aacute;¼¢','&aacute;¼£','&aacute;¼¤','&aacute;¼¥','&aacute;¼¦','&aacute;¼§','&aacute;¼°','&aacute;¼±','&aacute;¼&sup2;','&aacute;¼&sup3;','&aacute;¼´','&aacute;¼&micro;','&aacute;¼¶','&aacute;¼·','&aacute;½','&aacute;½','&aacute;½','&aacute;½','&aacute;½','&aacute;½',
        '&aacute;½',
        '&aacute;½',
        '&aacute;½',
        '&aacute;½','&aacute;½ ','&aacute;½¡','&aacute;½¢','&aacute;½£','&aacute;½¤','&aacute;½¥','&aacute;½¦','&aacute;½§','&aacute;½°','&aacute;½±','&aacute;½&sup2;','&aacute;½&sup3;','&aacute;½´','&aacute;½&micro;','&aacute;½¶','&aacute;½·','&aacute;½¸','&aacute;½¹','&aacute;½º','&aacute;½»','&aacute;½¼','&aacute;½½','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾','&aacute;¾ ','&aacute;¾¡','&aacute;¾¢','&aacute;¾£','&aacute;¾¤','&aacute;¾¥','&aacute;¾¦','&aacute;¾§','&aacute;¾°','&aacute;¾±',
        '&aacute;¾&sup3;',
        '&aacute;¾¾',
        '&aacute;¿',
        '&aacute;¿','&aacute;¿',
        '&aacute;¿ ','&aacute;¿¡',
        '&aacute;¿¥',
        '&aacute;¿&sup3;',
        '&acirc;°°','&acirc;°±','&acirc;°&sup2;','&acirc;°&sup3;','&acirc;°´','&acirc;°&micro;','&acirc;°¶','&acirc;°·','&acirc;°¸','&acirc;°¹','&acirc;°º','&acirc;°»','&acirc;°¼','&acirc;°½','&acirc;°¾','&acirc;°¿','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;±','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;','&acirc;&sup2;¡','&acirc;&sup2;£','&acirc;&sup2;¥','&acirc;&sup2;§','&acirc;&sup2;©','&acirc;&sup2;«','&acirc;&sup2;­','&acirc;&sup2;¯','&acirc;&sup2;±','&acirc;&sup2;&sup3;','&acirc;&sup2;&micro;','&acirc;&sup2;·','&acirc;&sup2;¹','&acirc;&sup2;»','&acirc;&sup2;½','&acirc;&sup2;¿','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;','&acirc;&sup3;¡','&acirc;&sup3;£',
        '&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´','&acirc;´ ','&acirc;´¡','&acirc;´¢','&acirc;´£','&acirc;´¤','&acirc;´¥',
        'ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½','ï½',
);