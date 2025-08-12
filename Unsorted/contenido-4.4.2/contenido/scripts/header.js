/*****************************************
*
* $Id: header.js,v 1.1 2003/09/26 15:08:31 jan Exp $
*
* File      :   $RCSfile: header.js,v $
* Project   :
* Descr     :
*
* Author    :   $Author: jan $
* Modified  :   $Date: 2003/09/26 15:08:31 $
*
* © four for business AG, www.4fb.de
******************************************/

function Highlight(sObject, sTextObj)
{
    this.object = document.getElementById(sObject);
    this.tObject = document.getElementById(sTextObj);
}

Highlight.prototype.getPos = function(oElement)
{
    this.x = 0;
    this.y = 0;

    var el = oElement;

    while (el.tagName != "BODY")
    {
        this.x += el.offsetLeft;
        this.y += el.offsetTop;
        el = el.offsetParent;
    }
}

Highlight.prototype.moveTo = function(x, y)
{
    this.object.style.left = x + "px";
    this.object.style.top  = y + "px";
    this.object.style.visibility = "visible";
}

Highlight.prototype.on = function(obj)
{
    this.getPos(obj);
    xpos = this.x - 32;
    this.moveTo(xpos, this.y);
    this.showText(obj.innerHTML);
}

Highlight.prototype.showText = function(sText)
{
    this.tObject.innerHTML = sText;
}

var active_main;
var active_sub;
var active_link;
var active_sub_link;

function show(id, link) {

    h.on(link.offsetParent);

    if (active_main) {
        hide(active_main);
    }

    if (active_link) {
        active_link.className = "main";
    }

    document.getElementById(id).style.visibility = "visible";
    active_main = id;
}

function hide(id) {
    document.getElementById(id).style.visibility = "hidden";
    active_main = 0;
}

function imgOn(id, link) {

    if (active_sub) {
        imgOff(active_sub);
        active_sub_link.className = 'sub';
    }

    link.className = 'sub_on';
    active_sub_link = link;
    document.getElementById(id).src = "images/arrow.gif";
    active_sub = id;

}

function imgOff(id) {
    document.getElementById(id).src = "images/spacer.gif";
}

