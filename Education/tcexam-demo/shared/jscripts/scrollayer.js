//============================================================+
// File name   : scrollayer.js                             
// Begin       : 2001-10-25                                    
// Last Update : 2004-05-29                                    
//                                                             
// Description : script for automatic layer vertical scrolling                                  
//                                                             
//                                                             
// Author: Nicola Asuni                                        
//                                                             
// (c) Copyright:                                              
//               Tecnick.com S.r.l.                            
//               Via Ugo Foscolo n.19                          
//               09045 Quartu Sant'Elena (CA)                  
//               ITALY                                         
//               www.tecnick.com                               
//               info@tecnick.com                              
//============================================================+

var scroll_speed_scrollayer = 1; 
var pos_tollerance_scrollayer = Math.round(1 + (scroll_speed_scrollayer / 2));
var previousY_scrollayer = 0;
var newY_scrollayer = 0;
function scroll_object_scrollayer() {
	current_y = get_current_position_scrollayer();
	if ( (current_y < (previousY_scrollayer - pos_tollerance_scrollayer)) || (current_y > (previousY_scrollayer + pos_tollerance_scrollayer)) ) {
		newY_scrollayer = previousY_scrollayer + Math.round((current_y - previousY_scrollayer)/scroll_speed_scrollayer);
		previousY_scrollayer = newY_scrollayer;
		if (scroll_speed_scrollayer == 1) {
			set_visibility_scrollayer(0);
		}
		set_position_scrollayer(newY_scrollayer);
	}
	else {
		set_position_scrollayer(current_y);
		if (scroll_speed_scrollayer == 1) {
			set_visibility_scrollayer(1);
		}
	}
	window.setTimeout("scroll_object_scrollayer()",10);
}
function set_visibility_scrollayer(visibility_value) {
	if (!visibility_value) {
		visibility_value='hidden';
	}
	else {
		visibility_value='visible';
	}
	if (document.all) {document.all.scrollayer.style.visibility = visibility_value;}
	else if (document.layers) {document.layers['scrollayer'].visibility = visibility_value;}
	else if (!document.all && document.getElementById) {document.getElementById('scrollayer').style.visibility = visibility_value;}
}
function set_position_scrollayer(new_position) {
	if (document.all) {document.all.scrollayer.style.pixelTop = new_position;}
	else if (document.layers) {document.layers['scrollayer'].top = new_position;}
	else if (!document.all && document.getElementById) {document.getElementById('scrollayer').style.top = new_position + "px";}
}
function get_current_position_scrollayer() {
	var current_y = 0;
	if (document.layers) {current_y = window.pageYOffset;}
	else if(!document.all && document.getElementById) {current_y = scrollY;} 
	else if(document.all) {current_y = document.body.scrollTop;}
	return current_y;
}
scroll_object_scrollayer();

// -------------------------------------------------------------------------
// END OF SCRIPT
// -------------------------------------------------------------------------