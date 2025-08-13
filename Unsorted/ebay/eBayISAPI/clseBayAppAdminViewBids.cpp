/*	$Id: clseBayAppAdminViewBids.cpp,v 1.9.130.2 1999/08/05 20:42:06 nsacco Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppViewBids.cpp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		The View Bids method(s) of clseBayApp,
//		which are here to keep us sane. 
//
//		Note that this app runs on the "do the work"
//		in the client maxim, which means that we fetch
//		"all" the bids for an item, and then do the
//		sorting, etc here.
//
//		Also note that this is a COPY of clsBidAppViewItems. 
//		The only difference is that we reveal the bid amounts.
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

// This pragma avoid annoying warning messages
// about overlength names generated for STL
#pragma warning( disable : 4786 )

#include "ebihdr.h"
#include "clsUserIdWidget.h"
#include "hash_map.h"


static char *Item	= "item";

//
// sort_bid_time
//
//	A private sort routine sort all bids by time
//
static bool sort_bid_time(clsBid *pA, clsBid *pB)
{
	if (pA->mTime < pB->mTime)
		return true;

	return false;
}


// This compare routine makes lower bids MORE important
// (less than) higher bids, and bids made later in time
// MORE important (less than) bids made earlier. The 
// reason this is the opposite of what you think it
// should be is to force a sort order for display
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
// ComputePrice
//	Computes the price of an item at any given
//	point in time
//
void ComputePrice(clsMarketPlace *pMarketPlace,
				  clsItem *pItem,
				  BidVector *pVBids,
				  double *pPrice,
				  int *pHighBidder)
{
	BidVector::iterator	vI;
	clsBid				*pHighBid;
	clsBid				*pNextHighestBid;
	double				proposedNewPrice;
	int					highBidder;

	// Sort the vector
	sort(pVBids->begin(), pVBids->end(), sort_bid_amount);

	// Let's get the highest bid, and the NEXT highest bid by 
	// ANOTHER user. First, set up.
	pHighBid		= NULL;
	highBidder		= 0;
	pNextHighestBid	= NULL;


	if (pVBids->size() > 0)
	{
		// Highest bid is the first one in the vector
		vI				= pVBids->begin();

		do
		{
			if (vI == pVBids->end())
				break;

			if ((*vI)->mAction == BID_RETRACTION ||
				(*vI)->mAction == BID_AUTORETRACT ||
				(*vI)->mAction == BID_CANCELLED ||
				(*vI)->mAction == BID_AUTOCANCEL)
			{
				vI++;
				continue;
			}
			pHighBid		= (*vI);
			highBidder		= pHighBid->mUser;
			break;
		} while (1==1);

		// Now, loop until we find the NEXT bid NOT by
		// the same user

		if (pHighBid)
		{
			do
			{
				vI++;
				if (vI	== pVBids->end())
					break;

				if ((*vI)->mAction == BID_RETRACTION ||
					(*vI)->mAction == BID_AUTORETRACT ||
					(*vI)->mAction == BID_CANCELLED ||
					(*vI)->mAction == BID_AUTOCANCEL)
				{
//					vI++;
					continue;
				}

				if ((*vI)->mUser != pHighBid->mUser)
				{
					pNextHighestBid	= (*vI);
					break;
				}
			} while (1==1);
		}
	}

	// If there's no highest bidder, then there are no bids
	// on the item, and the new price zero.
	if (!pHighBid)
	{
		proposedNewPrice	= 0;
		highBidder			= 0;
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
				pItem->GetBidIncrement(pNextHighestBid->mAmount);
		}
		else
			proposedNewPrice	=
				pItem->GetStartPrice();
	}

	// Pin to high bid
	if (pHighBid && 
		proposedNewPrice > pHighBid->mAmount)
	{
		proposedNewPrice	= pHighBid->mAmount;
	}

	// Store
	*pPrice			= proposedNewPrice;
	*pHighBidder	= highBidder;
	return;
}



void GetBids(clsItem *pItem,
			 BidVector *pBids)
{

	// Get the bids
	pItem->GetBids(pBids);

	// Sort the bids by time
	if ((*pBids).size() < 1)
		return;

	sort((*pBids).begin(), (*pBids).end(), sort_bid_time);

	return;
}


void clseBayApp::AdminViewBids(CEBayISAPIExtension* pCtxt,
							    int item,
								eBayISAPIAuthEnum authLevel)
{
	// The vector of final bids
	BidVector			finalBids;

	// The vector of working bids
	BidVector			workingBids;

	BidVector::iterator	i;

	// Interesting things about the item
	bool	isDutch;
	bool	isInProgress;

	// Interesting formatting things
	time_t					theTime;
	struct tm				*pTheTime;
	char					theStartTime[64];
	char					theEndTime[64];
	char					theBidTime[64];

	// Information about bidding user
	clsUser		*pBiddingUser;

	// Information about the current price
	double		currentPrice;
	int			currentHighBidder;
	clsUser		*pHighBidder;

	clsUserIdWidget		*pUserIdWidget;

	SetUp();

	// Heading, etc
	*mpStream <<	"<html><head><title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Item Bid History"
					"</title></head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}


	// Get the item
	GetAndCheckItem(item);

	if (!mpItem)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
		// Error Stuff Here
	}

	// Determine some stuff

	if (mpItem->GetQuantity() > 1)
		isDutch	= true;
	else
		isDutch	= false;

	if (mpItem->GetEndTime() > time(0))
		isInProgress	= true;
	else
		isInProgress	= false;

	pUserIdWidget = new clsUserIdWidget(mpMarketPlace, this);
	// Acquire information about the seller
	pUserIdWidget->SetUserInfo(mpItem->GetSellerUserId(), 
								mpItem->GetSellerEmail(),
								UserStateEnum(mpItem->GetSellerUserState()),
								mpMarketPlace->UserIdRecentlyChanged(mpItem->GetSellerIdLastModified()),
								mpItem->GetSellerFeedbackScore());
	
	// Reformat the dates to make them look pretty
	theTime		= mpItem->GetStartTime();
	pTheTime	= localtime(&theTime);
	strftime(theStartTime, sizeof(theStartTime), 
				"%m/%d/%y %H:%M:%S %Z",
				pTheTime);

	theTime		= mpItem->GetEndTime();
	pTheTime	= localtime(&theTime);
	strftime(theEndTime, sizeof(theEndTime), 
				"%m/%d/%y %H:%M:%S %Z",
				pTheTime);

	// PH added 04/29/99
	clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), 0);

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Some more heading we always put out...
	*mpStream	<< "<h2>"
					<< mpMarketPlace->GetCurrentPartnerName()
					<< " Administrative bid history for "
					<< mpItem->GetTitle()
					<< " "
						"(item #"
					<< mpItem->GetId()
					<< ")"
						"</h2>"
						"\n"
					<< flush;

	// Last/Start Bid info
	*mpStream	<< "<pre>\n";

	if (mpItem->GetBidCount() > 0 &&
		mpItem->GetPrice() > 0)
	{
		*mpStream	<< "Last bid for this item:"
							"                "
							"<strong>";
		// PH added 04/29/99
		currencyWidget.SetNativeAmount(mpItem->GetPrice());
		currencyWidget.EmitHTML(mpStream);
// PH							"$"
// PH						<< mpItem->GetPrice()
		*mpStream	<< "</strong>";
	}
	else
	{
		*mpStream	<< "Bidding started at:"
							"                    "
							"<strong>";
		// PH added 04/29/99
		currencyWidget.SetNativeAmount(mpItem->GetStartPrice());
		currencyWidget.EmitHTML(mpStream);
// PH							"$"
// PH						<< mpItem->GetStartPrice()
		*mpStream	<< "</strong>";
	}

	// If it's a reserve auction, do that thang too
	if (mpItem->GetReservePrice() != 0)
	{
		*mpStream	<<	" "
						"("
						"<b>Reserve Price: ";	// PH $"
		// PH added 04/29/99
		currencyWidget.SetNativeAmount(mpItem->GetReservePrice());
		currencyWidget.EmitHTML(mpStream);
// PH					<<	mpItem->GetReservePrice()
		*mpStream	<<	"</b>"
						")";
	}

	// Auction dates
	*mpStream << "\n"
					 "Date auction ends:"
					 "                     "
					 "<strong>"
				 << theEndTime
				 << "</strong>"
					 "\n"
					 "Date auction started:"
					 "                  "
				 << theStartTime
				 << "\n";


	// Seller information 
	// *** Need score and registered! ***
	*mpStream	<< "Seller:"
						"                                ";
	pUserIdWidget->SetIncludeEmail(true);
	pUserIdWidget->EmitHTML(mpStream);

	// First & current bid information
	*mpStream	<< "\n"
						"First bid at:"
						"                          ";
// PH						"$"
// PH					<< mpItem->GetStartPrice()
	// PH added 04/29/99
	currencyWidget.SetNativeAmount(mpItem->GetStartPrice());
	currencyWidget.EmitHTML(mpStream);
	*mpStream		<< "\n"
						"Number of bids made:"
						"                   "
						"<strong>"
					<< mpItem->GetBidCount()
					<< "</strong>"
						" "
						"<font size=-1>"
						"(may include multiple bids by same bidder)"
						"</font>"
						"\n";

	*mpStream	<< "</pre>"
						"\n\n"
					<< flush;


	GetBids(mpItem, &finalBids);

	// Ok, Now, let's output the bids!
	//
	if (isDutch)
	{
		*mpStream << "<hr>"
						 "<pre>"
						 "\n"
						 "<a name=dutch>"
						 "All bids in this Dutch Auction:"
						 "</a>"
						 "\n\n";
	}
	else
	{
		if (finalBids.size() > 0)
		{
			*mpStream << "<hr>"
							 "<pre>"
							 "\n"
							 "Bidding History "
							 "(earliest bids first):"
							 "\n\n";
		}
	}  
	// Note the bids are _backwards_ (losers to winners)
	// so we go through it backwards to print winners 
	// first
	for (i = finalBids.begin();
		  i != finalBids.end();
		  i++)
	{
		// Bidder id (if not a private auction)
		// Note:: Actual User display?
		pBiddingUser = mpUsers->GetUser((*i)->mUser);
		pUserIdWidget->SetUser(pBiddingUser);
		pUserIdWidget->SetIncludeEmail(true);
		pUserIdWidget->EmitHTML(mpStream);

		// Last bid
		*mpStream	<<	"\n"
						"        ";

		switch ((*i)->mAction)
		{
			case BID_UNKNOWN:
				*mpStream <<	"Unknown Type:      ";
				break;
			case BID_BID:
				*mpStream <<	"Bid:               ";
				break;
			case BID_DUTCH_BID:
				*mpStream <<	"Dutch Bid:         ";
				break;
			case BID_AUTORETRACT:
				*mpStream <<	"Bid Auto Retract:  ";
				break;
			case BID_RETRACTION:
				*mpStream <<	"Bid Retracted:     ";
				break;
			case BID_CANCELLED:
				*mpStream <<	"Bid Cancel:        ";
				break;
			case BID_AUTOCANCEL:
				*mpStream <<	"Bid Auto Cancel:   ";
				break;
			default:
				*mpStream <<	"Unknown Type:      ";
				break;
		}

// PH	*mpStream <<	"$"
// PH			  <<	(*i)->mAmount;
		// PH added 04/29/99
		currencyWidget.SetNativeAmount((*i)->mAmount);
		currencyWidget.EmitHTML(mpStream);


		// For dutch Auctions, we also do the quantity
		if (isDutch)
		{
			*mpStream <<	"\n"
							"        "
							"Quantity bid for:"
							"  "
					  <<	(*i)->mQuantity;
		} 


		// Date
		pTheTime	= localtime(&(*i)->mTime);
		strftime(theBidTime, sizeof(theBidTime), 
					"%m/%d/%y %H:%M:%S %Z",
					pTheTime);

		*mpStream <<	"\n"
						"        "
						"Date of bid:"
						"       "
				  <<	theBidTime;

		// For retractions of cancellations, show the reason
		if ((*i)->mAction == BID_RETRACTION ||
			(*i)->mAction == BID_CANCELLED)
		{
			*mpStream <<	"\n"
							"        "
							"Reason: "
					  <<	(*i)->mReason;
		}


		// Now, compute the price after this bid
		workingBids.push_back((*i));
		ComputePrice(mpMarketPlace,
					 mpItem,
					 &workingBids,
					 &currentPrice,
					 &currentHighBidder);

		if (currentHighBidder != 0)
		{
			pHighBidder	= mpUsers->GetUser(currentHighBidder);
		}
		else
			pHighBidder	= NULL;

		*mpStream <<	"\n"
						"        "
						"Price:             ";		// PH $"
		// PH added 04/29/99
		currencyWidget.SetNativeAmount(currentPrice);
		currencyWidget.EmitHTML(mpStream);
		*mpStream <<	currentPrice
				  <<	"\n"
						"        "
				  <<	"High Bidder:       ";

		if (pHighBidder)
			*mpStream <<	pHighBidder->GetUserId();


		*mpStream <<	"\n\n";

		delete pHighBidder;
		delete pBiddingUser;		
	}

	// Close pre, if necessary
	if (finalBids.size() != 0)
	{
		*mpStream	 << "</pre>"
						 << flush;
	}

	// Trailer
	if (!isDutch)
	{
		if (finalBids.size() != 0)
		{
			*mpStream << "Remember that earlier bids of the same amount "
							 "take precedence."
							 "\n";
		}
		else
		{
			*mpStream << "    "
							 "There were no earlier bidders."
							 "\n";
		}
	}
	else
	{
		if (finalBids.size() == 0)
		{
			*mpStream << "<a name=dutch>"
							  "There have been no bidders yet "
							  "for this Dutch Auction."
							  "</a>"
							  "\n\n";
		}
	}


	*mpStream <<	"</pre>"
					"\n"
			  <<	flush;


	// And the footer
	*mpStream << mpMarketPlace->GetFooter()
			  << flush;

	delete pUserIdWidget;
	// Clean up the bid list first. 
	for (i = finalBids.begin();
	     i != finalBids.end();
	     i++)
	{
		// Delete the bid
		delete	(*i);
	}

	// Same for final bids
	finalBids.erase(finalBids.begin(), finalBids.end());

	workingBids.erase(workingBids.begin(), workingBids.end());
			
	CleanUp();

	return;
}
