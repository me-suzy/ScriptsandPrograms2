/*	$Id: clsBidEngineDutch.cpp,v 1.14.2.4.28.1 1999/08/01 03:02:17 barry Exp $	*/
//
//	Class:	clsBidEngineDutch
//
//	Author:	Tini Widjojo (tini@ebay.com)
//
//	Function:
//
//		Bidding engine for the classic "Dutch" auction format.
//
// Modifications:
//				- 08/05/97 tini	- Cloned off clsBidEngineCHinese
//				- 07/12/99 beth - changed wording/layout as per Gillian Judge
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

// This pragma avoid annoying warning messages
// about overlength names generated for STL
#pragma warning( disable : 4786 )

#include "eBayKernel.h"
#include "clsBidEngineDutch.h"
#include "clsBidResult.h"
#include "clsMail.h"
#include "clsAnnouncements.h"
#include "hash_map.h"
#include "clsCurrencyWidget.h"
#include "clseBayTimeWidget.h"		// petra

//
// CTOR
//
// Let the superclass do all the work
//
clsBidEngineDutch::clsBidEngineDutch(clsItem *pItem) :
clsBidEngine(pItem)
{
	return;
}

//
// DTOR
//
clsBidEngineDutch::~clsBidEngineDutch()
{
	return;
}

// sort_bid_user
//
//	A private sort routine to group all bids
//	from a user together, ordered by time.
//
static bool sort_bid_user_value(clsBid *pA, clsBid *pB)
{
	if (pA->mUser < pB->mUser)
	{
		return true;
	}
	if (pA->mUser == pB->mUser)
	{
		if (pA->mValue > pB->mValue)
			return true;
		
		if (pA->mValue == pB->mValue)
		{
			if (pA->mTime < pB->mTime)
				return true;
		}
	}
	
	return false;
}

//	Sorts the bids value decending, time ascending,
//	so the higest, soonest bid is first
//
static bool sort_bid_amount(clsBid *pA, clsBid *pB)
{
	if (pA->mAmount > pB->mAmount)
		return true;
	
	if (pA->mAmount == pB->mAmount)
	{
		if (pA->mTime < pB->mTime)
			return true;
	}
	return false;
}
//
// ProposeBid
//
//	Propose bid validates a proposed bid by a user.
//	The process is as follows:
//
//	1.	Determines if the user has the right to bid at
//		all in this marketplace
//	2.	Determines if the user has the right to bid on
//		this item. Currently, if the marketplace will
//		accept this user, then we will..
//	3.	Computes the minimum acceptable bid for the item
//		at this time, which is the start price of the item.

clsBidResult *clsBidEngineDutch::ProposeBid(
											clsBid *pBid,
											ostream	*pStream,
											clsUser *pHelperUser
											)
{
	clsBidResult	*pResult			= NULL;
	clsBid			*pHighBidForUser	= NULL;
	
	double			currentPrice;
	double			minimumAcceptableBid;
	double			roundedMaxBid;
	
	// Time fields
	time_t			curtime;
	time_t			endTime;
	const struct tm	*pTimeAsTm;
	char			cOldBidDate[16];
	char			cOldBidTime[32];
	
	// Let's compose a bid result
	pResult	= new clsBidResult;
	pResult->mBidAccepted		= false;
	pResult->mBidChanged		= false;
	pResult->mOutBid			= false;
	pResult->mQuantity			= pBid->mQuantity;
	pResult->mMaxBid			= pBid->mAmount;
	pResult->mBid				= 0;
	pResult->mBidIncrement		= 0;
	
	
	// Let's make sure the item's still open for 
	// bidding
	curtime	= time(0);
	endTime	= mpItem->GetEndTime();
	
	if (curtime > endTime)
	{
		pResult->mBidAccepted	= false;
		if (pStream)
		{
			// First, we convert the times
			pTimeAsTm	= localtime(&endTime);
			clseBayTimeWidget timeWidget (mpMarketPlace, 1, -1, endTime);	// petra
			if (pTimeAsTm)
			{
				timeWidget.EmitString (cOldBidDate);		// petra
				timeWidget.SetDateTimeFormat (-1, 2);		// petra
				timeWidget.EmitString (cOldBidTime);		// petra
// petra				strftime(cOldBidDate, sizeof(cOldBidDate),
// petra					"%m/%d/%y",
// petra					pTimeAsTm);
// petra				
// petra				strftime(cOldBidTime, sizeof(cOldBidTime),
// petra					"%H:%M:%S %z",
// petra					pTimeAsTm);
			}
			else
			{
				strcpy(cOldBidDate, "*Error*");
				strcpy(cOldBidTime, "*Error*");
			}
			
			
			*pStream  <<	"<h2>Cannot proceed</h2>"
				"This auction ended on "
				<<	cOldBidDate
				<<	" at "
				<<	cOldBidTime
				<<	", and bidding is closed for this item.";
		}
		return pResult;
	}
	
	
	// See if we need to get a user object. We store
	// the result in an instance variable so that we
	// can use it later, which is most applicable
	// in the case of AcceptBid
	if (!pHelperUser)
	{		
		mpUser	= mpUsers->GetUser(pBid->mUser);
		//
		// If a user isn't returned here, then it
		// seems like we should emit an error. But,
		// then the caller hasn't adhered to the
		// contract, and there's something REALLY
		// wrong here.
		//
		if (!mpUser)
		{
			pResult->mBidAccepted	= false;
			// we don't have user for sure, 
			// since makebid won't pass user id anymore, but we need contiune --vicki
			//	return pResult; 
		}
	}
	else
		mpUser	= pHelperUser;
	
	// Ask the marketplace if it's ok for this user to bid
	// add caheck user first -- vicki
	if (mpUser && !mpMarketPlace->UserCanBid(mpUser, pStream))
	{
		pResult->mBidAccepted	= false;
		return	pResult;
	}
	
	//	Compute the minimum acceptable bid for this item
	// for dutch auctions, currentPrice is 0 if there's quantity
	// left to bid on.
	currentPrice	= mpItem->GetPrice();
	if (currentPrice == 0)
	{
		currentPrice	= mpItem->GetStartPrice();
	}
	
	currentPrice	= RoundToCents(currentPrice);
	
	// Before we go any furthur, let's see if the bid amount
	// is less than the advertised minimum bid. If it's not,
	// let's raise hell now.
	/*
	if (pBid->mAmount < currentPrice)
	{
	if (pStream)
	{
	*pStream <<	"<h2>Problem with bid amount</h2>";
	
	  
		*pStream <<	"Your Bid of $"
		<<	pBid->mAmount
		<<	" is less than the minimum bid.";
		*pStream << "\n"
		"Please go back and bid at least "
		"<b>"
		<<	currentPrice
		<<	"</b>"
		"."
		" (Please type numbers and the decimal point (.) only.)"
		"<p>";
		}
		
		  pResult->mBidAccepted	= false;
		  return pResult;
		  }
	*/
	
	// Note: This engine handles only multiple item auctions.
	// Single item auctions are handled by clsBidEngineChinese.
	// minimumAcceptableBid = GetStartPrice() if there's quantity left
	// since we can't tell bid quantity from actual quantity here we
	// assume StartPrice always.
	minimumAcceptableBid	= 
		mpItem->GetStartPrice();
	
	pResult->mMinimumAcceptableBid = RoundToCents(minimumAcceptableBid);
	
	// maximum bid is for regular auctions, not dutch. use pBid->mAmount.
	roundedMaxBid	= pBid->mAmount;
	
	// Now, let's see if the user's bid qualifies for the item's
	// current price
	if (roundedMaxBid < pResult->mMinimumAcceptableBid)
	{
		if (pStream)
		{
			*pStream <<	"<h2>Problem with bid amount</h2>";

			*pStream <<	"Your Bid of ";


			clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), roundedMaxBid);
			currencyWidget.EmitHTML(pStream);

			*pStream <<	" is less than the minimum bid.";

			*pStream << "\n"
				"Please go back and bid at least "
				"<b>"
				<<	pResult->mMinimumAcceptableBid
				<<	"</b>"
				"."
				" (Please type numbers and the decimal point (.) only.)"
				"<p>";
		}
		
		pResult->mBidAccepted	= false;
		return pResult;
	}
	
	// Let's see if the user is underbidding themselves
	// logic is the same for dutch and chinese auctions
	// high bids are ordered by value, not bid amount (value = bid x quantity).
// trouble ?? vicki
	if (mpUser)
	{
	if (mpItem->GetBidCount() > 0)
		pHighBidForUser	= mpItem->GetHighBidForUser(mpUser->GetId());
	}
	if (pHighBidForUser)
	{
		if (pHighBidForUser->mValue >= pBid->mValue)
		{
			pResult->mBidAccepted	= false;
			
			if (pStream)
			{
				// First, we convert the times
				pTimeAsTm	= localtime(&pHighBidForUser->mTime);
				clseBayTimeWidget timeWidget (mpMarketPlace, 1, -1, pHighBidForUser->mTime);	// petra
				if (pTimeAsTm)
				{
					timeWidget.EmitString (cOldBidDate);		// petra
					timeWidget.SetDateTimeFormat (-1, 2);		// petra
					timeWidget.EmitString (cOldBidTime);		// petra
// petra					strftime(cOldBidDate, sizeof(cOldBidDate),
// petra						"%m/%d/%y",
// petra						pTimeAsTm);
// petra					
// petra					strftime(cOldBidTime, sizeof(cOldBidTime),
// petra						"%H:%M:%S %z",
// petra						pTimeAsTm);
				}
				else
				{
					strcpy(cOldBidDate, "*Error*");
					strcpy(cOldBidTime, "*Error*");
				}
				
				*pStream  <<	"<h2>"
								"Problem with Bid"
								"</h2>"
								"On "
						  <<	cOldBidDate
						  <<	" at "
						  <<	cOldBidTime
						  <<	", you entered a bid for "
						  <<	pHighBidForUser->mQuantity
						  <<	" items at ";

				clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), pHighBidForUser->mAmount);
				currencyWidget.EmitHTML(pStream);

				*pStream  <<	" (for a total value of ";

				currencyWidget.SetNativeAmount(pHighBidForUser->mValue);
				currencyWidget.EmitHTML(pStream);
				
				*pStream  <<	"). This exceeds or is equal to your current bid for "
						  <<	pBid->mQuantity
						  <<	" items at ";
				
				currencyWidget.SetNativeAmount(pBid->mAmount);
				currencyWidget.EmitHTML(pStream);
				
				*pStream  <<	"; therefore your current bid is not a valid bid."
						  <<	"Please go back and bid again!";

			}
			
			return pResult;
		}
	}
	
	// If we get here, as far as we're concered, the bid is valid.
	pResult->mBid			= roundedMaxBid;
	pResult->mBidAccepted	= true;
	
	// Force the bid time to be when we checked the bid against
	// the auction end.
	pBid->mTime				= curtime;
	
	return pResult;
}

//
// AcceptBid
//
//	Accept bid placed a bid by a user. The process is as 
//	follows:
//
//	1.	Calls ProposeBid to validate the bid
//	2.	Adds the bid to the database
//	3.  Adjusts the dutch high bidder table 
//  4.  Inform user and clean up
//  
clsBidResult *clsBidEngineDutch::AcceptBid(
										   clsBid *pBid,
										   ostream	*pStream,
										   clsUser *pHelperUser
										   )
										   
										   
{
	clsBidResult		*pResult;
	BidVector			vBids;
	BidVector::iterator	i;
	float				TotalValue;
	
	
	// ProposeBid will give us a preliminary evaulation
	// on the bid
	pResult		= ProposeBid(pBid, 
							 pStream,
							 pHelperUser);
	
	// Let's see if ProposeBid liked it!
	if (!pResult->mBidAccepted)
	{
		return pResult;
	}
	
	//
	// NOTE:	ProposeBid will have filled in the pResult
	//			for us!
	//
	// Ok, let's add the bid to the bid db
	mpItem->Bid(mpUser->GetId(), pResult->mQuantity,
		pResult->mMaxBid, pBid->mTime);
	
	// high bidder is actually latest bidder in dutch auction
	// do not reverse the order, because SetImmedBidCount saves
	// both changes to the database.
	// moved to adjust price
	//	mpItem->SetHighBidder(mpUser->GetId());
	
	// increment bidcount for item here instead of in setHighBidder
	//	mpItem->SetImmedBidCount(mpItem->GetBidCount() + 1);
	
	
	// let's get the highest bids for the item
	mpItem->GetBidsForItemSorted(&vBids);
	
	// There's no notice of being outbid in dutch auction;
	// nevertheless, we need to adjust the price of the item
	// at each accept bid
	AdjustPrice(&vBids);
	
	TotalValue = pResult->mBid * pResult->mQuantity;
	
	// Now, if the caller wants, we can chat with the user
	if (pStream)
	{
		// Begin Beth's changes for 07/12/99
		*pStream <<	"<h2>Your bid for "
				 <<	mpItem->GetTitle()
				 <<	" (item #"
				 <<	mpItem->GetId()
				 << ") is confirmed!</h2>"
					"<strong>Thank you</strong> for your bid."
					"<pre>"
					"\n"
					"Your bid was in the amount of:         ";

		clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), pResult->mBid);
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(pStream);

		*pStream << "\n"
				 << "Your bid was for quantity of:           "
					"<strong>"
				 <<	pResult->mQuantity
				 <<	"</strong>"
					"\n"
					"Total value of your bid is:            ";

		currencyWidget.SetNativeAmount(TotalValue);
		// Still set to bold...
		currencyWidget.EmitHTML(pStream);	
				 
		*pStream << "<strong>"
				    " (Quantity x Amount) </strong>"
					"\n"
					"</pre>"
					"<p>";

//		*pStream <<	"After processing all the open bids for\n"
//					"this item, the current bid price is "
//					"<strong>"
//					"$"
//				 <<	mpItem->GetPrice()
//				 <<	"</strong>"
//					"</pre>"
//					"<p>";

		if (mpItem->GetReservePrice() != 0)
		{
			*pStream <<	"<strong>Note:</strong>"
				" This is a "
				"<a href="
				"\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/buyerguide/bidding-type.html#reserve"
				"\""
				">"
				"reserve price auction."
				"</a>"
				"<p>";
		}
	}
	
	
	// No notice of being outbid needed; we can now pass out the news
	if (pStream)
	{
				/*			"<p>Current Dutch auction standings are always available at the bottom \n"
				" of the item description page, and you should "
				"<a href="
				"\""
				<<	mpMarketPlace->GetCGIPath(PageViewBidsDutchHighBidder)
				<<	"eBayISAPI.dll?ViewBidsDutchHighBidder"
				"&item="
				<<	mpItem->GetId()
				<<	"#dutch"
				<<	"\""
				">"
				" go there now"
				"</a>"
				" to see the standings after this bid." */
			*pStream 
			 <<	"<p>\n"
				"<strong>Important:</strong> "
				"Please note that the listings on the index pages "
				"are not updated right away, but your bid has been "
				"recorded. "
				"Please also take a moment to note the ending date of your "
				"auction. If you are the high bidder at midnight on this date, "
				"eBay will send you and the seller an email. You will then have "
				"<strong>three</strong> (3) business days to contact each other "
				"before you lose your position as high bidder."
				"\n</p>";
		// end of Beth's changes for 07/12/99
}
	
	// Either way, if we were the high bidder, also send out email
	NotifyBidder(pBid, pResult);
	
	
	// clean up the vector
	// clean up category vector
	for (i = vBids.begin();
	i != vBids.end();
	i++)
	{
		// Delete the category
		delete	(*i);
	}
	
	vBids.erase(vBids.begin(), vBids.end());
	
	return pResult;
}



//
//	AdjustPrice
//		Adjustprice recomputes the item's current price
//		which is the lowest winning bid for dutch auctions.
//		This is done as follows:
//		1.	If the user hasn't provided helpers, then
//			obtain all bids for the item.
//		2.  Sort and iterate over the bids to figure out the
//			n highest bids where n = quantity of item for sale
//		3.	Set the item's new price and put the list of high 
//			bidders in the database.
//
// * note * GetHighBids for dutch auctions returns the cached
// high bidders. 
// Should we put the logic above in CalculateHighBids
// for Dutch auctions so AdjustPrice then calls it and adjusts 
// the current price? 
// 

// * note * comment out usage of dutch high bidders table
// due to commit logic problem

void clsBidEngineDutch::AdjustPrice(BidVector *pVBids)
{
	double						proposedNewPrice;
	BidVector::iterator			vI;
	clsBid						*pHighBid = NULL;
	clsBid						*pBid;
	int							total_qty = 0;
	int							item_qty = mpItem->GetQuantity();
	
	BidVector					*pvOwnBids;
	BidVector					vAllBids;
	bool						gotOwnBids;
	int							bcount;
	
	// the hash map of people who have high bids for this item
	hash_map<const int, int, hash<int>, eqint>
		bidTracks;
	
	// hasherator
	hash_map<const int, int, hash<int>, eqint>::
		const_iterator			ii;
	
	if (pVBids	== NULL)
	{
		pVBids = new BidVector;
		gotOwnBids	= true;
	}
	else
		gotOwnBids	= false;
	
	// faster than GetHighestBidsForItem as in RecomputeDutchBids
	mpItem->GetBids(&vAllBids);
	
	// initialize pvOwnBids containing highest winning bids per user
	pvOwnBids = new BidVector;
	
	// sort by user and value of bid to get highest per user
	if (vAllBids.begin() != vAllBids.end())
		sort(vAllBids.begin(), vAllBids.end(), sort_bid_user_value);
	
	// Eliminate irrelevant entries
	for (vI	= vAllBids.begin();
	vI != vAllBids.end();
	vI++)
	{
		// only valid bids
		if ((*vI)->mAction == BID_BID ||
			(*vI)->mAction == BID_DUTCH_BID)
		{
			// push only the first one of this user
			pBid = (*vI);
			
			// let's see if we've seen this user before
			ii = bidTracks.find((const int)(pBid->mUser));
			
			// if not, make a tracker 
			// and use the record as high bidder, since its sorted
			// adjust the total_qty by the amount
			if (ii == bidTracks.end())
			{
				bidTracks[pBid->mUser] = 0;
				pvOwnBids->push_back(pBid);
			}
			
			else
				// found user before; delete
				delete pBid;
		}
		else
			// not bids, only retractions/cancellations. Delete
			delete (*vI);
	}
	
	// Erase so later destruction of vector doesn't
	// destroy bids
	vAllBids.erase(vAllBids.begin(), vAllBids.end());
	
	// clean up
	bidTracks.erase(bidTracks.begin(),
		bidTracks.end());
	
	// if there aren't any non-retracted bids, there isn't any high bidder
	if (pvOwnBids->size() == 0)
	{
		// no high bidder, no current price, bidcount = 0
		/* Note: Use SetNewHighBidderAndBidCount instead of SetNewHighBidder and
		SetNewBidCount for better performance */
		mpItem->SetNewHighBidderAndBidCount(0, 0, 0);
		// mpItem->SetNewBidCount(0);
		
		// clean up
		delete	pvOwnBids;
		
		if (gotOwnBids)
		{
			delete	pVBids;
		}
		return;
	}
	
	// Sort remaining if any
	if (pvOwnBids->begin() != pvOwnBids->end())
		sort(pvOwnBids->begin(), pvOwnBids->end(), sort_bid_amount);
	
	// size of the vector = number of bids for the item
	bcount = pvOwnBids->size();
	
	// prep the high bidder's table by deleting all entries for the item
	//	mpItem->DeleteDutchHighBidder();
	
	// iterate through pVBids for the winning bids
	// till quantity is exhausted
	for (vI = pvOwnBids->begin();
	vI != pvOwnBids->end();
	vI++)
	{
		pBid = (*vI);
		
		if (total_qty < item_qty)
		{
			// insert into result vector, erase from original vector
			pVBids->push_back(pBid);
			
			// increment quantity - accounted for in the bid
			total_qty = total_qty + pBid->mQuantity;
			
			// save high bidder
			pHighBid = pBid;
		}
		else
			delete pBid;
		
	}
	
	// current price = lowest winning bid or start price if qty is not exhausted
	assert(pHighBid);
	if (total_qty >= item_qty)
	{
		proposedNewPrice = pHighBid->mAmount;
		// Round
		proposedNewPrice	= RoundToCents(proposedNewPrice);
		
	}
	else 
		proposedNewPrice	= mpItem->GetStartPrice();
	
	// Set new high bidder and price in item
	// Set new high bidder and price and bidcount in item
	mpItem->SetNewHighBidderAndBidCount(pHighBid->mUser, proposedNewPrice, bcount);
	// mpItem->SetNewBidCount(bcount);
	
	// clean up our own vector, don't delete the bids because
	// its used by another.
	pvOwnBids->erase(pvOwnBids->begin(), pvOwnBids->end());
	
	delete	pvOwnBids;
	
	// If we got our own bids, clean up
	if (gotOwnBids)
	{
		for (vI	= pVBids->begin();
		vI != pVBids->end();
		vI++)
		{
			delete	(*vI);
		}
		
		pVBids->erase(pVBids->begin(), pVBids->end());
		
		delete	pVBids;
	}
	return;	
}


void clsBidEngineDutch::NotifyBidder(clsBid *pBid, 
									 clsBidResult *pBidResult)
{

	// check to see if bid notices are turned off
#ifdef _MSC_VER
	if(!mpMarketPlace->GetMailControl()->GetMailBidNoticesState(bidNoticesDutch))
		return;
#endif

	// hack
//	return;

	clsMail		*pMail;
	ostrstream	*pMailStream;
	char		subject[512];
	clsAnnouncement			*pAnnouncement;
	
	// Date conversions -- ick
	time_t		endTime;
	char		cEndDateTime[32];
	char*		pTemp;
	
	endTime		= mpItem->GetEndTime();
	clsUtilities::GetDateTime(endTime, &cEndDateTime[0]);
	
	pMail	= new clsMail;
	
	pMailStream	= pMail->OpenStream();
	
	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);
	
	
	// emit general announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Header, 
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	}
	
	// emit bid notice announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(BidNotice,Header, 
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	}
	
	*pMailStream <<	"\nDear "
				 <<	mpUser->GetUserId()
				 <<	","
					"\n\n"
					"Here's a quick note to confirm your "
				 <<	mpMarketPlace->GetCurrentPartnerName()
				 << " bid!\n"
					"\n";
	

	
	*pMailStream <<	mpItem->GetTitle()
				 <<	" (item #"
				 <<	mpItem->GetId()
				 << ")\n"
					"\n";
	
	*pMailStream <<	"Your bid was in the amount of:         ";

	clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), pBidResult->mBid);
// petra	currencyWidget.SetForMail(true);
	currencyWidget.EmitHTML(pMailStream);

	*pMailStream << "\n"
				 << "The quantity you bid was: "
				 << pBidResult->mQuantity;

	if (pBidResult->mBidChanged)
	{
		*pMailStream <<	"\n"
						"  Your maximum bid of ";

		currencyWidget.SetNativeAmount(pBid->mAmount);
		// Still set for mail...
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	" was rounded down to the next bid increment of ";

		currencyWidget.SetNativeAmount(pBidResult->mBidIncrement);
		// Still set for mail...
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	", yielding ";
			
		currencyWidget.SetNativeAmount(pBidResult->mMaxBid);
		// Still set for mail...
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream << "\n";
	}
	
	*pMailStream <<	"\n"
		"The auction closes on:                 "
		<< cEndDateTime
		<<	"\n"
		<< "Web location used:                     "
		<< mpMarketPlace->GetHomeURL()
		<<	"\n";
	
	if (mpItem->GetQuantity() == 1)
	{
		*pMailStream << "After processing all open bids for\n"
						"this item, the current bid price is:   ";

		currencyWidget.SetNativeAmount(mpItem->GetPrice());
		// Still set for mail...
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream << "\n\n";
		if (mpItem->GetReservePrice() != 0)
		{
			*pMailStream <<	"Note: This is a reserve price auction.";
		}
	}
	
	*pMailStream <<	mpMarketPlace->GetCGIPath(PageViewItem);
	*pMailStream << "eBayISAPI.dll?ViewItem&item="
		<< mpItem->GetId();
	
	*pMailStream <<	"\n\n"
					"*If you placed this bid by mistake, here's what to do:\n"
					"Check out the information on retracting your bid, which "
					"can occasionally be done. You'll find this among the "
					"services listed in the Buyers section.\n"
					"\n"
					"Best of luck in your bidding--trade on!\n\n";
	
	// emit general footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Footer, 
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	}
	
	// emit bidNotice footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(BidNotice,Footer, 
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	}
	
    if (mpUser->SendBid()) { 
		
		sprintf(subject,
			"%s Bid Confirmation for item %d",
			mpMarketPlace->GetCurrentPartnerName(),
			mpItem->GetId());
		
		pMail->Send(mpUser->GetEmail(), 
			(char *)mpMarketPlace->GetAdminEmail(),
			subject);
    }
	
	delete	pMail;
	return;
}

