<?
//****************************************************************************************/
//  Crafty Syntax Live Help (CSLH)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// Crafty Syntax Live Help (CSLH) http://www.craftysyntax.com/livehelp/
// Copyright (C) 2003  Eric Gerdes 
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program in a file named LICENSE.txt .
// if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------  
// MODIFICATIONS: 
// ---------------------------------------------------------  
// [ Programmers who change this code should cause the  ]
// [ modified changes here and the date of any change.  ]
//======================================================================================
//****************************************************************************************/
require("globals.php");

if (!isset($rand_id)) {
    // get random
   srand(time());
   $letters = array ("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
   $index = rand(0,35);
   $random = "$letters[$index]";
   for ($z = 1; $z < 9; $z++){
       $index = rand(0,35);
       $random = "$random" . "$letters[$index]";
   }
   $rand_id = $random;
   setcookie ("rand_id", $random,time()+3600, "/");

}

if($identity == ""){
 if($REMOTE_ADDR == ""){
    $REMOTE_ADDR = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 
 }
 if($HTTP_USER_AGENT == ""){
    $HTTP_USER_AGENT = $HTTP_SERVER_VARS["HTTP_USER_AGENT"]; 
 }
 $identity = $REMOTE_ADDR . $HTTP_USER_AGENT . $rand_id;
 $referer = $HTTP_REFERER;
 $identity = ereg_replace(" ","",$identity);
 setcookie ("identity", $identity,time()+3600, "/");
}

include("config.php");
print "var WEBPATH = \"" . $webpath . "\"; ";
?>
//-----------------------------------------------------------------
// File: livehelp.js :
//      - This is the client side Javascript file to control the 
//        image shown on the clients website. It should be called
//        on the clients HTML page as a javascript include such as:
//        script src="http://yourwebsite.com/livehelp/livehelp_js.php"
//        This js file will show the image of online.gif if an operator
//        is online otherwise it will show offline.gif . Also a 
//        second image is placed on the site as a control image 
//        where the width of the image controls the actions made by 
//        the operator to the poor little visitor..  
// 
//-----------------------------------------------------------------

// GLOBALS..
//------------
// This is the control image where the width of it controls the 
// actions made by the operator. 
cscontrol= new Image;
// this is a flag to control if the image is set on the page 
// yet or not..
var csloaded = false;

// just to make sure that people do not just open up the page 
// and leave it open the requests timeout after 999 requests.
var csTimeout = 99;

// The id of the page request. 
var csID = null;

// if the operator requests a chat we only want to open one window... 
var openLiveHelpalready = false;

//-----------------------------------------------------------------
// loop though checking the image for updates from operators.
function csrepeat()
{
     // if the request has timed out do not do anything.
     if (csTimeout < 0)
	return;
     
     csTimeout--;

     // update image for requests from operator. 
     csgetimage();
          
     // check image for updates from operators.
     cslookatimage();

     // do it again. 
     setTimeout('csrepeat()', 3000);
}	

//-----------------------------------------------------------------
// Update the control image. This is the image that the operators 
// use to communitate with the visitor. 
function csgetimage()
{	 
	 // set a number to identify this page .
	 if (csID==null) csID=Math.round(Math.random()*9999);
        
	 var u = WEBPATH + 'image.php?' + 
					'cmd=userstat' + 
					'&page=' + escape(document.location) + 
					'&title=' + escape(document.title) + 
					'&referrer=' + escape(document.referrer) + 
					'&pageid=' + csID +
					'&department=' + <?= $department ?>;
	 cscontrol.src = u;
	 csloaded = true;
}

// looks at the size of the control image and if the width is 55 
// then open the chat.
//-----------------------------------------------------------------
function cslookatimage()
{
	if (csloaded) {
		var w = cscontrol.width;
		if (w == 0)
			return;
		csloaded = false;
		if ((w == 55) && (openLiveHelpalready == false)) {
  		   openWantsToChat();
		   openLiveHelpalready = true;
	        } 
	}
}


//-----------------------------------------------------------------
// opens live help
function openLiveHelp()
{
  window.open(WEBPATH + 'livehelp.php?department=<?= $department ?>', 'chat54050872', 'width=540,height=390,menubar=no,scrollbars=0,resizable=1');
}

//-----------------------------------------------------------------
// The Operator wants to chat with the visitor about something. 
function openWantsToChat()
{  
  // ok we asked them .. now lets not ask them again for awhile...
  var u = WEBPATH + 'image.php?' + 
					'cmd=browse' + 
					'&page=' + escape(document.location) + 
					'&title=' + escape(document.title) + 
					'&referrer=' + escape(document.referrer) + 
					'&pageid=' + csID +
					'&department=' + <?= $department ?>;
  cscontrol.src = u;  

  // open the window.. 
  window.open(WEBPATH + 'livehelp.php?cmd=chatinsession&department=<?= $department ?>', 'chat54050872', 'width=540,height=390,menubar=no,scrollbars=0,resizable=1');
}

<? 
 if ($cmd != "hidden"){
?>
//table  holding the live help icon... 
document.write('<table border="0" cellspacing="0" cellpadding="0">');
document.write('<tr>');
document.write('<td align="center" valign="top"><a name="chatRef" href="javascript:openLiveHelp()"><img name="csIcon" src="' + WEBPATH + 'image.php?cmd=getstate&department=<?= $department ?>" alt="Powered by CSLH" border="0"></a></td>');
document.write('</tr>');
document.write('<tr>');
document.write('<td align="center" valign="top"><a name="byRef" href="http://www.craftysyntax.com/livehelp/?v=<?= $version ?>" target="_blank"><img name="myIcon" src="' + WEBPATH + 'image.php?cmd=getcredit&department=<?= $department ?>" alt="Powered by CSLH" border="0"></a></td>');
document.write('</tr>');
document.write('</table>');
<? 
 } 
 // see if anyone is online.. 
 $query = "SELECT * FROM livehelp_users,livehelp_operator_departments WHERE livehelp_users.user_id=livehelp_operator_departments.user_id AND livehelp_users.isonline='Y' AND livehelp_users.isoperator='Y' AND livehelp_operator_departments.department='$department' ";
 $data = $mydatabase->select($query); 
 // getting the party started if someone is online..

 if( count($data) != 0){ ?>
   setTimeout('csrepeat()', 250);
<? } else { ?>
   setTimeout('csgetimage()', 250);
<? } ?>
