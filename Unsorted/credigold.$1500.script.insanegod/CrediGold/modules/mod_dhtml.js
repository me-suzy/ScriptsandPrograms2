/****************************************************************************   

DHTML library from DHTMLCentral.com

*   Copyright (C)2001 Thomas Brattli 2001

*   This script was released at DHTMLCentral.com

*   Visit for more great scripts!

*   This may be used and changed freely as long as this msg is intact!

*   We will also appreciate any links you could give us.

*

*   Made by Thomas Brattli 2001

*

*   Modified by Svetlin Staev (06/10/2001)

*   Added getStatus(), img(), centerLayer(), checkTargets(), setTarget(), animate(), nf(), er()

*   Modified ch(), obj(), dd_up()

*   Throughly modified by Sergi Meseguer from http://www.meddle.f2s.com

*   Added slideBy(), swap()

***************************************************************************/



/* bw.dom | bw.op5 | bw.ie4 | bw.ie5 | bw.ie6 | bw.ns4 | bw.moz |bw.ns6 | bw.mac */

function ch(){this.ver=navigator.appVersion;this.agent=navigator.userAgent;this.plat=navigator.platform;this.dom=document.getElementById?1:0;this.op5=this.agent.indexOf("Opera 5")>-1?1:0;this.ie5=this.ver.indexOf("MSIE 5")>-1&&this.dom&&!this.op5?1:0;this.ie6=this.ver.indexOf("MSIE 6")>-1&&this.dom&&!this.op5?1:0;this.ie4=document.all&&!this.dom&&!this.op5?1:0;this.ie=this.ie4||this.ie5||this.ie6;this.mac=this.agent.indexOf("Mac")>-1;this.win=this.agent.indexOf("Win")>-1;this.win98=this.agent.indexOf("Win98")>-1||this.agent.indexOf("Windows 98")>-1;this.win95=this.agent.indexOf("Win95")>-1||this.agent.indexOf("Windows 95")>-1;this.winnt=this.agent.indexOf("WinNT")>-1||this.agent.indexOf("Windows NT")>-1;this.winme=this.agent.indexOf("9x 4.90")>-1;this.linux=this.plat=="Linux";gecko=this.agent.indexOf("Netscape6");this.moz=this.dom&&parseInt(this.ver)>=5&&(gecko==-1)?1:0;this.ns6=this.dom&&parseInt(this.ver)>=5&&!this.moz&&(gecko!==1)?1:0;this.ns4=document.layers&&!this.dom?1:0;this.min=this.ie6||this.ie5||this.ie4||this.ns4||this.ns6||this.op5||this.moz;return this}

bw=new ch();dd_is_active=0;dd_obj=0;dd_mobj=0;trig=0;



function msg(t){alert(t);return false}

function getStatus(w){window.status=w}

function obj(obj,nest){if(!bw.min)return msg('Old browser');nest=(!nest)?"":'document.'+nest+'.';this.evnt=bw.dom?document.getElementById(obj):bw.ie4?document.all[obj]:bw.ns4?eval(nest+"document.layers."+obj):0;if(!this.evnt)return msg('No layer - '+obj+'');this.css=bw.dom||bw.ie4?this.evnt.style:this.evnt;this.ref=bw.dom||bw.ie4?document:this.css.document;this.x=parseInt(this.css.left)||this.css.pixelLeft||this.evnt.offsetLeft||0;this.y=parseInt(this.css.top)||this.css.pixelTop||this.evnt.offsetTop||0;this.w=this.evnt.offsetWidth||this.css.clip.width||this.ref.width||this.css.pixelWidth||0;this.h=this.evnt.offsetHeight||this.css.clip.height||this.ref.height||this.css.pixelHeight||0;this.z=this.css.zIndex;this.visible=(this.css.visibility=="")?true:false;this.c=0;if((bw.dom||bw.ie4)&&this.css.clip){this.c=this.css.clip;this.c=this.c.slice(5,this.c.length-1);this.c=this.c.split(' ');for(var i=0;i<4;i++) this.c[i]=parseInt(this.c[i])}this.ct=this.css.clip.top||this.c[0]||0;this.cr=this.css.clip.right||this.c[1]||this.w||0;this.cb=this.css.clip.bottom||this.c[2]||this.h||0;this.cl=this.css.clip.left||this.c[3]||0;this.obj=obj+"Object";this.name=obj;eval(this.obj+"=this");return this}

function dd(){dd_is_active=1;if(bw.ns4) document.captureEvents(Event.MOUSEMOVE|Event.MOUSEDOWN|Event.MOUSEUP);document.onmousemove=dd_move;document.onmousedown=dd_down;document.onmouseup=dd_up}

function dd_over(obj){dd_mobj=obj}

function dd_up(e){dd_obj=0;}

function dd_down(e){if(dd_mobj){x=(bw.ns4||bw.ns6)?e.pageX:event.x||event.clientX;y=(bw.ns4||bw.ns6)?e.pageY:event.y||event.clientY;dd_obj=dd_mobj;dd_obj.clX=x-dd_obj.x;dd_obj.clY=y-dd_obj.y}}

function dd_move(e,y,rresize){x=(bw.ns4||bw.ns6)?e.pageX:event.x||event.clientX;y=(bw.ns4||bw.ns6)?e.pageY:event.y||event.clientY;if(dd_obj){nx=x-dd_obj.clX;ny=y-dd_obj.clY;if(dd_obj.ddobj)dd_obj.ddobj.moveTo(nx,ny);else dd_obj.moveTo(nx,ny)}if(!bw.ns4)return false}



obj.prototype.moveTo=function(x,y){this.x=x;this.y=y;this.css.left=x;this.css.top=y}

obj.prototype.moveBy=function(x,y){this.css.left=this.x+=x;this.css.top=this.y+=y}

obj.prototype.show=function(){this.css.visibility="visible"}

obj.prototype.hide=function(){this.css.visibility="hidden"}

obj.prototype.bg=function(c){if(bw.op5)this.css.background=c;else if(bw.dom||bw.ie4)this.css.backgroundColor=c;else if(bw.ns4)this.css.bgColor=c;}

obj.prototype.fColor=function(c){if(bw.dom||bw.ie4)this.css.color=c}

obj.prototype.swap=function(img,newsrc){(this.ref)?this.ref.images[img].src=newsrc:document.images[img].src=newsrc}

obj.prototype.write=function(text,sM,eJ){if(bw.ns4){if(!sM){sM="";eJ=""}this.ref.open("text/html");this.ref.write(sM+text+eJ);this.ref.close()}else this.evnt.innerHTML=text;}

obj.prototype.clipTo=function(t,r,b,l,zW){this.ct=t;this.cr=r;this.cb=b;this.cl=l;if(bw.ns4){this.css.clip.top=t;this.css.clip.right=r;this.css.clip.bottom=b;this.css.clip.left=l}else{if(t<0)t=0;if(r<0)r=0;if(b<0)b=0;if(b<0)b=0;this.css.clip="rect("+t+","+r+","+b+","+l+")";if(zW){this.css.pixelWidth=this.css.width=r;this.css.pixelHeight=this.css.height=b}}}

obj.prototype.clipBy=function(t,r,b,l,zW){this.clipTo(this.ct+t,this.cr+r,this.cb+b,this.cl+l,zW)}

obj.prototype.clipIt=function(t,r,b,l,step,fn,wh){tstep=Math.max(Math.max(Math.abs((t-this.ct)/step),Math.abs((r-this.cr)/step)),Math.max(Math.abs((b-this.cb)/step),Math.abs((l-this.cl)/step)));if(!this.clipactive){this.clipactive=true;if(!wh)wh=0;if(!fn)fn=0;this.clip(t,r,b,l,(t-this.ct)/tstep,(r-this.cr)/tstep,(b-this.cb)/tstep,(l-this.cl)/tstep,tstep,0,fn,wh)}}

obj.prototype.setZ = function(m){if (bw.ns4) this.evnt.zIndex=m;else this.css.zIndex=m;this.Z=m;}

obj.prototype.getZ = function(){if(!this.Z){if(bw.ns4) this.Z=this.evnt.zIndex;else this.Z=this.css.zIndex;}}



obj.prototype.wipe=function(step,fn,wh){this.clipIt(0,0,0,0,step,fn,wh)}

obj.prototype.wipeUp=function(step,fn,wh){this.clipIt(0,this.w,0,0,step,fn,wh)}

obj.prototype.wipeLeft=function(step,fn,wh){this.clipIt(0,0,this.h,0,step,fn,wh)}

obj.prototype.wipeDown=function(step,fn,wh){this.clipIt(this.h,this.w,this.h,0,step,fn,wh)}

obj.prototype.wipeRight=function(step,fn,wh){this.clipIt(0,this.w,this.h,this.w,step,fn,wh)}

obj.prototype.boxIn=function(step,fn,wh){this.clipIt(this.w/2,0,0,this.h/2,step,fn,wh)}

obj.prototype.boxOut=function(step,fn,wh){this.clipIt(0,this.w,this.h,0,step,fn,wh)}

obj.prototype.horizon=function(step,fn,wh){this.clipIt(this.h/2,this.w,this.h/2,0,step,fn,wh)}

obj.prototype.vertical=function(step,fn,wh){this.clipIt(0,this.w/2,this.h,this.w/2,step,fn,wh)}



obj.prototype.clip=function(t,r,b,l,ts,rs,bs,ls,tstep,astep,fn,wh){if(astep<tstep){if(wh)eval(wh);astep++;this.clipBy(ts,rs,bs,ls,1);setTimeout(this.obj+".clip("+t+","+r+","+b+","+l+","+ts+","+rs+","+bs+","+ls+","+tstep+","+astep+",'"+fn+"','"+wh+"')",50)}else{this.clipactive=false;this.clipTo(t,r,b,l,1);if(fn)eval(fn)}}

obj.prototype.circleTo=function(rad,ainc,a,enda,xc,yc,speed,fn){if((Math.abs(ainc)<Math.abs(enda-a))){a += ainc;var x=xc+rad*Math.cos(a*Math.PI/180);var y=yc - rad*Math.sin(a*Math.PI/180);this.moveTo(x,y);setTimeout(this.obj+".circleIt("+rad+","+ainc+","+a+","+enda+","+xc+","+yc+","+speed+",'"+fn+"')",speed)}else if(fn&&fn!="undefined")eval(fn)}

obj.prototype.slideTo=function(endx,endy,inc,speed,fn,wh){if(!this.slideactive){var distx=endx-this.x;var disty=endy-this.y;var num=Math.sqrt(Math.pow(distx,2)+Math.pow(disty,2))/inc;var dx=distx/num;var dy=disty/num;this.slideactive=1;if(!wh)wh=0;if(!fn)fn=0;this.slide(dx,dy,endx,endy,speed,fn,wh)}}

obj.prototype.slideBy=function(endx,endy,inc,speed,fn,wh){relx=this.x+endx;rely=this.y+endy;this.slideTo(relx,rely,inc,speed,fn,wh)}

obj.prototype.slide=function(dx,dy,endx,endy,speed,fn,wh){if(this.slideactive&&(Math.floor(Math.abs(dx))<Math.floor(Math.abs(endx-this.x))||Math.floor(Math.abs(dy))<Math.floor(Math.abs(endy-this.y)))){this.moveBy(dx,dy);if(wh)eval(wh);setTimeout(this.obj+".slide("+dx+","+dy+","+endx+","+endy+","+speed+",'"+fn+"','"+wh+"')",speed)}else{this.slideactive=0;this.moveTo(endx,endy);if(fn)eval(fn)}}

obj.prototype.animate=function(path,spd,u){if(trig==0){this.moveTo(path[0],path[1]);trig=2;!spd?10:spd;setTimeout(this.obj+".animate('"+path+"',"+(!spd?"10":spd)+",'"+(!u?false:u)+"')",spd)}else if(trig<path.length){path=path.split(",");this.moveTo(path[trig],path[++trig]);trig++;!spd?10:spd;if(trig==path.length)if (u!=="false")eval(u);setTimeout(this.obj+".animate('"+path+"',"+(!spd?"10":spd)+",'"+(!u?false:u)+"')",spd)}}

obj.prototype.dragdrop=function(obj){if(!dd_is_active)dd();this.evnt.onmouseover=new Function("dd_over("+this.obj+")");this.evnt.onmouseout=new Function("dd_mobj=0");if(obj)this.ddobj=obj}

obj.prototype.nodragdrop=function(){this.evnt.onmouseover="";this.evnt.onmouseout="";dd_obj=0;dd_mobj=0}



/* Dimentions.width | Dimentions.height | Dimentions.halfWidth | Dimentions.halfHeight | Dimentions.scrollTop | Dimentions.scrollLeft */

function Dimentions(){this.l=0;this.width=bw.ie&&document.body.offsetWidth-20||innerWidth||0;this.h=0;this.height=bw.ie&&document.body.offsetHeight-5||innerHeight||0;if(!this.width||!this.height)return msg('Document has no width or height');this.halfWidth=this.width/2;this.halfHeight=this.height/2;this.scrollTop=bw.ns4||bw.op5?window.pageYOffset:document.body.scrollTop;this.scrollLeft=bw.ns4||bw.op5?window.pageXOffset:document.body.scrollLeft;return this}



/* img.top | img.left | img.height | img.width */

function pic(id){r="document.";if(bw.ns4){this.top=eval(r+id+".x");this.left=eval(r+id+".y")}else{a=".offsetParent";t=eval((bw.ns6?r:"")+id+".offsetTop");p=eval((bw.ns6?r:"")+id+a);while (p!=null){t+=p.offsetTop;p=p.offsetParent}l=eval((bw.ns6?r:"")+id+".offsetLeft");p=eval((bw.ns6?r:"")+id+a);while (p!=null){l+=p.offsetLeft;p=p.offsetParent}this.top=t;this.left=l}this.height=eval((bw.ns4||bw.ns6?r:"")+id+".height");this.width=eval((bw.ns4||bw.ns6?r:"")+id+".width")}

function preload(img,src){if(document.images){eval(img+'=new Image()');eval(img+'.src = "'+src+'"')}}

function centerLayer(n){n.moveTo((page.width-n.w)/2,(page.height-n.h)/2)}

function nf(){setTimeout("document.location.reload()",50)}

function er(){return true}

window.onresize=nf;

window.onerror=er;