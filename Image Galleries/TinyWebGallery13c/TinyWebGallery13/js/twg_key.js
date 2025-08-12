
var Netscape = new Boolean();
if(navigator.appName == "Netscape")  Netscape = true;

function TasteGedrueckt(Ereignis)
{
 if(Netscape) {
		 if (Ereignis.which == 37) {
				key_back();
		 } else if (Ereignis.which == 39)  {
				key_foreward();
		 } else if (Ereignis.which == 38)  {
				key_up();
		 }
  }
}
document.onkeydown = TasteGedrueckt;

