/*	$Id: clseBayAppAdminMoveAuctionConfirm.cpp,v 1.5.2.1.88.1 1999/08/01 02:51:47 barry Exp $	*/
//
//	File:		clseBayAppAdminMoveAuctionConfirm.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	The second step of movinging auction(s). Here, we validate the input
//	parameters, and "compose" the email to be sent to the 
//	seller. 
//
//	Modifications:
//				- 12/02/98 michael	Created.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"


bool clseBayApp::ValidateMoveAuctionInput(char *pUserId,
										  char *pPass,
										  char *pItemId,
										  int category,
										  int emailsellers,
										  int chargesellers,
										  char *pText)
{
	bool	error	= false;
	clsItem	*pItem	= NULL;

	if (clsNote::IsTextRequired(eNoteTypeItemMovedItemMovedToAppropriateCategory))
	{
		if (pText == NULL						||
			strcmp(pText, "default") == 0			)
		{
			*mpStream <<	"<font color=red size=+2>"
							"No long explanation!"
							"</font>"
							"Sorry, but you must provide a complete explanation for "
							"moving these auction(s)."
							"<p>";

			error	= true;
		}
	}

	if (error)
		return	false;
	else
		return	true;

}
void clseBayApp::MoveAuctionConfirm(char *pUserId,
								    char *pPass,
								    char *pItemId,
									int category,
								    int emailsellers,
									int chargesellers,
								    char *pText)
{
	list<unsigned int>				lItemIds;
	list<unsigned int>::iterator	ilItemIds;
	list<unsigned int>				lMissingItemIdList;
	list<unsigned int>::iterator	ilMissingItemIdList;
	vector<clsItemPtr>				vItems;
	vector<clsItemPtr>::iterator	ivItems;

	CategoryVector					vCategories;
	clsCategory						*pCategory;


	bool							gotBadItems	= false;

	ostrstream						goodItemStream;
	ostrstream						badItemStream;

	const char						*pEmailTemplate;
	const char						*pEmailSubjectTemplate;


	//
	// First, let's parse the input list of items into an STL
	// <list>. If an error is returned, then we've got real
	// problems, and return with an error.
	//
	//
	if (!ItemsToItemIdList(pItemId, &lItemIds))
	{
		*mpStream <<	"<font color=red size=+2>"
				    	"Error in item list!"
						"</font>"
						"<br>"
						"This is probably caused by a non-numeric character "
						"in an item id, or an item id of 0. Please correct "
						"this and try again!"
						"<br>";

		MoveAuctionShow(pUserId, pPass, pItemId, category, emailsellers,
					    chargesellers, pText);
		CleanUp();

		return;
	}

	//
	// Ok, now actually GET the auctions in question. 
	//
	mpItems->GetManyItemsForAuctionEnd(&lItemIds,
									   &vItems,
									   &lMissingItemIdList);

	//
	// Now, let's go thru the list of items, and missing items.
	// We're going to build two streams -- one for valid item
	// numbers, and one for items with errors or which are plain
	// missing.
	//
	//
	// ** NOTE **
	// We go through the vectors BACKWARDS to we have the item
	// numbers in the same order the user entered them.
	// ** NOTE **
	//
	if (lMissingItemIdList.size() > 0)
	{
		gotBadItems	= true;

		for (ilMissingItemIdList = lMissingItemIdList.end(), 
			 ilMissingItemIdList--;
			 ;
			 ilMissingItemIdList--)
		{
			badItemStream <<	"<tr>"
								"<td width=80>"
						  <<	(*ilMissingItemIdList)
						  <<	"</td>"
								"<td width=350>"
						  <<	"<font color=red>"
						  <<	"Item not found"
						  <<	"</font>"
								"</td>"
								"</tr>";

			if (ilMissingItemIdList == lMissingItemIdList.begin())
				break;
		}
	}


	// Look at the items we did get, and spit them out in the "good"
	// items stream
	if (vItems.size() > 0)
	{
		for (ivItems = vItems.end(),
			 ivItems--;
			 ;
			 ivItems--)
		{
			// The item is good, so let's put it out in the "good"
			// steeam
			goodItemStream <<	(*ivItems).mpItem->GetId()
						   <<	" ";

			if (ivItems == vItems.begin())
				break;
		}
	}

	badItemStream <<	ends;
	goodItemStream <<	ends;


	// Some little things we need
	mpUser	= mpUsers->GetUser(pUserId);

	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminMoveAuctionResult)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"AdminMoveAuction\">";

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

	// Now, for the item numbers, good, and, maybe, bad
	*mpStream <<	"<tr>" 
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Auction(s) to move:"
					"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">" 
					"<textarea name=\"item\" cols=\"56\" rows=\"8\">";

	if (goodItemStream.pcount() > 0)
	{
		*mpStream <<	goodItemStream.str();
	}

	*mpStream <<	"</textarea>"
					"<br>"
					"<font size=\"2\">"
					"The items/auctions above are valid, and can be moved. "
					"You can edit this list, and remove items if you wish."
					"</font>"
					"</td>"
					"</tr>";

	// Have we got any bad items to report?
	if (gotBadItems)
	{
		*mpStream <<	"<tr>"						
						"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
						"<font size=\"3\" color=\"#006600\">"
						"<strong>"
						"Bad Items"
						"</strong>" 
						"</font>"
						"</td>"
						"<td width=\"430\">" 
						"<table border=1>"
				  <<	badItemStream.str()
				  <<	"</table>"
				  <<	"<br>"
						"<font size=\"2\">"
				  <<	"The above items had errors associated with them, "
						"and will not be moved."
						"</font>"
						"</td>"
						"</tr>";
	}

	// Category
	pCategory	= mpCategories->GetCategory(category, true);

	*mpStream <<	"<tr> "
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Category"
					"</strong>" 
					"</font>"
					"</td>"
					"<td width=\"430\">"
					"<b>";

	if (pCategory != NULL)
		mpCategories->EmitHTMLQualifiedName(mpStream, pCategory);

	*mpStream <<	"</b>"
					"<hr>"
					"<font size=\"2\">"
					"<strong>"
					"Category to move auction(s) <b>to</b>."
					"</strong>"
					"</font>"
					"</td>"
					"</tr>"
					"<input type=hidden name=\"category\" value=\""
			  <<	category
			  <<	"\">";


	// Notify Sellers?
	*mpStream <<	"<tr> "
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Notify Seller(s)?"
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
					"Notify the sellers the auction(s) have moved? "
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
					"Charge the seller(s) "
//					<<	mpItem->GetItemMoveFee()
					<<	"a fee for each auction moved?"
					"</strong>"
					"</font>"
					"</td>"
					"</tr>";



	// Detailed explanation
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
	
	*mpStream <<	"</textarea>"
					"<br>"
					"<font size=\"2\" color=\"#006600\">"
					" (required)"
					"</font>"
					"</td>"
					"</tr>";


	// Seller's email subject
	pEmailSubjectTemplate	= 
		GetEmailSubjectForNoteType(eNoteTypeItemMovedItemMovedToAppropriateCategory);
	
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Seller email subject:"
					"</strong>"
					"</font>"
					"</td>"
					"<td width=\"430\">"
					"<input type=text name=\"selleremailsubject\" size=\"56\"";
	
	if (pEmailSubjectTemplate != NULL)
	{
		*mpStream <<	" value=\""
				  <<	pEmailSubjectTemplate
				  <<	"\"";
	}
	
	*mpStream <<	">"
					"<br>"
			  <<	"<font size=\"2\">"
					"Subject of e-mail to be sent to the seller(s). "
					"You may modify it if you wish, but please do not change "
					"any of the text beginning with \'%\'."
					"</font>"
					"</td>"
					"</tr>";

	// Seller email text
	pEmailTemplate	= 
		GetEmailTemplateForNoteType(eNoteTypeItemMovedItemMovedToAppropriateCategory);

	
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Seller email text:"
					"</strong>"
					"</font>"
					"</td>"
					"<td width=\"430\">"
					"<textarea name=\"selleremailtext\" cols=\"56\" rows=\"8\">"
			  <<	pEmailTemplate
			  <<	"</textarea>";
	
	*mpStream <<	"<br>"
					"<font size=\"2\">"
					" Text of e-mail to be sent to the sellers. If you modify it, "
					"<b>"
					"please "
					"</b>"
					"don't remove or modify the things beginning with \'%\'."
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
					" to move these auction(s)"
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



void clseBayApp::AdminMoveAuctionConfirm(CEBayISAPIExtension *pThis, 
										 char *pUserId,
										 char *pPass,
										 char *pItemId,
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
			  <<	" Move auction(s)"
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

	//
	// Let's make sure this user can do this!
	//
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);

	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		MoveAuctionShow(pUserId, pPass, pItemId, category, emailsellers,
						chargesellers, pText);

		CleanUp();
		return;
	}

	if (mpUser && strstr(mpUser->GetEmail(), "@ebay.com") == 0)
	{
		*mpStream <<	"<font color=red size=+2>Not Authorized</font>"
						"You are not authorized to use this "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" function. "
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		MoveAuctionShow(pUserId, pPass, pItemId, category, emailsellers,
						chargesellers, pText);

		CleanUp();
		return;
	}


	// Let's validate some input
	if (!ValidateMoveAuctionInput(pUserId, pPass, pItemId, category, 
								  emailsellers, chargesellers, pText))
	{
		*mpStream << "<p>";

		MoveAuctionShow(pUserId, pPass, pItemId, category, emailsellers,
					    chargesellers, pText);
		CleanUp();

		return;
	}


	*mpStream <<	"<h2>Move auction(s) confirmation</h2>"
					"Your request to move auctions has been validated, and "
					"hitting \"Submit\" below will complete the move. "
					"<br>";

	MoveAuctionConfirm(pUserId, pPass, pItemId, category, emailsellers,
					   chargesellers, pText);


	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;

}

