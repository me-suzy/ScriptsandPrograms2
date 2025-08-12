function add(inForm,val,text)
{
	 var pos = eval("inForm.elements['sgrp[]'].options.length");
	 for (x=0; x<pos; x++){
 	  if (inForm.elements['sgrp[]'].options[x].value == val) return;
	}

	if(navigator.appName == 'Netscape'){
	 var newbie = new Option(text, val);
	 eval("inForm.elements['sgrp[]'].options[pos]=newbie");
        } else {
         var d = window.document;
         var newbie = d.createElement("OPTION");
         newbie.text = text;
         newbie.value = val;
	 d.all.sgrp.add(newbie);
       }
}

function select(){
   if (!eval("document.frm1.elements['sgrp[]']")) return true;
   var pos=eval("document.frm1.elements['sgrp[]'].options.length");
   for (x=0; x<pos; x++)
      eval("document.frm1.elements['sgrp[]'].options["+x+"].selected=true");
      return true;
   }

function remove(inForm)
{
	 var pos = eval("inForm.elements['sgrp[]'].options.length");
	 for (x=pos-1; x>=0; x--)
	  if (inForm.elements['sgrp[]'].options[x].selected)
	     eval("inForm.elements['sgrp[]'].options[x]=null");
}
