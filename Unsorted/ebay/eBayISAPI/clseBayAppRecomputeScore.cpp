/*	$Id: clseBayAppRecomputeScore.cpp,v 1.3.706.2 1999/08/05 20:42:18 nsacco Exp $	*/
//
//	File:	clseBayAppRecomputeScore.cc
//
//	Class:	clseBayApp
//
//
//	Function:
//
//	This function just recomputes the user's feedback score
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
//
// Run
//
// Just look up the user's feedback, and emit it
//
void clseBayApp::RecomputeScore(CEBayISAPIExtension *pThis,
								char *pUserId)
{
	clsFeedback		*pFeedback;
	int				newScore;


	// Duh.
	SetUp();

	// Blah
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Recomputing feedback score for "
			  <<	pUserId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();


	// Get the user
	mpUser = mpUsers->GetAndCheckUser(pUserId, mpStream);
	if (!mpUser)
	{
		return;
	}

	// Looks good, let's get their feedback
	// DON'T delete the pFeedback object because clsUser will do it
	pFeedback	=	mpUser->GetFeedback();

	if (!pFeedback)
	{
		*mpStream <<	"<H2>"
						"No feedback object returned for user "
				  <<	pUserId
				  <<	"</H2>";
		CleanUp();
		return;
	}

	// Let's return it!
	if (!pFeedback->UserHasFeedback())
	{
		*mpStream <<	"<H2>"
						"User "
				  <<	pUserId
				  <<	" has no feedback at this time."
						"</H2>";
		CleanUp();
	}

	// Recompute
	newScore	= pFeedback->RecomputeScore();

	*mpStream <<	"<br>"
					"Feedback score recomputed. <i>Old</i> score was "
			  <<	pFeedback->GetScore()
			  <<	", <b>new</b> score is "
			  <<	newScore
			  <<	".";

	pFeedback->SetScore(newScore);

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}


