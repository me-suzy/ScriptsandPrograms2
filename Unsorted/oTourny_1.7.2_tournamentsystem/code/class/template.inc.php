<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Standerd Templates

 */



 class tlbhdrcnt { //toolbar+header+content

  var $toolbar;

  var $header;

  var $content; var $cnt_tpl;



  function tlbhdrcnt(){global $centercol;

   $centercol = "tlb_hdr_cnt.tpl";

  }



  //set toolbar text

  function set_tlb($txt){global $tpl;

   $tpl->assign(array("TOOLBAR" => $txt));

  }



  //set header text

  function set_hdr($txt){global $tpl;

   $tpl->assign(array("HEADER" => $txt));

  }



  //set content text

  function set_cnt($txt, $template = 0){global $tpl;

   $tpl->assign(array("CONTENT" => ($template == 0 ? $txt : $tpl->fetchfile($txt)) ));

  }



  //get content text

  function get_cnt(){global $tpl;

   return $tpl->get_assigned("CONTENT");

  }



  //hides page

  function hide(){global $tpl;

   $tpl->assign(array("CONTENT" => ""));

  }



 }



 class hdrtlbcnt { //header+toolbar+content

  var $toolbar;

  var $header;

  var $content; var $cnt_tpl;



  function hdrtlbcnt(){global $centercol;

   $centercol = "hdr_tlb_cnt.tpl";

  }



  //set toolbar text

  function set_tlb($txt){global $tpl;

   $tpl->assign(array("TOOLBAR" => $txt));

  }



  //set header text

  function set_hdr($txt){global $tpl;

   $tpl->assign(array("HEADER" => $txt));

  }



  //set content text

  function set_cnt($txt, $template = 0){global $tpl;

   $tpl->assign(array("CONTENT" => ($template == 0 ? $txt : $tpl->fetchfile($txt)) ));

  }



  //get content text

  function get_cnt(){global $tpl;

   return $tpl->get_assigned("CONTENT");

  }



  //hides page

  function hide(){global $tpl;

   $tpl->assign(array("CONTENT" => ""));

  }



 }



?>