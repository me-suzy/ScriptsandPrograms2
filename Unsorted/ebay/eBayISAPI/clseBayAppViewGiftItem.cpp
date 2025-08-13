/*	$Id: clseBayAppViewGiftItem.cpp,v 1.3.358.3 1999/08/05 20:42:26 nsacco Exp $	*/
//
//	File:	clseBayAppViewGiftItem.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppViewGiftItem.cpp
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//		Contains the methods used to display a gift item.
//
// Modifications:
//				- 10/24/98 mila		- Created
//				- 11/03/98 mila		- Modified to get occasion info from database
//									  instead of from static table; added marketplace
//									  ID to clsGiftOccasion
//				- 11/05/98 mila		- Added base64-decoding of item number and open date;
//									  note that code assumes that both are base64-encoded
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clseBayGiftGraphicsWidget.h"
#include "clseBayGiftItemWidget.h"
#include "clsUser.h"
#include "clsGiftOccasion.h"
#include "clsGiftOccasions.h"
#include "clsBase64.h"

static const char *bgGiftItemTitle = "#99cccc";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgGenericError =
"<h2>An Error Occurred</h2>"
"Sorry, your Gift cannot be viewed. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.";
*/

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgInvalidOpenDate =
"<h2>Gift Open Date is invalid</h2>"
"Sorry, the specified Gift Open Date is invalid. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.";
*/

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgInvalidItemNumber =
"<h2>Item Number is invalid</h2>"
"Sorry, the item number is invalid. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.";
*/

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
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>. "
"if you have a question about this problem.";
*/

//
// GetAndShowGiftItem
//
//	This routine emits the header, gift item, and footer
//
void clseBayApp::GetAndShowGiftItem(clsUser *pFromUser, 
									char *pFromName, 
									int item,
									clsGiftOccasion *pOccasion,
									time_t openDate)
{
	// widgets
	clseBayGiftGraphicsWidget *	pGraphicsWidget;
	clseBayGiftItemWidget *		pGiftItemWidget;

	// item
	clsItem *	pItem;

	// date stuff
	struct tm *	pTmDate;
	char		dateString[32];

	// misc
	char *		pSenderName = NULL;
	char *		pTemp = NULL;
	int			length = 0;


	if (!pFromUser || item == 0)
		return;

	*mpStream	<<	"<p><p><p>\n";

	// let's put the widgets in a column
	*mpStream	<<	"<center>\n"
					"<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\">\n"
					"  <tr>\n"
					"    <td align=\"center\">";

	// create and display header graphics
	pGraphicsWidget = new clseBayGiftGraphicsWidget(mpMarketPlace);
	pGraphicsWidget->SetImageFilename(pOccasion->GetHeader());
	pGraphicsWidget->EmitHTML(mpStream);
	// don't delete the widget yet, cuz we'll use it again 
	// for the footer graphics!

	*mpStream	<<	"<br>\n";

	*mpStream	<<	"    </td>\n"
					"  </tr>\n"
					"  <tr>\n"
					"    <td align=\"center\">";

	if (item > 0 && openDate <= time(0))
	{
		// get the item
		pItem = mpItems->GetItem(item, true);
		if (pItem != NULL)
		{
			// Create and display gift item
			pGiftItemWidget = new clseBayGiftItemWidget(mpMarketPlace);
			pGiftItemWidget->SetItem(pItem);
			pGiftItemWidget->SetColor((char *)bgGiftItemTitle);
			pGiftItemWidget->EmitHTML(mpStream);
			delete pGiftItemWidget;
		}
	}
	else
	{
		// Format date string
		pTmDate	= localtime(&openDate); //yp
		strftime(dateString, sizeof(dateString), "%B %d, %Y", pTmDate);

		// Replace any underscores in sender name with spaces
		if (pFromName != NULL)
		{
			// Make copy of sender name so we can change it
			length = strlen(pFromName);
			pSenderName = new char[length+1];
			strcpy(pSenderName, pFromName);

			// Remove start & end quotes if they were provided
			if ((length > 1) && (pSenderName[0]=='\"' && pSenderName[length-1]=='\"'))
			{
				pSenderName[length-1]='\0';	// remove ending "
				pSenderName++;					// remove beginning "
			}

			// Replace any underscores with spaces
			pTemp = pSenderName;
			while ((pTemp = strchr(pTemp, '_')) != NULL)
			{
				*pTemp = ' ';
			}
		}

		// No peeking!
		*mpStream	<<	"<h2><font color=\"red\">No Peeking!</font></h2>";

		// Explain why...
		*mpStream	<<	"<p>"
					<<	"<font color=\"#000000\">";

		if (pSenderName != NULL)
		{
			*mpStream	<<	pSenderName;
		}
		else
		{
			*mpStream	<<	"The sender";
		}

		*mpStream	<<	" requests that you not open your gift until "
					<<	dateString
					<<	"."
					<<	"<br>"
					<<	"Thank you!"
					<<	"</font>";
	}

	// Transition to next row in table
	*mpStream	<<	"    </td>\n"
					"  </tr>\n"
					"  <tr>\n"
					"    <td align=\"center\">";

	*mpStream	<<	"<br>\n";

	// Create and display footer graphics
	pGraphicsWidget = new clseBayGiftGraphicsWidget(mpMarketPlace);
	pGraphicsWidget->SetImageFilename(pOccasion->GetFooter());
	pGraphicsWidget->EmitHTML(mpStream);

	*mpStream	<<	"    </td>\n"
					"  </tr>\n"
					"</table>\n"
					"</center>\n";

	// now we're done with the graphics widget
	delete pGraphicsWidget;

	return;

}

//
// ViewGiftItem
//
void clseBayApp::ViewGiftItem(CEBayISAPIExtension *pThis,
							  char *pFromUserId,
							  char *pFromName,
							  char *pItemNo,
							  int occasion,
							  char *pOpenDate)
{	
	// occasions
	clsGiftOccasion	*	pOccasion;
	clsGiftOccasions *	pOccasions;

	// decoding
	const char *		pDecodedItemNo;
	const char *		pDecodedOpenDate;
	clsBase64 *			pBase64;
	int					length;

	int					item = 0;
	time_t				openDate = 0;


	SetUp();

	// title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" View Gift Item from "
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
	//	*mpStream << ErrorMsgInvalidItemNumber

	// kakiyama 07/12/99

		*mpStream << clsIntlResource::GetFResString(-1,
							"<h2>Item Number is invalid</h2>"
							"Sorry, the item number is invalid. "
							"Please report this to "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL)
				  << "<br><br>"
				  << mpMarketPlace->GetFooter();
		delete pBase64;
		CleanUp();
		return;
	}

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
	//	*mpStream	<<	ErrorMsgInvalidOpenDate

	// kakiyama 07/12/99

		*mpStream   << clsIntlResource::GetFResString(-1,
							"<h2>Gift Open Date is invalid</h2>"
							"Sorry, the specified Gift Open Date is invalid. "
							"Please report this to "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL)

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
	//	*mpStream	<<	ErrorMsgGenericError
	
	// kakiyama 07/12/99

		*mpStream   << clsIntlResource::GetFResString(-1,
							"<h2>An Error Occurred</h2>"
							"Sorry, your Gift cannot be viewed. "
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

	// Emit an error if this occasion is not currently supported.
	if (!pOccasion->IsActive())
	{
	//	*mpStream	<<	ErrorMsgOccasionNotSupported

	// kakiyama 07/12/99

		*mpStream   << clsIntlResource::GetFResString(-1,
							"<h2>Gift Occasion Not Supported</h2>"
							"Sorry, the Gift Occasion you selected is not currently supported. "
							"Please report this to "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>. "
							"if you have a question about this problem.",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL)
					<<	"<br><br>"
					<<	mpMarketPlace->GetFooter();
		delete pOccasion;
		delete pOccasions;
		CleanUp();
		return;
	}

	GetAndShowGiftItem(mpUser, pFromName, item, pOccasion, openDate);

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	delete pOccasion;
	delete pOccasions;

	CleanUp();

	return;
}

