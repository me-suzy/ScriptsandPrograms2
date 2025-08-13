event_addListener( window, "load", function() { ContextMenu.intializeContextMenu() } )

document.writeln('<iframe scrolling="no" class="ContextMenu" marginwidth="0" marginheight="0" frameborder="0" style="position:absolute; left:-600px; top:0px; width:400px; height:200px; z-index:50000000;" id="PopUp" name="PopUp"></iframe>');

ContextMenu.intializeContextMenu=function()
{
	PopUp    = self.frames["PopUp"]
	PopUpcss = document.getElementById("PopUp")

	event_addListener(document, "mousedown", function(){ContextMenu.hidePopup()})
	event_addListener(window, "blur", function(){ ContextMenu.hidePopup()})

	PopUpcss.onfocus  = function(){ContextMenu.showPopup()};
	PopUpcss.onblur  = function(){ContextMenu.showPopup()};

	this.hidePopup();
}

function ContextSeperator(){}

function ContextMenu(){}

ContextMenu.hidePopup=function()
{
	PopUpcss.style.visibility = "hidden"
}

ContextMenu.showPopup=function()
{
	PopUpcss.style.visibility = "visible"
}

ContextMenu.display=function(popupoptions, e)
{
	e = event_DOMify(e);ContextMenu.hidePopup()
	ContextMenu.populatePopup(popupoptions,window)
	
	e.preventDefault();
	e.stopPropagation();
	var sCommand =	"ContextMenu.showPopup(); " +
					"ContextMenu.fixSize(); " +
					"ContextMenu.fixPos(" + e.clientX + "," + e.clientY + ")";
	window.setTimeout(sCommand, 100);
}

 ContextMenu.getScrollTop=function()
 {
	return (typeof document.body.scrollTop == "undefined") ? window.pageYOffset : document.body.scrollTop;
 }
 
 ContextMenu.getScrollLeft=function()
 {
	return (typeof document.body.scrollLeft == "undefined") ? window.pageXOffset : document.body.scrollLeft;
 }
 

ContextMenu.fixPos=function(x,y)
{
	var docheight,docwidth,dh,dw;	
	docheight = document.body.clientHeight || window.innerHeight;
	docwidth  = document.body.clientWidth || window.innerWidth;
	dh = (PopUpcss.offsetHeight+y) - docheight;
	dw = (PopUpcss.offsetWidth+x)  - docwidth;
	if(dw>0)
	{
		PopUpcss.style.left = (x - dw) + ContextMenu.getScrollLeft() + "px";		
	}
	else
	{
		PopUpcss.style.left = x + ContextMenu.getScrollLeft();
	}
	if(dh>0)
	{
		PopUpcss.style.top = (y - dh) + ContextMenu.getScrollTop() + "px"
	}
	else
	{
		PopUpcss.style.top  = y + ContextMenu.getScrollTop();
	}
}

ContextMenu.fixSize=function()
{
	var doc = PopUp.document || PopUpcss.contentDocument;
	var body = doc.getElementById("ContextMenu-Body");
	PopUpcss.style.width = body.offsetWidth + "px";
	PopUpcss.style.height = body.offsetHeight + "px";
}

ContextMenu.populatePopup=function(arr)
{
	var win,alen,i,tmpobj,doc,height,htmstr;
	alen = arr.length;

	doc = PopUp.document || PopUpcss.contentDocument || null;

	PopUp_Commands = new Array();

	doc.open("text/html");
	doc.write('<html><head><link rel="StyleSheet" type="text/css" href="misc/context.css"></head>');
	doc.write('<body onselectstart="return false;">');
	doc.write('<div id="ContextMenu-Body" class="ContextMenu-Body">');


	for(i=0;i<alen;i++)
	{
		if(arr[i].constructor==ContextItem)
		{
			PopUp_Commands[i] = arr[i].action;

			var sHtml = "<div nowrap='true' ";
			
			if(arr[i].disabled)
			{
				sHtml += "class='ContextMenu-Disabled' ";
				sHtml += "onmouseover='this.className=\"ContextMenu-Disabled-Over\"' ";
				sHtml += "onmouseout='this.className=\"ContextMenu-Disabled\"' ";
				
				sHtml += '><span class="ContextMenu-DisabledContainer"><span class="ContextMenu-DisabledContainer">'
				sHtml += (arr[i].bold ? '<b>' : '') + arr[i].text + (arr[i].bold ? '</b>' : '') + '</span></span></div>'
			}
			else
			{
				sHtml += "class='ContextMenu-Item' ";
				sHtml += "onmouseover='this.className=\"ContextMenu-Over\"' ";
				sHtml += "onmouseout='this.className=\"ContextMenu-Item\"' ";
				
				if (arr[i].action != null) 
					sHtml += "onclick='window.parent.ContextMenu.hidePopup(); window.parent.PopUp_Commands[" + i + "]();'>" + (arr[i].bold ? '<b>' : '') + arr[i].text + (arr[i].bold ? '</b>' : '') + "</div>"
			}
			doc.write(sHtml);
		}
		else
		{
			doc.write("<div class='ContextMenu-Separator'></div>");
		}
	}
	doc.write('</div>');
	doc.write('</body></html>');
	doc.close();
}

function ContextItem(str,fn,disabled,bold)
{
	this.text     = str;
	this.action   = fn; 
	this.disabled = disabled || false;
	this.bold     = bold || false;
}