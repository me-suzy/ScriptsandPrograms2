/*	$Id: clsFixRelistApp.cpp,v 1.7 1999/05/19 02:34:02 josh Exp $	*/
//
//	File:	clsFixRelistApp.cpp
//
//	Class:	clsFixRelistApp
//
//	Author:	Tini Widjojo (tini@ebay.com)
//
//	Function:
//  A quick hack batch job to fix relist problems
//
// Modifications:
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsFixRelistApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsItems.h"
#include "clsItem.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsUtilities.h"
#include "clsAccount.h"

#include "vector.h"
#include "hash_map.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>

#ifdef _MSC_VER
#include <strstrea.h>
#else
#include <strstream.h>
#endif

clsFixRelistApp::clsFixRelistApp()
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	mpItems			= (clsItems *)0;
	return;
}


clsFixRelistApp::~clsFixRelistApp()
{
	return;
};

static const char *FixRelistMemo	=
	"Accounting adjustment: insertion fee for %d";

static const char *RelistUncreditMemo =
	"Accounting adjustment: reverse credit for %d";

void clsFixRelistApp::Run()
{
	// vector of affected users
	vector<unsigned int>			vUsers;
	vector<unsigned int>::iterator	i;

	// vector of affected account details
	AccountDetailVector				vAccount;
	AccountDetailVector::iterator	ii;

	// This is used for the fully-filled out item
	clsItem					*pItem;
	clsAccountDetail		*pAccountDetail;

	char					*pMemo;
	char					*pUncreditMemo;

	double					insertionFee;
	double					price;
	// cost basis for calculating listing fees.
	double					costprice;
	clsUser					*pUser;
	clsAccount				*pAccount;
	double					totalCharge;

	// This is the 
	// File stuff
	FILE			*pRelistLog;

	// File shenanigans
	pRelistLog	= fopen("Relistlog.txt", "a");

	if (!pRelistLog)
	{
		fprintf(stderr,"%s:%d Unable to open relist log. \n",
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
	mpUsers->GetUsersWithBadAccounts(&vUsers);

	// Show how many there are
	fprintf(pRelistLog, "%d Users to fix.\n", vUsers.size());

	// Now, we loop through them
	for (i = vUsers.begin();
		 i != vUsers.end();
		 i++)
	{
		// Get the affected user and his/her account detail record
		pUser	= mpUsers->GetUser((*i));
		if (!pUser)
		{
			printf("** Error ** Can not get user %d\n", (*i));
			continue;
		}

		pAccount	= pUser->GetAccount();

		if (!pAccount)
		{
			printf("** Error ** Could not get account for %s (%d)\n",
					pUser->GetUserId(),
					pUser->GetId());
			continue;
		}
	
		// Let's get all their account records. 
		pAccount->GetBadAccountDetail(&vAccount);
		totalCharge = 0;

		fprintf(pRelistLog,
				"User %s has %d detail records.\n", pUser->GetUserId(), vAccount.size());

		for (ii = vAccount.begin();
			 ii != vAccount.end();
			 ii++)
		{
			// double charge users for relist credit > 0 
			if ((*ii)->mAmount > 0)
			{
				// Ok, let's make an naked account detail
				pAccountDetail	= new clsAccountDetail;

				pMemo	= new char[strlen(FixRelistMemo) + 32];
				pUncreditMemo = new char[strlen(RelistUncreditMemo) + 32];

				sprintf(pMemo, FixRelistMemo, (*ii)->mItemId);
				sprintf(pUncreditMemo, RelistUncreditMemo, (*ii)->mItemId);

				// Fill it in with what we know
				pAccountDetail->mTime		= (*ii)->mTime + 1;
				pAccountDetail->mType		= AccountDetailMemo; // (*ii)->mType;
				pAccountDetail->mpMemo		= pUncreditMemo;
				pAccountDetail->mAmount		= -(*ii)->mAmount;
				pAccountDetail->mBatchId	= 828;

				// add a neutralizing account detail
				pAccount->AddRawAccountDetail(pAccountDetail,828);

				// another one as a charge
				pAccountDetail->mpMemo = pMemo;
				pAccount->AddRawAccountDetail(pAccountDetail,828);

				totalCharge = totalCharge + (*ii)->mAmount + (*ii)->mAmount;

				delete pAccountDetail;
				delete	[] pMemo;
				delete  [] pUncreditMemo;
			}  // end of fixing credit relisting fee

			else if ((*ii)->mAmount == 0)
			{
				// charge the user for 0 value relist
				// get the item
				pItem	= mpItems->GetItem((*ii)->mItemId);

				/* if not null, then do the rest */
				if (!pItem)
				{
					printf("** Error ** Could not get item %s \n",
						pItem->GetId());
					continue;
				}

				// Get the insertion fee
				price	= pItem->GetReservePrice();
				costprice = pItem->GetStartPrice();

				// price = max (price, costprice) for billing purposes
				if (price == 0)
					price = costprice;
				else
				{
					// check if reserve price > start price
					if (costprice > price)
						price = costprice;
				}

				// In theory, there ARE no Dutch reserve auctions, but if
				// on slips in...For non-dutch auctions, however, the 
				// quantity is always 1, so this does nothing.
				price	= price * pItem->GetQuantity();

				insertionFee = pItem->GetInsertionFee(price);

				// add new account detail
				pAccountDetail	= new clsAccountDetail;
				pMemo	= new char[strlen(FixRelistMemo) + 32];
				sprintf(pMemo, FixRelistMemo, pItem->GetId());

				pAccountDetail->mTime		= (*ii)->mTime + 1;
				pAccountDetail->mType		= AccountDetailMemo; // (*ii)->mType;
				pAccountDetail->mpMemo		= pMemo;
				pAccountDetail->mAmount		= -insertionFee;
				pAccountDetail->mBatchId	= 828;
				// add a neutralizing account detail
				pAccount->AddRawAccountDetail(pAccountDetail,828);

				totalCharge = totalCharge + insertionFee;

				delete pAccountDetail;
				delete	[] pMemo;

			}  // done charging user
			delete (*ii);
		}

		// rebalance user's acct
		pAccount->Rebalance();

		fprintf(pRelistLog,
				"User %s charged $%4.2f\n", pUser->GetUserId(), totalCharge);

		// clean up the vector of accounts
		vAccount.erase(vAccount.begin(), vAccount.end());

		delete pAccount;
		delete pUser;
	}

	vUsers.erase(vUsers.begin(), vUsers.end());

}


static clsFixRelistApp *pTestApp = NULL;

int main()
{
#ifdef _MSC_VER
	g_tlsindex = 0;
#endif

	if (!pTestApp)
	{
		pTestApp	= new clsFixRelistApp();
	}

	pTestApp->InitShell();
	pTestApp->Run();

	return 0;
}
