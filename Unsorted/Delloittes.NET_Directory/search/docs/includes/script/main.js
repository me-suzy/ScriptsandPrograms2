/* These functions (doSection, noSection) are used to make sidebars appear and disappear.
*/

function write_header(title,titleID,lang)
{
  var help_title= "deloittes.NET Directory Documentation";

document.write(
 
"<div id='scrbanner'>"
+"<div id='bannerrow1'>"
+"<TABLE CLASS='bannerparthead' CELLSPACING=0>"
+"<TR ID='hdr'>"
+"<TD CLASS='runninghead' nowrap>" + help_title + "</TD>"
+"<TD CLASS='product' nowrap>&nbsp;</TD>"
+"</TR>"
+"</TABLE>"
+"</div>"

);

document.write(

  "<div id='TitleRow'>"
  +"<H1 class='dtH1'><A NAME='" + titleID + "'></A><B>" + title + "</B></H1>"
  +"<H5></H5>"
  +"</div></div>"
  
  );

}

function write_footer()
{
document.write(
 
 "<DIV CLASS='footer'>"
+"<br>"
+"<HR>"
+"<p>&copy; 2001-2002 deloittes.NET. All rights reserved.</p>"
+"</div>"
+"</div>"

);

}

	function WinOpen(Url,x,y)
	{  var String;
	   String =  "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,copyhistory=0,";
	   String += ",width=";
	   String += x;
	   String += ",height=";
	   String += y;
	   WinPic=window.open(Url,WinNum++,String);	}
