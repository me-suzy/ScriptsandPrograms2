/*	$Id: clseBayAppViewHolidayBuyerAlert.cpp,v 1.4.166.2.90.2 1999/08/05 20:42:26 nsacco Exp $	*/
//
//	File:	clseBayAppViewHolidayBuyerAlert.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppViewHolidayBuyerAlert.cpp
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//		Contains the methods used to display the dynamic gift
//		alert promo page with the link to the request page.
//
// Modifications:
//				- 10/22/98 mila		- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

static const char *ErrorMsgInvalidUserId =
"<h2>User ID is invalid</h2>"
"Sorry, the user ID is invalid. ";

static const char *ErrorMsgOmittedItem =
"<h2>Item Number is missing</h2>"
"Sorry, the item number is missing. "
"Please go back and enter an item number.";

static const char *ErrorMsgInvalidItem =
"<h2>Item Number is invalid</h2>"
"Sorry, the item number is invalid. Please go back and try again.";

static const char *ErrorMsgActiveItem =
"<h2>Auction is not over</h2>"
"Sorry, the auction on the item you specified is not yet over. "
"Please wait until the auction is over to request a Gift Alert for this item.";

//
// HolidayBuyerAlertShow
//
//	This routine emits a feature promo page with a button
//	that takes the user to the gift alert request page.
//	It's a seperate method so that it can be called 
//	independantly of ViewHolidayBuyerAlert. The latter emits a
//	<TITLE> and other goodies.
//
void clseBayApp::HolidayBuyerAlertShow(char *pUserId, clsItem *pItem)
{
	if (pUserId == NULL)
	{
		*mpStream	<<	ErrorMsgInvalidUserId;
		CleanUp();
		return;
	}

	if (pItem == NULL)
	{
		*mpStream	<<	ErrorMsgInvalidItem;
		CleanUp();
		return;
	}

	// Output banner across top.
	*mpStream <<	"\n"
					"<center>\n"
					"  <table border=\"0\" width=\"600\" cellpadding=\"0\" cellspacing=\"0\">\n"
					"    <tr>\n"
					"      <td align=\"center\" colspan=\"3\">"
			  <<	"<img src=\""
			  <<	gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetImagePath()
			  <<	"gift-title.gif"
			  <<	"\" alt=\"eBay\" width=\"509\" height=\"49\" border=\"0\" usemap=\"#titlemap\">\n"
					"      </td>\n"
					"    </tr>\n"
					"  </table>\n"
					"</center>\n";

	*mpStream <<	"\n"
					"<center>\n"
					"  <table border=\"0\" width=\"600\" cellpadding=\"0\" cellspacing=\"0\">\n";

	// Output lots and lots of text.
	*mpStream <<	"    <tr>\n"
					"      <td valign=\"top\">\n";

	*mpStream <<	"        <p>So you're a little late on your gift giving. "
					"Well, that's not a problem on eBay. Let your friends and family "
					"have the pleasure of opening your gift twice. How is that possible? "
					"Try our new gift alert feature, one of the coolest eBay features for the holidays.\n";

	*mpStream <<	"        <p>Here's how it works. If you win an auction in which the seller "
					"has at least ten feedback points, you can choose to send a gift alert to let "
					"someone know that you bought them a gift on eBay. Simply click on the gift alert "
					"link on the email that tells you that you won the auction, and enter your gift "
					"recipient's name and email. You can even specify on what date the person can start "
					"seeing the gift.  Your friends or family members will receive emails from you notifying "
					"them of their gifts. The email will have a URL that points gift recipients to a \"card\" "
					"that has the gift description and the picture if one is available. If the gift recipients "
					"try to open the card before the date you specified, they get a \"no peeking\" warning.\n";

	*mpStream <<	"        <p>What if your friends or family don't have email? No problem. Just send the gift "
					"alert to yourself, print out the \"card,\" and give it to your loved one.\n";

	*mpStream <<	"        <p>Here's an example of what your friends or family will see:\n";

	*mpStream <<	"<p><img src=\""
			  <<	mpMarketPlace->GetPicsPath()
			  <<	"gift-card.gif"
					"\" alt=\"Gift Card\">";

	*mpStream <<	"        <p>You will also be able to send a gift alert after the auction is completed by "
					"clicking the gift alert icon on the item description, from your <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/myebay/myebay.html\">My eBay</a> account, or from the list of auctions that are generated after "
			  <<	"you do a <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"search/items/search.html\">bidder search</a> on your User ID.\n";

	*mpStream <<	"	     <br>NOTE: The gift alert is only available for 30 days after the end of the auction, "
					"but we'll be sure to let your gift recipient know.\n";

	*mpStream <<	"        <p>Here are some helpful tips about buying gifts on eBay.\n"
					"          <ul>\n"
					"            <li>Check the seller's <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/forum/feedback.html\">feedback</a> and see how the person conducts business on eBay. "
					"Checking feedback can be very valuable in ensuring a successful transaction.\n"
					"            <li>Email the seller and ask questions about the item to make sure "
					"you know exactly what you are buying. Be specific and get specific answers.\n"
					"            <li>Ask the seller to send the gift through a mailing service that "
					"has tracking capability, like UPS or FedEx, and ask the seller to tell you the "
					"tracking number.\n"
					"            <li>Ask the seller to gift wrap the gift.\n"
					"            <li>You may want to use an escrow service. <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/buyerguide/after-tips.html\">Escrow</a> services are available for a small fee if the buyer "
					"would like to see the purchased item and accept it before payment is released. "
					"If the item is not accepted, the buyer must return it quickly and the escrow service "
					"returns the funds to the buyer.\n"
					"          </ul>\n";

	// WHEW!!!

	// Ouput list of links to other holiday promos.
	*mpStream <<	"      <td valign=\"top\" nowrap width=\"20\">\n"
					"      </td>\n"
					"      <td valign=\"top\" nowrap><font size=\"2\">\n"
					"        <hr>"
					"          <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"holiday-buyer-fun.html\">Holiday Fun</a>\n"
					"          <br><a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"holiday-buyer-spirit.html\">Spirit of eBay Community</a>\n"
					"          <br><a href=\""
			  <<	mpMarketPlace->GetCGIPath(PageViewBoard)
			  <<	"eBayISAPI.dll?ViewBoard&name=giving\">The Giving Board</a>\n"
					"          <br><a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"holiday-buyer-empl.html\">Holidays with eBay Employees</a>\n"
					"          <br><a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"holiday-buyer-skippy.html\">Holidays with Skippy</a>\n"
					"          <br><a href=\""
			  <<	mpMarketPlace->GetCGIPath(PageViewBoard)
			  <<	"eBayISAPI.dll?ViewBoard&name=holiday\">The Holiday Board</a>\n"
					"          <br><b>Gift Alert</b>\n"
					"          </font>\n"
					"        </td>\n"
					"      </tr>\n"
					"      <tr>\n"
					"      </tr>\n";

	// End of table.
	*mpStream <<	"  </table>\n"
					"</center>\n"
					"<p>\n";

	// If auction is over...
	if (pItem->GetEndTime() < time(0))
	{
		// Start a form.
		*mpStream <<	"<form method=\"post\" action=\""
				  <<	mpMarketPlace->GetCGIPath(PageRequestGiftAlert)
				  <<	"eBayISAPI.dll?RequestGiftAlert\">\n"
				  <<	"  <input type=hidden name=item value=\""
				  <<	pItem->GetId()
				  <<	"\">\n"
				  <<	"  <input type=hidden name=userid";
		
		if (!FIELD_OMITTED(pUserId))
		{
			*mpStream <<	" value=\""
					  <<	pUserId
					  <<	"\"";
		}
		
		*mpStream <<	">\n";

		// Output submit button to allow jumping to request page.
		*mpStream <<	"  <p>\n"
						"  <input type=\"submit\" value=\"request gift alert\">\n"
						"  </p>\n";

		// End form.
		*mpStream <<	"</form>\n";
	}

	return;
}

//
// ViewHolidayBuyerAlert
//
void clseBayApp::ViewHolidayBuyerAlert(CEBayISAPIExtension *pThis,
								  char *pItemNo,
								  char *pUserId)
{
	int			item;
	clsItem *	pItem;

	SetUp();

	// Title and map
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	": Happy Holidays"
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();

	// Item number is required!!
	if (FIELD_OMITTED(pItemNo))
	{
		*mpStream	<<	ErrorMsgOmittedItem;
		CleanUp();
		return;
	}

	// Check the item number
	item = atoi(pItemNo);
	if (item == 0)
	{
		*mpStream	<<	ErrorMsgInvalidItem;
		CleanUp();
		return;
	}

	// Check the item
	pItem = mpItems->GetItem(item);
	if (pItem == NULL)
	{
		*mpStream	<<	ErrorMsgInvalidItem;
		CleanUp();
		return;
	}	
	
	HolidayBuyerAlertShow(pUserId, pItem);

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

