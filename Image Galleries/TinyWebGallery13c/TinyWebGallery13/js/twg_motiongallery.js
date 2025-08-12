/***********************************************
* CMotion Image Gallery- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Visit http://www.dynamicDrive.com for source code
* Last updated Mar 15th, 04'. Added "End of Gallery" message.
* This copyright notice must stay intact for legal use
***********************************************/

var restarea=30 
var maxspeed=6

var iedom=document.all||document.getElementById
var scrollspeed=0
var movestate=""

if (iedom)
document.write('<span id="temp" style="visibility:hidden;position:absolute;top:-100;left:-10000;"></span>')

var actualwidth=''
var cross_scroll, ns_scroll
var loadedyes=0

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function positiondiv(){
menuheight=parseInt(crossmain.offsetHeight)
mainobjoffsetH=getposOffset(crossmain, "top")
statusdiv.style.left=mainobjoffset+(menuwidth/2)-(statusdiv.offsetWidth/2)+"px"
statusdiv.style.top=menuheight+mainobjoffsetH+"px"
}

function getposOffset(what, offsettype){
var totaloffset=(offsettype=="left")? what.offsetLeft: what.offsetTop;
var parentEl=what.offsetParent;
while (parentEl!=null){
totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
parentEl=parentEl.offsetParent;
}
return totaloffset;
}

function setPos(position) {
if (position > 0) {
  position = -position;
}
if (loadedyes){
  cross_scroll.style.left= position + "px";
} else {
  setTimeout("setPos(" + position + ")",50) 
}
}


function moveleft(){
if (loadedyes){
movestate="left"
if (iedom&&parseInt(cross_scroll.style.left)>(menuwidth-actualwidth)){
cross_scroll.style.left=parseInt(cross_scroll.style.left)-scrollspeed+"px"
}}
lefttime=setTimeout("moveleft()",10)
}

function moveright(){
if (loadedyes){
movestate="right"
if (iedom&&parseInt(cross_scroll.style.left)<0){
cross_scroll.style.left=parseInt(cross_scroll.style.left)+scrollspeed+"px"
}}
righttime=setTimeout("moveright()",10)
}

function motionengine(e){
var dsocx=(window.pageXOffset)? pageXOffset: ietruebody().scrollLeft;
var dsocy=(window.pageYOffset)? pageYOffset : ietruebody().scrollTop;
var curposy=window.event? event.clientX : e.clientX? e.clientX: ""
curposy-=mainobjoffset-dsocx
var leftbound=(menuwidth-restarea)/2
var rightbound=(menuwidth+restarea)/2
if (curposy>rightbound){
scrollspeed=(curposy-rightbound)/((menuwidth-restarea)/2) * maxspeed
if (window.righttime) clearTimeout(righttime)
if (movestate!="left") moveleft()
}
else if (curposy<leftbound){
scrollspeed=(leftbound-curposy)/((menuwidth-restarea)/2) * maxspeed
if (window.lefttime) clearTimeout(lefttime)
if (movestate!="right") moveright()
}
else
scrollspeed=0
}

function contains_ns6(a, b) {
if (!b) return false;
while (b.parentNode)
if ((b = b.parentNode) == a)
  return true;
return false;
}

function stopmotion(e){
if ((window.event&&!crossmain.contains(event.toElement)) || (e && e.currentTarget && e.currentTarget!= e.relatedTarget && !contains_ns6(e.currentTarget, e.relatedTarget))){
if (window.lefttime) clearTimeout(lefttime)
if (window.righttime) clearTimeout(righttime)
movestate=""
}
}

function fillup(){
if (iedom){
crossmain=document.getElementById? document.getElementById("motioncontainer") : document.all.motioncontainer
if (!crossmain) return false;
menuwidth=parseInt(crossmain.style.width)
mainobjoffset=getposOffset(crossmain, "left")
cross_scroll=document.getElementById? document.getElementById("motiongallery") : document.all.motiongallery
  document.getElementById("temp").innerHTML=cross_scroll.innerHTML //NEW stuff
  actualwidth=document.all? cross_scroll.offsetWidth : document.getElementById("temp").offsetWidth
  if (!window.opera) document.getElementById("temp").style.display="none"

crossmain.onmousemove=function(e){
  motionengine(e)
}

crossmain.onmouseout=function(e){
  stopmotion(e)
}}
loadedyes=1
}
window.onload=fillup