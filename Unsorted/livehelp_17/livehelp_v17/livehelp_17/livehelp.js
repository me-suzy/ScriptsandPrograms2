// GLOBAL web path.. 
// change this to be the full web path to your live help
// for example WEBPATH = "http://www.mywebsite/livehelp/"
// INCLUDE THE ENDING SLASH
// "http://www.mywebsite/livehelp/" NOT "http://www.mywebsite/livehelp"

var WEBPATH = "WEB-PATH"; 

// You should not have to change anything below this line...
//-------------------------------------------------------------

//****************************************************************************************/
//  Crafty Syntax Live Help (CS Live Help)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// CS LIVE HELP http://www.craftysyntax.com/livehelp/
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

//-----------------------------------------------------------------
// File: livehelp.js :
//      - This is the client side Javascript file to control the 
//        image shown on the clients website. It should be called
//        on the clients HTML page as a javascript include such as:
//        script src="http://yourwebsite.com/livehelp/livehelp.js"
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
var csIsImage = false;

// just to make sure that people do not just open up the page 
// and leave it open the requests timeout after 999 requests.
var csTimeout = 99;

// The id of the page request. 
var csPageID = null;

// if the operator requests a chat we only want to open one window... 
var openChatalready = false;

//-----------------------------------------------------------------
// loop though checking the image for updates from operators.
function csLoop()
{
     // if the request has timed out do not do anything.
     if (csTimeout < 0)
	return;
     
     csTimeout--;

     // update image for requests from operator. 
     csSendRequest();
          
     // check image for updates from operators.
     csCheckImages();

     // do it again. 
     setTimeout('csLoop()', 4000);
}	

//-----------------------------------------------------------------
// gets the current date 
function csDate()
{
	var d = new Date();
	return d.getTime();
}

//-----------------------------------------------------------------
// Update the control image. This is the image that the operators 
// use to communitate with the visitor. 

function csSendRequest()
{	 
	 // set a number to identify this page .
	 if (csPageID==null) csPageID=Math.round(Math.random()*99999);
        
	 var u = WEBPATH + 'image.php?' + 
					'cmd=userstat' + 
					'&page=' + escape(document.location) + 
					'&title=' + escape(document.title) + 
					'&referrer=' + escape(document.referrer) + 
					'&pageid=' + csPageID +
					'&d=' + csDate();
	 cscontrol.src = u;
	 csIsImage = true;
}
//-----------------------------------------------------------------
function csHandleWidth(w)
{
        // if the width of the returned image is 55 
        // we want to open a chat session. 
	if (w == 55) {
		openWantsToChat();
	} 
}
//-----------------------------------------------------------------
function csCheckImages()
{
	if (csIsImage) {
		var w = cscontrol.width;
		if (w == 0)
			return;
		csIsImage = false;
		csHandleWidth(w);
	}
}

//-----------------------------------------------------------------
function csReloadIcon()
{
	document.images['csIcon'].src = WEBPATH + '?cmd=repstate&site=54050872&d=' + csDate();
}

//-----------------------------------------------------------------
// 
function openChat()
{
  window.open(WEBPATH + 'livehelp.php?cmd=file&file=visitorWantsToChat&site=54050872&d=' + csDate(), 'chat54050872', 'width=472,height=320,menubar=no,scrollbars=0,resizable=1');
}

//-----------------------------------------------------------------
// The Operator wants to chat with the visitor about something. 
// 
function openWantsToChat()
{  
  // ok we asked them .. now lets not ask them again for awhile...
  var u = WEBPATH + 'image.php?' + 
					'cmd=browse' + 
					'&page=' + escape(document.location) + 
					'&title=' + escape(document.title) + 
					'&referrer=' + escape(document.referrer) + 
					'&pageid=' + csPageID +
					'&d=' + csDate();
  cscontrol.src = u;  

  // open the window.. 
  window.open(WEBPATH + 'livehelp.php?cmd=chatinsession', 'chat54050872', 'width=472,height=320,menubar=no,scrollbars=0,resizable=1');
}


//-----------------------------------------------------------------
// MAIN PROGRAM BLOCK.
//-----------------------------------------------------------------
document.write('<br><center>');
document.write('<table border="0" cellspacing="0" cellpadding="0">');
document.write('<tr>');
document.write('<td align="center" valign="top"><a name="chatRef" href="javascript:openChat()" target="_self"><img name="csIcon" src="' + WEBPATH + 'image.php?cmd=getstate&d=' + csDate() + '" alt="Powered by CS LIve Help" border="0"></a></td>');
document.write('</tr>');
document.write('<tr>');
document.write('<td align="center" valign="top"><a name="byRef" href="http://www.craftysyntax.com/livehelp/" target="_blank"><img name="myIcon" src="' + WEBPATH + 'image.php?cmd=getcredit" width=141 height=13 alt="Powered by CS LIve Help" border="0"></a></td>');
document.write('</tr>');
document.write('</table>');
document.write('</center>');

// getting the party started.. 
setTimeout('csLoop()', 250);

