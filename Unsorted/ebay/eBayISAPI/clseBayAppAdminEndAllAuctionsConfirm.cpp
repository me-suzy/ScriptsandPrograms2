//
//	File:		clseBayAppAdminEndAllAuctionsConfirm.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	The second step of ending auction(s). Here, we validate the input
//	parameters, and try and "compose" the email to be sent to the 
//	seller, high bidders, and, if it's a copyright/trademark issue,
//	the "buddy". 
//
//	Modifications:
//				- 12/02/98 michael	Created.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

bool clseBayApp::ValidateEndAllAuctionsInput(char *pUserId,
											 char *pPass,
											 char *pTargetUser,
											 int suspended,
											 int creditFees,
											 int emailbidders,
											 int type,
											 int buddy,
											 char *pText)
{
	bool	error	= false;
	clsItem	*pItem	= NULL;

	if (clsNote::IsTextRequired(type))
	{
		if (pText == NULL						||
			strcmp(pText, "default") == 0			)
		{
			*mpStream <<	"<font color=red size=+2>"
							"No long explanation!"
							"</font>"
							"Sorry, but you must provide a complete explanation for "
							"ending this auction."
							"<p>";

			error	= true;
		}
	}

	// End Dat Auction
	if (pTargetUser == NULL ||
		strcmp(pTargetUser, "default") == 0)
	{
		*mpStream <<	"<font color=red size=+2>"
						"Error. Seller "
				  <<	pTargetUser
				  <<	" is invalid."
						"</font>"
						"<p>";

		error	= true;
	}
	else
	{
		mpUser	= mpUsers->GetAndCheckUser(pTargetUser, mpStream);

		if (!mpUser)
		{
			*mpStream <<	"<font color=red size=+2>"
							"Error. Seller "
					  <<	pTargetUser
					  <<	" not found!."
							"</font>"
							"<p>";

			error	= true;
		}
	}


	delete	mpUser;
	mpUser	= NULL;

	if (error)
		return	false;
	else
		return	true;

}
void clseBayApp::EndAllAuctionsConfirm(char *pUserId,
									   char *pPass,
									   char *pTargetUser,
									   int suspended,
									   int creditFees,
									   int emailbidders,
									   int type,
									   int buddy,
									   char *pText)
{
	clsUser						*pSeller			= NULL;
	clsFeedback					*pSellerFeedback	= NULL;
	clsUserIdWidget				*pUserIdWidget		= NULL;

	ItemList					itemList;

	bool						gotABidder	= false;

	const clsCopyrightBuddyInfo	*pBuddyInfo;
	const char					*pEmailSubject;
	const char					*pEmailTemplate;


	// Some little things we need
	mpUser	= mpUsers->GetUser(pUserId);
	pSeller	= mpUsers->GetUser(pTargetUser);

	// Get the list of items to end
	if (pSeller)
	{
		pSeller->GetListedItems(&itemList, -1, true);

		if (itemList.size() < 1)
		{
			*mpStream <<	"<font color=red size=+2>"
							"User has no Auctions"
							"</font>"
							"<br>"
					  <<	"User "
					  <<	pTargetUser
					  <<	" has no auctions in progress. No action taken."
							"<p>";

			EndAllAuctionsShow(pUserId, pPass, pTargetUser, suspended,
							   creditFees, emailbidders, type, buddy, pText);
			CleanUp();

			return;
		}
	}

	pSellerFeedback	= pSeller->GetFeedback();
	pUserIdWidget	= new clsUserIdWidget(mpMarketPlace, this);
	pUserIdWidget->SetUserInfo(pSeller->GetUserId(), 
							   pSeller->GetEmail(),
							   pSeller->GetUserState(),
							   pSeller->UserIdRecentlyChanged(),
							   pSellerFeedback->GetScore());

	*mpStream <<	"<font color=green>"
					"End all auctions for ";
	
	pUserIdWidget->SetShowUserStatus(false);
	pUserIdWidget->SetIncludeEmail(true);
	pUserIdWidget->EmitHTML(mpStream);

	
	*mpStream <<	" validated!"
					"<br>"
					"This seller has "
					"<A HREF=\""
			  <<	mpMarketPlace->GetCGIPath(PageViewListedItems)
			  <<	"eBayISAPI.dll?ViewListedItems&userid="
			  <<	pSeller->GetUserId()
			  <<	"\">"
			  <<	itemList.size()
			  <<	"</a>"
			  <<	" auctions in progress. Check the form below, and hit "
					"\"End Auctions\" to end the auctions."
					"</font>"
					"<br>"
					"<br>";


	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminEndAllAuctionsResult)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"AdminEndAllAuctions\">";

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
					"</tr>";


	*mpStream <<	"<tr>" 
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Seller UserId/E-Mail"
					"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">" 
					"<input type=\"text\" name=\"targetuser\" size=\""
				<<	24
				<<	"\" maxlength=\""
				<<	EBAY_MAX_USERID_SIZE
				<<	"\"";
				
	if (pTargetUser != NULL &&
		strcmp(pTargetUser, "default") != 0)
	{
		*mpStream <<	" VALUE=\""
				  <<	pTargetUser
				  <<	"\"";
	}
	
	*mpStream <<	">"
					"<br>"
					"<font size=\"2\">"
					"<strong>"
					"UserId"
					"</strong>"
					" or "
					"<strong>"
					"e-Mail"
					"</strong>"
					" address of user whose auctions are to be ended."
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

	// email bidders?
	*mpStream <<	"<tr> "
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Credit fees?"
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
	*mpStream <<	"<input type=\"hidden\" name=\"type\" value=\" "
			  <<	type
			  <<	"\">"
			  <<	"<tr>" 
					"<td width=\"160\" bgcolor=\"#EFEFEF\">"
					"<strong>"
					"<font size=\"3\" color=\"#006600\">"
					"Why is this auction being ended?"
					"</font>"
					"</strong>"
					"</td>"
					"<td width=430>"
			  <<	clsNote::GetNoteTypeDescription(type)										
			  <<	"</td>"
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
					"eNote text:"
					"</strong>"
					"</font>"
					"</td>"
					"<td width=\"430\">"
					"<textarea name=\"text\" cols=\"56\" rows=\"8\">";
	if (pText != NULL				&&
		strcmp(pText, "default") != 0	)
	{
		*mpStream <<	pText;
	}

	*mpStream <<	"</textarea>"
					"<br>"
					"eNote text describing why the auction was ended (required). "
					"This information is <i>not</i> visible to the public. HTML "
					"OK."
					"</font>"
					"</td>"
					"</tr>";

	// 
	// Ok, here we put the template text for the email which we'll send
	// to the seller.. 
	//
	if (type == eNoteTypeAuctionEndBuddy			||
		type == eNoteTypeAuctionEndBuddyAreadyEnded		)
	{
		pBuddyInfo	= GetBuddyInfo(buddy);
	}
	else
	{
		pBuddyInfo	= NULL;
	}

	pEmailSubject	= GetEmailSubjectForNoteType(type);
	
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Seller e-mail subject template:"
					"</strong>"
					"</font>"
					"</td>"
					"<td width=\"430\">"
					"<input type=\"text\" name=\"selleremailsubject\" size=\"56\"";
	
	if (pEmailSubject != NULL)
	{
		*mpStream <<	" value=\""
				  <<	pEmailSubject
				  <<	"\"";
	}
	
	*mpStream <<	">"
					"<br>";

	*mpStream <<	"Template of subject for the e-mail to be sent to the seller, "
					"<b>"
			  <<	pSeller->GetEmail()
			  <<	"</b>"
			  <<	". If you "
					"wish, you may change it, but keep the following in mind: "
					"<ol>"
					"<li> The \"%d\" is the item number."
					"</ol>"
					"."
					"</td>"
					"</tr>";


	pEmailTemplate	= GetEmailTemplateForNoteType(type);
	
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Seller e-mail template:"
					"</strong>"
					"</font>"
					"</td>"
					"<td width=\"430\">"
					"<textarea name=\"selleremailtext\" cols=\"56\" rows=\"8\">";

	if (pEmailTemplate != NULL)
		*mpStream <<	pEmailTemplate;

	*mpStream <<	"</textarea>"
					"<br>";

	*mpStream <<	"Template of the e-mail to be sent to the seller, "
					"<b>"
			  <<	pSeller->GetEmail()
			  <<	"</b>"
			  <<	". If you "
					"wish, you may change it, but please do <b>not</b> change any "
					"of the items beginning with \'%\'."
					"</td>"
					"</tr>";


	// Ok, now for the bidder email.
	//
	// Since we don't even have the auctions yet, we can just
	// show what we're going to send out, plugging "high bidder"
	// for the userid / email addresses
	//

	if (!suspended)
		pEmailTemplate	= GetBidderEmailTemplateForNoteType(type);
	else
		pEmailTemplate	= GetBidderEmailSellerSuspendedTemplateForNoteType(type);

	pEmailSubject	= GetBidderEmailSubjectForNoteType(type);
	
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Bidder e-mail subject template:"
					"</strong>"
					"</font>"
					"</td>"
					"<td width=\"430\">"
					"<input type=\"text\" name=\"bidderemailsubject\" size=\"56\"";
	
	if (pEmailSubject != NULL)
	{
		*mpStream <<	" value=\""
				  <<	pEmailSubject
				  <<	"\"";
	}
	
	*mpStream <<	">"
					"<br>";

	*mpStream <<	"Template of subject for the e-mail to be sent to the bidder, "
					"<b>"
			  <<	pSeller->GetEmail()
			  <<	"</b>"
			  <<	". If you "
					"wish, you may change it, but keep the following in mind: "
					"<ol>"
					"<li> The \"%d\" is the item number."
					"</ol>"
					"."
					"</td>"
					"</tr>";

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Bidder e-mail template:"
					"</strong>"
					"</font>"
					"</td>"
					"<td width=\"430\">"
					"<textarea name=\"bidderemailtext\" cols=\"56\" rows=\"8\">";

	if (pEmailTemplate != NULL)
	{
		*mpStream <<	pEmailTemplate;
	}

	*mpStream <<	"</textarea>"
					"<br>"
					"Template of the e-mail to be sent to the high bidders of this "
					"seller\'s auctions. If you wish, you may change it, but please "
					"do <b>not</b> modify any of the items beginning with \'%\'"
					"</font>"	
					"</td>"
					"</tr>";
	// 
	// If this is a copyright issue, we might need to show
	// the email to the buddy
	//
	if (type == eNoteTypeAuctionEndBuddy			||
		type == eNoteTypeAuctionEndBuddyAreadyEnded		)
	{
		if (pBuddyInfo->mpBuddyContactEmail != NULL)
		{
			pEmailSubject	= GetBuddyEmailSubjectForNoteType(type);
			pEmailTemplate	= GetBuddyEmailTemplateForNoteType(type);

			*mpStream <<	"<input type=\"hidden\" name=\"buddyemailaddress\" value=\""
					  <<	pBuddyInfo->mpBuddyContactEmail
					  <<	"\" >";

			*mpStream <<	"<tr>"
							"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
							"<font size=\"3\" color=\"#006600\">"
							"<strong>"
							"Buddy e-mail subject template:"
							"</strong>"
							"</font>"
							"</td>"
							"<td width=\"430\">"
							"<input type=\"text\" name=\"buddyemailsubject\" size=\"56\"";
			
			if (pEmailSubject != NULL)
			{
				*mpStream <<	" value=\""
						  <<	pEmailSubject
						  <<	"\"";
			}
			
			*mpStream <<	">"
							"<br>";

			*mpStream <<	"Template of subject for the e-mail to be sent to the buddy. "
					  <<	"If you "
							"wish, you may change it, but please do <b>not</b> change the "
							"item(s) beginning with \'%\'"
							"</td>"
							"</tr>";

			*mpStream <<	"<tr>"
							"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
							"<font size=\"3\" color=\"#006600\">"
							"<strong>"
							"Buddy e-mail template:"
							"</strong>"
							"</font>"
							"</td>"
							"<td width=\"430\">";

			*mpStream <<	"<textarea name=\"buddyemailtext\" cols=\"56\" rows=\"8\">";

			if (pEmailTemplate != NULL)
				*mpStream <<	pEmailTemplate;

			*mpStream <<	"</textarea>"
					  <<	"<br>"
							" Text of the e-mail to be sent to the Buddy contact, "
							"<b>"
					  <<	pBuddyInfo->mpBuddyContactEmail
					  <<	"</b>"
							" regarding this seller\'s auction. If you modify it, "
							"<b>"
							"please "
							"</b>"
							"don't remove or modify the things beginning with \'%\'."
							"</font>";
	
			*mpStream <<	"</td>"
							"</tr>";
		}
	}
	else
	{
		*mpStream <<	"<input type=\"hidden\" name=\"buddyemailaddress\" "
						"value=\"default\">"
						"<input type=\"hidden\" name=\"buddyemailsubject\" "
						"value=\"default\" >"
						"<input type=\"hidden\" name=\"buddyemailtext\" "
						"value=\"default\" >";
	}

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
					"<input type=\"submit\" value=\"End Auctions\">"
					" to end this seller\'s auctions"
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

	delete	pUserIdWidget;
	delete	pSeller;

	return;
}



void clseBayApp::AdminEndAllAuctionsConfirm(char *pUserId,
											char *pPass,
											char *pTargetUser,
											int  suspended,
											int  creditFees,
											int  emailbidders,
											int type,
											int buddy,
											char *pText,
											eBayISAPIAuthEnum authLevel)
{
	SetUp();


	// We'll need a title here
	*mpStream <<	"<html>"
					"<head>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" End an auction"
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

	// Let's validate some input
	if (!ValidateEndAllAuctionsInput(pUserId, pPass, pTargetUser, suspended,
									 creditFees, emailbidders, type, buddy, pText))
	{
		*mpStream << "<p>";

		EndAllAuctionsShow(pUserId, pPass, pTargetUser, suspended,
						   creditFees, emailbidders, type, buddy, pText);
		CleanUp();

		return;
	}


	EndAllAuctionsConfirm(pUserId, pPass, pTargetUser, suspended, creditFees,
						  emailbidders,
						  type, buddy, pText);


	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;

}

