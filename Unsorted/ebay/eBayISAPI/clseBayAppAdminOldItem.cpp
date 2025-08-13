/*	$Id: clseBayAppAdminOldItem.cpp,v 1.10.120.1 1999/08/01 02:51:47 barry Exp $	*/
//
//	File:		clseBayAppAdminOldItem.cc
//
//	Class:		clseBayApp
//
//	Author:		tini widjojo (tini@ebay.com)
//
//	Function:
//		Displays the archived item given an item number
//
//	Modifications:
//				- 12/01/97 tini	- Created
//				- 01/07/98 charles changes for privacy User ID
//				- 05/05/99 gurinder modified AdminViewOldItem function
//				  to use new widgets when displaying the item.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()


#include "ebihdr.h"

//Widget classes
#include "clsUserIdWidget.h"
#include "clseBayItemDetailWidget.h"
#include "clsBidBoxWidget.h"
#include "clseBayTimeWidget.h"

bool clseBayApp::GetAndCheckOldItem(char *pItemNo)
{
	int		item;

	// Ok, let's get started
	if (pItemNo)
	{
		item	= atoi(pItemNo);
		mpItem	= mpItems->GetOldItem(item);
	}

	// If we did't get the item, then put out a 
	// nice error message.
	if (!mpItem || item == 0)
	{
		*mpStream <<	"<HTML>"
						"<HEAD>"
						"<TITLE>"
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" - Invalid Item"
						"</TITLE>"
						"</HEAD>";

		*mpStream <<	mpMarketPlace->GetHeader();

		*mpStream <<	"<p>"
						"<H2>"
						"Item \""
				  <<	pItemNo
				  <<	"\" is invalid or could not be found."
						"</H2>"
						"<p>"
						"Please go back and try again.";

		*mpStream <<	mpMarketPlace->GetFooter();

		*mpStream <<	flush;
		return false;
	}

	return true;
}

//- Gurinder 05/05/99
void clseBayApp::AdminViewOldItem(CEBayISAPIExtension *pServer, char *pItemNo,
								eBayISAPIAuthEnum authLevel)
{
	// Time fields
// petra	time_t  startTime;
// petra	time_t	endTime;
// petra	struct tm *timeAsTm;
// petra	char	titleEndTime[96];
// petra	TimeZoneEnum		timeZone;
	//end

	// Used to set the page's expiration to now + 5 minutes
	int			rc;
	time_t		nowTime;
	time_t		expirationTime;
	struct tm	*pExpirationTimeAsTM;
	char		expiresHeader[128];
	
	
	// Item details
	clseBayItemDetailWidget *idw;
	
	// Setup
	SetUp();
	
	// Let's try and get the item
	if (!GetAndCheckOldItem(pItemNo))
	{
		CleanUp();
		return;
	}

	clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), 0); // set below

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	//start time
// petra	startTime = mpItem->GetStartTime();
	

	// end time
// petra	endTime		= mpItem->GetEndTime();
// petra	timeAsTm	= localtime(&endTime);

// petra	timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra	endTimeWidget.SetTime(endTime);
// petra	endTimeWidget.SetTimeZone(timeZone);

	
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
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" item "
			  <<	pItemNo
			  <<	" (Ends ";
	//samuel au, 4/6/99
	clseBayTimeWidget endTimeWidget(mpMarketPlace, 1, 2, mpItem->GetEndTime());	// petra
	endTimeWidget.EmitHTML(mpStream);
	//		  <<	titleEndTime
	//end
	*mpStream <<	") - "
			  <<	mpItem->GetTitle()
			  <<	"</TITLE>"
					"</HEAD>";


	*mpStream <<	mpMarketPlace->GetHeader();

	// Use clseBayItemDetailWidget to show auction properties
	idw = new clseBayItemDetailWidget(mpMarketPlace);
	idw->SetItem(mpItem);
	idw->SetShowTitleBar(false);
	idw->SetIsViewOldItemPage(true);
	idw->SetShowDescription(true);
	idw->SetColor("#99CCCC");
	idw->SetMode(clseBayItemDetailWidget::Generic);
	idw->EmitHTML(mpStream);
	delete idw;

	

	*mpStream << mpMarketPlace->GetFooter()
			  << flush;

	CleanUp();
	return;

}
//end 05/05/99 - gurinder