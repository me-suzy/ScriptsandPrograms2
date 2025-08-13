<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Toolbar

 */



 class form_toolbar {

  var $buttons, $cols, $parse_str;



  function form_toolbar($cols = 5){

   $this->cols = $cols;//cols per row

  }



  function add($txt = '', $link = ''){

   if($txt =='' || $link == '') return 0;



   $i = count($this->buttons);



   $this->buttons[$i][0] = $txt;

   $this->buttons[$i][1] = $link;



   return 1;

  }



  //specify the cols for the table

  function fetch_table($fCol, $lCol){global $tpl;

   if($fCol >= $lCol) return;



   $tpl->clear("COLS"); $tpl->clear("ROWS"); $tpl->clear("TLB_TABLE");



   for($i=$fCol;$i<=$lCol; $i++)

   if($this->buttons[$i][0] != ''){

    $tpl->assign(array(

      "TXT" => $this->buttons[$i][0],

      "LINK" => $this->buttons[$i][1]

     ));



    $tpl->parse("COLS", ".TLB_COL");

   }



   //parse

   $tpl->parse("ROWS", "TLB_ROW");

   $tpl->parse("TLB_TABLE", "TLB_TABLE");

   $table = $tpl->fetch("TLB_TABLE");



   return $table;

  }



  //parses toolbar

  //use assign for quick parse assignment to template

  //0 will return toolbar - defaults to TOOLBAR

  function parse(

    $template_table = "toolbar_table.tpl", $template_row = "toolbar_row.tpl", $template_col = "toolbar_col.tpl",

    $CLASS_TABLE = "panel", $CLASS_TR = "adminbar", $CLASS_TD = "adminbar", $CLASS_A = "adminbar"

   ){global $tpl;



   //define files

   $tpl->define(array(

     "TLB_TABLE" => $template_table,

     "TLB_ROW" => $template_row,

     "TLB_COL" => $template_col,

    ));



   //set styles

   $tpl->assign(array(

     "TB_CLASS_TABLE" => $CLASS_TABLE,

     "TB_CLASS_TR" => $CLASS_TR,

     "TB_CLASS_TD" => $CLASS_TD,

     "TB_CLASS_A" => $CLASS_A,

    ));



   for($i=0;$i<count($this->buttons); $i+=$this->cols)

    $str .= $this->fetch_table($i, $i+$this->cols-1);



   //remove all the vars

   $tpl->assign(array(

     "ROWS" => "",

     "COLS" => "",

     "TLB_TABLE" => ""

    ));



   $tpl->clear("TLB_TABLE");

   $tpl->clear("TLB_ROW");

   $tpl->clear("TLB_COL");



   return $this->parse_str = $str;

  }

 }



?>