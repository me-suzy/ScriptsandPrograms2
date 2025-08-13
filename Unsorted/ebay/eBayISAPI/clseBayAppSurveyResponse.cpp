/*	$Id: clseBayAppSurveyResponse.cpp,v 1.4.548.2 1999/08/05 20:42:20 nsacco Exp $	*/
//
//	File:	clseBayAppSurveyResponse.cpp
//
//	Class:	clseBayApp
//
//	File:	clseBayAppSurveyResponse
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		This file supports the survey response support.
//		Right now, it handles ONE case, which is accepting
//		a boolean response to ONE question from a survey.
//		
//		One day, it should be changed to support "variable forms"
//		(something the base ISAPI classes don't support).
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsUserSurvey.h"
#include "hash_map.h"

void clseBayApp::SurveyResponse(CEBayISAPIExtension *pThis,
							    char *pUserId,
							    char *pPassword,
							    char *pSurveyId,
							    char *pQuestionId,
							    char *pResponse)
{
	int				surveyId;
	int				questionId;
	clsUserSurvey	*pSurvey;
	bool			wantsToParticipate;

	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" User Survey"
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();

	//
	// Validate the Survey and Question Id. 
	//
	// When this code is more real, we'll put more flexible checks
	// in here
	//
	surveyId	= atoi(pSurveyId);
	questionId	= atoi(pQuestionId);

	if (surveyId != 1 ||
		questionId != 1)
	{
		*mpStream <<	"Invalid input, or incorrect usage. Please go back "
						"and ensure you have filled in the form properly. If "
						"this problem persists, please report this to technical "
						"support. (["
				  <<	pSurveyId
				  <<	"], ["
				  <<	pQuestionId
				  <<	"])."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Make a survey object
	pSurvey	= new clsUserSurvey(surveyId);

	// Usual User Stuff 
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPassword, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}


	// Normally, we'd check here to see if the user has ALREADY
	// participated, but we won't, since this little version just
	// checks to see if they WANT to participate.


	// Set the user's desires
	if (*pResponse == '1')
		wantsToParticipate	= true;
	else
		wantsToParticipate	= false;

	pSurvey->SetUserWishesToParticipate(mpUser->GetId(), 
										wantsToParticipate);

	delete pSurvey;

	//
	// Tell the user goodbye!
	//
	*mpStream <<	"Thank you for your response!"
					"<p>";

	if (wantsToParticipate)
		*mpStream <<	"You will be contacted via email when the survey "
						"becomes availible. eBay does not poll all volunteers "
						"for all surveys, so don't be disappointed if you are "
						"not contacted right away!";

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}


