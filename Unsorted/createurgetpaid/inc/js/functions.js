function CheckAll(isOnload)
{
	var trk=0;
	
	for (var i = 0; i < frm.elements.length; i++)
	{
		var e = frm.elements[i];
		
		if ((e.name != 'allbox') && (e.type=='checkbox'))
		{
			if (isOnload != 1)
			{
				trk++;
				e.checked = frm.allbox.checked;
				
				if (frm.allbox.checked)
				{
					hL(e);
				}
				else
				{
					dL(e);
				}
				
				if (frm.nullbulkmail)
					frm.nullbulkmail.disabled = frm.notbulkmail.disabled;
			}
			else
			{
				e.tabIndex = i;
				
				if (e.checked)
				{
					hL(e);
				}
				else
				{
					dL(e);
				}
			}
		}
	}
}

function CheckItem(CB)
{
	if (CB.checked)
		hL(CB);
	else
		dL(CB);
	
	var TB=TO=0;
	
	for (var i = 0; i < frm.elements.length; i++)
	{
		var e = frm.elements[i];
		
		if ((e.name != 'allbox') && (e.type=='checkbox'))
		{
			TB++;
			
			if (e.checked)
				TO++;
		}
	}
	
	if (TO==TB)
		frm.allbox.checked=true;
	else
		frm.allbox.checked=false;
}

function hL(E)
{
	while (E.tagName!="TR")
	{
		E=E.parentNode;
	}
	
	E.className = "H";
}

function dL(E)
{
	while (E.tagName!="TR")
	{
		E=E.parentNode;
	}
	
	E.className = "";
}