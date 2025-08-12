
//Das Objekt, das gerade bewegt wird.
var dragobjekt = null;

// Position, an der das Objekt angeklickt wurde.
var dragx = 0;
var dragy = 0;

// Mausposition
var posx = 0;
var posy = 0;


function draginit() {
 // Initialisierung der Überwachung der Events

  document.onmousemove = drag;
  document.onmouseup = dragstop;
}


function dragstart(element) {
   //Wird aufgerufen, wenn ein Objekt bewegt werden soll.
  dragobjekt = element;
  dragx = posx - dragobjekt.offsetLeft;
  dragy = posy - dragobjekt.offsetTop;
}


function dragstop() {
  //Wird aufgerufen, wenn ein Objekt nicht mehr bewegt werden soll.
  dragobjekt=null;
}


function drag(ereignis) {
  //Wird aufgerufen, wenn die Maus bewegt wird und bewegt bei Bedarf das Objekt.

  posx = document.all ? window.event.clientX : ereignis.pageX;
  posy = document.all ? window.event.clientY : ereignis.pageY;
  if(dragobjekt != null) {
    dragobjekt.style.left = (posx - dragx) + "px";
    dragobjekt.style.top = (posy - dragy) + "px";
  }
}
