/*	$Id: clseBayAppAdminUnflagUser.cpp,v 1.2.158.2 1999/08/05 20:42:06 nsacco Exp $	*/
//
//	File:		clseBayAppAdminUnflagUser.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 06/14/97 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include <time.h>

void clseBayApp::AdminUnflagUser(char *pUserId,
								    char *pPass,
								    char *pTarget,
								    int type,
								    char *pText,
								    char *pEmailSubject,
								    char *pEmailText,
								    eBayISAPIAuthEnum authLevel)
{
	clsUser			*pTargetUser			= NULL;
	clsFeedback		*pTargetUserFeedback	= NULL;
	clsUserIdWidget	*pUserIdWidget			= NULL;

	char			*pUserInfoText;
	char			*pTextWithUserInfo;
	time_t			nowTime;
	clsNotes		*pNotes;
	clsNote			*pNote;

	// Setup
	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Unflagging for "
			  <<	pUserId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<p>";

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();

		return;
	}

	// Check the input
	if (!ValidateReinstateInput(pUserId, pPass, pTarget, type, pText))
	{
		*mpStream <<	"<p>";
		UnflagUserShow(pUserId, pPass, pTarget, type, pText);
		CleanUp();

		return;
	}

	// All is well! Let's get the user!
	pTargetUser			= mpUsers->GetAndCheckUser(pTarget, mpStream);
	pTargetUserFeedback	= pTargetUser->GetFeedback();


	// Do them!
	pTargetUser->SetHasABlockedItem(false);

	// update them!
	pTargetUser->UpdateUser();

	nowTime	= time(0);

	pUserInfoText		= clsNote::GetUserInfo(0, pTargetUser);
	pTextWithUserInfo	= new char[strlen(pUserInfoText) + 8 + strlen(pText) + 1];
	strcpy(pTextWithUserInfo, pUserInfoText);
	strcat(pTextWithUserInfo, "<br><br>");
	strcat(pTextWithUserInfo, pText);

	pNotes	= mpMarketPlace->GetNotes();

	pNote	= new clsNote(pNotes->GetSupportUser()->GetId(),
						  mpUser->GetId(),
						  0,
						  pTargetUser->GetId(),
						  eClsNoteFromTypeAutoAdminPost,
						  type,
						  eClsNoteVisibleSupportOnly,
						  nowTime,
						  (time_t)0,
						  pEmailSubject,
						  pTextWithUserInfo);

	pNotes->AddNote(pNote);

	delete pTextWithUserInfo;

	// Indicate there's a note about this user
	pTargetUser->SetHasANote(true);

	// Tell them it worked!
	pUserIdWidget			= new clsUserIdWidget(mpMarketPlace, this);
	pUserIdWidget->SetUserInfo(pTargetUser->GetUserId(), 
							   pTargetUser->GetEmail(),
							   pTargetUser->GetUserState(),
							   pTargetUser->UserIdRecentlyChanged(),
							   pTargetUserFeedback->GetScore());
	pUserIdWidget->SetShowUserStatus(true);
	pUserIdWidget->SetIncludeEmail(true);

	*mpStream <<	"<font color=green>"
					"User ";

	pUserIdWidget->EmitHTML(mpStream);

	*mpStream <<	" Unflagged!"
					"</font>"
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	delete	pTargetUser;
	delete	pUserIdWidget;
	CleanUp();

	return;
}

	