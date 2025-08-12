var textarea = document.getElementsByTagName('textarea');

function insert(text1, text2)
{
	var area = textarea[0];
	area.focus();
	if (typeof document.selection != 'undefined') /* IE */
	{
		var sel = document.selection.createRange();
		var str = sel.text;
		sel.text = text1 + str + text2;
		sel = document.selection.createRange();
		if (str.length == 0)
		{
			sel.move('character', -text2.length);
		}
		else
		{
			sel.moveStart('character', text1.length + str.length + text2.length);
		}
		sel.select();
	}
	else if (typeof area.selectionStart != 'undefined') /* Gecko */
	{
		var start = area.selectionStart;
		var end = area.selectionEnd;
		var str = area.value.substring(start, end);
		area.value = area.value.substr(0, start) + text1 + str + text2 + area.value.substr(end);
		var pos;
		if (str.length == 0)
		{
			pos = start + text1.length;
		}
		else
		{
			pos = start + text1.length + str.length + text2.length;
		}
		area.selectionStart = pos;
		area.selectionEnd = pos;
	}
	else /* Others */
	{
		var pos;
		var re = new RegExp('^[0-9]{0,3}$');
		while (!re.test(pos))
		{
			pos = prompt('Insert string at position (0...' + area.value.length + '):', area.value.length);
		}
		if (pos > area.value.length)
		{
			pos = area.value.length;
		}
		var str = prompt('Please enter string:');
		area.value = area.value.substr(0, pos) + text1 + str + text2 + area.value.substr(pos);
	}
}

// Correctly handle PNG transparency in Win IE 5.5 or higher.
// http://homepage.ntlworld.com/bobosola. Updated 02-March-2004

function correctPNG()
{
	for(var i=0; i<document.images.length; i++)
	{
		var img = document.images[i]
		var imgName = img.src.toUpperCase()
		if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
		{
			var imgID = (img.id) ? "id='" + img.id + "' " : ""
			var imgClass = (img.className) ? "class='" + img.className + "' " : ""
			var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' "
			var imgStyle = "display:inline-block;" + img.style.cssText
			if (img.align == "left") imgStyle = "float:left;" + imgStyle
			if (img.align == "right") imgStyle = "float:right;" + imgStyle
			if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle
			var strNewHTML = "<span " + imgID + imgClass + imgTitle
			+ " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
			+ "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
			+ "(src=\'" + img.src + "\', sizingMethod='scale');\"></span>"
			img.outerHTML = strNewHTML
			i = i-1
		}
	}
}
window.attachEvent("onload", correctPNG);