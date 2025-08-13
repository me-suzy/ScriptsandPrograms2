/*	$Id: clseBayAppItem.cpp,v 1.19.2.15.34.3 1999/08/05 20:42:15 nsacco Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 05/01/97 michael	- Created
//				- 07/13/97 tini		- changed Featured to add SuperFeatured; 
//					still need to add Category Featured html and 
//				- 09/12/97 alex		- changed layout; added time left display
//				- 12/28/97 mkw		- *** NOTE ***
//									  HACK amount field to max dollar size + 1 to
//									  allow amounts > 9,999.99. Change back when
//									  we change #define
//									  *** NOTE ***
//				- 03/12/99	kaz		- Moved one GetAndCheckItem() version here from
//									  clseBayAppBid.cpp.  Then combined so one calls
//									  the other to simplify upkeep
//				- 03/16/99	kaz		- AddToItem() now checks time zone
//				- 03/19/99	kaz		- Modified GetAndCheckItem() text again.
//				- 06/23/99  jen     - Revert to E117 (original UI look) with New IA 
//									  header and footer
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "vector.h"
#include "clsUserIdWidget.h"
#include "clseBayItemDetailWidget.h"
#include "clsBidBoxWidget.h"

#include "clseBayTimeWidget.h"

static const char eBayBlockedItemAppealEmailAddress[] = "itemapl@ebay.com";

#define CHECKED(x)	(!strcmp(x,"on"))
//
// Common routine to get the item (if we can), and report
// an error if we can't...
//
// kaz: 3/12/99: This version was moved here from clseBayAppBid.cpp
// so we have both overloaded versions in the same file
bool clseBayApp::GetAndCheckItem(int item, char *pRowNo, time_t delta)
{
	if (item != 0)
		mpItem	= mpItems->GetItem(item, true, pRowNo, delta);

	// If we did't get the item, then put out a 
	// nice error message.

	if (!mpItem || item == 0)
	{
		*mpStream <<	"<HTML>"
						"<HEAD>"
						"<TITLE>"
						// nsacco 07/19/99
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" "
						"\'"
				  <<	item
				  <<	"\'"
				  <<	" - Invalid Item"
						"</TITLE>"
						"</HEAD>";

		*mpStream <<	mpMarketPlace->GetHeader();

		// kaz: 3/12/99: modify the error message per request from support
		// to handle the auctions being ended for legal reasons
		// kaz: 3/19/99: modified text again
		*mpStream <<	"<P>"
						"The item you requested <B>("
				  <<	item
				  <<	")</B> "
						"is invalid or no longer in our database. Please check the number and try again. "
						"If this message persists, the item has expired "
						"and is no longer available."
						"</P>";

		*mpStream <<	mpMarketPlace->GetFooter();

		*mpStream <<	flush;
		return false;
	}
	return true;
}

// kaz: 3/12/99: This version now calls the other overloaded version
bool clseBayApp::GetAndCheckItem(char *pItemNo, char *pRowNo, time_t delta)
{
	int		item = 0;
	
	if (pItemNo)
		item = atoi(pItemNo);
	
	return(GetAndCheckItem(item,pRowNo,delta));
}

//
// Does whatever cleanup needs to be done on descriptions
//
char *clseBayApp::CleanUpDescription(char *pDescription)
{
	char		*pIt;
	char		*pItOut;
	int			quoteCount;
	int			newSize;
	char		*pNewDescription;

	// First, count the evil double quotes
	quoteCount	= 0;

	for (pIt	= pDescription;
		 *pIt	!= '\0';
		 pIt++)
	{
		if ((*pIt == '\"'))
			quoteCount++;
	}

	// The new size is the origional size + 6
	// tims the quote count. We transform '"'
	// to &quot;
	newSize	= strlen(pDescription) +
			  6 * quoteCount +
			  1;

	pNewDescription	= new char[newSize];

	// Now, go through and replace those evil quotes
	for (pIt	= pDescription,
		 pItOut	= pNewDescription;
		 *pIt	!= '\0';
		 pIt++)
	{
		if (*pIt != '\"')
		{
			*pItOut	= *pIt;
			pItOut++;
			continue;
		}

		memcpy(pItOut, "&quot;", 6);
		pItOut	=	pItOut + 6;
	}

	*pItOut	= '\0';

	// all done. We can't delete the origional string
	// since it belongs to ISAPI (probably)
	return	pNewDescription;
}

//
// Does whatever cleanup needs to be done on titles
//
// ** YES, this was just copied from the description code.
//
// petra 06/17/99 remove stripping of special chars
char *clseBayApp::CleanUpTitle(char *pDescription)
{
	char		*pIt;
	char		*pItOut;
	int			sizeCount;
	int			newSize;
// petra	unsigned int iso_val;
	char		*pNewDescription;

	// First, count the evil special characters
	sizeCount	= 0;

	for (pIt	= pDescription;
		 *pIt	!= '\0';
		 pIt++)
	{
		
		// ISO characters start at 160
// petra		if ((unsigned char) *pIt >= 160)
//		{
//			sizeCount += 5;
//			continue;
// petra		}

		switch (*pIt)
		{
		case '\"': 
			sizeCount += 5; 
			break;
		case '<': case '>': 
			sizeCount += 3; 
			break;
		case '&': case '%': case '\'':
			sizeCount += 4; 
			break;
		}
	}

	// The new size is the origional size + sizeCount
	newSize	= strlen(pDescription) + sizeCount + 1;

	pNewDescription	= new char[newSize];

	// Now, go through and replace those characters
	for (pIt	= pDescription,
		 pItOut	= pNewDescription;
		 *pIt	!= '\0';
		 pIt++)
	{
// petra		if ((unsigned char) *pIt >= 160)
//		{
//			iso_val = (unsigned char) *pIt;
//			sprintf(pItOut, "&#%d;", iso_val);
//			pItOut += 6;
//			continue;
// petra		}

		switch (*pIt)
		{
		case '\"':
			memcpy(pItOut, "&quot;", 6);
			pItOut += 6;
			continue;
		case '<':
			memcpy(pItOut, "&lt;", 4);
			pItOut += 4;
			continue;
		case '>':
			memcpy(pItOut, "&gt;", 4);
			pItOut += 4;
			continue;
		case '&':
			memcpy(pItOut, "&amp;", 5);
			pItOut += 5;
			continue;
		case '%':
			memcpy(pItOut, "&#37;", 5);
			pItOut += 5;
			continue;
		case '\'':
			memcpy(pItOut, "&#39;", 5);
			pItOut += 5;
			continue;
		default:
			*pItOut = *pIt;
			++pItOut;
			continue;
		}
	}

	*pItOut	= '\0';

	// all done. We can't delete the origional string
	// since it belongs to ISAPI (probably)
	return	pNewDescription;
}

//
// UnstripHTML
//
// Undoes what CleanUpTitle does
//
// petra 06/17/99 remove stripping of special chars
char *clseBayApp::UnstripHTML(char *pString)
{
	char *pIt;
	char *pItLast;
	char *pItOut;
	int  newSize;
// petra	unsigned int  iso_val;
	char *pNewString;

	// If they're all ISO characters, this could be up
	// to six times as long.
	newSize = strlen(pString) * 6 + 1;

	pNewString = new char[newSize];

	pIt		= pString;
	pItLast	= pString + strlen(pString) - 1;
	pItOut	= pNewString;

	do
	{
		if (pIt > pItLast)
			break;

		// ISO characters start at 160
// petra		if ((unsigned char) *pIt >= 160)
//		{
//			iso_val = (unsigned char) *pIt;
//			sprintf(pItOut, "&#%d;", iso_val);
//			pItOut += 6;
//			pIt++;
//			continue;
// petra		}

		if (((pItLast - pIt) < 4) || (*pIt != '&'))
		{
			*pItOut = *pIt;
			pIt++;
			pItOut++;
			continue;
		}

		if (memcmp(pIt, "&lt;", 4) == 0)
		{
			*pItOut = '<';
			pIt += 4;
			++pItOut;
			continue;
		}

		if (memcmp(pIt, "&gt;", 4) == 0)
		{
			*pItOut = '>';
			pIt += 4;
			++pItOut;
			continue;
		}

		if ((pItLast - pIt) < 5)
		{
			*pItOut = *pIt;
			pIt++;
			pItOut++;
			continue;
			// Bah!
			// If this gets here, we got a &
			// even though it didn't match
			// any of our escape sequences!
			// This should never ever ever happen, but I've been
			// told asserts are also very bad.
			// assert(0);
#ifdef _MSC_VER
			LogEvent("Impossible event 5 in clseBayApp::UnstripHTML");
#endif
			break;
		}

		if (memcmp(pIt, "&#37;", 5) == 0)
		{
			*pItOut = '%';
			pIt += 5;
			++pItOut;
			continue;
		}

		if (memcmp(pIt, "&#39;", 5) == 0)
		{
			*pItOut = '\'';
			pIt += 5;
			++pItOut;
			continue;
		}

		if (memcmp(pIt, "&amp;", 5) == 0)
		{
			*pItOut = '&';
			pIt += 5;
			++pItOut;
			continue;
		}

		if ((pItLast - pIt) < 6)
		{
			*pItOut = *pIt;
			pIt++;
			pItOut++;
			continue;
			// If this gets here, we got a &
			// even though it didn't match
			// any of our escape sequences!
#ifdef _MSC_VER
			LogEvent("Impossible event 6 in clseBayApp::UnstripHTML");
#endif
			break;
		}

		if (memcmp(pIt, "&quot;", 6) == 0)
		{
			*pItOut = '\"';
			pIt += 6;
			++pItOut;
			continue;
		}

		*pItOut = *pIt;
		pIt++;
		pItOut++;
		continue;

	} while (1 == 1);

	// Copy the last bit of the input (plus the trailng null)
	pItOut[0]='\0';
//	strcat(pNewString, pIt);

	// all done. We can't delete the origional string
	// since it belongs to ISAPI (probably)
	return	pNewString;

/*	// Commented this buggy code

	// Copy the last bit of the input (plus the trailng null)
	strcat(pItOut, pIt);

	// all done. We can't delete the origional string
	// since it belongs to ISAPI (probably)
	return	pNewString;

*/
}

//
// HTMLQuoteToQuote
//
// Does the opposite of what the Cleanup routines do --
// this routine converts &quot; to " in a string.
//
char *clseBayApp::ChangeHTMLQuoteToQuote(char *pString)
{
	char		*pIt;
	char		*pItLast;
	char		*pItOut;
	int			newSize;
	char		*pNewString;

	// We'll just make a new buffer the same size
	// as the old one. We're making the string SHORTER,
	// not longer!
	newSize	= strlen(pString) +
			  1;

	pNewString	= new char[newSize];
	memset(pNewString, 0x00, newSize);

	pIt		= pString;
	pItLast	= pString + strlen(pString);
	pItOut	= pNewString;

	do
	{
		if ((pItLast - pIt) < 6)
			break;

		if (memcmp(pIt, "&quot;", 6) != 0)
		{
			*pItOut	= *pIt;
			pIt++;
			pItOut++;
			continue;
		}

		*pItOut	=	'\"';
		pIt	= pIt + 6;
		pItOut++;
	} while (1==1);

	// Copy the last bit of the input (plus the trailng null)
	strcat(pItOut, pIt);

	// all done. We can't delete the origional string
	// since it belongs to ISAPI (probably)
	return	pNewString;
}





void clseBayApp::Run(CEBayISAPIExtension *pServer, char *pItemNo, char *pRowNo, time_t delta,
						   CHttpServerContext* pCtxt)
{
	// Time fields
	time_t	endTime;
// petra	struct tm *timeAsTm;
// petra	char	titleEndTime[96];

// petra	clseBayTimeWidget	endTimeWidget;
// petra	TimeZoneEnum		timeZone;

	// Used to set the page's expiration to now + 5 minutes
	int			rc;
	time_t		nowTime;
	time_t		expirationTime;
	struct tm	*pExpirationTimeAsTM;
	char		expiresHeader[128];
	char		*cleanTitle;

	// If we happen to bid
	double	bidIncrement;
	double	minimumBid;

	// Item details
	clseBayItemDetailWidget *idw;

	// for possible redirect for adult signin
	unsigned long lLength;
	char newURL[255];

	//int		URLConfuser;

	// Setup
	SetUp();
	// Dynamic Cobrand


	// Let's try and get the item
	if (!GetAndCheckItem(pItemNo, pRowNo, delta))
	{
		CleanUp();
		return;
	}

	clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), 0); // set below

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// end time
	endTime		= mpItem->GetEndTime();
// petra	timeAsTm	= localtime(&endTime);

	//samuel, 4/6/99
// petra	timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra	endTimeWidget.SetTime(endTime);
// petra	endTimeWidget.SetTimeZone(timeZone);
	//end

// petra	if (timeAsTm)
// petra	{
// petra		if (timeAsTm->tm_isdst)
// petra		{
// petra			strftime(titleEndTime, sizeof(titleEndTime),
// petra					 "%m/%d/%y %H:%M:%S PDT",
// petra					 timeAsTm);
// petra		}
// petra		else
// petra		{
// petra			strftime(titleEndTime, sizeof(titleEndTime),
// petra					 "%m/%d/%y %H:%M:%S PST",
// petra					 timeAsTm);
// petra		}
// petra	}


	// Headers
	*mpStream <<	"<HTML>"
					"<HEAD>";


	// Check to see if the item is in an adult category and user isn't an adult
	if ((mpItem->IsAdult()) && (!HasAdultCookie()))
	{

		// We'll need a page title here
		*mpStream <<	"<TITLE>"
						// nsacco 07/19/99
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" Adult Verification Required"
				  <<	"</TITLE>"
						"</HEAD>";

		// uncommented on 07/20/99 by nsacco
		*mpStream <<	mpMarketPlace->GetHeader()
				  <<	flush;

		// calculate URL of adult login page
		strcpy(newURL, mpMarketPlace->GetCGIPath(PageAdultLoginShow));
		strcat(newURL, "eBayISAPI.dll?AdultLoginShow&t=1");

		// Just in case the redirect doesn't work, tell user where to go
		*mpStream << "<p>Click <b>refresh</b> or <b>reload</b> button on your browser now.";

		// uncommented on 07/20/99 by nsacco
		*mpStream << mpMarketPlace->GetFooter();

		CleanUp();

		// redirect to adult sign-in page
		pServer->EbayRedirect(pCtxt, newURL);
		lLength = strlen(newURL);
		// pCtxt->ServerSupportFunction(HSE_REQ_SEND_URL_REDIRECT_RESP, newURL, &lLength, NULL);
		return;
	}

	// Set the page to expire 5 minutes from how
	nowTime				= time(0);
	expirationTime		= nowTime + (60*5);

	pExpirationTimeAsTM	= gmtime(&expirationTime);

	if (pExpirationTimeAsTM)
	{
		// Make it the evil RFC1123 format.
		rc = strftime(expiresHeader,
			 		  sizeof(expiresHeader),
					  "%a, %d %b %Y, %H:%M:%S GMT",
					  pExpirationTimeAsTM);

		if (rc != 0)
		{
			*mpStream <<	"<meta http-equiv=\"Expires\" "
							"content=\""
					  <<	expiresHeader
					  <<	"\">";
		}
	}

	
	// We'll need a page title here
	*mpStream <<	"<TITLE>"
					// nsacco 07/19/99
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" item "
			  <<	pItemNo
			  <<	" (Ends ";

	clseBayTimeWidget endTimeWidget(mpMarketPlace,					// petra 
									EBAY_TIMEWIDGET_MEDIUM_DATE,	// petra
									EBAY_TIMEWIDGET_LONG_TIME,		// petra
									endTime);						// petra
	//samuel au, 4/6/99
	endTimeWidget.EmitHTML(mpStream);

	*mpStream <<	") - "
			  <<	mpItem->GetTitle()
			  <<	"</TITLE>"
					"</HEAD>";

	*mpStream <<	mpMarketPlace->GetHeader(false);	// false means no announcements
//	*mpStream <<	"<br>";

	// Use clseBayItemDetailWidget to show auction properties
	idw = new clseBayItemDetailWidget(mpMarketPlace);
	idw->SetItem(mpItem);
	idw->SetShowTitleBar(false);
	idw->SetShowDescription(true);
	idw->SetColor("#99CCCC");
	idw->SetMode(clseBayItemDetailWidget::Generic);
	idw->EmitHTML(mpStream);
	delete idw;


	// *** end of item details *** //


/* FOR TESTING!!!
	*mpStream << "<p>Exchange rate: "
		      << mpItem->GetFVFConversionRate()
			  << " and fvf: "
			  << mpItem->GetListingFee();

	clsAccount *pAccount = gApp->GetDatabase()->GetUserById(mpItem->GetSeller())->GetAccount();
	pAccount->ChargeListingFee(mpItem);
*/


	*mpStream	<<	"<a name=BID>"
					"<center>"
					"<table border=\"1\" cellspacing=\"0\" " 
					"width=\"100%\" bgcolor=\"#99CCCC\">\n"
					"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font size=5 color=\"#000000\">"
					"<b>"
					"Bidding"
					"</b></font></td>\n"
					"</tr>\n"
					"</table></center></a>\n";

	*mpStream <<	"\n";

	// If the item's still up for sale, let's put out the
	// bid page now
	if (nowTime < endTime)
	{
		if (   // mpItem->GetBidCount() > 0 &&
			mpItem->GetPrice() > 0)
		{
			bidIncrement	= 
				mpItem->GetBidIncrement(); // from current price
		}
		else
		{
			bidIncrement	= 
				mpItem->GetBidIncrement(
							mpItem->GetStartPrice()
											  );
		}

		//clean up title
		cleanTitle = clsUtilities::StripHTML(mpItem->GetTitle());

		// Show item title and item number again
		*mpStream <<	"<p align=center><font size=4>\n"
				  <<	cleanTitle
				  <<	"</font>"
						"<font size=3>"
						" (Item #"
				  <<	pItemNo
				  <<	")</font></p>\n";

		delete [] cleanTitle;

		// Current bid prices and increment

		// Begin table tag
		*mpStream <<	"<center><table border=0 cellpadding=0 "
						"cellspacing=0 width=\"35%\">\n";

		// Create row for current bid
		char bidTitle[30];
		double bidAmount;

		// If dutch, use the wording "Minimum bid"
		if (mpItem->GetQuantity() > 1)
		{
			strcpy(bidTitle, "Minimum bid");
			bidAmount = mpItem->GetStartPrice();
			minimumBid = mpItem->GetStartPrice();
		}
		// not dutch, so use "Current bid" or "Bidding starts at"
		//  depending on whether or not there are any bids yet
		else
		{
			if ( // mpItem->GetBidCount() > 0) 
				mpItem->GetPrice() > 0)
			{
				strcpy(bidTitle, "Current bid");
				bidAmount = mpItem->GetPrice();
				minimumBid = mpItem->GetPrice() + bidIncrement;
			}
			else
			{
				strcpy(bidTitle, "Starts at");
				bidAmount = mpItem->GetStartPrice();
				minimumBid = mpItem->GetStartPrice();
			}
		}

		// Html for the row for current bid
		*mpStream <<	"<tr>\n"
						"<td width=\"50%\"><font size=2>"
				  <<	bidTitle
				  <<	"</font></td>\n"
						"<td width=\"50%\" align=right><font size=4>";

		currencyWidget.SetNativeAmount(bidAmount);
		currencyWidget.EmitHTML(mpStream);
		
		*mpStream  <<	"</font></td>\n"
						"</tr>\n";
		
		// If not dutch, create row for bid increment
		if (mpItem->GetQuantity() <= 1)
		{
			*mpStream <<	"<tr>\n"
							"<td width=\"50%\"><font size=2>"
							"Bid increment"
							"</font></td>\n"
							"<td width=\"50%\" align=right><font size=4>";

			currencyWidget.SetNativeAmount(bidIncrement);
			currencyWidget.EmitHTML(mpStream);
	
			*mpStream  <<	"</font></td>\n"
							"</tr>\n";
		}

		// Create row for minimum bid
		*mpStream <<	"<tr>\n"
						"<td width=\"50%\"><font size=2><b>"
						"Minimum bid"
						"</b></font></td>\n"
						"<td width=\"50%\" align=right><font size=4><b>";

		currencyWidget.SetNativeAmount(minimumBid);
		currencyWidget.EmitHTML(mpStream);

		*mpStream  <<	"</font></b></td>\n"
						"</tr>\n";

		// End table tag
		*mpStream <<	"</table></center>";

		// Spacer
		*mpStream <<	"<br>";

		// Registration is required to bid
		*mpStream <<	"\n"
						"<font size=\"3\">"
						"<b>"
						"Registration required."
						"</b>"
						" eBay requires registration in order to bid. Find out how to "
					<<	"<a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"services/registration/register.html\">"
						"become a registered user"
						"</a>"
						". It's fast and it's <b>free</b>!"
						"</font>\n"
						"\n";

		clsBidBoxWidget bidBox(mpMarketPlace, mpItem, minimumBid);
		bidBox.EmitHTML(mpStream);
	}
	// if bidding is closed...
	else
	{
		*mpStream <<	"<p align=center>"
						"<b>"
						"Bidding is closed for this item."
						"</b>"
						"</p>"
						"\n";
	}

	// Spacer
	*mpStream <<	"<br>";

	*mpStream << mpMarketPlace->GetFooter()
			  << flush;

	CleanUp();
	return;
}

void clseBayApp::EmitItemAddToDescDenied(clsItem *pItem,
										 FilterVector *pvFilters,
										 ostream *pStream)
{
#if 1
	// Display message to tell seller that update is not being allowed but not why
	*pStream	<< "<h2>Addition to Item Description Denied</h2>"
				<< "The description of item "
				<< pItem->GetId()
				<< " ("
				<< pItem->GetTitle()
				<< ") cannot be appended as specified.";
#else
	FilterVector::iterator	i;
	char *					pString;

	// Display message to tell seller that update is not being allowed and why
	*pStream	<< "<h2>Addition to Item Description Denied</h2>"
				<< "The description of item "
				<< pItem->GetId()
				<< " ("
				<< pItem->GetTitle()
				<< ") cannot be appended as specified because the text"
				<< "to be appended contain(s) the following word(s)"
				<< "and/or phrase(s):<br>";
	
	for (i = pvFilters->begin(); i != pvFilters->end(); i++)
	{
		pString = (*i)->GetExpression();
		if (pString == NULL)
		{
			*pStream	<< "<li>"
						<< pString
						<< "</li>";
		}
	}
#endif

//	*pStream	<< "<p>"
//				<< "If you wish to appeal, please send an email which includes "
//				<< "your user ID, the item number, and the item title to "
//				<< "<a href=\"mailto:"
//				<< eBayBlockedItemAppealEmailAddress
//				<< "\">"
//				<< eBayBlockedItemAppealEmailAddress
//				<< "</a>.";
}

	
void clseBayApp::AddToItem(CEBayISAPIExtension *pServer,
						   char *pUser,
						   char *pPass,
						   char *pItemNo,
						   char *pAddition)
{
// petra	time_t		nowTime;
// petra	struct tm	*pTheTime;
	char		cDate[16];
	char		cTime[16];

// petra	clseBayTimeWidget	nowTimeWidget;
// petra	TimeZoneEnum		timeZone;

	char		seperator[128];
	char		*pDescription;
	int			descLen;
	char		*pNewAddition;
	char		*pNewDesc;

	clsCategory *				pCategory = NULL;
	CategoryId					categoryId;

	FilterVector				vFilters;

	// Setup
	SetUp();

	// Let's try and get the item
	if (!GetAndCheckItem(pItemNo))
	{
		CleanUp();
		return;
	}

	// Usual Title and Header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
					// nsacco 07/19/99
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Adding to item "
			  <<	pItemNo
			  <<	" - "
			  <<	mpItem->GetTitle()
			  <<	"</TITLE>"
					"</HEAD>";

	*mpStream <<	mpMarketPlace->GetHeader();


	// GetAndCheckItem gets the item in such a way
	// that the seller userid is populated. Let's 
	// see if this item belongs to this user before
	// going any furthur
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUser, pPass, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	if (mpUser->GetId() != mpItem->GetSeller())
	{
		*mpStream <<	"<p>"
						"<B>"
				  <<	pUser
				  <<	" is not the seller for item "
				  <<	pItemNo
				  <<	"</B>"
						"<p>"
						"Only the seller is allowed to add to an item\'s "
						"description. If you are the seller, please go back, "
						"correct the "
				  <<	mpMarketPlace->GetLoginPrompt()
				  <<	", and try again. "
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;
		
		CleanUp();
		return;

	}

	// Let's elide junk from the addition
	pNewAddition	= ChangeHTMLQuoteToQuote(pAddition);

	// Let's build the nice line which goes 
	// before their addition

	clseBayTimeWidget nowTimeWidget (mpMarketPlace,					// petra
									 EBAY_TIMEWIDGET_MEDIUM_DATE,	// petra
									 EBAY_TIMEWIDGET_NO_TIME);		// petra
	nowTimeWidget.EmitString (cDate);								// petra
	nowTimeWidget.SetDateTimeFormat (EBAY_TIMEWIDGET_NO_DATE,		// petra
									 EBAY_TIMEWIDGET_LONG_TIME);	// petra
	nowTimeWidget.EmitString (cTime);
	sprintf(seperator,
			"\n<hr><samp>On %s at %s, seller added the following information:</samp><p>\n",
			cDate,
			cTime);

	// Let's make a buffer big enough for the old description
	// plus the new
	pDescription	= mpItem->GetDescription();
	if (pDescription)
		descLen	= strlen(pDescription);
	else
		descLen	= 0;
	pNewDesc	= new char[descLen + 
						   strlen(seperator) +
						   strlen(pNewAddition) +
						   1];
	
	if (pDescription)
		strcpy(pNewDesc, mpItem->GetDescription());
	else
		*pNewDesc	= '\0';

	strcat(pNewDesc, seperator);
	strcat(pNewDesc, pNewAddition);

	// Stick it in the item but don't update the database
	mpItem->SetDescription(pNewDesc);

	categoryId = mpItem->GetCategory();
	pCategory = mpCategories->GetCategory(categoryId, true);
	if (pCategory == NULL)
	{
		*mpStream <<	"<p>"
						"The category designated for this item is invalid."
						"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;
		
		CleanUp();
		return;
	}

	// Do we need to screen items in this category?
	if (pCategory->GetScreenItems())
	{
		// Screen the item against filters for new category
		ActionType action = AdminScreenItem(mpItem,
											mpUser,
											&vFilters,
											ScreenItemOnChangeCategory,
											mpStream);

	// Does the listing need to be blocked?
	if (action == ActionTypeBlockListing)
	{
		// Display a message to the seller about the category change
			// being denied
		EmitChangeCategoryDenied(mpItem, &vFilters, mpStream);

		*mpStream	<< "<p>"
					<< mpMarketPlace->GetFooter();

		// Clear vector
		vFilters.erase(vFilters.begin(), vFilters.end());

		// Clean up and return cuz we're done
		CleanUp();
		return;
	}

#if 0   // don't display message to seller by default

    // BUG FIX: 2418 S.Forgaard. Allow seller to add to their item description a word that is flagged
	else if (action == ActionTypeFlagListing)
	{
		*mpStream	<< "Item #"
					<<	mpItem->GetId()
					<< " ("
					<< mpItem->GetTitle()
					<< ") has been flagged for review.";
	}
#endif
	}


	// Change got past screening, so let's continue...

	// Stick it in the item and update the database as well
	mpItem->SetNewDescription(pNewDesc);

	// Tell the user it worked
	*mpStream <<	"<h2>Your addition has been recorded</h2>"
					"Your addition to item number "
			  <<	pItemNo
			  <<	" has been time-stamped and recorded."
					"<p>"
					"Follow this link to view the change: "
					"<a href="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageViewItem)
			  <<	"eBayISAPI.dll?ViewItem&item="
			  <<	pItemNo
			  <<	"\""
					">"
			  <<	mpMarketPlace->GetCGIPath(PageViewItem)
			  <<	"eBayISAPI.dll?ViewItem&item="
			  <<	pItemNo
			  <<	"</a>"
					"<p>"
					"\n"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	// Bye Bye
	delete	pNewAddition;
	delete	pNewDesc;

	CleanUp();

	return;

}

//
// VerifyStop
//
void clseBayApp::VerifyStop(CEBayISAPIExtension *pServer,
						   char *pItemNo,
						   char *pUser,
						   char *pPass)
{
	const struct tm	*pTimeAsTm;
	char			cEndDate[16];
	char			cEndTime[32];

	time_t			curtime;
	time_t			endtime;

	// Setup
	SetUp();

	// Let's try and get the item
	if (!GetAndCheckItem(pItemNo))
	{
		CleanUp();
		return;
	}

	// Usual Title and Header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  // nsacco 07/19/99
			  <<    mpMarketPlace->GetCurrentPartnerName()
			  <<	" End Auction Verification"
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();


	// GetAndCheckItem gets the item in such a way
	// that the seller userid is populated. Let's 
	// see if this item belongs to this user before
	// going any furthur
	_strlwr(pUser);						// Bad, Mike, Bad!!!
	_strlwr(mpItem->GetSellerUserId());	// Bad!


	// Now, let's see if the user's legitimate 
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUser, pPass, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Let's see if the auction's ended
	curtime	= time(0);

	if (mpItem->GetEndTime() < curtime)
	{
		endtime		= mpItem->GetEndTime();
		clseBayTimeWidget timeWidget (mpMarketPlace, 1, -1, endtime);	// petra
		pTimeAsTm	= localtime(&endtime);
		if (pTimeAsTm)
		{
			timeWidget.EmitString (cEndDate);		// petra
			timeWidget.SetDateTimeFormat (-1, 2);	// petra
			timeWidget.EmitString (cEndTime);		// petra
// petra			strftime(cEndDate, sizeof(cEndDate),
// petra					 "%m/%d/%y",
// petra					 pTimeAsTm);
// petra
// petra			strftime(cEndTime, sizeof(cEndTime),
// petra					 "%H:%M:%S %z",
// petra					pTimeAsTm);
		}
		else
		{
			strcpy(cEndDate, "*Error*");
			strcpy(cEndTime, "*Error*");
		}

		*mpStream <<	"<h2>Bidding already closed</h2>"
						"The bidding on the item: "
				  <<	mpItem->GetTitle()
				  <<	" (item #"
				  <<	pItemNo
				  <<	") ended on "
				  <<	cEndDate
				  <<	" at "
				  <<	cEndTime
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	if (mpUser->GetId() != mpItem->GetSeller())
	{
		*mpStream <<	"<p>"
						"<H2>"
				  <<	pUser
				  <<	" is not the seller for item "
				  <<	pItemNo
				  <<	"</H2>"
						"<p>"
						"Only the seller is allowed to stop an auction. "
						"If you are the seller, please go back, "
						"correct the "
				  <<	mpMarketPlace->GetLoginPrompt()
				  <<	", and try again. "
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;
		
		CleanUp();
		return;

	}
	// Everything's cool. 
	*mpStream <<	"<h2>Verifying ending auction for: "
			  <<	mpItem->GetTitle()
			  <<	" (item #"
			  <<	pItemNo
			  <<	")"
					"</h2>"
					"\n"
			  <<	"Are you sure you wish to end this auction now? An e-mail "
					"message will be sent to you and to the high bidder announcing "
					"that this auction is over. The results "
					"will also be publicly available. You will not be able "
					"to re-start this same auction. If you want to end the auction "
					"now, press the end auction button."
					"<p>"
					"\n"
					"<strong>Please consider this:</strong>"
					" many people wait until the very last minute to place "
					"a bid, and you may be losing out on potential buyers."
					"<p>"
					"\n"
					"<blockquote>"
					"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageStop)
			  <<	"eBayISAPI.dll?stop"
					"\""
					">"
			  <<	"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" "
					"VALUE=\"Stop\">\n"
					"<input type=hidden name=item value="
			  <<	pItemNo
			  <<	">\n"
					"<input type=hidden name=userid value="
			  <<	pUser
			  <<	">\n"
					"<input type=hidden name=pass value=\""
			  <<	pPass
			  <<	"\">\n"
			  <<	"<input type=submit value="
					"\""
					"end auction"
					"\""
					">"
					"</blockquote>"
					"</form>"
					"<p>"
			  <<	mpMarketPlace->GetFooter();

	
	CleanUp();

	return;

}

//
// Stop
//
void clseBayApp::Stop(CEBayISAPIExtension *pServer,
					  char *pItemNo,
					  char *pUser,
					  char *pPass)
{
	const struct tm	*pTimeAsTm;
	char			cEndDate[16];
	char			cEndTime[32];

	time_t			curtime;
	time_t			endtime;

	// Setup
	SetUp();

	// Let's try and get the item
	if (!GetAndCheckItem(pItemNo))
	{
		CleanUp();
		return;
	}

	// Usual Title and Header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
					// nsacco 07/19/99
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" End Auction Acknowledgement"
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();


	// Now, let's see if the user's legitimate 
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUser, pPass, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// GetAndCheckItem gets the item in such a way
	// that the seller userid is populated. Let's 
	// see if this item belongs to this user before
	// going any furthur
	if (mpUser->GetId() != mpItem->GetSeller())
	{
		*mpStream <<	"<p>"
						"<B>"
				  <<	pUser
				  <<	" is not the seller for item "
				  <<	pItemNo
				  <<	"</B>"
						"<p>"
						"Only the seller is allowed to stop an auction. "
						"If you are the seller, please go back, "
						"correct the "
				  <<	mpMarketPlace->GetLoginPrompt()
				  <<	", and try again. "
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;
		
		CleanUp();
		return;

	}

	// Let's see if the auction's ended
	curtime	= time(0);

	if (mpItem->GetEndTime() < curtime)
	{
		endtime		= mpItem->GetEndTime();
		clseBayTimeWidget timeWidget (mpMarketPlace, 1, -1, endtime);	// petra
		pTimeAsTm	= localtime(&endtime);
		if (pTimeAsTm)
		{
			timeWidget.EmitString (cEndDate);		// petra
			timeWidget.SetDateTimeFormat (-1, 2);	// petra
			timeWidget.EmitString (cEndTime);		// petra
// petra			strftime(cEndDate, sizeof(cEndDate),
// petra					 "%m/%d/%y",
// petra					 pTimeAsTm);
// petra
// petra			strftime(cEndTime, sizeof(cEndTime),
// petra					 "%H:%M:%S %z",
// petra					pTimeAsTm);
		}
		else
		{
			strcpy(cEndDate, "*Error*");
			strcpy(cEndTime, "*Error*");
		}

		*mpStream <<	"<h2>Bidding already closed</h2>"
						"The bidding on the item: "
				  <<	mpItem->GetTitle()
				  <<	" (item #"
				  <<	pItemNo
				  <<	") ended on "
				  <<	cEndDate
				  <<	" at "
				  <<	cEndTime
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}


	// Everything's cool. "End" the auction by setting the
	// ending time to now.
	mpItem->SetNewEndTime(curtime);

	// invalidate th e seller list
	mpDatabase->InvalidateSellerList(mpMarketPlace->GetId(),
									 mpItem->GetSeller());

	// Tell the user it's toast
	*mpStream <<	"<h2>Ended auction for: "
			  <<	mpItem->GetTitle()
			  <<	" (item #"
			  <<	pItemNo
			  <<	")</h2>\n"
			  <<	"<strong>"
					"This auction is officially over! Congratulations!"
					"</strong>"
					"<p>"
					"Notification will be sent via e-mail to the "
					"seller and to the highest bidder at the next "
					"scheduled update. Thank you for using "
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	". We hope you enjoyed using this service, "
					"and "
					"<strong>please spread the word!</strong>"
					"\n"
					"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;

}

//
// Featured
//	This is a silly method. It's whole purpose in life is
//	to emit the HTML for submitting a featured auction.
//	This page contains various embedded fields -- like
//	the price for featuring auctions -- which we need 
//	to fill in. Rather than counting on the pages getting
//	updated, we emit it here.
//
//	This is WAY ugly. Sorry.
//
static const char *Featured_Block_1 =
" service, some people may find that their listings are \
getting buried among thousands of items. If you want \
more visitors to notice your listing, make it \
a <strong>Featured Auction</strong> \
or <strong>Featured Category Auction</strong>. \
<p> \
<strong>Featured Auctions</strong> rotate at random intervals on the eBay home page, \
guaranteeing that every visitor will have an \
excellent chance of seeing your listing. \
<p> \
<strong>Featured Category Auctions</strong> appear at the top \
of the category in which the item is listed, guaranteeing \
that every visitor viewing the category in which your item \
is listed will have an excellent chance of seeing your listing. \
<p>";

static const char *Featured_Block_2 =
"Both fees apply for the entire length of the auction. \
<p> \
<h3>Getting listed</h3> \
To order a \
<strong>Featured Auction</strong> or \
<strong>Featured Category Auction</strong>, \
please provide us with the following information. ";

static const char *Featured_Block_3 = 
"reserves the right to refuse placing an auction into the \
Featured Auctions area. \
<p> \
Please note that the following types of auctions are not \
eligible for Featured placement: \
<p> \
<ul>";

static const char *Featured_Block_4 =
"<li> Listings for services or for the sale of information. \
<li> Listings that are of a promotional/advertising nature. \
<li> Listings that may be illicit, illegal, or immoral.";


static const char *Featured_Block_5 =
"</ul> \
<p> \
 \
Please note that this is a non-exhaustive list, and eBay's \
decisions on Featured placement are final. \
<p> \
\
<strong>We reserve the right to refuse Featured Auction \
placement for any auction for any reason without \
explanation.</strong> You will be notified if we remove your \
listing from this section, and your feature fee will be \
refunded. Your insertion fee will <strong>not</strong> \
be refunded, since your auction will be allowed to continue \
in a different category. Your Featured Auction request \
indicates your agreement to this policy. \
<p>";

/*void clseBayApp::Featured(CEBayISAPIExtension *pServer)
{
	const clsMarketPlaceUserCriteria *pCriteria;

	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);
	time_t when = time(0);
	// Title
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Featured Auctions"
					"</title>"
					"</head>"			  
			  <<	mpMarketPlace->GetHeader()
			  <<	"<h2>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Featured Auctions"
					"</h2>"
					"<p>"
					"\n"
					"With the growing popularity of the "
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	Featured_Block_1
			  <<	"<strong>Featured Auctions</strong> are availible "
					"for a flat fee of "
					"<strong>"
					"$"
			  <<	mpMarketPlace->GetFeaturedFee()
			  <<	"</strong>"
					".  ";
	if (clsUtilities::CompareTimeToGivenDate(when, 02, 15, 99, 0, 0, 0) < 0) 
	{				
		*mpStream	<< "<font color=\"red\">As part of Featured Auction changes, "
					"prices for Featured Auctions will be increased to $99.95 on "
					"2/15/99.</font>";
	}
	*mpStream	<<	"<p>"
					"<strong>Featured Category Auctions</strong> are "
					"availible for a flat fee of "
					"<strong>"
					"$"
			  <<	mpMarketPlace->GetCategoryFeaturedFee()
			  <<	"</strong>"
					".  ";
	if (clsUtilities::CompareTimeToGivenDate(when, 02, 15, 99, 0, 0, 0) < 0) 
	{				
		*mpStream	<< 	"<font color=\"red\">Prices for Category Featured Auctions "
					"will be increased to $14.95 on 2/15/99.</font>";
	}
	*mpStream	<<	"<p>"
				<<	Featured_Block_2
			  <<	"Note that you must be a "
					"<a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"register-by-country.html"
					"\""
					">"
					"registered user"
					"</a>"
					" before proceeding."
					"<p>"
					"<strong>"
					"NOTE:"
					"</strong>"
					"\n"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" "
			  <<	Featured_Block_3
			  <<	"<li> "
					"Listings of an "
					"<a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"adult.html"
					"\""
					">"
					"adult"
					"</a>"
					" nature."
			  <<	Featured_Block_4
			  <<	"<li> "
					"Listings that do not offer a genuine auction "
					"per eBay's "
					"<a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/community/png-list.html"
					"\""
					">"
					"Guidelines."
					"</a>"
			  <<	Featured_Block_5;

	pCriteria	= mpMarketPlace->GetFeaturedCriteria();

	if (pCriteria && pCriteria->mFeedbackCriteria)
	{
		*mpStream <<	"<b>Feedback Rating</b>"
						"<p>"
						"Effective immediately, users must have a feedback "
						"rating of "
				  <<	pCriteria->mMinimumFeedbackScore
				  <<	" or higher in order to place a Featured Auction."
						"</b>"
						"<p>";
	}

	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageMakeFeatured)
			  <<	"eBayISAPI.dll"
			  <<	"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\""
					" VALUE=\"MakeFeatured\""
					">"
					"<pre>"
					"Your registered "
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	":                   "
					"<input type=text name=userid "
					"size=" << 40 << " "
					"maxlength=" << 40 << " "
					">"
					"\n"
					"Your "
				<<	mpMarketPlace->GetPasswordPrompt()
				<<	":"
					"                             "
					"<input type=password name=pass size=40>"
					"\n"
					"\n"
					"Item number you wish to feature:"
					"           "
					"<input type=text name=itemno "
					"size=" << EBAY_MAX_ITEM_SIZE << " "
					"maxlength=" << EBAY_MAX_ITEM_SIZE << " "
					">"
					"\n"
					"\n"
					"Select the type of Featured auction you would like to order:"
					"\n"
					"<input type=checkbox name=typesuper>"
					"<strong>Featured Auction</strong>"
					"\n"
					"<input type=checkbox name=typefeature>"
					"<strong>Featured Category Auction</strong>"
					"</pre>"
					"\n"
					"<p>"
					"<strong>"
					"Press this button to order your featured auction. "
					"A non-refundable fee of $"
			  <<	mpMarketPlace->GetFeaturedFee()
			  <<	" for a Featured Auction, or $"
			  <<	mpMarketPlace->GetCategoryFeaturedFee()
			  << 	" for a Featured Category Auction "   
			  <<	" will be added to your account. "
					"By pressing this button, you agree to pay the fee, as appropriate,"
					" when invoiced on the first of the following month."
					"</strong>"
					"\n"
					"<p>"
					"<blockquote>"
					"<input type=submit value="
					"\""
					"agree to fee "
			  <<	"\""
					">"
					" "
					"<strong>"
					"<font size=-1>"
					"Note: new fee!"
					"</font>"
					"</strong>"
					"</blockquote>"
					"\n"
					"<p>"
					"Press this button to clear the form if you made "
					"a mistake."
					"\n"
					"<p>"
					"<blockquote>"
					"<input type=reset value=\"clear form\">"
					"</blockquote>"
					"</form>"
					"\n"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}
*/

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *Featured_Block_featured_new1 =
"<p>eBay offers sellers an opportunity to showcase their items in two high-visibility "
"listing options:  <strong>Featured Auctions</strong> and <strong>Category Featured Auctions</strong>. "  
"<h3>Featured Auction Listing Option</h3>"
"<p>When you choose the <strong>Featured Auction</strong> listing option, your item appears at the top of "
"the main Listings page (accessible from the menu bar at the top of every page on eBay).  "
"As an added benefit, <strong>Featured Auctions</strong> are selected randomly to appear on the Featured "
"display area on the main eBay Home page and in the Featured Items section of related category home pages. "
"These display areas are updated periodically each day. eBay does not guarantee that your item will appear "
"on the Home page or in the Featured Items section of category home pages.  The appearance of a specific "
"item in these areas depends on the probability of your item being selected randomly from among all of the "
"current <strong>Featured Auctions</strong> at any given time. "
"<p>eBay enforces quality standards for <strong>Featured Auctions</strong>.  "
"We will remove any item listed in this area that violates the "
"<a href=\"http://pages.ebay.com/help/community/png-user.html\">eBay User Agreement</a> "
"or is on the list below. The following types of auctions are not eligible for listing as "
"<strong>Featured Auctions</strong> (Please note that this is not an exhaustive list, and eBay "
"decisions regarding removal of items are final.): "
"<ul><li>Listings of an <a href=\"http://pages.ebay.com/help/buyerguide/adult.html\">adult</a> nature. "
"<li>Listings that may be illicit, illegal, or immoral. "
"<li>Listings for services or the sale of information (includes \"get rich quick\" schemes, fad diet plans, "
"and multilevel marketing promotions). "
"<li>Listings that are promotional or advertising in nature. "
"<li>Listings for firearms. "
"<li>Listings for novelty items and other items in poor taste (includes: phony tickets and IDs, "
"fake money, and items of scatological or sexual nature). "
"<li>Listings for auction utility software. "
"<li>Listings that do not offer a genuine auction per eBay <a href=\"http://pages.ebay.com/help/community/png-comm.html\">Guidelines</a>. "
"</ul>"
"<p>These restrictions apply only to the <strong>Featured Auction</strong> option. eBay offers listing options "
"for many of these items in other sections (as long as they comply with the "
"<a href=\"http://pages.ebay.com/help/community/png-user.html\">eBay User Agreement</a>). "
" <strong>Category Featured Auctions</strong> (see below) are not subject to these restrictions.  "
"<p>If you list an item in the <strong>Featured Auctions</strong> section that does not belong "
"there, eBay will remove it.  You will be notified of the removal of the item from this section, "
"and your feature fee will be refunded.  As long as the item is qualified for listing elsewhere, "
"we will not remove the auction from the site.  The item will remain in the category in which you "
"listed it.  Your insertion fee will not be refunded.  "
"<p><strong>Featured Auctions</strong> are available for a fee of ";
*/

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *Featured_Block_featured_new2 =
"<h3>Category Featured Auctions Listing Option</h3>"
"<p><strong>Category Featured Auctions</strong> appear at the top of listings pages "
"for that category, and are also selected on a random basis for display in the Featured "
"Items section of related category Home pages (when the category has a Home page).  "
"Visit eBay's <a href=\"http://pages.ebay.com/bookmarks.html\">Bookmarks page</a> "
"to see categories that have Home pages. eBay does not guarantee that your item will "
"appear in the Featured Items section of category home pages.  The appearance of a "
"specific item in these areas depends upon the probability of that item being selected "
"at random from eligible <strong>Featured Auctions</strong> and <strong>Category "
"Featured Auctions</strong>. "
"<p>Any item that is eligible for sale on eBay can be listed as a <strong>Category "
"Featured Auctions</strong>.  eBay does not apply the restrictions for <strong>Featured "
"Auctions</strong> described above to <strong>Category Featured Auctions</strong>. "
"<p><strong>Category Featured Auctions</strong> are available for a fee of ";
*/

static const char *Featured_Block_featured_new3 =
"<h3>Listing in Featured or Category Featured Areas</h3>"
"<p>An eBay member must have a feedback rating of 10 or more in order to place a "
"<strong>Featured Auction</strong> or a <strong>Category Featured Auction</strong>. "
"<p>Select the listing option(s) you would like to order.  You may choose both for "
"maximum exposure of your item: ";

static const char *Featured_Block_featured_new4 =
"<p>Selecting the <strong>Featured Auction</strong> or <strong>Category Featured "
"Auction</strong> listing option indicates your "
"agreement to the policies described above.  When you click on the "
"<strong>agree to fee</strong> button below, eBay "
"will insert your auction into the featured area you have chosen, and will add the "
"appropriate fees "
"to your account. The fees will appear on your invoice for the month "
"in which you choose the listing "
"option.  <strong>Featured Auction</strong> and "
"<strong>Category Featured Auction</strong> fees are non-refundable. </strong>";

void clseBayApp::Featured(CEBayISAPIExtension *pServer)
{
	const clsMarketPlaceUserCriteria *pCriteria;

	clsFees objFees(Currency_USD);

	clsCurrencyWidget currencyWidget(mpMarketPlace, Currency_USD, 0); // set below
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);
	time_t when = time(0);
	// Title
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
					// nsacco 07/19/99
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Featured Auctions and Category Featured Auctions"
					"</title>"
					"</head>"			  
			  <<	mpMarketPlace->GetHeader()
			  <<	"<h2>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Featured Auctions and Category Featured Auctions"
					"</h2>"
					"<p>"
					"\n"

	//		  <<	Featured_Block_featured_new1

	// kakiyama 07/09/99

			  <<	clsIntlResource::GetFResString(-1,
												"<p>eBay offers sellers an opportunity to showcase their items in two high-visibility "
												"listing options:  <strong>Featured Auctions</strong> and <strong>Category Featured Auctions</strong>. "  
												"<h3>Featured Auction Listing Option</h3>"
												"<p>When you choose the <strong>Featured Auction</strong> listing option, your item appears at the top of "
												"the main Listings page (accessible from the menu bar at the top of every page on eBay).  "
												"As an added benefit, <strong>Featured Auctions</strong> are selected randomly to appear on the Featured "
												"display area on the main eBay Home page and in the Featured Items section of related category home pages. "
												"These display areas are updated periodically each day. eBay does not guarantee that your item will appear "
												"on the Home page or in the Featured Items section of category home pages.  The appearance of a specific "
												"item in these areas depends on the probability of your item being selected randomly from among all of the "
												"current <strong>Featured Auctions</strong> at any given time. "
												"<p>eBay enforces quality standards for <strong>Featured Auctions</strong>.  "
												"We will remove any item listed in this area that violates the "
												"<a href=\"%{1:GetHTMLPath}help/community/png-user.html\">eBay User Agreement</a> "
												"or is on the list below. The following types of auctions are not eligible for listing as "
												"<strong>Featured Auctions</strong> (Please note that this is not an exhaustive list, and eBay "
												"decisions regarding removal of items are final.): "
												"<ul><li>Listings of an <a href=\"%{2:GetHTMLPath}help/buyerguide/adult.html\">adult</a> nature. "
												"<li>Listings that may be illicit, illegal, or immoral. "
												"<li>Listings for services or the sale of information (includes \"get rich quick\" schemes, fad diet plans, "
												"and multilevel marketing promotions). "
												"<li>Listings that are promotional or advertising in nature. "
												"<li>Listings for firearms. "
												"<li>Listings for novelty items and other items in poor taste (includes: phony tickets and IDs, "
												"fake money, and items of scatological or sexual nature). "
												"<li>Listings for auction utility software. "
												"<li>Listings that do not offer a genuine auction per eBay <a href=\"%{3:GetHTMLPath}help/community/png-comm.html\">Guidelines</a>. "
												"</ul>"
												"<p>These restrictions apply only to the <strong>Featured Auction</strong> option. eBay offers listing options "
												"for many of these items in other sections (as long as they comply with the "
												"<a href=\"%{4:GetHTMLPath}help/community/png-user.html\">eBay User Agreement</a>). "
												" <strong>Category Featured Auctions</strong> (see below) are not subject to these restrictions.  "
												"<p>If you list an item in the <strong>Featured Auctions</strong> section that does not belong "
												"there, eBay will remove it.  You will be notified of the removal of the item from this section, "
												"and your feature fee will be refunded.  As long as the item is qualified for listing elsewhere, "
												"we will not remove the auction from the site.  The item will remain in the category in which you "
												"listed it.  Your insertion fee will not be refunded.  "
												"<p><strong>Featured Auctions</strong> are available for a fee of ",
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												NULL)


			<<		"<strong>";

	currencyWidget.SetNativeAmount(objFees.GetFee(NewFeaturedFee));
	currencyWidget.EmitHTML(mpStream);		

	*mpStream <<	"</strong>"
					".  ";
	if (clsUtilities::CompareTimeToGivenDate(when, 02, 15, 99, 0, 0, 0) < 0) 
		*mpStream	<< "\n"
					 "<font color=\"red\">As part of Featured Auction changes, "
					"prices for Featured Auctions will be increased to $99.95 on "
					"2/15/99.</font>";

	// *mpStream	<<	Featured_Block_featured_new2
	   *mpStream    << clsIntlResource::GetFResString(-1,
													"<h3>Category Featured Auctions Listing Option</h3>"
													"<p><strong>Category Featured Auctions</strong> appear at the top of listings pages "
													"for that category, and are also selected on a random basis for display in the Featured "
													"Items section of related category Home pages (when the category has a Home page).  "
													"Visit eBay's <a href=\"%{1:GetHTMLPath}/bookmarks.html\">Bookmarks page</a> "
													"to see categories that have Home pages. eBay does not guarantee that your item will "
													"appear in the Featured Items section of category home pages.  The appearance of a "
													"specific item in these areas depends upon the probability of that item being selected "
													"at random from eligible <strong>Featured Auctions</strong> and <strong>Category "
													"Featured Auctions</strong>. "
													"<p>Any item that is eligible for sale on eBay can be listed as a <strong>Category "
													"Featured Auctions</strong>.  eBay does not apply the restrictions for <strong>Featured "
													"Auctions</strong> described above to <strong>Category Featured Auctions</strong>. "
													"<p><strong>Category Featured Auctions</strong> are available for a fee of ",
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													NULL)

			<<		"<strong>";

	currencyWidget.SetNativeAmount(objFees.GetFee(NewCategoryFeaturedFee));
	currencyWidget.EmitHTML(mpStream);		

	*mpStream  <<	"</strong>"
					".  ";

	*mpStream		 <<	Featured_Block_featured_new3;

	pCriteria	= mpMarketPlace->GetFeaturedCriteria();


	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageMakeFeatured)
			  <<	"eBayISAPI.dll"
			  <<	"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\""
					" VALUE=\"MakeFeatured\""
					">"
					"\n"
					"<pre>"
					"<input type=checkbox name=typesuper>"
					"<strong>Featured Auction"
					" (";
	
	currencyWidget.SetNativeAmount(objFees.GetFee(NewFeaturedFee));
	currencyWidget.EmitHTML(mpStream);		

	*mpStream <<	")</strong>\n"
					"<input type=checkbox name=typefeature>"
					"<strong>Featured Category Auction"
					" (";

	currencyWidget.SetNativeAmount(objFees.GetFee(NewCategoryFeaturedFee));
	currencyWidget.EmitHTML(mpStream);		

	*mpStream <<	")</strong>\n"
					"\n"
					"Your registered "
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	":                   "
					"<input type=text name=userid "
					"size=" << 40 << " "
					"maxlength=" << 40 << " "
					">"
					"\n"
					"Your "
				<<	mpMarketPlace->GetPasswordPrompt()
				<<	":"
					"                             "
					"<input type=password name=pass size=40>"
					"\n"
					"Item number you wish to feature:"
					"           "
					"<input type=text name=itemno "
					"size=" << EBAY_MAX_ITEM_SIZE << " "
					"maxlength=" << EBAY_MAX_ITEM_SIZE << " "
					"></pre>"
					"\n"
					"\n"
				<<  Featured_Block_featured_new4
				<<	"<p>"
					"<blockquote>"
					"<input type=submit value="
					"\""
					"agree to fee "
			  <<	"\""
					">"
					" "
					"</blockquote>"
					"\n"
					"<p>"
					"Press this button to clear the form if you made "
					"a mistake."
					"\n"
					"<p>"
					"<blockquote>"
					"<input type=reset value=\"clear form\">"
					"</blockquote>"
					"</form>"
					"\n"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}


//
// MakeFeatured
//
void clseBayApp::MakeFeatured(CEBayISAPIExtension *pServer,
							  char *pItemNo,
							  char *pUser,
							  char *pPass,
							  char *pTypeSuper,
							  char *pTypeFeature)
{
	const struct tm	*pTimeAsTm;
	char			cEndDate[16];
	char			cEndTime[32];

	time_t			curtime;
	time_t			endtime;

	clsCurrencyWidget currencyWidget(mpMarketPlace, Currency_USD, 0); // set below.
	
	// Setup
	SetUp();

	// Let's try and get the item
	if (!GetAndCheckItem(pItemNo))
	{
		CleanUp();
		return;
	}

	clsFees objFees(mpItem);

	// Usual Title and Header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
					// nsacco 07/19/99
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Ordering Featured Auction Acknowledgement"
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();


	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUser, pPass, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Let's see if this item belongs to this user before
	// going any furthur
	if (mpUser->GetId() != mpItem->GetSeller())
	{
		*mpStream <<	"<p>"
						"<h2>Someone else's item?</h2>"
						"Sorry, the item number you entered "
						"<samp>"
						"("
				  <<	pItemNo
				  <<	")"
						"</samp>"
						"appears to belong to another seller"
						"<samp>"
						"("
				  <<	mpItem->GetSellerUserId()
				  <<	")"
						"</samp>."
						" You can only request featured auctions for your "
						"own items. Please use the back button on your "
						"browser to go back and try again."
						"\n"
						"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;
		
		CleanUp();
		return;

	}



	// Let's see if the user can do this
	if (!mpMarketPlace->UserCanFeature(mpUser,
									   mpStream))
	{
		*mpStream <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}
	// Let's see if the auction's ended
	curtime	= time(0);

	if (mpItem->GetEndTime() < curtime)
	{
		endtime		= mpItem->GetEndTime();
		clseBayTimeWidget timeWidget (mpMarketPlace, 1, -1, endtime);	// petra
		pTimeAsTm	= localtime(&endtime);
		if (pTimeAsTm)
		{
			timeWidget.EmitString (cEndDate);		// petra
			timeWidget.SetDateTimeFormat (-1, 2);	// petra
			timeWidget.EmitString (cEndTime);		// petra
// petra			strftime(cEndDate, sizeof(cEndDate),
// petra					 "%m/%d/%y",
// petra					 pTimeAsTm);
// petra
// petra			strftime(cEndTime, sizeof(cEndTime),
// petra					 "%H:%M:%S %z",
// petra					pTimeAsTm);
		}
		else
		{
			strcpy(cEndDate, "*Error*");
			strcpy(cEndTime, "*Error*");
		}

		*mpStream <<	"<h2>Bidding already closed</h2>"
						"The bidding on the item: "
				  <<	mpItem->GetTitle()
				  <<	" (item #"
				  <<	pItemNo
				  <<	") ended on "
				  <<	cEndDate
				  <<	" at "
				  <<	cEndTime
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Let's see if the auction's already super featured
	if (mpItem->GetSuperFeatured() && 
		CHECKED(pTypeSuper))
	{
		*mpStream <<	"<h2>Already featured</h2>"
						"The item number you entered "
						"<samp>"
						"("
				  <<	pItemNo
				  <<	")"
						"</samp>"
						" is already designated a Featured Auction."
						" If you don't see it in the Featured Auctions"
						" section, it probably means that no update has"
						" taken place since you placed your order."
                        " If you "
						"<a href="
						"\""
				  <<	mpMarketPlace->GetCGIPath(PageViewItem)
				  <<	"eBayISAPI.dll?ViewItem&item="
				  <<	pItemNo
				  <<	"\""
				  <<	">"
						"access the item's page"
						"</a>"
						" directly, you should see the Featured Auction"
                        " annotation in the category field."
						"\n"
						"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();

		return;
	}

	// Let's see if the auction's already featured
	if (mpItem->GetFeatured() && 
		CHECKED(pTypeFeature))
	{
		*mpStream <<	"<h2>Already featured</h2>"
						"The item number you entered "
						"<samp>"
						"("
				  <<	pItemNo
				  <<	")"
						"</samp>"
						" is already designated a Category Featured Auction."
						" If you don't see it in the Category Featured Auctions"
						" section, it probably means that no update has"
						" taken place since you placed your order."
                        " If you "
						"<a href="
						"\""
				  <<	mpMarketPlace->GetCGIPath(PageViewItem)
				  <<	"eBayISAPI.dll?ViewItem&item="
				  <<	pItemNo
				  <<	"\""
				  <<	">"
						"access the item's page"
						"</a>"
						" directly, you should see the Featured Auction"
                        " annotation in the category field."
						"\n"
						"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();

		return;
	}
	// Let's check the type
	if (!CHECKED(pTypeSuper) &&
		!CHECKED(pTypeFeature))
	{
		*mpStream <<	"<h2>Invalid Featured Type</h2>"
						"The type of featured auction was not transmitted "
						"properly to our server. Please ensure that you have "
						"used the form properly. Please go back and try "
						"again"
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Everything's cool. Let's make it featured
	if (CHECKED(pTypeSuper))
		mpItem->SetNewSuperFeatured(true);
	if (CHECKED(pTypeFeature))
		mpItem->SetNewFeatured(true);

	// Charge them!!
	if (CHECKED(pTypeSuper))
	{
		mpUser->GetAccount()->ChargeFeaturedFee(mpItem);
		//inna reset credit given for the feature fee flag
		mpItem->SetHasFeaturedCredit(false);
	}
	if (CHECKED(pTypeFeature))
	{
		mpUser->GetAccount()->ChargeCategoryFeaturedFee(mpItem);
		//inna reset credit given for the feature fee flag
		mpItem->SetHasCategoryFeaturedCredit(false);
	}

	// Tell the user it's toast
	*mpStream <<	"<h2>Featured Auction Ordered!</h2>"
					"Thank you for ordering a featured auction for your item "
					"<samp>"
			  <<	pItemNo
			  <<	"</samp>"
			  <<	". The item will show up as featured "
					"at the next update (within a few hours), "
					"and your account has been charged a non-refundable "
					"fee of ";

	if (CHECKED(pTypeSuper))
	{
		currencyWidget.SetNativeAmount(objFees.GetFee(NewFeaturedFee));
		currencyWidget.EmitHTML(mpStream);	
		
		*mpStream << " for featured fee";
	};



	if (CHECKED(pTypeFeature))
	{
		if (CHECKED(pTypeSuper))
			*mpStream << " and ";

		currencyWidget.SetNativeAmount(objFees.GetFee(NewCategoryFeaturedFee));
		currencyWidget.EmitHTML(mpStream);		

		*mpStream	<< " for category featured fee";
	};

	*mpStream <<	".\n"
					"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;

}

void clseBayApp::RecomputeDutchBids(CEBayISAPIExtension *pThis,
								char *pItemNo)
{
	// Duh.
	SetUp();

	// Let's try and get the item
	if (!GetAndCheckItem(pItemNo))
	{
		CleanUp();
		return;
	}

	mpItem->RecomputeDutchBids();
	
	// Blah
	*mpStream <<	"<html><head>"
					"<title>"
					// nsacco 07/19/99
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Recomputing dutch auction"
			  <<	pItemNo
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();


	*mpStream <<    "<p>Done\n";
	*mpStream <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}


void clseBayApp::RecomputeChineseBids(CEBayISAPIExtension *pThis,
								char *pItemNo)
{
	// Duh.
	SetUp();

	// Let's try and get the item
	if (!GetAndCheckItem(pItemNo))
	{
		CleanUp();
		return;
	}

	mpItem->AdjustPrice();
	
	// Blah
	*mpStream <<	"<html><head>"
					"<title>"
					// nsacco 07/19/99
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Recomputing Chinese auction price"
			  <<	pItemNo
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();


	*mpStream <<    "<p>Done\n";
	*mpStream <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}


