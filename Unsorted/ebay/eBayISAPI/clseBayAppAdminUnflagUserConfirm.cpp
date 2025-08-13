/*	$Id: clseBayAppAdminUnflagUserConfirm.cpp,v 1.2.138.1 1999/08/01 03:01:04 barry Exp $	*/
//
//	File:		clseBayAppAdminUnflagUserConfirm.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	This is the "confirmation step" for unflagging a user. It's main
//	function in life is to validate the information input and present
//	the support rep with a template for the enote to be filed. 
//
//	Modifications:
//				- 04/03/98 michael	Created.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

//
// Common routine to validate input. Returns true if ok, false
// if not.
//
bool clseBayApp::ValidateUnflagInput(char *pUser,
										char *pPass,
										char *pTarget,
										int type,
										char *pText)
{
	bool			error					= false;
	clsUser			*pTargetUser			= NULL;
	clsFeedback		*pTargetUserFeedback	= NULL;
	clsUserIdWidget	*pUserIdWidget			= NULL;


	//
	// Let's make sure this user can do this!
	//
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUser, pPass, mpStream);

	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		error	= true;
	}

	if (mpUser && strstr(mpUser->GetEmail(), "@ebay.com") == 0)
	{
		*mpStream <<	"<font color=red>Not Authorized</font>"
						"You are not authorized to use this "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" function. ";
		
		error	= true;
	}


	// Get the suspendee
	if (pTarget == NULL ||
		strcmp(pTarget, "default") == 0)
	{
		*mpStream <<	"<font color=red>No one to suspend!</font>"
						"Sorry, but you must indicate which user must be "
						"unflagged!";

		error	= true;
	}
	else
	{
		pTargetUser	= mpUsers->GetAndCheckUser(pTarget, mpStream);
	}


	if (!pTargetUser)
	{
		error	= true;
	}

	if (pTargetUser != NULL &&
		pTargetUser->IsUnconfirmed())
	{
		pTargetUserFeedback		= pTargetUser->GetFeedback();
		pUserIdWidget			= new clsUserIdWidget(mpMarketPlace, this);
		pUserIdWidget->SetUserInfo(pTargetUser->GetUserId(), 
								   pTargetUser->GetEmail(),
								   pTargetUser->GetUserState(),
								   pTargetUser->UserIdRecentlyChanged(),
								   pTargetUserFeedback->GetScore());
		pUserIdWidget->SetShowUserStatus(true);
		pUserIdWidget->SetIncludeEmail(true);

		*mpStream <<	"<font color=red>"
						"User ";

		pUserIdWidget->EmitHTML(mpStream);

		*mpStream	<<	" is not confirmed"
					<<	"No action was taken."
						"</font>"
					<<	"<p>";

		error	= true;
	}


	if (pText == NULL						||
		strcmp(pText, "default") == 0			)
	{
		*mpStream <<	"<font color=red>"
						"No long explanation!"
						"</font>"
						"Sorry, but you must provide a complete explanation for "
						"the user\'s unflagging."
						"<p>";

		error	= true;
	}

	delete	pTargetUser;
	delete	pUserIdWidget;

	if (error)
		return false;
	else
		return true;
}


void clseBayApp::UnflagUserConfirm(char *pUser,
									  char *pPass,
									  char *pTarget,
									  int type,
									  char *pText)
{
	clsUser			*pTargetUser			= NULL;
	clsFeedback		*pTargetUserFeedback	= NULL;
	clsUserIdWidget	*pUserIdWidget			= NULL;

	char			*pEndingUserCompany;

	const char		*pEmailTemplate;
	const char		*pEmailSubject;

	int				emailTextLength;

	char			*pEmailText;

	// Git the user	
	pTargetUser				= mpUsers->GetUser(pTarget);
	pTargetUserFeedback		= pTargetUser->GetFeedback();
	pUserIdWidget			= new clsUserIdWidget(mpMarketPlace, this);
	pUserIdWidget->SetUserInfo(pTargetUser->GetUserId(), 
							   pTargetUser->GetEmail(),
							   pTargetUser->GetUserState(),
							   pTargetUser->UserIdRecentlyChanged(),
							   pTargetUserFeedback->GetScore());



	*mpStream <<	"<font color=green>"
					"Unflagging for ";
	
	pUserIdWidget->SetShowUserStatus(false);
	pUserIdWidget->SetIncludeEmail(true);
	pUserIdWidget->EmitHTML(mpStream);

	
	*mpStream <<	" validated! Complete the e-mail text below and hit "
					"\"Unflag\" to unflag this user."
					"</font>"
					"<br>"
					"<br>";

	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminUnflagUser)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"AdminUnflagUser\">";

	*mpStream <<	"<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">"
					"<tr>" 
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<strong>"
					"<font size=\"3\" color=\"#006600\">"
					"User ID / Password"
					"</font>"
					"</strong>"
					"</td>"
					"<td width=\"430\">"
					"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">"
					"<tr>" 
					"<td width=\"50%\" valign=\"top\">" 
					"<input type=\"text\" name=\"userid\" size=\"24\" maxlength=\""
			   <<	EBAY_MAX_USERID_SIZE
			   <<	"\"";
	if (pUser != NULL					&&
		strcmp(pUser, "default") != 0		)
	{
		*mpStream <<	" VALUE=\""
				  <<	pUser
				  <<	"\" ";
	}
	
	*mpStream <<	">"
					"<br>"
					"<font size=\"2\">"
					"<strong>"
					"User ID"
					"</strong>"
					" or E-mail address"
					"</font>"
					"</td>"
					"<td width=\"50%\" valign=\"top\">" 
					"<input type=\"password\" name=\"pass\" size=\"18\" maxlength=\""
				<<	EBAY_MAX_PASSWORD_SIZE
				<<	"\"";
	if (pPass != NULL					&&
		strcmp(pPass, "default") != 0		)
	{
		*mpStream <<	" VALUE=\""
				  <<	pPass
				  <<	"\" ";
	}
	
	*mpStream <<	">"
					"<br>"
					"<font size=\"2\">"
					"Password"
					"</font>"
					"</td>"
					"</tr>"
					"</table>"
					"</td>"
					"</tr>"
					"<tr>" 
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"User to Unflag:"
					"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">" 
					"<input type=\"text\" name=\"target\" size=\"24\" maxlength=\""
				<<	EBAY_MAX_USERID_SIZE
				<<	"\"";
				
	if (pTarget != NULL					&&
		strcmp(pTarget, "default") != 0		)
	{
		*mpStream <<	" VALUE=\""
				  <<	pTarget
				  <<	"\"";
	}
	
	*mpStream <<	">"
					"<br>"
					"<font size=\"2\">"
					"<strong>"
					"User ID"
					"</strong>"
					" or E-mail address"
					"</font>"
					"</td>"
					"</tr>";


	*mpStream <<	"<tr>" 
					"<td width=\"160\" bgcolor=\"#EFEFEF\">"
					"<strong>"
					"<font size=\"3\" color=\"#006600\">"
					"Suspension reason:"
					"</font>"
					"</strong>"
					"</td>"
					"<td width=430>"
					"<SELECT name=\"type\" size=\"1\">";

	clsNote::EmitNoteTypesAsHTMLOptions(mpStream, eNoteMajorTypeSellerUnflagged, type);
										
	*mpStream <<	"</SELECT>"
					"</td>"
					"</tr>";

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"eNote Text:"
					"</strong>"
					"</font>"
					"<font size=\"2\">"
					" (HTML&nbsp;ok)"
					"</font>"
					"</td>"
					"<td width=\"430\">"
					"<textarea name=\"text\" cols=\"56\" rows=\"8\">";
	if (pText != NULL				&&
		strcmp(pText, "default") != 0	)
	{
		*mpStream <<	pText;
	}

	*mpStream <<	"</textarea>";
					"<font size=\"2\" color=\"#006600\">"
					" (required)"
					"</font>"
					"</td>"
					"</tr>";

	// 
	// Email Subject
	//
	pEmailSubject	= GetEmailSubjectForNoteType(type);

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Email subject:"
					"</strong>"
					"</font>"
					"</td>"
					"<td width=\"430\">"
					"<input type=text name=\"emailsubject\" size=\"56\"";
	
	if (pEmailSubject != NULL)
	{
		*mpStream <<	" value=\""
				  <<	pEmailSubject
				  <<	"\"";
	}
	
	*mpStream <<	">"
					"<br>";
	
	*mpStream <<	"<font size=\"2\" color=\"#006600\">"
					" Subject of the e-mail to be sent to "
			  <<	pTargetUser->GetEmail()
			  <<	". You may modify it if you wish."
					"</font>"
					"</td>"
					"</tr>";


	// 
	// Ok, here we put the template text for the email which we'll send
	// to the user. 
	//
	// Make Company Safe
	if (mpUser->GetCompany() == NULL)
		pEndingUserCompany	= "eBay Inc";
	else
		pEndingUserCompany	= mpUser->GetCompany();


	pEmailTemplate	= GetEmailTemplateForNoteType(type);
	emailTextLength	= strlen(pEmailTemplate);
	emailTextLength	= emailTextLength +
					  strlen(pTargetUser->GetUserId()) +
					  strlen(pTargetUser->GetEmail()) +
					  strlen(mpUser->GetName()) +
					  strlen("support@ebay.com") +
					  strlen(pEndingUserCompany);

	pEmailText	= new char[emailTextLength + 1];
	sprintf(pEmailText, pEmailTemplate, 
			pTargetUser->GetUserId(), 
			pTargetUser->GetEmail(),
			mpUser->GetName(),
			"support@ebay.com",
			pEndingUserCompany);

	
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Email text:"
					"</strong>"
					"</font>"
					"</td>"
					"<td width=\"430\">"
					"<textarea name=\"emailtext\" cols=\"56\" rows=\"8\">"
			  <<	pEmailText
			  <<	"</textarea>"
					"<br>";
	
	*mpStream <<	"<font size=\"2\" color=\"#006600\">"
					" Text of the e-mail to be sent to "
			  <<	pTargetUser->GetEmail()
			  <<	". You may modify it if you wish."
					"</font>"
					"</td>"
					"</tr>";

	*mpStream <<	"</table>"
					"<p>"
					"<br>"
					"</p>"
					"<table border=\"0\" width=\"590\">"
					"<tr>"
					"<td>"
					"<p>"
					"<strong>"
					"Press " 
					"<input type=\"submit\" value=\"Unflag\">"
					" to Unflag this user"
					"</strong>"
					"</p>"
					"<p>"
					"Press " 
					"<input type=\"reset\" value=\"clear form\">"
					" to start over."
					"</p>"
					"</td>"
					"</tr>"
					"</table>"
					"</form>";

	delete	pTargetUser;
	delete	pUserIdWidget;

	return;
}



void clseBayApp::AdminUnflagUserConfirm(char *pUserId,
										  char *pPass,
										  char *pTarget,
										  int type,
										  char *pText,
										  eBayISAPIAuthEnum authLevel)
{
	SetUp();

	// We'll need a title here
	*mpStream <<	"<html>"
					"<head>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Unflag a Member"
			  <<	"</TITLE>"
					"</head>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();

		return;
	}

	// See if we're good to go!
	if (!ValidateUnflagInput(pUserId, pPass, pTarget, type, pText))
	{
		*mpStream <<	"<p>";
		UnflagUserShow(pUserId, pPass, pTarget, type, pText);
		CleanUp();

		return;
	}

	UnflagUserConfirm(pUserId, pPass, pTarget, type, pText);

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;

}

