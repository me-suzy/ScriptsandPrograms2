// browser identification
var agt = navigator.userAgent.toLowerCase();
var is_ie = ((agt.indexOf("msie") != -1) && (agt.indexOf("opera") == -1));
var is_gecko = ( (agt.indexOf('gecko') != -1) && (agt.indexOf("safari") == -1) && (agt.indexOf("konqueror") == -1) );
var is_opera = (agt.indexOf("opera") != -1);
var is_safari = (agt.indexOf("safari") != -1);
var is_konqueror = (agt.indexOf("konqueror") != -1);



function openComments(post_id, href) {
	
	if ( is_gecko || is_ie ) {
		if (eval(document.getElementById('comments_'+post_id)).innerHTML.length > 20) { 
			eval(document.getElementById('comments_'+post_id)).innerHTML = "";
		} else {
			var myFrame = document.createElement("IFRAME");
			myFrame.src = pivot_url + "/entry.php?id="+post_id+"&t=_aux_template_onlycomments.html";
			myFrame.id="newframe";
			myFrame.width=0;
			myFrame.height=0;
			myFrame.style.display="none";
			document.body.appendChild(myFrame);

		}
	} else {
		self.location = href;
	}
	
}

function openBody(post_id, href) {

	if ( is_gecko || is_ie ) {
		if (eval(document.getElementById('body_'+post_id)).innerHTML.length > 20) { 
			eval(document.getElementById('body_'+post_id)).innerHTML = "";
		} else {
			var myFrame = document.createElement("IFRAME");
			myFrame.src = pivot_url + "/entry.php?id="+post_id+"&t=_aux_template_onlybody.html";
			myFrame.id="newframe";
			myFrame.width=0;
			myFrame.height=0;
			myFrame.style.display="none";
			document.body.appendChild(myFrame);
		}
	} else {
		self.location = href;
	}
}


function iframeCallback(obj, target, post_id) {

	var divs = document.getElementsByTagName('div');

	for (i=0;i<divs.length;i++)	{
		if (divs[i].id.substring(0,9)== (target+"_") )	{
			divs[i].innerHTML="";
		}
	}
	eval(document.getElementById(target+'_'+post_id)).innerHTML = obj.document.body.innerHTML;
}

