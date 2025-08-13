/*	$Id: clseBayAppAdminRemoveItem.cpp,v 1.5.396.2 1999/08/05 20:42:03 nsacco Exp $	*/
//
//	File:		clseBayAppAdminRemoveItem.cpp
//
//	Class:		clseBayApp
//
//	Author:		Inna Markov (inna@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 08/19/98 inna 	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

void clseBayApp::AdminRemoveItem(CEBayISAPIExtension *pCtxt, 
								 char *pItemNo,eBayISAPIAuthEnum authLevel)
{
	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Remove Item Request for "
			  <<	pItemNo
			  <<    " item."
			  <<	"</title>"
					"</head>"
			  <<	"<p>"
			  <<    mpMarketPlace->GetHeader();	


	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

   	// Let's get the item
	mpItem = mpItems -> GetItem(atoi(pItemNo), false);
	//I don't want to use getAndCheckItem because I still need to 
	//call GetItem to create clsItem so I can check for End Time.
	//So I can see myself if this valis item;
	if (!mpItem)
	{

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
		
		CleanUp();
		return;

	}

	//if we got item exists - check for end of auction

	if (mpItem->GetEndTime() > time(0))
	{
		*mpStream <<	"<p>"
						"<H2>"
				  <<	"<font color=red> ERROR: &nbsp </font>"
						"Item \""
				  <<	pItemNo
				  <<	"\" auction has NOT been ended."
						"</H2>"
						"<p>"
						"Please go back and end auction first."
				  <<	mpMarketPlace->GetFooter();
		*mpStream <<	flush;
	
	CleanUp();
	return;

	}

	//if we got here - item can be removed, let's do it
	gApp->GetDatabase()->RemoveItem(mpItem);
	*mpStream <<	"<p>"
			  <<	"<font color=red size=+1>"
			  <<	"Item \""
			  <<	pItemNo
			  <<	"\" has been removed.</font>"
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();
	*mpStream <<	flush;

	CleanUp();
	return;

}

