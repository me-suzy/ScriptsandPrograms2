/*	$Id: clseBayAppAdminSuspendUserConfirm.cpp,v 1.2.198.1.34.1 1999/08/01 03:01:03 barry Exp $	*/
//
//	File:		clseBayAppAdminSuspendUserConfirm.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	This is the "confirmation step" for suspending a user. It's main
//	function in life is to validate the information input and present
//	the support rep with a template for the email to be sent to the
//	user. 
//
//	I'm torn about allowing the Rep to modify information here. 
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
bool clseBayApp::ValidateSuspensionInput(char *pUser,
										 char *pPass,
										 char *pSuspendee,
										 int type,
										 char *pText)
{
	bool	error			= false;
	clsUser			*pSuspendeeUser	= NULL;
	clsFeedback		*pSuspendeeUserFeedback	= NULL;
	clsUserIdWidget	*pUserIdWidget			= NULL;


	//
	// Let's make sure this user can do this!
	//
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUser, pPass, mpStream);

	if (!mpUser)
	{
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
	if (pSuspendee == NULL ||
		strcmp(pSuspendee, "default") == 0)
	{
		*mpStream <<	"<font color=red>No one to suspend!</font>"
						"Sorry, but you must indicate which user must be "
						"suspended! Someone must be suspended!";

		error	= true;
	}
	else
	{
		pSuspendeeUser	= mpUsers->GetAndCheckUser(pSuspendee, mpStream);
	}


	if (!pSuspendeeUser)
	{
		error	= true;
	}

	// Sanity
	if (pSuspendeeUser != NULL &&
		pSuspendeeUser->IsSuspended())
	{
		pSuspendeeUserFeedback	= pSuspendeeUser->GetFeedback();
		pUserIdWidget			= new clsUserIdWidget(mpMarketPlace, this);
		pUserIdWidget->SetUserInfo(pSuspendeeUser->GetUserId(), 
								   pSuspendeeUser->GetEmail(),
								   pSuspendeeUser->GetUserState(),
								   pSuspendeeUser->UserIdRecentlyChanged(),
								   pSuspendeeUserFeedback->GetScore());
		pUserIdWidget->SetShowUserStatus(true);
		pUserIdWidget->SetIncludeEmail(true);

		*mpStream <<	"<font color=red>"
						"User ";

		pUserIdWidget->EmitHTML(mpStream);

		*mpStream <<	" is already suspended!"
				  <<	"No action was taken."
						"</font>"
						"<p>";

		error	= true;
	}

	if (pSuspendeeUser != NULL &&
		pSuspendeeUser->IsUnconfirmed())
	{
		pSuspendeeUserFeedback	= pSuspendeeUser->GetFeedback();
		pUserIdWidget			= new clsUserIdWidget(mpMarketPlace, this);
		pUserIdWidget->SetUserInfo(pSuspendeeUser->GetUserId(), 
								   pSuspendeeUser->GetEmail(),
								   pSuspendeeUser->GetUserState(),
								   pSuspendeeUser->UserIdRecentlyChanged(),
								   pSuspendeeUserFeedback->GetScore());
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
						"the user\'s suspension."
						"<p>";

		error	= true;
	}

	delete	pSuspendeeUser;
	delete	pUserIdWidget;

	if (error)
		return false;
	else
		return true;
}



void clseBayApp::SuspendUserConfirm(char *pUser,
									char *pPass,
									char *pSuspendee,
									int type,
									char *pText)
{
	clsUser			*pSuspendeeUser			= NULL;
	clsFeedback		*pSuspendeeUserFeedback	= NULL;
	clsUserIdWidget	*pUserIdWidget			= NULL;

    char			*pEndingUserCompany;

	const char		*pEmailTemplate;
	const char		*pEmailSubjectTemplate;
	int				emailSubjectLength;
	char			*pEmailSubject;
	int				emailTextLength;
	char			*pEmailText;



	// Git the user	
	pSuspendeeUser			= mpUsers->GetUser(pSuspendee);
	pSuspendeeUserFeedback	= pSuspendeeUser->GetFeedback();
	pUserIdWidget			= new clsUserIdWidget(mpMarketPlace, this);
	pUserIdWidget->SetUserInfo(pSuspendeeUser->GetUserId(), 
							   pSuspendeeUser->GetEmail(),
							   pSuspendeeUser->GetUserState(),
							   pSuspendeeUser->UserIdRecentlyChanged(),
							   pSuspendeeUserFeedback->GetScore());



	*mpStream <<	"<font color=green>"
					"Suspension for ";
	
	pUserIdWidget->SetShowUserStatus(false);
	pUserIdWidget->SetIncludeEmail(true);
	pUserIdWidget->EmitHTML(mpStream);

	
	*mpStream <<	" validated! Complete the e-mail text below and hit "
					"\"suspend\" to suspend this user."
					"</font>"
					"<br>"
					"<br>";

	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminSuspendUserResult)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"AdminSuspendUser\">";

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
					"User to Suspend:"
					"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">" 
					"<input type=\"text\" name=\"target\" size=\"24\" maxlength=\""
				<<	EBAY_MAX_USERID_SIZE
				<<	"\"";
				
	if (pSuspendee != NULL					&&
		strcmp(pSuspendee, "default") != 0		)
	{
		*mpStream <<	" VALUE=\""
				  <<	pSuspendee
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
					"<input type=\"hidden\" name=\"type\" value=\""
			  <<	type
			  <<	"\">"
					"<strong>"
					"<font size=\"3\" color=\"#006600\">"
					"Suspension reason:"
					"</font>"
					"</strong>"
					"</td>"
					"<td width=430>"
			  <<	clsNote::GetNoteTypeDescription(type)										
			  <<	"</td>"
					"</tr>";

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"eNote text:"
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
	pEmailSubjectTemplate	= GetEmailSubjectForNoteType(type);

	if (pEmailSubjectTemplate != NULL)
	{
		emailSubjectLength	= strlen(pEmailSubjectTemplate) +
								  EBAY_MAX_EMAIL_SIZE;
	}
	else
		emailSubjectLength = EBAY_MAX_EMAIL_SIZE;

	pEmailSubject			= new char[emailSubjectLength + 1];

	// XXX is this really what we want to do if pEmailSubjectTemplate
	// is NULL???  inquiring minds want to know!!!  (mila 5/28/99)
	if (pEmailSubjectTemplate != NULL)
	{
		sprintf(pEmailSubject,
				pEmailSubjectTemplate,
				pSuspendeeUser->GetEmail());
	}
	else
	{
		strcpy(pEmailSubject, pSuspendeeUser->GetEmail());
	}

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
					"<br>"
			  <<	"<font size=\"2\" color=\"#006600\">"
					" Subject of the e-mail to be sent to "
			  <<	pSuspendeeUser->GetEmail()
			  <<	". You may modify it if you wish."
					"</font>"
					"</td>"
					"</tr>";
	// 
	// Ok, here we put the template text for the email which we'll send
	// to the user. 
	//

	// Make Company safe
	if (mpUser->GetCompany() == NULL)
		pEndingUserCompany	= "eBay Inc";
	else
		pEndingUserCompany	= mpUser->GetCompany();

	pEmailTemplate	= GetEmailTemplateForNoteType(type);
	if (pEmailTemplate != NULL)
		emailTextLength	= strlen(pEmailTemplate);
	else
		emailTextLength	= 0;

	emailTextLength	= emailTextLength +
					  strlen(pSuspendeeUser->GetUserId()) +
					  strlen(pSuspendeeUser->GetEmail()) +
					  strlen(mpUser->GetName()) +
//					  strlen(mpUser->GetEmail()) +
	                  strlen("support@ebay.com") +
					  strlen(pEndingUserCompany);

	// XXX what do we do here if pEmailTemplate is NULL???
	// inquiring minds want to know!!!  (mila 5/28/99)
	pEmailText	= new char[emailTextLength + 1];
	sprintf(pEmailText, pEmailTemplate, 
			pSuspendeeUser->GetUserId(), 
			pSuspendeeUser->GetEmail(),
			mpUser->GetName(),
  // 		mpUser->GetEmail(),
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
			  <<	"</textarea>";
	
	*mpStream <<	"<font size=\"2\" color=\"#006600\">"
					" Text of the e-mail to be sent to "
			  <<	pSuspendeeUser->GetEmail()
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
					"<input type=\"submit\" value=\"Suspend\">"
					" to Suspend this user"
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

	delete	pSuspendeeUser;
	delete	pUserIdWidget;
	delete	pEmailSubject;
	delete	pEmailText;

	return;
}



void clseBayApp::AdminSuspendUserConfirm(char *pUserId,
										 char *pPass,
										 char *pSuspendee,
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
			  <<	" Suspend a User"
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
	if (!ValidateSuspensionInput(pUserId, pPass, pSuspendee, type, pText))
	{
		*mpStream <<	"<p>";
		SuspendUserShow(pUserId,
						pPass,
						pSuspendee,
						type,
						pText);
		CleanUp();

		return;
	}

	SuspendUserConfirm(pUserId, pPass, pSuspendee, type, pText);

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;

}

