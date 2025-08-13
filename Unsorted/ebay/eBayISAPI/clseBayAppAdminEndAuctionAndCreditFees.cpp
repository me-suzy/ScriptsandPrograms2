/*	$Id: clseBayAppAdminEndAuctionAndCreditFees.cpp,v 1.8.258.1 1999/08/01 02:51:45 barry Exp $	*/
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


// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.


//
// The text of text
//
static const char *AdminEndOfAuctionText =
"on which you have bid, has been ended early by eBay. This is due to the\n"
"fact that the seller has been suspended. eBay is not permitted to discuss\n"
"the reasons for any suspension.\n"
"\n"
"\n"
"The results of this auction are null and void. The seller is under no\n"
"obligation to complete this sale. If you do decide to complete this sale,\n"
"you are doing so at your own risk. eBay has taken this action in the\n"
"interests of protecting all parties involved.\n"
"\n";


//
//	AdminEndAuction
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
void clseBayApp::AdminEndAuction(clsItem *pItem,
								 clsUser *pEndingUser,
								 char *pComment,
								 bool refundFees)
{

	time_t							nowTime;
	clsUser							*pSeller;
	clsAccount						*pSellerAccount;
	AccountDetailVector				accountDetail;
	AccountDetailVector::iterator	i;
	clsAccountDetail				*pDetail;

	// The following are for emailing the bidders
	// of the victim auction.
	list<int>						lBidders;
	list<int>::iterator				ii;
	clsUser							*pBidder;

	clsMail							*pMail;
	ostrstream						*pMailStream;
	char							subject[512];

	//inna 12/17, move this call above Set Notice time,in case it stops
	//on that line no FVF flag will get update first!
	
	// We ALWAYS set the magic flag which tells us NOT to
	// bill the user for the auction
	pItem->SetNoFinalValueFee(true);

	nowTime	= time(0);

	// We ALWAYS end the auction (duh!)
	pItem->SetNewEndTime(nowTime);

	// We ALWAYS set the magic flag which tells us NOT to 
	// send an end of auction notice
    pItem->SetNoticeTime(nowTime);



	// If we need to, refund the fees
	if (refundFees)
	{
		pSeller	= mpUsers->GetUser(pItem->GetSeller());

		if (!pSeller)
		{
			*mpStream <<	"<h2>"
							"Error. Unable to get seller id "
					  <<	pItem->GetSeller()
					  <<	"</h2>";
			return;
		}

		pSellerAccount	= pSeller->GetAccount();

		if (!pSellerAccount)
		{
			*mpStream <<	"<h2>"
							"Error. Unable to get account for seller id "
					  <<	pItem->GetSeller()
					  <<	"</h2>";
			return;
		}

		// Now, get the account records for the item
		pSellerAccount->GetAccountDetailForItem(pItem->GetId(),
												&accountDetail);


		for (i = accountDetail.begin();
			 i != accountDetail.end();
			 i++)
		{
			switch ((*i)->mType)
			{
				case	AccountDetailFeeInsertion:
					pDetail	=	new clsAccountDetail(AccountDetailCreditInsertion,
													 -(*i)->mAmount,
													 NULL);
					gApp->GetDatabase()->AddAccountDetail(pSeller->GetId(),
														  pDetail);

					pSellerAccount->AdjustBalance((*i)->mAmount);

					// Take care of cross reference
					gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
															pItem->GetId());

					delete	pDetail;
					
					break;

				case	AccountDetailFeeBold:
					pDetail	=	new clsAccountDetail(AccountDetailCreditBold,
													 -(*i)->mAmount,
													 NULL);
					gApp->GetDatabase()->AddAccountDetail(pSeller->GetId(),
														  pDetail);

					pSellerAccount->AdjustBalance((*i)->mAmount);

					// Take care of cross reference
					gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
															pItem->GetId());

					delete	pDetail;
					
					break;

				case	AccountDetailFeeFeatured:
					pDetail	=	new clsAccountDetail(AccountDetailCreditFeatured,
													 -(*i)->mAmount,
													 NULL);
					gApp->GetDatabase()->AddAccountDetail(pSeller->GetId(),
														  pDetail);

					pSellerAccount->AdjustBalance((*i)->mAmount);

					// Take care of cross reference
					gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
															pItem->GetId());

					delete	pDetail;
					
					break;

				case	AccountDetailFeeCategoryFeatured:
					pDetail	=	new clsAccountDetail(AccountDetailCreditCategoryFeatured,
													 -(*i)->mAmount,
													 NULL);
					gApp->GetDatabase()->AddAccountDetail(pSeller->GetId(),
														  pDetail);

					pSellerAccount->AdjustBalance((*i)->mAmount);

					// Take care of cross reference
					gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
															pItem->GetId());

					delete	pDetail;
					
					break;

				// There's a tiny chance the final value fee was billed before
				// we got the flag set. Handle that.
				case	AccountDetailFeeFinalValue:
					pDetail	=	new clsAccountDetail(AccountDetailCreditFinalValue,
													 -(*i)->mAmount,
													 NULL);
					gApp->GetDatabase()->AddAccountDetail(pSeller->GetId(),
														  pDetail);

					pSellerAccount->AdjustBalance((*i)->mAmount);

					// Take care of cross reference
					gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
															pItem->GetId());

					delete	pDetail;
					
					break;


				default:
					break;
			}
		}

		// All done with account, clean up
		for (i = accountDetail.begin();
			 i != accountDetail.end();
			 i++)
		{
			delete (*i);
		}

		accountDetail.erase(accountDetail.begin(),
							accountDetail.end());

		delete	pSellerAccount;

	}

	delete	pSeller;

	// 
	// Let the bidders know it's over.
	//
	pItem->GetBidders(&lBidders);

	if (lBidders.size() == 0)
		return;

	// We build the mail ONCE, and send it out lots ;-)
	sprintf(subject,
			"%s Auction Ended - Item %d: %s",
			mpMarketPlace->GetCurrentPartnerName(),
			pItem->GetId(),
			pItem->GetTitle());

	pMail	= new clsMail;

	pMailStream	= pMail->OpenStream();

	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	*pMailStream <<	"Dear Bidder on "
				 << mpMarketPlace->GetCurrentPartnerName()
				 <<	" auction #"
				 <<	pItem->GetId()
				 <<	","
					"\n\n"
					"DO NOT REPLY TO THIS MESSAGE. SEE BELOW FOR INSTRUCTIONS\n"
					"IF THERE IS A PROBLEM.\n"
					"\n"
				 <<	"The auction:\n"
					"\n"
				 <<	pItem->GetId()
				 <<	"\t"
				 <<	pItem->GetTitle()
				 <<	"\n"
					"\n"
				 <<	AdminEndOfAuctionText
				 << "\n"
				 <<	mpMarketPlace->GetThankYouText()
				 <<	"\n"
					"\t"
				 <<	mpMarketPlace->GetHomeURL()
				 <<	"\n"
				 <<	ends;



	// Now, we spam ;-)
	for (ii = lBidders.begin();
		 ii != lBidders.end();
		 ii++)
	{
		pBidder	= mpUsers->GetUser((*ii));

		if (!pBidder)
			continue;

		if (pBidder->SendEndofAuction()) { 

			pMail->Send(pBidder->GetEmail(), 
						(char *)mpMarketPlace->GetConfirmEmail(),
						subject);
		}
		delete pBidder;
		pBidder	= NULL;
	}

	// Allllll done!
	delete	pMail;

	lBidders.erase(lBidders.begin(), lBidders.end());

	return;
};



void clseBayApp::EndAuctionAndCreditFees(CEBayISAPIExtension *pServer,
										 char *pItemId,
										 eBayISAPIAuthEnum authLevel)
{
	int				itemId;
	
	// Setup
	SetUp();

	// Title
	*mpStream <<	"<head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative End Auction And Credit Fees for "
			  <<	pItemId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<p>";

	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	// End Dat Auction
	itemId	= atoi(pItemId);

	if (itemId == 0)
	{
		*mpStream <<	"<h2>"
						"Error. Item #"
				  <<	pItemId
				  <<	" is invalid."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	mpItem	= mpItems->GetItem(itemId);

	if (!mpItem)
	{
		*mpStream <<	"<h2>"
						"Error. Item #"
				  <<	itemId
				  <<	" is invalid."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	
	AdminEndAuction(mpItem, NULL, NULL, true);

	// Tell them it worked!
	*mpStream <<	"<p>"
					"<font color=red size=+1>Auction "
			  <<	itemId
			  <<	" has been ended and all fees refunded! </font>"
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();


	CleanUp();

	return;
}

	