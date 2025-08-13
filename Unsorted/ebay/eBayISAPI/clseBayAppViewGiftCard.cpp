/*	$Id: clseBayAppViewGiftCard.cpp,v 1.4.358.3 1999/08/05 20:42:25 nsacco Exp $	*/
//
//	File:	clseBayAppViewGiftCard.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppViewGiftCard.cpp
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//		Contains the methods used to display
//		a gift card.
//
// Modifications:
//				- 10/24/98 mila		- Created
//				- 11/03/98 mila		- Modified to get occasion info from database
//									  instead of from static table; added marketplace
//									  ID to clsGiftOccasion
//				- 11/05/98 mila		- Added base64-decoding of item number and open date;
//									  note that code assumes that both are base64-encoded
//				- 01/28/99 mila		- Fixed memory leaks
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clseBayGiftGraphicsWidget.h"
#include "clseBayGiftCardWidget.h"
#include "clsGiftOccasion.h"
#include "clsGiftOccasions.h"
#include "clsUser.h"
#include "clsBase64.h"

static const char *pGiftImageFile = "gift-card-man.gif";

static const char *ErrorMsgGenericError =
"<h2>An Error Occurred</h2>"
"Sorry, your Gift Card cannot be viewed. ";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgInvalidOpenDate =
"<h2>Gift Open Date is invalid</h2>"
"Sorry, the specified Gift Open Date is invalid. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.";
*/

static const char *ErrorMsgInvalidItemNumber =
"<h2>Item Number is invalid</h2>"
"Sorry, the item number is invalid. "
"Please go back and try again.";

static const char *ErrorMsgInvalidItem =
"<h2>Item Number is invalid</h2>"
"Sorry, the item is invalid. "
"Please go back and try again.";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgOccasionNotSupported =
"<h2>Gift Occasion Not Supported</h2>"
"Sorry, the Gift Occasion you selected is not currently supported. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.";
*/

//
// GetAndShowGiftCard
//
//	This routine emits the header, gift card, and footer
//
void clseBayApp::GetAndShowGiftCard(clsUser *pFromUser, 
									char *pFromName,
									char *pToName,
									int item,
									clsGiftOccasion *pOccasion,
									time_t openDate)
{
	// widgets
	clseBayGiftGraphicsWidget	*pGraphicsWidget;
	clseBayGiftCardWidget		*pCardWidget;

	// names
	char *pSender;
	char *pRecipient;

	if (!pFromUser || item == 0)
		return;

	*mpStream	<<	"<p><p><p>\n";

		// let's put the widgets in a column
	*mpStream	<<	"<center>\n"
					"<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\">\n"
					"<tr>\n"
					"<td align=\"center\">";

	// create and display header graphics
	pGraphicsWidget = new clseBayGiftGraphicsWidget(mpMarketPlace);
	pGraphicsWidget->SetImageFilename(pOccasion->GetHeader());
	pGraphicsWidget->EmitHTML(mpStream);
	// don't delete the widget yet, cuz we'll use it again 
	// for the footer graphics!

	*mpStream	<<	"</td>\n"
					"</tr>\n"
					"<tr>\n"
					"<td align=\"center\">";

	if (pFromName != NULL)
	{
		// make copy of sender name so we can change it
		pSender = new char[strlen(pFromName)+1];
		strcpy(pSender, pFromName);

		// remove any delimiting quotes and change underscores to spaces
		clsUtilities::RemoveDelimitingQuotes(pSender);
		clsUtilities::ReplaceUnderscoresWithSpaces(pSender);
	}

	if (pToName != NULL)
	{
		// make copy of recipient name so we can change it
		pRecipient = new char[strlen(pToName)+1];
		strcpy(pRecipient, pToName);

		// remove any delimiting quotes and change underscores to spaces
		clsUtilities::RemoveDelimitingQuotes(pRecipient);
		clsUtilities::ReplaceUnderscoresWithSpaces(pRecipient);
	}

	// create and display gift card
	pCardWidget = new clseBayGiftCardWidget(mpMarketPlace);
	pCardWidget->SetGreeting(pOccasion->GetGreeting());
	pCardWidget->SetSenderName(pSender);
	pCardWidget->SetRecipientName(pRecipient);
	pCardWidget->SetSenderUserId(pFromUser->GetUserId());
	pCardWidget->SetItem(item);
	pCardWidget->SetOpenDate(openDate);
	pCardWidget->SetImageFilename((char *)pGiftImageFile);
	pCardWidget->SetOccasion(pOccasion->GetId());
	pCardWidget->SetEncodeItemInURL(true);
	pCardWidget->SetEncodeOpenDateInURL(true);
	pCardWidget->EmitHTML(mpStream);
	delete pCardWidget;

	*mpStream	<<	"</td>\n"
					"</tr>\n"
					"<tr>\n"
					"<td align=\"center\">";

	*mpStream	<<	"<br>\n";

	// create and display footer graphics
	pGraphicsWidget = new clseBayGiftGraphicsWidget(mpMarketPlace);
	pGraphicsWidget->SetImageFilename(pOccasion->GetFooter());
	pGraphicsWidget->EmitHTML(mpStream);

	*mpStream	<<	"</td>\n"
					"</tr>\n"
					"</table>\n"
					"</center>\n";
						
	// now we're done with the graphics widget
	delete pGraphicsWidget;

	// ...and the names
	delete [] pSender;
	delete [] pRecipient;

	return;

}

//
// ViewGiftCard
//
void clseBayApp::ViewGiftCard(CEBayISAPIExtension *pThis,
							  char *pFromUserId,
							  char *pFromName,
							  char *pToName,
							  char *pItemNo,
							  int occasion,
							  char *pOpenDate)
{	
	// occasions
	clsGiftOccasion *	pOccasion;
	clsGiftOccasions *	pOccasions;

	// decoding
	const char *		pDecodedItemNo;
	const char *		pDecodedOpenDate;
	clsBase64 *			pBase64 = NULL;
	int					length;

	int					item = 0;
	time_t				openDate = 0;


	SetUp();

	// title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" View Gift Card from "
			  <<	pFromUserId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();

	mpUser = mpUsers->GetAndCheckUser(pFromUserId, mpStream);
	if (!mpUser)
	{
		return;
	}

	if(mpUser->GetUserState() == UserUnconfirmed ||
	   mpUser->GetUserState() == UserCCVerify)
	{
		*mpStream << "<H2>User not yet confirmed </H2>"  
			      << "The User ID \""
				  << mpUser->GetUserId()
				  << " \" is not a registered eBay user.";

		*mpStream << "<p>"
			      << mpMarketPlace->GetFooter();

		return;
	}

	// Decode the item number
	pBase64 = new clsBase64;
	length = strlen(pItemNo);
	pDecodedItemNo = pBase64->Decode(pItemNo, length);
	// Don't delete the clsBase64 object yet, cuz we're not done with it...

	// Validate the item number
	item = atoi(pDecodedItemNo);
	if (item <= 0)
	{
		*mpStream << ErrorMsgInvalidItemNumber
				  << "<br><br>"
				  << mpMarketPlace->GetFooter();
		delete pBase64;
		CleanUp();
		return;
	}

	// Validate the item
	if (!GetAndCheckItem(item))
	{
		*mpStream << ErrorMsgInvalidItem
				  << "<br><br>"
				  << mpMarketPlace->GetFooter();
		delete pBase64;
		CleanUp();
		return;
	}

	// Decode the open date
	length = strlen(pOpenDate);
	pDecodedOpenDate = pBase64->Decode(pOpenDate, length);

	// Make it a number
	openDate = atoi(pDecodedOpenDate);
	if (openDate <= 0)
	{


	// kakiyama 07/12/99
		*mpStream	<<	clsIntlResource::GetFResString(-1,
								"<h2>Gift Open Date is invalid</h2>"
								"Sorry, the specified Gift Open Date is invalid. "
								"Please report this to "
								"<a href=\"{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.",
								clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
								NULL)

					<<	" (decoded open date = \""
					<<	pDecodedOpenDate
					<<	"\" and open date = \""
					<<	openDate
					<<	")"
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		delete pBase64;
		CleanUp();
		return;
	}

	// Get the active occasions from the database.
	pOccasions = new clsGiftOccasions;
	pOccasion = pOccasions->GetGiftOccasion(mpMarketPlace->GetId(), occasion);

	// Emit an error if this occasion is not in the database.
	if (pOccasion == NULL)
	{
		*mpStream	<<	ErrorMsgGenericError
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		delete pBase64;
		delete pOccasion;
		delete pOccasions;
		CleanUp();
		return;
	}

	// Emit an error if this occasion is not currently supported.
	if (!pOccasion->IsActive())
	{
	//	*mpStream	<<	ErrorMsgOccasionNotSupported

	// kakiyama 07/12/99

		*mpStream   << clsIntlResource::GetFResString(-1,
							"<h2>Gift Occasion Not Supported</h2>"
							"Sorry, the Gift Occasion you selected is not currently supported. "
							"Please report this to "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL)
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		delete pBase64;
		delete pOccasion;
		delete pOccasions;
		CleanUp();
		return;
	}

	GetAndShowGiftCard(mpUser, pFromName, pToName, item, pOccasion, openDate);

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	delete pBase64;
	delete pOccasion;
	delete pOccasions;

	CleanUp();

	return;
}

