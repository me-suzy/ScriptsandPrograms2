/*	$Id: clseBayAppAdminWarnUserShow.cpp,v 1.2.396.1 1999/08/01 03:01:06 barry Exp $	*/
//
//	File:		clseBayAppAdminWarnUserShow.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				This function draws the "form" for warning a user.
//
//	Modifications:
//				- 04/03/98 michael	Created.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

void clseBayApp::WarnUserShow(char *pUser,
							  char *pPass,
							  char *pTarget,
							  int type,
							  char *pText)
{
	*mpStream <<	"<h2>"
					"Warn an eBay Member"
					"</h2>"
					"Use this form to warn an eBay member of a violation of "
					"procedures, rules, etc. An email will be sent, and the "
					"warning filed as an eNote."
					"<br>";

	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminWarnUserConfirm)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"AdminWarnUserConfirm\">";

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
					"User to Warn:"
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
					"Warning reason:"
					"</font>"
					"</strong>"
					"</td>"
					"<td width=430>"
					"<SELECT name=\"type\" size=\"1\">";

	clsNote::EmitNoteTypesAsHTMLOptions(mpStream, eNoteMajorTypeSupportWarning, type);
										
	*mpStream <<	"</SELECT>"
					"</td>"
					"</tr>";

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Detailed explanation:"
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
	
	*mpStream <<	"</textarea>"
					"<font size=\"2\" color=\"#006600\">"
					" (required)"
					"</font>"
					"</td>"
					"</tr>"
					"</table>"
					"<p>"
					"<br>"
					"</p>"
					"<table border=\"0\" width=\"590\">"
					"<tr>"
					"<td>"
					"<p>"
					"<strong>"
					"Press " 
					"<input type=\"submit\" value=\"Submit\">"
					" to Warn this user"
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



void clseBayApp::AdminWarnUserShow(char *pUser,
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
			  <<	" Warn a User"
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

	WarnUserShow(pUser, pPass, pTarget, type, pText);


	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;

}

