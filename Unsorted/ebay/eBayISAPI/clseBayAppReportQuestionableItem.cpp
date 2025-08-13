//
//	File:	clseBayAppReportQuestionableItem.cpp
//
//	Class:	clseBayApp
//
//	Author:	Steve Yan (stevey@ebay.com)
//
//	Function:
//
//		report questionable item to support
//
// Modifications:
//				- 04/06/99 Steve	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

void clseBayApp::ReportQuestionableItem(CEBayISAPIExtension *pServer,
							char * pUserId,
							char * pPass,
							char * pItemType,
							int	   itemID,
							char * pMessage)
{
	int		mailRc;

	clsMail			Mail;
	ostream			*pMStream;
	char			*pEmail;


	// Setup
	SetUp();


	// Usual Title and Header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Report Questionable Item to Support"
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();


	// Let's try and get the item
	if (!GetAndCheckItem(itemID))
	{
		// Item does not exist
		*mpStream	<< "<P>"
					<<	mpMarketPlace->GetFooter()
					<< flush;
		CleanUp();
		return;
	}


	if (FIELD_OMITTED(pItemType))
	{
		// subject cannot be NULL
		*mpStream	<< "<h2>Item type is not selected</h2>"
					<< "Please select an item type."
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (FIELD_OMITTED(pMessage))
	{
		// msg cannot be NULL
		*mpStream	<< "<h2>Message cannot be empty</h2>"
					<< "Please go back and try again."
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}


	// Before we do anything, check the user again
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);

	if (!mpUser)
	{
		*mpStream  <<	"<p>"
				   <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}
	pEmail = mpUser->GetEmail();


	// We need a mail object
	pMStream	= Mail.OpenStream();

	*pMStream	<<	"This email is to report a questionable item. The item information is:\n\n";
		
	*pMStream	<<	"Item ID:\t\t\t"
				<<	itemID << "\n"
				<<	"Item Title: \t\t"
				<<	mpItem->GetTitle() << "\n"
				<<	"Item Type:\t\t"
				<<	pItemType
				<<	"\n\n\n";


	*pMStream <<	pMessage;
	// Sign the email with the sender's name
	*pMStream << "\n\n"
				 << "-- "
				 << pUserId
				 << "\n";

	// send the query to support
//	char * testEmailAddress = "elena@ebay.com";
//	mailRc =	Mail.Send(testEmailAddress,
	mailRc =	Mail.Send((char *)mpMarketPlace->GetSupportEmail(),
							pEmail,   
							pItemType,
							NULL,
							NULL,
							HELP_POOL); 
	// handle send errors
	if (!mailRc)
	{
		*mpStream <<	"<h2>Unable to send email</h2>"
						"Sorry, we could not send the email now!"
						"<br>"
						"\n";
	}
	else
	{
		*mpStream <<	"<h2>Your email has been sent to eBay!</h2>"
						"<p>";
	}

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>"
					"\n";

	CleanUp();

	return;
}
