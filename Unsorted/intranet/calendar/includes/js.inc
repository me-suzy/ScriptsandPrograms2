<SCRIPT LANGUAGE="JavaScript">
<!--
// The following code is used to support the small popups that
// give the full description of an event when the user move the
// mouse over it.

NS4 = (document.layers) ? 1 : 0;
IE4 = (document.all) ? 1 : 0;

function show ( name ) {
  //x = currentX;
  y = currentY + 20;
  if ( NS4 ) {
    //Position popup correctly
    var docWidth = document.width;   //width of current frame
    var docHeight = document.height;  //height of current frame
    var layerWidth = document.layers[name].clip.width;  //width of popup layer
    var layerHeight = document.layers[name].clip.height; //height of popup layer
    if ( ( currentX + layerWidth ) >= docWidth ) {
      x = ( currentX - layerWidth );
    } else {
      x = currentX;
    }
/********************
  if ( ( currentY + layerHeight ) >= docHeight ) {
    y = ( currentY - layerHeight - 20 );
  }
  else {
    y=currentY+20;
  }
*********************/
    document.layers[name].xpos = parseInt ( x );
    document.layers[name].left = parseInt ( x );
    document.layers[name].ypos = parseInt ( y );
    document.layers[name].top = parseInt ( y );
    document.layers[name].visibility = "show";
  } else {
    var docHeight = document.body.offsetHeight;
    var docWidth = document.body.offsetWidth;
    //var layerWidth = document.all[name].offsetWidth;
    // for some reason, this doesn't seem to work... so set it to 200
    var layerWidth = 200;
    var layerHeight = document.all[name].offsetHeight;
    if ( ( currentX + layerWidth ) > docWidth ) {
      x = ( currentX - layerWidth );
    }
    else {
      x = currentX;
    }
    if ( ( currentY + layerHeight ) >= docHeight ) {
       y = ( currentY - layerHeight - 20 );
    }
    else {
      y = currentY + 20;
    }
    document.all[name].style.left = parseInt ( x );
    document.all[name].style.top = parseInt ( y );
    document.all[name].style.visibility = "visible";
  }
}
function hide ( name ) {
  if (NS4) {
    document.layers[name].visibility = "hide";
  } else {
    document.all[name].style.visibility = "hidden";
  }
}

currentX = currentY = 0;

function grabEl(e) {
  if ( NS4 ) {
    currentX = e.pageX;
    currentY = e.pageY;
  } else {
    currentX = event.x + document.body.scrollLeft;
    currentY = event.y + document.body.scrollTop  
    //currentX = event.x;
    //currentY = event.y;
  }
}

if ( NS4 ) {
  document.captureEvents(Event.MOUSEDOWN | Event.MOUSEMOVE);
}
document.onmousemove = grabEl;

//-->

</SCRIPT>

