<? /*************************************************************************************************
Copyright (c) 2003, 2004 Nathan 'Random' Rini
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*
  String Array Functions
 */

 //remove a value from list
 function remvalue($list,$value){
  if(!findvalue($list,$value)) return $list;

  $strarray = explode("!",$list); $list = '';
  foreach($strarray as $str)
   if($value != $str) $list = addvalue($list,$str);
  return $list;
 }

 //add a value with no repeats
 function addvalue($list,$value){//no repeats
  if(findvalue($list,$value)) return $list; //no repeats
  if($list == '') return $value;
  else return $list ."!". $value;
 }

 //add a value with repeats
 function addvaluerepeat($list,$value){//repeats
  if($list == '') return $value;
  else return $list ."!". $value;
 }

 //finds value in list
 function findvalue($list,$value){
  $strarray = explode("!",$list);
  if(in_array($value, $strarray)) return 1;
  else return 0;
 }

 //return value by index
 function findvalueindex($list,$value){
  $strarray = explode("!",$list);
  foreach($strarray as $str){$i++;
   if($str == $value) return $i;
  }
  return 0;
 }

 //counts items in list - ignores nulls
 function countvalues($list){$i = 0;
  $strarray = explode("!",$list);
  foreach($strarray as $str) if($str != '') $i++;
  return $i;
 }

 //replace value by index
 function changevalue($list,$index, $val){
  $strarray = explode("!",$list);
  $strarray[$index] = $val; $list = '';

  foreach($strarray as $str)
   $list = addvaluerepeat($list,$str);

  return $list;
 }

 //fills a array
 // use $1i for a index+1
 // use $i for a index
 function filllist($ubound, $val){
  for($i = 0; $i <= $ubound - 1; $i++)
   $list = addvaluerepeat($list, str_replace("\$1i",$i+1,str_replace("\$i",$i, $val)));

  return $list;
 }

 //remove any nulls from an array
 function remove_nulls($arr){
  if(is_array($arr))
  foreach($arr as $item)
   if($item != '')
    $out[] = $item;

  return $out;
 }

?>