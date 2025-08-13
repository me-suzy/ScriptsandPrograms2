<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Cipher Functions

 */



 //hashes str - double encryption

 function hash($str){

  return md5($str);

 }



 //generates a random string

 function gen_rand_str($len){

  for($i = 1; $i <= $len; $i++)

   $str .= gen_rand_char();



  return $str;

 }



 //generates a random char or number

 function gen_rand_char(){

  //10 - numbers  rand(48,57)

  //26 - lcase    rand(97,122)

  //26 - ucase    rand(65,90)



  switch(rand(1,3)){ //choose a type

   case 1: //number

    return rand(0,9);

    break;

   case 2://lcase

    return chr(rand(97,122));

    break;

   case 3://ucase

    return chr(rand(65,90));

    break;

  }

 }



?>