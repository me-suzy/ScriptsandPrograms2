/*	$Id: clsComputePastARApp.cpp,v 1.2 1999/02/21 02:21:34 josh Exp $	*/
//
//	File:	clsComputePastARApp.cpp
//
//	Class:	clsComputePastARApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	Makes invoices. Duh.
//
//	In this "first run" incarnation, we have to use the "past due"
//	calculation to compute the finance charge (if any). In the later
//	versions, we'll scan the users account records for the last
//	"invoiced" record, and use that. 
//

//
// Modifications:
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsComputePastARApp.h"
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
// Sort routine
//
static bool sort_account_detail_time(clsAccountDetail *pA, clsAccountDetail *pB)
{
	if (pA->mTime < pB->mTime)
		return true;

	return false;
}


clsComputePastARApp::clsComputePastARApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	mpItems			= (clsItems *)0;
	return;
}


clsComputePastARApp::~clsComputePastARApp()
{
	return;
};


void clsComputePastARApp::Run()
{
	time_t							baseTime;
	const struct tm					*pBaseTimeAsTM;
	struct tm						baseTimeAsTM;

	time_t							baseMinus1Time;
	time_t							baseMinus2Time;
	time_t							baseMinus3Time;

	float							baseBalance			= 0;
	float							baseMinus1Balance	= 0;
	float							baseMinus2Balance	= 0;
	float							baseMinus3Balance	= 0;

	// This is the vector of userids who've got accounts
	vector<unsigned int>			vUsers;
	vector<unsigned int>::iterator	i;
	int								userCount	= 0;

	clsUser							*pUser;
	clsAccount						*pAccount;

	AccountDetailVector				vAccount;
	AccountDetailVector::iterator	ii;

	bool							error;


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

	// First, let's set our own baselines.

	//
	// Get the current time, and round it to the beginning
	// of the month.
	//
	baseTime							= time(0);
	pBaseTimeAsTM						= localtime(&baseTime);
	memcpy(&baseTimeAsTM, pBaseTimeAsTM, sizeof(baseTimeAsTM));

	baseTimeAsTM.tm_sec					= 0;
	baseTimeAsTM.tm_min					= 0;
	baseTimeAsTM.tm_hour				= 0;
	baseTimeAsTM.tm_mday				= 1;
	baseTimeAsTM.tm_isdst				= -1;
	baseTime							= mktime(&baseTimeAsTM);


	baseTimeAsTM.tm_mon					= baseTimeAsTM.tm_mon - 1;
	baseMinus1Time						= mktime(&baseTimeAsTM);

	baseTimeAsTM.tm_mon					= baseTimeAsTM.tm_mon - 1;
	baseMinus2Time						= mktime(&baseTimeAsTM);

	baseTimeAsTM.tm_mon					= baseTimeAsTM.tm_mon - 1;
	baseMinus3Time						= mktime(&baseTimeAsTM);




	// Ok, let's get all the users who have accounts...
	mpUsers->GetUsersWithAccounts(&vUsers);

	printf("*** %d users to check for invoicing!\n",
			 vUsers.size());

	// Now, we loop through them
	for (i = vUsers.begin();
		 i != vUsers.end();
		 i++)
	{
		userCount++;

		if (userCount % 1000 == 0)
		{
			printf("%d users...\n", userCount);
		}

		error			= false;

		
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
		pAccount->GetAccountDetail(&vAccount);

		// If there are no detail records, we shouldn't be invoicing them
		if (vAccount.size() < 1)
		{
			delete	pAccount;
			delete	pUser;
			continue;
		}


		// and sort them...
		sort(vAccount.begin(), vAccount.end(), sort_account_detail_time);



		// 
		// ***********
		//	The GOODS
		// ***********
		//
		//	Here, we actually compose the invoice for mailing
		//	to the user.
		//

		//
		// First, pass over the account once to accumulate a current amount
		// due
		//
		for (ii = vAccount.begin();
			 ii != vAccount.end();
			 ii++)
		{
			if ((*ii)->mTime <= baseTime)
				baseBalance	+= (*ii)->mAmount;

			if ((*ii)->mTime <= baseMinus1Time)
				baseMinus1Balance	+= (*ii)->mAmount;

			if ((*ii)->mTime <= baseMinus2Time)
				baseMinus2Balance	+= (*ii)->mAmount;

			if ((*ii)->mTime <= baseMinus3Time)
				baseMinus3Balance	+= (*ii)->mAmount;
		}


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


	}

	printf("*********\n");
	printf("Base $%8.2f\n", baseBalance);
	printf("Base-1 $%8.2f\n", baseMinus1Balance);
	printf("Base-2 $%8.2f\n", baseMinus2Balance);
	printf("Base-3 $%8.2f\n", baseMinus3Balance);
	printf("*********\n");


}

static clsComputePastARApp *pTestApp = NULL;

int main()
{

	if (!pTestApp)
	{
		pTestApp	= new clsComputePastARApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run();

	return 0;
}
