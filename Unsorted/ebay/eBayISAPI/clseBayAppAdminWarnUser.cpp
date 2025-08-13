/*	$Id: clseBayAppAdminWarnUser.cpp,v 1.2.198.1.106.2 1999/08/05 20:42:09 nsacco Exp $	*/
//
//	File:		clseBayAppAdminWarnUser.cpp
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

void clseBayApp::AdminWarnUser(char *pUserId,
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

	// For mailing the suspendee
	clsMail							*pMail;
	ostrstream						*pMailStream;


	char		*pUserInfoText;
	char		*pTextWithUserInfo;
	time_t		nowTime;
	clsNotes	*pNotes;
	clsNote		*pNote;

	// Setup
	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Warning for "
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
	if (!ValidateWarningInput(pUserId, pPass, pTarget, type, pText))
	{
		*mpStream <<	"<p>";
		WarnUserShow(pUserId, pPass, pTarget, type, pText);
		CleanUp();

		return;
	}

	// All is well! Let's file an enote!
	pTargetUser			= mpUsers->GetAndCheckUser(pTarget, mpStream);
	pTargetUserFeedback	= pTargetUser->GetFeedback();

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
						  eNoteMajorTypeSupportWarning,
						  eClsNoteVisibleSupportOnly,
						  nowTime,
						  (time_t)0,
						  pEmailSubject,
						  pTextWithUserInfo);

	pNotes->AddNote(pNote);

	pTargetUser->SetHasANote(true);

	delete pTextWithUserInfo;

	// Now, mail them!
	pMail	= new clsMail();

	pMailStream	= pMail->OpenStream();

	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	*pMailStream <<	pEmailText;

	pMail->Send(pTargetUser->GetEmail(),
				mpUser->GetEmail(),
				pEmailSubject,
				NULL,
				(char **)AutomatedSupportEmailBccList);

	delete	pMail;



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

	*mpStream <<	" Warned!"
					"</font>"
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	delete	pTargetUser;
	delete	pUserIdWidget;
	CleanUp();

	return;
}

	