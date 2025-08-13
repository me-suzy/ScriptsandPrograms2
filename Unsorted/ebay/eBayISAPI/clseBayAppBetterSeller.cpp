/*	$Id: clseBayAppBetterSeller.cpp,v 1.6.94.3.20.1 1999/08/01 03:01:07 barry Exp $	*/
//
//	File:	clseBayAppBetterSeller.cc
//
//	Class:	clseBayApp
//
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//		Show how to become a better seller
//
// Modifications:
//				- 07/02/98 wen	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
//
// BetterSeller
//
void clseBayApp::BetterSeller(CEBayISAPIExtension *pServer, int ItemNo)
{
	// Setup
	SetUp();	

	// Headers
	*mpStream <<	"<HTML>"
					"<HEAD>";

	// Usual Title and Header
	*mpStream <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Better Seller Guide"
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// Spacer
	*mpStream <<	"<br>";

/*	// Title using black on darkgrey table
	*mpStream <<	"<center>"
					"<table border=1 cellspacing=0 "
					"width=100% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=5 color=#000000>"
					"<b>"
					"Better Seller Quide"
					"</b></font></td>\n"
					"</tr>\n"
					"</table></center>\n";
	// Spacer
	*mpStream <<	"<br>";
*/
	*mpStream << "<h2>How to Get More Bids! </h2>\n"
				 "<P>eBay charges a listing fee when you relist an auction.  If "
				 "your item did not sell the first time around, and it sells when "
				 "you list it a second time, we will automatically refund the "
				 "relisting fee (a few conditions apply, review our "
				 "<a href=\""
			  << mpMarketPlace->GetHTMLPath()
			  << "help/basics/f-selling.html#29\">relist policy</a>" 
				 " for details).  Take a moment to review the tips below to help "
				 "you relist your item.\n"
			     "<h2>Tips for a Successful Re-listing</h2>\n"
			     "</B> \n";

	*mpStream << "<ol>";

	*mpStream << "<li><b> Add Photos</b> \n"
			     "<ul type = disc>\n"
			     "<li> People love to see what they're buying! <br>\n"
			     "<li> If you'd like to learn how, see the tips on adding pictures (<a href=\"http://pages.ebay.com/help/basics/phototut-index.html\">http://pages.ebay.com/help/basics/phototut-index.html</a>).\n" 
			     "Need more information on including pictures with your listings? Two excellent "
			     "tutorials on the subject can be found at (<a href=\"http://www.pongo.com/tutorials/aweb-images/\">http://www.pongo.com/tutorials/aweb-images/</a>) \n"
			     "and (<a href=\"http://www.twaze.com/aolpix/\">http://www.twaze.com/aolpix/</a>). \n"
			     "(These tutorials are maintained by eBay community members, and eBay is \n"
			     "not responsible for their content or accuracy). <br>\n"
			     "<b><br>\n"
			     "</b> \n"
			     "</ul>\n"
			     "</li>\n";

	*mpStream << "<li><b> Change the Title</b> <br>\n"
			     "Some bidders start by scanning the titles. So make yours eye-catching! Winners are descriptive and exciting. Is your item: <br>\n"
			     "<ul type = disc>\n"
			     "<li> beautiful \n"
			     "<li> rare \n"
			     "<li> mint \n"
			     "<li> wonderful \n"
			     "<li> cherished \n"
			     "<li> original \n"
			     "<li> or something better? <br>\n"
			     "<br>\n"
			     "</ul>\n"
			     "</li>	\n";

	*mpStream << "<li><b> Price it right</b> \n"
			     "<ul type = disc>\n"
			     "<li> When you re-list your item, try setting the minimum bid at a lower price. \n"
			     "<li> When you re-list a Reserve Price Auction item, you may want to consider a lower Reserve Price. <br>\n"
			     "<br>\n"
			     "</ul>\n"
			     "</li>\n";

	*mpStream << "<li><b> Change the category</b> \n"
			     "<ul type = disc>\n"
			     "<li> Look for other categories that might help buyers find your item. For \n"
			     "example, if you listed your Disney-Beanie Baby in the Collectibles: Disney \n"
			     "category, you may want to re-list it in the Toys: Beanie Babies: Disney category. \n"
			     "<li> Check the <a href=\""
			  << mpMarketPlace->GetListingPath()
			  << "/overview.html\">Category Overview</a> to find just the right one.\n"
			     "<li> Or search for items like yours and see where they're listed. You can \n"
			     "even check to see how bidding is going. <br>\n"
			     "<br>\n"
			     "</ul>\n"
			     "</li>	\n";


	*mpStream << "<li><b> Update the description</b> \n"
				 "<ul type = disc>\n"
				 "<li> Include as much information and as many details as you can. \n"
				 "<li> Tell about how you got a particular item, its age, the material, the shape, and more. \n"
				 "<li> Remember, a minor detail to you could be a very big deal to a potential bidder!    \n" 
				 "</ul>\n"
				 "</li> \n";

	*mpStream << "</ol>";

	*mpStream << "<p> While you will have to pay a listing fee to get your auction re-listed, eBay \n"
				 "will automatically refund the re-listing fee if your item sells the second time. \n"
				 "If it doesn't sell, you will be responsible for the re-listing fee. A few conditions \n"
				 "apply, so review the <a href=\""
			  << mpMarketPlace->GetHTMLPath()
			  << "help/basics/f-faq.html#27\">re-list policy</a> for details. </p>\n";

	// Begin the form					
	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageListItemForSale)
			  <<	"eBayISAPI.dll?ListItemForSale"
			  <<	"\""
					">\n";

	if (ItemNo)
	{
		*mpStream <<	"<input type=hidden name=item ";
		*mpStream <<	"value=\""
				  <<	ItemNo
				  <<	"\">\n";
	}

	*mpStream << "<p><b>Click</b> \n"
			     "  <input type=\"submit\" value=\"relist\">\n"
			     "  <b>after you've read these tips and are ready to relist your item</b>.</p>\n";

	*mpStream	<<	"</form>\n";

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	CleanUp();

	return;

}

