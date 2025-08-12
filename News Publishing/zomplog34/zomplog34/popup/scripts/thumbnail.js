/* This page is heavily based on popup code from the wonderful "www.themaninblue.com" */

function expandThumbnail(theTarget)
{
	var theBody = document.getElementsByTagName("body")[0];
	var theCanvasShadow = document.getElementById("canvasShadow");
	var theIDNum = 0;

	var theContainer = document.createElement("div");
	var theCloseLink = document.createElement("a");
	var theImageCloseLink = document.createElement("a");
	var theImage = document.createElement("img");
	var theSpan1 = document.createElement("div");
	var theSpan2 = document.createElement("span");
	var theBreak = document.createElement("br");
	
	var hrefSrc = this.href.replace(/\?.*/, "");
	var hrefWidth = this.href.replace(/.*\?(.*),.*/, "$1");
	var hrefHeight = this.href.replace(/.*,/, "");

	theIDNum = parseInt(this.id.replace(/.*thumbnailLink(.).*/, "$1"));

	theCanvasShadow.innerHTML = "";

	theBody.style.backgroundColor = "#000000";
	theCanvasShadow.className = "on";
	
	theContainer.id = "popupContainer";
	theContainer.style.width = hrefWidth + "px";

	theCloseLink.id = "closeLink";	
	theCloseLink.href = "#";
	theCloseLink.onclick = closeThumbnail;
	theCloseLink.title = "Close this image";
	theCloseLink.innerHTML = "";
	
	theSpan1.appendChild(theCloseLink);
	theSpan1.style.width = hrefWidth;
	theSpan1.id = "linkContainer";

	theImage.id = "PopupImage";
	theImage.src = hrefSrc;
	theImage.width = hrefWidth;
	theImage.height = hrefHeight;
	theImage.alt = this.title.replace(/View full size /, "");
	theImage.onclick = closeThumbnail;

	theImageCloseLink.id = "closeImage";	
	theImageCloseLink.href = "#";
	theImageCloseLink.onclick = closeThumbnail;
	theImageCloseLink.title = "Close this image";
	theImageCloseLink.appendChild(theImage);

	theSpan2.innerHTML = theImage.alt;
	
	theContainer.appendChild(theSpan1);
	theContainer.appendChild(theImageCloseLink);
	theContainer.appendChild(theBreak);
	theContainer.appendChild(theSpan2);
	theCanvasShadow.appendChild(theContainer);

	return false;
}

function closeThumbnail()
{
	var theBody = document.getElementsByTagName("body")[0];
	var theCanvasShadow = document.getElementById("canvasShadow");
	theBody.style.backgroundColor = "#FFFFFF";
	theCanvasShadow.className = "";
	return false;
}