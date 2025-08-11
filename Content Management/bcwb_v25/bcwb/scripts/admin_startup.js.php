<?PHP
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

$add_js = <<<EOD

function menu(obj, el) {
	if(	obj.style.display == "none" )	{ 
		obj.style.display = "block"; 
		el.value = " - ";
	}	else	{
		obj.style.display = "none"; 
		el.value = " + ";
	}
	return false;
}


function cleanHTML(obj) {

	if(obj.selection.createRange().text=="") { 
	var arr = new Array();
	arr = obj.body.innerText.split(unescape("%0A"));
	obj.body.innerHTML = arr.join(unescape("%3C")+"p"+unescape("%3E")+unescape("%0A"));
	} else 	{
	obj.selection.createRange().execCommand("RemoveFormat");
	obj.focus();
	}
}


function inserthtml(obj, sHTML) { 
	if(obj.selection.type == "Control")
		obj.selection.clear();
	obj.selection.createRange().pasteHTML(sHTML);
	obj.focus();
}

function ecommand(obj, command) {
	obj.selection.createRange().execCommand(command);
	obj.focus();
 	return false;
}



function insimage(obj, tag) {

  var args=new Array();
  var sHTML;

  if(obj.selection.type == "Control") 	{
	    var oImg = obj.selection.createRange().item(0);
	    bcwb_form.arg_ImgUrl.value = oImg.src;
	    bcwb_form.arg_AltText.value = oImg.alt;
	    bcwb_form.arg_ImgBorder.value = oImg.border;
	    bcwb_form.arg_HorSpace.value = oImg.hspace;
	    bcwb_form.arg_VerSpace.value = oImg.vspace;
	    bcwb_form.arg_ImgAlign.value = oImg.align;
	    bcwb_form.arg_ImgHeight.value = oImg.height;
	    bcwb_form.arg_ImgWidth.value = oImg.width;
	}	    
EOD;
$add_js .= '			window.open(\''.$GLOBALS["http_path"].'scripts/insert_image.php?tag=\'+tag, \'displayWindow\',\'width=500,height=530,status=yes,toolbar=no,menubar=no, scrollbars=auto, resizable=yes\'); } ';

$add_js .=' function insfile(obj, tag) {
	window.open(\''.$GLOBALS["http_path"].'scripts/insert_file.php?tag=\'+tag, \'displayWindow\',\'width=350,height=200,status=yes,toolbar=no,menubar=no, scrollbars=auto, resizable=yes\'); 
}

function inslink(obj, tag) {
	window.open(\''.$GLOBALS["http_path"].'scripts/insert_link.php?tag=\'+tag, \'displayWindow\',\'width=350,height=230,status=yes,toolbar=no,menubar=no, scrollbars=auto, resizable=yes\'); 
}
function inspage(obj, tag) {
	window.open(\''.$GLOBALS["http_path"].'scripts/insert_page.php?tag=\'+tag, \'displayWindow\',\'width=350,height=230,status=yes,toolbar=no,menubar=no, scrollbars=auto, resizable=yes\'); 
}
';

?>