function AddText(NewCode)
{
document.<<docname>>.<<field>>.value+=NewCode;
}

function addBreak()
{
	txt=prompt("{LANG:pagename}","{LANG:name}");
	if (txt!=null)
	{             
		AddTxt="\n[pagebreak title='"+txt+"']\n";
		AddText(AddTxt);
	}
}

function SimpleTag(type,tagname) {
txt=prompt(tagname,"{LANG:inserthere}");
if (txt!=null)
{             
AddTxt="["+type+"]"+txt+"[/"+type+"] ";
con = confirm("{LANG:willadd} \n"+txt+" \n");
if (!con)
{
return;
} else {
AddText(AddTxt);
}
}
}

function mlink()
{ 
txt2 = prompt("{LANG:urlname}",""); 
if (txt2 != null) { 
txt=prompt("{LANG:url}","http://"); 
if (txt != "" && txt != "http://")
{ 
if (txt2 == '')
{ 
AddTxt="[url]"+txt+"[/url]"; 
AddText(AddTxt); 
} else { 
AddTxt="[url=\""+txt+"\"]"+txt2+"[/url]"; 
AddText(AddTxt); 
} 
}
else
{
alert ("{LANG:invalidval}");
}
}
} 

function mail()
{ 
txt2 = prompt("{LANG:emailname}",""); 
if (txt2 != null) { 
txt=prompt("{LANG:email}",""); 
if (txt != "")
{ 
if (txt2 == '')
{ 
AddTxt="[email]"+txt+"[/email]"; 
AddText(AddTxt); 
} else { 
AddTxt="[email=\""+txt+"\"]"+txt2+"[/email]"; 
AddText(AddTxt); 
} 
}
else
{
alert ("{LANG:invalidval}");
}
}
}

function image()
{
txt=prompt("{LANG:imgurl}","http://");
if (txt!=null && txt != "http://") {             
AddTxt="\n[img]"+txt+"[/img] ";
AddText(AddTxt);
}
else
{
alert ("{LANG:invalidval}");
}
}

function code()
{
AddTxt="\n[code][/code]\n ";
AddText(AddTxt);
alert("{LANG:monocode}");
}

function php()
{
AddTxt="\n[php][/php]\n ";
AddText(AddTxt);
alert("{LANG:phpcode}");
}

function makebuttons()
{
	<<additional_buttons>>
	document.write(" <input type='button' value=' B ' onClick='javascript:SimpleTag(\"b\",\"Bold\")' title=\"Bold\" style=\"font-weight:bold\"> ");
	document.write(" <input type='button' value=' I ' onClick='javascript:SimpleTag(\"i\",\"Italic\")' title=\"Italic\" style=\"font-style:italic\"> ");
	document.write(" <input type='button' value=' u ' onClick='javascript:SimpleTag(\"u\",\"Underline\")' title=\"Underline\" style=\"text-decoration:underline\"> ");
	document.write(" <input type='button' value='http://' onClick='javascript:mlink()' title=\"Link\" style=\"\"> ");
	document.write(" <input type='button' value='@' onClick='javascript:mail()' title=\"Email\" style=\"\"> ");
	document.write(" <input type='button' value='image' onClick='javascript:image()' title=\"Image\" style=\"\"> ");
	document.write(" <input type='button' value='PHP' onClick='javascript:php()' title=\"PHP\" style=\"\"> ");
	document.write(" <input type='button' value='Code' onClick='javascript:code()' title=\"Monospaced Text\" style=\"\"> ");
}