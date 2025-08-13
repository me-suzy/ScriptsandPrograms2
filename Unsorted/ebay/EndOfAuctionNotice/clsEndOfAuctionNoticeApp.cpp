/*	$Id: clsEndOfAuctionNoticeApp.cpp,v 1.14.2.5.14.1 1999/08/01 02:51:08 barry Exp $	*/
//
//	File:	clsEndOfAuctionNoticeApp.cpp
//
//	Class:	clsEndOfAuctionNoticeApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 10/26/98 inna	- added wacko filter and wacko report
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsEndOfAuctionNoticeApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsItems.h"
#include "clsItem.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsBid.h"
#include "clsAnnouncements.h"
#include "clsAnnouncement.h"
#include "clsUtilities.h"
#include "clsMail.h"
#include "clsCurrencyWidget.h"
#include "clseBayTimeWidget.h"	// petra

#include "vector.h"
#include "hash_map.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>

#ifdef _MSC_VER
#include <process.h>
#else
#include <sys/types.h>
#include <unistd.h>
#include <signal.h> 
#endif

#ifdef _MSC_VER
#include <strstrea.h>
#else
#include <strstream.h>
#endif

//#ifdef _WIN32
//FILE *popen(const char *, const char *);
//int pclose(FILE *);
//#endif


clsEndOfAuctionNoticeApp::clsEndOfAuctionNoticeApp()
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	mpItems			= (clsItems *)0;
	mpAnnouncements = (clsAnnouncements *)0;
	return;
}


clsEndOfAuctionNoticeApp::~clsEndOfAuctionNoticeApp()
{
	return;
};

//
// DailyStatusFileName
//
//	*** NOTE ***
//	Should this be mpMarketPlace->GetDailyStatusFileName()?
//	*** NOTE ***
//
static const char *DailyStatusFileName	= "endofauction.txt";

// for dutch high bidders for items
// static const char *DailyStatusFileDutch = "endauctiondutch.txt";

// this function emits the item detail and description
// pass is only used for private dutch auctions; if its = 0, its to seller;
// if = 1 its to bidders on the bcc
void clsEndOfAuctionNoticeApp::EmitItemText(ostrstream *pM, clsItem *pItem, int pass)
{

	time_t					itemEndTime;
	char					cItemEndTime[32];
	char					*pSafeText;
	clsAnnouncement			*pAnnouncement;
	clsUser					*pSeller;
	clsUser					*pBidder;

	itemEndTime		= pItem->GetEndTime();
	clsUtilities::GetDateTime(itemEndTime, &cItemEndTime[0]);
	clsCurrencyWidget		currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), pItem->GetPrice());
// petra	currencyWidget.SetForMail(true);

	//e118 move announcements up:
	// emit general announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Header);
	if (pAnnouncement)
	{
		pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pM << pSafeText;
		*pM << "\n";
		delete pAnnouncement;
		delete pSafeText;
	};

	// emit end of auction announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(EndOfAuction,Header);
	if (pAnnouncement)
	{
		pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pM << pSafeText;
		*pM << "\n";
		delete pAnnouncement;
		delete pSafeText;
	};

	// Text	- greetings
	if (pass == 0)
	{
		*pM <<	"\nDear "
			<<	pItem->GetSellerUserId(); // pSeller->GetEmail();

		if (pItem->GetAuctionType() == AuctionChinese)
		{
			if (pItem->GetHighBidder() != 0)
			{

				*pM <<	" and "
					<<	pItem->GetHighBidderUserId(); // pBidder->GetEmail();

				
			}
		}
		else if ((pItem->GetAuctionType() == AuctionDutch) &&
				 (pItem->GetBidCount() > 0) &&
				 (pItem->GetPrivate() != true))
		{
			*pM <<	" and Dutch Auction Bidders";
		}
	}
	else
	{	// pass > 0 means emit item text for private dutch auction bidders
		*pM << "Dear Dutch Auction Bidders";
	};

	*pM <<	",";;

	// private dutch auction; seller only
	//e118 - no more of this text 
	/*if ((pass == 0) && (pItem->GetAuctionType() == AuctionDutch) &&
		(pItem->GetPrivate()))
	{
		*pM << "DO NOT REPLY TO THIS MESSAGE. PLEASE ADDRESS YOUR MAIL DIRECTLY TO BUYER OR SELLER.\n";
	}
	// private dutch auction, buyer only
	else if (pass == 1)
	{
		*pM << "DO NOT REPLY TO THIS MESSAGE. PLEASE ADDRESS YOUR MAIL DIRECTLY TO BUYER OR SELLER.\n";
	}
	// all other cases
	else
	{
		*pM << "DO NOT REPLY TO THIS MESSAGE. PLEASE ADDRESS YOUR MAIL DIRECTLY TO BUYER OR SELLER.\n";
	};*/

	//e118 _ first sentence section
	if (pItem->GetBidCount() < 1)
	{
		*pM <<	"\n\nWe are sorry - the following auction has ended without "
		    <<	"any bidders.";
	}
	else if (pItem->GetPrice() <  pItem->GetReservePrice())
	{	
		*pM <<	"\n\nWe are sorry - the following auction has ended without "
		    <<	"reaching the reserve price.";
	}
	else
	{
		*pM <<	"\n\nCongratulations - this auction successfully ended.";
	}

		
	pSeller = mpUsers->GetUser(pItem->GetSeller());
/* e118 no more 
	*pM <<	"\n"
			"This message is to notify you that the following auction has ended:\n"
			"\n"
			"\t" */
	//e118 - auction info section
	*pM		<<	"\n\n"
			<<  "Item Title: "
			<<	pItem->GetTitle()
			<<	" "
			<<	"(Item #"
			 <<	pItem->GetId()
			 <<	")"
				"\n"
				"\n"
				"\t"
				"Final price:\t\t";

	currencyWidget.EmitHTML(pM);

	*pM	 <<	"\n"
			"\t"
			"Auction ended at:\t"
		 <<	cItemEndTime
		 <<	"\n"
			"\t"
			"Total number of bids:\t"
		 <<	pItem->GetBidCount()
		 <<	"\n"
			"\t"
			"Seller User ID:\t\t"
		 <<	pItem->GetSellerUserId()
		 <<	"\n"
		 	"\t"
			"Seller E-mail:\t\t"
		 <<	pSeller->GetEmail();


	//no bids auction
	if (pItem->GetBidCount() < 1)
	{
		*pM <<	"\n\n*If you'd like to try again and relist your item, eBay can help! "
			<<	"Just visit"
			<<	mpMarketPlace->GetCGIPath()
			<<	"eBayISAPI.dll?ViewItem&item="
			<<	pItem->GetId();
		
	}	// if not dutch auction, emit high bidder
	else
	{	// item has bids
		if (pItem->GetAuctionType() == AuctionChinese)
		{
			if (pItem->GetHighBidder() != 0)
			{
				pBidder = mpUsers->GetUser(pItem->GetHighBidder());

				*pM  <<	"\t"
						"\n\tHigh-bidder User ID:\t\t"
					 <<	pItem->GetHighBidderUserId()
					 <<	"\n"
					 <<	"\t"
						"High-bidder E-mail:\t\t"
					 <<	pBidder->GetEmail();
	
				delete pBidder;
			};

		// 
		// Reserve Auction logic
		//
		// *** NOTE ***
		// If it's NOT a reserve auction, then the reserve price will
		// be 0, and this is just fine...
		// *** NOTE ***
		//

/* out e118			if (pItem->GetPrice() >= pItem->GetReservePrice())
			{
				*pM <<	"Seller "
//					<<	"("
//					<<	pItem->GetSellerUserId()
//					<<	") and high-bidder ("
//					<<	pItem->GetHighBidderUserId()
//					<<	") should now\n"
					<<	"and high bidder should now "
						"contact each other to complete the sale.\n";
                *pM <<  "\n"
						"IMPORTANT: buyer and seller should contact each other "
						"within three business days,\n"
						"or risk losing their right "
						"to complete this transaction.\n\n";  
				}
			else*/
//instead e118
			if (pItem->GetPrice() < pItem->GetReservePrice())
			{
				*pM <<	"\n\nBecause the reserve price set by the seller was not met, "
					<<	"the seller does not have to sell the item at this price.";
				
				*pM <<	"\n\n*This auction results are available for 30 days at "
					<<	mpMarketPlace->GetCGIPath()
					<<	"eBayISAPI.dll?ViewItem&item="
					<<	pItem->GetId();
			}


		} // end chinese auction
		else if (pItem->GetAuctionType() == AuctionDutch)
		{
		// we do not emit dutch auction bidders here because
		// we need to handle private dutch auctions where bidders
		// do not see other bidders; only seller.
		// also we do not have to pass the vector here
			*pM  <<	"\n\t"
					"Quantity For Sale:\t"
				<<	pItem->GetQuantity();
		}
		
/* e118 move iEWscrow wher it shoudl be Item Blurb 
		// Sam, i-escrow hook
		// Seller checked online escrow services in T's&C's
		// Final Item Value is $1000 or more now but will decrement to $200
		if (pItem->AcceptsPaymentEscrow()&& pItem->GetCurrencyId() ==Currency_USD )
		{
			*pM <<	"The seller is willing to use escrow for this transaction.\n"
				<<	"To begin or to learn more, go to:\n"
				<<   mpMarketPlace->GetCGIPath(PageIEscrowLogin)
				<<	"eBayISAPI.dll?iescrowlogin&item="
				<<	pItem->GetId()
				<<	"&type=initial&bidderno=0"
				<<	"\n\n";
		}
		else if ((pItem->GetPrice() >= 750 && pItem->GetCurrencyId() == Currency_USD)
				|| (pItem->GetPrice() >= 600 && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 3, 99, 0, 0, 0) >= 0) 
				    && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 10, 99, 0, 0, 0) < 0) 
					&& pItem->GetCurrencyId() == Currency_USD)
				|| (pItem->GetPrice() >= 500 && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 10, 99, 0, 0, 0) >= 0) 
				    && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 17, 99, 0, 0, 0) < 0) 
					&& pItem->GetCurrencyId() == Currency_USD)
				|| (pItem->GetPrice() >= 400 && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 17, 99, 0, 0, 0) >= 0) 
				    && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 24, 99, 0, 0, 0) < 0) 
					&& pItem->GetCurrencyId() == Currency_USD)
				|| (pItem->GetPrice() >= 300 && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 24, 99, 0, 0, 0) >= 0) 
				    && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 31, 99, 0, 0, 0) < 0) 
					&& pItem->GetCurrencyId() == Currency_USD)
				|| (pItem->GetPrice() >= 200 && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 31, 99, 0, 0, 0) >= 0)
					&& pItem->GetCurrencyId() == Currency_USD))
		{
			*pM <<  "Consider using online escrow to complete your transaction securely.\n"
				<<	"To begin or to learn more, go to:\n"
				<<   mpMarketPlace->GetCGIPath(PageIEscrowLogin)
				<<	"eBayISAPI.dll?iescrowlogin&item="
				<<	pItem->GetId()
				<<	"&type=initial"
				<<	"\n\n";
		} */

	} // has bids

	// cleanup
	delete pSeller;
};

/* end of the mail notice blurb */
void clsEndOfAuctionNoticeApp::EmitItemBlurb(ostrstream *pM, clsItem *pItem)
{
	clsAnnouncement *pAnnouncement;
	char			*pSafeText;

	//old if (pItem->GetBidCount() > 0)
	//e118, separate by reserved not met, no bids and sucess
	if (pItem->GetBidCount() > 0 && pItem->GetPrice() >= pItem->GetReservePrice())
	{
		//part one real eslate
		if (pItem->CheckForRealEstateListing())
		{
			*pM <<	"\n\n*The offer and sale of real estate is a complex area, and may be governed "
					"by a variety of local, state and federal laws and private party contractual "
					"arrangements. Buyers and sellers are advised to consult with qualified "
					"professionals as to legal sufficiency, legal effect and tax consequences "
					"when involved in any transactions in real estate.";
		}
		
		//part 2: next step
		*pM <<	"\n\nHere's what to do next:\n\n"
				"*The buyer and seller should contact each other within three business "
				"days to complete the sale. Not getting in touch leaves the contract in "
				"limbo and can earn you negative feedback. If you have trouble, though, "
				"just visit  "
			<<	mpMarketPlace->GetCGIPath()
			<<	"eBayISAPI.dll?MemberSearchShow";

		//part 3 iEscrow:
				// Seller checked online escrow services in T's&C's
		// Final Item Value is $1000 or more now but will decrement to $200
		if (pItem->AcceptsPaymentEscrow()&& pItem->GetCurrencyId() ==Currency_USD )
		{
			*pM <<	"\n\n*The seller is willing to use escrow for this transaction.\n"
				<<	"To begin or to learn more, go to:\n"
				<<   mpMarketPlace->GetCGIPath(PageIEscrowLogin)
				<<	"eBayISAPI.dll?iescrowlogin&item="
				<<	pItem->GetId()
				<<	"&type=initial&bidderno=0";
		}
		else if ((pItem->GetPrice() >= 750 && pItem->GetCurrencyId() == Currency_USD)
				|| (pItem->GetPrice() >= 600 && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 3, 99, 0, 0, 0) >= 0) 
				    && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 10, 99, 0, 0, 0) < 0) 
					&& pItem->GetCurrencyId() == Currency_USD)
				|| (pItem->GetPrice() >= 500 && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 10, 99, 0, 0, 0) >= 0) 
				    && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 17, 99, 0, 0, 0) < 0) 
					&& pItem->GetCurrencyId() == Currency_USD)
				|| (pItem->GetPrice() >= 400 && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 17, 99, 0, 0, 0) >= 0) 
				    && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 24, 99, 0, 0, 0) < 0) 
					&& pItem->GetCurrencyId() == Currency_USD)
				|| (pItem->GetPrice() >= 300 && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 24, 99, 0, 0, 0) >= 0) 
				    && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 31, 99, 0, 0, 0) < 0) 
					&& pItem->GetCurrencyId() == Currency_USD)
				|| (pItem->GetPrice() >= 200 && (clsUtilities::CompareTimeToGivenDate(time(0), 5, 31, 99, 0, 0, 0) >= 0)
					&& pItem->GetCurrencyId() == Currency_USD))
		{
			*pM <<  "\n\n*Because this is a high-price item, "
				<<  "you might want to safeguard your transaction by using an online "
				<<   "escrow service. To learn more, just visit "
				<<   mpMarketPlace->GetCGIPath(PageIEscrowLogin)
				<<	"eBayISAPI.dll?iescrowlogin&item="
				<<	pItem->GetId()
				<<	"&type=initial";
		} 

		//part 4 feedback:
			*pM <<	"\n"
					"\n"
					"*Help other eBay users by leaving feedback about your transaction, at "
				<<	mpMarketPlace->GetCGIPath(PageLeaveFeedbackShow)
				<<	"eBayISAPI.dll?LeaveFeedbackShow&item="
				<<	pItem->GetId();

		//part5 auction info
		*pM <<	"\n\n*This auction's results, including email addresses of all bidders, "
			<<  "are available for 30 days at "
			<<	mpMarketPlace->GetCGIPath()
			<<	"eBayISAPI.dll?ViewItem&item="
			<<	pItem->GetId();

		//part 6 gift icon
		*pM<< "\n\n*If you've bought this item as a gift, you can let the lucky recipient "
			<< "know what's coming! As long as the seller has a positive feedback rating of "
			<< "at least 10, just visit "
			<<	mpMarketPlace->GetCGIPath()
			<<	"eBayISAPI.dll?ViewGiftAlert"
				"&item="
			<<	pItem->GetId();

		if (pItem->GetQuantity() == 1 && pItem->GetHighBidder() != 0 &&
			!pItem->GetPrivate())
		{
			*pM <<	"&userid="
				<<	pItem->GetHighBidderUserId();
		}

		//part 7 general info
		*pM <<	"\n\n*For further information and resources, visit "
			<< mpMarketPlace->GetHTMLPath()
			<<	"help/sellerguide/after-tips.html";
		
		//part 8 notes on payments
		*pM << "\n"
			   "\n"
				"Note to Bidders: If you're a winning bidder, send your payment to the seller."

				"\n\nNote to Sellers: If you're a seller paying eBay with a check or money order, "
				"here's where to mail it:\n"
				"eBay, Inc.\n"
				"P.O. Box 200945\n"
				"Dallas, TX 75320-0945";

		//part 9 ending 
		*pM << "\n\nWe're so glad your auction was successful, and we hope to see you at "
			<< "eBay again soon! And be sure to tell your friends about us-we'd love to "
			<< "see them here too.";

	}//end of acutions with bids
	else // no bids, or not reserve not met
	{ 
			*pM << "\n\nThank you for coming to eBay--we hope to see "
				<< "you here again soon! And be sure to tell your friends "
				<< "about us--we's love to see them here too.";
	}


		// emit general footer announcements - e118 flip genral and EOA
		pAnnouncement = mpAnnouncements->GetAnnouncement(EndOfAuction,Footer);
		if (pAnnouncement)
		{
			*pM << "\n\n";
			pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
			*pM << pSafeText;
			delete pAnnouncement;
			delete pSafeText;
		};

		// emit end of auction footer announcements
		pAnnouncement = mpAnnouncements->GetAnnouncement(General,Footer);
		if (pAnnouncement)
		{
			pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
			*pM << "\n";
			*pM << pSafeText;
			delete pAnnouncement;
			delete pSafeText;
		};

	/* old text
	  *pM <<	mpMarketPlace->GetThankYouText()
		<<	" If you have not already done so\n"
			"today, it wouldn't hurt to mention "
		<<	mpMarketPlace->GetCurrentPartnerName()
		<<	" to a few of your friends!\n"
			"\n"
		<<	mpMarketPlace->GetHomeURL()
		<<	"\n"
			"--------------------------------------------------------------------"
			"\n";
	*/

	/* e118 Trade ON! */

	*pM <<	"\n\nTrade On!";

	pSafeText = clsUtilities::RemoveHTMLTag(pItem->GetDescription());

	// item's description
	*pM <<	"\n\nItem Description:"
			"\n\n";
	//inna monitor for no description found:
	//if there was no description pSafeText 
	if (pSafeText!=NULL)
		*pM <<	pSafeText;

	*pM	<<	"\n\n";

	delete pSafeText;
};

/* emits end of auction notice for a given item */
void clsEndOfAuctionNoticeApp::EmitEndOfAuctionNotice(clsItem *pItem,
								   	FILE			*pEndAuctionLog)
{
	// The stream we'll build the notice in
//	strstream				*pM			= NULL;
	// second mail msg for private dutch bidders
//	strstream				*pPvtDutchM	= NULL;


	// Reformatted dates
	time_t					itemEndTime;
	struct tm				*pItemEndTimeTM;
	char					cItemEndTime[32];
// petra - unused?	char					cItemEndDateRFC802[128];

//	char					*pTheNotice;
	//
	// Mailer
	//
//	char					mailCommand[64 + EBAY_MAX_USERID_SIZE + 1];
//	FILE					*pPipe;
//	int						mailRc;
	clsMail		*pMail;
	clsMail		*pDutchMail; // for private dutch auctions
	ostrstream	*pM;
	ostrstream  *pPvtDutchM;
	char		subject[512];
	char		**recipients;

	// For Dutch Auctions only
	BidVector				*pvBidders	= NULL;
	BidVector::iterator		i;
	clsUser					*pUser;
	clsUser					*pSeller;
	clsUser					*pBidder;

	int						cumTotal;
	int						qtyWon;
	double					amtDue;
	int						j;
	int						k;
	bool					hasCC;  // has cc indicator

	//
	// Reformat dates
	//
	itemEndTime		= pItem->GetEndTime();
	pItemEndTimeTM	= localtime(&itemEndTime);
//	strftime(cItemEndTime, sizeof(cItemEndTime), "%m/%d/%y %H:%M:%Y PST",
//             pItemEndTimeTM);
	clsUtilities::GetDateTime(itemEndTime, &cItemEndTime[0]);
// petra - unused?	strftime(cItemEndDateRFC802, sizeof(cItemEndDateRFC802),
// petra				"%a, %d %b %Y %H:%M:%S %z",
// petra				pItemEndTimeTM);

//	pM	= new strstream();
	pMail	= new clsMail();
	pM	= pMail->OpenStream();

	pSeller = mpUsers->GetUser(pItem->GetSeller());

	// check validity of seller email
	if (pSeller->GetEmail() == '\0')
	{
		fprintf(pEndAuctionLog, "** Error ** Could NOT get email for seller %d of item %d\n",
				pItem->GetSeller(), pItem->GetId());
		delete pMail;
		delete pSeller;
		return;
	}

	sprintf(subject,
				"%s End of Auction - Item # %d (%s)",
				mpMarketPlace->GetCurrentPartnerName(),
				pItem->GetId(),
				pItem->GetTitle());

	hasCC = false;
	recipients = 0;

	//
	// If it's NOT a dutch auction, we're going to send
	// out one notice, and we can do it now.
	//
	if (pItem->GetAuctionType() == AuctionChinese)
	{

		// prepare the stream
		pM->setf(ios::fixed, ios::floatfield);
		pM->setf(ios::showpoint, 1);
		pM->precision(2);

		EmitItemText(pM, pItem);

		// Nice headers
//		*pM <<	"To: "
//			<<	pSeller->GetEmail(); // pItem->GetSellerUserId();

		if (pItem->GetHighBidder() != 0)
		{
			pBidder = mpUsers->GetUser(pItem->GetHighBidder());

			if (pBidder->GetEmail() == '\0')
			{
				fprintf(pEndAuctionLog, "** Error ** Could NOT get email for bidder %d of item %d\n",
					pItem->GetHighBidder(), pItem->GetId());

				EmitItemBlurb(pM, pItem);
				if (pSeller->SendEndofAuction())
				{
					pMail->Send(pSeller->GetEmail(),
					//inna-testpMail->Send("inna@ebay.com",
					(char *)mpMarketPlace->GetConfirmEmail(),
					subject);
				}
			}
			else
			{
//			*pM	<<	","
//				<<	pBidder->GetEmail();  // pItem->GetHighBidderUserId();
				j = 2;
				recipients = new char *[2];
				recipients[0] = new char[strlen(pBidder->GetEmail()) + 1];
				strcpy(recipients[0], pBidder->GetEmail());
				//inna-test strcpy(recipients[0], "inna@ebay.com");
				recipients[1] = NULL;

				EmitItemBlurb(pM, pItem);
				pMail->Send(pSeller->GetEmail(),
				//inna-test pMail->Send("inna@ebay.com",
					(char *)mpMarketPlace->GetConfirmEmail(),
					subject,
					recipients);

				// cleanup
				for (k = 0; k < j; k++)
				{
					delete recipients[k];
				}
				delete [] recipients;
			}
			delete pBidder;
		}
		else
		{	// no bidder	
			EmitItemBlurb(pM, pItem);
			pMail->Send(pSeller->GetEmail(),
			//inna-test pMail->Send("inna@ebay.com",
						(char *)mpMarketPlace->GetConfirmEmail(),
						subject);
		};
	
		//inna-make log file smaller
		fprintf(pEndAuctionLog, "Mail sent to %s for item %d.\n",
		pSeller->GetEmail(), pItem->GetId());

		delete	pMail;

	} // chinese auctions are DONE SENT OUT at this point
	else if (pItem->GetAuctionType() == AuctionDutch)
	{
		// For Dutch auctions, we send out one email to the seller and
		// the sucessful high bidder(s). 

		EmitItemText(pM, pItem);

		// create new vector
		if (!pvBidders)
			pvBidders = new BidVector;

		// Let's get the bidders
		pItem->GetDutchHighBidders(pvBidders);

		// First, the standard header..
//		*pM <<	"To: "
//			<<	pSeller->GetEmail() // pItem->GetSellerUserId()
//			<<	"\n";

		// get list of recipients and
		// emit list of bidders to email
		if (pvBidders->size() > 0)
		{
//			*pM <<	"Cc: ";

			cumTotal = 0;

			*pM << "\nList of Dutch auction high bidders: "
				<< "\n";

			recipients = new char *[pvBidders->size() + 1];
	
			j = 0;
			// gather all recipients on cc or bcc list
			// and emit high bidders and their quantities
			for (i = pvBidders->begin();
				 i != pvBidders->end();
				 i++)
			{
				pUser	= mpUsers->GetUser((*i)->mUser);
				if (!pUser)
				{
					fprintf(pEndAuctionLog, "*** Error could not get user %d for item %d\n",
						(*i)->mUser,
						pItem->GetId());
					continue;
				}

//					if (i != pvBidders->begin())
//						*pM <<	",";

				if (pUser->GetEmail() == '\0')
				{
					fprintf(pEndAuctionLog, "** Error ** Could not get email for bidder %d for item %d\n",
							pUser->GetId(), pItem->GetId());
				}
				else
				{		
					recipients[j] = new char[strlen(pUser->GetEmail()) + 1];
					strcpy(recipients[j], pUser->GetEmail());
					//inna-test strcpy(recipients[j],"inna@ebay.com");
					j = j + 1;

//							*pM <<	pUser->GetEmail()
//						<< " ";
				};

				cumTotal = cumTotal + (*i)->mQuantity;
//				*pM	<< "<A HREF=mailto:"
//					<< pUser->GetUserId()
//					<< ">"
//					<< "\t\t";

                if (cumTotal > pItem->GetQuantity())
				{
                     qtyWon = pItem->GetQuantity() - (cumTotal - (*i)->mQuantity);
                    }
                else
                     qtyWon = (*i)->mQuantity;

                amtDue = RoundToCents(pItem->GetPrice()) * qtyWon;

                *pM     << pUser->GetEmail()
						<< " ("
 						<< pUser->GetUserId()
						<< ") "
                        << "\t";
                *pM << " Qty bid:"
                       "  "
                     << (*i)->mQuantity
                     <<      "\t";

               *pM << " Qty won:"
                      "  "
                   << qtyWon
                   << "\t";
               *pM << " Total Value:"

                      "  ";

			   clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), amtDue);
// petra			   currencyWidget.SetForMail(true);
			   currencyWidget.EmitHTML(pM);
           
                *pM   << "\n";

				if (cumTotal > pItem->GetQuantity())
				{
					*pM << "\n"
						"If you are the last bidder on this list you might not receive the entire quantity "
						"you requested. In thi case you have the right to refuse purchase of anything less than full "
						"quantity. The seller may then skip to next bidder, if any. Please go to "
						"the item's bidding history page to view all the bidders for this item."
						"\n";
				}

				delete	pUser;
				delete (*i);
			}

			hasCC = true;
			recipients[j] = NULL;
//				*pM << "\n";
			pvBidders->erase(pvBidders->begin(), pvBidders->end());

           /*e118 move to Item Blurb 
		   *pM <<  "\n"
					"IMPORTANT: buyer and seller should contact each other "
					"within three business days,\n"
					"or risk losing their right "
					"to complete this transaction.\n\n";   */
		}

		EmitItemBlurb(pM, pItem);
//		*pM	<<	endl;

		if (!pItem->GetPrivate() && hasCC)
		{
			pMail->Send(pSeller->GetEmail(),
			//inna-test  pMail->Send("inna@ebay.com",
						(char *)mpMarketPlace->GetConfirmEmail(),
						subject,
						recipients);
		}
		else // no bidders or private dutch 
			pMail->Send(pSeller->GetEmail(),
			//inna-test pMail->Send("inna@ebay.com",
					(char *)mpMarketPlace->GetConfirmEmail(),
					subject);

		//inna-make log smaller
		fprintf(pEndAuctionLog, "Mail sent to %s for item %d.\n",
		pSeller->GetEmail(), pItem->GetId());

		delete pMail;

	// Print it!
//	pTheNotice	= pM->str();
//	pTheNotice[pM->pcount()]='\0';
	// printf("\n%s\n", pTheInvoice);

//	sprintf(mailCommand,
//				 "/usr/lib/sendmail -odq -f %s -F \'eBay Billing\' -t",
//				 mpMarketPlace->GetConfirmEmail());
//	pPipe	= popen(mailCommand, "w");
//	fprintf(pPipe, "%s", pTheNotice);
//	mailRc	= pclose(pPipe);

	// Let's clean and free that buffer
//	memset(pTheNotice, 0x00, pM->pcount());
//	delete	pTheNotice;

	// Now, scotch the stream
//	delete	pM;
//	pM	= NULL;


//	if (mailRc != 0)
//	{
//		fprintf(pEndAuctionLog, "** Error! Sendmail returned %d mailing to %s item %d\n",
//			   mailRc, pUser->GetEmail(), pItem->GetId());
//		fprintf(pEndAuctionLog, "** Command <%s>\n", mailCommand);
//
//	}
//	else
//		fprintf(pEndAuctionLog, "Mail sent to %s for item %d.\n",
//		pSeller->GetEmail(), pItem->GetId());

	// mail off the private dutch bidders' separate mail
		if (pItem->GetPrivate())
		{

		// new mail for just the bidders list
			pDutchMail	= new clsMail();
			pPvtDutchM	= pDutchMail->OpenStream();
//			pPvtDutchM = new strstream();
//			*pPvtDutchM << "To: \n"
//						<< "Bcc: ";
						
			EmitItemText(pPvtDutchM, pItem, 1);
			EmitItemBlurb(pPvtDutchM, pItem);
//			*pPvtDutchM	<<	endl;
			// send to all bidders in bcc list
			/** inna-test **/	
			pDutchMail->Send(NULL,
					(char *)mpMarketPlace->GetConfirmEmail(),
					subject,
					NULL, recipients);
			/** inna-test **/

			delete pDutchMail;
		};

		if (hasCC)
		{
			for (k = 0; k < j; k++)
			{
				delete recipients[k];
			}
			delete [] recipients;
		};

//		if (pPvtDutchM != NULL)
//		{ 
			// mail it off
//			pTheNotice	= pPvtDutchM->str();
//			pTheNotice[pPvtDutchM->pcount()]='\0';

//			sprintf(mailCommand,
//				 "/usr/lib/sendmail -odq -f %s -F \'eBay Billing\' %s",
//				 mpMarketPlace->GetConfirmEmail(),
//				 "tini@ebay.com"
//				 /* pUser->GetUserId() */);
//			pPipe	= popen(mailCommand, "w");
//			fprintf(pPipe, "%s", pTheNotice);
//			mailRc	= pclose(pPipe);

			// Let's clean and free that buffer
//			memset(pTheNotice, 0x00, pPvtDutchM->pcount());
//			delete	pTheNotice;

			// Now, scotch the stream
//			delete	pPvtDutchM;
//			pPvtDutchM	= NULL;

/*			if (mailRc != 0)
			{
				fprintf(pEndAuctionLog, "** Error! Sendmail returned %d mailing to %s item %d\n",
					   mailRc, pUser->GetEmail(), pItem->GetId());
				fprintf(pEndAuctionLog, "** Command <%s>\n", mailCommand);
			}
			else
				fprintf(pEndAuctionLog, "Mail sent to %s for item %d\n",
				pSeller->GetEmail(), pItem->GetId());
				*/
//		}
	}
	delete pSeller;
	delete pvBidders;
	return;
};

/* emits end of auction notice for a given item */
void clsEndOfAuctionNoticeApp::EmitBillNotice(clsItem *pItem,
								   	FILE			*pEndAuctionLog,
									FILE			*pWackoItemsLog)
{
	clsUser					*pUser;
	clsAccount				*pAccount;

	BidVector				vBidders;
	BidVector::iterator	iBidder;
	clsBid					*pBid;
	int						qtysold;
	long					billtime;
	// next, check if it has been billed before
	billtime = pItem->GetDBBillTime();

	if (billtime == 0)
	{ // need to bill; item not billed before

		if (pItem->GetHighBidder() == 0)
		{
			pItem->SetBillTime(time(0));
			//delete	pItem;
			return;
		}

		if (pItem->GetReservePrice() != 0 &&
			pItem->GetPrice() < pItem->GetReservePrice())
		{
			pItem->SetBillTime(time(0));
			//delete	pItem;
			return;
		}

		if (pItem->ChargeNoFinalValueFee())
		{
			pItem->SetBillTime(time(0));
			//delete	pItem;
			return;
		}

		// And now the user
		pUser	= mpUsers->GetUser(pItem->GetSeller());

		if (!pUser)
		{
			fprintf(stderr, "*Error* Cannot find user %d for item %d\n",
					  pItem->GetSeller(), pItem->GetId);
			return;
		}

		// We'll need their account too
		pAccount	= pUser->GetAccount();

		// For Dutch Auctions, get quantity sold
		if (pItem->GetAuctionType() == AuctionDutch &&
			pItem->GetBidCount() > 0 &&
			pItem->GetPrice() > 0)
		{
			// dutch auction, emit dutch high bidder to *mpStreamDutch
			pItem->GetDutchHighBidders(&vBidders);
			qtysold = 0;
			for (iBidder = vBidders.begin();
				 iBidder != vBidders.end();
				 iBidder++)
			{
				pBid = (*iBidder);
				qtysold = qtysold + pBid->mQuantity;
			}
		  for (iBidder = vBidders.begin();
			  iBidder != vBidders.end();
			  iBidder++)
		  {
			 delete (*iBidder);
		  }
			vBidders.erase(vBidders.begin(), vBidders.end());
		}
		else
			qtysold	= 1;

		// See if the qtysold exceeds the item's quantity, and,
		// if it does, force it to the quantity. This accounts
		// for the case where the last Dutch high bidder didn't
		// get "all" of their order, but we only bill for the
		// quantity
		if (qtysold > pItem->GetQuantity())
			qtysold	= pItem->GetQuantity();

		// Emit Seller things...
		// Let's charge the user for the listing
		if (pItem->GetAuctionType() == AuctionDutch)
		{
			pAccount->ChargeListingFee(pItem, qtysold);
			//update gms, for sold dutch auctions
			pItem->SetDBDutchGMS(pItem->GetPrice() * qtysold);

			//inna - added wacko processing 
			//apply Wacko Filter
			if ((pItem->GetPrice() * qtysold) >= 25000)
			{
				//add to wako report
				//inna-make log smaller
				fprintf(pEndAuctionLog, "** Item %d is wacko\n", pItem->GetId());
				fprintf(pWackoItemsLog, "%d\t%d\t%8.2f\t%d\n",pItem->GetId(),
						qtysold,pItem->GetPrice(),pItem->GetSeller());
				//set wacko flag
				pItem->SetItemWackoFlag(true);
			}
		}
		else
		{
			pAccount->ChargeListingFee(pItem);
			//inna - added wacko processing 
			//apply Wacko Filter
			if (pItem->GetPrice() >= 10000)
			{
				//add to wako report
				//inna-make log smaller
				fprintf(pEndAuctionLog, "** Item %d is wacko \n", pItem->GetId());
				fprintf(pWackoItemsLog, "%d\t%d\t%8.2f\t%d\n",pItem->GetId(),
							pItem->GetQuantity(),pItem->GetPrice(),pItem->GetSeller());
				//set wacko flag
				pItem->SetItemWackoFlag(true);
			}

			// if item was relisted after change in relisting rules
			// 1998-07-06  899704800 or 899712000
			// inna-temporary code to be removed once our 
			// problems with python are over
			struct tm*      pTimeTm;
			time_t			Nineth=time(0);
			time_t			ThirtyFirst=time(0);

			pTimeTm = localtime(&Nineth);
			pTimeTm->tm_sec = 0;
			pTimeTm->tm_min = 0;
			pTimeTm->tm_hour = 0;
			pTimeTm->tm_mday = 9;
			pTimeTm->tm_mon = 11;
			pTimeTm->tm_year=98;

			Nineth= mktime(pTimeTm);

			pTimeTm = localtime(&ThirtyFirst);
			pTimeTm->tm_sec = 23;
			pTimeTm->tm_min = 59;
			pTimeTm->tm_hour = 59;
			pTimeTm->tm_mday = 24;
			pTimeTm->tm_mon = 0;
			pTimeTm->tm_year=99;
			ThirtyFirst = mktime(pTimeTm);

			if (pItem->GetStartTime() > 899708400) 
			{
			if ((pItem->GetStartTime() < Nineth) ||
				(pItem->GetEndTime() > ThirtyFirst))
				{
					// if it is a relisting item, refund the insertion fee
					if (pItem->GetPassword() & ItemRelisting)
						pAccount->ApplyInsertionFeeCredit(pItem,
						NULL);
				}
			}
		}

		// Show we've billed them
		pItem->SetBillTime(time(0));

		delete	pUser;
		delete	pAccount;
	}

	// else, already billed
	//delete	pItem;
	return;

};


void clsEndOfAuctionNoticeApp::Run(time_t today, time_t StartTime, time_t EndTime)
{
	// This a vector of "Stub" items which haven't 
	// gotten their end-of-auction notices yet
	//vector<int> 			vItems;

	//inna NOW vItems contain  RowId AND ItemId
	vector<clsItemIdRowId*>				vItems;

	// This is a vector of the same items, all filled
	// out
   // ItemVector				vUnNoticedItems;

	// And an iterator
	//vector<int>::iterator	i;
	vector<clsItemIdRowId*>::iterator	i;

	// This is used for the fully-filled out item
	clsItem					*pItem;

	time_t					noticeTime;
	// used for timing stats; 
	// currentTime and endTime figures out time for mail
	// mailTime add them up for the whole session.
    time_t                  nowTime;
	time_t					endTime;
	time_t					mailTime = 0;
	struct tm*				LocalTime;

	// This is the 
	// File stuff
	FILE			*pEndAuctionLog;
	char			fname[25];
	struct tm*		pTime;

	//wako report stuff
	FILE			*pWackoItemsLog;
	char			fwacko[25];

	int				our_pid;
	char			pPid[10];
	clsEOAState		*pEOAState;

	//check for item not being noticed already
	long			current_Notice_Time;

	//lets get out state records in order:
	//what is our pid?
	#ifdef _MSC_VER
		our_pid = getpid();
	#else
		our_pid = _getpid();
	#endif

	sprintf(pPid,"%d", our_pid);

	// The things we need
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();

	if (!mpItems)
		mpItems			= mpMarketPlace->GetItems();

	if (!mpAnnouncements)
		mpAnnouncements = mpMarketPlace->GetAnnouncements();
	

	if (StartTime==0 && EndTime ==0)
	{
		//if no dates passed just a simple new run, next call creates a state record
		pEOAState = new clsEOAState(today, pPid);
		if (!pEOAState->CreateNextEOAStateInfo())
		{
			//problems with state table
			fprintf(stderr,"Unable to create a next instance record in a state table\n");
			delete pEOAState;
			return;
		}
	}
	else
	// dates were passed
	{
		pEOAState = new clsEOAState(today, StartTime, EndTime,pPid);
		//rerun, if possible, 
		//next call rerives and updates started, pid, seqid on the record
		if (!pEOAState->GetEOAStateInfo())
		{
			//problems with state table
			fprintf(stderr,"Unable rerun instance due to state table problems\n");
			delete pEOAState;
			return;
		}
	}

	noticeTime = time(0);
	pTime = localtime(&noticeTime);

	sprintf(fname, "%dendauction%04d%02d%02d.txt", 
			pEOAState->GetSeqId(),
			pTime->tm_year + 1900,
			pTime->tm_mon+1, 
			pTime->tm_mday);

	// File shenanigans
	pEndAuctionLog	= fopen(fname, "a");

	if (!pEndAuctionLog)
	{
		fprintf(stderr,"%s:%d Unable to open end of auction log. \n",
			  __FILE__, __LINE__);
	}


	//lest get name and open wacko report
	sprintf(fwacko, "%dwackoitems%04d%02d%02d.txt", 
			pEOAState->GetSeqId(),
			pTime->tm_year + 1900,
			pTime->tm_mon+1, 
			pTime->tm_mday);

	pWackoItemsLog	= fopen(fwacko, "a");

	if (!pWackoItemsLog)
	{
		fprintf(stderr,"%s:%d Unable to open wacko items log. \n",
			  __FILE__, __LINE__);
	}

	fprintf(pWackoItemsLog, "Item\tQuantity\tPrice\tSeller\n");


	// First, let's get the items
	//inna - cvhange to get rowids 
	//mpItems->GetItemsNotNoticed(&vItems, pEOAState->GetFrom_Time(),pEOAState->GetEnd_Time());
	mpItems->GetItemsNotNoticedRowId(&vItems, pEOAState->GetFrom_Time(),pEOAState->GetEnd_Time());

	nowTime = time(0);
	LocalTime = localtime(&nowTime);

	fprintf(pEndAuctionLog,
		"%2.2d/%2.2d/%2.2d %2.2d:%2.2d:%2.2d\t Done getting all items not noticed.\n",
		LocalTime->tm_mon+1, LocalTime->tm_mday, LocalTime->tm_year,
		LocalTime->tm_hour, LocalTime->tm_min, LocalTime->tm_sec);

	
	time_t temp_time=pEOAState->GetFrom_Time();
	LocalTime = localtime(&temp_time);
	fprintf(pEndAuctionLog,
		"items with sale_end > = %2.2d/%2.2d/%2.2d %2.2d:%2.2d:%2.2d ",
		LocalTime->tm_mon+1, LocalTime->tm_mday, LocalTime->tm_year,
		LocalTime->tm_hour, LocalTime->tm_min, LocalTime->tm_sec);

	temp_time=pEOAState->GetEnd_Time();
	LocalTime = localtime(&temp_time);
	fprintf(pEndAuctionLog,
		" and sale_end < %2.2d/%2.2d/%2.2d %2.2d:%2.2d:%2.2d \n",
		LocalTime->tm_mon+1, LocalTime->tm_mday, LocalTime->tm_year,
		LocalTime->tm_hour, LocalTime->tm_min, LocalTime->tm_sec);

	// Show how many there are
	fprintf(pEndAuctionLog, "%d Items to notice\n", vItems.size());

	//inna-test int inna=1;
	// Now, we loop through them
	for (i = vItems.begin();
		 i != vItems.end();
		 i++)
	//inna-test if (inna<50)
	//inna-test {
	{
		// Let's get the whole item
		//inna - fake itemid, give it rownum
		pItem	= mpItems->GetItem((*i)->GetItemId(), true, (*i)->GetRowId());

		if (!pItem)
        {
			fprintf(pEndAuctionLog,"** Error could get item %d\n", (*i)->GetItemId());
            continue;
         }

		//lets see if it is not in the ebay_item_info table yet?
		//as of e118, it must be null in ebay_items table
		current_Notice_Time = pItem->GetDBNoticeTime();
		/* if not null, then do this */
		if (pItem && (current_Notice_Time == 0))
		{
			pItem->Finalize();

			//moved up, so billing done first, then notice goes out
			EmitBillNotice(pItem, pEndAuctionLog, pWackoItemsLog);

			nowTime = time(0);
			LocalTime = localtime(&nowTime);

			//inna-make log smaller
			fprintf(pEndAuctionLog,
			"%2d/%2d/%2d %2d:%2d:%2d start: ",
			LocalTime->tm_mon+1, LocalTime->tm_mday, LocalTime->tm_year,
			LocalTime->tm_hour, LocalTime->tm_min, LocalTime->tm_sec);

			// for chinese only; do smth different for dutch?
			EmitEndOfAuctionNotice(pItem, pEndAuctionLog);

			endTime = time(0);
			LocalTime = localtime(&endTime);
			
			//inna-make log smaller
		fprintf(pEndAuctionLog,
		"%2d/%2d/%2d %2d:%2d:%2d end: ",
		LocalTime->tm_mon+1, LocalTime->tm_mday, LocalTime->tm_year,
		LocalTime->tm_hour, LocalTime->tm_min, LocalTime->tm_sec);

			mailTime = mailTime + (endTime - nowTime);
			noticeTime	= time(0);
			pItem->SetNoticeTime(noticeTime);

			//MOVE UP - inna- bill now
			//EmitBillNotice(pItem, pEndAuctionLog, pWackoItemsLog);

		}
		delete (*i);
		delete pItem;

	//inna-test inna++;
	}
    //inna-test }//end inna 
	fprintf(pEndAuctionLog,
			"%d total mail time\n ", mailTime);

	vItems.erase(vItems.begin(), vItems.end());

	//time to update our state table - we are done
	pEOAState->MakeInstanceComplete();
	delete pEOAState;
}


static clsEndOfAuctionNoticeApp *pTestApp = NULL;

// expecting pTime in format: dd:mm:yy
//
bool ConvertToTime_t(char *pDate,
					   time_t *pTheTime)
{
	struct tm	localTime;

	memset(&localTime, 0x00, sizeof(localTime));

	// We can use sscanf since Oracle is assumed
	// to be well behaved
	sscanf(pDate, "%d-%d-%d-%d:%d:%d",
		   &localTime.tm_year,
		   &localTime.tm_mon,
		   &localTime.tm_mday,
		   &localTime.tm_hour,
		   &localTime.tm_min,
		   &localTime.tm_sec);

	localTime.tm_mon--;
	localTime.tm_year -= 1900;
	localTime.tm_isdst	= -1;

	
	*pTheTime		= mktime(&localTime);

	return true;
}

void InputError()
{
	// wrong syntax
	printf("Input syntax error!\n");
printf("Usage:\n\tEndOfAuctionNotice [-s yyyy-mm-dd-hh24:mi:ss] [-e yyyy-mm-dd-hh24:mi:ss]\n");
}


int main(int argc, char* argv[])
{
	time_t today= time(0);

	int		Index = 1;
	char*	pStartDate = NULL;
	char*	pEndDate = NULL;
	time_t	StartTime = 0;
	time_t	EndTime = 0;


#ifdef _MSC_VER
	g_tlsindex = 0;
#endif

		//get dates from command line
	while (--argc)
	{
		switch (argv[Index][1])
		{
		// Get starting date
		case 's':
			pStartDate = argv[++Index];
			Index++;
			argc--;

			if (!ConvertToTime_t(pStartDate, &StartTime))
			{
				InputError();
				exit(0);
			}
			break;

		// Get ending date
		case 'e':
			pEndDate = argv[++Index];
			Index++;
			argc--;

			if (!ConvertToTime_t(pEndDate, &EndTime))
			{
				InputError();
				exit(0);
			}
			break;

		default:
			InputError();
			return 0;
		}
	}

	//only one date provided - error
	if (StartTime == 0 && EndTime != 0)
	{
		printf("Start Date is required when End Date is provided.\n");
		return 0;
	}
	if (StartTime != 0 && EndTime == 0)
	{
		printf("End Date is required when Start Date is provided.\n");
		return 0;
	}


	if (!pTestApp)
	{
		pTestApp	= new clsEndOfAuctionNoticeApp();
	}

	pTestApp->InitShell();
	pTestApp->Run(today, StartTime, EndTime);

	return 0;
}
