var offsetX = 0;
var offsetY = 0;
var selectedObj;

function jfactor(val)
{
	if(val != false) new_factor = val;
	else return new_factor;
}

function min_x(w)
{
	var x	 = getObjectLeft("img_crop")-jfactor(false);
	var y	 = getObjectTop("img_crop");
	if(x > 0) {
		shiftTo("img_crop",x,y);
		document.holder.crop_x.value = getObjectLeft("img_crop");
	}
}

function plus_x(w)
{
	var x	 = getObjectLeft("img_crop")+jfactor(false);
	var y	 = getObjectTop("img_crop");
	if(x > 0 && (x + getObjectWidth("img_crop")) < w) {
		shiftTo("img_crop",x,y);
		document.holder.crop_x.value = getObjectLeft("img_crop");
	}
}

function min_y(h)
{
	var x	 = getObjectLeft("img_crop");
	var y	 = getObjectTop("img_crop")-jfactor(false);
	if(y > 0) {
		shiftTo("img_crop",x,y);
		document.holder.crop_y.value = getObjectTop("img_crop");
	}
}

function plus_y(h)
{
	var x	 = getObjectLeft("img_crop");
	var y	 = getObjectTop("img_crop")+jfactor(false);
	if(y > 0 && (y + getObjectHeight("img_crop")) < h) {
		shiftTo("img_crop",x,y);
		document.holder.crop_y.value = getObjectTop("img_crop");
	}
}

function min_w(w)
{
	var x	 = getObjectWidth("img_crop")-jfactor(false);
	var y	 = getObjectHeight("img_crop");
	if(x > 0) {
		resizeTo("img_crop",x,y);
		document.holder.crop_w.value = getObjectWidth("img_crop");
	}
}

function plus_w(w)
{
	var x	 = getObjectWidth("img_crop")+jfactor(false);
	var y	 = getObjectHeight("img_crop");
	if(x > 0 && (x + getObjectLeft("img_crop")) < w) {
		resizeTo("img_crop",x,y);
		document.holder.crop_w.value = getObjectWidth("img_crop");
	}
}

function min_h(h)
{
	var x	 = getObjectWidth("img_crop");
	var y	 = getObjectHeight("img_crop")-jfactor(false);
	if(y > 0) {
		resizeTo("img_crop",x,y);
		document.holder.crop_h.value = getObjectHeight("img_crop");
	}
}

function plus_h(h)
{
	var x	 = getObjectWidth("img_crop");
	var y	 = getObjectHeight("img_crop")+jfactor(false);
	if(y > 0 && (y + getObjectTop("img_crop")) < h) {
		resizeTo("img_crop",x,y);
		document.holder.crop_h.value = getObjectHeight("img_crop");
	}
}

function validate(n)
{
	document.holder.crop_x.value = Math.round(document.holder.crop_x.value * n);
	document.holder.crop_y.value = Math.round(document.holder.crop_y.value * n);
	document.holder.crop_w.value = Math.round(document.holder.crop_w.value * n);
	document.holder.crop_h.value = Math.round(document.holder.crop_h.value * n);

	return true;
}

function update()
{
	document.holder.crop_x.value = getObjectLeft("img_crop");
	document.holder.crop_y.value = getObjectTop("img_crop");
	document.holder.crop_w.value = getObjectWidth("img_crop");
	document.holder.crop_h.value = getObjectHeight("img_crop");

	target();
}

function target() {

	targetWidth = document.holder.crop_w.value * factor;
	targetHeight = document.holder.crop_h.value * factor;

	scaleWidth = targetWidth / maxWidth;
	scaleHeight = targetHeight / maxHeight;

	newfactor = Math.max(scaleWidth,scaleHeight);

	destWidth = targetWidth / newfactor;
	destHeight = targetHeight / newfactor;

	//resizeTo("target",destWidth,destHeight);
	
	document.getElementById("target").innerHTML = "&rarr;  Actual target size: <strong>" + Math.round(destWidth) + "</strong> x <strong>" + Math.round(destHeight) + "</strong> pixels";
}


function draglimit() 
{
	if(getObjectTop("img_crop") <= getObjectTop("img_holder")) { 
		shiftTo("img_crop",getObjectLeft("img_crop"),1);
	}

	if(getObjectLeft("img_crop") <= getObjectLeft("img_holder")) { 
		shiftTo("img_crop",1,getObjectTop("img_crop"));
	}

	if((getObjectTop("img_crop") + getObjectHeight("img_crop")) > getObjectHeight("img_holder")) {
		shiftTo("img_crop",getObjectLeft("img_crop"),(getObjectHeight("img_holder") - getObjectHeight("img_crop"))-5);
	}

	if((getObjectLeft("img_crop") + getObjectWidth("img_crop")) > getObjectWidth("img_holder")) {
		shiftTo("img_crop",(getObjectWidth("img_holder") - getObjectWidth("img_crop"))-5,getObjectTop("img_crop"));
	}

	if(getObjectHeight("img_crop") > getObjectHeight("img_holder")) {
		resizeTo("img_crop",getObjectWidth("img_crop"),getObjectHeight("img_holder")-5);
		draglimit();
	}

	if(getObjectWidth("img_crop") > getObjectWidth("img_holder")) {
		resizeTo("img_crop",getObjectWidth("img_holder")-5,getObjectHeight("img_crop"));
		draglimit();
	}

	if(getObjectWidth("img_crop") < 30) {
		resizeTo("img_crop",30,getObjectHeight("img_crop"));
		draglimit();
	}

	if(getObjectHeight("img_crop") < 30) {
		resizeTo("img_crop",getObjectWidth("img_crop"),30);
		draglimit();
	}
}


function setSelected(evt) 
{
	var target = (evt.target) ? evt.target : evt.srcElement;
	var ab = (target.name && target.src) ? target.name.toLowerCase() : "";
	if(ab) { 
		if (document.layers) {
			selectedObj = document.layers[ab + "_crop"];
		} else {
			selectedObj = document.getElementById(ab + "_crop");
		}
		return;
	}
	selectedObj = null;
	return;
}


function engage(evt) 
{
	evt = (evt) ? evt : event;
	setSelected(evt);
	if (selectedObj) {
		if (evt.pageX) {
			offsetX = evt.pageX - ((selectedObj.offsetLeft) ? selectedObj.offsetLeft : selectedObj.left);
			offsetY = evt.pageY - ((selectedObj.offsetTop) ? selectedObj.offsetTop : selectedObj.top);
		} else if (evt.offsetX || evt.offsetY) {
			offsetX = evt.offsetX - ((evt.offsetX < -2) ? 0 : document.body.scrollLeft);
			offsetY = evt.offsetY - ((evt.offsetY < -2) ? 0 : document.body.scrollTop);
		}
		evt.cancelBubble = true;
		return false;
	}
}


function drag(evt) 
{
	evt = (evt) ? evt : event;
	if (selectedObj) {
		if (evt.pageX) {
			if(selectedObj.id == "slider_crop") resizeTo(selectedObj.parentNode,(evt.pageX - offsetX) + 8,(evt.pageY - offsetY) + 8);
			else shiftTo(selectedObj,(evt.pageX - offsetX),(evt.pageY - offsetY));
		} else if (evt.clientX || evt.clientY) {
			if(selectedObj.id == "slider_crop") resizeTo(selectedObj.parentNode,(evt.clientX - getObjectLeft(selectedObj.parentNode)) - 9,(evt.clientY - getObjectTop(selectedObj.parentNode)) - 87);
			else shiftTo(selectedObj,(evt.clientX - offsetX) - 15,(evt.clientY - offsetY) - 93);
		}
		return false;
	}
}


function release(evt) 
{
	evt = (evt) ? evt : event;
	selectedObj = null;
	// then...
	draglimit();
	update();
}


function init() 
{
	jfactor(50);
	if (document.layers) {
		document.captureEvents(Event.MOUSEDOWN | Event.MOUSEMOVE | Event.MOUSEUP);
	}
	document.onmousedown = engage;
	document.onmousemove = drag;
	document.onmouseup	 = release;
}