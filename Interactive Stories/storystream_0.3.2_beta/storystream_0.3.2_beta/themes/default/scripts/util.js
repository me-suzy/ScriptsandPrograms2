
function toggleDiv(divName, show, type)
{
	var element = document.getElementById (divName);
	if (element)
	{
		element.style.display = show ? type : 'none';
	}
}
