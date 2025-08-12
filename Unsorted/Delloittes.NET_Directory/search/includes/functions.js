<!--

// Open Window Function for Add To Favorites & Report Error Links

var WinNum=0;
var sContentID = ""; 

function WindowOpen(Url,Width,Height,Scroll)	{	
	var String;
   	String =  "toolbar=0,location=0,directories=0,status=1,menubar=0,"
	String += "scrollbars=" + Scroll + ",resizable=0,copyhistory=0,";
   	String += ",width=";
   	String += Width;
   	String += ",height=";
   	String += Height;
   	WinPic = window.open(Url,WinNum++,String);	
}

// Check for search string

function checkSearch(theform)	{
		 var passed = false;
		 if (theform.keyword.value == "" || theform.keyword.value == "Search Directory")
		 	{alert ("Please provide a search term");theform.keyword.select();}
		 else
		 	{passed = true;}
		 return passed;
	}
	

// Redirect From Combo

function ComboNavigation(id)	{

	if (id != ''){location = 'default.asp?id=' + id}

}

// Check Email Address For Newsletter

function checkGlobalEmail(theform)	{
		 var passed = false;
		 if (theform.email.value == "" || theform.email.value == "Email@Address")
		 	{alert ("Please provide a valid email address");
			theform.email.select();
			}
		 else if (theform.email.value.indexOf("@") == -1 || theform.email.value.indexOf(".") == -1) 
		 	{alert ("Please provide a valid email address");
			theform.email.select();
			}
		 else
		 	{passed = true;}
		 return passed;
	}



//-->