/*	$Id: clseBayAppSendGiftAlert.cpp,v 1.4.352.2 1999/08/05 18:59:04 nsacco Exp $	*/
//
//	File:		clseBayAppSendGiftAlert.cpp
//
//	Class:		clseBayAppSendGiftAlert
//
//	Author:		Mila Bird (mila@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 10/22/98	mila	- Created
//				- 10/30/98	mila	- Fixed mismatch between error message
//									  title and text.
//				- 11/03/98	mila	- Modified to get occasion info from database
//									  instead of from static table; added marketplace
//									  ID to clsGiftOccasion
//				- 11/05/98 mila		- Added base64-encoding of item number and open date
//				- 12/02/98 mila		- Amended to tell users to cut and paste URL
//				- 01/28/99 mila		- Added checks for unselected occasion, sender name
//									  too long, and recipient name too long; made user ID,
//									  sender name, and recipient name in URL safe by replacing
//									  '&' with '%26'; fixed memory leaks.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

#include "string.h"

#include "clsBase64.h"
#include "clsGiftOccasion.h"
#include "clsGiftOccasions.h"
#include <clseBayTimeWidget.h>	// petra

const int MinSellerFeedbackScore = 10;

static const char *ErrorMsgOmittedSenderUserId =
"<h2>User ID is missing</h2>"
"Sorry, your User ID is missing. "
"Please go back and enter your User ID.";

static const char *ErrorMsgOmittedSenderPassword =
"<h2>Password is missing</h2>"
"Sorry, your password is missing. "
"Please go back and enter your password.";

static const char *ErrorMsgOmittedSenderName =
"<h2>Sender's Name is missing</h2>"
"Sorry, your name is missing. "
"Please go back and enter your name.";

static const char *ErrorMsgSenderNameTooLong =
"<h2>Sender's Name is too long</h2>"
"Sorry, the sender's name must be 32 characters or less. "
"Please go back and enter a shorter sender's name.";

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

static const char *ErrorMsgOmittedRecipientName =
"<h2>Recipient's Name is missing</h2>"
"Sorry, the gift recipient's name is missing. "
"Please go back and enter the gift recipient's name.";

static const char *ErrorMsgRecipientNameTooLong =
"<h2>Recipient's Name is too long</h2>"
"Sorry, the recipient's name must be 32 characters or less. "
"Please go back and enter a shorter recipient's name.";

static const char *ErrorMsgOmittedDestinationEmail =
"<h2>E-mail Address is missing</h2>"
"Sorry, the e-mail address is missing. "
"Please go back and enter the e-mail address you would like the "
"Gift Alert sent to.";

static const char *ErrorMsgInvalidDestinationEmail =
"<h2>E-mail Address is invalid</h2>"
"Sorry, the E-mail Address you specified is invalid. "
"Please go back and enter a valid E-mail Address. ";

static const char *ErrorMsgIncompleteOpenDate =
"<h2>Open Date is incomplete</h2>"
"Sorry, the gift's Open Date is incomplete. "
"Please go back and select the earliest month, day, and year in which you'd "
"like the gift to be opened.";

static const char *ErrorMsgInvalidOpenDate =
"<h2>Open Date is invalid</h2>"
"Sorry, the gift's Open Date is invalid. "
"Please go back and enter a valid Open Date. ";

static const char *ErrorMsgInvalidSeller =
"<h2>Item Seller is invalid</h2>"
"Sorry, the item's seller ID is invalid. "
"Please go back and try again.";

static const char *ErrorMsgSellerNotQualified =
"<h2>Item Seller is not qualified</h2>"
"Sorry, you cannot send a Gift Alert for this item because the item's seller "
"does not have a feedback score of %d or more. "
"Please select an item whose seller has a feedback score of at least %d.";

static const char *ErrorMsgSenderNotHighBidder =
"<h2>Ineligible item</h2>"
"Sorry, you cannot send a Gift Alert for this item because you are not a "
"winning bidder of this item. ";


// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgGenericError =
"<h2>An Error Occurred</h2>"
"Sorry, your Gift Alert request cannot be processed. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.";
*/

static const char *ErrorMsgOccasionNotSelected =
"<h2>Gift Occasion Not Selected</h2>"
"Sorry, you did not select a Gift Occasion. Please go back and select a Gift Occasion.";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgOccasionNotSupported =
"<h2>Gift Occasion Not Supported</h2>"
"Sorry, the Gift Occasion you selected is not currently supported. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.";
*/

// NOTE:  the next two are used as format strings for sprintf()!!!
static const char *ErrorMsgOpenDateTooLate =
"<h2>Open Date is too late</h2>"
"Sorry, the gift card and gift item will not be viewable after %s. "
"Please go back and enter a date earlier than %s.";

static const char *ErrorMsgRequestTooLate =
"<h2>Gift Alert Request is too late</h2>"
"Sorry, the last day to request a gift alert for item "
"<font color=\"green\">%d</font> was %s.";

//
// SendGiftAlert
//
void clseBayApp::SendGiftAlert(CEBayISAPIExtension *pThis,
							   char *pUserId,
							   char *pPass,
							   char *pFromName,
							   char *pItemNo,
							   char *pToName,
							   char *pDestEmail,
							   char *pMessage,
							   int occasion,
							   char *pOpenMonth,
							   char *pOpenDay,
							   char *pOpenYear)							  
{
	// email
	clsMail	*	pMail;
	ostream	*	pMailStream;
	int			mailRc;
	char		subject[512];

	char * 		pURLUserId;			// user ID of sender, as it appears in URL
	char * 		pURLFromName;		// name of sender, as it appears in URL
	char * 		pURLToName;			// name of recipient, as it appears in URL
	char		cGiftCardURL[512];	// gift card URL, as it appears in the email

	// date/time
	struct tm	tmOpenDate;
	struct tm *	pTmEndOpenDate;
	struct tm *	pTmCurrentDate;

	time_t		now;
	time_t		openDate;
	time_t		endOpenDate;

	char		cOpenDate[32];

	int			month;
	int			day;
	int			year;
	int			currentYear;
	char		pStartOpenDate[64];
	char		pEndOpenDate[64];

	// encoding
	const char *pEncodedItem;
	const char *pEncodedOpenDate;

	// errors
	char		errorMsg[512];

	// item
	clsItem *	pItem;
	int			item;

	// seller
	clsUser *	pSeller;
	clsFeedback *pFeedback;

	// occasions
	clsGiftOccasion	*	pOccasion = NULL;
	clsGiftOccasions *	pOccasions = NULL;

	// encoding
	clsBase64 *pItemEncoder = NULL;
	clsBase64 *pDateEncoder = NULL;

	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Whatever happens, we need a title
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Gift Alert Confirmation"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	// And a heading for it all..
	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	// Is the sender's user ID specified???
	if(FIELD_OMITTED(pUserId))
	{
		*mpStream	<<	ErrorMsgOmittedSenderUserId
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Is the sender's password specified???
	if(FIELD_OMITTED(pPass))
	{
		*mpStream	<<	ErrorMsgOmittedSenderPassword
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Is the sender's name specified???
	if(FIELD_OMITTED(pFromName))
	{
		*mpStream	<<	ErrorMsgOmittedSenderName
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Is the sender's name too long???
	if(strlen(pFromName) > 32)
	{
		*mpStream	<<	ErrorMsgSenderNameTooLong
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Is the item number specified???
	if(FIELD_OMITTED(pItemNo))
	{
		*mpStream	<<	ErrorMsgOmittedItem
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Is the sender's name specified???
	if(FIELD_OMITTED(pToName))
	{
		*mpStream	<<	ErrorMsgOmittedRecipientName
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Is the recipient's name too long???
	if(strlen(pToName) > 32)
	{
		*mpStream	<<	ErrorMsgRecipientNameTooLong
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (occasion < 0)
	{
		*mpStream	<<	ErrorMsgOccasionNotSelected
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Is the gift's open date completely specified???
	if (FIELD_OMITTED(pOpenMonth) ||
		FIELD_OMITTED(pOpenDay) ||
		FIELD_OMITTED(pOpenYear))
	{
		*mpStream	<<	ErrorMsgIncompleteOpenDate
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Parse the date.
	month = atoi(pOpenMonth);
	day = atoi(pOpenDay);
	year = atoi(pOpenYear);
	if (month == 0 || day == 0 || year == 0)
	{
		*mpStream	<<	ErrorMsgIncompleteOpenDate
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Get current date.
	now = time(0);
	pTmCurrentDate = localtime(&now);
	currentYear = pTmCurrentDate->tm_year;

	// Convert to a numeric value.
	memset(&tmOpenDate, 0, sizeof(tmOpenDate));
	tmOpenDate.tm_mday = day;
	tmOpenDate.tm_mon = month - 1;
	if (year >= currentYear)	// later in current century?
	{
		tmOpenDate.tm_year = year - 1900;
	}
	else	// next century
	{
		tmOpenDate.tm_year = (year - 1900) + 100;
	}
	openDate = mktime(&tmOpenDate);

	// Make sure it all worked
	if (openDate == (time_t)-1)
	{
		*mpStream	<<	ErrorMsgInvalidOpenDate
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Make a copy of the open date so we can change the output format.
	strftime(pStartOpenDate, sizeof(pStartOpenDate), "%A, %B %d, %Y", &tmOpenDate);

	item = atoi(pItemNo);

	// Get the item and check it
	pItem = mpItems->GetItem(item);
	if (pItem == NULL)
	{
		*mpStream	<<	ErrorMsgInvalidItem
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}		

	// Make sure the auction is over.
	if (now < pItem->GetEndTime())
	{
		*mpStream	<<	ErrorMsgActiveItem
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Make sure item's seller is valid.
	pSeller = mpUsers->GetUser(pItem->GetSeller());
	if (pSeller == NULL)
	{
		*mpStream	<<	ErrorMsgInvalidSeller
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Make sure item's seller qualifies with feedback score >= MinSellerFeedbackScore.
	pFeedback = pSeller->GetFeedback();
	if (pFeedback == NULL || pFeedback->GetScore() < MinSellerFeedbackScore)
	{
		sprintf(errorMsg, ErrorMsgSellerNotQualified, MinSellerFeedbackScore, MinSellerFeedbackScore);
		*mpStream	<<	errorMsg
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Set last date recipient can view gift (30 days after EOA).
	endOpenDate = pItem->GetEndTime() + (60 * 60 *24 *30);

	pTmEndOpenDate = localtime(&endOpenDate);
	if (pTmEndOpenDate == NULL)
	{
	//  *mpStream	<<	ErrorMsgGenericError
	
	// kakiyama 07/08/99
	
		*mpStream   << clsIntlResource::GetFResString(-1,
							"<h2>An Error Occurred</h2>"
							"Sorry, your Gift Alert request cannot be processed. "
							"Please report this to "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL)


					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Is it too late to send the gift alert?
	if (time(0) >= endOpenDate)
	{
		strftime(pEndOpenDate, sizeof(pEndOpenDate), "%B %d, %Y", pTmEndOpenDate);
		sprintf(errorMsg, ErrorMsgRequestTooLate, item, pEndOpenDate);
		*mpStream	<<	errorMsg
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Make sure open date is before or on this date
	if (openDate > endOpenDate)
	{
// petra		strftime(pEndOpenDate, sizeof(pEndOpenDate), "%m/%d/%Y", pTmEndOpenDate);
		clseBayTimeWidget timeWidget (mpMarketPlace, 1, -1, endOpenDate);	// petra
		timeWidget.EmitString (pEndOpenDate);	// petra
		sprintf(errorMsg, ErrorMsgOpenDateTooLate, pEndOpenDate, pEndOpenDate);
		*mpStream	<<	errorMsg
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	strftime(pEndOpenDate, sizeof(pEndOpenDate), "%A, %B %d, %Y", pTmEndOpenDate);

	// Validate the requestor.
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);

	// If we didn't get the user, we're done
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();
		return;
	}

	// Is the destination e-mail address specified???
	// If not, send the gift alert to the requestor.
	if(FIELD_OMITTED(pDestEmail))
	{
		pDestEmail = mpUser->GetEmail();
	}

	// Remove the space in pDestEmail and convert it to lower case
	if(!ValidateEmail(pDestEmail))
	{
		*mpStream	<<	ErrorMsgInvalidDestinationEmail
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Make sure the requestor is a high bidder of this item.
	if (pItem->GetAuctionType() == AuctionChinese) 
	{
		if (mpUser->GetId() != pItem->GetHighBidder())
		{
			*mpStream	<<	ErrorMsgSenderNotHighBidder
						<<	"<br><br>"
						<<	mpMarketPlace->GetFooter();
			CleanUp();
			return;
		}
	}
	else if (pItem->GetAuctionType() == AuctionDutch)
	{
		if (!pItem->IsDutchHighBidder(mpUser->GetId()))
		{
			*mpStream	<<	ErrorMsgSenderNotHighBidder
						<<	"<br><br>"
						<<	mpMarketPlace->GetFooter();
			CleanUp();
			return;
		}
	}

	// For email stuff
	pMail	= new clsMail();
	pMailStream	= pMail->OpenStream();

	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	// Get the active occasions from the database.
	pOccasions = new clsGiftOccasions;
	pOccasion = pOccasions->GetGiftOccasion(mpMarketPlace->GetId(), occasion);

	// Emit an error if this occasion is not in the database.
	if (pOccasion == NULL)
	{
	//	*mpStream	<<	ErrorMsgGenericError

	// kakiyama 07/08/99

		*mpStream   << clsIntlResource::GetFResString(-1,
							"<h2>An Error Occurred</h2>"
							"Sorry, your Gift Alert request cannot be processed. "
							"Please report this to "
							"<a href=\"%{1:GetHTMLPath}/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL)

					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		delete pOccasion;
		delete pOccasions;
		CleanUp();
		return;
	}

	// Emit an error if this occasion is not currently supported.
	if (!pOccasion->IsActive())
	{
	//	*mpStream	<<	ErrorMsgOccasionNotSupported

	// kakiyama 07/08/99

		*mpStream   << clsIntlResource::GetFResString(-1,
						        "<h2>Gift Occasion Not Supported</h2>"
							"Sorry, the Gift Occasion you selected is not currently supported. "
							"Please report this to "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL)

					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		delete pOccasion;
		delete pOccasions;
		CleanUp();
		return;
	}

	// Greet the recipient by name, if possible.
	*pMailStream << pOccasion->GetGreeting()
				 << ", "
				 << pToName
				 << "!"
				 << "\n"
				 << "\n";

	if (!FIELD_OMITTED(pMessage))
	{
		// Include the sender's personal message
		*pMailStream <<	pMessage;

		// Sign the email with the sender's name
		*pMailStream << "\n\n"
					 << "-- "
					 << pFromName
					 << "\n";
	}

	// Make the sender's user ID URL-safe by replacing any '&' chars
	pURLUserId = clsUtilities::MakeSafeString(pUserId);

	// Make the sender's name URL-safe by replacing any '&' chars
	pURLFromName = clsUtilities::MakeSafeString(pFromName);

	// Replace any spaces with underscores.
	clsUtilities::ReplaceSpacesWithUnderscores(pURLFromName);

	// Make the recipient's name URL-safe by replacing any '&' chars
	pURLToName = clsUtilities::MakeSafeString(pToName);

	// Replace any spaces with underscores.
	clsUtilities::ReplaceSpacesWithUnderscores(pURLToName);

	// Encode the item number and open date so the recipient can't
	// change them
	pItemEncoder = new clsBase64;
	pEncodedItem = pItemEncoder->Encode(pItemNo, strlen(pItemNo));

	sprintf(cOpenDate, "%d", openDate);
	pDateEncoder = new clsBase64;
	pEncodedOpenDate = pDateEncoder->Encode(cOpenDate, strlen(cOpenDate));

	memset(cGiftCardURL, 0, sizeof(cGiftCardURL));
	sprintf(cGiftCardURL,
			"%seBayISAPI.dll?ViewGiftCard2&uid=%s&sn=%s&rn=%s&iid=%s&od=%s&occ=%d",
			mpMarketPlace->GetCGIPath(PageViewGiftCard),
			pURLUserId,
			pURLFromName,
			pURLToName,
			pEncodedItem,
			pEncodedOpenDate,
			occasion);

	delete pItemEncoder;
	delete pDateEncoder;

	*pMailStream << "\n"
				 << "To see what "
				 <<	pFromName
				 << " bought for you on the "
				 <<	mpMarketPlace->GetCurrentPartnerName()
				 << " web site, please copy and paste the following URL "
					"(one line at a time AND eliminating any blank spaces) "
					"into the URL address or location bar of your browser:"
				 << "\n\n\t"
				 << cGiftCardURL
				 << "\n\n";

	// Tell the recipient how long the URL is valid.
	*pMailStream << "You can view your gift anytime between "
				 << pStartOpenDate
				 << " and "
				 << pEndOpenDate
				 << "."
				 << "\n"
				 << "\n";

	// Tell the recipient how long the URL is valid.
	*pMailStream << "Please contact "
				 << pFromName
				 << " for details on how you will receive your gift."
				 << "\n";

	// Add the eBay tag line at the bottom
	*pMailStream <<	"\n\n\tVisit eBay, the world's largest Personal Trading Community at"
//				 << " http://www.ebay.com";
// kakiyama 07/16/99
				 << mpMarketPlace->GetHTMLPath();




	// format the subject line
	if (occasion != 0)
	{
		sprintf(subject, "A %s gift for you on %s!",
				pOccasion->GetName(), mpMarketPlace->GetCurrentPartnerName());
	}
	else
	{
		sprintf(subject, "A special gift for you on %s!", mpMarketPlace->GetCurrentPartnerName());
	}

	// send the gift alert
	mailRc = pMail->Send(pDestEmail, mpUser->GetEmail(), subject);

	// We don't need mail now
	delete	pMail;

	// handle send errors
	if (!mailRc)
	{
		*mpStream <<	"<h2>Unable to send gift alert</h2>"
						"Sorry, we could not send the gift alert via e-mail. "
						"Please check the e-mail address to ensure it "
						"is valid and try again. If the e-mail address is valid, "
						"the destination machine may be having problems sending "
						"or receiving mail; you may wish to contact the appropriate "
						"service provider. "
						"<br>"
						"\n";
	}
	else
	{
		*mpStream <<	"<h2>Your gift alert has been sent!</h2>"
				  <<	"Your gift alert has been sent to "
				  <<	"<font color=\"green\">"
				  <<	pDestEmail
				  <<	"</font>"
				  <<	".";

		*mpStream <<	"<p>Click "
				  <<	"<a href=\""
				  <<	cGiftCardURL
				  <<	"\">here</a> to view the gift card "
				  <<	"that the recipient will see.";

		*mpStream << "<br>"	
				  << "\n";
	}

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>"
					"\n";

	delete pItem;
	delete pSeller;

	delete pOccasion;
	delete pOccasions;

	delete [] pURLUserId;
	delete [] pURLFromName;
	delete [] pURLToName;

	CleanUp();

	return;
}
