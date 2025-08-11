function Mesg(Msg)
{
	if (Msg.length > 0)
		alert(Msg);
}

function Send(frmname, field, value)
{
	var frm = document.getElementById(frmname)

	frm.action += '&' + field + '=' + value;
	frm.submit();
}

function SetOption(name, value)
{
	var frm = document.getElementById("item_list")

	for (var i=0; i<frm.length; i++)
	{
		if (frm.elements[i].name == name)
			var element = frm.elements[i];
	}
	if (element == null)
		return;

	if (element.type == 'checkbox')
	{
		if (element.value == value)
			element.checked = true;
	}
	else if (element.type == 'radio')
	{
		for (var i=0; i<frm.length; i++)
		{
			if (frm.elements[i].name == name &&
				frm.elements[i].value == value)
				frm.elements[i].checked = true;
		}
	}
	else if (element.type == 'select-one')
	{
		for (var i=0; i<element.length; i++)
		{
			if (element.options[i].value == value)
				element.selectedIndex = i;
		}
	}
}

/* List View Functions */
function Sort(SortKey)
{
	var frm = document.getElementById("item_list")

	if (frm.Sort.value.indexOf(SortKey) == -1)
		frm.Sort.value = SortKey;
	else
	{
		if (frm.Sort.value.indexOf('Rev') == -1)
			frm.Sort.value = 'Rev' + SortKey;
		else
			frm.Sort.value = SortKey;
	}
	frm.submit(); 
}

function ToggleAll() 
{
	for (var i=0;i<frm.elements.length;i++) 
	{
		var e=frm.elements[i];
		if (e.type == 'checkbox')
			e.checked=!e.checked;
	}
}

function SelectAll() 
{
	var frm = document.getElementById("item_list")
	for (var i=0;i<frm.elements.length;i++) 
	{
		var e=frm.elements[i];
		if (e.type == 'checkbox')
			e.checked=true;
	}
}

function ClearAll() 
{
	var frm = document.getElementById("item_list")
	for (var i=0;i<frm.elements.length;i++) 
	{
		var e=frm.elements[i];
		if (e.type == 'checkbox')
			e.checked=false;
	}
}

function CheckItem(itu_name) 
{
	var frm = document.getElementById("item_list")
	for (var i=0;i<frm.elements.length;i++) 
	{
		var e=frm.elements[i];
		if (e.name == itu_name && e.type == 'checkbox')
			e.checked=true;
	}
}

function CheckItem2(e) 
{
    e.checked=true;
}

function ChangeFolder() 
{
	var frm = document.getElementById("item_list")
	frm.DestFolder.selectedIndex=frm.BottomFolder.selectedIndex;
}

function ChangeBottomFolder() 
{
	var frm = document.getElementById("item_list")
	frm.BottomFolder.selectedIndex=frm.DestFolder.selectedIndex;
}

/* Message View Functions */
function Print(url) {
	window.open(url,'Print','height=400,width=600,scrollbars=yes,status=yes,resizable=1,toolbar=yes,menubar=yes');
}

function Source(url) {
	window.open(url,'Source','height=400,width=600,scrollbars=yes,status=yes,resizable=1');
}


function changePage(newLoc)
 {
   nextPage = newLoc.options[newLoc.selectedIndex].value
		
   if (nextPage != "")
   {
      document.location.href = nextPage
   }
 }

