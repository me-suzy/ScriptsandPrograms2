<!--
function SelectAllBoxes (myform, TotalField, CheckboxField)
{
	for (i = 1; i <= myform[TotalField].value; i++)
		myform[CheckboxField+i].checked = true;
}

function DeSelectAllBoxes (myform, TotalField, CheckboxField)
{
	for (i = 1; i <= myform[TotalField].value; i++)
		myform[CheckboxField+i].checked = false;
}

function ConfirmArchivePurgeAction (myform, TotalField, CheckboxField, action)
{
	var numSelected=0;
	for (i = 1; i <= myform[TotalField].value; i++)
		if (myform[CheckboxField+i].checked)
			numSelected++;

	// Nothing selected?
	if (numSelected == 0)
		return false;

	return confirm('You are about to ' + action + ' ' + numSelected + ' articles. Are you sure?');
}

function ConfirmMassAction (myform, TotalField, CheckboxField)
{
	var numSelected=0;

	if (myform.NewCatAction.value != '-'  &&  myform.NewCatID.value == '0')
	{
		alert ("You have chosen to copy or remove a Category, but you have not chosen the appropriate category");
		return false;
	}

	for (i = 1; i <= myform[TotalField].value; i++)
		if (myform[CheckboxField+i].checked)
			numSelected++;

	// Nothing selected?
	if (numSelected == 0)
		return false;

	return confirm('You are about to perform mass-maintenance on ' + numSelected + ' articles. Are you sure?');
}


//-->
