<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function get_mailstyles()
{
$msg="<style>
blockquote.bbc_quote{
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 10pt;
}
table.bbc_quote{
	background-color : #F0FFFF;
	border : 1 solid Black;
	color : #444444;
}
td.bbc_quote_title{
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 9pt;
	font-weight : bold;
}
table.bbc_code{
	background-color : #F0FFFF;
	border : 1 solid Black;
	color : #444444;
}
td.bbc_code_title{
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 9pt;
	font-weight : bold;
}
PRE.bbc_code {
	font-family : \"Courier New\", Courier, monospace;
	font-size : 10pt;
}
a.copyright:hover{
	text-decoration : underline;
}
.contentcopy{
	font-size : 12pt;
	font-family : \"Times New Roman\", Times, serif;
}
ul.faqe{
	list-style-type : none;
}
</style>\n";
return $msg;
}
?>