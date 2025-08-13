/*	$Id: clsBetaCreditsApp.cpp,v 1.2.388.1 1999/08/06 02:26:51 nsacco Exp $	*/
//
//	File:	clsBetaCreditsApp.cpp
//
//	Class:	clsBetaCreditsApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	*** DOES DO BETA CREDITS ANY MORE ***
//	Fixes some line itmes which were in the "wrong"
//	month.
//
// Modifications:
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsBetaCreditsApp.h"
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


clsBetaCreditsApp::clsBetaCreditsApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	mpItems			= (clsItems *)0;
	return;
}


clsBetaCreditsApp::~clsBetaCreditsApp()
{
	return;
};


void clsBetaCreditsApp::Run()
{
	// This is the vector of userids who've got
	// beta credits coming
	vector<unsigned int>			vUsers;
	vector<unsigned int>::iterator	i;

	clsUser							*pUser;
	clsAccount						*pAccount;
	clsItem							*pItem;

	AccountDetailVector				vAccount;
	AccountDetailVector::iterator	ii;

	struct tm						bTimeTM;
	time_t							bTime;
	struct tm						eTimeTM;
	time_t							eTime;

	int								updateCount	= 0;

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

	// Set up times
	memset(&bTimeTM, 0x00, sizeof(bTimeTM));
	bTimeTM.tm_sec			= 00;
	bTimeTM.tm_min			= 00;
	bTimeTM.tm_hour			= 00;
	bTimeTM.tm_mon			= 8;	// Month - 1!!!
	bTimeTM.tm_mday			= 3;
	bTimeTM.tm_year			= 97;
	bTimeTM.tm_isdst		= 1;
	bTime	= mktime(&bTimeTM);

	memset(&eTimeTM, 0x00, sizeof(eTimeTM));
	eTimeTM.tm_sec			= 59;
	eTimeTM.tm_min			= 59;
	eTimeTM.tm_hour			= 23;
	eTimeTM.tm_mon			= 8;	// Month - 1!!!
	eTimeTM.tm_mday			= 3;
	eTimeTM.tm_year			= 97;
	eTimeTM.tm_isdst		= 1;
	eTime	= mktime(&eTimeTM);


	// First, let's get the items
	gApp->GetDatabase()->GetUsersWithAccounts(&vUsers);

	// Now, we loop through them
	for (i = vUsers.begin();
		 i != vUsers.end();
		 i++)
	{
		// Get the user
		pUser	= mpUsers->GetUser((*i));
		if (!pUser)
		{
			fprintf(stderr, "** Error ** Could not get user %d\n",
					(*i));
			continue;
		}
		pAccount	= pUser->GetAccount();
		pAccount->GetAccountDetail(&vAccount);

		printf("*** Begin User %s\n", pUser->GetUserId());

		for (ii = vAccount.begin();
			 ii != vAccount.end();
			 ii++)
		{
			if ((*ii)->mType == AccountDetailFeeFinalValue)
			{
				if ((*ii)->mTime < bTime ||
					(*ii)->mTime > eTime)
				{
					continue;
				}

				if ((*ii)->mItemId == 0)
					continue;

				if ((*ii)->mItemId != 0)
				{
					pItem	= mpItems->GetItem((*ii)->mItemId);
					if (!pItem)
					{
						fprintf(stderr, "** Error Item %d not found for %s\n",
								(*ii)->mItemId,
								pUser->GetUserId());
						continue;
					}
					if ((*ii)->mTime == (pItem->GetEndTime() + 1))
						continue;

					// Lets' replace the transaction!
					(*ii)->mTime	= (pItem->GetEndTime() + 1);

					gApp->GetDatabase()->UpdateAccountDetailTime(pUser->GetId(),
																// nsacco 08/05/99
																// needs table indicator
															    (*ii));

					printf("User %s (%d), Item %d Updated\n",
						   pUser->GetUserId(),
						   pUser->GetId(),
						   pItem->GetId());
					updateCount++;

				}

				delete pItem;
			}
		}

		for (ii = vAccount.begin();
			  ii != vAccount.end();
			  ii++)
		{
			delete (*ii);
		}

		vAccount.erase(vAccount.begin(), vAccount.end());

		delete	pAccount;
		delete	pUser;
	}

	printf("** Done! %d Updates!\n", updateCount);

}

static clsBetaCreditsApp *pTestApp = NULL;

int main()
{

	if (!pTestApp)
	{
		pTestApp	= new clsBetaCreditsApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run();

	return 0;
}
