/*	$Id: clsUserSurvey.cpp,v 1.3 1998/06/30 09:11:48 josh Exp $	*/
//
//	File:	clsUserSurvey.cpp
//
//	Class:	clsUserSurvey
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		User Survey support
//
// Modifications:
//

// This pragma avoid annoying warning messages
// about overlength names generated for STL
#pragma warning( disable : 4786 )
#include "eBayKernel.h"
#include "clsUserSurvey.h"

//
// Constructor
//
clsUserSurvey::clsUserSurvey(int survey_id)
{
	mMarketPlaceId	= gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId();
	mSurveyId		= survey_id;
}

//
// Destructor
//
clsUserSurvey::~clsUserSurvey()
{
	return;
}

//
// UserHasParticipated
//
bool clsUserSurvey::UserHasParticipated(int user_id)
{
	bool	gotBoolResponse;
	bool	boolResponse;
	bool	gotNumberResponse;
	float	numberResponse;
	bool	gotTextResponse;
	int		textResponseLength;
	char	*pTextResponse;

	gApp->GetDatabase()->GetUserSurveyResponse(mMarketPlaceId,
											 user_id,
											 mSurveyId,
											 0,
											 &gotBoolResponse,
											 &boolResponse,
										     &gotNumberResponse,
										     &numberResponse,
										     &gotTextResponse,
										     &textResponseLength,
											 &pTextResponse);

	if (!gotBoolResponse)
		return false;

	return boolResponse;
}

//
// UserHasParticipated
//
void clsUserSurvey::UserWishesToParticipate(int user_id,
											bool *pDontKnow,
											bool *pWantsTo)
{
	bool	gotBoolResponse;
	bool	boolResponse;
	bool	gotNumberResponse;
	float	numberResponse;
	bool	gotTextResponse;
	int		textResponseLength;
	char	*pTextResponse;

	gApp->GetDatabase()->GetUserSurveyResponse(mMarketPlaceId,
											 user_id,
											 mSurveyId,
											 1,
											 &gotBoolResponse,
											 &boolResponse,
										     &gotNumberResponse,
										     &numberResponse,
										     &gotTextResponse,
										     &textResponseLength,
											 &pTextResponse);

	if (!gotBoolResponse)
	{
		*pDontKnow	= true;
		return;
	}
	else
		*pDontKnow	= false;

	*pWantsTo	= boolResponse;

	return;
}

//
// SetUserWishesToParticipate
//
void clsUserSurvey::SetUserWishesToParticipate(int user_id, bool wantsTo)
{
	gApp->GetDatabase()->SetUserSurveyResponse(mMarketPlaceId,
										       user_id,
										       mSurveyId,
											   1,
										       wantsTo);
	return;
}