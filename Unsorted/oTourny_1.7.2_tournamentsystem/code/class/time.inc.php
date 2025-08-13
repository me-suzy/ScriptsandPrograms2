<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Time Conversion Class

 */



 //Entry time - take user time entry and adjust

 class entry_time {

  var $timestamp;

  var $trim;

  var $adjusted;



  function entry_time($entry, $trim = 2, $adjust = true){

   if($entry == ''){

    //they didnt enter anything

    $this->timestamp = 0;



    return;

   }



   //grab time

   $this->timestamp = strtotime($entry);

   $this->trim      = $trim;

   $this->adjusted  = $adjust;



   if($this->adjusted) $this->adjust();

  }



  //change user entry time to server time

  function adjust(){global $user;

   //add time user's offset

   if($user->id > 0)

    $this->timestamp -= $user->get("time_offset");

  }



  //retrieve timestamp

  function get(){

   return $this->timestamp;

  }

 }



 //Time - Used to adjust and get dates of a timestamp

 class time {

  var $timestamp; //unix timestamp

  var $data;      //getdate array

  var $adjust = FALSE;    //time adjusted to user?



  //timestamp : 0 - set to now

  function time($timestamp = FALSE, $adjust = TRUE){

   $this->set($timestamp, $adjust);

  }



  //set the current time

  function set($timestamp = FALSE, $adjust = FALSE){

   if($timestamp === FALSE) //set to now

    $timestamp = time();



   if($timestamp == '') return; //invalid time



   if($adjust){ //adjust time to their time zone

    $this->adjust($timestamp);

    return;

   }



   //grab time stamp

   if(!$timestamp > 0) return; //invalid time



   //save timestamp

   $this->timestamp = (INT) $timestamp;



   //grab date data

   $this->data = @getdate($this->timestamp);

  }



  //adjust timestamp to user's time zone

  function adjust($timestamp){global $user;

   if(!$user->id > 0) return; //Cant Adjust if they are not a user



   //dont set as adjusted if there is no diff

   if($user->get("time_offset") == 0) return $this->set($timestamp);



   //set to adjusted time

   if($user->id > 0) //valid user

    $this->set($timestamp + $user->get("time_offset"), false);



   //save that it was adjusted

   $this->adjust = true;

  }



  //grab info

  function get($item){

   return $this->data[$item];

  }



  //get formated text of time

  function get_formated($format = FALSE){global $user;

   if($format === FALSE)//grab user format

    if($user->id > 0) //valid user

     $format = $user->get("time_format");



   //check for null formats

   if($format == '') $format = $this->get_format_default();



   if($this->timestamp > 60) //gotta be a real time

    return date($format, $this->timestamp);

  }



  //Grabs default format text

  function get_format_default($zone = NULL){

   if($this->adjust || $zone === false) //its their time zone, so dont show zone

    return "g:i A F j, Y"; // 5:16 PM March 10, 2001

   else  //non adjusted, IE server time

    return "g:i A F j, Y T"; // 5:16 PM March 10, 2001 MST

  }



  //get diff of time

  function get_time_offset($timestamp, $trim = 0){

   //trim off unwanted diff

   if(strlen($timestamp) > $trim)

    $timestamp = substr($timestamp, 0, strlen($timestamp) - $trim) . substr($this->timestamp, - $trim);



   //find diff

   return $timestamp - $this->timestamp;

  }



 }



?>