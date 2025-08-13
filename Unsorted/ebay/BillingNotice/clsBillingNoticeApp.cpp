/*	$Id: clsBillingNoticeApp.cpp,v 1.6 1999/02/21 02:21:13 josh Exp $	*/
//
//	File:	clsBillingNoticeApp.cpp
//
//	Class:	clsBillingNoticeApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsBillingNoticeApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsItems.h"
#include "clsItem.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsAccount.h"

#include "vector.h"
#include "hash_map.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>




clsBillingNoticeApp::clsBillingNoticeApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	mpItems			= (clsItems *)0;
	mpStream			= NULL;
	return;
}


clsBillingNoticeApp::~clsBillingNoticeApp()
{
	return;
};

//
// BillingFileName
//
//	*** NOTE ***
//	Should this be mpMarketPlace->GetDailyStatusFileName()?
//	*** NOTE ***
//
static const char *BillingFileName	= "billinginfo.txt";
void clsBillingNoticeApp::EmitUserStatus(int itemId, 
										 FILE			*pWackoItemsLog)
{
	clsUser					*pUser;
	clsItem					*pWholeItem;
	clsAccount				*pAccount;

	BidVector				vBidders;
	BidVector::iterator	iBidder;
	clsBid					*pBid;
	int						qtysold;
	long					billtime;
	//	time_t		relistStartTime;
	//	struct tm	timeAsRelistStart  = { 0, 0, 23, 5, 6, 98,0,0, -1 };
	//	relistStartTime = mktime(&timeAsRelistStart);


	// First, let's see if the file's open
	if (!mpStream)
	{
		mpStream	= new ofstream(BillingFileName);
		if (!mpStream->is_open())
		{
			fprintf(stderr, "** Error ** Could NOT open %s\n",
					BillingFileName);
			fprintf(stderr, "** Error ** Error was %d/%s\n",
					errno, strerror(errno));
			exit(0);
		}
	}

	// First, we need the item
	pWholeItem	= mpItems->GetItem(itemId);
	if (!pWholeItem)
	{
		fprintf(stderr,
				  "*Error* Cannot find item %d\n",
				  itemId);
		return;
	}

	// next, check if it has been billed before
	billtime = pWholeItem->GetDBBillTime();

	if (billtime == 0)
	{ // need to bill; item not billed before

		if (pWholeItem->GetHighBidder() == 0)
		{
			pWholeItem->SetBillTime(time(0));
			delete	pWholeItem;
			return;
		}

		if (pWholeItem->GetReservePrice() != 0 &&
			pWholeItem->GetPrice() < pWholeItem->GetReservePrice())
		{
			pWholeItem->SetBillTime(time(0));
			delete	pWholeItem;
			return;
		}

		if (pWholeItem->ChargeNoFinalValueFee())
		{
			pWholeItem->SetBillTime(time(0));
			delete	pWholeItem;
			return;
		}

		// And now the user
		pUser	= mpUsers->GetUser(pWholeItem->GetSeller());

		if (!pUser)
		{
			fprintf(stderr, "*Error* Cannot find user %d for item %d\n",
					  pWholeItem->GetSeller(), itemId);
			return;
		}

		// We'll need their account too
		pAccount	= pUser->GetAccount();

		// For Dutch Auctions, get quantity sold
		if (pWholeItem->GetAuctionType() == AuctionDutch &&
			pWholeItem->GetBidCount() > 0 &&
			pWholeItem->GetPrice() > 0)
		{
			// dutch auction, emit dutch high bidder to *mpStreamDutch
			pWholeItem->GetDutchHighBidders(&vBidders);
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
		if (qtysold > pWholeItem->GetQuantity())
			qtysold	= pWholeItem->GetQuantity();

		// Emit Seller things...
		// Let's charge the user for the listing
		if (pWholeItem->GetAuctionType() == AuctionDutch)
			pAccount->ChargeListingFee(pWholeItem, qtysold);
			//inna - added wacko processing 
			//apply Wacko Filter
			if ((pWholeItem->GetPrice() * qtysold) >= 25000)
			{
				//add to wako report
				//fprintf(pEndAuctionLog, "** Item %d is wacko\n", pWholeItem->GetId());
				fprintf(pWackoItemsLog, "Item %d\tDutch Auction\n", pWholeItem->GetId());
				//set wacko flag
				pWholeItem->SetItemWackoFlag(true);
			}
		else
		{
			pAccount->ChargeListingFee(pWholeItem);
			//inna - added wacko processing 
			//apply Wacko Filter
			if (pWholeItem->GetPrice() >= 10000)
			{
				//add to wako report
				//fprintf(pEndAuctionLog, "** Item %d is wacko\n", pWholeItem->GetId());
				fprintf(pWackoItemsLog, "Item %d\tChineese Auction\n", pWholeItem->GetId());
				//set wacko flag
				pWholeItem->SetItemWackoFlag(true);
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
			pTimeTm->tm_mday = 31;
			pTimeTm->tm_mon = 11;
			pTimeTm->tm_year=98;
			ThirtyFirst = mktime(pTimeTm);
			if (pWholeItem->GetStartTime() > 899708400)
				if ((pWholeItem->GetStartTime() < Nineth) && 
					(pWholeItem->GetStartTime() > ThirtyFirst))
			{
				{
					// if it is a relisting item, refund the insertion fee
					if (pWholeItem->GetPassword() & ItemRelisting)
						pAccount->ApplyInsertionFeeCredit(pWholeItem,
									NULL);
				}
			}
		}

		// Show we've billed them
		pWholeItem->SetBillTime(time(0));

		// And emit the stuff we need to notify them
		*mpStream <<	"---SELLER\t"
				  <<	pUser->GetUserId()
				  <<	"\t"
				  <<	pWholeItem->GetId()
				  <<	"\t"
				  <<	pWholeItem->GetTitle()
				  <<	"\t"
				  <<	pWholeItem->GetEndTime()
				  <<	"\t"
				  <<	pWholeItem->GetBidCount()
				  <<	"\t"
				  <<	pWholeItem->GetStartPrice()
				  <<	"\t"
				  <<	pWholeItem->GetPrice()
				  <<	"\t"
				  <<	pWholeItem->GetQuantity()
				  <<	"\t"
				  <<	qtysold
				  <<	"\n";

		delete	pUser;
		delete	pAccount;
	}

	// else, already billed
	delete	pWholeItem;
	return;

}


void clsBillingNoticeApp::Run()
{
	// This is the great mother vector of items
	vector<int>					vItems;

	// And it's iterator
	vector<int>::iterator	i;

	struct tm*	pTime;

	//wako report stuff
	FILE			*pWackoItemsLog;
	char			fwacko[25];

	time_t noticeTime = time(0);
	pTime = localtime(&noticeTime);

	//lest get name and open wacko report
	sprintf(fwacko, "wackoitems%04d%02d%02d.txt", 
			pTime->tm_year + 1900,
			pTime->tm_mon+1, 
			pTime->tm_mday);

	pWackoItemsLog	= fopen(fwacko, "a");

	if (!pWackoItemsLog)
	{
		fprintf(stderr,"%s:%d Unable to open wacko items log. \n",
			  __FILE__, __LINE__);
	}

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


	// First, let's get the items
	mpItems->GetItemsNotBilled(&vItems);

	printf("*** %d Items to bill ***\n", vItems.size());

	// Now, we loop through them
	for (i = vItems.begin();
		 i != vItems.end();
		 i++)
	{
		EmitUserStatus((*i), pWackoItemsLog);
	}
}

static clsBillingNoticeApp *pTestApp = NULL;

int main()
{
	#ifdef _MSC_VER
		g_tlsindex = 0;
#endif

	if (!pTestApp)
	{
		pTestApp	= new clsBillingNoticeApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run();

	return 0;
}
