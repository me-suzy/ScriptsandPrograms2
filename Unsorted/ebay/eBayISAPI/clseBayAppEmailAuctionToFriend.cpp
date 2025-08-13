/*	$Id: clseBayAppEmailAuctionToFriend.cpp,v 1.11.152.2 1999/08/05 18:58:55 nsacco Exp $	*/
//
//	File:		clseBayAppEmailAuctionToFriend.cpp
//
//	Class:		clseBayAppEmailAuctionToFriend
//
//	Author:		Craig Huang (chuang@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 03/01/98 Craig	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clseBayTimeWidget.h"

static const char *ErrorMsgOmittedEmailOfFriend =
"<h2>The e-mail address of your friend is omitted or invalid</h2>"
"Sorry, the e-mail address of your friend is invalid. "
"Please go back and try again.";

//
// AcceptBid
//
void clseBayApp::EmailAuctionToFriend(CEBayISAPIExtension *pThis,
							  int item,
							  char *userid,
							  char *password,
							  //char *friendname,
							  char *email,
							  char *message,
							  char *htmlenable)						   				  							   							  						  					  
{
		// Just so we can email the user
	clsMail		*pMail;
	ostream	*pMailStream;
	int			mailRc;
	char		subject[512];
// petra	struct tm *timeAsTm;
// petra	char	cStartTime[96];
// petra	char	cEndTime[96];
		// Time fields
// petra	time_t	startTime;
// petra	time_t	endTime;
	clsUser *pSeller;
	char*	pTemp;

// petra	clseBayTimeWidget	startTimeWidget;
// petra	clseBayTimeWidget	endTimeWidget;
// petra	TimeZoneEnum		timeZone;

	SetUp();
	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" E-mail Auction To A Friend Confirmation"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	// And a heading for it all


	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";



	// validate the requestor
	mpUser	= mpUsers->GetAndCheckUserAndPassword(userid, password, mpStream);
	// If we didn't get the user, we're done
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();
		return;
	}

	// Get the item and check it
	if (!GetAndCheckItem(item))
	{
		CleanUp();
		return;
	}		


	// Is the field specified ???
	// Remove the space in pEmail and convert it to lower case
	if( FIELD_OMITTED(email) || !ValidateEmail(email) )
	{
		*mpStream	<<	ErrorMsgOmittedEmailOfFriend
					<<	"<BR><BR>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	*mpStream <<	"<h2>"			  
		  <<	"Your e-mail to a friend has been sent!"
		  <<	"</h2>";
	// For email stuff
	pMail	= new clsMail;
	pMailStream	= pMail->OpenStream();
	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	//samuel au, 4/6/99
	// set timeZone here
// petra	timeZone = mpMarketPlace->GetCurrentTimeZone();
	//end

	// First, we convert the times to nice strings
	//  start time
// petra	startTime	= mpItem->GetStartTime();
// petra	timeAsTm	= localtime(&startTime);

	//samuel au, 4/6/99
// petra	startTimeWidget.SetTime(startTime);
// petra	startTimeWidget.SetTimeZone(timeZone);
	//end

// petra	if (timeAsTm)
// petra	{
// petra		if (timeAsTm->tm_isdst)
// petra		{
// petra			strftime(cStartTime, sizeof(cStartTime),
// petra					 "%m/%d/%y %H:%M:%S PDT ",
// petra					  timeAsTm);
// petra		}
// petra		else
// petra		{
// petra			strftime(cStartTime, sizeof(cStartTime),
// petra					 "%m/%d/%y %H:%M:%S PST ",
// petra					  timeAsTm);
// petra		}
// petra		
// petra	}
// petra	else
// petra		strcpy(cStartTime, "(*Error*)");
// petra
	// end time
// petra	endTime		= mpItem->GetEndTime();
// petra	timeAsTm	= localtime(&endTime);

	//samuel au, 4/6/99
// petra	endTimeWidget.SetTime(endTime);
// petra	endTimeWidget.SetTimeZone(timeZone);
	//end

// petra	if (timeAsTm)
// petra	{
// petra		if (timeAsTm->tm_isdst)
// petra		{
// petra			strftime(cEndTime, sizeof(cEndTime),
// petra					 "%m/%d/%y %H:%M:%S PDT ",
// petra					  timeAsTm);
// petra		}
// petra		else
// petra		{
// petra			strftime(cEndTime, sizeof(cEndTime),
// petra					 "%m/%d/%y %H:%M:%S PST ",
// petra					  timeAsTm);
// petra		}	
// petra	}
// petra	else
// petra		strcpy(cEndTime, "(*Error*)");
	
	if( !FIELD_OMITTED(message) ) 
	{
	*pMailStream <<	message
				 <<	"\n";
	}			 
	

	//Get the seller email
	pSeller = mpUsers->GetUser(mpItem->GetSeller());	

	*pMailStream <<	"\n"				 
					"Title of item:\t"
				 <<	mpItem->GetTitle()
				 <<	"\n"
					"Seller:\t"
				 <<	pSeller->GetEmail() 
				 <<	"\n"
				 << "Starts:\t";

	clseBayTimeWidget timeWidget (mpMarketPlace,					// petra
									EBAY_TIMEWIDGET_MEDIUM_DATE,	// petra
									EBAY_TIMEWIDGET_LONG_TIME,		// petra
									mpItem->GetStartTime() );		// petra
	//samuel au, 4/6/99
	timeWidget.EmitHTML(pMailStream);

	*pMailStream << "\n"	
				 << "Ends:\t";

	timeWidget.SetTime (mpItem->GetEndTime() );		// petra
	//samuel au, 4/6/99
	timeWidget.EmitHTML(pMailStream);

	*pMailStream << "\n";	
	delete pSeller;			 
	
	// For price stuff
	*pMailStream <<	"Price:\t";					
	if (mpItem->GetBidCount() > 0 && mpItem->GetPrice() > 0)
	{
		// show current/lowest bid because there are bids
		*pMailStream <<	((mpItem->GetQuantity() > 1) ? 				 
						"Lowest" :
						"Currently ");

		clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), mpItem->GetPrice());
// petra		currencyWidget.SetForMail(true);
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	"\n";
	}
	else
	{
		// show starting bid because there are no bids yet
		*pMailStream <<	"Starts at ";

		clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), mpItem->GetStartPrice());
// petra		currencyWidget.SetForMail(true);
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	"\n";
	}
				 

	// URL to bid
	*pMailStream << "To bid on the item, go to:\t"
				 << mpMarketPlace->GetCGIPath(PageViewItem)
				 << "eBayISAPI.dll?ViewItem&"
				 <<	"item="
				 << item
				 <<	"\n"
	 			 <<	"\n"
				 <<	"\n";

	//Description last
	if( strcmp(htmlenable, "default") ==0)
	{
		pTemp = clsUtilities::RemoveHTMLTag(mpItem->GetDescription());
		*pMailStream 
				 << "Item Description:		\n\t"
				 <<	pTemp
				 <<	"\n";
		delete pTemp;
	}
	else
		*pMailStream 
				 << "Item Description:		\n\t"
				 <<	mpItem->GetDescription()
				 <<	"\n";
	
	*pMailStream 
				 <<	"\n\tVisit eBay, the world's largest Personal Trading Community at"
	//			 << " http://www.ebay.com";
	// kakiyama 07/16/99
				 << mpMarketPlace->GetHTMLPath();



	sprintf(subject,
			"Interesting item on %s web site item#%d: %s",
			mpMarketPlace->GetCurrentPartnerName(),
			mpItem->GetId(),
			mpItem->GetTitle());

	mailRc =	pMail->Send(email,	
							mpUser->GetEmail(),
							subject);

	// We don't need no mail now
	delete	pMail;

	if (!mailRc)
	{
		*mpStream <<	"<h2>Warning: Unable to send auction item notice</h2>"
						"Sorry, we could not send the item information to your friend "
						"via e-mail. Please check your friend's e-mail to ensure it "
						"is valid and try again. If the e-mail is valid, "
						"your friend may be having problems sending "
						"or receiving mail; you may wish to contact his/her service provider. "
						"<br>";
	}
	else
	{
		*mpStream << "<br>"	
				  << "\n";
	}

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";
	CleanUp();
	return;
}
