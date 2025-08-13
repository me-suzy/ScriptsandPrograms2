/*	$Id: clseBayAppAdminReinstateAuctionConfirm.cpp,v 1.2.138.1 1999/08/01 02:51:48 barry Exp $	*/
//
//	File:		clseBayAppAdminReinstateAuctionConfirm.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	This is the "confirmation step" for Reinstate a user. It's main
//	function in life is to validate the information input and present
//	the support rep with a template for the email to be sent to the
//	user. 
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
bool clseBayApp::ValidateReinstateAuctionInput(char *pUser,
											   char *pPass,
											   char *pItemNo,
											   int type,
											   char *pText)
{
	bool			error				= false;
	clsUser			*pSeller			= NULL;
	clsFeedback		*pSellerFeedback	= NULL;
	clsUserIdWidget	*pUserIdWidget		= NULL;


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
		*mpStream <<	"<font color=red>Not Authorized! </font>"
						"You are not authorized to use this "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" function. ";
		
		error	= true;
	}


	// Get the suspendee
	if (pItemNo == NULL || strcmp(pItemNo, "default") == 0)
	{
		*mpStream <<	"<font color=red>No auction to reinstate!</font>"
						"Sorry, but you must indicate which auction must be "
						"reinstated!";

		error	= true;
	}
	else
	{
		mpItem = mpItems->GetItem(atoi(pItemNo), false, NULL, 0, true);
		if (mpItem == NULL)
		{
			*mpStream <<	"<font color=red>Invalid item number!</font> "
							"Sorry, but the item could not be found!";

			error	= true;
		}
		else
		{
			pSeller	= mpUsers->GetUser(mpItem->GetSeller());
			if (pSeller == NULL)
			{
				error	= true;
			}
			else
			{
				if (pSeller->IsUnconfirmed())
				{
					pSellerFeedback		= pSeller->GetFeedback();
					pUserIdWidget		= new clsUserIdWidget(mpMarketPlace, this);
					pUserIdWidget->SetUserInfo(pSeller->GetUserId(), 
											   pSeller->GetEmail(),
											   pSeller->GetUserState(),
											   pSeller->UserIdRecentlyChanged(),
											   pSellerFeedback->GetScore());
					pUserIdWidget->SetShowUserStatus(true);
					pUserIdWidget->SetIncludeEmail(true);

					*mpStream <<	"<font color=red>"
									"Seller ";

					pUserIdWidget->EmitHTML(mpStream);

					*mpStream	<<	" is not confirmed"
								<<	"No action was taken."
									"</font>"
								<<	"<p>";

					error	= true;
				}
			}
		}
	}

	if (pText == NULL || strcmp(pText, "default") == 0)
	{
		*mpStream <<	"<font color=red>"
						"No long explanation!"
						"</font>"
						"Sorry, but you must provide a complete explanation for "
						"the auction\'s reinstatement."
						"<p>";

		error	= true;
	}

	delete	pSeller;
	delete	pUserIdWidget;

	if (error)
		return false;
	else
		return true;
}



void clseBayApp::ReinstateAuctionConfirm(char *pUser,
									  char *pPass,
									  char *pItemNo,
									  int type,
									  char *pText)
{
	clsUser			*pSeller = NULL;
#if 0
	char			*pEndingUserCompany;

	const char		*pEmailTemplate;
	const char		*pEmailSubject;

	int				emailTextLength;

	char			*pEmailText;
#endif

	// We have the item in mpItem

	*mpStream <<	"<font color=green>"
					"Reinstatement of Item "
			  <<	pItemNo
			  <<	" validated! Hit "
					"\"Reinstate\" below to reinstate this item."
					"</font>"
					"<br>"
					"<br>";

	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminReinstateAuction)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"AdminReinstateAuction\">";

	// Tell function that we are reinstating and not filing a eNote for appeal denied
	*mpStream	<<	"<input type=hidden name=action value=\"0\">\n";


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
	if (pUser != NULL && strcmp(pUser, "default") != 0)
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
	if (pPass != NULL && strcmp(pPass, "default") != 0)
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
					"Blocked Item to Reinstate:"
					"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">" 
					"<input type=\"text\" name=\"item\" size=\"24\" maxlength=\""
				<<	EBAY_MAX_USERID_SIZE
				<<	"\"";
				
	if (pItemNo != NULL && strcmp(pItemNo, "default") != 0)
	{
		*mpStream <<	" VALUE=\""
				  <<	pItemNo
				  <<	"\"";
	}
	
	*mpStream <<	">"
					"<br>"
					"<font size=\"2\">"
					"<strong>"
					"Item number"
					"</strong>"
					"</font>"
					"</td>"
					"</tr>";


	*mpStream <<	"<tr>" 
					"<td width=\"160\" bgcolor=\"#EFEFEF\">"
					"<strong>"
					"<font size=\"3\" color=\"#006600\">"
					"Blocked reason:"
					"</font>"
					"</strong>"
					"</td>"
					"<td width=430>"
					"<SELECT name=\"type\" size=\"1\">";

	clsNote::EmitNoteTypesAsHTMLOptions(mpStream, eNoteMajorTypeItemReinstatement, type);
										
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
	if (pText != NULL && strcmp(pText, "default") != 0)
	{
		*mpStream <<	pText;
	}

	*mpStream <<	"</textarea>";
					"<font size=\"2\" color=\"#006600\">"
					" (required)"
					"</font>"
					"</td>"
					"</tr>";

#if 0
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
	
	pSeller = mpUsers->GetUser(mpItem->GetSeller());

	*mpStream <<	"<font size=\"2\" color=\"#006600\">"
					" Subject of the e-mail to be sent to "
			  <<	pSeller->GetEmail()
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
					  strlen(pSeller->GetUserId()) +
					  strlen(pSeller->GetEmail()) +
					  strlen(mpUser->GetName()) +
					  strlen("support@ebay.com") +
					  strlen(pEndingUserCompany);

	pEmailText	= new char[emailTextLength + 1];
	sprintf(pEmailText, pEmailTemplate, 
			pSeller->GetUserId(), 
			pSeller->GetEmail(),
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
			  <<	pSeller->GetEmail()
			  <<	". You may modify it if you wish."
					"</font>"
					"</td>"
					"</tr>";
#endif

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
					"<input type=\"submit\" value=\"Reinstate\">"
					" to Reinstate this auction"
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

	return;
}



void clseBayApp::AdminReinstateAuctionConfirm(int action,
												char *pUserId,
												char *pPass,
												char *pItemNo,
												int type,
												char *pText,
												eBayISAPIAuthEnum authLevel)
{
	//Make sure we have an action that is valid
	if ((action < 0) || (action > 1 ))
	{
		//Set a default
		action = 0;
	}

	SetUp();

	// We'll need a title here
	*mpStream <<	"<html>"
					"<head>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Reinstate a Member"
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
	if (!ValidateReinstateAuctionInput(pUserId, pPass, pItemNo, type, pText))
	{
		*mpStream <<	"<p>";
		ReinstateAuctionShow(action, pUserId, pPass, pItemNo, type, pText);
		CleanUp();

		return;
	}

	ReinstateAuctionConfirm(pUserId, pPass, pItemNo, type, pText);

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;

}

