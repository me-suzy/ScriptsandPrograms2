function avatardisplay(what)
{
    value = what.options[what.selectedIndex].value;
    if (value != '')
        if (document.images)
            document.images['avatarimg'].src = 'images/avatars/' + value;
}

function SetAllCheckBoxes(FormName, FieldName, CheckValue)
{
	if(!document.forms[FormName])
		return;
	var objCheckBoxes = document.forms[FormName].elements[FieldName];
	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = CheckValue;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = CheckValue;
}

function hyperlink(FormName, FieldName)
{
	var link = prompt("Enter your link URI below.  Remember the http:// part of the address!", "");
	var linkname = prompt("Enter your link name below.", "");
	var link = "<a href=\"" + link + "\">" + linkname + "</a>";
	insert(FormName, FieldName, link);
}

function insert(FormName, FieldName, toInsert)
{
	document.forms[FormName].elements[FieldName].value = document.forms[FormName].elements[FieldName].value + toInsert;
}

function profile(id,text)
{
	FormName = 'form'+id;
	FieldName = 'info'+id;
	document.forms[FormName].elements[FieldName].value = text;
}