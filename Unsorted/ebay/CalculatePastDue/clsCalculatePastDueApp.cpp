/*	$Id: clsCalculatePastDueApp.cpp,v 1.2 1999/02/21 02:21:20 josh Exp $	*/
//
//	File:	clsCalculatePastDueApp.cpp
//
//	Class:	clsCalculatePastDueApp
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
#include "clsCalculatePastDueApp.h"
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

// 
// This little struct helps us keep track of past dues by
// an arbitrary number of MONTHS. 
//
typedef struct
{
	int			mBillingPeriodsBack;
	time_t		mBegins;
	time_t		mEnds;
	double		mBalance;
} PastDueOverTime;


//
// Sort routine
//
static bool sort_account_detail_time(clsAccountDetail *pA, clsAccountDetail *pB)
{
	if (pA->mTime < pB->mTime)
		return true;

	return false;
}


clsCalculatePastDueApp::clsCalculatePastDueApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	mpItems			= (clsItems *)0;
	return;
}


clsCalculatePastDueApp::~clsCalculatePastDueApp()
{
	return;
};


void clsCalculatePastDueApp::Run(char *pUserId)
{
	// This tells us whether or not to recalculate ALL
	// past due, or just people with existing past due
	// and those who have NOT had past due calculated.
	//
	// Typically, this is only set to "true" just before
	// the monthly invoicing run
	//
	bool							calculateAll	= true;

	//	This is the PastDueOverTime array. 
	PastDueOverTime					pastDue[7] =
	{
		{	25,	0,	0,	0	},
		{	5,	0,	0,	0	},
		{	4,	0,	0,	0	},
		{	3,	0,	0,	0	},
		{	2,	0,	0,	0	},
		{	1,	0,	0,	0	},
		{	0,	0,	0,	0	}
	};

	int								pastDueSize	=7;
	int								pastDueCount;
	int								pastDueCount2;
	PastDueOverTime					*pPastDue;
	PastDueOverTime					*pPastDue2;
	struct tm						nowTimeTM;
	time_t							nowTime;
	struct tm						thenTimeTM;
	time_t							thenTime;

	const struct tm					*pTheTime;
	char							theTime[32];

	// This is the vector of userids who've got accounts
	vector<unsigned int>						vUsers;
	vector<unsigned int>::iterator				i;
	int											userCount;
	int											processedUserCount;

	clsUser							*pUser;
	clsUser							*pPassedUser;
	clsAccount						*pAccount;

	AccountDetailVector				vAccount;
	AccountDetailVector::iterator	ii;

	int								iii;

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


	// All righty. First, let's do some calculations to figure
	// out "when" 90, 60, and 30 days ago were.
	memset(&nowTimeTM, 0x00, sizeof(nowTimeTM));
	nowTimeTM.tm_sec			= 0;
	nowTimeTM.tm_min			= 0;
	nowTimeTM.tm_hour			= 0;
	nowTimeTM.tm_mon			= 00;
	nowTimeTM.tm_mday			= 1;
	nowTimeTM.tm_year			= 98;
	nowTimeTM.tm_isdst			= -1;
	nowTime						= mktime(&nowTimeTM);
	// nowTime		= time(0);

	// Populate the table...
	for (pastDueCount = 0,
		 pPastDue	= &pastDue[0];
		 pastDueCount < pastDueSize;
		 pastDueCount++,
		 pPastDue++)
	{
		memcpy(&thenTimeTM, &nowTimeTM, sizeof(thenTimeTM));
		for (iii = 0;
			 iii < pPastDue->mBillingPeriodsBack;
			 iii++)
		{
			// If the month is 0, then we're about to go back
			// a year, and we need to decrement the year and
			// reset the month
			if (thenTimeTM.tm_mon == 0)
			{
				thenTimeTM.tm_year--;
				thenTimeTM.tm_mon	= 11;
			}
			else
				thenTimeTM.tm_mon--;
		}
		thenTime			= mktime(&thenTimeTM);
		pPastDue->mBegins	= thenTime;
	}
		 
	for (pastDueCount = 0,
		 pPastDue	= &pastDue[0];
		 pastDueCount < pastDueSize - 1;
		 pastDueCount++,
		 pPastDue++)
	{
		pPastDue->mEnds	= (pPastDue + 1)->mBegins - 1;
	}	
	pastDue[pastDueSize - 1].mEnds = INT_MAX; 

	for (pastDueCount = 0,
		 pPastDue	= &pastDue[0];
		 pastDueCount < pastDueSize;
		 pastDueCount++,
		 pPastDue++)
	{
		pTheTime	= localtime(&pPastDue->mBegins);
		strftime(theTime, sizeof(theTime), "%m/%d/%y %H:%M:%S",
				   pTheTime);
		printf("DaysBack %d, %s-", pPastDue->mBillingPeriodsBack, theTime);
		pTheTime	= localtime(&pPastDue->mEnds);
		if (pTheTime > 0)
			strftime(theTime, sizeof(theTime), "%m/%d/%y %H:%M:%S",
					   pTheTime);
		else
			printf("0x%x", pPastDue->mEnds);

		printf("%s\n", theTime);
	}	


	// If we were passed a user, then we just do that one,
	// otherwise we do ALL the users with accounts
	if (pUserId)
	{
		pPassedUser	= mpUsers->GetUser(pUserId);
		if (!pPassedUser)
		{
			printf("** Error %s could not be found\n",
				   pUserId);
			return;
		}

		vUsers.push_back(pPassedUser->GetId());

		delete	pPassedUser;
		pPassedUser	= NULL;
	}
	else
	{
		// Ok, let's get all the users who have accounts...
		mpUsers->GetUsersWithAccounts(&vUsers);
		userCount				= vUsers.size();
		processedUserCount		= 0;
	}

	printf("*** %d Users to Process ***\n",
			 userCount);

	// Now, we loop through them
	for (i = vUsers.begin();
		 i != vUsers.end();
		 i++)
	{
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

		//
		// If we're not recalculating ALL past due, then we'll only
		// do this user IF they have existing past due, OR if we've
		// never calculated past due for them
		//
		if (!calculateAll)
		{
			if (pAccount->GetPastDueBase() != (time_t) 0 &&
				 pAccount->GetPastDue30Days() == 0)
			{
				printf("%d of %d %s Skipped\n",
						 processedUserCount,
						 userCount,
						 pUser->GetUserId());
				processedUserCount++;
				delete	pAccount;
				delete	pUser;
				continue;
			}
		}

		// Let's get all their account records. 
		pAccount->GetAccountDetail(&vAccount);

      // If no entries, no past due!
      if (vAccount.size() == 0)
      {
         delete   pAccount;
         delete   pUser;
         continue;
      }

		// and sort them...
		sort(vAccount.begin(), vAccount.end(), sort_account_detail_time);

      //
      // Clear old past due counts
      //
      for (pastDueCount = 0,
          pPastDue   = &pastDue[0];
          pastDueCount < pastDueSize;
          pastDueCount++,
          pPastDue++)
      {
         pPastDue->mBalance   = 0;
      }

		//
		// Loops through the records, which are sorted by date now 
		// (earliest first).
		//
		pPastDue		= &pastDue[0];
		pastDueCount	= 0;

		for (ii = vAccount.begin();
			 ii != vAccount.end();
			 ii++)
		{
			// First, compare the time of this entry to the 
			// "time" in the NEXT pastDue entry. If it's
			// later, we need to advance....Remember that
			// the mWhen represents the earliest day in the
			// period. For example, the period for debits 
			// accrued from 90 - 119 days agao, is specified
			// as 90 days ago.
			if ((*ii)->mTime > pPastDue->mEnds)
			{
				// Yes, advance
				for (pastDueCount = pastDueCount + 1,
					 pPastDue = pPastDue + 1;
					 pastDueCount < pastDueSize;
					 pastDueCount++,
					 pPastDue++)
				{
					// printf("Advancing...\n");

					// Carry balance forward
					if (pastDueCount > 0)
					{
						pPastDue->mBalance	= 
							(pPastDue - 1)->mBalance;
					}

					// Let's see if we're in the right period
					// yet.
					if ((*ii)->mTime <= pPastDue->mEnds)
						break;
				}
			}
			pTheTime = localtime(&(*ii)->mTime);
 			strftime(theTime, sizeof(theTime), "%m/%d/%y %H:%M:%S",
            		pTheTime);

			//printf("%s\t%d\t%8.2f\t%8.2f\n", theTime, (*ii)->mType,
			//        (*ii)->mAmount, pPastDue->mBalance);

			// New debts apply to only the current period
			if ((*ii)->mAmount < 0)
			{
				pPastDue->mBalance	+=	(*ii)->mAmount;
				continue;
			}

			// As long as there's a debit balance in a prior 
			// period, we apply credits to the prior month(s)
			for (pastDueCount2 = 0,
				 pPastDue2 = &pastDue[0];
				 pastDueCount2 < pastDueCount;
				 pastDueCount2++,
				 pPastDue2++)
			{
				//printf("c %d, c2 %d, p 0x%x, p2 0x%x\n",
				//		 pastDueCount, pastDueCount2,
				//		 pPastDue, pPastDue2);
				if (pPastDue2->mBalance < 0)
				{
					pPastDue2->mBalance += (*ii)->mAmount;
				}

				// If the last addition wiped out the last
				// period's balance, just zero it.
				if (pPastDue2->mBalance > 0)
					pPastDue2->mBalance = 0;

			}

			// Credits always apply to the current period
			pPastDue->mBalance += (*ii)->mAmount;
		}

		// 
		// We're done. Let's make sure any balances are 
		// propagated into the future (handles the case where
		// the user's last activity was some time ago...)
		//
		if (pastDueCount < pastDueSize - 1)
		{
			for (;
				 pastDueCount < pastDueSize - 2;
				 pastDueCount++,
				 pPastDue++)
			{
				(pPastDue + 1)->mBalance = pPastDue->mBalance;
			}
		}

      //
      // Any past due amounts over $0 are really $0.
      //
      for (pastDueCount = 0,
          pPastDue   = &pastDue[0];
          pastDueCount < pastDueSize;
          pastDueCount++,
          pPastDue++)
      {
         if (pPastDue->mBalance > 0)
				pPastDue->mBalance   = 0;
      }

		// Now we know all. update.
		printf("%d of %d %s %d:%8.2f %d:%8.2f %d:%8.2f %d:%8.2f %d:%8.2f\n",
				processedUserCount,
				userCount,
			   pUser->GetUserId(),
			   pastDue[2].mBillingPeriodsBack,
			   (float)pastDue[2].mBalance,
			   pastDue[3].mBillingPeriodsBack,
			   (float)pastDue[3].mBalance,
			   pastDue[4].mBillingPeriodsBack,
			   (float)pastDue[4].mBalance,
			   pastDue[5].mBillingPeriodsBack,
			   (float)pastDue[5].mBalance,
			   pastDue[46].mBillingPeriodsBack,
			   (float)pastDue[6].mBalance);

		pAccount->SetPastDue(nowTime,
								pastDue[4].mBalance,
								pastDue[3].mBalance,
								pastDue[2].mBalance,
								pastDue[1].mBalance,
								pastDue[0].mBalance);
		// Clean up....
		for (ii = vAccount.begin();
			  ii != vAccount.end();
			  ii++)
		{
			delete (*ii);
		}

		vAccount.erase(vAccount.begin(), vAccount.end());


		delete	pAccount;
		delete	pUser;

		processedUserCount++;

	}

}

static clsCalculatePastDueApp *pTestApp = NULL;

int main(int argc, char* argv[])
{
	char	*pUserId;

	if (argc > 0)
		pUserId	= argv[1];
	else
		pUserId	= NULL;

	if (!pTestApp)
	{
		pTestApp	= new clsCalculatePastDueApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run(pUserId);

	return 0;
}
