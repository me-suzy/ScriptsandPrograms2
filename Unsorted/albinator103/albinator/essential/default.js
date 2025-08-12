function ManWindow()
{ ManWindow=window.open('','ManWindow','maximize=1,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=1,width=620,height=350'); 
}

function openwin()
{
terms=window.open("","terms","status=no,resize=no,toolbar=no,scrollbars=yes,width=500,height=360,maximize=no");
}

function onLoad()
{
		if (document.forms[0].length > 0)
		document.forms[0].elements[0].focus()
}

function loginCheck()
{
uidlen  = document.Login.uidform.value;
passlen = document.Login.password.value;

		if (document.Login.uidform.value == "")
		{
			alert('Please enter a username');
			document.Login.uidform.focus();
			return false;
		}
		if (uidlen.length < 4)
		{
			alert('username must be atleast 4 char long')
			document.Login.uidform.focus()
			return false
		}
		if (document.Login.password.value == "")
		{
			alert('Please enter a password')
			document.Login.password.focus()
			return false
		}	
		if (passlen.length < 6)
		{
			alert('password must be atleast 6 char long')
			document.Login.password.focus()
			return false
		}
}

function Popup(url, window_name, window_width, window_height) 
{ 
	var WinWd, WinHt;

	showindow = "toolbar=no,location=no,directories=no,"+ "status=no,menubar=no,scrollbars=yes,"+ "resizable=yes,width="+window_width+",height="+window_height; 

	WinWd = screen.width/2 - window_width/2;
	WinHt = screen.height/2 - window_height/2;

	NewWindow=window.open(url,window_name,showindow);
	NewWindow.moveTo(WinWd,WinHt);
}
