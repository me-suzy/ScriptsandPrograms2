/*	$Id: clseBayAppRequestGiftAlert.cpp,v 1.4.166.2.90.1 1999/08/01 03:01:26 barry Exp $	*/
//
//	File:	clseBayAppRequestGiftAlert.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppRequestGiftAlert.cpp
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//		Contains the methods used to display the page
//		where a user requests a gift alert.
//
// Modifications:
//				- 10/22/98 mila		- Created
//				- 10/30/98 mila		- Changed gift open date select menus to
//									  default to today's date
//				- 11/25/98 mila		- Fixed dimensions of title image
//				- 12/22/98 mila		- Changed occasion value from 1 to 0, which
//									  effectively changes the occasion from
//									  holiday to generic; changes to #if'ed out
//									  code for occasion pulldown in preparation
//									  for additional occasions.
//				- 01/12/99 mila		- Uncommented and fixed code to let users
//									  select the gift occasion at the time they
//									  request a gift alert.
//				- 01/28/99 mila		- Enforced 32-char limit on entry of sender and
//									  recipient names; changed value of occasion menu
//									  option "Select Occasion" to -1.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsGiftOccasion.h"
#include "clsGiftOccasions.h"

static const char *pGiftAlertImageFile = "gift-title.gif";

static const char *pMonthArray[] =
{
	"Select Month",
	"January",
	"February",
	"March",
	"April",
	"May",
	"June",
	"July",
	"August",
	"September",
	"October",
	"November",
	"December"
};

static const int MaxDaysPerMonth = 31;
static const int MonthsPerYear = 12;

static const char *ErrorMsgInvalidUserId =
"<h2>User ID is invalid</h2>"
"Sorry, your User ID is missing. "
"Please go back and enter your User ID.";

static const char *ErrorMsgOmittedItem =
"<h2>Item Number is missing</h2>"
"Sorry, the item number is missing. "
"Please go back and enter an item number.";

static const char *ErrorMsgInvalidItem =
"<h2>Item Number is invalid</h2>"
"Sorry, the item number you specified is invalid. "
"Please go back and try again.";

static const char *ErrorMsgActiveItem =
"<h2>Auction is not over</h2>"
"Sorry, the auction on the item you specified is not yet over. "
"Please wait until the auction is over to request a Gift Alert for this item.";

//
// RequestGiftAlertShow
//
//	This routine emits a page that allows a user to
//	enter the information required to send a gift alert.
//	It's a seperate method so that it can be called 
//	independantly of ViewFeedback. The latter emits a
//	<TITLE> and other goodies.
//
void clseBayApp::RequestGiftAlertShow(char *pUserId, clsItem *pItem)
{
	// time/date stuff
	time_t		now;
	time_t		endAuction;
	time_t		eoaPlus30Days;

	int			day;
	int			nowDay;
	int			endAuctionDay;
	int			eoaPlus30DaysDay;

	int			month;
	int			nowMonth;
	int			endAuctionMonth;
	int			eoaPlus30DaysMonth;

	int			year;
	int			nowYear;
	int			endAuctionYear;
	int			eoaPlus30DaysYear;

	char		endAuctionDateString[24];
	char		eoaPlus30DaysDateString[24];

	struct tm *	pTmDate;

	int					occasion;
	int					numOccasions = 0;

	char *				pOccasionName = NULL;

	char *				pSafeUserId;

	clsGiftOccasion	*	pOccasion;
	clsGiftOccasions *	pGiftOccasions;

	GiftOccasionVector	vOccasions;

	// Let's do some calculations and error checking first...

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

	// Get EOA day, month, and year.
	now = time(0);
	pTmDate = localtime(&now);
	nowDay = pTmDate->tm_mday;
	nowMonth = pTmDate->tm_mon + 1;
	nowYear = pTmDate->tm_year + 1900;

	// Get EOA day, month, and year.
	endAuction = pItem->GetEndTime();
	pTmDate = localtime(&endAuction);
	endAuctionDay = pTmDate->tm_mday;
	endAuctionMonth = pTmDate->tm_mon + 1;
	endAuctionYear = pTmDate->tm_year + 1900;

	// If today is before EOA, then we have an error!
	if (now < endAuction)
	{
		*mpStream	<<	ErrorMsgActiveItem;
		CleanUp();
		return;
	}

	// Format into date string.
	strftime(endAuctionDateString, sizeof(endAuctionDateString),
			 "%B %d, %Y", pTmDate);

	// Get day, month, and year of EOA + 30 days.
	eoaPlus30Days = endAuction + (60 * 60 * 24 * 30);
	pTmDate = localtime(&eoaPlus30Days);
	eoaPlus30DaysDay = pTmDate->tm_mday;
	eoaPlus30DaysMonth = pTmDate->tm_mon + 1;
	eoaPlus30DaysYear = pTmDate->tm_year + 1900;

	// Format into date string.
	strftime(eoaPlus30DaysDateString, sizeof(endAuctionDateString),
			 "%B %d, %Y", pTmDate);

	// Now we can emit the page...

	// display gift alert banner across top
	*mpStream <<	"\n"
			  <<	"<p><table width=\"100%\"><tr><td align=\"center\">"
			  <<	"<img width=\"468\" height=\"60\" src=\""
			  <<	gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetImagePath()
//			  <<	"file:C:\\E108_gift_alert\\pics\\"
			  <<	pGiftAlertImageFile
			  <<	"\" alt=\"Gift Alert!\">"
			  <<	"</td></tr></table><p>"
			  <<	"\n";

	*mpStream <<	"<form method=\"post\" action=\""
			  <<	mpMarketPlace->GetCGIPath(PageSendGiftAlert)
			  <<	"eBayISAPI.dll?SendGiftAlert\">"
					"\n";

	// Begin 2-column table.
	*mpStream	<<	"<table border=\"0\" cellpadding=\"6\" cellspacing=\"0\" width=\"590\">"
					"\n"
				<<	"  <tr>"
					"\n";

	// Output column 1 for user input.
	*mpStream	<<	"    <td valign=\"top\">"
					"\n";

	// User ID
	*mpStream	<<	"      <input type=\"text\" name=\"userid\" size=\"40\"";

	pSafeUserId = clsUtilities::MakeSafeString(pUserId);

	if (!FIELD_OMITTED(pUserId) && !pItem->GetPrivate())
	{
		*mpStream	<<	" value=\""
					<<	pSafeUserId
					<<	"\"";
	}

	delete [] pSafeUserId;

	*mpStream	<<	"><br>"
					"\n"
					"        <font size=\"2\">Your registered <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/myinfo/userid.html\">User ID</a>"
					"\n"
					"        </font>"
					"\n";

	// Password
	*mpStream <<	"      <p>"
					"\n"
					"        <input type=\"password\" name=\"password\" size=\"40\"><br>"
					"        <font size=\"2\">Your <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/buyandsell/reqpass.html\">password</a>"
					"\n"
					"        </font>"
					"\n"
					"      </p>"
					"\n";

	// Sender name
	*mpStream <<	"      <p><input type=\"text\" name=\"fromname\" size=\"40\" maxlength=\"32\"><br>"
					"\n"
					"        <font size=\"2\">Your name</font>"
					"\n"
					"      </p>"
					"\n";

	// Item number
	*mpStream <<	"      <p><input type=\"text\" name=\"item\" size=\"40\""
					" value=\""
			  <<	pItem->GetId()
			  <<	"\">"
					"<br>"
					"\n"
					"        <font size=\"2\">Item number</font>"
					"\n"
					"      </p>"
					"\n";

	// Recipient name
	*mpStream <<	"      <p><input type=\"text\" name=\"toname\" size=\"40\" maxlength=\"32\"><br>"
					"\n"
					"        <font size=\"2\">Name of gift recipient</font>"
					"\n"
					"      </p>"
					"\n";

	// Destination e-mail address
	*mpStream <<	"      <p><input type=\"text\" name=\"destemail\" size=\"40\"><br>"
					"\n"
					"        <font size=\"2\">E-mail address of gift recipient</font>"
					"\n"
					"      </p>"
					"\n";

	// End column 1.
	*mpStream	<<	"    </td>"
					"\n";

#if 0	// sellers didn't like this at all!!!!
	// Output column 2 for sender checklist message box.
	*mpStream	<<	"    <td valign=\"top\" align=\"right\">"
					"\n"
					"      <table border=\"1\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">"
					"\n"
					"        <tr>"
					"\n"
					"          <td width=\"100%\" bgcolor=\"#99cccc\" align=\"center\">"
					"\n"
					"            <font face=\"arial,helvetica\" size=\"2\">"
					"\n"
					"              <strong>Helpful tips about buying gifts on eBay</strong>"
					"\n"
					"            </font>"
					"\n"
					"          </td>"
					"\n"
					"        </tr>"
					"\n"
					"        <tr>"
					"\n"
					"          <td width=\"100%\" bgcolor=\"#ffffcc\">"
					"\n"
					"            <font size=\"2\">"
					"\n"
					"Check the seller's feedback and see how the person conducts business on "
					"eBay. Checking feedback can be very valuable in ensuring a successful "
					"transaction.<p>"
					"Email the seller and ask questions about the item to make sure you know "
					"exactly what you are buying. Be specific and get specific answers.<p>"
					"Ask the seller to send the gift through a mailing service that has tracking "
					"capability, like UPS or FedEx, and ask the seller to tell you the tracking "
					"number.<p>"
					"Ask the seller to gift wrap the gift.<p>"
					"You may want to use an escrow service. Escrow services are available for a "
					"small fee if the buyer would like to see the purchased item and accept it "
					"before payment is released. If the item is not accepted, the buyer must "
					"return it quickly and the escrow service returns the funds to the buyer."
					"\n"
					"            </font>"
					"\n"
					"          </td>"
					"\n"
					"        </tr>"
					"\n"
					"      </table>"
					"\n"
					"    </td>"
					"\n";
#endif

	*mpStream	<<	"  </tr>"
					"\n"
					"</table>"
					"\n";

	// Start new table to keep alignment along left margin of page.
	*mpStream <<	"<table border=\"0\" cellpadding=\"6\" cellspacing=\"0\" width=\"590\">"
					"\n"
			  <<	"  <tr>"
					"\n"
			  <<	"    <td>"
					"\n";

	// Requestor's personal message.
	*mpStream <<	"      <p><input type=\"text\" name=\"message\" size=\"80\" maxlength=\"80\"><br>"
			  <<	"\n";

	*mpStream <<	"        <font size=\"2\">Your personal message to gift recipient (max. 80 characters)</font>"
					"\n";

	// Occasion

	// Label for the occasions menu
	*mpStream	<<	"<p>"
					"  <font size=\"2\">What's the occasion?</font>"
					"\n"
					"<br>"
					"\n";

	// Select occasion from list of occasions
	*mpStream	<<	"\n"
					"  <select name=\"occasion\">"
					"\n"
					"    <option selected value=\"-1\">Select Occasion</option>"
					"\n";

	// Get vector of active occasions
	pGiftOccasions = new clsGiftOccasions;
	pGiftOccasions->GetActiveGiftOccasions(mpMarketPlace->GetId(), &vOccasions);
	numOccasions = vOccasions.size();

	// Create pulldown options from vector elements
	for (occasion = 0; occasion < numOccasions; ++occasion)
	{
		*mpStream	<<	"  <option ";
		
		pOccasion = vOccasions[occasion];
		if (pOccasion != NULL)
			pOccasionName = pOccasion->GetName();

		*mpStream	<<	"value=\""
					<<	occasion
					<<	"\">";

		if (pOccasionName != NULL)
			*mpStream	<<	pOccasionName;
		else
			*mpStream	<<	"Error";

		*mpStream	<<	"</option>"
						"\n";
	}

	// End occasion selection list
	*mpStream	<<	"  </select>"
					"\n";

#if 0	// force occasion to be generic
	*mpStream	<<	"      <p><input type=\"hidden\" name=\"occasion\" value=\"0\">"
					"\n";
#endif

	// Open date (month, day, and year)...

	*mpStream	<<	"<p>"
					"  <font size=\"2\">What's the earliest date the gift can be opened?<br>"
					"    <i>(NOTE: must be between "
				<<	endAuctionDateString
				<<	" and "
				<<	eoaPlus30DaysDateString
				<<	")"
				<<	"    </i>"
					"</font>"
					"\n"
					"<br>"
					"\n";

	// Select open month from list of months
	*mpStream	<<	"\n"
					"  <select name=\"month\">"
					"\n";

	// Start with the current month...
	*mpStream	<<	"  <option selected value=\""
				<<	endAuctionMonth
				<<	"\">"
				<<	pMonthArray[endAuctionMonth]
				<<	"</option>"
					"\n";

	// And append all months up to and including the month in which 
	// (EOA + 30 days) occurs.
	if (eoaPlus30DaysMonth != endAuctionMonth)
	{
		// We can't just do one more because February has < 30 days in it,
		// so we have to be able to handle the case where EOA is January 31,
		// which means that (EOA + 30 days) is in early March...
		for (month = (endAuctionMonth % 12) + 1; 
			 month <= eoaPlus30DaysMonth;
			 ++month)
		{
			if (month == nowMonth)
			{
				*mpStream	<<	"  <option selected value=\""
							<<	month
							<<	"\">"
							<<	pMonthArray[month]
							<<	"</option>"
								"\n";
			}
			else
			{
				*mpStream	<<	"  <option value=\""
							<<	month
							<<	"\">"
							<<	pMonthArray[month]
							<<	"</option>"
								"\n";
			}
		}
	}

	// End month selection list
	*mpStream	<<	"  </select>"
					"\n"
					"&nbsp;&nbsp;&nbsp;";

	// Select open day from list of days
	*mpStream	<<	"  <select name=\"day\">"
					"\n";

	for (day = 1; day <= MaxDaysPerMonth; ++day)
	{
		if (day == nowDay)
		{
			*mpStream	<<	"  <option selected value=\""
						<<	day
						<<	"\">"
						<<	day
						<<	"</option>"
							"\n";
		}
		else
		{
			*mpStream	<<	"  <option value=\""
						<<	day
						<<	"\">"
						<<	day
						<<	"</option>"
							"\n";
		}
	}

	// End day selection list
	*mpStream	<<	"  </select>"
					"\n"
					"&nbsp;&nbsp;&nbsp;";

	// Select open year from list of this/these years
	// (current year and maybe next year)
	*mpStream	<<	"  <select name=\"year\">"
					"\n";

	for (year = endAuctionYear; year <= eoaPlus30DaysYear; ++year)
	{
		*mpStream	<<	"  <option ";
		
		if (year == nowYear)
		{
			*mpStream	<<	"  <option selected value=\""
						<<	year
						<<	"\">"
						<<	year
						<<	"</option>"
							"\n";
		}
		else
		{
			*mpStream	<<	"  <option value=\""
						<<	year
						<<	"\">"
						<<	year
						<<	"</option>"
							"\n";
		}
	}

	// End year selection list
	*mpStream	<<	"  </select>"
					"\n"
					"<br>"
					"\n";

	// End table
	*mpStream <<	"    </td>"
					"\n"
					"  </tr>"
					"\n"
					"</table>"
					"\n";

	// Output warning message.
	*mpStream	<<	"<p>"
					"\n"
					"  <strong>WARNING: Once sent, gift alerts cannot be retracted or cancelled.</strong>"
					"\n"
					"</p>"
					"\n";

	// Output submit button.
	*mpStream	<<	"<p>"
					"\n"
					"  <input type=\"submit\" value=\"submit\">"
					"\n"
					"</p>"
					"\n";

	// Output clear button.
	*mpStream	<<	"<p>"
					"\n"
					"  <input type=\"reset\" value=\"clear form\">"
					"\n"
					"</p>"
					"\n";

	// Output separator.
	*mpStream	<<	"</form>";

	return;
}

//
// RequestGiftAlert
//
void clseBayApp::RequestGiftAlert(CEBayISAPIExtension *pThis,
								  char *pItemNo,
								  char *pUserId)
{
	int			item;
	clsItem *	pItem;

	SetUp();

	// Title
	*mpStream <<	"<head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Request a Gift Alert"
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
	
	RequestGiftAlertShow(pUserId, pItem);

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

