/*	$Id: clseBayAppAdminReinstateAuctionShow.cpp,v 1.2.158.1 1999/08/01 02:51:48 barry Exp $	*/
//
//	File:		clseBayAppAdminReinstateAuctionShow.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				This function draws the "form" for reinstating a user.
//
//	Modifications:
//				- 04/03/98 michael	Created.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

static const int nReinstateAuction = 0;
static const int nDenyAppeal = 1;

// Define title string
static const char *TitleText[] =
{
"Reinstate Auction",
"Appeal Denied"
};

static const char *DescriptionText[] =
{
"Use this form to Reinstate an eBay auction. An email will be sent, "
"the auction reinstated, and an eNote filed.",

"Use this form to file a Blocked Item Appeal Denied eNote."
};

static const char *ItemPromptText[] =
{
"Auction to Reinstate:",
"Blocked Item:"
};

static const char *EnoteTypeText[] =
{
"Reinstatement reason:",
"Appeal Denied reason:"
};

static const char *QText[] =
{
" to Reinstate this Auction.",
" to file a Blocked Item Appeal Denied eNote."
};

void clseBayApp::ReinstateAuctionShow( int action, char *pUser,
										char *pPass, char *pItemNo,
										int type, char *pText)
{
	eNoteMajorTypeEnum	majorType;

	//Make sure we have an action that is valid
	if ((action < 0) || (action > 1 ))
	{
		//Set a default
		action = 0;
	}

	
	*mpStream	<<	"<h2>"
				<<	TitleText[action]
				<<	"</h2>"
				<<	DescriptionText[action]
				<<	"<br>";

	// Decide where we are going next
	if (action == 0)
	{
		// Reinstate needs to go to the confirm page
		*mpStream	<<	"<form method=post action="
						"\""
					<<	mpMarketPlace->GetCGIPath(PageAdminReinstateAuctionConfirm)
					<<	"eBayISAPI.dll"
						"\""
						">"
						"<INPUT TYPE=HIDDEN "
						"NAME=\"MfcISAPICommand\" "
						"VALUE=\"AdminReinstateAuctionConfirm\">";
	}
	else
	{
		// Appeal denied can go right to the commit page
		*mpStream	<<	"<form method=post action="
						"\""
					<<	mpMarketPlace->GetCGIPath(PageAdminReinstateAuction)
					<<	"eBayISAPI.dll"
						"\""
						">"
						"<INPUT TYPE=HIDDEN "
						"NAME=\"MfcISAPICommand\" "
						"VALUE=\"AdminReinstateAuction\">";
	}

	// Add action type to pass on
	*mpStream	<<	"<input type=hidden name=action value=\""
				<<	action
				<<	"\">\n";

	*mpStream	<<	"<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">"
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
		*mpStream	<<	" VALUE=\""
					<<	pUser
					<<	"\" ";
	}
	
	*mpStream	<<	">"
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
		*mpStream	<<	" VALUE=\""
					<<	pPass
					<<	"\" ";
	}
	
	*mpStream	<<	">"
				<<	"<br>"
				<<	"<font size=\"2\">"
				<<	"Password"
				<<	"</font>"
				<<	"</td>"
				<<	"</tr>"
				<<	"</table>"
				<<	"</td>"
					"</tr>"
					"<tr>" 
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
				<<	"<strong>"
				<<	ItemPromptText[action]
				<<	"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">" 
					"<input type=\"text\" name=\"item\" size=\"24\" maxlength=\""
				<<	EBAY_MAX_USERID_SIZE
				<<	"\"";
				
	if (pItemNo != NULL && strcmp(pItemNo, "default") != 0)
	{
		*mpStream	<<	" VALUE=\""
					<<	pItemNo
					<<	"\"";
	}
	
	*mpStream	<<	">"
					"<br>"
					"<font size=\"2\">"
					"Item number"
					"</font>"
					"</td>"
					"</tr>";

	*mpStream	<<	"<tr>" 
					"<td width=\"160\" bgcolor=\"#EFEFEF\">"
					"<strong>"
				<<	"<font size=\"3\" color=\"#006600\">"
				<<	EnoteTypeText[action]
				<<	"</font>"
					"</strong>"
					"</td>"
					"<td width=430>"
					"<SELECT name=\"type\" size=\"1\">";

	// Decide what type of enote type we are going to use
	if (action == 0)
	{
		majorType = eNoteMajorTypeItemReinstatement;
	}
	else
	{
		majorType = eNoteMajorTypeAppealResults;
	}

	clsNote::EmitNoteTypesAsHTMLOptions(mpStream, majorType, type);
										
	*mpStream	<<	"</SELECT>"
					"</td>"
					"</tr>";

	*mpStream	<<	"<tr>"
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
		*mpStream	<<	pText;
	}

	*mpStream	<<	"</textarea>";
	
	*mpStream	<<	"</textarea>"
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
				<<	"Press " 
				<<	"<input type=\"submit\" value=\"Submit\">"
				<<	QText[action]
				<<	"</strong>"
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



void clseBayApp::AdminReinstateAuctionShow(int action, char *pUser,
											char *pPass, char *pItemNo,
											int type, char *pText,									      
											eBayISAPIAuthEnum authLevel)
{
	SetUp();

	// We'll need a title here
	*mpStream	<<	"<html>"
					"<head>"
				<<	"<TITLE>"
				<<	mpMarketPlace->GetCurrentPartnerName()
				<<	" "
				<<	TitleText[action]
				<<	"</TITLE>"
					"</head>"
				<<	flush;

	*mpStream	<<	mpMarketPlace->GetHeader()
				<<	"<br>";

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();

		return;
	}

	ReinstateAuctionShow(action, pUser, pPass, pItemNo, type, pText);


	*mpStream	<<	"<br>"
				<<	mpMarketPlace->GetFooter();

	CleanUp();
	return;
}

