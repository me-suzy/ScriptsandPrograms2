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
|   > Javascript Hilffunktionen, Urls, etc.
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: utilities.js 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

var neu = null;
function displayHTML() {
    var inf = document.tpl.temp_content.value;
    neu = window.open('', 'POPup', 'toolbar = no, status = no, scrollbars=yes');
    if (neu != null) {
    if (neu.opener == null) {
        neu.opener = self;
        }
    neu.document.write("" + inf + "");
    }
}

function changecolor(theelement,color) {
	theelement.style.backgroundColor = color;
}

function imgdata(data) {
	opener.document.alp.thumb.value += data+" ";
}

function previewImage( list, image, base_path ) {
	form = document.alp;
	srcList = eval( "form." + list );
	srcImage = eval( "document." + image );
	var fileName = srcList.options[srcList.selectedIndex].text;
	var fileName2 = srcList.options[srcList.selectedIndex].value;
	if (fileName.length == 0 || fileName2.length == 0) {
		srcImage.src = 'images/gif.gif';
	} else {
		srcImage.src = base_path + fileName2 + ".gif";
	}
}