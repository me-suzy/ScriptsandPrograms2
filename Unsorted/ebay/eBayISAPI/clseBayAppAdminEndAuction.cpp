/*	$Id: clseBayAppAdminEndAuction.cpp,v 1.14.124.1 1999/08/01 02:51:45 barry Exp $	*/
//
//	File:		clseBayAppAdminEndAuctionAndCreditFees.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Contains the "AdminEndAuction" function, and the app
//		function AdminEndAuctionAndCreditFees function.
//
//	Modifications:
//				- 01/05/98 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include <time.h>


void clseBayApp::EndAuctionRefundFees(clsItem *pItem,
									  clsUser *pSeller,
									  clsAccount *pSellerAccount,
									  ostream *pStream,
									  ostrstream *pMailStream)
{
	// We use this to form account detail records.
	clsAccountDetail	*pDetail;

	// Total credit to account
	double				accountCredit	= 0;

	*pStream <<	"<br>"
				"Fees credited: ";

	// Let's use the new spiffy-fast item flags to decide what
	// credits to give
	if (!pItem->HasInsertionCredit())
	{
		// I can't believe I'm doint this
		double	itemPrice;
		double	itemInsertionFee;

		if (pItem->GetQuantity() > 1)
		{
			itemPrice = pItem->GetQuantity() * pItem->GetStartPrice();
		}
		else
		{
			if (pItem->GetReservePrice() >= pItem->GetStartPrice())
				
				itemPrice = pItem->GetReservePrice();
			else
				itemPrice = pItem->GetStartPrice();
		}					

		itemInsertionFee	= pItem->GetInsertionFee(itemPrice);
	
		pDetail	=	new clsAccountDetail(AccountDetailCreditInsertion,
										 itemInsertionFee,
										 NULL, pItem->GetId());
		gApp->GetDatabase()->AddAccountDetail(pSeller->GetId(),
			pSellerAccount->GetTableIndicator(), pDetail );

		accountCredit	+=	itemInsertionFee;

		// Take care of cross reference
 		gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
												pItem->GetId());

		pItem->SetHasInsertionCredit(true);


		delete	pDetail;

		*pStream  <<	"insertion (";

		clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), itemInsertionFee);
		currencyWidget.EmitHTML(pStream);

		*pStream  <<	")";
	}

	if (pItem->GetBoldTitle() && !pItem->HasBoldCredit())
	{
		double	boldFee;

		boldFee	= pItem->GetBoldFee(0);

		pDetail	=	new clsAccountDetail(AccountDetailCreditBold,
										 boldFee,
										 NULL, pItem->GetId());
		gApp->GetDatabase()->AddAccountDetail(pSeller->GetId(), 
			pSellerAccount->GetTableIndicator(), pDetail);

		accountCredit	+=	boldFee;

		// Take care of cross reference
		gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
												pItem->GetId());

		delete	pDetail;
		
		pItem->SetHasBoldCredit(true);

		*pStream  <<	", bold (";

		clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), boldFee);
		currencyWidget.EmitHTML(pStream);

		*pStream  <<	")";


	}

	if (pItem->GetSuperFeatured() && !pItem->HasFeaturedCredit())
	{
		double	featuredFee;

		featuredFee	= pItem->GetFeaturedFee(pItem->GetStartTime());

		pDetail	=	new clsAccountDetail(AccountDetailCreditFeatured,
										 featuredFee,
										 NULL, pItem->GetId());
		gApp->GetDatabase()->AddAccountDetail(pSeller->GetId(),
			pSellerAccount->GetTableIndicator(), pDetail);

		accountCredit	+=	featuredFee;

		// Take care of cross reference
		gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
												pItem->GetId());

		delete	pDetail;

		pItem->SetHasFeaturedCredit(true);

		*pStream  <<	", featured (";

		clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), featuredFee);
		currencyWidget.EmitHTML(pStream);

		*pStream  <<	")";

	}
		
	if (pItem->GetFeatured() && !pItem->HasCategoryFeaturedCredit())
	{
		double	featuredFee;

		featuredFee	= pItem->GetCategoryFeaturedFee(pItem->GetStartTime());

		pDetail	=	new clsAccountDetail(AccountDetailCreditCategoryFeatured,
										 featuredFee,
										 NULL, pItem->GetId());
		gApp->GetDatabase()->AddAccountDetail(pSeller->GetId(),
			pSellerAccount->GetTableIndicator(), pDetail);

		accountCredit	+=	featuredFee;

		// Take care of cross reference
		gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
												pItem->GetId());

		delete	pDetail;

		pItem->SetHasCategoryFeaturedCredit(true);

		*pStream  <<	", category featured (";

		clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), featuredFee);
		currencyWidget.EmitHTML(pStream);

		*pStream  <<	")";

	}

	//
	// Though someone has made the nice methods to debit and credit
	// gift icon charges, we can't use them, since we want to 
	// accumulate the credits. Perhaps we should change / create 
	// methods for all charges/credits to work this way, and return
	// the amount credited.
	//
	if ((pItem->GetGiftIconType() > 0) && !pItem->HasGiftIconCredit())
	{
		double	giftFee;

		giftFee	= pItem->GetGiftIconFee(pItem->GetGiftIconType());

		pDetail	=	new clsAccountDetail(AccountDetailCreditGiftIcon,
										 giftFee,
										 NULL, pItem->GetId());
		gApp->GetDatabase()->AddAccountDetail(pSeller->GetId(),
			pSellerAccount->GetTableIndicator(), pDetail);

		accountCredit	+=	giftFee;

		// Take care of cross reference
		gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
												pItem->GetId());

		delete	pDetail;

		pItem->HasGiftIconCredit();

		*pStream  <<	", gift icon (";

		clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), giftFee);
		currencyWidget.EmitHTML(pStream);
		
		*pStream  <<	")";

	}

	// Gallery
	if (pItem->IsGallery() && !pItem->HasGalleryCredit())
	{
		double	galleryFee;

		galleryFee	= pItem->GetGalleryFee();

		pDetail	=	new clsAccountDetail(AccountDetailCreditGallery,
										 galleryFee,
										 NULL, pItem->GetId());
		gApp->GetDatabase()->AddAccountDetail(pSeller->GetId(),
			pSellerAccount->GetTableIndicator(), pDetail);

		accountCredit	+=	galleryFee;

		// Take care of cross reference
		gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
												pItem->GetId());

		delete	pDetail;

		pItem->SetHasGalleryCredit(true);

		*pStream  <<	", gallery (";

		clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), galleryFee);
		currencyWidget.EmitHTML(pStream);

		*pStream  <<	")";

	}

	// Gallery Featured
	if (pItem->IsFeaturedGallery() && !pItem->HasFeaturedGalleryCredit())
	{
		double	galleryFee;

		galleryFee	= pItem->GetFeaturedGalleryFee();

		pDetail	=	new clsAccountDetail(AccountDetailCreditFeaturedGallery,
										 galleryFee,
										 NULL, pItem->GetId());
		gApp->GetDatabase()->AddAccountDetail(pSeller->GetId(),
			pSellerAccount->GetTableIndicator(), pDetail);

		accountCredit	+=	galleryFee;

		// Take care of cross reference
		gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
												pItem->GetId());

		delete	pDetail;

		pItem->SetHasFeaturedGalleryCredit(true);

		*pStream  <<	", gallery featured (";

		clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), galleryFee);
		currencyWidget.EmitHTML(pStream);

		*pStream  <<	")";

	}


	
	if (accountCredit != 0)
	{
		pSellerAccount->AdjustBalance(accountCredit);
		*mpStream <<	"<br>";
	}

	return;
};


//
//	EndAuctionInternal
//
//	Ends an auction. Returns false if something went wrong.
//
bool clseBayApp::EndAuctionInternal(clsItem *pItem,
									ostream *pStream,
									ostrstream *pMailStream)
{

	time_t	nowTime;

	nowTime	= time(0);

	if (pItem->GetEndTime() < nowTime)
	{
		return false;
	}

	//inna 2/24, move this call above Set Notice time,in case it stops
	//on that line no FVF flag will get update first!

	// We ALWAYS set the magic flag which tells us NOT to
	// bill the user for the auction
	pItem->SetNoFinalValueFee(true);
	
	// We ALWAYS end the auction (duh!)
	pItem->SetNewEndTime(nowTime);

	//inna: both bill and notice time are now written to items and item_info

	// We ALWAYS set the magic flag which tells us NOT to 
	// send an end of auction notice
    pItem->SetNoticeTime(nowTime);

	//inna 2/24 Also set Bill Time (to prepare fro itemsX10)
	 pItem->SetBillTime(nowTime);
	
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
//	EndAuctions
//
//	Ends an auction, and, optionally, refund all fees 
//	associated with it. 
//
//	If "refundFees" is set, the the insertion fee, plus
//	any bold, featured, etc, fees will be refunded. 
//
//	The flags to NOT charge the final value fees will
//	ALWAYS be set. 
//
//	*NOTE*
//	This function accepts extra parameters which will one
//	day be used for administrative/user functions
//	*NOTE*
//
void clseBayApp::EndAuctions(list<unsigned int> *pItemIdList,
							 clsUser *pEndingUser,
							 eNoteTypeEnum type,
							 int buddy,
							 char *pText,
							 char *pSellerEmailSubjectTemplate,
							 char *pSellerEmailTemplate,
							 char *pBidderEmailSubjectTemplate,
							 char *pBidderEmailTemplate,
							 char *pBuddyEmailAddress,
							 char *pBuddyEmailSubjectTemplate,
							 char *pBuddyEmailTemplate,
							 bool refundFees,
							 bool suspended,
							 bool emailbidders)
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

	// The following are for emailing the bidders
	// of the victim auction.
	list<int>						lBidders;
	list<int>::iterator				ii;
	clsUser							*pBidder;

	clsMail							*pMail;
	ostrstream						*pMailStream;

	const char						*pReturnAddress;
	char							*pEndingUserCompany;

	int								bidderEmailSubjectLen;
	int								bidderEmailTextLen;
	char							*pBidderEmailSubject;
	char							*pBidderEmailText;

	bool							gotABuddy;
	bool							okIfAlreadyEnded;

	clsCopyrightBuddyInfo			*pBuddyInfo;
	int								buddyEmailSubjectLen;
	int								buddyEmailTextLen;
	char							*pBuddyEmailSubject;
	char							*pBuddyEmailText;

	int								sellerEmailSubjectLen;
	int								sellerEmailTextLen;
	char							*pSellerEmailSubject;
	char							*pSellerEmailText;

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

	// Let's see if we got a buddy
	if ((type == eNoteTypeAuctionEndBuddy					|| 
		 type == eNoteTypeAuctionEndBuddyAreadyEnded
		)													&&
		pBuddyEmailAddress != NULL							&&
		strcmp(pBuddyEmailAddress, "default") != 0			&&
		pBuddyEmailSubjectTemplate != NULL					&&
		strcmp(pBuddyEmailSubjectTemplate, "default") != 0	&&
		pBuddyEmailTemplate != NULL							&&
		strcmp(pBuddyEmailTemplate, "default") != 0				)
	{
		gotABuddy		= true;
		pBuddyInfo		= &clseBayApp::mCopyrightBuddyInfo[buddy - 1];
		pReturnAddress	= "whyended@ebay.com";

	}
	else
	{
		gotABuddy	= false;
		pBuddyInfo	= NULL;
		pReturnAddress	= "ended@ebay.com";
	}

	if (type == eNoteTypeAuctionEndBuddyAreadyEnded								||
		type == eNoteTypeAuctionEndAlreadyEnded									||
		type == eNoteTypeAuctionEndAlreadyEndedBootlegPiratedReplica			||
		type == eNoteTypeAuctionEndAlreadyEndedAdultItemInappropriateCategory	||
		type == eNoteTypeAuctionEndAlreadyEndedMicrosoft						||
		type == eNoteTypeItemBlockedUponListing									||		
		type == eNoteTypeItemBlockedAfterReview		
	   )
		okIfAlreadyEnded	= true;
	else
		okIfAlreadyEnded	= false;


	// Check why we are running the EOA
	if (type == eNoteTypeItemBlockedAfterReview)
	{
		// If we are blocking the auctions, then get the full item info
		// including the description.
		mpItems->GetManyItemsForAuctionEnd(pItemIdList,
										   &lItems,
										   &lMissingItemIdList,
										   true);
	}
	else
	{
		// First, let's get all the items. Fortunatly, we have this
		// coolio method which will get us lots of items at once
		mpItems->GetManyItemsForAuctionEnd(pItemIdList,
										   &lItems,
										   &lMissingItemIdList,
										   false);
	}

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

	// We'll need storage to build the e-mail to the bidders. We'll just 
	// allocate a big fat space -- large enough for the largest bidder email,
	// name, item #, item title, support email address and name.

	bidderEmailSubjectLen	= strlen(pSellerEmailSubjectTemplate) +
							  EBAY_MAX_ITEM_SIZE;

	pBidderEmailSubject		= new char[bidderEmailSubjectLen + 1];

	bidderEmailTextLen	= strlen(pBidderEmailTemplate) +
						  EBAY_MAX_USERID_SIZE +
						  EBAY_MAX_NAME_SIZE +
						  EBAY_MAX_ITEM_SIZE +
						  EBAY_MAX_TITLE_SIZE +
						  EBAY_MAX_NAME_SIZE + 
						  EBAY_MAX_NAME_SIZE +
						  EBAY_MAX_EMAIL_SIZE +
						  EBAY_MAX_COMPANY_SIZE;

	pBidderEmailText	= new char[bidderEmailTextLen + 1];

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
			// Tell us what's going on
			*mpStream <<	"<b>"
							"#"
					  <<	(*ilSellerItems).mpItem->GetId()
					  <<	"</b>"
					  <<	" "
					  <<	(*ilSellerItems).mpItem->GetTitle()
					  <<	"<br>";

			// Tack the item # and title into the stream
			*pItemListStream <<	"\n"
							 <<	(*ilSellerItems).mpItem->GetId()
							 <<	" "
							 <<	(*ilSellerItems).mpItem->GetTitle();

			// End the auction. If this returns false, the auction's
			// already over. 
			//
			// If this is the case and it's NOT being ended because of
			// a "Buddy" issue, we just skip it. Otherwise, we keep 
			// going, since we're going to do some special stuff
			//
			if (!EndAuctionInternal((*ilSellerItems).mpItem, mpStream, NULL))
			{
				*mpStream <<	"...Auction <i>already</i> ended";

				if (!okIfAlreadyEnded)
					continue;
			}
			else
				*mpStream  <<	"...Auction ended";

			
			// Credit fees
			if (refundFees)
			{
				EndAuctionRefundFees((*ilSellerItems).mpItem, 
									 pSeller,
									 pSellerAccount,
									 mpStream,
									 pItemListStream);
			}

			// Now, notify the bidders
			(*ilSellerItems).mpItem->GetBidders(&lBidders);

			if (lBidders.size() != 0)
			{
				// A small optimization. We know the subject of the
				// bidder email doesn't vary by bidder, so we just
				// build it once now.
				memset(pBidderEmailSubject, 0x00, bidderEmailSubjectLen);
					   
				sprintf(pBidderEmailSubject, pBidderEmailSubjectTemplate,
						(*ilSellerItems).mpItem->GetId());

				// Now, we spam ;-)
				for (ii = lBidders.begin();
					 ii != lBidders.end();
					 ii++)
				{
					pBidder	= mpUsers->GetUser((*ii));

					if (!pBidder)
						continue;

				    if (pBidder->SendEndofAuction()) { 

						if (emailbidders)
						{

							memset(pBidderEmailText, 0x00, bidderEmailTextLen);

							sprintf(pBidderEmailText, 
									pBidderEmailTemplate,
									pBidder->GetName(),
									pBidder->GetEmail(),
									(*ilSellerItems).mpItem->GetId(),
									(*ilSellerItems).mpItem->GetTitle(),
									pEndingUser->GetName(),
									pReturnAddress,
									pEndingUserCompany);


							pMail	= new clsMail();

							pMailStream	= pMail->OpenStream();

							// Prepare the stream
							pMailStream->setf(ios::fixed, ios::floatfield);
							pMailStream->setf(ios::showpoint, 1);
							pMailStream->precision(2);

							*pMailStream <<	pBidderEmailText
										 <<	ends;

							pMail->Send(pBidder->GetEmail(), 
										(char *)pReturnAddress,
										pBidderEmailSubject);

							delete pMail;

							*mpStream <<	"...bidder(s) emailed";

						}
					}
					//
					// Finally, invalidate the bidder list
					//
					// ** NOTE **
					// BAD, Michael, BAD. Need to abstract this call out to
					// the user!
					// ** NOTE **
					//

					gApp->GetDatabase()->InvalidateBidderList(mpMarketPlace->GetId(),
															  pBidder->GetId());

					delete pBidder;
					pBidder	= NULL;
				}

				// Allllll done!
				lBidders.erase(lBidders.begin(), lBidders.end());
			}

			// Now, while we're here, do the buddy
			//
			if (gotABuddy)
			{
				buddyEmailSubjectLen	= 
					strlen(pBuddyEmailSubjectTemplate) +
					EBAY_MAX_ITEM_SIZE;

				pBuddyEmailSubject	= new char[buddyEmailSubjectLen + 1];

				sprintf(pBuddyEmailSubject, pBuddyEmailSubjectTemplate,
						(*ilSellerItems).mpItem->GetId());

				buddyEmailTextLen	= 
					strlen(pBuddyEmailTemplate) +
					strlen(pBuddyEmailAddress) +
					EBAY_MAX_ITEM_SIZE +
					strlen((*ilSellerItems).mpItem->GetTitle()) +
					strlen(pSeller->GetUserId()) +
					strlen(pSeller->GetEmail()) +
					strlen(pEndingUser->GetName()) +
					strlen(pReturnAddress) +
					strlen(pEndingUserCompany);

				pBuddyEmailText	= new char[buddyEmailTextLen + 1];

				sprintf(pBuddyEmailText, pBuddyEmailTemplate, 
						pBuddyEmailAddress,
						(*ilSellerItems).mpItem->GetId(),
						(*ilSellerItems).mpItem->GetTitle(),
						pSeller->GetUserId(), 
						pSeller->GetEmail(),
						pEndingUser->GetName(),
						pReturnAddress,
						pEndingUserCompany);

				pMail	= new clsMail();

				pMailStream	= pMail->OpenStream();

				// Prepare the stream
				pMailStream->setf(ios::fixed, ios::floatfield);
				pMailStream->setf(ios::showpoint, 1);
				pMailStream->precision(2);

				*pMailStream <<	pBuddyEmailText
							 <<	ends;

				pMail->Send(pBuddyEmailAddress, 
							(char *)pReturnAddress,
							pBuddyEmailSubject,
							NULL,
							(char **)AutomatedSupportEmailBccListAuctionEnded);

				delete	pMail;
				delete	pBuddyEmailText;
				delete	pBuddyEmailSubject;


				*mpStream <<	"...buddy "
						  <<	pBuddyEmailAddress
						  <<	" emailed";
			}

			// File an eNote
			sprintf(noteSubject, "Auction %d Ended", 
					(*ilSellerItems).mpItem->GetId());

			pItemInfoText		= clsNote::GetItemInfo(0, (*ilSellerItems).mpItem);

			// In case no text or default text was passed, make safe
			if (pText == NULL ||
				strcmp(pText, "default") == 0)
			{
				pText	= "";
			}

			noteTextLen			= strlen(pItemInfoText);

			if (gotABuddy)
			{
				noteTextLen		= noteTextLen + 
								  strlen("<br>") +
								  strlen("<b>Buddy</b>: ") +
								  strlen(pBuddyInfo->mpBuddyEmailName);
			}

			noteTextLen			= noteTextLen +
								  strlen("<br><br>") +
								  strlen(pText);

			pTextWithItemInfo	= new char[noteTextLen + 1];

			strcpy(pTextWithItemInfo, pItemInfoText);

			if (gotABuddy)
			{
				sprintf(pTextWithItemInfo + strlen(pTextWithItemInfo),
						"<br><b>Buddy</b>: %s",
						pBuddyInfo->mpBuddyEmailName);
			}

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
								  type,
								  eClsNoteVisibleSupportOnly,
								  nowTime,
								  (time_t)0,
								  noteSubject,
								  pTextWithItemInfo);

			pNotes->AddNote(pNote);

			delete	pTextWithItemInfo;
			delete	pItemInfoText;
			delete  pNote;

			*mpStream <<	"...eNote filed";

			if (type == eNoteTypeItemBlockedAfterReview)
			{
				BidVector	vBids;
				BidVector::iterator iBids;

				// Get the bidders on this item
				(*ilSellerItems).mpItem->GetBidders(&lBidders);

				// first cancel all bids on the item
				for (ii = lBidders.begin(); ii != lBidders.end(); ii++)
				{
					(*ilSellerItems).mpItem->CancelBids((*ii));
				}
				lBidders.erase(lBidders.begin(), lBidders.end());

				// now get all bids on the item
				(*ilSellerItems).mpItem->GetBids(&vBids);

				// add the cancelled bids to the blocked bids table, then delete
				// them from the bids table
				for (iBids = vBids.begin(); iBids != vBids.end(); iBids++)
				{
					(*ilSellerItems).mpItem->BlockBid(*iBids);
				}
				vBids.erase(vBids.begin(), vBids.end());

				// reset the item's bid count to 0 and current price to $0.00
				(*ilSellerItems).mpItem->SetBidCount(0);
				(*ilSellerItems).mpItem->SetPrice(0.0);

				// add the item to the blocked items table
				mpItems->AddItem((*ilSellerItems).mpItem, true);

				// now delete the item from the active items table
				mpItems->DeleteItem(((*ilSellerItems).mpItem)->GetId(), false);

				*mpStream <<	"...item blocked";

				// now flag the seller for trying to list the item
//				pSeller->SetHasABlockedItem(true);		// Don't set flag for now
			}
			else
			{
				// Eradicate the auction
				mpItems->ArchiveItem((*ilSellerItems).mpItem);
				*mpStream <<	"...item archived and deleted";
			}

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

		if (gotABuddy)
		{
			//sellerEmailTextLen	= sellerEmailTextLen +
			//					  strlen(pBuddyInfo->mpBuddyEmailName);
		}

		pSellerEmailText		= new char[sellerEmailTextLen + 1];


		if (gotABuddy)
		{
			sprintf(pSellerEmailText, pSellerEmailTemplate,
									  pSeller->GetName(),
									  pSeller->GetEmail(),
									  pItemListStream->str(),
									  /* pBuddyInfo->mpBuddyEmailName, */
									  pEndingUser->GetName(),
									  pReturnAddress,
									  pEndingUserCompany);
		}
		else
		{
			sprintf(pSellerEmailText, pSellerEmailTemplate,
									  pSeller->GetName(),
									  pSeller->GetEmail(),
									  pItemListStream->str(),
									  pEndingUser->GetName(),
									  pReturnAddress,
									  pEndingUserCompany);
		}

		// Let's mail it out
		pMail	= new clsMail();

		pMailStream	= pMail->OpenStream();

		// Prepare the stream
		pMailStream->setf(ios::fixed, ios::floatfield);
		pMailStream->setf(ios::showpoint, 1);
		pMailStream->precision(2);

		*pMailStream <<	pSellerEmailText
					 <<	ends;

		if (pSeller->SendEndofAuction()) { 
			pMail->Send(pSeller->GetEmail(), 
						(char *)pReturnAddress,
						pSellerEmailSubject,
						NULL,
						(char **)AutomatedSupportEmailBccListAuctionEnded);
		}


		delete	pMail;
		delete	pSellerEmailSubject;
		delete	pSellerEmailText;
		delete	pItemListStream;
		pItemListStream	= NULL;

		*mpStream <<	"...seller emailed"
				  <<	"<br>";

		//
		// Finally, invalidate the seller list
		//
		// ** NOTE **
		// BAD, Michael, BAD. Need to abstract this call out to
		// the user!
		// ** NOTE **
		//
		gApp->GetDatabase()->InvalidateSellerList(mpMarketPlace->GetId(),
												  pSeller->GetId());


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

	// Need to delete these 
	delete pSeller;
	delete pSellerAccount;
	delete [] pBidderEmailSubject;
	delete [] pBidderEmailText;

	return;
};



void clseBayApp::AdminEndAuction(CEBayISAPIExtension *pServer,
								 char *pUserId,
								 char *pPass,
								 char *pItemIds,
								 int suspended,
								 int creditFees,
								 int emailbidders,
								 int type,
								 int buddy,
								 char *pText,
								 char *pSellerEmailSubjectTemplate,
								 char *pSellerEmailTemplate,
								 char *pBidderEmailSubjectTemplate,
								 char *pBidderEmailTemplate,
								 char *pBuddyEmailAddress,
								 char *pBuddyEmailSubjectTemplate,
								 char *pBuddyEmailTemplate,
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
	*mpStream <<	"<head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative End Auction And Credit Fees"
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

		EndAuctionShow(pUserId, pPass, pItemIds, suspended,
				   creditFees, emailbidders, type, buddy, pText);

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

		EndAuctionShow(pUserId, pPass, pItemIds, suspended,
					   creditFees, emailbidders, type, buddy, pText);

		CleanUp();
		return;
	}


	// Let's validate some input
	if (!ValidateEndAuctionInput(pUserId, pPass, pItemIds, suspended,
								 creditFees, emailbidders, type, buddy, pText))
	{
		*mpStream << "<p>";

		EndAuctionShow(pUserId, pPass, pItemIds, suspended,
					   creditFees, emailbidders, type, buddy, pText);
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

		EndAuctionShow(pUserId, pPass, pItemIds, suspended,
					   creditFees, emailbidders, type, buddy, pText);
		CleanUp();

		return;
	}


	EndAuctions(&lItemIds, 
				mpUser,
			    (eNoteTypeEnum)type,
			    buddy,
			    pText, 
			    pSellerEmailSubjectTemplate,
			    pSellerEmailTemplate,
			    pBidderEmailSubjectTemplate,
			    pBidderEmailTemplate,
			    pBuddyEmailAddress,
			    pBuddyEmailSubjectTemplate,
			    pBuddyEmailTemplate,
			    creditFees == 0 ? false : true, 
			    suspended == 0 ? false : true,
				emailbidders == 0 ? false : true);

	// Tell them it worked!
	*mpStream <<	"<p>"
					"<font color=red size=+1>Auctions have been ended";

	if (creditFees != 0)
		*mpStream <<	" and all fees refunded";

	*mpStream <<	"."
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();


	CleanUp();

	return;
}

	
