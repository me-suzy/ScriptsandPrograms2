<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script language="JavaScript" type="text/javascript">
<!--
/**********************************************************************************
PopupDescriptions
*   Copyright (C) 2001 <a href="/dhtmlcentral/thomas_brattli.asp">Thomas Brattli</a>
*   This script was released at DHTMLCentral.com
*   Visit for more great scripts!
*   This may be used and changed freely as long as this msg is intact!
*   We will also appreciate any links you could give us.
*
*   Made by <a href="/dhtmlcentral/thomas_brattli.asp">Thomas Brattli</a>
*********************************************************************************/

function lib_bwcheck(){ //Browsercheck (needed)
	this.ver=navigator.appVersion
	this.agent=navigator.userAgent
	this.dom=document.getElementById?1:0
	this.opera5=this.agent.indexOf("Opera 5")>-1
	this.ie5=(this.ver.indexOf("MSIE 5")>-1 && this.dom && !this.opera5)?1:0;
	this.ie6=(this.ver.indexOf("MSIE 6")>-1 && this.dom && !this.opera5)?1:0;
	this.ie4=(document.all && !this.dom && !this.opera5)?1:0;
	this.ie=this.ie4||this.ie5||this.ie6
	this.mac=this.agent.indexOf("Mac")>-1
	this.ns6=(this.dom && parseInt(this.ver) >= 5) ?1:0;
	this.ns4=(document.layers && !this.dom)?1:0;
	this.bw=(this.ie6 || this.ie5 || this.ie4 || this.ns4 || this.ns6 || this.opera5)
	return this
}
var popup_bw=new lib_bwcheck();

var popup_messages=new Array();
popup_messages[0] = '<?php echo str_replace("\"","\\\"",$l_incfile_remark)?>';

popup_fromX = 10 //How much from the actual mouse X should the description box appear?
popup_fromY = -20 //How much from the actual mouse Y should the description box appear?

function makeObj(obj){
   	this.evnt=popup_bw.dom? document.getElementById(obj):popop_bw.ie4?document.all[obj]:popup_bw.ns4?document.layers[obj]:0;
	if(!this.evnt) return false
	this.css=popup_bw.dom||popup_bw.ie4?this.evnt.style:popup_bw.ns4?this.evnt:0;
   	this.wref=popup_bw.dom||popup_bw.ie4?this.evnt:popup_bw.ns4?this.css.document:0;
	this.writeIt=b_writeIt;
	return this
}

// A unit of measure that will be added when setting the position of a layer.
var popup_px = popup_bw.ns4||window.opera?"":"px";

function b_writeIt(text){
	if (popup_bw.ns4){this.wref.write(text);this.wref.close()}
	else this.wref.innerHTML = text
}

//Capturing mousemove
var popup_descx = 0
var popup_descy = 0
function popmousemove(e){popup_descx=popup_bw.ns4||popup_bw.ns6?e.pageX:event.x; popup_descy=popup_bw.ns4||popup_bw.ns6?e.pageY:event.y}

var oDesc;
//Shows the messages
function popup(num){
    if(oDesc){
		oDesc.writeIt('<div class="clDescription">'+popup_messages[num]+'</div>')
		if(popup_bw.ie5||popup_bw.ie6) popup_descy = popup_descy+document.body.scrollTop
		oDesc.css.left = (popup_descx+popup_fromX)+popup_px
		oDesc.css.top = (popup_descy+popup_fromY)+popup_px
		oDesc.css.visibility = "visible"
    }
}
//Hides it
function popout(){
	if(oDesc) oDesc.css.visibility = "hidden"
}
function setPopup(){
	if(popup_bw.ns4)
		document.captureEvents(Event.MOUSEMOVE);
	document.onmousemove = popmousemove;
	oDesc = new makeObj('divDescription')
}
// -->
</script>
<script type="text/javascript" language="javascript">
<!--
function cfgratingspublic(){
	if(document.myform.ratingspub.checked == true)
		document.myform.pubratecom.disabled=false;
	else
	{
		document.myform.pubratecom.checked=false;
		document.myform.pubratecom.disabled=true;
	}
	return;
}
function srchdispprog()
{
	if(document.myform.searchdisplayprog.checked==true)
	{
		document.myform.searchdisplaycat.disabled=false;
	}
	else
	{
		document.myform.searchdisplaycat.checked=false;
		document.myform.searchdisplaycat.disabled=true;
		document.myform.searchdisplaysubcat.checked=false;
		document.myform.searchdisplaysubcat.disabled=true;
	}
}
function srchdispcat()
{
	if(document.myform.searchdisplaycat.checked==true)
	{
		document.myform.searchdisplaysubcat.disabled=false;
	}
	else
	{
		document.myform.searchdisplaysubcat.checked=false;
		document.myform.searchdisplaysubcat.disabled=true;
	}
}
// -->
</script>
<script type="text/javascript" language="javascript">
<!--
function color_picker(fieldname)
{
<?php
	$targeturl=do_url_session("colorwheel.php?$langvar=$act_lang");
?>
	document.myform.colorfield.value=fieldname;
	openWindow2('<?php echo $targeturl?>',20,20,440,400);
}
function color_chooser(fieldname)
{
<?php
	$targeturl=do_url_session("colorchooser.php?$langvar=$act_lang");
?>
	document.myform.colorfield.value=fieldname;
	openWindow2('<?php echo $targeturl?>',20,20,450,400);
}
function color_prev(fieldname)
{
	eval('colorcode=document.layoutform.'+fieldname+'.value');
<?php
	if(is_ns6())
	{
		echo "prevfield=document.getElementById(fieldname+'prev');\n";
		echo "prevfield.bgColor=colorcode;\n";
	}
	else
	{
		echo "eval(fieldname + 'prev.bgColor=' + '\''+colorcode+'\'');\n";

	}
?>
	return;
}
function setcolor(colorcode)
{
	eval('document.myform.' + document.myform.colorfield.value + '.value=\'' + colorcode + '\'');
	eval(document.myform.colorfield.value + 'prev.bgColor=\'' + colorcode + '\'');
}
// -->
</script>
