/*	$Id: clsBidBoxWidget.cpp,v 1.4.2.13.8.1 1999/08/01 02:51:22 barry Exp $	*/
//
//	File:		clsBidBoxWidget.cpp
//
//	Class:		clsBidBoxWidget
//
//	Author:		Barry Boone (barry@ebay.com)
//
//	Function:
//		Show the bid box so users can place a bid.
//
//	Modifications:
//				- 2/24/99 Barry	- Create
//				- 5/03/99 Vicki - removed password field
//				- 6/23/99 Jen   - Revert to E117 (original UI layout)
//								  with new IA header and footer
//				- 07/09/99 Beth - Change wording for bidding as per 
//                                Gillian Judge/Elaine Fung
//
//////////////////////////////////////////////////////////////////////

#include "widgets.h"
#include "clsCurrencyWidget.h"
#include "clsCurrencies.h"
#include "clsBidBoxWidget.h"

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

/*
struct clsBidBoxWidgetOptionsStruct
{

};
*/


clsBidBoxWidget::clsBidBoxWidget(clsWidgetHandler *pHandler,
                                     clsMarketPlace *pMarketPlace,
                                     clsApp *pApp)
                                 : clseBayWidget(pHandler, pMarketPlace, pApp)
{
	mpMarketPlace = pMarketPlace;
}

clsBidBoxWidget::clsBidBoxWidget(clsMarketPlace *pMarketPlace,
                                     clsItem *pItem,
									 double minimumBid)
                                 : clseBayWidget(pMarketPlace)
{

	mpMarketPlace = pMarketPlace;
	mpItem        = pItem;
	mMinimumBid   = minimumBid;
}

clsBidBoxWidget::~clsBidBoxWidget()
{
}

void clsBidBoxWidget::DrawTag(ostream *pStream, const char *pName, bool /* comments = true */)
{
	*pStream << "<"
			 << pName;

	*pStream << ">";
}



void clsBidBoxWidget::SetParams(vector<char *> *pvArgs)
{
	// Let's run through our known attributes and check them out.
}

void clsBidBoxWidget::SetParams(const void *pData, const char *, bool)
{
}

long clsBidBoxWidget::GetBlob(clsDataPool *pDataPool, bool mReverseBytes)
{
	return 0;
}



bool clsBidBoxWidget::EmitHTML(ostream *pStream)
{
	if (mpMarketPlace == NULL)
		return false;

	*pStream  <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageMakeBid)
			  <<	"eBayISAPI.dll"
					"\""
					">"
			  <<	"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" "
					"VALUE=\"MakeBid\">\n"
					"<input type=hidden name=item value="
			  <<	mpItem->GetId()
			  <<	">"
					"\n";

	//new layout start:
	*pStream  <<	"\n"
					"<table border=\"1\" cellspacing=\"0\" width=\"540\" cellpadding=\"4\">\n"
//					"<tr>\n"
//					"<td width=\"40\" bgcolor=\"#99CCCC\">"
//					"<font size=\"4\" color=\"#000000\">&nbsp;</font></td>"
//					"<td width=\"500\">"
//					"<table border=\"0\" width=\"100%\" cellspacing=\"0\">"
//					"<tr>"
//					"<td>"
//					"<a href=\""
//				<<	mpMarketPlace->GetHTMLPath()
//				<<	"userid.html\"><strong>User ID</strong>"
//					"</a> "
//					"or E-mail address"
//					"</td>"
//					"<td>"
//					"<strong>Password</strong> "
//					"(<a href=\""
//			  <<	mpMarketPlace->GetHTMLPath()
//			  <<	"reqpass.html\">forgotten</a>"
//					" it?)"
//					"</td>"
//					"</tr>"
//					"<tr>"
//					"<td>"
//					"<input type=\"text\" name=\"userid\" size=\"32\" maxlength=\"64\">"
//					"</td>"
//					"<td>"
//				//	"<input type=\"password\" name=\"pass\" size=\"24\" maxlength=\"64\">"
//					"</td>"
//					"</tr>\n"
//					"</table>\n"
//					"</td>\n"
//					"</tr>\n"
					"<tr>\n"
					"<td width=\"40\" valign=\"top\" bgcolor=\"#99CCCC\">&nbsp;</td>"
					"<td width=\"500\">";
	//eBayla fix notice
	//Beth 7/9/99
	*pStream  <<	"<p><font size=\"2\">To finalize your bid, you will need to submit your User ID and Password in "
					"the next step &#151; once you click on Review Bid."  
					"</font><p>";


	if (mpItem->GetQuantity() > 1)
	{
		*pStream  <<	"<input type=\"text\" name=\"quant\" size=\"18\" "
						"maxlength=\""
				  <<	EBAY_MAX_QUANTITY_SIZE
				  <<	"\"><br>"
				  <<	"<font size=\"3\"><strong>Quantity</strong> you are bidding for.</font>"
						"<br><br>\n";
	}


	// Beth update box size 7/22/99
	*pStream   <<	"<input type=\"text\" name=\"maxbid\" size=\"22\" maxlength=\"12\">"
					"<font size=\"2\"><i>"
					" Current minimum bid is ";
	// PH added 05/03/1999
	clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), mMinimumBid); // set below

	currencyWidget.EmitHTML(pStream);
//			   <<	mMinimumBid
	*pStream   <<	"</i></font>&nbsp;&nbsp;&nbsp;&nbsp;"
					"<input type=\"submit\" value=\"review bid\"><br>"

	// Beth 7/9/99 - start "Enter your maximum bid" section, 7/22 change wording again
					"<font size=\"3\"><strong>Enter your maximum bid</strong></font>"
					"<table>";

	//show amount of each item for dutch auction 
	if (mpItem->GetQuantity() > 1)
	{
		// Beth 7/9/99
		*pStream  <<	"<tr>"
						"<td valign=\"TOP\" width=\"10\"><font size=\"2\"> &#149 </font> </td>"
						"<td><font size=\"2\">This is the amount you are bidding <strong>for each item</strong>."
						"</font></td>"
						"</tr>";
	}

	// PH 04/26/99 changed to work for all currencies >>
	clsCurrency* cur = mpMarketPlace->GetCurrencies()->GetCurrency(mpItem->GetCurrencyId());
  
	// Beth 7/9/99 (changed down through End of "Your bid is a contract" section)
	*pStream  <<	"<tr>"
					"<td valign=\"TOP\"><font size=\"2\"> &#149 </font> </td>"
					"<td><font size=\"2\">Remember to type in <strong>numbers only</strong> and use a decimal symbol"
					" when necessary. <strong>Don't include a currency symbol ("
			  <<	cur->GetSymbol()
			  <<	") or thousand separator</strong>. For example: 1000000"
			  <<	"."
			  <<	"00"
					"</font></td>"
					"</tr>"
					"</table>";
	// End of "Enter your maximum bid" section

	*pStream  <<	"<br>";

	if (mpItem->GetQuantity() > 1)
	{
		// start "This is a Dutch auction!" section
		*pStream   <<	"<font size=\"3\"><strong>This is a Dutch Auction!</strong></font>"
						"<table>"
						"<tr>"
						"<td valign=\"TOP\" width=\"10\"><font size=\"2\"> &#149 </font> </td>"
						"<td><font size=\"2\">For a quick explanation of how this format works, check out "
						"<a href="
						"\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/basics/f-format.html#dutch"
						"\""
						">"
						"Dutch Auction guidelines"
						"</strong>.</font></td>"
						"</tr>"
						"<tr>"
						"<td valign=\"TOP\" width=\"10\"><font size=\"2\"> &#149 </font> </td>"
						"<td><font size=\"2\">If you've bid on this item before, "
						"remember that your new maximum bid total must be <strong>higher</strong> "
						"than your previous bid total. Your total is the quantity multiplied by "
						"the dollar amount per item you bid."
						"</font></td>"
						"</tr>"
						"</table>";
		// End of "This is a Dutch auction!" section
	}
	else
	{
		// Start of "Bid efficiently with Proxy Bidding..." section
		*pStream   <<	"<font size=\"3\"><strong>"
						"Bid efficiently with Proxy Bidding &#151; here's how it works:"
						"</strong></font>"
						"<table>"
						"<tr>"
						"<td valign=\"TOP\" width=\"10\"><font size=\"2\"> &#149 </font> </td>"
						"<td><font size=\"2\">Your maximum bid is kept "
						"<strong>secret</strong>, and <strong>it's not necessarily "
						"what you'll pay</strong>. eBay will bid on your behalf "
						"(which is called proxy bidding) by steadily increasing your "
						"bid by <strong>small increments until your maximum is reached"
						"</strong>.</font></td>"
						"<tr>"
						"<td valign=\"TOP\" width=\"10\"><font size=\"2\"> &#149 </font> </td>"
						"<td><font size=\"2\"><strong>Why?</strong> Because it means "
						"<strong>you don't have to monitor the auction</strong> as it "
						"unfolds. You also <strong>don't have to worry about being outbid "
						"at the very last minute</strong> unless someone bids over your "
						"maximum dollar amount. Want more details?  Check out an "
						"<a href="
						"\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/buyerguide/bidding-prxy.html"
						"\""
						">"
						"example of proxy bidding</a>.</font></td>"
						"</tr>"
						"<tr>"
						"<td valign=\"TOP\" width=\"10\"><font size=\"2\"> &#149 </font> </td>"
						"<td><font size=\"2\"><strong>Choose your maximum carefully</strong>, "
						"though, as you won't be able to reduce it later."
						"</font></td>"
						"</tr>"
						"</table>";
		// End of "Bid efficiently with Proxy Bidding..." section
	}

	*pStream  <<	"<br>";

	// Start "Your bid is a contract" section
	*pStream   <<	"<font size=\"3\"><strong>Your bid is a contract</strong></font>"
					"<table>"
					"<tr>"
					"<td valign=\"TOP\"><font size=\"2\"> &#149 </font> </td>"
					"<td><font size=\"2\">Only place a bid if you're serious about "
					"buying the item; if you're the winning bidder, you <strong>will"
					"</strong> enter into a legally binding contract to purchase the "
					"item from the seller."
					"</font></td>"
					"</tr>"
					"</table>";
//	switch (mpItem->GetCurrencyId())
//	{
//	case Currency_GBP:
//		*pStream << "British pounds ('&pound;')";
//		break;
//	case Currency_USD:
//	default:
//		*pStream << "a dollar sign ('$')";
//	}
//	*pStream << "or commas (',').";
	// <<

/* EAB
	*pStream <<		"<br>"
					"\n"
					"<b><font size=\"3\">"
					"Binding contract."
					"</font></b>"
					"<br>\n"
					"<font size=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
					"Placing a bid is a binding contract in\n"
					"many states. Do not bid unless you "
					"intend to buy this item at the amount of "
					"your bid.<p>\n"
					"</font>\n";
	// Rules about proxy bidding or dutch auction
	if (mpItem->GetQuantity() > 1)
	{
		*pStream  <<	"\n"
						"<b><font size=3>"
						"This is a "
						"<a href="
						"\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/buyerguide/bidding-type.html#dutch"
						"\""
						">"
						"Dutch auction"
						"</a>"
						"</font></b>"
						"<br>\n"
						"<font size=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
						"Please refer to the preceding link for the rules "
						"governing Dutch auctions\n"
						"<b>before bidding.</b>"
						"<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
						"If you have bid on this item before, note that your "
						"new total value must be greater than your previous one. "
						"Total value is determined by multiplying the quantity you "
						"are bidding for by the amount bid per item.</font> \n";
	}
	else 
	{
		*pStream  <<	"\n"
						"<b><font size=3>"
						"Proxy bidding for all bids"
						"</font></b>"
						"<br>\n"
						"<font size=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
						"Please bid the <strong>maximum amount</strong> "
						"you are willing to pay\n"
						"for this item. Your maximum amount will be kept "
						"<b>secret;</b> eBay will bid on your "
						"behalf "
						"as necessary by increasing your bid by the current "
						"bid "
						"increment up until your maximum is reached. This saves you the\n"
						"trouble of having to keep track of the auction as it\n"
						"proceeds and prevents you from being outbid at the "
						"last minute unless your spending limit is exceeded. "
						"(See an "
						"<a href="
						"\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/buyerguide/bidding-prxy.html"
						"\""
						">"
						"example of proxy bidding"
						"</a>"
						"). "
						"Also, in case of a tie for high bidder,\n"
						"<b>earlier</b> bids take precedence. "
						"And, keep in\n"
						"mind that you cannot reduce your maximum bid at a later\n"
						"date. Unless otherwise noted, bids are in U.S. dollars.\n"
						"<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
						"If you have bid on this item before, note that "
						"your new bid must be greater than your previous bid.</font> \n";
	}

	if (mpItem->GetPrivate())
	{
		*pStream  <<	"<p><font size=2><b>This is a private auction -- your address "
						"will not be revealed."
						"</b>"
						" Only the seller will know the high-bidder\'s "
						"address at the end of the auction.</font>";
	}
EAB */
	*pStream  <<	"</td></tr>"
					"</table>"
					"</form>";

	return true;
}
						