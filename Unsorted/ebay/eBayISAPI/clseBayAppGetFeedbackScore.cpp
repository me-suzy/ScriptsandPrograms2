/*	$Id: clseBayAppGetFeedbackScore.cpp,v 1.3 1998/06/30 09:09:08 josh Exp $	*/
//
//	File:	clseBayAppGetFeedbackScore.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppViewBids.cpp
//
//
//	Function:
//
//		Returns (emits) NOTHING but the requested
//		user's feedback score. Used to remotely 
//		access a user's feedback score.
//
// Modifications:
//				- 02/06/97 michael	- Created
//

#include "ebihdr.h"

//
// Run
//
// Just look up the user's feedback, and emit it
//
void clseBayApp::GetFeedbackScore(CEBayISAPIExtension *pThis,
									 char *pUserId)
{
	clsFeedback		*pFeedback;

	// Trace("%s:%d GetFeedbackScore for %s",
	//	  __FILE__, __LINE__, pUserId);

	// Duh.
	SetUp();

	// Get the user we're being questioned on
	mpUser	= mpUsers->GetUser(pUserId);
	if (!mpUser)
	{
		//Trace("...No such User\n");
		*mpStream <<	"(?)";
		CleanUp();
		return;
	}

	// Looks good, let's get their feedback
	// DON'T delete the pFeedback object because clsUser will do it
	pFeedback	=	mpUser->GetFeedback();

	if (!pFeedback)
	{
		//Trace("...No Feedback Object returned\n");
		*mpStream <<	"(?)";
		CleanUp();
		return;
	}

	// Let's return it!
	if (!pFeedback->UserHasFeedback())
	{
		*mpStream <<	"(NONE)";
		//Trace("...User has no feedback\n");
	}
	else
	{
		*mpStream <<	"("
				  <<	pFeedback->GetScore()
				  <<	")";

		//Trace("...Score is %d\n", pFeedback->GetScore());

	}

	CleanUp();

	return;
}


