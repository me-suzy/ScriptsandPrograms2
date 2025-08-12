var popupLinks = document.getElementsByTagName("a");

for (i = 0; i < popupLinks.length; i++)
{
	if (popupLinks[i].className == "thumbnail")
	{
		popupLinks[i].onclick = expandThumbnail;
	}
}
