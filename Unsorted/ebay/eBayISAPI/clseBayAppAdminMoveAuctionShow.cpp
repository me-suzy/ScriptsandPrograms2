/*	$Id: clseBayAppAdminMoveAuctionShow.cpp,v 1.4.210.1 1999/08/01 02:51:47 barry Exp $	*/
//
//	File:		clseBayAppAdminMoveAuctionShow.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				This method draws a "form" for support to move one of more
//				auctions to a new category
//
//	Modifications:
//				- 04/03/98 michael	Created.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

void clseBayApp::MoveAuctionShow(char *pUserId,
								 char *pPass,
								 char *pItemIds,
								 int category,
								 int emailsellers,
								 int chargesellers,
								 char *pText)
{
	CategoryVector	vCategories;

	*mpStream <<	"<h2>Move auctions to a new category</h2>"
					"Use this form to move one of more auctions to a new category. When "
					"you're done the auction(s) will be ended, and the sellers e-mailed "
					"(if so chosen), and an eNote filed."
					"<br>";

	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminMoveAuctionConfirm)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"AdminMoveAuctionConfirm\">";

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
					"Auction(s) to move:"
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

	// Category
	*mpStream <<	"<tr> "
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Category"
					"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">";

	mpCategories->EmitHTMLLeafSelectionList(mpStream,
										"category",
										(CategoryId)category,
										NULL,
										NULL,
										&vCategories, false, true);

	*mpStream <<	"<br>"
					"<font size=\"2\">"
					"<strong>"
					"Category to move auction(s) <b>to</b>."
					"</strong>"
					"</font>"
					"</td>"
					"</tr>";


	// e-mail the seller?
	*mpStream <<	"<tr> "
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Notify seller(s)?"
					"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">" 
					"<input type=\"radio\" name=\"emailsellers\" value=\"1\"";
	if (emailsellers == 1)
		*mpStream <<	" checked";
	
	*mpStream <<	">"
					"Yes"
					"<input type=\"radio\" name=\"emailsellers\" value=\"0\"";
	
	if (emailsellers != 1)
		*mpStream <<	" checked";
	
	*mpStream <<	">"
					"No"
					"</font>"
					"<br>"
					"<font size=\"2\">"
					"<strong>"
					"Notify seller(s)?"
					"</strong>"
					"</font>"
					"</td>"
					"</tr>";

	// Charge sellers?
	*mpStream <<	"<tr> "
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Charge Seller(s)?"
					"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">" 
					"<input type=\"radio\" name=\"chargesellers\" value=\"1\"";
	if (chargesellers == 1)
		*mpStream <<	" checked";
	
	*mpStream <<	">"
					"Yes"
					"<input type=\"radio\" name=\"chargesellers\" value=\"0\"";
	
	if (chargesellers != 1)
		*mpStream <<	" checked";
	
	*mpStream <<	">"
					"No"
					"</font>"
					"<br>"
					"<font size=\"2\">"
					"<strong>"
					"Charge the seller(s) a "
					" fee for each auction moved?"
					"</strong>"
					"</font>"
					"</td>";


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
					"<input type=\"submit\" value=\"Move auction(s)\">"
					" to move these auctions"
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

	vCategories.erase(vCategories.begin(), vCategories.end());


	return;
}



void clseBayApp::AdminMoveAuctionShow(CEBayISAPIExtension *pThis, 
									   char *pUserId,
									   char *pPass,
									   char *pItemIds,
									   int category,
									   int  emailsellers,
									   int  chargesellers,
									   char *pText,
									   eBayISAPIAuthEnum authLevel)
{
	SetUp();

	// We'll need a title here
	*mpStream <<	"<html>"
					"<head>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Move auctions"
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

	MoveAuctionShow(pUserId,
				    pPass,
				    pItemIds,
					category,
				    emailsellers,
					chargesellers,
				    pText);


	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;

}

