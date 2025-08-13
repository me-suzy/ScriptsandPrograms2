/*	$Id: clseBayAppAdminEndAuctionConfirm.cpp,v 1.8.148.1 1999/08/01 02:51:45 barry Exp $	*/
//
//	File:		clseBayAppAdminEndAuctionConfirm.cpp
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

//
//	e-Mail address where we'll send messages about people trying
//	to end top seller's auctions
//
static const char *GoodSellerEmailAddress		= "queue@phoenix.ebay.com";
static const char *GoodSellerEmailReturnAddress	= "queue@phoenix.ebay.com";

//
//	ItemsToItemIdList
//
//	This little thing tokenizes a list of item ids, and turns
//	it into a list. 
//
//	Returns true if parsing was sucessful, otherwise false. An
//	item id of 0 (usually from a bad atoi() conversion) is an 
//	error.
//
const char Seps[] = " \t,;\r\n";

bool clseBayApp::ItemsToItemIdList(char *pItems,
								   list<unsigned int> *plItemIds)
{
	char			*pItemId;
	unsigned int	itemId;
	bool			atLeastOneBad	= false;

	// Prime the strtok
	pItemId = strtok(pItems, Seps);

	// And here we go!
	while (pItemId != NULL)
	{
		itemId	= atoi(pItemId);

		if (itemId == 0)
		{
			atLeastOneBad	= true;
			pItemId	= strtok(NULL, Seps);
			continue;
		}

		plItemIds->push_back(itemId);

		pItemId = strtok(NULL, Seps);
	}

	return !atLeastOneBad;
}


bool clseBayApp::ValidateEndAuctionInput(char *pUserId,
										 char *pPass,
										 char *pItemId,
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

	if (error)
		return	false;
	else
		return	true;

}
void clseBayApp::EndAuctionConfirm(char *pUserId,
								   char *pPass,
								   char *pItemId,
								   int suspended,
								   int creditFees,
								   int emailbidders,
								   int type,
								   int buddy,
								   char *pText)
{
	list<unsigned int>				lItemIds;
	list<unsigned int>::iterator	ilItemIds;
	list<unsigned int>				lMissingItemIdList;
	list<unsigned int>::iterator	ilMissingItemIdList;
	vector<clsItemPtr>				vItems;
	vector<clsItemPtr>::iterator	ivItems;

	clsUser							*pSeller;
	clsFeedback						*pSellerFeedback;
	clsUserIdWidget					*pUserIdWidget		= NULL;

	time_t							nowTime;
	char							dateTime[32];

	bool							okIfAlreadyEnded;
	bool							gotBadItems	= false;

	ostrstream						goodItemStream;
	ostrstream						badItemStream;

	bool							gotABidder	= false;

	const clsCopyrightBuddyInfo		*pBuddyInfo;
	const char						*pEmailTemplate;
	const char						*pEmailSubjectTemplate;

	bool							gotGoodSellerItems	= false;
	ostrstream						goodSellerStream;

	const char						*pGoodSellerEmailSubjectTemplate;
	const char						*pGoodSellerEmailTemplate;
	char							*pGoodSellerEmailText;

	clsMail							*pMail;
	ostrstream						*pMailStream;

	char							*pGoodSellerEmail;
	char							*pGoodSellerEmailReturnAddress;

	//
	// First, let's parse the input list of items into an STL
	// <list>. If an error is returned, then we've got real
	// problems, and return with an error.
	//
	// ** NOTE **
	// Maybe we should have done this in ValidateEndAuctionInput. It's
	// kind of weird there, since we'd have to pass the ItemId list 
	// around.
	// ** NOTE **
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

		EndAuctionShow(pUserId, pPass, pItemId, suspended,
					   creditFees, emailbidders, type, buddy, pText);
		CleanUp();

		return;
	}

	// 
	// Let's see if it's ok if the auctions are already over
	//
	if (type == eNoteTypeAuctionEndBuddyAreadyEnded								||
		type == eNoteTypeAuctionEndAlreadyEnded									||
		type == eNoteTypeAuctionEndAlreadyEndedBootlegPiratedReplica			||
		type == eNoteTypeAuctionEndAlreadyEndedAdultItemInappropriateCategory	||
		type == eNoteTypeAuctionEndAlreadyEndedMicrosoft		
	   )
		okIfAlreadyEnded	= true;
	else
		okIfAlreadyEnded	= false;

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
	// We'll also investigate the auctions to see if any have 
	// bids
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


	// Look at the items we did get, checking end time, and, if they
	// have any bidders
	if (vItems.size() > 0)
	{
		for (ivItems = vItems.end(),
			 ivItems--;
			 ;
			 ivItems--)
		{
			// Top Seller?
			pSeller	= mpUsers->GetUserFromCache((*ivItems).mpItem->GetSeller());

			if (!pSeller)
			{
				badItemStream <<	"<tr>"
									"<td width=80>"
							  <<	"<A HREF=\""
    						  <<	mpMarketPlace->GetCGIPath(PageViewItem)
    						  <<	"eBayISAPI.dll?ViewItem&item="
							  <<	(*ivItems).mpItem->GetId()
							  <<	"\""
									">"
							  <<	(*ivItems).mpItem->GetId()
							  <<	"</A>"
							  <<	"</td>"
									"<td width=350>"
							  <<	"<font color=red>"
							  <<	"Can\'t find seller!"
							  <<	"</font>"
							  <<	"</td>"
									"</tr>";
				gotBadItems	= true;

				if (ivItems == vItems.begin())
					break;

				continue;
			}

			// Top Seller?
			if (pSeller->IsTopSeller())
			{

				// We're going to need to emit the user
				pSellerFeedback	= pSeller->GetFeedback();
				pUserIdWidget	= new clsUserIdWidget(mpMarketPlace, this);
				pUserIdWidget->SetUserInfo(pSeller->GetUserId(), 
										   pSeller->GetEmail(),
										   pSeller->GetUserState(),
										   pSeller->UserIdRecentlyChanged(),
										   pSellerFeedback->GetScore());
				pUserIdWidget->SetShowUserStatus(true);
				pUserIdWidget->SetIncludeEmail(true);

				pUserIdWidget->EmitHTML(mpStream);

				badItemStream <<	"<tr>"
									"<td width=80>"
							  <<	"<A HREF=\""
    						  <<	mpMarketPlace->GetCGIPath(PageViewItem)
    						  <<	"eBayISAPI.dll?ViewItem&item="
							  <<	(*ivItems).mpItem->GetId()
							  <<	"\""
									">"
							  <<	(*ivItems).mpItem->GetId()
							  <<	"</A>"
							  <<	"</td>"
									"<td width=350>"
							  <<	"<font color=red>"
							  <<	"Power Seller!"
							  <<	"</font>"
									" ";

				pUserIdWidget->EmitHTML(&badItemStream);

				badItemStream <<	" "
							  <<	"</td>"
									"</tr>";

				goodSellerStream <<	"<tr>"
									"<td width=80>"
								 <<	"<A HREF=\""
    							 <<	mpMarketPlace->GetCGIPath(PageViewItem)
    							 <<	"eBayISAPI.dll?ViewItem&item="
								 <<	(*ivItems).mpItem->GetId()
								 <<	"\""
									">"
								 <<	(*ivItems).mpItem->GetId()
								 <<	"</A>"
								 <<	"</td>"
									"<td width=350>"
								 <<	"<font color=red>"
								 <<	"Power Seller!"
								 <<	"</font>"
									" ";

				pUserIdWidget->EmitHTML(&badItemStream);

				goodSellerStream <<	" "
							     <<	"</td>"
									"</tr>";


				delete	pUserIdWidget;

				gotBadItems			= true;
				gotGoodSellerItems	= true;

				if (ivItems == vItems.begin())
					break;

				continue;
			}

			// High feedback?
			pSellerFeedback	= pSeller->GetFeedback();

			if (pSellerFeedback->GetScore() > 100)
			{

				// We're going to need to emit the user
				pUserIdWidget	= new clsUserIdWidget(mpMarketPlace, this);
				pUserIdWidget->SetUserInfo(pSeller->GetUserId(), 
										   pSeller->GetEmail(),
										   pSeller->GetUserState(),
										   pSeller->UserIdRecentlyChanged(),
										   pSellerFeedback->GetScore());
				pUserIdWidget->SetShowUserStatus(true);
				pUserIdWidget->SetIncludeEmail(true);

				pUserIdWidget->EmitHTML(mpStream);

				badItemStream <<	"<tr>"
									"<td width=80>"
							  <<	"<A HREF=\""
    						  <<	mpMarketPlace->GetCGIPath(PageViewItem)
    						  <<	"eBayISAPI.dll?ViewItem&item="
							  <<	(*ivItems).mpItem->GetId()
							  <<	"\""
									">"
							  <<	(*ivItems).mpItem->GetId()
							  <<	"</A>"
							  <<	"</td>"
									"<td width=350>"
							  <<	"<font color=red>"
							  <<	"High feedback!"
							  <<	"</font>"
									" ";

				pUserIdWidget->EmitHTML(&badItemStream);

				badItemStream <<	" "
							  <<	"</td>"
									"</tr>";

				goodSellerStream <<	"<tr>"
									"<td width=80>"
								 <<	"<A HREF=\""
    							 <<	mpMarketPlace->GetCGIPath(PageViewItem)
    							 <<	"eBayISAPI.dll?ViewItem&item="
								 <<	(*ivItems).mpItem->GetId()
								 <<	"\""
									">"
								 <<	(*ivItems).mpItem->GetId()
								 <<	"</A>"
								 <<	"</td>"
									"<td width=350>"
								 <<	"<font color=red>"
								 <<	"High feedback!"
								 <<	"</font>"
									" ";

				pUserIdWidget->EmitHTML(&badItemStream);

				goodSellerStream <<	" "
								 <<	"</td>"
									"</tr>";

				delete	pUserIdWidget;

				gotBadItems			= true;
				gotGoodSellerItems	= true;

				if (ivItems == vItems.begin())
					break;

				continue;
			}

			// What time is it?
			nowTime	= time(0);

			// Has this auction ended already?
			if (!okIfAlreadyEnded && (*ivItems).mpItem->GetEndTime() < nowTime)
			{
				clsUtilities::GetDateTime((*ivItems).mpItem->GetStartTime(),
										   dateTime);

				badItemStream <<	"<tr>"
									"<td width=80>"
							  <<	"<A HREF=\""
    						  <<	mpMarketPlace->GetCGIPath(PageViewItem)
    						  <<	"eBayISAPI.dll?ViewItem&item="
							  <<	(*ivItems).mpItem->GetId()
							  <<	"\""
									">"
							  <<	(*ivItems).mpItem->GetId()
							  <<	"</A>"
							  <<	"</td>"
									"<td width=350>"
							  <<	"<font color=red>"
							  <<	"Already ended @ "
							  <<	"</font>"
							  <<	dateTime
							  <<	"</td>"
									"</tr>";

				gotBadItems	= true;

				if (ivItems == vItems.begin())
					break;

				continue;
			}

			// Let's see if we've got a bidder. We count on the item's
			// bid count to figure this out.
			//
			// *NOTE*
			// The bid count is accurate, right? Retractions/cancels are
			// decremented?
			// *NOTE*

			if ((*ivItems).mpItem->GetBidCount() > 0)
			{
				gotABidder	= true;
			}

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
			  <<	mpMarketPlace->GetCGIPath(PageAdminEndAuctionResult)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"AdminEndAuction\">";

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
					"Auction(s) to End:"
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
					"The items/auctions above are valid, and can be ended. "
					"You can edit this list, and remove items if you wish."
					"</font>"
					"</td>"
					"</tr>";

	// Have we got any bad items to report?
	if (badItemStream.pcount() > 0)
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
						"and will not be ended."
						"</font>"
						"</td>"
						"</tr>";
	}

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

	// eMail Bidders?
	*mpStream <<	"<tr> "
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"eMail bidders?"
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
					"<tr>" 
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

	// 
	// Ok, here we put the template text for the email which we'll send
	// to the seller.. 
	//
	if (type == eNoteTypeAuctionEndBuddy			||
		type == eNoteTypeAuctionEndBuddyAreadyEnded	||
		type == eNoteTypeAuctionEndBuddyIDSA		||
		type == eNoteTypeAuctionEndAlreadyEndedBuddyIDSA)
	{
		pBuddyInfo	= GetBuddyInfo(buddy);
	}
	else
	{
		pBuddyInfo	= NULL;
	}

	// Seller's email subject
	pEmailSubjectTemplate	= GetEmailSubjectForNoteType(type);
	
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
	pEmailTemplate	= GetEmailTemplateForNoteType(type);

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Seller email text:"
					"</strong>"
					"</font>"
					"</td>"
					"<td width=\"430\">"
					"<textarea name=\"selleremailtext\" cols=\"56\" rows=\"8\">";

	if (pEmailTemplate != NULL)
		*mpStream <<	pEmailTemplate;

	*mpStream <<	"</textarea>";
	
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

	// Ok, now for the bidder email.
	//
	// If there's no high bidder, well, then we won't do
	// anything. 
	// 
	// If there is, and it's not a dutch auction, we'll 
	// need to get the high bidder's userid/email.
	//
	// If it IS a dutch auction, then we can't show the
	// userids/addresses just yet. We'll substitute some
	// boilerplate.
	//
	pEmailSubjectTemplate	= GetBidderEmailSubjectForNoteType(type);
	pEmailTemplate			= GetBidderEmailTemplateForNoteType(type);

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Bidder email subject:"
					"</strong>"
					"</font>"
					"</td>"
					"<td width=\"430\">";

	if (gotABidder)
	{
		*mpStream <<	"<input type=text name=\"bidderemailsubject\" size=\"56\"";
			
		if (pEmailSubjectTemplate != NULL)
		{
			*mpStream <<	" value=\""
					  <<	pEmailSubjectTemplate
					  <<	"\"";
		}

		*mpStream <<	">"
						"<br>"
				  <<	"<font size=\"2\">"
						" Text of e-mail to be sent to the bidders. If you modify it, "
						"<b>"
						"please "
						"</b>"
						"don't remove or modify the things beginning with \'%\'."
						"</font>"
						"</td>"
						"</tr>";
	}
	else
	{
		*mpStream	<<	"<font color=red>"
						"There are <i>no</i> bidders to email for these auctions"
						"</font>";
		*mpStream	<<	"<input type=hidden name=\'bidderemailsubject\' value=\'\'>";
	}

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>"
					"Bidder email text:"
					"</strong>"
					"</font>"
					"</td>"
					"<td width=\"430\">";

	// Now, if we haven't got a bidder, note it here. Else, we'll show them
	// what we've got.

	if (gotABidder)
	{
		*mpStream	<< "<textarea name=\"bidderemailtext\" cols=\"56\" rows=\"8\">";

		if (pEmailTemplate != NULL)
		{
			*mpStream	<<	pEmailTemplate;
		}

		*mpStream	<<	"</textarea>"
						"<br>"
						"<font size=\"2\">"
					<<	" Text of e-mail to be sent to the bidders on this item."
					<<	". You may modify it if you wish."
						"</font>";

	}
	else
	{
		*mpStream	<<	"<font color=red>"
						"There are <i>no</i> bidders to email for this auction"
						"</font>";
		*mpStream	<<	"<input type=hidden name=\'bidderemailtext\' value=\'\'>";
	}
	
	*mpStream <<	"</td>"
					"</tr>";

	// 
	// If this is a copyright issue, we might need to show
	// the email to the buddy
	//
	if (type == eNoteTypeAuctionEndBuddy			||
		type == eNoteTypeAuctionEndBuddyAreadyEnded	||
		type == eNoteTypeAuctionEndBuddyIDSA		||
		type == eNoteTypeAuctionEndAlreadyEndedBuddyIDSA)
	{
		if (pBuddyInfo->mpBuddyContactEmail != NULL)
		{
			// Buddy email address
			*mpStream <<	"<tr>"
							"<input type=hidden name=\'buddyemailaddress\' value=\'"
					  <<	pBuddyInfo->mpBuddyContactEmail
					  <<	"\'>";

			// Buddy's email subject
			pEmailSubjectTemplate	= GetBuddyEmailSubjectForNoteType(type);
			
			*mpStream <<	"<tr>"
							"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
							"<font size=\"3\" color=\"#006600\">"
							"<strong>"
							"Buddy email subject:"
							"</strong>"
							"</font>"
							"</td>"
							"<td width=\"430\">"
							"<input type=text name=\"buddyemailsubject\" size=\"56\"";
			
			if (pEmailSubjectTemplate != NULL)
			{
				*mpStream <<	" value=\""
						  <<	pEmailSubjectTemplate
						  <<	"\"";
			}

			*mpStream <<	">"
							"<br>"
					  <<	"<font size=\"2\">"
							"Subject of e-mail to be sent to: "
							"<b>"
					  <<	pBuddyInfo->mpBuddyContactEmail
					  <<	"</b>"
							"."
							"</font>"
							"</td>"
							"</tr>";

			pEmailTemplate	= GetBuddyEmailTemplateForNoteType(type);

			*mpStream <<	"<tr>"
							"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
							"<font size=\"3\" color=\"#006600\">"
							"<strong>"
							"Buddy email text:"
							"</strong>"
							"</font>"
							"</td>"
							"<td width=\"430\">"
					  <<	"<textarea name=\"buddyemailtext\" cols=\"56\" rows=\"8\">";

			if (pEmailTemplate)
				*mpStream << pEmailTemplate;

			*mpStream <<	"</textarea>"
							"<br>"
					  <<	"<font size=\"2\">"
							" Text of e-mail to be sent to the buddies. If you modify it, "
							"<b>"
							"please "
							"</b>"
							"don't remove or modify the things beginning with \'%\'."
							"</font>"
					  <<	"</td>"
							"</tr>";

		}
		else
		{
			*mpStream <<	"<tr>"
							"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
							"<font size=\"3\" color=\"#006600\">"
							"<strong>"
							"Buddy email subject:"
							"</strong>"
							"</font>"
							"</td>"
							"<td width=\"430\">"
							"(There is <i>no</i> buddy email contact address for this buddy)"
							"</td>"
							"</tr>"
							"<tr>"
							"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
							"<font size=\"3\" color=\"#006600\">"
							"<strong>"
							"Buddy email text:"
							"</strong>"
							"</font>"
							"</td>"
							"<td width=\"430\">"
							"(There is <i>no</i> buddy email contact address for this buddy)"
							"</td>"
							"</tr>"
							"<input type=hidden name=\'buddyemailaddress\' value=\'"
							"default"
							"\'>"
							"<input type=hidden name=\'buddyemailsubject\' value=\'"
							"default"
							"\'>"
							"<input type=hidden name=\'buddyemailtext\' value=\'"
							"default"
							"\'>";
		}
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
					"<input type=\"submit\" value=\"Submit\">"
					" to end these auction(s)"
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

	// Before we go, if we caught an attempt to end a "good seller's" items,
	// we need to tell someone
	if (0 /* gotGoodSellerItems */)
	{
		pGoodSellerEmailSubjectTemplate	= 
			GetBidderEmailSubjectForNoteType(eNoteTypeAuctionEndAttemptForGoodSeller);
		pGoodSellerEmailTemplate		= 
			GetBidderEmailTemplateForNoteType(eNoteTypeAuctionEndAttemptForGoodSeller);

		pGoodSellerEmailText	= new char[strlen(pGoodSellerEmailTemplate) +
										   strlen(goodSellerStream.str())	+ 
										   1];

		strcpy(pGoodSellerEmailText, pGoodSellerEmailTemplate);
		strcat(pGoodSellerEmailText, goodSellerStream.str());
		*(pGoodSellerEmailText + strlen(pGoodSellerEmailText)) = '\0';

		pGoodSellerEmail				= (char *)GoodSellerEmailAddress;
		pGoodSellerEmailReturnAddress	= (char *)GoodSellerEmailReturnAddress;

		pMail	= new clsMail();

		pMailStream	= pMail->OpenStream();

		// Prepare the stream
		pMailStream->setf(ios::fixed, ios::floatfield);
		pMailStream->setf(ios::showpoint, 1);
		pMailStream->precision(2);


		*pMailStream <<	pGoodSellerEmailText
					 <<	ends;

		pMail->Send((char *)pGoodSellerEmail, 
					(char *)pGoodSellerEmailReturnAddress,
					(char *)pGoodSellerEmailSubjectTemplate);

		delete	pMail;

		delete	pGoodSellerEmailText;

	}


	return;
}



void clseBayApp::AdminEndAuctionConfirm(CEBayISAPIExtension *pThis, 
										char *pUserId,
										char *pPass,
										char *pItemId,
										int  suspended,
										int  creditFees,
										int	 emailbidders,
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

	// Let's validate some input
	if (!ValidateEndAuctionInput(pUserId, pPass, pItemId, suspended,
								 creditFees, emailbidders, type, buddy, pText))
	{
		*mpStream << "<p>";

		EndAuctionShow(pUserId, pPass, pItemId, suspended,
					   creditFees, emailbidders, type, buddy, pText);
		CleanUp();

		return;
	}


	EndAuctionConfirm(pUserId, pPass, pItemId, suspended, creditFees,
					  emailbidders,
					  type, buddy, pText);


	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;

}

