function isNumber (x)
	{
	var anum=/(^\d+$)|(^\d+\.\d+$)/
	if (anum.test(x))
		return true;
	else 
		return false;
	}
function ValidateUserForm ()
	{
	if(document.userForm.realname.value.length<1)
		{return false;}
	if(document.userForm.username.value.length<1)
		{return false;}
	if(document.userForm.password.value.length<1)
		{return false;}
	if(document.userForm.cpassword.value != document.userForm.password.value)
		{return false;}
	return true;
	}
function ConfirmDeleteUser ()
	{
	if(window.confirm('Delete User ?')){return true;}
	else{return false;}
	}
function ValidateGroupForm ()
	{
	if(document.groupForm.groupname.value.length<1)
		{return false;}
	return true;
	}
function ConfirmDeleteGroup ()
	{
	if(window.confirm('Delete Group ?')){return true;}
	else{return false;}
	}
function ValidateNavOrder ()
	{
	for(i=0;i<(document.orderForm.elements.length-1);i++)
		{
		val = document.orderForm.elements[i].value;
		if(!isNumber(val))
			{return false;}
		}
	return true;
	}
function ConfirmDeleteFile ()
	{
	if(window.confirm('Delete File ?')){return true;}
	else{return false;}
	}