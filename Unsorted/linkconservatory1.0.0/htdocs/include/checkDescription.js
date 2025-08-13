function checkDescription()
{
	if(document.addItemForm.inputDesc.value.length > 255 )
	{
		var message = 'Descriptions must be 255 characters or less...\n';
		message = message + 'Your description has been truncated.  Review and ';
		message = message + 'click submit again is acceptable.';
		alert(message);
		
		document.addItemForm.inputDesc.value =
			document.addItemForm.inputDesc.value.substring(0,255);
		return(false);
	}
	else
	{
		return(true);
	}
}
