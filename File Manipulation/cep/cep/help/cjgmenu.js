/*------------------------------------------------------------------------------
CJG EXPLORER PRO v3.2 - WEB FILE MANAGEMENT - Copyright (C) 2003 CARLOS GUERLLOY
CJGSOFT Software
cjgexplorerpro@guerlloy.com
guerlloy@hotmail.com
carlos@weinstein.com.ar
Buenos Aires, Argentina
--------------------------------------------------------------------------------
This program is free software; you can  redistribute it and/or  modify it  under
the terms   of the   GNU General   Public License   as published   by the   Free
Software Foundation; either  version 2   of the  License, or  (at  your  option)
any  later version. This program  is  distributed in  the hope that  it  will be
useful,  but  WITHOUT  ANY  WARRANTY;  without  even  the   implied  warranty of
MERCHANTABILITY  or FITNESS  FOR A  PARTICULAR  PURPOSE.  See the  GNU   General
Public License for   more details. You  should have received  a copy of  the GNU
General Public License along  with this  program; if   not, write  to the   Free
Software  Foundation, Inc.,  59 Temple Place,  Suite 330, Boston,  MA 02111-1307
USA
------------------------------------------------------------------------------*/

var VLABEL=0;
var VHREF=1;
var VTARGET=2;
var VICON=3;
var VOPEN=4;
var VPARENT=5;
var VNAME=6;
var VPOS=7;
var VHEIGHT=8;
var VMENU=9;

var IROOT=0;
var IFOLDER=1;
var IPLUS=2;
var IMINUS=3;
var IJOIN=4;
var ILINE=5;
var IFILE=6;
var ICLOSE=0;
var IOPEN=1;
var IMIDDLE=0;
var IBOTTOM=1;
var ITOP=2;
var ITOPBOT=3;

var seqname=0;
var selback='';

function menuhtml(sm,id) { var i,s,o,starget,shref,sfolder,sfile,splus,sjoin,stomenu; 
if(arguments.length<1) sm="root[VMENU]";
if(arguments.length<2) id=0; seqname=id+1;
var m=eval(sm);
s="<TABLE class=tm id=m"+id+" border=0 cellspacing=0 cellpadding=0>\n";
if(showfoldericon) sfolder='<IMG align=absmiddle src="'+menuimg(IFOLDER,ICLOSE)+'" '+imgattrs+'>'; else sfolder='';
if(id==0 && showfoldericon && shownodelines && showroot) s+='<TR><TD><IMG src="'+menuimg(IROOT)+'" '+imgattrs+'>\n';
for(i=0;i<m.length;i++) { o=m[i]; 
o[VPOS]=IMIDDLE; if(i==m.length-1) o[VPOS]=IBOTTOM; 
if(id==0 && !showroot && shownodelines) { if(!i) o[VPOS]=ITOP; if(m.length==1) o[VPOS]=ITOPBOT; }
if(o[VTARGET]!='') starget=' target="'+o[VTARGET]+'"'; else starget='';
if(o[VHREF]!='') shref=' href="'+o[VHREF]+'"'; else shref=' href="#"';
if(shownodelines)  splus='<IMG src="'+menuimg(IPLUS,o[VPOS])+'" '+imgattrs+'>'; else splus='';
sjoin='<IMG src="'+menuimg(IJOIN,o[VPOS])+'" '+imgattrs+'>';
if(o[VMENU]!='') {
s+="<TR><TD><A onclick='menuanchor(this,\""+sm+"\","+i+");'>"+splus+"</A></TD><TD nowrap>"+sfolder+"<A id=t"+id+"_"+i+shref+starget+" onclick='menusel(this);menuopen(this,\""+sm+"\","+i+");'>"+o[VLABEL]+"</A></TD></TR>\n";
} else {
if(showfileicon) sfile='<IMG align=absmiddle src="'+menuimg(IFILE,o[VICON])+'" '+imgattrs+'>'; else sfile='';
s+="<TR><TD>"+sjoin+"</TD><TD nowrap>"+sfile+"<A id=t"+id+"_"+i+shref+starget+" onclick='menusel(this);'>"+o[VLABEL]+"</A></TD></TR>\n";  
} }
s+="</TABLE>\n"; return(s); }

function menuanchor(este,sm,opt) { var m=eval(sm); 
if(m[opt][VOPEN]) menuclose(este,sm,opt); else menuopen(este,sm,opt); }

function menuopen(este,sm,opt) { var m=eval(sm); var n;
if(m[opt][VOPEN]) return;
var t0=este.parentElement.parentElement.children.tags('TD')[0];
var t1=este.parentElement.parentElement.children.tags('TD')[1];
if(shownodelines) t0.children.tags('A')[0].children.tags('IMG')[0].src=menuimg(IMINUS,m[opt][VPOS]);
if(showfoldericon) t1.children.tags('IMG')[0].src=menuimg(IFOLDER,IOPEN);
n=seqname++; t1.innerHTML+=menuhtml(sm+'['+opt+'][VMENU]',n); 
m[opt][VOPEN]=1; m[opt][VNAME]="m"+n;
for(var i=0;i<m[opt][VMENU].length;i++) m[opt][VMENU][i][VPARENT]=m[opt];
if(shownodelines) menubacktd(m[opt],0);
m0.onpropertychange(); }

function menuclose(este,sm,opt) { var m=eval(sm);
var t0=este.parentElement.parentElement.children.tags('TD')[0];
var t1=este.parentElement.parentElement.children.tags('TD')[1];
t0.innerHTML=t0.innerHTML.match(/.*<\/A>/);
if(shownodelines) t0.children.tags('A')[0].children.tags('IMG')[0].src=menuimg(IPLUS,m[opt][VPOS]);
t1.innerHTML=t1.innerHTML.match(/.*<\/A>/);
if(showfoldericon) t1.children.tags('IMG')[0].src=menuimg(IFOLDER,ICLOSE);
if(shownodelines) menubacktd(m[opt],1); menuclosevar(m[opt]);
m0.onpropertychange(); }

function menuclosevar(o) { var i;
if(o[VMENU]!="") { o[VNAME]=""; o[VOPEN]=0; o[VHEIGHT]=0;
for(i=0;i<o[VMENU].length;i++) menuclosevar(o[VMENU][i]);  } }

function menuline(n,bottom) { var s,i;
s='<TABLE class=tm border=0 cellspacing=0 cellpadding=0>';
for(var i=0;i<n;i++) s+='<TR><TD><IMG src="'+menuimg(ILINE,bottom)+'" '+imgattrs+'></TD></TR>';
s+='</TABLE>'; return(s); }
	
function menubacktd(o,neg) { var t0,s,e1;
l=o[VMENU].length; 
if(neg) { l=-o[VHEIGHT]; o=o[VPARENT]; }
while(o!='') { o[VHEIGHT]+=l; 
e1=eval(o[VNAME]); 
t0=e1.parentElement.parentElement.children.tags('TD')[0];
s=t0.innerHTML.match(/<A.*<\/A>/); s+=menuline(o[VHEIGHT],o[VPOS]); t0.innerHTML=s; 
o=o[VPARENT]; } }

function menusel(t) { 
if(selback!='') { if(document.all[selback]) eval(selback).className=''; }
selback=t.id; t.className='sel';
aparece(t); 
//alert(t.offsetHeight);
 }

function aparece(t) { var aes,sb,i,nsb;
aes=document.body.innerHTML.match(/id=t[0-9]*_[0-9]*/g);
sb=t.id;
for(i=0;i<aes.length;i++) if(aes[i]=='id='+sb) break; if(i==aes.length) return;
delete aes;
var h,y0,y1,m0,m1;
h=t.offsetHeight;
y0=h*i; y1=y0+h;
m0=document.body.scrollTop;
m1=document.body.scrollTop+document.body.clientHeight;
if(y1>m1) window.scrollBy(0,y1-m1); else if(y0<m0) window.scrollBy(0,y0-m0); }
 


function menuimg(t,s) { var img,dir;
dir=folderimages;
switch(t) {
case IROOT: img=imgroot; break;
case IFOLDER: switch(s) {
	      case ICLOSE: img=imgfolderclose; break;
	      case IOPEN: img=imgfolderopen; break; } break;
case IPLUS: switch(s) {	      
	      case ITOP: img=imgplustop; break;
	      case ITOPBOT: img=imgplustopbot; break;
	      case IMIDDLE: img=imgplusmiddle; break;
	      case IBOTTOM: img=imgplusbottom; break; } break;
case IMINUS: switch(s) {	      
	      case ITOP: img=imgminustop; break;
	      case ITOPBOT: img=imgminustopbot; break;
	      case IMIDDLE: img=imgminusmiddle; break;
	      case IBOTTOM: img=imgminusbottom; break; } break;
case IJOIN: switch(s) {	      
	      case ITOP: img=imgjointop; break;
	      case ITOPBOT: img=imgjointopbot; break;
	      case IMIDDLE: img=imgjoinmiddle; break;
	      case IBOTTOM: img=imgjoinbottom; break; }
	      if(!shownodelines) img=imglinebottom; break;
case ILINE: switch(s) {	      
	      case IBOTTOM: case ITOPBOT: img=imglinebottom; break;
	      default: img=imglinemiddle; break; } break;
case IFILE: if(s=='') img=imgfiledefault; else { dir=foldericons; img=s; } break;
} 
return(dir+"/"+img); }

function menuexpand(r,i) { return(menuclick(r,i,false)); }

function menucollapse(r,i) { return(menuclick(r,i,true)); }

function menuclick(r,n,oc) { var m,qt,mh,o,i;
if(r[VNAME]=='') return(false); 
mh=eval(r[VNAME]); m=r[VMENU];
if(typeof(n)=='number') i=n; else { for(i=0;i<m.length;i++) if(n==m[i][VLABEL]) break; if(i==m.length) return(false); }
qt=mh.children.tags('TBODY')[0].children.tags('TR'); o=m[i]; 
if(o[VMENU]!='') { if(o[VOPEN]==oc) qt[i].children.tags('TD')[1].children.tags('A')[0].click(); }
else { qt[i].children.tags('TD')[1].children.tags('A')[0].click(); } return(o); }	

function menuexpandall(r) { var m,i,qt,mh,o;
if(arguments.length<1) { r=root; } mh=eval(r[VNAME]); m=r[VMENU];
qt=mh.children.tags('TBODY')[0].children.tags('TR');
for(i=0;i<m.length;i++) { o=m[i]; 
if(o[VMENU]!='') { if(!o[VOPEN]) qt[i].children.tags('TD')[0].children.tags('A')[0].click(); 
menuexpandall(o); } } }

function menucollapseall() { var i,qt,o,m; 
qt=m0.children.tags('TBODY')[0].children.tags('TR'); m=root[VMENU];
for(i=0;i<m.length;i++) { o=m[i]; 
if(o[VMENU]!='') { if(o[VOPEN]) qt[i].children.tags('TD')[0].children.tags('A')[0].click(); menuclosevar(o); } } }

function menuhere() { document.write(menuhtml()); document.close(); }

function menufind(n) { var a,v,i,r; a=menufindb(n);
if(a!="x") { v=a.split(",");
for(r=root,i=0;i<v.length;i++) r=menuexpand(r,Number(v[i])); } }

function menufindb(n,r) { var m,i,o;
if(arguments.length<2) { r=root; } m=r[VMENU];
for(i=0;i<m.length;i++) { o=m[i];  if(n.toLowerCase()==o[VLABEL].toLowerCase()) { return String(i); }
if(o[VMENU]!='') { a=menufindb(n,o); if(a!="x") { return i+","+a; } } }
return "x"; }
