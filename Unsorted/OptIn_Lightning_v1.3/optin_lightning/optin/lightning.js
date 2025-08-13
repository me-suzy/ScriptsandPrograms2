//////////////////////////////////////////////////////////////////////
//															
//                     Opt-In Lightning
//                      			  
//     Main Script Code By Gary Ambrose ( panic@panicweb.net )
//    Additional Code By Donald Rowell ( drowell@lordjava.com )
//           
//       Distributed By And Designed For Brian Garvin of
//              http://www.optinlightning.com				 
// 														  
//                    Copyright <c> 2001     			
//														
//     Selling or distributing this software in whole or              
//     in part or of any modification of this software without 		 		 
//     written permission is expressly forbidden. Permission			
//     to modify the script for personal use on the domain for
//     which this script is licensed is granted to the purchaser.      
//     In all cases this full header and copyright information        
//	   must remain fully intact. Any and All violators will be 			
//	   PROSECUTED to the fullest extent of the law.						
//															
//////////////////////////////////////////////////////////////////////
//                 
//                         lightning.js
//
//  javascript code used in conjunction with the auto-capture system 
//   ONLY EDIT THE ONE LINE OF CODE BETWEEN THE EDIT COMMENTS BELOW
//
//////////////////////////////////////////////////////////////////////

function setCookie(name, value, expire)
{document.cookie = name + "=" + escape(value) + ((expire == null) ? "" : ("; expires=" + expire.toGMTString()))}

function getCookie(Name)
{var search = Name + "=";if (document.cookie.length > 0)
{offset = document.cookie.indexOf(search);
if (offset != -1) {offset += search.length;end = document.cookie.indexOf(";",offset);
if (end == -1)
end = document.cookie.length;return unescape(document.cookie.substring(offset, end))
		}
	}
}

function setBeenHere()
{ var Value="thunder";setCookie("thunder",Value,null);
	}

function DoIt()
{ if (navigator.appVersion.indexOf("AOL") == -1){var GetIt=getCookie("thunder");
	if(GetIt==null){
		if (confirm(
///////////////////////////////////////////////////////////////////
//////// EDIT THE NEXT LINE ONLY - EDIT THE NEXT LINE ONLY ////////
///////////////////////////////////////////////////////////////////
"Please Join Our Free Newsletter.\nTo Join Simply Click O.K. Below.\n\nYou may unsubscribe at any time.\nYour email will never be sold.\n "))
///////////////////////////////////////////////////////////////////
////// DO NOT EDIT ANY FURTHER - DO NOT EDIT ANY FURTHER //////////
///////////////////////////////////////////////////////////////////
{setBeenHere();
document.lightning.button.click();
} else
	{setBeenHere();
			}
		}
	}
}

function NukeIt()
{setCookie("lightning",null,null);
	}

var counted=0;

