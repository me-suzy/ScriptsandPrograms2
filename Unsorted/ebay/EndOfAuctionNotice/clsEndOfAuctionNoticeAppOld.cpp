/*	$Id	*/
//
//	File:	clsEndOfAuctionNoticeAppOld.cpp
//
//	Class:	clsEndOfAuctionNoticeAppOld
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 10/26/98 inna	- added wacko filter and wacko report
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//				- 08/02/99 petra	- no SetForMail in currency widget any more..
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsEndOfAuctionNoticeAppOld.h"
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


#include "vector.h"
#include "hash_map.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>

#ifdef _MSC_VER
#include <strstrea.h>
#else
#include <strstream.h>
#endif

//#ifdef _WIN32
//FILE *popen(const char *, const char *);
//int pclose(FILE *);
//#endif


clsEndOfAuctionNoticeAppOld::clsEndOfAuctionNoticeAppOld()
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	mpItems			= (clsItems *)0;
	mpAnnouncements = (clsAnnouncements *)0;
	return;
}


clsEndOfAuctionNoticeAppOld::~clsEndOfAuctionNoticeAppOld()
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
void clsEndOfAuctionNoticeAppOld::EmitItemText(ostrstream *pM, clsItem *pItem, int pass)
{

	time_t					itemEndTime;
	char					cItemEndTime[32];
	char					*pSafeText;
	clsAnnouncement			*pAnnouncement;
	clsUser					*pSeller;
	clsUser					*pBidder;

        itemEndTime             = pItem->GetEndTime();
        clsUtilities::GetDateTime(itemEndTime, &cItemEndTime[0]);
        clsCurrencyWidget		currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), pItem->GetPrice());
// petra	currencyWidget.SetForMail(true);


	// Text
	if (pass == 0)
	{
		*pM <<	"Dear "
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

	*pM <<	","
			"\n"
			"\n";

	// private dutch auction; seller only
	if ((pass == 0) && (pItem->GetAuctionType() == AuctionDutch) &&
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
	};

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

	pSeller = mpUsers->GetUser(pItem->GetSeller());

	*pM <<	"\n"
			"This message is to notify you that the following auction has ended:\n"
			"\n"
			"\t"
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
		 <<	pSeller->GetEmail()
		 <<	"\n";

	if (pItem->GetBidCount() < 1)
	{
		*pM <<	 "\nUnfortunately, there were no bidders for this item.\n"
				"If you wish to relist this item, please go to the relist link at:\n"
			<<	mpMarketPlace->GetCGIPath()
			<<	"eBayISAPI.dll?ViewItem&item="
			<<	pItem->GetId()
			<<	"\n"
				"\n";   
		
	}	// if not dutch auction, emit high bidder
	else
	{	// item has bids
		if (pItem->GetAuctionType() == AuctionChinese)
		{
			if (pItem->GetHighBidder() != 0)
			{
				pBidder = mpUsers->GetUser(pItem->GetHighBidder());

				*pM  <<	"\t"
						"High-bidder User ID:\t\t"
					 <<	pItem->GetHighBidderUserId()
					 <<	"\n"
					 <<	"\t"
						"High-bidder E-mail:\t\t"
					 <<	pBidder->GetEmail()
					 <<	"\n"
						"\n";
	
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

			if (pItem->GetPrice() >= pItem->GetReservePrice())
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
			else
			{
				*pM <<	"Seller's reserve price was NOT MET by any bidder.\n"
						"Seller HAS NOT PROMISED TO SELL THIS ITEM AT THIS PRICE.\n\n";
				}


			} // end chinese auction
		else if (pItem->GetAuctionType() == AuctionDutch)
		{
		// we do not emit dutch auction bidders here because
		// we need to handle private dutch auctions where bidders
		// do not see other bidders; only seller.
		// also we do not have to pass the vector here
			*pM  <<	"\t"
					"Quantity For Sale:\t"
				<<	pItem->GetQuantity()
				<<	"\n"
					"\n";
		}

		// Sam, i-escrow hook
		// Seller checked online escrow services in T's&C's
		// Final Item Value is $1000 or more now but will decrement to $200
		if (pItem->AcceptsPaymentEscrow()&& pItem->GetCurrencyId() == Currency_USD )
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
		}
	} // has bids

	// cleanup
	delete pSeller;
};

/* end of the mail notice blurb */
void clsEndOfAuctionNoticeAppOld::EmitItemBlurb(ostrstream *pM, clsItem *pItem)
{
	clsAnnouncement *pAnnouncement;
	char			*pSafeText;

	if (pItem->GetBidCount() > 0)
	{
		if (pItem->CheckForRealEstateListing())
		{
			*pM <<	"The offer and sale of real estate is a complex area, and may be governed "
					"by a variety of local, state and federal laws and private party contractual "
					"arrangements. Buyers and sellers are advised to consult with qualified "
					"professionals as to legal sufficiency, legal effect and tax consequences "
					"when involved in any transactions in real estate."
					"\n"
					"\n";
		}

		*pM <<	  "The official results of this auction (including e-mail "
				"addresses of all bidders) can be\n"
				"found for 30 days after the auction closes at:\n"
			<<	mpMarketPlace->GetCGIPath()
			<<	"eBayISAPI.dll?ViewItem&item="
			<<	pItem->GetId()
			<<	"\n"
				"\n"
				"If you won an auction in which the seller has at least "
				"a positive feedback rating\n"
				"of 10, you can send a gift alert. This is a great feature "
				"if you're buying gifts\n"
				"or if you're a little late on your gift-giving. To use this "
				"feature, see:\n"
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

		*pM <<	"\n"
				"\n"
				"If you have trouble contacting each other via email:\n"
			<<	mpMarketPlace->GetHTMLPath()
			<<	"user-query.html"
                        <<      "\n"
                                "\n"
                                "Please leave feedback about your transaction:\n"
                        <<      mpMarketPlace->GetCGIPath(PageLeaveFeedbackShow)
                        <<      "eBayISAPI.dll?LeaveFeedbackShow&item="
                        <<      pItem->GetId()
			<<	"\n"
				"\n"
				"For other valuable \"after the auction\" needs:\n"
			<<	mpMarketPlace->GetHTMLPath()
			<<	"postauction.html"
			<<	"\n\n";
	}

		// emit general footer announcements
		pAnnouncement = mpAnnouncements->GetAnnouncement(General,Footer);
		if (pAnnouncement)
		{
			pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
			*pM << pSafeText;
			*pM << "\n";
			delete pAnnouncement;
			delete pSafeText;
		};

		// emit end of auction footer announcements
		pAnnouncement = mpAnnouncements->GetAnnouncement(EndOfAuction,Footer);
		if (pAnnouncement)
		{
			pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
			*pM << pSafeText;
			*pM << "\n";
			delete pAnnouncement;
			delete pSafeText;
		};

		*pM	<<	mpMarketPlace->GetThankYouText()
		<<	" If you have not already done so\n"
			"today, it wouldn't hurt to mention "
		<<	mpMarketPlace->GetCurrentPartnerName()
		<<	" to a few of your friends!\n"
			"\n"
		<<	mpMarketPlace->GetHomeURL()
		<<	"\n"
			"--------------------------------------------------------------------"
			"\n";

	pSafeText = clsUtilities::RemoveHTMLTag(pItem->GetDescription());

	// item's description
	*pM <<	"Item Description:"
			"\n\n";
	//inna monitor for no description found:
	//if there was no description pSafeText 
	if (pSafeText!=NULL)
		*pM <<	pSafeText;

	*pM	<<	"\n\n";

	delete pSafeText;
};

/* emits end of auction notice for a given item */
void clsEndOfAuctionNoticeAppOld::EmitEndOfAuctionNotice(clsItem *pItem,
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
// petra - unused	char					cItemEndDateRFC802[128];

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
// petra - unused	strftime(cItemEndDateRFC802, sizeof(cItemEndDateRFC802),
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
					if (pSeller->SendEndofAuction())  //AndyTon3!
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
				//pMail->Send("inna@ebay.com",
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
			//pMail->Send("inna@ebay.com",
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

			*pM << "List of Dutch auction high bidders: "
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

               *pM << "\n";

				if (cumTotal > pItem->GetQuantity())
				{
					*pM << "\n"
						"The last bidder on this list may not receive total quantity bid for,\n"
						"and reserves the right to refuse purchase of anything less than full\n"
						"quantity. Seller may then skip to next bidder, if any. Please go to\n"
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

            *pM <<  "\n"
					"IMPORTANT: buyer and seller should contact each other "
					"within three business days,\n"
					"or risk losing their right "
					"to complete this transaction.\n\n";  
		}

		EmitItemBlurb(pM, pItem);
//		*pM	<<	endl;

		if (!pItem->GetPrivate() && hasCC)
		{
			pMail->Send(pSeller->GetEmail(),
			//pMail->Send("inna@ebay.com",
						(char *)mpMarketPlace->GetConfirmEmail(),
						subject,
						recipients);
		}
		else // no bidders or private dutch 
			pMail->Send(pSeller->GetEmail(),
			//pMail->Send("inna@ebay.com",
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
}

/* emits end of auction notice for a given item */
void clsEndOfAuctionNoticeAppOld::EmitBillNotice(clsItem *pItem,
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

}


void clsEndOfAuctionNoticeAppOld::Run(time_t StartTime, time_t EndTime)
{
	// This a vector of "Stub" items which haven't 
	// gotten their end-of-auction notices yet
	vector<int>				vItems;

	// This is a vector of the same items, all filled
	// out
	// ItemVector				vUnNoticedItems;

	// And an iterator
	vector<int>::iterator	i;

	// This is used for the fully-filled out item
	clsItem					*pItem;

	time_t					noticeTime;
	// used for timing stats; 
	// currentTime and endTime figures out time for mail
	// mailTime add them up for the whole session.
    time_t                  nowTime;
	time_t					endTime;
	time_t					mailTime = 0;
	struct tm*      LocalTime;

	// This is the 
	// File stuff
	FILE			*pEndAuctionLog;
	char		fname[30];
	struct tm*	pTime;

	//wako report stuff
	FILE			*pWackoItemsLog;
	char			fwacko[30];

	char fromdate[64];
	char todate[64];
	struct tm*	pStartDateAsTm;
	struct tm*	pEndDateAsTm;

	//check for item not being noticed already
	long			current_Notice_Time;

	//lets record dates we use - first convert from time_t to struct tm*
	pEndDateAsTm	= localtime(&EndTime);
	sprintf(todate, "19%2.2d-%2.2d-%2.2d %2.2d:%2.2d:%2.2d", 
			pEndDateAsTm->tm_year, 
			pEndDateAsTm->tm_mon+1,
			pEndDateAsTm->tm_mday,
			pEndDateAsTm->tm_hour, 
			pEndDateAsTm->tm_min,
			pEndDateAsTm->tm_sec);

	pStartDateAsTm	= localtime(&StartTime);
	sprintf(fromdate, "19%2.2d-%2.2d-%2.2d %2.2d:%2.2d:%2.2d", 
			pStartDateAsTm->tm_year, 
			pStartDateAsTm->tm_mon+1,
			pStartDateAsTm->tm_mday,
			pStartDateAsTm->tm_hour, 
			pStartDateAsTm->tm_min,
			pStartDateAsTm->tm_sec);

	noticeTime = time(0);
	pTime = localtime(&noticeTime);

	sprintf(fname, "endauction%04d%02d%02d.%c%c%c%ctxt", 
			pTime->tm_year + 1900,
			pTime->tm_mon+1, 
			pTime->tm_mday,
			fromdate[5],fromdate[6],fromdate[8],fromdate[9]);

	// File shenanigans
	pEndAuctionLog	= fopen(fname, "a");

	if (!pEndAuctionLog)
	{
		fprintf(stderr,"%s:%d Unable to open end of auction log. \n",
			  __FILE__, __LINE__);
	}

	//lest get name and open wacko report
	sprintf(fwacko, "wackoitems%04d%02d%02d.%c%c%c%ctxt",
			pTime->tm_year + 1900,
			pTime->tm_mon+1, 
			pTime->tm_mday,
			fromdate[5],fromdate[6],fromdate[8],fromdate[9]);

	pWackoItemsLog	= fopen(fwacko, "a");

	if (!pWackoItemsLog)
	{
		fprintf(stderr,"%s:%d Unable to open wacko items log. \n",
			  __FILE__, __LINE__);
	}

	fprintf(pWackoItemsLog, "Item\tQuantity\tPrice\tSeller\n");

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

	fprintf(pEndAuctionLog,
		" Items with sale end date >= %s and sale end date < %s\n", fromdate, todate);

	//convert dates to time_t

	// First, let's get the items
	mpItems->GetItemsNotNoticed(&vItems,StartTime, EndTime);

	nowTime = time(0);
	LocalTime = localtime(&nowTime);

	fprintf(pEndAuctionLog,
		"%2d/%2d/%2d %2d:%2d:%2d\t Done getting all items not noticed.\n",
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
		pItem	= mpItems->GetItem((*i), true);
         //tini please buddy check
        if (!pItem)
         {
              fprintf(pEndAuctionLog,"** Error could not get item %d\n", (*i));
              continue;
         }


		//lets see if it is not in the ebay_item_info table yet?
		//as of e118 it must be null in the ebay_items table
		current_Notice_Time = pItem->GetDBNoticeTime();

		/* if not null, then do this */
		if (pItem && (current_Notice_Time == 0))
		{
			pItem->Finalize();

			//moved up  so billing done first, then notice goes out
			//once ebay_items has notice and billing time, we can do this switch
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

			// MOVE UP 
			//EmitBillNotice(pItem, pEndAuctionLog, pWackoItemsLog);

		}
//		delete (*i);
		delete pItem;

	//inna-test inna++;
	}
    //inna-test }//end inna 
	fprintf(pEndAuctionLog,
			"%d total mail time\n ", mailTime);

	vItems.erase(vItems.begin(), vItems.end());
}

static clsEndOfAuctionNoticeAppOld *pTestApp = NULL;

void InputError()
{
	// wrong syntax
	printf("Input syntax error!\n");
	printf("Usage:\n\tEndOfAuctionNotice [-s mm/dd/yy] [-e mm/dd/yy]\n");
}

bool LeapYear(int year)
{
	if ( ( year % 4 ) == 0 ) 
	{
		if ( ( ( year + 1900 ) % 1000 ) == 0 )
			return false;
		else
			return true;
	}
	return false;

}
// expecting pTime in format: dd:mm:yy
//
bool ConvertToTime_t(char* pTime, time_t* pTimeTValue)
{
	int		Day;
	int		Month;
	int		Year;
	struct tm*	pTimeTm;

	char	Sep[] = "/";
	char*	p;

	// Get day
	p = strtok(pTime, Sep);
	Month = atoi(p);
	if (Month < 1 || Month > 12)
	{
		return false;
	}

	// Get month
	p = strtok(NULL, Sep);
	Day = atoi(p);
	if (Day < 1 || Day > 31)
	{
		return false;
	}

	// Get Year
	p = strtok(NULL, Sep);
	Year = atoi(p);
	if (Year < 0)
	{
		return false;
	}

	// put the day, month, and year together
	*pTimeTValue = time(0);
	pTimeTm = localtime(pTimeTValue);
	pTimeTm->tm_mday = Day;
	pTimeTm->tm_mon = Month-1;
	pTimeTm->tm_year = Year;
	pTimeTm->tm_sec			= 0;
	pTimeTm->tm_min			= 0;
	pTimeTm->tm_hour		= 0;
	pTimeTm->tm_isdst		= -1;
	//zero out time
	*pTimeTValue = mktime(pTimeTm);

	return true;
}
int main(int argc, char* argv[])
{
	int		Index = 1;
	char*	pStartDate = NULL;
	char*	pEndDate = NULL;
	time_t	StartTime = 0;
	time_t	EndTime = 0;
	struct tm*	pStartDateAsTm;

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

	//at this point StartTime and End Time have time_t value of parms entered

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

	//if no dates provuded use system date
	//use actual hours,minutes,seconds
	if (StartTime == 0 && EndTime == 0)
	{
		EndTime = time(0); //just sustem date for the end
		
		StartTime = time(0);
		pStartDateAsTm	= localtime(&StartTime);

		//subtract 3 days
		pStartDateAsTm->tm_mday = pStartDateAsTm->tm_mday - 3; 

		if (pStartDateAsTm->tm_mday <=0)
		{ 
			//3 days were in prev month
			
			switch ( pStartDateAsTm->tm_mon)
			{
				case 1: 
				case 3:
				case 5:
				case 7:
				case 8:
				case 10:
					pStartDateAsTm->tm_mday = 31 + pStartDateAsTm->tm_mday;
					pStartDateAsTm->tm_mon -- ; 
					break;

				case 4: 
				case 6: 
				case 9:
				case 11:
					pStartDateAsTm->tm_mday = 30 + pStartDateAsTm->tm_mday;
					pStartDateAsTm->tm_mon -- ; 
					break;

				case 0:
					pStartDateAsTm->tm_mday = 31 + pStartDateAsTm->tm_mday;
					pStartDateAsTm->tm_year -- ; 
					pStartDateAsTm->tm_mon = 11; 
					break;

				case 2:
					if (  LeapYear( pStartDateAsTm->tm_year))
						pStartDateAsTm->tm_mday = 29 + pStartDateAsTm->tm_mday;
					else
						pStartDateAsTm->tm_mday = 28 + pStartDateAsTm->tm_mday;
					pStartDateAsTm->tm_mon -- ; 
					break;

				default:
					break;
			}//Start of case			
		
		}//Start of x-rossin over to next month
		
		//we need to covert Start Time to time_t
		StartTime=mktime(pStartDateAsTm);				
		
	}//end of no params passer


	if (!pTestApp)
	{
		pTestApp	= new clsEndOfAuctionNoticeAppOld();
	}

	pTestApp->InitShell();
	pTestApp->Run(StartTime, EndTime);

	return 0;
}
