<? /*************************************************************************************************
Copyright (c) 2003, 2004 Nathan 'Random' Rini
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*************************************************************************************************/ if(!defined('CONFIG')) die;
 /*
  Standerd Functions
 */

 //check for valid format email
 function checkemail($email){
  if((!ereg(".+\@.+\..+", $email)) || (!ereg("^[a-zA-Z0-9_@.-]+$", $email))) return 0;
  return 1;
 }

 function write_option($txt,$selected){
  return "<option ". ($selected?"selected":'') . ">". $txt ."</option>";
 }

 function write_refresh($page,$time=0){global $page_refreshing, $tpl;
  $tpl->assign(array(
    "LINK_REFRESH" => $page,
    "REFRESH_TIME" => $time
   ));

  //header( "Location: /" . $page );

  $page_refreshing = 1;
 }

 function clientip(){//gethostbyaddr($REMOTE_ADDR); <-- host name
  //grab their ip
  if(getenv(HTTP_CLIENT_IP))
   return getenv(HTTP_CLIENT_IP);
  else
  {
   if(getenv(HTTP_X_FORWARDED_FOR))
    return getenv(HTTP_X_FORWARDED_FOR);
   else
    return getenv(REMOTE_ADDR);
  }
 }

 //returns booleon from Yes/No strings
 function strgetbool($str){
  if($str == "Yes" || $str == "1") return 1;
  else return 0;
 }

 //returns Yes/No strings
 //for check guis
 function numtobool($str){
  if($str == "1") return " checked=\"true\" ";
  else return "";// " checked=\"false\" ";
 }

 function dict($string) //found in http://www.zend.com/
 {//$string=dict("[dict]foo[/dict]");
  return preg_replace('/\[dict\](.*?)\[\/dict\]/','\\1 [<a href=http://www.dictionary.com/search?q=\\1>?</a>]',$string);
 }

 function htmlchars($txt){
  return nl2br(htmlentities($txt, ENT_QUOTES));
 }

?>