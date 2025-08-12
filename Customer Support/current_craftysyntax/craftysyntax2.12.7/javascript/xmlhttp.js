var xmlhttp = false; 
var XMLHTTP_supported = false;
/*@cc_on @*/ 
/*@if (@_jscript_version >= 5) 
// JScript gives us Conditional compilation, we can cope with old IE versions. 
// and security blocked creation of the objects. 
  try { 
  xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); 
  } catch (e) { 
   try { 
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); 
   } catch (E) { 
    xmlhttp = false; 
   } 
  } 
@end @*/ 
if (!xmlhttp && typeof XMLHttpRequest!='undefined') { 
  xmlhttp = new XMLHttpRequest(); 
} 

function loadXMLHTTP() { 
     // account for cache..
     randu=Math.round(Math.random()*99);
     // load a test page page:
     loadOK('xmlhttp.php?whattodo=ping&rand='+ randu); 
} 

function loadOK(fragment_url) { 
    xmlhttp.open("GET", fragment_url); 
    xmlhttp.onreadystatechange = function() { 
     if (xmlhttp.readyState == 4 && xmlhttp.status == 200) { 
       isok = xmlhttp.responseText;
       if(isok == "OK")
          XMLHTTP_supported = true;   
          checkXMLHTTP();
     } 
    } 
    xmlhttp.send(null); 
}


 // XMLHTTP ----------------------------------------------------------------- 
 // Code by Kent (design4effect)
 // ActiveX GETs require send(), Mozilla requires send(null) 
      var oXMLHTTP_ActiveX = true;      // Most common case is running on IE/Windows 
      var bSubmitting      = false;      // For keeping track of Submit button 
     
     
      // XMLHTTP State Handler 
      function oXMLHTTPStateHandler() { 
         // only if req shows "loaded" 
         if(oXMLHTTP){
          if( oXMLHTTP.readyState==4 ) {         // 4="completed" 
            bSubmitting = false;      // Enable SUBMIT button 
            if( oXMLHTTP.status==200 ) {         // 'OK Operation successful                
               try { 
                 resultingtext = oXMLHTTP.responseText;
               } catch(e) { 
                 resultingtext ="error=1;";
               }
               ExecRes(unescape(resultingtext)); 
               delete oXMLHTTP; 
               oXMLHTTP=false;
               //DEBUG:SetStatus('Response received... Now Processing',0); 
            } else { 
            	return false;
               //DEBUG:alert( "There was a problem receiving the data.\n" 
               //         +"Please wait a few moments and try again.\n" 
               //         +"If the problem persists, please contact us.\n" 
               //  +oXMLHTTP.getAllResponseHeaders() 
               //       ); 
             }   
           }
         } 
      } 
      
      // Submit POST data to server and retrieve results 
      function PostForm(sURL, sPostData) { 
         if( window.ActiveXObject ) { 
            try { 
               oXMLHTTP = new ActiveXObject("Microsoft.XMLHTTP"); 
            } catch(e) { 
               try { 
                  oXMLHTTP = new ActiveXObject("Msxml2.XMLHTTP"); 
               } catch(e) { 
                  oXMLHTTP = false; 
               }   
            } 
            oXMLHTTP_ActiveX = true; 
         } else if( window.XMLHttpRequest ) { 
            oXMLHTTP = new XMLHttpRequest(); 
            oXMLHTTP_ActiveX = false; 
         } 
         if( typeof(oXMLHTTP)!="object" ) return false; 
         
         oXMLHTTP.onreadystatechange = oXMLHTTPStateHandler; 
         try { 
            oXMLHTTP.open("POST", sURL, true); 
         } catch(er) { 
            //DEBUG: alert( "Error opening XML channel\n"+er.description ); 
            return false; 
         }    
         oXMLHTTP.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
         oXMLHTTP.send(sPostData); 
         return true; 
      }  
      
      // Submit GET data to server and retrieve results 
      function GETForm(sURL) { 
         if( window.ActiveXObject ) { 
            try { 
               oXMLHTTP = new ActiveXObject("Microsoft.XMLHTTP"); 
            } catch(e) { 
               try { 
                  oXMLHTTP = new ActiveXObject("Msxml2.XMLHTTP"); 
               } catch(e) { 
                  oXMLHTTP = false; 
               }   
            } 
            oXMLHTTP_ActiveX = true; 
         } else if( window.XMLHttpRequest ) { 
            oXMLHTTP = new XMLHttpRequest(); 
            oXMLHTTP_ActiveX = false; 
         } 
         if( typeof(oXMLHTTP)!="object" ) return false; 
         
         oXMLHTTP.onreadystatechange = oXMLHTTPStateHandler; 
         try { 
            oXMLHTTP.open("GET", sURL); 
         } catch(er) { 
            //DEBUG: alert( "Error opening XML channel\n"+er.description ); 
            return false; 
         }    
         oXMLHTTP.send(null); 
         return true; 
      }        