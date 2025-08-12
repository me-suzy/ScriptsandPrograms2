// Trim Function from: http://www.breakingpar.com/bkp/home.nsf/Doc?OpenNavigator&U=87256B14007C5C6A87256AFB0013C722 //

function trim(inputString) {

	if (typeof inputString != "string") { return inputString; }

	var retValue = inputString;

	var ch = retValue.substring(0, 1);

	while (ch == " ") {

		retValue = retValue.substring(1, retValue.length);

		ch = retValue.substring(0, 1);

	}

	ch = retValue.substring(retValue.length-1, retValue.length);

	while (ch == " ") {

		retValue = retValue.substring(0, retValue.length-1);

		ch = retValue.substring(retValue.length-1, retValue.length);

	}

	while (retValue.indexOf("  ") != -1) { 

		retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length); 

   	}

	return retValue;
 
}

function countit(count,name,dis){

	count = trim( count ); 

	count = count.split( " " ) ;

    	if (document.layers) {

        	document.layers[name].document.close();
       		document.layers[name].document.write(count.length);
       		document.layers[name].document.close();

    	} else {

		if (document.all) {

			eval("document.all." + name + ".innerHTML='" + count.length + "'");

		} else {

			document.getElementById(name).innerHTML = count.length;

		}

	}

	for( i=0 ; i<count.length ; i++ ) {

		if( count[i].length > 60 ) { 

			if(dis==1) document.form1.addstory.disabled=true;
			if(dis==2) document.form1.addchapter.disabled=true;

			return;

		}

	}

	if( count.length > maxl ) {

		if(dis==1) document.form1.addstory.disabled=true;
		if(dis==2) document.form1.addchapter.disabled=true;

	} else {

		if(dis==1) document.form1.addstory.disabled=false;
		if(dis==2) document.form1.addchapter.disabled=false;

	}

}
	
function mozWrap(txtarea, lft, rgt) {
	var selLength = txtarea.textLength;
	var selStart = txtarea.selectionStart;
	var selEnd = txtarea.selectionEnd;
	if (selEnd==1 || selEnd==2) selEnd=selLength;
	var s1 = (txtarea.value).substring(0,selStart);
	var s2 = (txtarea.value).substring(selStart, selEnd)
	var s3 = (txtarea.value).substring(selEnd, selLength);
	txtarea.value = s1 + lft + s2 + rgt + s3;
}
	
function IEWrap(lft, rgt) {
	strSelection = document.selection.createRange().text;
	if (strSelection!="") {
	document.selection.createRange().text = lft + strSelection + rgt;
	}
}
	
function wrapSelection(txtarea, lft, rgt) {
	if (document.all) {IEWrap(lft, rgt);}
	else if (document.getElementById) {mozWrap(txtarea, lft, rgt);}

}

function breaker(someText) {

	return someText.replace (/\n/gm, '<br>' );

}


