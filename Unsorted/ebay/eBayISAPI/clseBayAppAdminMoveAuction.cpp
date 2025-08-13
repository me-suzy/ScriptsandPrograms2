/*	$Id: clseBayAppAdminMoveAuction.cpp,v 1.6.22.1.34.2 1999/08/05 20:42:00 nsacco Exp $	*/
//
//	File:		clseBayAppAdminEndAuctionAndCreditFees.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Actually moves auctions
//
//	Modifications:
//				- 01/05/98 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include <time.h>

//
// Charge a "fee" for moving an auction.
//
// *** NOTE ***
// Doesn't actually do anything yet
// *** NOTE ***
//

void clseBayApp::ChargeMoveFee(clsItem *pItem,
							   clsUser *pSeller)
{
	// Seller's account
	clsAccount			*pSellerAccount;

	// We use this to form account detail records.
	clsAccountDetail	*pDetail;

	double				itemMoveFee		= 0;

	// Total charge to the account
	double				accountCharge	= 0;

	*mpStream <<	"<br>"
					"Fees charged: ";

	itemMoveFee	= pItem->GetItemMoveFee();

	pSellerAccount	= pSeller->GetAccount();

	pDetail	=	new clsAccountDetail(AccountDetailItemMoveFee,
									 itemMoveFee,
									 NULL, pItem->GetId());
	gApp->GetDatabase()->AddAccountDetail(pSeller->GetId(),
		pSellerAccount->GetTableIndicator(), pDetail );

	accountCharge	+=	itemMoveFee;

	// Take care of cross reference
 	gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
											pItem->GetId());

	delete	pDetail;

	*mpStream <<	"item move fee ($"
			  <<	itemMoveFee
			  <<	")";


	
	if (accountCharge != 0)
	{
		pSellerAccount->AdjustBalance(accountCharge);
		*mpStream <<	"<br>";
	}

	delete	pSellerAccount;

	return;
};



//
//	MoveAuctionInternal
//
//	Moves an auction
//
bool clseBayApp::MoveAuctionInternal(clsItem *pItem,
									 int category,
									 ostream *pStream,
									 ostrstream *pMailStream)
{
	pItem->SetNewCategory(category);

	return true;
}

//
// sort_items_by_seller
//
//	Sorts a list of items by seller id
//
static bool sort_items_by_seller(clsItemPtr pA, clsItemPtr pB)
{
	if (pA.mpItem->GetSeller() < pB.mpItem->GetSeller())
		return true;
	return false;
}

//
//	MoveAuctions
//
//	Moves auctions. 
//
//
void clseBayApp::MoveAuctions(list<unsigned int> *pItemIdList,
							  clsUser *pEndingUser,
							  int category,
							  bool emailsellers,
							  bool chargesellers,
							  char *pText,
							  char *pSellerEmailSubjectTemplate,
							  char *pSellerEmailTemplate)
{
	// Itcherator
	list<unsigned int>::iterator	iItem;

	// The list of items
	list<unsigned int>				lMissingItemIdList;
	vector<clsItemPtr>				lItems;
	vector<clsItemPtr>::iterator	ilItems;
	ItemList						lSellerItems;
	ItemList::iterator				ilSellerItems;


	// The Current Seller, and their account
	UserId							currentSeller;
	clsUser							*pSeller;
	clsAccount						*pSellerAccount;
	clsFeedback						*pSellerFeedback	= NULL;
	clsUserIdWidget					*pUserIdWidget		= NULL;


	time_t							nowTime;

	// This little stream is used to hold the "auctions"
	// part of the email to the seller.
	ostrstream						*pItemListStream;


	clsMail							*pMail;
	ostrstream						*pMailStream;

	const char						*pReturnAddress;
	char							*pEndingUserCompany;

	int								sellerEmailSubjectLen;
	int								sellerEmailTextLen;
	char							*pSellerEmailSubject;
	char							*pSellerEmailText;
	clsCategory						*pTargetCategory;
	clsCategory						*pItemCategory;

	// eNote goodies
	int								noteTextLen;
	char							noteSubject[256];
	char							*pItemInfoText;
	char							*pTextWithItemInfo;

	clsNotes						*pNotes;
	clsNote							*pNote;

	nowTime	= time(0);

	// Make Company Safe
	if (pEndingUser->GetCompany() == NULL)
		pEndingUserCompany	= "eBay Inc";
	else
		pEndingUserCompany	= pEndingUser->GetCompany();

	// Return address
	pReturnAddress	= "ended@ebay.com";

	// Target category
	pTargetCategory		= mpCategories->GetCategory(category);

	//
	// First, let's get all the items. Fortunatly, we have this
	// coolio method which will get us lots of items at once
	//
	mpItems->GetManyItemsForAuctionEnd(pItemIdList,
									   &lItems,
									   &lMissingItemIdList);

	if (lItems.size() == 0)
	{
		*mpStream <<	"<font color=red>"
						"None of the "
				  <<	pItemIdList->size()
				  <<	" items were found!"
				  <<	"<br>";
		return;
	}

	//
	// Sort the list of items by seller, so that
	// all of a seller's auctions are grouped together.
	// This makes it possible for us to send out one
	// e-mail to the seller for all their auctions
	//
	// If the list of items is only 1 item long, then
	// there's no need to sort it 
	//
	if (lItems.size() > 1)
	{
		sort(lItems.begin(), lItems.end(), sort_items_by_seller);
	}


	// 
	// Ok, now we iterate through the items
	//
	currentSeller	= 0;
	for (ilItems = lItems.begin();
		 ;
		)
	{
		if (ilItems != lItems.end())
		{
		//check if this item has illegal category move
		//if yes, inform support and continue
		//do NOT allow movement in or out of real estate or Cars
		if ((((*ilItems).mpItem->CheckForAutomotiveListing() 
				&&  !(*ilItems).mpItem->CheckForAutomotiveListing(category)))
			|| ((!(*ilItems).mpItem->CheckForAutomotiveListing() 
				&& (*ilItems).mpItem->CheckForAutomotiveListing(category)))
			|| (((*ilItems).mpItem->CheckForRealEstateListing() 
				&& !(*ilItems).mpItem->CheckForRealEstateListing(category)))
			|| ((!(*ilItems).mpItem->CheckForRealEstateListing()
				&& (*ilItems).mpItem->CheckForRealEstateListing(category))))
		{
			*mpStream	<< "<b>Error Item "
						<< (*ilItems).mpItem->GetId();
			*mpStream	<<	". You may not move this item because pricing is "
						"different in the two categories. You may end "
						"this auction. (Insertion fees would be refunded, in that case).</b><br>";

				*ilItems++;
				continue;

		}
			
			// If we're still on the current seller, just push this
			// item onto the current seller's list.
			if ((*ilItems).mpItem->GetSeller() == currentSeller)
			{
				lSellerItems.push_back(*ilItems);
				*ilItems++;
				continue;
			}

			// We're not on the same seller! 
			//
			// If the prior seller was 0, then this is just the
			// first item in the list. Make it the current seller,
			// push the item, and continue.
			//
			if (currentSeller == 0)
			{
				currentSeller	= (*ilItems).mpItem->GetSeller();
				lSellerItems.push_back(*ilItems);
				*ilItems++;
				continue;
			}
		}
		else
		{
			if (lSellerItems.size() == 0)
				break;
		}
			
		// New Seller! 
		//
		// Let's handle the "previous" seller's auctions now in one
		// big batch!
		//
		// We do this as follows:
		//	1. End the auctions, and credit the fees.
		//	2. e-Mail the bidders on the auctions
		//	3. Send the seller one email for all the auctions
		//

		// Let's get the seller, and their account
		pSeller			= mpUsers->GetUser(currentSeller);
		pSellerAccount	= pSeller->GetAccount();
		
		// We'll use this nice in-memory stream to build the 
		// "auctions" part of the e-mail to the seller
		pItemListStream	= new ostrstream();

		// Let people know what's going on
		pSellerFeedback	= pSeller->GetFeedback();
		pUserIdWidget	= new clsUserIdWidget(mpMarketPlace, this);
		pUserIdWidget->SetUserInfo(pSeller->GetUserId(), 
								   pSeller->GetEmail(),
								   pSeller->GetUserState(),
								   pSeller->UserIdRecentlyChanged(),
								   pSellerFeedback->GetScore());
		pUserIdWidget->SetShowUserStatus(true);
		pUserIdWidget->SetIncludeEmail(true);

		*mpStream <<	"<hr>"
						"Processing auctions for ";

		pUserIdWidget->EmitHTML(mpStream);

		*mpStream <<	":<br>";

		delete	pUserIdWidget;

		// Ok, now, let's iterate through the list
		for (ilSellerItems = lSellerItems.begin();
			 ilSellerItems != lSellerItems.end();
			 ilSellerItems++)
		{
			pItemCategory	= 
				mpCategories->GetCategory((*ilSellerItems).mpItem->GetCategory());
			
			// Tell us what's going on
			*mpStream <<	"<b>"
							"#"
					  <<	(*ilSellerItems).mpItem->GetId()
					  <<	"</b>"
					  <<	" "
					  <<	(*ilSellerItems).mpItem->GetTitle()
					  <<	"<br>"
							"<b>in</b>: ";

			mpCategories->EmitHTMLQualifiedName(mpStream, pItemCategory);

			*mpStream <<	"<br>"
							"<b>moved to</b>: ";

			mpCategories->EmitHTMLQualifiedName(mpStream, pTargetCategory);

			*mpStream <<	"<br>";
			
			// Tack the item # and title into the stream
			*pItemListStream <<	"\n"
							 <<	(*ilSellerItems).mpItem->GetId()
							 <<	" "
							 <<	(*ilSellerItems).mpItem->GetTitle()
							 <<	"\n"
								"in : ";

			mpCategories->EmitHTMLQualifiedName(pItemListStream, pItemCategory);

			*pItemListStream << "\n"
								"moved to: ";

			mpCategories->EmitHTMLQualifiedName(pItemListStream, pTargetCategory);

			*pItemListStream <<	"\n";

			//
			// Move the auction
			//
			if (!MoveAuctionInternal((*ilSellerItems).mpItem, 
									 category,
									 mpStream, NULL))
			{
				*mpStream <<	"...Error moving auction.<br>";

				continue;
			}
			else
				*mpStream  <<	"...Auction moved.<br>";

			
			// Charge the moving fee
			if (chargesellers)
			{
				ChargeMoveFee((*ilSellerItems).mpItem, 
							  pSeller);
			}		
			// File an eNote
			sprintf(noteSubject, "Auction %d moved to appropriate category", 
					(*ilSellerItems).mpItem->GetId());

			pItemInfoText		= clsNote::GetItemInfo(0, (*ilSellerItems).mpItem);

			// In case no text or default text was passed, make safe
			if (pText == NULL ||
				strcmp(pText, "default") == 0)
			{
				pText	= "";
			}

			noteTextLen			= strlen(pItemInfoText);

			noteTextLen			= noteTextLen +
								  strlen("<br><br>") +
								  strlen(pText);

			pTextWithItemInfo	= new char[noteTextLen + 1];

			strcpy(pTextWithItemInfo, pItemInfoText);

			sprintf(pTextWithItemInfo + strlen(pTextWithItemInfo),
					"<br><br>%s",
					pText);


			pNotes	= mpMarketPlace->GetNotes();

			pNote	= new clsNote(pNotes->GetSupportUser()->GetId(),
								  pEndingUser->GetId(),
								  0,
								  (*ilSellerItems).mpItem->GetId(),
								  pSeller->GetId(),
								  eClsNoteFromTypeAutoAdminPost,
								  eNoteTypeItemMovedItemMovedToAppropriateCategory,
								  eClsNoteVisibleSupportOnly,
								  nowTime,
								  (time_t)0,
								  noteSubject,
								  pTextWithItemInfo);

			pNotes->AddNote(pNote);

			delete	pTextWithItemInfo;
			delete	pItemInfoText;

			*mpStream <<	"...eNote filed";
			*mpStream <<	"<br>";

		}

		// At this point, we've ended all of the current seller's auctions,
		// credited the fees, and e-mailed the bidders. Now, we can send
		// the seller ONE email covering all of the auctions.

		// Finish off the stream
		*pItemListStream <<	"\n\n"
						 <<	ends;

		// Build up the subject for the e-mail.
		// *NOTE* 
		// We're just going through the motions here, since there are no
		// substitutions in the text
		// *NOTE*

		sellerEmailSubjectLen	= strlen(pSellerEmailSubjectTemplate);
		pSellerEmailSubject		= new char[sellerEmailSubjectLen + 
										   EBAY_MAX_ITEM_SIZE +
										   1];
		sprintf(pSellerEmailSubject, pSellerEmailSubjectTemplate);

	
		// Build up the seller e-mail. The process of ending the auctions
		// cleverly put all the text about the auctions in the stream
		// above. 

		sellerEmailTextLen		= strlen(pSellerEmailTemplate) +
								  strlen(pSeller->GetName()) +
								  strlen(pSeller->GetEmail()) +
								  pItemListStream->pcount() +
								  strlen(pEndingUser->GetName()) +
								  strlen(pReturnAddress) +
								  strlen(pEndingUserCompany);

		pSellerEmailText		= new char[sellerEmailTextLen + 1];


		sprintf(pSellerEmailText, pSellerEmailTemplate,
								  pSeller->GetName(),
								  pSeller->GetEmail(),
								  pItemListStream->str(),
								  pEndingUser->GetName(),
								  pReturnAddress,
								  pEndingUserCompany);

		// Let's mail it out
		pMail	= new clsMail();

		pMailStream	= pMail->OpenStream();

		// Prepare the stream
		pMailStream->setf(ios::fixed, ios::floatfield);
		pMailStream->setf(ios::showpoint, 1);
		pMailStream->precision(2);

		*pMailStream <<	pSellerEmailText
					 <<	ends;

		pMail->Send(pSeller->GetEmail(),
					(char *)pReturnAddress,
					pSellerEmailSubject,
					NULL,
					(char **)AutomatedSupportEmailBccList);

		delete	pMail;
		delete	pSellerEmailSubject;
		delete	pSellerEmailText;
		delete	pItemListStream;
		pItemListStream	= NULL;

		*mpStream <<	"...seller emailed"
				  <<	"<br>";

		// Ok, all done. Clean up the list of seller's items
		lSellerItems.erase(lSellerItems.begin(), lSellerItems.end());

		// Finally, indicate that there's a note about this seller
		pSeller->SetHasANote(true);

		// If we were done with the list of items, then we're alll 
		// done.
		if (ilItems == lItems.end())
			break;

		// Make the new seller the current one. Don't advance -- 
		// we want the top of the loop to push this item onto the 
		// list of seller's auctions.
		currentSeller	= (*ilItems).mpItem->GetSeller();
	}

	*mpStream <<	"<br>";

	return;
};



void clseBayApp::AdminMoveAuction(CEBayISAPIExtension *pServer,
								 char *pUserId,
								 char *pPass,
								 char *pItemIds,
								 int category,
								 int emailsellers,
								 int chargesellers,
								 char *pText,
								 char *pSellerEmailSubjectTemplate,
								 char *pSellerEmailTemplate,
								 eBayISAPIAuthEnum authLevel)
{
	bool			error	= false;
	
	//
	// We cram the auction number(s) to end into this list
	// so EndAuction can end them
	//
	list<unsigned int>	lItemIds;
	
	// Setup
	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative move auction(s)"
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<p>";

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

		MoveAuctionShow(pUserId, pPass, pItemIds, category, emailsellers,
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

		MoveAuctionShow(pUserId, pPass, pItemIds, category, emailsellers,
						chargesellers, pText);

		CleanUp();
		return;
	}


	// Let's validate some input
	if (!ValidateMoveAuctionInput(pUserId, pPass, pItemIds, category, 
								  emailsellers,
								  chargesellers, pText))
	{
		*mpStream << "<p>";

		MoveAuctionShow(pUserId, pPass, pItemIds, category, emailsellers,
						chargesellers, pText);
		CleanUp();

		return;
	}

	// Transmorgify the list of items input into a list. 
	if (!ItemsToItemIdList(pItemIds, &lItemIds))
	{
		*mpStream <<	"<font color=red size=+2>"
				    	"Error in item list!"
						"</font>"
						"<br>"
						"This is probably caused by a non-numeric character "
						"in an item id, or an item id of 0. Please correct "
						"this and try again!"
						"<br>";

		MoveAuctionShow(pUserId, pPass, pItemIds, category, emailsellers,
						chargesellers, pText);
		CleanUp();

		return;
	}


	MoveAuctions(&lItemIds, 
				 mpUser,
				 category,
				 emailsellers == 0 ? false : true,
				 chargesellers == 0 ? false : true,
			     pText, 
			     pSellerEmailSubjectTemplate,
			     pSellerEmailTemplate
				 );


	// TD, 7/8/99, fix for bug #2000
	// Changed "Auctions have been moved" message to
	// "Move Auctions finished" to reduce confusion

	// Tell them it worked!
	*mpStream <<	"<p>"
					"<font color=red size=+1>Move Auctions finished";

	*mpStream <<	"."
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();


	CleanUp();

	return;
}

	