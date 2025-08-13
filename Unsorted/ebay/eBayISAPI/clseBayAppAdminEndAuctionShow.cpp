/*	$Id: clseBayAppAdminEndAuctionShow.cpp,v 1.2.396.1 1999/08/01 02:51:46 barry Exp $	*/
//
//	File:		clseBayAppAdminEndAuctionShow.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				This function draws the "form" for ending an auction.
//				Unlike the prior "just shoot them" method, this one
//				demands that a userid and password be entered, along
//				with a reason for the ending. This will be used
//				in the new "end auction" functionality.
//
//	Modifications:
//				- 04/03/98 michael	Created.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

void clseBayApp::EndAuctionShow(char *pUserId,
								char *pPass,
								char *pItemIds,
								int suspended,
								int creditFees,
								int emailbidders,
								int type,
								int buddy,
								char *pText)
{
	*mpStream <<	"<h2>End a member\'s auction(s)</h2>"
					"Use this form to end a member\'s auction(s) When you\'re done "
					"the auction(s) will be ended, the fee(s) credited (if so chosen), "
					"the seller, high bidders, and \"buddy\" emailed (if applicable), "
					"and an eNote filed."
					"<br>";

	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminEndAuctionConfirm)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"AdminEndAuctionConfirm\">";

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
	if (pUserId != NULL					&&
		strcmp(pUserId, "default") != 0		)
	{
		*mpStream <<	" VALUE=\""
				  <<	pUserId
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
					"Auction(s) to End:"
					"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">" 
					"<textarea name=\"item\" cols=\"56\" rows=\"8\">";
				
	if (pItemIds != NULL &&
		strcmp(pItemIds, "default") != 0)
	{
		*mpStream <<	pItemIds;
	}
	
	*mpStream <<	"</textarea>"
					"<br>"
					"<font size=\"2\">"
					"<strong>"
					"Enter item #s, seperated by spaces."
					"</strong>"
					"</font>"
					"</td>"
					"</tr>";

	// Was the user suspended?
	*mpStream <<	"<tr> "
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"User Suspended?"
					"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">" 
					"<input type=\"radio\" name=\"suspended\" value=\"1\"";
	if (suspended == 1)
		*mpStream <<	" checked";
	
	*mpStream <<	">"
					"Yes"
					"<input type=\"radio\" name=\"suspended\" value=\"0\"";
	
	if (suspended != 1)
		*mpStream <<	" checked";
	
	*mpStream <<	">"
					"No"
					"</font>"
					"<br>"
					"<font size=\"2\">"
					"<strong>"
					"Has the user been suspended? "
					"</strong>"
					"</font>"
					"</td>"
					"</tr>";

	// Credit the fees back to the user?
	*mpStream <<	"<tr> "
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Credit fees?"
					"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">" 
					"<input type=\"radio\" name=\"creditfees\" value=\"1\"";
	if (creditFees == 1)
		*mpStream <<	" checked";
	
	*mpStream <<	">"
					"Yes"
					"<input type=\"radio\" name=\"creditfees\" value=\"0\"";
	
	if (creditFees != 1)
		*mpStream <<	" checked";
	
	*mpStream <<	">"
					"No"
					"</font>"
					"<br>"
					"<font size=\"2\">"
					"<strong>"
					"Credit all fees? "
					"</strong>"
					"(this will credit the seller for insertion, bold, featured, etc fees)"
					"</font>"
					"</td>"
					"</tr>";

	// eMail bidders?
	*mpStream <<	"<tr> "
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"eMail Bidders?"
					"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">" 
					"<input type=\"radio\" name=\"emailbidders\" value=\"1\"";
	if (emailbidders == 1)
		*mpStream <<	" checked";
	
	*mpStream <<	">"
					"Yes"
					"<input type=\"radio\" name=\"emailbidders\" value=\"0\"";
	
	if (emailbidders != 1)
		*mpStream <<	" checked";
	
	*mpStream <<	">"
					"No"
					"</font>"
					"<br>"
					"<font size=\"2\">"
					"<strong>"
					"eMail Bidders? "
					"</strong>"
					"(this will e-Mail all bidders for the ended auction)"
					"</font>"
					"</td>"
					"</tr>";



	// Ending type
	*mpStream <<	"<tr>" 
					"<td width=\"160\" bgcolor=\"#EFEFEF\">"
					"<strong>"
					"<font size=\"3\" color=\"#006600\">"
					"Why is this auction being ended?"
					"</font>"
					"</strong>"
					"</td>"
					"<td width=430>"
					"<SELECT name=\"type\" size=\"1\">";

	clsNote::EmitNoteTypesAsHTMLOptions(mpStream, eNoteMajorTypeAuctionEnd, type);
										
	*mpStream <<	"</SELECT>"
					"</td>"
					"</tr>";

	// Buddy Id
	*mpStream <<	"<tr>" 
					"<td width=\"160\" bgcolor=\"#EFEFEF\">"
					"<strong>"
					"<font size=\"3\" color=\"#006600\">"
					"Copyright Buddy"
					"</font>"
					"</strong>"
					"</td>"
					"<td width=430>"
					"<SELECT name=\"buddy\" size=\"1\">";
	
	EmitBuddyInfoAsHTMLOptions(buddy);
	
	*mpStream <<	"</SELECT>"
					"</td>"
					"</tr>";


	// Detailed explanation
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"eNote Text"
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
					"<input type=\"submit\" value=\"End Auction\">"
					" to end this auction"
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



void clseBayApp::AdminEndAuctionShow(CEBayISAPIExtension *pThis, 
									  char *pUserId,
									  char *pPass,
									  char *pItemIds,
									  int  suspended,
									  int  creditFees,
									  int  emailbidders,
									  int type,
									  int buddy,
									  char *pText,
									  eBayISAPIAuthEnum authLevel)
{
	bool	bSuspended		= false;
	bool	bCreditFees		= false;
	bool	bEmailBidders	= false;

	SetUp();


	// Set booleans
	if (suspended == 1)
	{
		bSuspended	= true;
	}

	if (creditFees == 1)
	{
		bCreditFees	= true;
	}

	if (emailbidders == 1)
	{
		bEmailBidders	= true;
	}

	// We'll need a title here
	*mpStream <<	"<html>"
					"<head>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" End An Auction"
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

	EndAuctionShow(pUserId,
				   pPass,
				   pItemIds,
				   bSuspended,
				   bCreditFees,
				   bEmailBidders,
				   type,
				   buddy,
				   pText);


	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;

}

