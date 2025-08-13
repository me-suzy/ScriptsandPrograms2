/*	$Id: clseBayAppChangeFeedbackOptions.cpp,v 1.7.388.2 1999/08/05 20:42:12 nsacco Exp $	*/
//
//	File:	clseBayAppViewListedItems.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppViewBids.cpp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Contains the methods user to retrieve
//		and show all items listed by a user.
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 11/05/98 mila		- Added itemsPerPage parameter so we can
//									  pass it back to ViewFeedback as needed
//				- 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "hash_map.h"

//
// ChangeFeedbackOptions
//
void clseBayApp::ChangeFeedbackOptions(CEBayISAPIExtension *pThis,
									   char *pUserId,
									   char *pPass,
									   char *pOption,
									   int startingPage,
									   int itemsPerPage)
{
	clsUser		*pUser;
	clsFeedback	*pFeedback;

	SetUp();

	// Title
	if (strcmp(pOption, "showme") == 0 || 
		strcmp(pOption, "showmeleft") == 0)
	{
		*mpStream <<	"<html><head>"
						"<title>"
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" View User Feedback for "
				  <<	pUserId
				  <<	"</title>"
						"</head>"
				  <<	mpMarketPlace->GetHeader();
	}
	else
	{
		*mpStream <<	"<html><head>"
						"<title>"
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" Changing feedback options for "
				  <<	pUserId
				  <<	"</title>"
						"</head>"
				  <<	mpMarketPlace->GetHeader();
	}

	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream, 
												 true, NULL, true, false, false, true);
	if (!mpUser)
	{
		*mpStream <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	pUser	= mpUser;


	// Ok, we can leave the feedback now
	// DON'T delete the pFeedback object because clsUser will do it
	pFeedback	= pUser->GetFeedback();
	
	if (!pFeedback)
	{
		*mpStream <<	"\n"
						"<h2>Internal Error</h2>"
						"Unable to obtain feedback for "
				  <<	pUserId
				  <<	". Please report this to "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" "
				  <<	mpMarketPlace->GetSupportEmail()
				  <<	" ."
				  <<	"\n"
				  <<	mpMarketPlace->GetFooter();
		
		CleanUp();
		return;
	}

	if (strcmp(pOption, "hide") == 0)
	{
		pFeedback->SetFlag(FEEDBACK_FLAG_HIDE, true);
	}
	else if (strcmp(pOption, "show") == 0)
	{
		pFeedback->SetFlag(FEEDBACK_FLAG_HIDE, false);
	}
	else if (strcmp(pOption, "showme") == 0)
	{
		// Use the internal routine GetAndShowFeedback
		// (in clseBayAppViewFeedback.cpp) to get our
		// feedback and show it. We set the "honorHidden"
		// flag to false to NOT hide our feedback (after
		// all, it's ours).
		GetAndShowFeedback(pUser, startingPage, itemsPerPage, false);
	}
	else if (strcmp(pOption, "showmeleft") == 0)
	{
		// Use the internal routine GetAndShowFeedbackLEft
		// (in clseBayAppViewFeedback.cpp) to get our
		// feedback and show it. We set the "honorHidden"
		// flag to false to NOT hide our feedback (after
		// all, it's ours).
		GetAndShowFeedbackLeft(pUser, false);
	}

	else
	{
		*mpStream <<	"<h2>Internal Error</h2>"
						"The feedback option "
				  <<	pOption
				  <<	" is unrecognized."
						"Please report this problem to "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" "
				  <<	mpMarketPlace->GetSupportEmail()
				  <<	" ."
				  <<	"\n"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Now, announce that we're done
	*mpStream <<	"<p>";
	
	
	if (strcmp(pOption, "hide") == 0)
	{
		*mpStream <<	"The Feedback Profile for "
				  <<	pUserId
				  <<	" is now private.";
		*mpStream << "<p>"
			      << "To view your private Feedback Profile, "
			      << "<a href = "
			      << mpMarketPlace->GetCGIPath(PageViewFeedback)
			      << "eBayISAPI.dll?ViewFeedback&userId="
				  << pUserId
				  << "&page="
				  << 1
				  << "&items="
				  << 0
				  << ">"
				  << "click here."
				  << "</a>";
	}
	else if (strcmp(pOption, "show") == 0)
	{
		*mpStream <<	"The Feedback Profile for "
				  <<	pUserId
				  <<	" is now public.";
		*mpStream << "<p>"
			      << "To view your public Feedback Profile, "
			      << "<a href = "
			      << mpMarketPlace->GetCGIPath(PageViewFeedback)
			      << "eBayISAPI.dll?ViewFeedback&userId="
				  << pUserId
				  << "&page="
				  << 1
				  << "&items="
				  << itemsPerPage
				  << ">"
				  << "click here."
				  << "</a>";
		
	}
	
	
	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}


