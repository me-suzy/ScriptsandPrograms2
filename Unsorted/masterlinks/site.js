////////////////////////////////////////////////////////////////////////////
// db Masters' Links Directory 3.1.2, Copyright (c) 2003 db Masters Multimedia
// Links Directory comes with ABSOLUTELY NO WARRANTY
// Licensed under the AGPL
// See license.txt and readme.txt for details
////////////////////////////////////////////////////////////////////////////
function validate_cat()
{
	if(document.getElementById('form').name.value==""){alert("Please enter a name for this category.");return false;}		
	else if(document.getElementById('form').dsc.value==""){alert("Please enter a short description of this category.");return false;}
}
function validate_link()
{
	if(document.getElementById('form').name.value==""){alert("Please enter a name for your website.");return false;}
	else if(document.getElementById('form').dsc.value==""){alert("Please enter a short description of your website.");return false;}
	else if(document.getElementById('form').url.value==""){alert("Please enter the URL of your website.");return false;}
}
function validate_add()
{
	if(document.getElementById('form').cat_id.selectedIndex == 0){alert("Please choose a category for your website.");return false;}		
	else if(document.getElementById('form').name.value==""){alert("Please enter a name for your website.");return false;}
	else if(document.getElementById('form').dsc.value==""){alert("Please enter a short description of your website.");return false;}
	else if(document.getElementById('form').url.value==""){alert("Please enter the URL of your website.");return false;}
}
function validate_edit()
{
	if(document.getElementById('form').name.value==""){alert("Please enter a name for your website.");return false;}
	else if(document.getElementById('form').dsc.value==""){alert("Please enter a short description of your website.");return false;}
	else if(document.getElementById('form').url.value==""){alert("Please enter the URL of your website.");return false;}
}
function validate_login()
{
	if(document.getElementById('form').url.value==""){alert("Please enter the URL of the website you wish to edit.");return false;}
	else if(document.getElementById('form').email.value==""){alert("Please enter the email address you registered the website with.");return false;}
	else if(document.getElementById('form').password.value==""){alert("Please enter the password you registered the website with.");return false;}
}
function validate_admin()
{
	if(document.getElementById('form').pw.value==""){alert("Please enter a password.");return false;}
}
function validate_remind()
{
	if(document.getElementById('form').email.value==""){alert("Please enter your email address.");return false;}
}
function blocking(nr)
{
	if (document.getElementById)
	{
		current=(document.getElementById(nr).style.display=='none')?'block':'none';
		document.getElementById(nr).style.display=current;
	}
}