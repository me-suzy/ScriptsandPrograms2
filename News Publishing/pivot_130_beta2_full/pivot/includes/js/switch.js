
/**
 * Textarea resizer stuff..
 *
 * This code is based on the work of max: http://www.doenietzomoeilijk.nl,
 * and is used with his permission.
 *
 */
 
/* Resizebox start */
var startpos, diffpos=0, currentresizer = "", currentheight="";
var handled = false;
var is_safari;
if ( navigator.userAgent.match( 'Safari' ) ) { is_safari = true; }

function DMD(Event) {

	if (is_safari) {
		startpos = Event.pageY;
	} else if (!document.all) {
		startpos = Event.screenY;
	} else {
		startpos = event.clientY;
	}
	
	currentresizer = this.resizer;
	currentheight = parseInt(document.getElementById(currentresizer).style.height);
	
	handled = true;
	return false;
}

function DMU(Event) {
	handled = false;
	return false;
}

function DMM(Event) {
	if (handled) {
		if ( is_safari ) { 
			curpos = Event.pageY;
		} else if (!document.all) {
			curpos = Event.screenY;
		} else {
			curpos = event.clientY;
		}
				
		diffpos = startpos - curpos;
		if (diffpos > -800 && diffpos < 400) {
			document.getElementById( currentresizer ).style.height = currentheight - diffpos + 'px';
		}
	}
}





addEvent(window, 'load', function()
{

	// make sure opera doesn't add 5 resizers.
	if (handled==true) { return; }
	handled=true;
	
	var elms = document.getElementsByClassName('resizable');
	for (i=0; i<elms.length; i++)
	{
		var elm = elms[i];
				
		// wrapper around textarea..		
		wrapper = document.createElement('DIV');
		wrapper.className = 'resizerWrapper';
		elm.parentNode.replaceChild(wrapper, elm);
		wrapper.appendChild(elm);
		wrapper.style.width = elm.style.width;
				
		// add the resizer to the wrapper		
		bigger = document.createElement('HR');
		bigger.resizer = elm.id;
		bigger.noshade = "noshade";
		bigger.className = "resizer";
		bigger.title = "Drag me..";
		wrapper.appendChild(bigger);

		// attach events..
		bigger.onmousedown = DMD;
		document.onmouseup = DMU;
		document.onmousemove = DMM;
		
	}

});

/**
 * getElementsByClassName
 */ 
document.getElementsByClassName = function (needle)
{
  var my_array = document.getElementsByTagName("*");
  var retvalue = new Array();
  var i;
  var j;

  for (i=0, j=0; i < my_array.length; i++)   {
    var c = " " + my_array[i].className + " ";
    if (c.indexOf(" " + needle + " ") != -1) {
      retvalue[j++] = my_array[i];
    }
  }
  return retvalue;
}

/**
 * addEvent
 */ 
function addEvent(obj, evType, fn)
{
	if (obj.addEventListener) {
		obj.addEventListener(evType, fn, true);
		return true;
	} 
	else if (obj.attachEvent) {
		var r = obj.attachEvent("on"+evType, fn);
		return r;
	} else {
		return false;
	}
}






/**
 * Switch stuff..
 */
 
function moveOver(from,to) {
	// Move them over
	for (var i=0; i<from.options.length; i++) {
		var o = from.options[i];
		if (o.selected) {
			to.options[to.options.length] = new Option( o.text, o.value, false, false);
			}
		}
	// Delete them from original
	for (var i=(from.options.length-1); i>=0; i--) {
		var o = from.options[i];
		if (o.selected) {
			from.options[i] = null;
			}
		}
		
	sortSelect(from);
	sortSelect(to);
	
	from.selectedIndex = -1;
	to.selectedIndex = -1;
}

function sortSelect(obj) {
	var o = new Array();
	if (obj.options==null) { return; }
	for (var i=0; i<obj.options.length; i++) {
		o[o.length] = new Option( obj.options[i].text, obj.options[i].value, obj.options[i].defaultSelected, obj.options[i].selected) ;
		}
	if (o.length==0) { return; }
	o = o.sort( 
		function(a,b) { 
			if ((a.text+"") < (b.text+"")) { return -1; }
			if ((a.text+"") > (b.text+"")) { return 1; }
			return 0;
			} 
		);

	for (var i=0; i<o.length; i++) {
		obj.options[i] = new Option(o[i].text, o[i].value, o[i].defaultSelected, o[i].selected);
		}
	}


	function selectAll() {
	
		var elts = document.getElementsByTagName('select');

		// do this for all <select>'s in document.
	    for (var i = 0; i < elts.length; i++) {

	    	// if the size > 1, meaning it's a multi-box, instead of a normal select..
	    	if(elts[i].size > 1 ) {
	    	
				// for each <select>, we now select all the options
				for (var j = 0; j < elts[i].length; j++) {
					elts[i].options[j].selected = 1;
				}
	    	}
		}
	}
	
	
	function open_preview(code) { 
  window.open("entry.php?id="+code,"preview","toolbar=yes, status=yes, location=yes, scrollbars=yes, resizable=yes, width=600, height=450");
}

function open_win(url, title, params) { 
  if (window.open(url, title, params)) {
	} else {
		alert("popup was blocked by your browser");
		document.getElementById("note").innerHTML = "<div style=\"border:1px solid #999; padding: 4px;\" click <a href=\'"+url+"\' onclick=\'window.open(\""+url+"\", \""+title+"\", \""+params+"\");return false;\'>here</a> to open the requested window.</div>";
	}
}


function setCheckboxes(the_form, do_check) {
	var elts      =  document.getElementsByTagName("input");
	var elts_cnt  = (typeof(elts.length) != "undefined") ? elts.length : 0;
	if (elts_cnt) {
		for (var i = 0; i < elts_cnt; i++) {             
			elts[i].checked = do_check;         
		}      
	} else {
		elts.checked = do_check;
     }       
    return true; 
}  

