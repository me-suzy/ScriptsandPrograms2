/*----------------------[      General Module      ]-----------------------*/

/*                                                                         */

/*   This JavaScript module is a copyright of Svetlin Staev. It could be   */

/*  copied, modified and/or reproduced in any form let it be private or    */

/*  public with the only means of recognition to its authors in the form   */

/*  of this copyright message.                                             */

/*                                                                         */

/*  Date   : 01/03/2001                                                    */

/*  Author : Svetlin Staev (svetlin@developer.bg)                            */

/*                                                                         */

/*     Copyright(c)2001,2002 Infinity Interactive. All rights reserved.    */

/*-------------------------------------------------------------------------*/

function getStatus(msg)

   {

      window.status=msg;

   }

function search()

   {

      if (document.searchForm.str.value.length < 3) alert("You search word is too small");

      else document.searchForm.submit()

   }



function LightOn(theRow, thePointerColor)

   {

      if (thePointerColor == '' || typeof(theRow.style) == 'undefined') {

         return false;

      }

      if (typeof(document.getElementsByTagName) != 'undefined') {

         var theCells = theRow.getElementsByTagName('td');

      }

      else if (typeof(theRow.cells) != 'undefined') {

         var theCells = theRow.cells;

      }

      else {

         return false;

      }



      var rowCellsCnt  = theCells.length;

      for (var c = 0; c < rowCellsCnt; c++) {

         theCells[c].style.backgroundColor = thePointerColor;

         theCells[c].style.cursor = "pointer";

      }



      return true;

   } // end of the 'setPointer()' function



function openInfo(id)

   {

      openRemote('productInfo.php?id='+id, 'Products', 550, 380, null, null, 'none.gif', 'close_dwn.gif', 'close_up.gif', 'close_ovr.gif', 'mini_dwn.gif', 'mini_up.gif', 'mini_ovr.gif', 'clock.gif', '<font face=verdana size=1>&nbsp;:: Products Information ::</font>', 'Products Information', 'black', 'black', '8EC056', 'AED286');

   }

function truePollPreview(id)

   {

      openRemote('preview.phtml?poll='+id, 'truePoll', 400, 300, null, null, 'none.gif', 'close_dwn.gif', 'close_up.gif', 'close_ovr.gif', 'mini_dwn.gif', 'mini_up.gif', 'mini_ovr.gif', 'clock.gif', '<font face=verdana size=1>&nbsp;:: truePoll Preview ::</font>', 'truePoll Preview', 'black', 'black', '8EC056', 'AED286');

   }

function copyright()

   {

      w=document.all?283:288;

      h=document.all?340:355;

      l=(screen.width-w)/2;

      t=(screen.height-h)/2;

      c=window.open('about.php','_copyright','fullscreen,scrollbars,scroll=no');

      c.blur();

      window.focus();

      c.resizeTo(w,h);

      c.moveTo(l,t);

      c.focus();

   }





function view(id)

   {

      var path = "images/remote/";

      openRemote('view.php?id='+id, 'View', 600, 500, null, null, path+'none.gif', path+'close_dwn.gif', path+'close_up.gif', path+'close_ovr.gif', path+'mini_dwn.gif', path+'mini_up.gif', path+'mini_ovr.gif', path+'clock.gif', '<font face=verdana size=1>&nbsp;:: View Request ::</font>', 'View Request', 'black', 'black', '#B9C5D4', '#CAD6E4');

   }

function deny(id)

   {

      var path = "images/remote/";

      openRemote('deny.php?id='+id, 'Deny', 600, 180, null, null, path+'none.gif', path+'close_dwn.gif', path+'close_up.gif', path+'close_ovr.gif', path+'mini_dwn.gif', path+'mini_up.gif', path+'mini_ovr.gif', path+'clock.gif', '<font face=verdana size=1>&nbsp;:: Deny Request ::</font>', 'Deny Request', 'black', 'black', '#B9C5D4', '#CAD6E4');

   }

function request(id)

   {

      var path = "images/remote/";

      openRemote('request.php?id='+id, 'Request', 600, 180, null, null, path+'none.gif', path+'close_dwn.gif', path+'close_up.gif', path+'close_ovr.gif', path+'mini_dwn.gif', path+'mini_up.gif', path+'mini_ovr.gif', path+'clock.gif', '<font face=verdana size=1>&nbsp;:: Make A New Request ::</font>', 'Make A New Request', 'black', 'black', '#B9C5D4', '#CAD6E4');

   }

function addAddress(id, resolved)

   {

      var path = "images/remote/";

      openRemote('address.php?action=addAccount&resolvedName='+resolved+'&id='+id, 'Address', 400, 180, null, null, path+'none.gif', path+'close_dwn.gif', path+'close_up.gif', path+'close_ovr.gif', path+'mini_dwn.gif', path+'mini_up.gif', path+'mini_ovr.gif', path+'clock.gif', '<font face=verdana size=1>&nbsp;:: Address Book ::</font>', 'Address Book', 'black', 'black', '#B9C5D4', '#CAD6E4');

   }

function seeTransaction(id)

   {

      window.location.href = "transfer.php?cmd=history&tid="+id;

   }