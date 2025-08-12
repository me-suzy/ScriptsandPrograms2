/******************************************
CM_ADD-IN - hideselectboxes (last updated: 11/13/02)
IE5+ and NS6+ only - ignores the other browsers

Because of the selectbox bug in the browsers that makes 
selectboxes have the highest z-index whatever you do 
this script will check for selectboxes that interfear with
your menu items and then hide them. 

Just add this code to the coolmenus js file
or link the cm_addins.js file to your page as well.
*****************************************/
if(bw.dom&&!bw.op){
  makeCM.prototype.sel=0
  makeCM.prototype.onshow+=";this.hideselectboxes(pm,pm.subx,pm.suby,maxw,maxh,pm.lev)"
  makeCM.prototype.hideselectboxes=function(pm,x,y,w,h,l){
    var selx,sely,selw,selh,i
    if(!this.sel){
      this.sel=this.doc.getElementsByTagName("SELECT")
		  this.sel.level=0
    }
    var sel=this.sel
    for(i=0;i<sel.length;i++){
			selx=0; sely=0; var selp;
			if(sel[i].offsetParent){selp=sel[i]; while(selp.offsetParent){selp=selp.offsetParent; selx+=selp.offsetLeft; sely+=selp.offsetTop;}}
			selx+=sel[i].offsetLeft; sely+=sel[i].offsetTop
			selw=sel[i].offsetWidth; selh=sel[i].offsetHeight			
			if(selx+selw>x && selx<x+w && sely+selh>y && sely<y+h){
				if(sel[i].style.visibility!="hidden"){sel[i].level=l; sel[i].style.visibility="hidden"; if(pm){ if(!pm.mout) pm.mout=""; pm.mout+=this.name+".sel["+i+"].style.visibility='visible';"}}
      }else if(l<=sel[i].level && !(pm&&l==0)) sel[i].style.visibility="visible"
    }
  }
}
