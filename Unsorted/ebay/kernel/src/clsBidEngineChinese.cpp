/*	$Id: clsBidEngineChinese.cpp,v 1.16.2.6.28.1 1999/08/01 03:02:17 barry Exp $	*/
//
//	Class:	clsBidEngineChinese
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Bidding engine for the classic "Chinese" or
//		"silent" auction format.
//
// Modifications:
//				- 02/10/97 michael	- Created
//				- 23/09/97 tini - added reserve price yank up and
//					reserve has been met
//				- 07/12/99 beth - changed wording/layout as per Gillian Judge
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "eBayKernel.h"

#include "clsBidEngineChinese.h"
#include "clsBidResult.h"
#include "clsMail.h"
#include "clsAnnouncements.h"
#include "clsCurrencyWidget.h"
#include "clseBayTimeWidget.h"		// petra

//
// CTOR
//
// Let the superclass do all the work
//
clsBidEngineChinese::clsBidEngineChinese(clsItem *pItem) :
	clsBidEngine(pItem)
{
	return;
}

//
// DTOR
//
clsBidEngineChinese::~clsBidEngineChinese()
{
	return;
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
//		at this time, which is:
//		a.	If NO bids have been made, the "start price"
//			for the item.
//		b.	If bids have been made, the item's current
//			price, plus the bid increment at the item's
//			current price.
//	4.	Rounds the user's maximum bid DOWN to an even multiple
//		of the bid increment.
//	5.	Determines if the rounded maximum bid (computed in #3) 
//		is AT LEAST the minimum acceptable bid amount (computed
//		in #2). 
//
clsBidResult *clsBidEngineChinese::ProposeBid(
									clsBid *pBid,
									ostream	*pStream,
									clsUser *pHelperUser
											  )
{
	clsBidResult	*pResult			= NULL;
#ifdef NOT
	clsBid			*pHighBidForUser	= NULL;
#endif

	double			currentPrice;
	double			minimumAcceptableBid;
	double			roundedMaxBid;
	int				bidMultiple;

	// Time fields
	time_t			curtime;
	time_t			endTime;
	const struct tm	*pTimeAsTm;
	char			cOldBidDate[16];
	char			cOldBidTime[32];


	clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), 0); // set below

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
			clseBayTimeWidget timeWidget (mpMarketPlace, 1, -1, endTime);	// petra

			// First, we convert the times
			pTimeAsTm	= localtime(&endTime);
			if (pTimeAsTm)
			{
				timeWidget.EmitString (cOldBidDate);	// petra
				timeWidget.SetDateTimeFormat (-1, 2);	// petra
				timeWidget.EmitString (cOldBidTime);	// petra
// petra				strftime(cOldBidDate, sizeof(cOldBidDate),
// petra						"%m/%d/%y",
// petra						pTimeAsTm);
// petra
// petra				strftime(cOldBidTime, sizeof(cOldBidTime),
// petra						"%H:%M:%S %z",
// petra						pTimeAsTm);
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
//			return pResult; //make bid will not pass user -- vicki
		}
	}
	else
		mpUser	= pHelperUser;

	// Ask the marketplace if it's ok for this user to bid
	// chaneck whether we user first -- vicki
	if (mpUser && !mpMarketPlace->UserCanBid(mpUser, pStream))
	{
		pResult->mBidAccepted	= false;
		return	pResult;
	}

	//	Compute the minimum acceptable bid for this item
	currentPrice	= mpItem->GetPrice();
	if (currentPrice == 0)
	{
		currentPrice	= mpItem->GetStartPrice();
	}

	currentPrice	= RoundToCents(currentPrice);

	// Before we go any furthur, let's see if the bid amount
	// is less than the advertised minimum bid. If it's not,
	// let's raise hell now.
	if (pBid->mAmount < currentPrice)
	{
		if (pStream)
		{
			*pStream <<	"<h2>Problem with bid amount</h2>";

			*pStream <<	"Your bid of ";

			currencyWidget.SetNativeAmount(pBid->mAmount);
			currencyWidget.EmitHTML(pStream);
					 	
			*pStream <<	" is less than the minimum bid.";

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


	pResult->mBidIncrement	= 
				mpItem->GetBidIncrement(); // from current price

	
	// Note: This engine handles only single - item auctions.
	// Multiple item auctions are handled by clsBidEngineDutch.
	if (mpItem->GetBidCount() > 0 &&
		mpItem->GetPrice() > 0)
	{
		minimumAcceptableBid	= 
			currentPrice + pResult->mBidIncrement;
	}
	else
	{
		minimumAcceptableBid	= 
			mpItem->GetStartPrice();
	}

	minimumAcceptableBid	= RoundToCents(minimumAcceptableBid);
	pResult->mMinimumAcceptableBid = minimumAcceptableBid;

	// *** NOTE ***
	// 08/08/97 No MORE Bid Rounding!
	// *** NOTE ***
	bidMultiple		= (int)((pBid->mAmount - currentPrice) / pResult->mBidIncrement);
	roundedMaxBid	= currentPrice + (bidMultiple * pResult->mBidIncrement);
	
	// Just pretend their bid amount was correctly rounded
	roundedMaxBid	= pBid->mAmount;

	pResult->mMaxBid		= roundedMaxBid;
	if (roundedMaxBid != pBid->mAmount)
	{
		pResult->mBidChanged	= true;
	}
	else
	{
		pResult->mBidChanged	= false;
	}

		
	// Now, let's see if the user's bid qualifies for the item's
	// current price
	if (roundedMaxBid < minimumAcceptableBid)
	{
		if (pStream)
		{
			*pStream <<	"<h2>Problem with bid amount</h2>";

			*pStream <<	"Your bid of ";

			currencyWidget.SetNativeAmount(roundedMaxBid);
			currencyWidget.EmitHTML(pStream);
			
			*pStream <<	" is less than the minimum bid.";

			if (pResult->mBidChanged)
			{
				*pStream <<	"\n"
							"<b>"
							"  Your bid of ";

			currencyWidget.SetNativeAmount(pBid->mAmount);
			currencyWidget.EmitHTML(pStream);

			*pStream	 <<	" was rounded down to the next bid increment of ";

			currencyWidget.SetNativeAmount(pResult->mBidIncrement);
			currencyWidget.EmitHTML(pStream);

			*pStream	 << ", yielding ";

			currencyWidget.SetNativeAmount(roundedMaxBid);
			currencyWidget.EmitHTML(pStream);

			*pStream	 <<	"</b>"
							"\n";
			}

			*pStream << "\n"
						"Please go back and bid at least "
						"<b>"
					 <<	minimumAcceptableBid
					 <<	"</b>"
						"."
						" (Please type numbers and the decimal point (.) only.)"
						"<p>";
		}
			
		pResult->mBidAccepted	= false;
		return pResult;
	}

#ifdef NOT
	// Let's see if the user's underbidding themselves
	// chaneck whether we have user first -- vicki
	if (mpUser && mpItem->GetBidCount() > 0)
		pHighBidForUser	= mpItem->GetHighBidForUser(mpUser->GetId());
	
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
					timeWidget.EmitString (cOldBidDate);	// petra
					timeWidget.SetDateTimeFormat (-1, 2);	// petra
					timeWidget.EmitString (cOldBidTime);	// petra
// petra					strftime(cOldBidDate, sizeof(cOldBidDate),
// petra							"%m/%d/%y",
// petra							pTimeAsTm);
// petra
// petra					strftime(cOldBidTime, sizeof(cOldBidTime),
// petra							"%H:%M:%S %z",
// petra							pTimeAsTm);
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

				currencyWidget.SetNativeAmount(pHighBidForUser->mAmount);
				currencyWidget.EmitHTML(pStream);
					  	
				*pStream  <<	" (for a value of ";

				currencyWidget.SetNativeAmount(pHighBidForUser->mValue);
				currencyWidget.EmitHTML(pStream);			  	
						  	
				*pStream <<		"). This exceeds or is equal to your current bid for "
						  <<	pBid->mQuantity
						  <<	" items at ";

				currencyWidget.SetNativeAmount(pBid->mAmount);
				currencyWidget.EmitHTML(pStream);			  	

				*pStream  <<	", and is therefore not a valid bid."
						  <<	"Please go back and bid again!";
			}

			delete pHighBidForUser;
			return pResult;
		}

		delete	pHighBidForUser;
	}
#endif /* NOT */


	// If we get here, as far as we're concered, the bid is valid.
	pResult->mBid			= minimumAcceptableBid;
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
//	3,	Gets the two highest bids for the item. Note that
//		this includes 
//
clsBidResult *clsBidEngineChinese::AcceptBid(
									clsBid *pBid,
									ostream	*pStream,
									clsUser *pHelperUser
											  )


{
	clsBidResult		*pResult;
	BidVector			vBids;
	BidVector::iterator	vI;
	clsBid				*pHighestBid;
	clsBid				*pNextHighestBid;
	char				*cleanTitle = NULL;
	time_t		reserveStartTime;
	struct tm	timeAsResStart  = { 0, 0, 23, 9, 9, 97, 0, 0, 0 };
//	struct tm	timeAsResStart  = { 0, 0, 23, 8, 9, 97 };

	// Related to bids
	// Added by charles 01/29/98
	// changed by tini to use Item status as ITEM_BID_BY_SELLER_FLAG

	reserveStartTime = mktime(&timeAsResStart);

	// ProposeBid will give us a preliminary evaulation
	// on the bid
	pResult		= ProposeBid(pBid, 
							 pStream,
							 pHelperUser);
/*
	// check for seller bidding on his/her own items twice
	// Added by Charles 01/29/98
	// check if bidder is seller and seller has already bid
	if (0 ) // mpItem->GetOwner() == mpUser->GetId())
	{
		// We have to find out if this owner (seller)
		// have already bid once on his/her own item

		if (mpItem->GetItemBidBySellerFlag())
		{
			// the owner is not allowed to bid again !!!
			*pStream	<<	"<h2>Bid Rejected. </h2>\n"
							"<p>Sorry, you cannot bid more than once on your own listed item.</p>";

			pResult->mBidAccepted	= false;
			return pResult;
		}
		else
			mpItem->SetItemBidBySellerFlag(true);
		// End of the code Added by Charles 01/29/98
	}
*/

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

	// Let's get the highest bids for the item
	mpItem->GetBidsForItemSorted(&vBids);

	// Now, establish the highest and next highest bids
	pHighestBid		= NULL;
	pNextHighestBid	= NULL;

	if (vBids.size() > 0)
	{
		vI			=	vBids.begin();
		pHighestBid	= (*vI);

		vI++;
		if (vI != vBids.end())
			pNextHighestBid	= (*vI);
	}

	// Our first test is to see if there's an existing
	// high bid. If there's not, well, there's something
	// seriously wrong here.
	if (pHighestBid)
	{
		// Now, let's see if the current high bidder
		// is the same as the "current" bidder. If not,
		// we've been outbid!
		if (pHighestBid->mUser != pBid->mUser)
		{
			pResult->mOutBid	= true;
		}
		else
		{
			pResult->mOutBid	= false;
		}
	}
	else
	{
		// Silly case
		pResult->mOutBid	= true;
	}


	// Whether we were outbid or not, we need to adjust
	// the price of the item
	AdjustPrice(&vBids);

	if (!pResult->mOutBid)
	{
		// If we didn't just outbid ourselves, let the 
		// old high bidder know they're a loser
		if (pNextHighestBid &&
			pNextHighestBid->mUser != pBid->mUser)
		{
			NotifyOutBid(pNextHighestBid->mUser); 
		}
	}

	// Now, if the caller wants, we can chat with the user
	if (pStream)
	{
		// Begin Beth's changes for 07/12/99
		cleanTitle = clsUtilities::StripHTML(mpItem->GetTitle());
		*pStream <<	"<h2>Your bid for "
				 <<	cleanTitle
				 <<	" (item #"
				 <<	mpItem->GetId()
				 << ") is confirmed";
		if (pResult->mOutBid)
		{
			*pStream <<	"</h2>";
		}
		else
		{
			*pStream <<	"!</h2>"
						"<strong>Thank you</strong> for your bid. "
						"<strong>You are the current high bidder"
						"</strong>! Don't worry &#151; if someone bids over your maximum, "
						"eBay will contact you.";
		}
		*pStream << "<pre>"
					"\n"
					"Your bid was in the amount of:         ";

		clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), pResult->mBid);
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(pStream);

		*pStream <<	"\n"
				 << "Your maximum bid was in the amount of: ";

		currencyWidget.SetNativeAmount(pResult->mMaxBid);
		// Still bold, still set to currency id...
		currencyWidget.EmitHTML(pStream);

		*pStream <<	"\n";

		delete [] cleanTitle;

		if (pResult->mBidChanged)
		{
				*pStream <<	"\n"
							"<b>"
							" Your bid of ";

				currencyWidget.SetNativeAmount(pBid->mAmount);
				// Still bold, still set to currency id...
				currencyWidget.EmitHTML(pStream);

				*pStream <<	" was rounded down to the next bid increment of ";

				currencyWidget.SetNativeAmount(pResult->mBidIncrement);
				currencyWidget.SetBold(false);
				currencyWidget.EmitHTML(pStream);
						  
				*pStream << ", yielding ";

				currencyWidget.SetNativeAmount(pResult->mMaxBid);
				currencyWidget.EmitHTML(pStream);
						 	
				*pStream << "</b>"
							"\n\n";
		}

		*pStream <<	"After processing all the open bids for\n"
					"this item, the current bid price is    ";

		currencyWidget.SetNativeAmount(mpItem->GetPrice());
		// Still bold, still set to currency id...
		currencyWidget.EmitHTML(pStream);

		*pStream <<	"</pre>"
					"<p>";

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
						"</a>";
						
			// notify bidder of reserve here

			if ((mpItem->GetReservePrice() <= mpItem->GetPrice())
				&& ((mpItem->GetStartTime() >= reserveStartTime)
				||  ((mpItem->GetSeller() == 65618) || 
				     (mpItem->GetSeller() == 158320) ||
					 (mpItem->GetSeller() == 230851))))
			{
				*pStream << "<strong> Reserve has been met! </strong>";
			}
		}
	}

	// Mason - revise this per E117.
	// If we were outbid, we can now pass out the news
	if (pStream)
	{
		if (pResult->mOutBid)
		{
			*pStream 
			  <<	"<p>\n"
			  <<	"Try again &#151; <strong>someone has outbid you!</strong> This "
					"means you'll need to "
					"<a href="
					"\""
			 <<		mpMarketPlace->GetCGIPath(PageViewItem)
			 <<		"eBayISAPI.dll?ViewItem"
					"&item="
			 <<		mpItem->GetId()
			 <<		"#BID"
			 <<		"\""
					">"
					" bid again"
					"</a>"
					". "
					"If you still want this item, try a higher maximum bid. And "
					"keep in mind that earlier maximum bids are favored over "
					"later bids if both are for the same amount. Good luck!"
					"<p>";
		}
		else
		{
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
		}
	}
	// end of Beth's changes for 07/12/99

	// Either way, if we were the high bidder, also send out email
	if (!pResult->mOutBid)
	{
		NotifyBidder(pBid, pResult);
	}

	// Clean up the bids
	// If we got our own bids, clean up
	for (vI	= vBids.begin();
		 vI != vBids.end();
		 vI++)
	{
		delete	(*vI);
	}

	vBids.erase(vBids.begin(), vBids.end());

	return pResult;
}

//
//	AdjustPrice
//		Adjustprice recomputes the item's current price.
//		This is done as follows:
//		1.	If the user hasn't provided helpers, then
//			obtain the highest bid and next highest bid
//			for the item.
//		2.	Compute a proposed new price for the item by
//			adding the bid increment to the amount of
//			the next highest bid.
//		3.	If the proposed new price is HIGHER than the
//			amount of the highest bid, then pin it to the
//			amount of the highest bid. Otherwise, leave
//			it alone.
//		4.	Set the item's new price and high bidder
//
void clsBidEngineChinese::AdjustPrice(BidVector *pVBids)
{
	BidVector::iterator	vI;
	clsBid				*pHighBid;
	bool				gotOwnBids;
	clsBid				*pNextHighestBid;
	double				proposedNewPrice;
	int					highBidder;
	int					bcount;
	time_t		reserveStartTime;
	struct tm	timeAsResStart  = { 0, 0, 23, 9, 9, 97, 0, 0, 0 };
//	struct tm	timeAsResStart  = { 0, 0, 23, 8, 9, 97 };

	// time new reserve auction rules start
	reserveStartTime = mktime(&timeAsResStart);


	// Let's see if we have to get our OWN bids
	if (pVBids == NULL)
	{
		pVBids	= new BidVector;
		mpItem->GetBidsForItemSorted(pVBids);
		gotOwnBids	= true;
	}
	else
		gotOwnBids	= false;

	// Let's get the highest bid, and the NEXT highest bid by 
	// ANOTHER user. First, set up.
	pHighBid		= NULL;
	highBidder		= 0;
	pNextHighestBid	= NULL;

	bcount = pVBids->size();

	if (pVBids->size() > 0)
	{
		// Highest bid is the first one in the vector
		vI				= pVBids->begin();
		pHighBid		= (*vI);
		highBidder		= pHighBid->mUser;


		// Now, loop until we find the NEXT bid NOT by
		// the same user
		while(1)
		{
			vI++;
			if (vI	== pVBids->end())
				break;

			if ((*vI)->mUser != pHighBid->mUser)
			{
				pNextHighestBid	= (*vI);
				break;
			}
		}

	}

	// If there's no highest bidder, then there are no bids
	// on the item, and the new price zero.
	if (!pHighBid)
	{
		proposedNewPrice	= 0;
	}
	else
	{
		// If there's a next highest bidder, then the new price
		// is the next highest bid amount plus the bid increment. 
		// Otherwise, we pin the price to the start price..
		if (pNextHighestBid)
		{
			proposedNewPrice	= 
				pNextHighestBid->mAmount +
				mpItem->GetBidIncrement(pNextHighestBid->mAmount);
		}
		else
			proposedNewPrice	=
				mpItem->GetStartPrice();
	}

	// Pin to high bid
	if (pHighBid && 
		proposedNewPrice > pHighBid->mAmount)
	{
		proposedNewPrice	= pHighBid->mAmount;
	}

	// Round
	proposedNewPrice	= RoundToCents(proposedNewPrice);

	// yank up to reserve
	if (pHighBid &&
		proposedNewPrice <= RoundToCents(mpItem->GetReservePrice()) &&
		RoundToCents(pHighBid->mAmount) >= RoundToCents(mpItem->GetReservePrice())
		&& ((mpItem->GetStartTime() >= reserveStartTime)
		||  ((mpItem->GetSeller() == 65618) || 
		     (mpItem->GetSeller() == 158320) ||
			 (mpItem->GetSeller() == 230851))))
	{
		// yank it up
		proposedNewPrice = RoundToCents(mpItem->GetReservePrice());
	}

	// Set new price in item
//	mpItem->SetNewHighBidder(highBidder,
//							 proposedNewPrice);

	/* Note: Use SetNewHighBidderAndBidCount instead of SetNewHighBidder and
		SetNewBidCount for better performance */
	
	if (!pHighBid)
	{
		mpItem->SetNewHighBidderAndBidCount(0, proposedNewPrice, 0);
		// mpItem->SetNewBidCount(0);
	}
	else
	{
		/* Note: Use SetNewHighBidderAndBidCount instead of SetNewHighBidder and
			SetNewBidCount for better performance */
		mpItem->SetNewHighBidderAndBidCount(highBidder, proposedNewPrice, bcount);
		// mpItem->SetNewBidCount(bcount);
	}

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

void clsBidEngineChinese::NotifyBidder(clsBid *pBid, 
									   clsBidResult *pBidResult)
{
	// check to see if bid notices are turned off
#ifdef _MSC_VER
	if(!mpMarketPlace->GetMailControl()->GetMailBidNoticesState(bidNoticesChinese))
		return;
#endif

	// hack
//	return;

	clsMail		*pMail;
	ostrstream	*pMailStream;
	char		subject[512];

	// Date conversions -- ick

	time_t		reserveStartTime;
	struct tm	timeAsResStart  = { 0, 0, 23, 9, 9, 97, 0, 0, 0 };
//	struct tm	timeAsResStart  = { 0, 0, 23, 8, 9, 97 };
	clsAnnouncement			*pAnnouncement;

	time_t		endTime;
	char		cEndDateTime[32];

	char*		pTemp;

	// time new reserve auction rules start
	reserveStartTime = mktime(&timeAsResStart);


	endTime		= mpItem->GetEndTime();
	clsUtilities::GetDateTime(endTime, &cEndDateTime[0]);

	clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), 0); // set below
// petra	currencyWidget.SetForMail(true);

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

	*pMailStream <<	"Dear "
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

	currencyWidget.SetNativeAmount(pBidResult->mBid);
	currencyWidget.EmitHTML(pMailStream);

	*pMailStream << "\n"
				 << "Your maximum bid was in the amount of: ";

	currencyWidget.SetNativeAmount(pBidResult->mMaxBid);
	currencyWidget.EmitHTML(pMailStream);

	if (pBidResult->mBidChanged)
	{
		*pMailStream <<	"\n"
						"  Your maximum bid of ";

		currencyWidget.SetNativeAmount(pBid->mAmount);
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	" was rounded down to the next bid increment of ";

		currencyWidget.SetNativeAmount(pBidResult->mBidIncrement);
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	", yielding ";

		currencyWidget.SetNativeAmount(pBidResult->mMaxBid);
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
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream << "\n\n";

		if (mpItem->GetReservePrice() != 0)
		{
			*pMailStream <<	"Note: This is a reserve price auction. \n";
			if ((mpItem->GetReservePrice() <= mpItem->GetPrice())
				&& ((mpItem->GetStartTime() >= reserveStartTime)
				||  ((mpItem->GetSeller() == 65618) || 
				     (mpItem->GetSeller() == 158320) ||
					 (mpItem->GetSeller() == 230851))))
			{
				*pMailStream << "\nNote: RESERVE HAS BEEN MET! \n";
			}
		}
	}

	*pMailStream <<	mpMarketPlace->GetCGIPath(PageViewItem);
	*pMailStream << "eBayISAPI.dll?ViewItem&item="
				<< mpItem->GetId();

	*pMailStream <<	"\n\n"
//					"*If you did not place this bid, here's what to do:\n"
//					"\n"
//					"Forward this entire message with a quick note from\n"
//					"you, as soon as possible, to support@eBay.com\n" 
//					"(Don't hit automatic reply, though, or your message\n"
//					"may get lost.)\n"
//				 	"\n" // comment out on 6/22/99 bug1186

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

	// emit bid notice footer announcements
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
				"%s Bid Notice - Item %d: %s",
				mpMarketPlace->GetCurrentPartnerName(),
				mpItem->GetId(),
				mpItem->GetTitle());

		pMail->Send(mpUser->GetEmail(), 
					(char *)mpMarketPlace->GetConfirmEmail(),
					subject);

	}

	delete	pMail;

	return;
}

//
// NotifyOutBid
//	Notifies the old high bidder they're toast
//
void clsBidEngineChinese::NotifyOutBid(int outBidId) 
{
	// check to see if outbid notices are turned off
#ifdef _MSC_VER
	if(!mpMarketPlace->GetMailControl()->GetMailBidNoticesState(outBidNoticesChinese))
		return;
#endif
	// HACK
//	return;

	clsUser		*pUser;
	clsMail		*pMail;
	ostrstream	*pMailStream;
	char		subject[512];
	clsAnnouncement			*pAnnouncement;
	char*		pTemp;

	// Date conversions -- ick
	time_t		endTime;
	char		cEndDateTime[32];

	// Convert the date
	endTime		= mpItem->GetEndTime();
	clsUtilities::GetDateTime(endTime, &cEndDateTime[0]);

	clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), 0); // set below

	pMail	= new clsMail;

	pMailStream	= pMail->OpenStream();

	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	pUser	= mpUsers->GetUser(outBidId);

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

	// emit outbid notice announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(OutBidNotice,Header, 
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	}
	
	*pMailStream 
		<<	"\nDear "
		<<	pUser->GetUserId()
		<<	", "
		<<	"\n\n"
			"Heads up! Another eBay user has outbid you on the following item!\n"
			"\n";

	*pMailStream <<	mpItem->GetTitle()
 				 <<	"\n"
				 <<	"The current bid amount is: ";

	currencyWidget.SetNativeAmount(mpItem->GetPrice());
// petra	currencyWidget.SetForMail(true);
	currencyWidget.EmitHTML(pMailStream);

	*pMailStream <<	"\n"
		<<	"The auction closes :       "
		<<	cEndDateTime
		<<	"\n\n";

	*pMailStream	<<	"Of course, your existing bid may be reinstated if "
			"this competitor's bid falls through. You can keep an eye "
			"on things if there's still plenty time before the auction "
			"closes. Visit\n"
		<<	mpMarketPlace->GetCGIPath(PageViewItem)
		<<	"eBayISAPI.dll?ViewItem&item="
		<<	mpItem->GetId()
		<<	"\n\n"
			"Otherwise, you can stay in the running and place another "
			"bid. Just visit\n"
		<<	mpMarketPlace->GetCGIPath(PageViewItem)
		<<	"eBayISAPI.dll?ViewItem&item="
		<<	mpItem->GetId()
		<<	"\n\n"
			"Safety tip: now that you're no longer the high bidder, you "
			"may be contacted by the seller or another person to sell you "
			"a similar item without going through eBay. Because this is "
			"against eBay rules and we cannot track such transactions, "
			"you would not be eligible for eBay's services that protect "
			"buyers, such as insurance or mediation.\n"
			"\n"
			"If you have any questions, be sure to visit our Help section; "
			"it's best to email us from there rather than replying to this "
			"message, as replies here can't be processed. Just click "
			"http://pages.ebay.com/help/index.html\n"
			"\n"
			"Good luck with your bidding!\n";

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

	// emit outbid notice footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(OutBidNotice,Footer, 
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	}

    if (pUser->SendOutBid()) { 

		sprintf(subject,
				"%s Outbid Notice - item %d: %s",
				mpMarketPlace->GetCurrentPartnerName(),
				mpItem->GetId(),
				mpItem->GetTitle());

		pMail->Send(pUser->GetEmail(), 
					(char *)mpMarketPlace->GetConfirmEmail(),
					subject);
    }

	delete	pMail;
	delete	pUser;

	return;
}
