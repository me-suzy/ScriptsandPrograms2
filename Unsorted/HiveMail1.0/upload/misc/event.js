event_list= new Array();

event_level= 0;
if (document.implementation)
  if (document.implementation.hasFeature('Events', '2.0'))
    event_level= 2;

function event_addListener(esource, etype, elistener) {
  var i;
  var alreadyTriggering= false;
  for (i= 0; i<event_list.length; i++) {
    if (event_list[i][0]==esource && event_list[i][1]==etype) {
      if (event_list[i][2]==elistener) return;
      alreadyTriggering= true;
  } }
  event_list[event_list.length]= new Array(esource, etype, elistener);
  if (!alreadyTriggering) {
    if (event_level==2) {
      esource.addEventListener(etype, event_trigger_DOM2, true);
    } else {
      esource['on'+etype] = new Function("e", "event_dispatch(this, '" + etype + "', e)");
      if (esource.captureEvents)
        esource.captureEvents('Event.'+etype.toUpperCase());
  } }
}

function event_removeListener(esource, etype, elistener) {
  var i; var e;
  var j= 0;
  var removedListener= false;
  var keepTrigger= false;
  for (i= 0; i<event_list.length; i++) {
    if (event_list[i][0]==esource && event_list[i][1]==etype) {
      if (event_list[i][2]==elistener) {
        removedListener= true;
        continue;
      }
      else keepTrigger= true;
    }
    if (i!=j) event_list[j]= event_list[i];
    j++;
  }
  event_list.length= j;
  if (removedListener && !keepTrigger) {
    if (event_level==2)
      esource.removeEventListener(etype, elistener, true);
    else
      esource['on'+etype]= window.clientInformation ? null : window.undefined;
  }
}

function event_trigger_DOM2(e) {
  if (event_dispatch(this, e.type, e)==false)
    e.preventDefault();
}

function event_dispatch(esource, etype, e) {
  e = event_DOMify(e);
  var i; var r;
  var elisteners= new Array();
  var result= window.undefined;
  for (i= 0; i<event_list.length; i++)
    if (event_list[i][0]==esource && event_list[i][1]==etype)
      elisteners[elisteners.length]= event_list[i][2];
  for (i= 0; i<elisteners.length; i++) {
    r= elisteners[i](e);
    if (r+''!='undefined') result= r;
  }
  return result;
}

function event_prevent(esource, etype) { return false; }

function event_DOMify(e) {
	if (!e && window.event) 
		e = window.event;
	
	if (!e.target && e.srcElement) {
		e.target = e.srcElement;
		e.originalTarget = e.srcElement;
	}
	if (typeof e.layerX == "undefined" && typeof e.offsetX != "undefined") {
		e.layerX = e.offsetX;
		e.layerY = e.offsetY;
	}

	if (!e.preventDefault) e.preventDefault = event_iePreventDefault;
	if (!e.stopPropagation) e.stopPropagation = event_ieStopPropagation;

	return e;
}

function event_iePreventDefault() {
	this.returnValue = false;
}

function event_ieStopPropagation() {
	this.cancelBubble = true;
}