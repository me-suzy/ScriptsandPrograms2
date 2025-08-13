/*	$Id: clseBayAppViewBids.cpp,v 1.11.2.3.86.1 1999/08/01 03:01:37 barry Exp $	*/
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
//		Of course, this can be changed ;-)
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 01/06/97 Charles  - modified for privacy User ID
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

// This pragma avoid annoying warning messages
// about overlength names generated for STL
#pragma warning( disable : 4786 )

#include "ebihdr.h"

// Added by Charles
#include "clsUserIdWidget.h"
#include "clsUserEmailWidget.h"

#include "clsNameValue.h"
#include "hash_map.h"
#include "clseBayTimeWidget.h"

const char ErrorMsgTooManyRequest[] = "You have requested too many e-mails today. ";

static char *Item	= "item";

//
// sort_bid_user
//
//	A private sort routine to group all bids
//	from a user together, ordered by time.
//
bool sort_bid_user_time(clsBid *pA, clsBid *pB)
{
	if (pA->mUser < pB->mUser)
	{
		return true;
	}
	if (pA->mUser == pB->mUser)
	{
		if (pA->mTime < pB->mTime)
			return true;
	}

	return false;
}

// This compare routine makes lower bids MORE important
// (less than) higher bids, and bids made later in time
// MORE important (less than) bids made earlier. The 
// reason this is the opposite of what you think it
// should be is to force a sort order for display
bool sort_bid_amount(clsBid *pA, clsBid *pB)
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

void GetBids(clsItem *pItem,
			 BidVector *pBids,
			 hash_map<const int, clsBid *, hash<const int>, eqint>
				*pUserBids,
			 vector<clsBid *> *pFinalBids)
{
	vector<clsBid *>::iterator i;
	hash_map<const int, clsBid *, hash<int>, eqint>::
		const_iterator				ii;

	// Get the bids
	pItem->GetBids(pBids);

	// Sort the bids by user, time so we can 
	// accumulate the highest bid per user
	if ((*pBids).size() < 1)
		return;

	sort((*pBids).begin(), (*pBids).end(), sort_bid_user_time);

	// Now, iterate over the vector of bidders.
	for (i = (*pBids).begin();
		 i != (*pBids).end();
		 i++)
	{
		// First, let's see if we've seen this user
		// before
		ii	= (*pUserBids).find((*i)->mUser);

		// If we haven't seen this user, and it's not
		// a bid retraction (huh?) then create an entry
		// for the user.
		if (ii == pUserBids->end())
		{
			if ((*i)->mAction != BID_RETRACTION &&
				(*i)->mAction != BID_AUTORETRACT &&
				(*i)->mAction != BID_CANCELLED &&
				(*i)->mAction != BID_AUTOCANCEL)
			{
				(*pUserBids)[(*i)->mUser]	= (*i);
			}
			continue;
		}

		// If we've seen this user and it's a bid
		// retraction, remove their entry
		if ((*i)->mAction == BID_RETRACTION ||
			 (*i)->mAction == BID_AUTORETRACT ||
			 (*i)->mAction == BID_CANCELLED ||
			 (*i)->mAction == BID_AUTOCANCEL)
		{
			(*pUserBids).erase((*i)->mUser);
			continue;
		}

		// If we've seen this user, and the current
		// bid is greater than this one (which it 
		// should be -- it's later in time!), then
		// replace it.
		//
		// Note that this computation is based on
		// the _value_ of the bid, which is quantity *
		// amount. For non-dutch auctions, the quantity
		// is forced to 1.
		if ((*i)->mAction != BID_RETRACTION &&
			(*i)->mAction != BID_AUTORETRACT &&
			(*i)->mAction != BID_CANCELLED &&
			(*i)->mAction != BID_AUTOCANCEL)
		{
			if ((*ii).second->mValue < (*i)->mValue)
				(*pUserBids)[(*i)->mUser] = (*i);
		}
	}

	// Now, the hash table has the "final" bid amounts
	// for all the users. Let's put them into a vector
	// and sort them by bid amount
	for (ii = (*pUserBids).begin();
		  ii != (*pUserBids).end();
		  ii++)
	{
		(*pFinalBids).push_back((*ii).second);
	}

	if ((*pFinalBids).size() > 0)
	{
		sort((*pFinalBids).begin(), (*pFinalBids).end(),
			  sort_bid_amount);
	}
	return;
}

void clseBayApp::ViewBids(CEBayISAPIExtension* pCtxt,
						 int item)
{
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Item Bid History"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	DisplayBids(item, false);

	// And the footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	return;


}

void clseBayApp::ViewBidderWithEmails(CEBayISAPIExtension* pCtxt,
						 int item, char * pUserId, char * pPass)
{
	clsUserValidation *pValidation;

	SetUp();

	pValidation = mpUsers->GetUserValidation();

	if (!pValidation->IsSoftValidated() && !strcmp(pUserId, "default"))
	{
		char Action[255];
		char theItem[64];
		clsNameValuePair theNameValuePairs[2];


		// Create the actions tring
		sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PageViewBidderWithEmails));
		sprintf(theItem, "%d", item);

		// create the name value pairs
		theNameValuePairs[0].SetName("MfcISAPICommand");
		theNameValuePairs[0].SetValue("ViewBidderWithEmails");
		theNameValuePairs[1].SetName("item");
		theNameValuePairs[1].SetValue(theItem);

		// show login page
		LoginDialog(Action, 2, theNameValuePairs);

		CleanUp();
		return;
	}

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Item Bid History"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;


	if (pValidation->IsSoftValidated())
	{
		mpUser = mpUsers->GetAndCheckUser((char *) pValidation->GetValidatedUserId(), mpStream);
	}
	else
	{
		mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);
	}

	// If we didn't get the user, we're done
	if (mpUser)
	{
		// Check whether over the limit
		if (mpUser->GetReqEmailCount() >= EBAY_EMAILS_REQUEST_PER_DAY)
		{
			*mpStream <<	ErrorMsgTooManyRequest
					  <<	"<p>";

			DisplayBids(item, false);
		}
		else
		{
			DisplayBids(item, true);
		}
	}

	// And the footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	return;
}


// display the bids
void clseBayApp::DisplayBids(int item, bool IncludingEmail)
{
	// Related to viewing bids
	BidVector			bids;

	// The hash map of user's bids
	hash_map<const int, clsBid *, hash<const int>, eqint>
						userBids;

	// The vector of final bids
	vector<clsBid *>	finalBids;

	vector<clsBid *>::iterator i;
	hash_map<const char *, clsBid *, hash<const char *>, eqstr>::
		const_iterator ii;

	// Interesting things about the item
	bool	isDutch;
	bool	isInProgress;
	bool	haveRetractions;

//	clsUser		*pUser		= NULL;
	//clsFeedback	*pFeedback	= NULL;
	//bool		userHasFeedback;
	//int			score;

	// Interesting formatting things
// petra	time_t					theTime;
	//struct tm				*pTheTime;
	//char					theStartTime[64];
	//char					theEndTime[64];
	//char					theBidTime[64];

	clseBayTimeWidget		timeWidget (mpMarketPlace,				// petra
										EBAY_TIMEWIDGET_MEDIUM_DATE,// petra
										EBAY_TIMEWIDGET_LONG_TIME);	// petra
// petra	clseBayTimeWidget		endTimeWidget;
// petra	clseBayTimeWidget		bidTimeWidget;

// petra	TimeZoneEnum			tz = (TimeZoneEnum)(mpMarketPlace->GetCurrentTimeZone());
	//samuel au, 4/9/99
	// change the time zone to be "London" for now for the UK site
	//end

	// Information about bidding user
	clsUser		*pBiddingUser;

	// Added by Charles
	clsUserIdWidget *pUserIdWidget;

	clsCurrencyWidget currencyWidget(mpMarketPlace, Currency_USD, 0); // Set below.

	time_t		reserveStartTime;
	struct tm	timeAsResStart  = { 0, 0, 23, 9, 9, 97 };

	// Get the item
	mpItem = mpItems->GetItem(item, true);

	if (!mpItem)
	{
		*mpStream <<	"<h2>Unknown Item</h2>"
						"Sorry, the item "
				  <<	item
				  <<	" does not appear to be a valid number on "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	". Please go back and try again.";

		return;
	}

	// time new reserve auction rules start
	reserveStartTime = mktime(&timeAsResStart);

	// Determine some stuff

	if (mpItem->GetQuantity() > 1)
		isDutch	= true;
	else
		isDutch	= false;

	if (mpItem->GetEndTime() > time(0))
		isInProgress	= true;
	else
		isInProgress	= false;

	// Acquire information about the seller
//	pUser				= mpUsers->GetUser(mpItem->GetSeller());
	
	// Reformat the dates to make them look pretty
// petra	theTime		= mpItem->GetStartTime();
// petra	startTimeWidget.SetTime(theTime);
// petra	startTimeWidget.SetTimeZone(tz);

	/*
	pTheTime	= localtime(&theTime);
	strftime(theStartTime, sizeof(theStartTime), 
				"%m/%d/%y %H:%M:%S %Z",
				pTheTime);
	*/

// petra	theTime		= mpItem->GetEndTime();
// petra	endTimeWidget.SetTime(theTime);
// petra	endTimeWidget.SetTimeZone(tz);

	/*
	pTheTime	= localtime(&theTime);
	strftime(theEndTime, sizeof(theEndTime), 
				"%m/%d/%y %H:%M:%S %Z",
				pTheTime);
	*/

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Some more heading we always put out...
	*mpStream	<< "<h2>"
					<< mpMarketPlace->GetCurrentPartnerName()
					<< " bid history for "
					<< mpItem->GetTitle()
					<< " "
						"(item #"
					<< mpItem->GetId()
					<< ")"
						"</h2>"
						"\n"
					<< flush;

	// If the auction is still in process, print a little
	// warning
	if (isInProgress & !isDutch)
	{
		*mpStream	<< "<strong>"
							"Bid amounts are not available while the "
							"auction is underway. You will be able to "
							"see the previous bidders, but not the "
							"amount they bid. Please check back after "
							"the close of the auction for the complete "
							"history."
							"</strong>"
							"<p>"
							"\n"
						<< flush;
	}
	
	*mpStream	<<	"If you have questions about this item, please "
						"contact the seller at the User ID provided "
						"below. Seller assumes all responsibility for "
						"listing this item."
						"<p>"
					<< flush; 

	// Last/Start Bid info
	*mpStream	<< "<pre>\n";

	if (mpItem->GetBidCount() > 0 &&
		mpItem->GetPrice() > 0)
	{
		*mpStream	<< "Last bid for this item:"
							"                ";

		currencyWidget.SetNativeAmount(mpItem->GetPrice());
		currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(mpStream);
	}
	else
	{
		*mpStream	<< "Bidding started at:"
							"                    ";
		currencyWidget.SetNativeAmount(mpItem->GetStartPrice());
		currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(mpStream);
	}

	// optionally add reserve price auction message next to the bid price
	if (mpItem->GetReservePrice() != 0)
	{

	/*	// this method won't work across databases
		if ((mpItem->GetSeller() == 230851) ||	// poon
			(mpItem->GetSeller() == 65618) ||	// tini
			(mpItem->GetSeller() == 158320) ||	// skippy
			(mpItem->GetSeller() == 60806) ||	// pete
			(mpItem->GetSeller() == 48978))		// wwen
	*/

		// check to see if seller is poon, tini, skippy, pete or wwen.
		//  if so, then reveal whether or not reserve has been met
		if ((mpItem->GetStartTime() >= reserveStartTime)	||				// after wed nite
			(strcmp(mpItem->GetSellerUserId(), "poon@ebay.com")==0) ||		// poon
			(strcmp(mpItem->GetSellerUserId(), "tini@ebay.com")==0) ||		// tini
			(strcmp(mpItem->GetSellerUserId(), "skippy@ebay.com")==0) ||	// skippy
			(strcmp(mpItem->GetSellerUserId(), "pete@ebay.com")==0) ||		// pete
			(strcmp(mpItem->GetSellerUserId(), "wwen@ebay.com")==0))		// wwen
		{
			*mpStream <<	" "
							"<a href=\""
					  <<	mpMarketPlace->GetHTMLPath()
					  <<	"help/buyerguide/bidding-type.html#reserve"
							"\"><font size=2>"
					  <<	((RoundToCents(mpItem->GetPrice())
							>= RoundToCents(mpItem->GetReservePrice())) ?
							"(reserve price met)" : 
							"(reserve price not yet met)")
					  <<	"</font>"
							"</a>";	
		}
		else	// seller is not special, so don't reveal it, just
				//  print "(reserve price auction)"
		{
			*mpStream <<	" "
							"<a href=\""
					  <<	mpMarketPlace->GetHTMLPath()
					  <<	"help/buyerguide/bidding-type.html#reserve"
							"\"><font size=2>"
							"(reserve price auction)"
							"</font>"
							"</a>";
		}
	}
	
	// Auction dates
	*mpStream << "\n"
					 "Date auction ends:"
					 "                     "
					 "<strong>";

	timeWidget.SetTime (mpItem->GetEndTime() );			// petra
	timeWidget.EmitHTML(mpStream);						// petra

    *mpStream	 << "</strong>"
					 "\n"
					 "Date auction started:"
					 "                  ";
	
	timeWidget.SetTime (mpItem->GetStartTime() );		// petra
	timeWidget.EmitHTML(mpStream);						// petra

	*mpStream	 << "\n";


	pUserIdWidget	= new clsUserIdWidget(mpMarketPlace, this);
	// Seller information 
	// *** Need score and registered! ***
	*mpStream	<<	"Seller:"
				<<	"                                ";


	pUserIdWidget->SetUserInfo(mpItem->GetSellerUserId(), 
								mpItem->GetSellerEmail(),
								UserStateEnum(mpItem->GetSellerUserState()),
								mpMarketPlace->UserIdRecentlyChanged(mpItem->GetSellerIdLastModified()),
								mpItem->GetSellerFeedbackScore());

	pUserIdWidget->SetIncludeEmail(IncludingEmail);
	pUserIdWidget->EmitHTML(mpStream);
	*mpStream	<< flush;

	// First & current bid information
	*mpStream	<< "\n"
						"First bid at:"
						"                          ";

	currencyWidget.SetNativeAmount(mpItem->GetStartPrice());
	currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
	currencyWidget.EmitHTML(mpStream);

	*mpStream		<<  "\n"
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


	GetBids(mpItem, &bids, &userBids, &finalBids);

	// Ok, Now, let's output the bids!
	//
	// *** Note ***
	// I'm not sure I'm happy with this. The output for dutch
	// and usual auctions is subtley different, so at the right
	// places I just do something a little different. 
	// *** Note ***

	// Heading
	// *** Note ***
	// Question: Why do we print a heading if there are no bids for
	// a Dutch Auction?
	// *** Note ***
	if (isDutch)
	{
		*mpStream << "<HR>"
						 "<PRE>"
						 "\n"
						 "<A name=dutch>"
						 "All bids in this Dutch Auction:"
						 "</A>"
						 "\n\n";
	}
	else
	{
		if (finalBids.size() > 0)
		{
			*mpStream << "<HR>"
							 "<PRE>"
							 "\n"
							 "Bidding History "
							 "(in order of bid amount):"
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
		pBiddingUser			= NULL;
		// Bidder id (if not a private auction)
		// Note:: Actual User display?
		if (!(mpItem->GetPrivate()))
		{
			// Added by Charles
			pBiddingUser = mpUsers->GetUser((*i)->mUser);
			pUserIdWidget->SetUser(pBiddingUser);
			pUserIdWidget->SetIncludeEmail(IncludingEmail);
			pUserIdWidget->EmitHTML(mpStream);
			delete	pBiddingUser;
		}
		else
		{
			*mpStream << "    "
							 "private auction -- bidders\' "
							 "identities protected";
		}

		// Last bid (Maybe)
		*mpStream	<< "\n"
							"        ";
		if (!isDutch && isInProgress)
		{
			*mpStream << "Bid amount protected until close of auction";
		}
		else
		{ 
			*mpStream << "Last bid at:"
							 "       ";
		
			// Huh?
			if (!isDutch)
			{
				if ((*i)->mAmount > mpItem->GetPrice())
				{
					currencyWidget.SetNativeAmount(mpItem->GetPrice());
					currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
					currencyWidget.EmitHTML(mpStream);
				}
				else
				{
					currencyWidget.SetNativeAmount((*i)->mAmount);
					currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
					currencyWidget.EmitHTML(mpStream);
				}
			}
			else
			{
				currencyWidget.SetNativeAmount((*i)->mAmount);
				currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
				currencyWidget.EmitHTML(mpStream);
			}
		}

		// For dutch Auctions, we also do the quantity
		if (isDutch)
		{
			*mpStream << "\n"
							 "        "
							 "Quantity bid for:"
							 "  "
						 << (*i)->mQuantity;
		} 

		// Date

		timeWidget.SetTime( (*i)->mTime );		// petra
// petra		bidTimeWidget.SetTimeZone(tz);

		/*
		pTheTime	= localtime(&(*i)->mTime);
		strftime(theBidTime, sizeof(theBidTime), 
					"%m/%d/%y %H:%M:%S %Z",
					pTheTime);
		*/

		*mpStream << "\n"
						 "        "
						 "Date of bid:"
						 "       ";
		
		timeWidget.EmitHTML(mpStream);			// petra

		*mpStream << "\n\n";
	}

	// update req email count
	if (IncludingEmail && !(mpItem->GetPrivate()) && finalBids.size() > 0)
	{
		mpUser->AddReqEmailCount(finalBids.size());
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

	// Bid Retractions
	*mpStream << "<pre>"
					 "\n"
					 "<a href="
					 "\""
				<<	mpMarketPlace->GetHTMLPath()
				<< "services/buyandsell/retract-bid.html"
					"\""
					">"
					"Bid retraction"
					"</a>"
					" and "
					"<a href="
					"\""
				<< mpMarketPlace->GetHTMLPath()
				<< "services/buyandsell/seller-cancel-bid.html"
					"\""
					">"
					"cancellation"
					"</a>"
					" history (if any):\n\n";

	haveRetractions	= false;
	
	for (i = bids.begin();
		  i != bids.end();
		  i++)
	{
		if ((*i)->mAction == BID_RETRACTION ||
			(*i)->mAction == BID_CANCELLED)
		{
			haveRetractions	= true;

			timeWidget.SetTime( (*i)->mTime );		// petra
// petra			bidTimeWidget.SetTimeZone(tz);

			/*
			pTheTime	= localtime(&(*i)->mTime);
			strftime(theBidTime, sizeof(theBidTime), 
					 "%m/%d/%y %H:%M:%S %Z",
					  pTheTime);
			*/

			if (!(mpItem->GetPrivate()))
			{
				// Added by Charles
				pBiddingUser = mpUsers->GetUser((*i)->mUser);
				pUserIdWidget->SetUser(pBiddingUser);
				pUserIdWidget->EmitHTML(mpStream);
				delete	pBiddingUser;
			}
			else
			{
				*mpStream << "    "
								 "private auction -- bidders\' "
								 "identities protected";
			}
		
		*mpStream <<	"\n"
						"        ";

		if ((*i)->mAction == BID_RETRACTION ||
			(*i)->mAction == BID_AUTORETRACT)
			*mpStream <<	"Retracted.";
		else
			*mpStream <<	"Cancelled.";

		*mpStream <<	" time: Bids prior to ";
		
		timeWidget.EmitHTML(mpStream);		// petra

		*mpStream  <<	" have been ";
		
		if ((*i)->mAction == BID_RETRACTION ||
			(*i)->mAction == BID_AUTORETRACT)
			*mpStream <<	"retracted.";
		else
			*mpStream <<	"cancelled.";

		*mpStream <<	"\n"
						"        "
						"Explanation:"
						"     "
				  <<	(*i)->mReason
						<< "\n\n";


		}
	}

	if (!haveRetractions)
	{
		*mpStream << "    "
						 "There are no bid retractions."
						 "\n";
	}

	*mpStream << "</pre>"
					 "\n"
				 << flush;


	// Added by Charles
	delete pUserIdWidget;
	// Clean up the bid list first. 
	for (i = bids.begin();
	     i != bids.end();
	     i++)
	{
		// Delete the bid
		delete	(*i);
	}

	bids.erase(bids.begin(), bids.end());
		
	// We can delete everything in the hash map with 
	// impunity, since we transferred the bid objects
	// to the final bids vector
	userBids.erase(userBids.begin(), userBids.end());

	// Same for final bids
	finalBids.erase(finalBids.begin(), finalBids.end());
			
	// Clean

//	delete	pUser;

	return;
}

// view bids dutch high bidders

void clseBayApp::ViewBidsDutchHighBidder(CEBayISAPIExtension* pCtxt,
						 int item)
{
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Item Dutch Auction High Bidders"
					"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n"
			  <<	flush;

	DisplayDutchHighBidders(item, false);

	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	return;
}


// view bids dutch high bidders email
void clseBayApp::ViewBidDutchHighBidderEmails(CEBayISAPIExtension* pCtxt,
						 int item, char * pUserId, char * pPass)
{
	clsUserValidation *pValidation;

	SetUp();

	pValidation = mpUsers->GetUserValidation();

	if (!pValidation->IsSoftValidated() && !strcmp(pUserId, "default"))
	{
		char Action[255];
		char theItem[64];
		clsNameValuePair theNameValuePairs[2];


		// Create the actions tring
		sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PageViewBidDutchHighBidderEmails));
		sprintf(theItem, "%d", item);

		// create the name value pairs
		theNameValuePairs[0].SetName("MfcISAPICommand");
		theNameValuePairs[0].SetValue("ViewBidDutchHighBidderEmails");
		theNameValuePairs[1].SetName("item");
		theNameValuePairs[1].SetValue(theItem);

		// show login page
		LoginDialog(Action, 2, theNameValuePairs);

		CleanUp();
		return;
	}

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Item Dutch Auction High Bidders"
					"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n"
			  <<	flush;

	if (pValidation->IsSoftValidated())
		mpUser = mpUsers->GetAndCheckUser((char *) pValidation->GetValidatedUserId(), mpStream);
	else
		mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);

	// If we didn't get the user, we're done
	if (mpUser)
	{
		// Check whether over the limit
		if (mpUser->GetReqEmailCount() >= EBAY_EMAILS_REQUEST_PER_DAY)
		{
			*mpStream <<	ErrorMsgTooManyRequest
					  <<	"<p>";

			DisplayDutchHighBidders(item, false);
		}
		else
		{
			DisplayDutchHighBidders(item, true);
		}
	}

	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	return;
}

// view bids dutch high bidders email
void clseBayApp::DisplayDutchHighBidders(int item, 
										 bool IncludingEmail /*=false*/)
{
	// Related to viewing bids
	BidVector			*pvBids;
	int		cumTotal;
	double   BidAmount;

	vector<clsBid *>::iterator i;

	// Interesting things about the item
	bool	isDutch;
	bool	isInProgress;

//	clsUser		*pUser		= NULL;
	clsFeedback	*pFeedback	= NULL;
	bool		userHasFeedback = false;
	int			score = 0;

	// Interesting formatting things
// petra	time_t					theTime;
	//struct tm				*pTheTime;
	//char					theStartTime[64];
	//char					theEndTime[64];
	//char					theBidTime[64];

// petra	clseBayTimeWidget		startTimeWidget;
// petra	clseBayTimeWidget		endTimeWidget;
// petra	clseBayTimeWidget		bidTimeWidget;

// petra	TimeZoneEnum			tz = (TimeZoneEnum)(mpMarketPlace->GetCurrentTimeZone());

	// Information about bidding user
	clsUser		*pBiddingUser;

	// Added by Charles
	clsUserIdWidget *pUserIdWidget;

	clsCurrencyWidget currencyWidget(mpMarketPlace, Currency_USD, 0); // Set below.
	int winningCurrencyId;

	// Get the item
	mpItem = mpItems->GetItem(item, true);

	if (!mpItem)
	{
		*mpStream <<	"<h2>Unknown Item</h2>"
						"Sorry, the item "
				  <<	item
				  <<	" does not appear to be a valid number on "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	". Please go back and check it again.";

		return;
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

	// Acquire information about the seller
//	pUser				= mpUsers->GetUser(mpItem->GetSeller());
	
	// Reformat the dates to make them look pretty
// petra	theTime		= mpItem->GetStartTime();
// petra	startTimeWidget.SetTime(theTime);
// petra	startTimeWidget.SetTimeZone(tz);

	/*
	pTheTime	= localtime(&theTime);
	strftime(theStartTime, sizeof(theStartTime), 
				"%m/%d/%y %H:%M:%S %Z",
				pTheTime);
	*/

// petra	theTime		= mpItem->GetEndTime();
// petra	endTimeWidget.SetTime(theTime);
// petra	endTimeWidget.SetTimeZone(tz);

	/*
	pTheTime	= localtime(&theTime);
	strftime(theEndTime, sizeof(theEndTime), 
				"%m/%d/%y %H:%M:%S %Z",
				pTheTime);
	*/

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Some more heading we always put out...
	*mpStream	<< "<h2>"
					<< mpMarketPlace->GetCurrentPartnerName()
					<< " Dutch Auction high bidders for "
					<< mpItem->GetTitle()
					<< " "
						"(item #"
					<< mpItem->GetId()
					<< ")"
						"</h2>"
						"\n"
					<< flush;

	// refer to bidding history page
	*mpStream	<<	"<p>For information about all bidders for this auction, "
					"follow this link to the "
							"<A HREF="
							"\""
						<<	mpMarketPlace->GetCGIPath(PageViewBids)
						<<	"eBayISAPI.dll?ViewBids&item="
						<<	mpItem->GetId()
						<<	"\""
						<<	">"
							"Bidding history page"
							"</a>";

	*mpStream	<<	"<p>If you have questions about this item, please "
						"contact the seller at the e-mail address provided "
						"below. Seller assumes all responsibility for "
						"listing this item."
						"<p>"
					<< flush; 

	// Last/Start Bid info
	*mpStream	<< "<PRE>\n";

	// only dutch here
	if (mpItem->GetBidCount() > 0)
	{
		*mpStream	<< "Current bid for this item:"
							"             "
							"<a href=\"#dutch\">"
							"See below for Dutch Auction standings"
							"</a>";
	}
	else
	{
		*mpStream	<< "Bidding started at:"
							"                    ";

		currencyWidget.SetNativeAmount(mpItem->GetStartPrice());
		currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(mpStream);
	}

	// Quantity, Dates, and other goodies!
	*mpStream <<	"\n"
					"Quantity:"
					"                              "
					"<STRONG>"
			  <<	mpItem->GetQuantity()
			  <<	"</STRONG>"
					"\n"
			  <<	"Date auction ends:"
					"                     "
					"<STRONG>";

	clseBayTimeWidget timeWidget (mpMarketPlace,				// petra
								  EBAY_TIMEWIDGET_MEDIUM_DATE,	// petra
								  EBAY_TIMEWIDGET_LONG_TIME,	// petra
								  mpItem->GetEndTime() );		// petra
	timeWidget.EmitHTML(mpStream);								// petra
	
	*mpStream <<	"</STRONG>"
					"\n"
					"Date auction started:"
					"                  ";
	
	timeWidget.SetTime (mpItem->GetStartTime() );				// petra
	timeWidget.EmitHTML(mpStream);								// petra

	*mpStream <<	"\n"
					"Seller:"
					"                                ";

	pUserIdWidget	= new clsUserIdWidget(mpMarketPlace, this);

	pUserIdWidget->SetUserInfo(mpItem->GetSellerUserId(), 
								mpItem->GetSellerEmail(),
								UserStateEnum(mpItem->GetSellerUserState()),
								mpMarketPlace->UserIdRecentlyChanged(mpItem->GetSellerIdLastModified()),
								mpItem->GetSellerFeedbackScore());
	pUserIdWidget->EmitHTML(mpStream);
	*mpStream	<<	flush;


	// First & current bid information
	*mpStream	<< "\n"
						"First bid at:"
						"                          ";
	currencyWidget.SetNativeAmount(mpItem->GetStartPrice());
	currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
	currencyWidget.SetBold(false);
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

	// current high bidders ref
	*mpStream	<<	"current high bidders:"
					"                  "
			  <<	"<a href=\"#dutch\">"
							"Dutch Auction bidders here"
							"</a>";

	*mpStream	<< "</pre>"
						"\n\n"
					<< flush;


	// Ok, Now, let's output the bids!
	//

	*mpStream	<<	"<hr>"
					"<pre>"
					"\n"
					"<a name=\"dutch\">"
					"Current high bidders in this Dutch Auction: "
					"</a>"
					"\n\n";

	// prepare vector
	pvBids = new BidVector;

	// get the high bidders
	mpItem->GetDutchHighBidders(pvBids);

	// Note the bids are _backwards_ (losers to winners)
	// so we go through it backwards to print winners 
	// first
	cumTotal = 0;
	for (i = pvBids->begin();
		  i != pvBids->end();
		  i++)
	{
		pBiddingUser			= NULL;

		cumTotal = cumTotal + (*i)->mQuantity;

		// Bidder id (if not a private auction)
		// Note:: Actual User display?
		if (!(mpItem->GetPrivate()))
		{
			// Added by Charles
			pBiddingUser = mpUsers->GetUser((*i)->mUser);
			pUserIdWidget->SetUser(pBiddingUser);
			pUserIdWidget->SetIncludeEmail(IncludingEmail);
			pUserIdWidget->EmitHTML(mpStream);
			delete	pBiddingUser;
		}
		else
		{
			// private auctions
			*mpStream << "    "
							 "private auction -- bidders\' "
							 "identities protected";
		}

		// Last bid (Maybe)
		*mpStream	<< "\n"
						"       Last Bid at: ";
    
		if (!isDutch)
		{
			if ((*i)->mAmount > mpItem->GetPrice())
			{
				currencyWidget.SetNativeAmount(mpItem->GetPrice());
				currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
				currencyWidget.SetBold(true);
				currencyWidget.EmitHTML(mpStream);
			}
			else
			{
				currencyWidget.SetNativeAmount((*i)->mAmount);
				currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
				currencyWidget.SetBold(true);
				currencyWidget.EmitHTML(mpStream);
			}
		}
		else
		{
			currencyWidget.SetNativeAmount((*i)->mAmount);
			currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
			currencyWidget.SetBold(true);
			currencyWidget.EmitHTML(mpStream);

			BidAmount = (*i)->mAmount;
			winningCurrencyId = mpItem->GetCurrencyId();
		}

		// For dutch Auctions, we also do the quantity
		if (isDutch)
		{
			*mpStream << "\n"
							 "        "
							 "Quantity bid for:"
							 "  "
						 << (*i)->mQuantity;
		} 

		// Date
		timeWidget.SetTime( (*i)->mTime );			// petra
// petra		bidTimeWidget.SetTimeZone(tz);			

		/*
		pTheTime	= localtime(&(*i)->mTime);
		strftime(theBidTime, sizeof(theBidTime), 
					"%m/%d/%y %H:%M:%S %Z",
					pTheTime);
		*/

		*mpStream << "\n"
						 "        "
						 "Date of bid:"
						 "       ";

		timeWidget.EmitHTML(mpStream);				// petra

		*mpStream << "\n\n";
	}

	// update request email count
	if (IncludingEmail && pvBids->size() > 0 && mpItem->GetPrivate())
	{
		mpUser->AddReqEmailCount(pvBids->size());
	}

	if (cumTotal > mpItem->GetQuantity())
	{
		*mpStream	<< "\n"
						"Lowest winning bid is :";

		currencyWidget.SetNativeAmount(BidAmount);
		currencyWidget.SetNativeCurrencyId(winningCurrencyId);
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(mpStream);

		*mpStream	<< "\n\n";

		// last bidder may not get full quantity msg
		*mpStream << "\n"
						"The last bidder on this list may not receive total quantity bid for,\n"
						"and reserves the right to refuse purchase of anything less than full\n"
						"quantity. Seller may then skip to next bidder, if any."
						"\n\n";
	}
	else if (cumTotal < mpItem->GetQuantity())
	{
		*mpStream << "\n"
					"Quantity still available. Lowest successful bid is minimum bid amount.\n\n";
	}

	// Close pre, if necessary
	if (pvBids->size() != 0)
	{
		*mpStream	 << "</pre>"
						 << flush;
	}

	// Trailer
	if (pvBids->size() == 0)
	{
		*mpStream << 	  "There have been no bidders yet "
						  "for this Dutch Auction."
						  "\n\n";
	}

	*mpStream << "</pre>"
					 "\n"
				 << flush;


	delete pUserIdWidget;
	// Clean up the bid list first. 
	for (i = pvBids->begin();
	     i != pvBids->end();
	     i++)
	{
		// Delete the bid
		delete	(*i);
	}

	pvBids->erase(pvBids->begin(), pvBids->end());

	delete pvBids;
		
	// Clean
//	delete	pUser;

	return;
}

