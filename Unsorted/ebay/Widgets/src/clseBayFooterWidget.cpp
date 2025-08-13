/*	$Id: clseBayFooterWidget.cpp,v 1.2.546.2 1999/08/05 18:58:47 nsacco Exp $	*/
//
//	File:	clseBayFooterWidget.cpp
//
//	Class:	clseBayFooterWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that emits the eBay footer.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 10/20/97	Poon - Created
//				- 07/01/99	nsacco - use GetPicsPath()
//


#include "widgets.h"
#include "clseBayFooterWidget.h"


clseBayFooterWidget::clseBayFooterWidget(clsMarketPlace *pMarketPlace) : 
	clseBayWidget(pMarketPlace)
{
}



bool clseBayFooterWidget::EmitHTML(ostream *pStream)
{

	// home link
	*pStream <<		"<a href=\""
			 <<		mpMarketPlace->GetHTMLPath(PageHomePage)
			 <<		"index.html\">"
			 <<		"<img src=\""
			 <<		mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			 <<		"b_home.gif\" "
			 <<		"hspace=\"0\" vspace=\"0\" width=\"40\" height=\"24\" alt=\"Home\" border=\"0\">"
			 <<		"</a>";

	// listings link
	*pStream <<		"<a href=\""
			 <<		mpMarketPlace->GetListingPath()
			 <<		"/list\">"
			 <<		"<img src=\""
			 <<		mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			 <<		"b_list.gif\" "
			 <<		"hspace=\"0\" vspace=\"0\" width=\"56\" height=\"24\" alt=\"Listings\" border=\"0\">"
			 <<		"</a>";

	// buyers link
	*pStream <<		"<a href=\""
			 <<		mpMarketPlace->GetHTMLPath()
			 <<		"ps.html\">"
			 <<		"<img src=\""
			 <<		mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			 <<		"b_buy.gif\" "
			 <<		"hspace=\"0\" vspace=\"0\" width=\"49\" height=\"24\" alt=\"Buyers\" border=\"0\">"
			 <<		"</a>";

	// sellers link
	*pStream <<		"<a href=\""
			 <<		mpMarketPlace->GetHTMLPath()
			 <<		"seller-services.html\">"
			 <<		"<img src=\""
			 <<		mpMarketPlace->GetPicsPath()	// nsacco 07/01/99			 
			 <<		"b_sell.gif\" "
			 <<		"hspace=\"0\" vspace=\"0\" width=\"53\" height=\"24\" alt=\"Sellers\" border=\"0\">"
			 <<		"</a>";

	// search link
	*pStream <<		"<a href=\""
			 <<		mpMarketPlace->GetHTMLPath()
			 <<		"search.html\">"
			 <<		"<img src=\""
			 <<		mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			 <<		"b_search.gif\" "
			 <<		"hspace=\"0\" vspace=\"0\" width=\"49\" height=\"24\" alt=\"Search\" border=\"0\">"
			 <<		"</a>";

	// contact link
	*pStream <<		"<a href=\""
			 <<		mpMarketPlace->GetHTMLPath()
			 <<		"contact.html\">"
			 <<		"<img src=\""
			 <<		mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			 <<		"b_help.gif\" "
			 <<		"hspace=\"0\" vspace=\"0\" width=\"37\" height=\"24\" alt=\"Help\" border=\"0\">"
			 <<		"</a>";

	// news/chat link
	*pStream <<		"<a href=\""
			 <<		mpMarketPlace->GetHTMLPath()
			 <<		"newschat.html\">"
			 <<		"<img src=\""
			 <<		mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			 <<		"b_newschat.gif\" "
			 <<		"hspace=\"0\" vspace=\"0\" width=\"66\" height=\"24\" alt=\"News/Chat\" border=\"0\">"
			 <<		"</a>";

	// sitemap link
	*pStream <<		"<a href=\""
			 <<		mpMarketPlace->GetHTMLPath()
			 <<		"sitemap.html\">"
			 <<		"<img src=\""
			 <<		mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			 <<		"b_map.gif\" "
			 <<		"hspace=\"0\" vspace=\"0\" width=\"57\" height=\"24\" alt=\"Sitemap\" border=\"0\">"
			 <<		"</a>";

	// copyright notice
	*pStream <<		"<br><FONT size=\"2\">"
					"Copyright &copy; 1995-1998 "
					"<a href=\""
	//		 <<		"http://www.ebay.com/aboutebay98/\">"
	//	kakiyama 07/18/99
			 <<     mpMarketPlace->GetHTMLPath()
			 <<     "aboutebay98/\">"
					"eBay Inc.</a> "
					"All Rights Reserved."
					"</font>\n";

	return true;
}

