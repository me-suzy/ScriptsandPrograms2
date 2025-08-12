/*
+--------------------------------------------------------------------------
|   Alex JS Engine
|   ========================================
|   by Alex Höntschel
|   (c) 2002 AlexScriptEngine
|   http://www.alexscriptengine.de
|   ========================================
|   Web: http://www.alexscriptengine.de
|   Email: info@alexscriptengine.de
+---------------------------------------------------------------------------
|
|   > Beschreibung
|   > Javascript Funktionen
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: menu.js 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

function Frame() {
    if (FrameStat == "Show") {
        FrameSize = "00,*";
        FrameStat = "Hide";
    }
    else {
        FrameSize = "200,*";
        FrameStat = "Show";
    }
    parent.document.all("frameset1").cols = FrameSize;
}

function blocking(nr, vis_state) {
	if (document.layers)
	{
		current = (document.layers[nr].display == 'none') ? vis_state : 'none';
		document.layers[nr].display = current;
	}
	else if (document.all)
	{
		current = (document.all[nr].style.display == 'none') ? vis_state : 'none';
		document.all[nr].style.display = current;
	}
	else if (document.getElementById)
	{
		display = (document.getElementById(nr).style.display == 'none') ? vis_state : 'none';
		document.getElementById(nr).style.display = display;
	}
}