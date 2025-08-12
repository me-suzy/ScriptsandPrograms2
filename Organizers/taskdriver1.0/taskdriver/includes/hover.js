<script type="text/javascript"><!--
   var elem = "TR"; 
   var rClick;
 window.onload = function(){
  if(document.getElementsByTagName){
   var el = document.getElementsByTagName(elem);
    for(var i=0; i<el.length; i++){
      if(el[i].childNodes[0].tagName != "TH"
      && el[i].parentNode.parentNode.className.indexOf("tbl") != -1){
     if(i%2 == 1){
      el[i].className = "on";
      el[i].oldClassName = "on";
      el[i].onmouseout  = function(){
	     this.className = "on";
      }
    } else {
      el[i].className = "off";
      el[i].oldClassName = "off";
      el[i].onmouseout  = function(){
	     this.className = "off";
      }
    }
      el[i].onmouseover = function(){
	     if(this.className == this.oldClassName)
	       {this.className = "hover";}
	     if(this.onmouseout == null && this.className != "click"){
    	    this.onmouseout = function(){
        	    this.className = this.oldClassName;
    	    }
	     }
      }
      el[i].onclick = function(){
          if(this.className != "click"){
             this.className = "click";
          } else {
             this.className = this.oldClassName;
          }
        this.onmouseout = null;
       }
    }
   }
  }
 }
  //--></script>