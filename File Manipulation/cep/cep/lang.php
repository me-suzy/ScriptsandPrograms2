<?php
/*------------------------------------------------------------------------------
CJG EXPLORER PRO v3.2 - WEB FILE MANAGEMENT - Copyright (C) 2003 CARLOS GUERLLOY
CJGSOFT Software
cjgexplorerpro@guerlloy.com
guerlloy@hotmail.com
carlos@weinstein.com.ar
Buenos Aires, Argentina
--------------------------------------------------------------------------------
This program is free software; you can  redistribute it and/or  modify it  under
the terms   of the   GNU General   Public License   as published   by the   Free
Software Foundation; either  version 2   of the  License, or  (at  your  option)
any  later version. This program  is  distributed in  the hope that  it  will be
useful,  but  WITHOUT  ANY  WARRANTY;  without  even  the   implied  warranty of
MERCHANTABILITY  or FITNESS  FOR A  PARTICULAR  PURPOSE.  See the  GNU   General
Public License for   more details. You  should have received  a copy of  the GNU
General Public License along  with this  program; if   not, write  to the   Free
Software  Foundation, Inc.,  59 Temple Place,  Suite 330, Boston,  MA 02111-1307
USA
------------------------------------------------------------------------------*/

error_reporting(E_ERROR);

if(phpversion()<'4.1') eval('
function array_key_exists($key, $arr) {
foreach (array_keys($arr) as $k) if ($k==$key) return true; return false; }
');

$L=array();

function loadstrings($scr) { global $langdir,$L,$PHP_SELF;
$n=basename($scr);
if(substr($n,-4)==".php") $n=substr($n,0,-4);
$flang="langs/$langdir/$n.txt";
if(file_exists($flang)) { $texto=file($flang);
while(list($a,$b)=each($texto)) { 
if(substr($b,0,1)!='#') { list($k,$v)=explode("=",$b); if($v!="") $L[$k]=trim($v); } }
} }

loadstrings($PHP_SELF);

function T($key) { global $L; $mens=$key;
if(array_key_exists($key,$L)) { $mens=$L[$key];
$k1=substr($key,0,1); $k2=' ';
if(strlen($key)>1) $k2=substr($key,1,1);
if(($k1>='A' && $k1<='Z') && ($k2>='A' && $k2<='Z')) $mens=strtoupper($mens);
if(($k1>='a' && $k1<='z') && ($k2>='a' && $k2<='z')) $mens=strtolower($mens);
if(($k1>='A' && $k1<='Z') && ($k2>='a' && $k2<='z')) $mens=ucfirst($mens); }
return(addslashes($mens)); }

?>
