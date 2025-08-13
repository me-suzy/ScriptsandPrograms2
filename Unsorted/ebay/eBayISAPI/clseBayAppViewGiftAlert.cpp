/*	$Id: clseBayAppViewGiftAlert.cpp,v 1.4.166.2.90.2 1999/08/05 20:42:25 nsacco Exp $	*/
//
//	File:	clseBayAppViewGiftAlert.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppViewGiftAlert.cpp
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//		Contains the methods used to display the dynamic gift
//		alert promo page with the link to the request page.
//
// Modifications:
//				- 11/20/98 mila		- Copied from clseBayAppViewHolidayBuyerAlert.cpp,
//									  which is now obsolete; changed page to give
//									  brief overview with links to detailed info
//									  and gift alert request pages.
//				- 11/25/98 mila		- Fixed dimensions of title image
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

static const char *ErrorMsgInvalidUserId =
"<h2>User ID is invalid</h2>"
"Sorry, the user ID is invalid. Please go back and try again.";

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
// GiftAlertShow
//
//	This routine emits a feature promo page with a button
//	that takes the user to the gift alert request page.
//	It's a seperate method so that it can be called 
//	independantly of ViewGiftAlert. The latter emits a
//	<TITLE> and other goodies.
//
void clseBayApp::GiftAlertShow(char *pUserId, clsItem *pItem)
{
	clsFeedback *	pFeedback = NULL;
	clsUser *		pSeller = NULL;

	char *			pSafeUserId;

	const int		minFeedbackScore = 10;

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
			  <<	"\" alt=\"eBay\" width=\"468\" height=\"60\" border=\"0\" usemap=\"#titlemap\">\n"
					"      </td>\n"
					"    </tr>\n"
					"  </table>\n"
					"</center>\n";

	*mpStream <<	"\n"
					"<p><center>\n"
					"  <table border=\"0\" width=\"600\" cellpadding=\"0\" cellspacing=\"0\">\n";

	// Output lots and lots of text.
	*mpStream <<	"    <tr>\n"
					"      <td valign=\"top\">\n";

	*mpStream <<	"        <p>If you are the winning bidder, and you want to "
					"give the item as a gift, you can send a "
					"<a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/buyerguide/holiday-buyer-alert.html\">"
					"gift alert"
					"</a>"
					".\n";

	*mpStream <<	"The gift alert (see below) notifies the gift recipient that "
					"you bought him/her a gift on eBay. If you're a little late on "
					"your gift giving, this cool feature can save the day!\n";

	pSeller = mpUsers->GetUser(pItem->GetSeller());
	if (pSeller == NULL)
	{
		CleanUp();
		return;
	}

	pFeedback = pSeller->GetFeedback();
	if (pFeedback == NULL)
	{
		CleanUp();
		return;
	}

	if (pFeedback->GetScore() < minFeedbackScore)
	{
		*mpStream <<	"        <p><b>Unfortunately, you cannot send a gift alert for "
						"this particular auction because the seller does not have a "
						"feedback rating of at least "
				  <<	minFeedbackScore
				  <<	".</b>\n";

		*mpStream <<	"        <p>To read more about gift alert, click "
						"<a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/buyerguide/holiday-buyer-alert.html\">"
						"here"
						"</a>"
						".\n";

		*mpStream <<	"        <p><center>\n"
						"<img border = \"0\" src=\""
				  <<	mpMarketPlace->GetImagePath()
				  <<	"gift-card.gif"
						"\" alt=\"Gift Card\">"
						"</center>\n";
	}
	else
	{
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
			
			pSafeUserId = clsUtilities::MakeSafeString(pUserId);

			if (!FIELD_OMITTED(pUserId) && !pItem->GetPrivate())
			{
				*mpStream <<	" value=\""
						  <<	pSafeUserId
						  <<	"\"";
			}

			delete [] pSafeUserId;
			
			*mpStream <<	"        <p><b>Click on the button below to request a gift alert:</b>";

			// Output submit button to allow jumping to request page.
			*mpStream <<	"  <p>\n"
							"  <input type=\"submit\" value=\"request gift alert\">\n"
							"  </p>\n";

			// End form.
			*mpStream <<	"</form>\n";
		}
		else
		{
			*mpStream <<	"        <p><b>Sorry, you cannot send a gift alert for "
							"an ongoing auction. If you would like to send a gift "
							"alert for this auction, please come back after the "
							"auction has ended.</b>\n";
		}

		*mpStream <<	"	     <p>NOTE: The gift alert is <b>only available for 30 days after "
						"the end of the auction</b>, "
						"but we'll be sure to let your gift recipient know.\n";

		*mpStream <<	"        <p>To read more about gift alert, click "
						"<a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/buyerguide/holiday-buyer-alert.html\">"
						"here"
						"</a>"
						".\n";

		*mpStream <<	"        <p><center>\n"
						"          <a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageRequestGiftAlert)
				  <<	"eBayISAPI.dll?RequestGiftAlert&item="
				  <<	pItem->GetId();

		if (!FIELD_OMITTED(pUserId) && !pItem->GetPrivate())
		{
			*mpStream <<	"&userid="
					  <<	pUserId;
		}

		*mpStream <<	"\">"
						"<img border = \"0\" src=\""
				  <<	mpMarketPlace->GetImagePath()
				  <<	"gift-card.gif"
						"\" alt=\"Gift Card\">"
						"</a>"
						"</center>\n";
	}

	// End of table.
	*mpStream <<	"      </td/>\n"
					"    </tr>\n"
					"  </table>\n"
					"</center>\n"
					"<p>\n";

	delete pSeller;

	return;
}

//
// ViewGiftAlert
//
void clseBayApp::ViewGiftAlert(CEBayISAPIExtension *pThis,
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
	
	GiftAlertShow(pUserId, pItem);

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

