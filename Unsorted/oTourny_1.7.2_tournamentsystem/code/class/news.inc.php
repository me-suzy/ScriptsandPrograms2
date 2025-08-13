<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

/**

* News Generator Class

*

*/



  class news {

   var $tpl = "news.tpl"; //location of the news template

   var $count; //count of how many news items to show

   var $items = array(); //all news items

   var $item_id; //holds all ids for sorting



   function news($count = 10){

    $this->count = $count;

   }



   //add news item to array

   function add_news($id, $subject, $text){

    //echo $id . "<br>". $subject . "<br>" . $text . "<hr>";



    //save item array for sorting

    $this->item_id[] = $id;



    //save items

    $this->items[$id]["subject"] = $subject;

    $this->items[$id]["text"]    = $text;

   }



   //create the news template

   function generate(){global $tpl;

    $tpl->splice("NEWS", "news.tpl");



    //sort in reverse

    rsort($this->item_id);



    //run through each news item

    for($i = 0;$i < $this->count;$i++){

     $id = $this->item_id[$i]; //grab id



     if($this->items[$id]["text"] != '' && $this->items[$id]["subject"] != '')

      $tpl->parse("NEWS->ITEM", "NEWS->ITEM", TRUE, array(

        "TEXT"    => $this->items[$id]["text"],

        "SUBJECT" => $this->items[$id]["subject"]

       ));

    }



    //save news

    $tpl->save("news.tpl", $tpl->parse("NEWS", "NEWS"));

   }



  }



?>