/*	$Id: clseBayAppAdminShowNoteShow.cpp,v 1.5.158.1 1999/08/01 03:01:03 barry Exp $	*/
//
//	File:		clseBayAppAdminShowNoteShow.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				This function draws the "form" for showing notes. 
//				It's a function (rather than a static
//				form) so a private routine inside of it can be called
//				to emit a form with the fields "intact" from a previous
//				invocation.
//
//	Modifications:
//				- 04/03/98 michael	Created.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

void clseBayApp::AdminInternalShowNoteShow(char *pUser,
										   char *pPass,
										   char *pAboutFilter,
										   unsigned int typeFilter)
{
	*mpStream <<	"<h2>"
					"Show eNotes"
					"</h2>"
					"Use this form to to view eNotes. Enter <i>your</i> User ID "
					"and Password, fill in the \"About filter\" and/or choose "
					"an eNote category, and hit \"Submit\" to show eNotes."
					"<br>"
					"<br>"
					"<font color=red><i>Caution!</i></font> If you don\'t provide "
					"a filter, then <i>all</i> eNotes will be retrieved! You don\'t "
					"want to do that!"
					"<br>";

	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminShowNote)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"AdminShowNote\">";

	*mpStream <<	"<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">"
					"<tr>" 
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<strong>"
					"<font size=\"3\" color=\"#006600\">"
					"Your User ID / Password"
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
					"</tr>";

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>About filter</strong>"
					"</font>"
					"</td>"
					"<td width=\"430\">"
					"<input type=\"text\" name=\"aboutfilter\" size=\"24\" maxlength=\""
			  <<	EBAY_MAX_PASSWORD_SIZE
			  <<	"\"";
	if (pAboutFilter != NULL					&&
		strcmp(pPass, "pAboutFilter") != 0		)
	{
		*mpStream <<	" VALUE=\""
				  <<	pAboutFilter
				  <<	"\" ";
	}
	
	*mpStream <<	">"
					"<br>"
					"<font size=\"2\">"
					"<strong>"
					"User ID"
					"</strong>"
					", "
					"<strong>"
					"E-mail address"
					"</strong>"
					", or "
					"<strong>"
					"item number"
					"</strong>"
					". Leave "
					"<strong>"
					"blank "
					"</strong>"
					"for "
					"<strong>"
					"all "
					"</strong>"
					" notes"
					"</font>"
					"</td>"
					"</tr>";

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\">"
					"<strong>"
					"<font size=\"3\" color=\"#006600\">"
					"Note category filter"
					"</font>"
					"</strong>"
					"</td>"
					"<td width=430>"
					"<SELECT name=\"typefilter\" size=\"1\">"
					"<OPTION VALUE=0>All</OPTION>";

	clsNote::EmitNoteTypesAsHTMLOptions(mpStream, eNoteMajorTypeUnknown, typeFilter);
										
	*mpStream <<	"</SELECT>"
					"<br>"
					"<font size=\"2\">"
					"Choose a category or <b>All</b> for all notes"
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
					"<input type=\"submit\" value=\"Submit\">"
					" to see eNotes"
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



void clseBayApp::AdminShowNoteShow(CEBayISAPIExtension *pThis, 
								   char *pUserid,
								   char *pPass,
								   char *pAboutFilter,
								   int categoryFilter,
								   eBayISAPIAuthEnum authLevel)
{
	SetUp();

	// We'll need a title here
	*mpStream <<	"<html>"
					"<head>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Show eNotes"
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

	AdminInternalShowNoteShow(pUserid, pPass, pAboutFilter, categoryFilter);


	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;

}

