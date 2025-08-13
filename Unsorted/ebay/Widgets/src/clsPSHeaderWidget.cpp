/*	$Id: clsPSHeaderWidget.cpp,v 1.2 1999/05/19 02:34:09 josh Exp $	*/
//
//	File:	clsPSHeaderWidget.cpp
//
//	Class:	clsPSHeaderWidget
//
//	Author:	Wen Wen
//
//	Function:
//		This class displays a NetMind logo
//							
// Modifications:
//				- 2/2/99	Wen - Created
//
#include "widgets.h"
#include "clsPSHeaderWidget.h"


// constructor
//
clsPSHeaderWidget::clsPSHeaderWidget(clsMarketPlace* pMarketPlace)
{
	mpMarketPlace = pMarketPlace;

}	

clsPSHeaderWidget::~clsPSHeaderWidget()
{
}

// emit the HTML
//
void clsPSHeaderWidget::EmitHTML(ostream* pStream, const char* pHeader)
{
	// display netmind brand and link to netmind site.
	*pStream	<<	"<table border=\"0\" width=\"100%\">\n"
					"<tr><td valign=\"middle\" width=\"10%\"><img src=\""
				<<	mpMarketPlace->GetPicsPath()
				<<	"pslogo.gif\" alt=\"Personal Shopper\" border=\"0\" align=\"middle\">"
					"<td valign=\"bottom\">&nbsp;<b><font face=\"Helvetica, Arial\" size=+2>"
				<<	pHeader
				<<	"</font></b></td>\n"
					"<td align=\"right\"><a href=\"http://www.netmind.com\">"
					"<img src=\""
				<<	mpMarketPlace->GetPicsPath()
				<<	"poweredby.gif\" alt=\"Powered by NetMind\" align=\"middle\""
					"border=0></a></td></tr></table>";

}
